<!-- Undefined Field -->
<div class="form-group col-sm-12">
    {!! Form::label('undefined', 'Undefined:') !!}
    {!! Form::text('undefined', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Producto Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name_producto', 'Name Producto:') !!}
    {!! Form::text('name_producto', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.productos.index') !!}" class="btn btn-default">Cancel</a>
</div>
