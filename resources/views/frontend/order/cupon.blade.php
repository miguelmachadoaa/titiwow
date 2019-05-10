   <div class="col-sm-6">
        
       
          
         <br>


         @if (session('aviso'))
            <div class="alert alert-danger">
                {{ session('aviso') }}
            </div>
        @endif

       

        @include('frontend.includes.detallesventa')
        
          



     

    </div> <!-- end Row --><!-- col-sm-8 -->


    <div class="col-sm-6">

         @include('frontend.includes.direcciones')





           @include('frontend.includes.formasenvio')





        @include('frontend.includes.formaspago')

     

    </div>

    <br> 

    <div class=" res_env">  </div> 