

function getSitef(host, ajaxLib) {

	var ajax;
	if (ajaxLib.Axios) {
		ajax = ajaxLib
    }
	if (ajaxLib.prototype.jquery) {
		ajax = {
			jq:ajaxLib,
			async get(url, data) {
				const rs = await this.jq.get(url, data);
				return {data:rs}
			},
			async post(url, data) {
				const rs = await this.jq.ajax({
					'type': 'POST',
					'url': url,
					'contentType': 'application/json',
					'data': JSON.stringify(data),
					'dataType': 'json'
				})		
				return {data:rs}
			}
		}
	}

	let sitef_data = {}
		
	function clearData() {
		Object.assign(sitef_data, {
			cancelarTransaccion:false,
			responseValues: {} //fieldtypes
		})
	}

	const events = {
		confirm: function (data) {
			console.log("confirm", data)
		},
		prompt: function (data) {
			console.log("prompt", data)
		},
		alert: function (data) {
			console.log('timeout',data)
		},
		select: function (data) {
			console.log('select', data)
        },
		statusMessage: function (data) {
			console.log("statusMessage", data)
		},
		transactionCanceled: function (data) {
			console.log("transaccionCanceled", data)
		},
		transactionCompleted: function (data) {
			console.log("transactionCompleted", data);
		},
		balanceCompleted: function (data) {
			console.log("balanceCompleted", data);
		},
		transactionError: function (data) {
			console.log("transactionError", data)
		}

	}
	function on(evt, fn) {
		events[evt] = fn;
	}
	function emit(evt, data, resolver) {
		events[evt](data,resolver);
	}

	async function verificarPinPad() {

		try {

			res = await ajax.get(`${host}/api/verificarPinPad`);
			ret = res.data;
			//
			emit("statusMessage", { ret, status: ret == 1 ? 'PinPad Ok' : 'PinPad No Ok' })
			return ret
	    

		} catch(err) {

		//
		emit("statusMessage", { err, data: 'Error' })
		return err
		  

		}

		
	}

	async function iniciarTransaccion(params) {
		if (params.invoiceDate == '') {
			const dt = new Date().toISOString().split('-')
			params.invoiceDate = dt[0] + dt[1] + dt[2].substr(0, 2)
		}

		clearData();
		sitef_data.params = params;

		res = await ajax.post(`${host}/api/iniciarTransaccion`, params)

		return res.data
	}
	async function menu({ saleInvoice, value, operator }) {
		rs = await iniciarTransaccion({ sitefFunction: 110, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmación del pos ?
		}
		return rs
	}
	async function cancelarTransaccion() {
		//return ajax.get(`${host}/api/cancelarTransaccion`).then(() => finalizarTransaccion({ confirmationFlag:0 }));
		sitef_data.cancelarTransaccion = true;
	}

	async function enviarMensajesPendientes() {
		return await ajax.post(`${host}/api/enviarMensajesPendientes`);
	}

	async function cantidadTransaccionesPendientes(invoiceNumber, invoiceDate) {
		if (!invoiceDate) {
			const dt = new Date().toISOString().split('-')
			invoiceDate = dt[0] + dt[1] + dt[2].substr(0, 2)
        }
		rs = await ajax.post(`${host}/api/cantidadTransaccionesPendientes`, { dataFiscal: invoiceDate, cupomFiscal: invoiceNumber })
		return rs
    }
	
	async function transaccionesPendientes(p) {
		const params = {factura:'',invoiceDate:'',operador:'',value:0,...p

        }
		const sitefFunction = params.factura ? 131 : 130
		
		if (params.invoiceDate == '') {
			const dt = new Date().toISOString().split('-')
			params.invoiceDate=dt[0]+dt[1]+dt[2].substr(0,2)
        }
		rs = await iniciarTransaccion({ sitefFunction, value:params.value, saleInvoice: params.factura, operatorCode: params.operador, invoiceDate:params.invoiceDate })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		
		return rs.ret
	}

	//finalizarTransaccion({saleInvoice:'002',invoiceDate:'20210709',confirmationFlag:0})
	async function finalizarTransaccion(p) {

		params = {
			"confirmationFlag": 1,// 1 para confirmar
			"saleInvoice": '',
			"invoiceDate": '', //"yyyyMMdd",
			"invoiceTime": '', //"HHmmss",
			"additionalParam": '',
			...p
		}
		if (params.invoiceDate == '') {
			const dt = new Date().toISOString().split('-')
			params.invoiceDate = dt[0] + dt[1] + dt[2].substr(0, 2)
		}

		res = await ajax.post(`${host}/api/finalizarTransaccion`, params)

		return res;

	}
	async function continuarTransaccion() {
		let res = {}
		let params = {
			"command": 0,
			"fieldType": 0,
			"minSize": 0,
			"maxSize": 0,
			"transferBuffer": "",
			"bufferSize": 20000,
			"continuityFlag": 0
		}
		let menuTitle = ""

		do {
			if (sitef_data.cancelarTransaccion) {
				params.continuityFlag = -1;
            }
			const rs = await ajax.post(`${host}/api/continuarTransaccion`, params)
			res = rs.data;

			params = { ...res.continueParams }
			if (params.command != 23) { // sin log para comandos en espera
				console.log('proximo', params.command, params.fieldType, res)
            }
			

			//
			if (res.ret == 10000) {
				
				let responseToSitef = ""

				let proximo = params.command;
				
				// ver comandos en manual página 36
				switch (proximo) {
					case 0: //procesar tipo de campo
						sitef_data.responseValues[params.fieldType] = params.transferBuffer;
						procFieldType(params);
						break;
					case 1: // mensaje para operador
						emit("statusMessage", params.transferBuffer)
						break;
					case 2: // mensaje para usuario
						emit("statusMessage", params.transferBuffer)
						break;
					case 3: // mensaje para operador y usuario
						emit("statusMessage", params.transferBuffer)
						break;
					case 4: // texto para título de menú
						//emit("statusMessage", params.transferBuffer)
						menuTitle = params.transferBuffer;
						break;
					case 11: // debe limpia visor operador
						emit("statusMessage", '...')
						break;
					case 12: // debe limpia visor usuario
						emit("statusMessage", '...')
						break;
					case 13: // remover mensaje en las pantallas
						//console.log('en comando 13 se debe limpiar la pantalla del pinpad. Cómo?? no veo método para ello en la doc ')
						emit("statusMessage", '...')
						break;
					case 14: // limpia título a mostrar en menues
						menuTitle = "";
						break;
					case 15: //Información adicional que debe mostrarse en la pantalla 
						emit("statusMessage", { buffer:params.transferBuffer, res})
						break;
					case 16: //eliminar texto presentado en 15
						emit("statusMessage", { buffer: params.transferBuffer, res })
						break;
					case 20: //espera respuesta Si='0 continúa'/No='1 detiene la secuencia'
						if (params.continuityFlag == -1) { //se está cancelando la transacción
							responseToSitef = '1'
						} else {
							emit("statusMessage", params.transferBuffer);
							var p = new Promise((resolve, reject) => {
								emit('confirm', params.transferBuffer, resolve)
							})

							responseToSitef = await p;
                        }						
						break;
					case 21:
						var items = params.transferBuffer
							.split(";").map((r) => {
								a = r.split(":");
								return { value: a[0], text: a[1] };
							})
						items.pop();
						
						var p = new Promise((resolve, reject) => {
							emit('select', { title:menuTitle, items }, resolve)
						})
						responseToSitef = await p;
						if (responseToSitef == null) {
							params.continuityFlag = -1
                        }
						break;
					case 22: //      
						emit("statusMessage", params.transferBuffer)
						var p = new Promise((resolve, reject) => {
							emit('alert', params.transferBuffer, resolve)
						})
						// Sin respuesta continúa flujo natural
						break;
					case 23:
						//console.log('case 23', 'esperando...')
						break;
					case 29:
						alert('command 29: ' + params.transferBuffer)
						break;
					case 30: // pide cedula		
						emit("statusMessage", params.transferBuffer)
						var p = new Promise((resolve, reject) => {
							emit('prompt', params.transferBuffer, resolve)
						})
						responseToSitef = await p;
						if (responseToSitef == null) {
							params.continuityFlag = -1
						}
						break;
						
					case 34: // pedir datos de moneda ??
						//emit("statusMessage", params.transferBuffer)
						var p = new Promise((resolve, reject) => {
							emit('prompt', params.transferBuffer, resolve)
						})
						responseToSitef = await p;
						break;
					default:
						console.log('res', res)
				}
				params.transferBuffer = responseToSitef;
			}

		} while (res.ret == 10000)

		if (res.ret == 0) {
			emit("transactionCompleted", sitef_data)
		} else {
			if (res.ret == -2) {
				emit("transactionCanceled", res)
			} else {
				emit("transactionError", res);
			}
		}

		return res
	}

	function procFieldType(params) {
		const fieldType = params.fieldType;
		//console.log('*fieldType', params)
		switch (fieldType) {
			//case 10-99: // info  de opciones seleccioandas ??
			case 100: // info de modalidad de pago (pag 34)
			case 101:
			case 102:// metodo de pago 
				break;
			case 105: // fecha y hora de la transaccion
				break;
			case 121: // recibo para IMPRIMIR
			case 122: // recibo para IMPRIMIR
				break;
			case 123: // indica el comprobante a entregar
				break;
			case 131: // la institución que procesará la transacción (pag 111)
				break;
			case 132: //
				break;
			case 133: // nro sitef
				break;
			case 134: // nro del autorizador
				break;
			case 135: // código de autorización para transacciones de crédito
				break;
			case 136: // 6 primeros dígitos de la TC
				break;
			case 156: // nombre institución (eje: VISA Credito)
				break;
			case 157: // código de establecimiento
				break;
			case 158: // código de red autorizadora (eje: AP)
				break;
			case 161: // contador de nro de pagos ??
				break;
			//case 170-176 // info venta a plazos
			case 1002: // data de validación ???
				break;
			case 1003: // nombre en tarjeta
				break;
			case 1190: // 4 últimos dígitos de la TC
				break;
			case 2010: // código de respuesta del autorizador
				break;
			case 2021: //datos sensibles
			case 2022:
			case 2023:
				break;
			case 2053: // menu visanet seleccionado ??
				break;
			case 2090: // tipo de tarjeta leida
				break;
			case 2091: // status de lectura
				break;
			case 2333: // identificador de transacción
				console.log('transacción Nro.', params.transferBuffer);
				break;
			case 2362: //
				break;
			case 2364: //
				break;
			case 2421: //
				break;
			//case 800-849 // reservados para GerPdv
			case 5057: // inicio, fin de datos sensibles
			case 5058:
				break;

		}
	}

	async function printPinPad(msg) {
		res = await ajax.post(`${host}/api/EscreveMensagemPermanentePinPad`, { text: msg })
		return res.data
	}


	async function pruebaComunicacion(params) {
		rs = await iniciarTransaccion({ sitefFunction: 111 })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		return rs;
	}

	async function iniciarFuncion({ sitefFunction }) {
		rs = await iniciarTransaccion({ sitefFunction })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		return rs;
	}

	async function iniciarPago({ saleInvoice, value, operator }) {
		rs = await iniciarTransaccion({ sitefFunction: 0, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmación del pos ?
		}
		return rs
	}

	async function pagoTarjetaCredito({ saleInvoice, value, operator }) {
		rs = await iniciarTransaccion({ sitefFunction: 3, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmación del pos ?
		}
		return rs
	}

	async function pagoTarjetaDebito({ saleInvoice, value, operator }) {

		rs = await iniciarTransaccion({ sitefFunction: 2, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmacion del pos ?
		}
		return rs
	}

	async function anularTransaccion({ saleInvoice, value, operator }) {

		rs = await iniciarTransaccion({ sitefFunction: 200, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmacion del pos ?
		}
		return rs
	}

	async function anularPagoCredito({ saleInvoice, value, operator }) {

		rs = await iniciarTransaccion({ sitefFunction: 210, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmacion del pos ?
		}
		return rs
	}

	async function anularPagoDebito({ saleInvoice, value, operator }) {

		rs = await iniciarTransaccion({ sitefFunction: 211, value, saleInvoice, operatorCode: operator })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}
		if (rs.ret == 0) {
			//rs = await finalizarTransaccion ?? o esperar confirmacion del pos ?
		}
		return rs
	}
	
	async function cierreTerminal(p) {
		const params = {
			factura: '', invoiceDate: '', operador: '', value: 0, ...p

		}
		const sitefFunction = 999

		if (params.invoiceDate == '') {
			const dt = new Date().toISOString().split('-')
			params.invoiceDate = dt[0] + dt[1] + dt[2].substr(0, 2)
		}
		rs = await iniciarTransaccion({ sitefFunction, value: params.value, saleInvoice: params.factura, operatorCode: params.operador, invoiceDate: params.invoiceDate })
		if (rs == 10000) {
			rs = await continuarTransaccion()
		}

		return rs.ret
	}


	

	async function configureSItef() {
		res = await ajax.post(`${host}/api/ConfiguraIntSiTefInterativo`, {})
		return res.data
	}

	clearData();
	const sitef = {
		on,
		emit,
		verificarPinPad,
		pruebaComunicacion,
		iniciarFuncion,

		iniciarPago,
		pagoTarjetaCredito,
		pagoTarjetaDebito,
		
		anularPagoCredito,
		anularPagoDebito,

		cierreTerminal,
		menu,

		anularTransaccion,
		cancelarTransaccion,
		finalizarTransaccion,
		transaccionesPendientes,
		cantidadTransaccionesPendientes,
		enviarMensajesPendientes,

		sitef_data,
		getSitefData() {
			const data = JSON.parse(JSON.stringify(sitef_data));

			data.transaccionesPendientes = Number(data.responseValues[210]);
			data.facturaPendiente = data.responseValues[160];
			
			return data;
		},
		

	}
	return sitef;
}

