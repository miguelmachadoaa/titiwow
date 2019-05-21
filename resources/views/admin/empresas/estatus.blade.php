  @if($empresa->estado_registro=='1')

                                                    <button data-url="{{secure_url('admin/empresas/estatus')}}" type="buttton" data-id="{{$empresa->id}}" data-estatus="0" class="btn btn-xs btn-primary estatus">Desactivar</button>

                                                @else

                                                    <button data-url="{{secure_url('admin/empresas/estatus')}}" type="buttton" data-id="{{$empresa->id}}" data-estatus="1" class="btn btn-xs btn-primary estatus">Activar</button>

                                                @endif