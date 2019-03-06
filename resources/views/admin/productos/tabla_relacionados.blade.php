 <div class="col-sm-12">
                       
                    <table class="table table-responsive">
                        <tr>
                            <td>Producto Relacionado</td>

                            <td>Accion</td>
                        </tr>
                        @foreach($relacionados as $r)

                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>
                                <button data-id="{{ $r->id }}" type="button" class="btn btn-danger delRelacionado"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>

                        @endforeach

                    </table>

                   </div>