{!! '<'.'?xml version="1.0" encoding="utf-8" ?>' !!}
{!!'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/">'!!}
{!!'<tem:LoginInfo>'!!}
{!!'<tem:UserName>'.$encrypted_user.'</tem:UserName>'!!}
{!!'<tem:Password>'.$encrypted_password.'</tem:Password>'!!}
{!!'<tem:Fecha>'.$fecha.'</tem:Fecha>'!!}
{!!'</tem:LoginInfo>'!!}
{!!'</soap:Header>'!!}
{!!'<soap:Body>'!!}
{!!'<tem:ValidarCuposGo>'!!}
{!!'<tem:DocumentoEmpleado>79964463</tem:DocumentoEmpleado>'!!}
{!!'</tem:ValidarCuposGo>'!!}
{!!'</soap:Body>'!!}
{!!'</soap:Envelope>'!!}