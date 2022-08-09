
@extends('layouts/pos')

{{-- Page title --}}
@section('title')
Area clientes   
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

  <link rel="canonical" href="{{secure_url('clientes')}}" />

    <style type="text/css">
        
        .btn-medium {
            height: 120px;
            line-height: 120px;
            width: 120px;
            margin-bottom: 1em;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff !important; 
            box-shadow: 2px 2px 2px #ddd;
            border-radius: 1em;
        }


        .btn-medium {
            text-decoration: none;
            color: #000;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            transition: .2s ease-out;
            cursor: pointer;
        }

        .btn-medium i {
            font-size: 3.6rem;
        }

        h4 span {
    color: #007add;
}



    </style>
@stop


{{-- Page content --}}
@section('content')

<div class="container-fluid contain_body">

    <div class="row">
        
        <div class="col-sm-8 panelprincipal">
            
            @include('pos.dashboard')

        </div> 
        <div class="col-sm-4 ordenactual" >
            
            <di class="row">
                <div class="col-sm-12">
                    Current Order
                </div>
            </di>

             <di class="row">
                <div class="col-sm-10">
                   <input class="form-control" type="text" name="cliente" id="cliente" value="">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary"><i class="fa fa-user"></i></button>
                </div>
            </di>


        </div> 


    </div>
    
</div>


<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">

  
@endsection

<!-- Modal Direccion -->

{{-- page level scripts --}}
@section('footer_scripts')


<script>
    
    $(document).ready(function(){

        $(document).on('click', '.cajita', function(){

            vista=$(this).data('id');

            base=$('#base').val();

                $.ajax({
                    type: "GET",
                    url: base+"/pos/"+vista,
                        
                    complete: function(datos){     

                        $('.panelprincipal').html((datos.responseText));
                    }

                });

        });

    });
</script>
  
@stop