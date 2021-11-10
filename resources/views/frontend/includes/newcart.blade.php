<div class="modal fade" id="CartModal" role="dialog" aria-labelledby="modalLabeldanger">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MI PEDIDO</h4>
              </div>
              <div class="modal-body bodycarrito">
                
                 @include('frontend.includes.bodycarrito')
              </div>
            
            </div><!-- /.modal-content -->

        </div>
    </div>




    <button class="btn btn-default" id="btnCarrito" >
        <img   src="{{secure_url('assets/images/carrito-compras.png')}}" alt="">
        <span class="badgecarrito cantidadCarrito">0</span>
    </button>