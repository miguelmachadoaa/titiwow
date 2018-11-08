<div class="row">
    <h3>Categorías</h3>
    <div class="dd" id="nestable_list_1">
        <ul class="dd-list">
            @foreach ($categorias as $key => $item)
                @if ($item['parent'] != 0)
                    @break
                @endif
                @include('layouts.menu-sidebar', ['item' => $item])
            @endforeach
        </ul>
    </div>
</div>
<div class="row">
    <h3>Marcas</h3>
    <div  id="">
        <ul >
            @foreach ($marcas as $key => $item)
                @if ($item['parent'] != 0)
                    @break
                @endif
                @include('layouts.menu-sidebar', ['item' => $item])
            @endforeach
        </ul>
    </div>
</div>