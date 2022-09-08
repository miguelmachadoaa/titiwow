<div class="row">
    <h3 class="subtitulo">Categorías</h3>
    <div class="dd" id="nestable_list_1">
        <ul class="dd-list">
            @php $categorias = []; @endphp
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
    <h3 class="subtitulo">Marcas</h3>
    <div  id="">
        <ul >
            @php $marcas = []; @endphp
            @foreach ($marcas as $key => $item)
                @if ($item['parent'] != 0)
                    @break
                @endif
                @include('layouts.menu-sidebar', ['item' => $item])
            @endforeach
        </ul>
    </div>
</div>