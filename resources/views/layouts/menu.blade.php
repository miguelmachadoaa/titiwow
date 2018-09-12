@if ($item['submenu'] == [])
    <li>
        <a href="{{ url($item['slug']) }}">{{ $item['name'] }} </a>
    </li>
@else
    <li class="dropdown {!! (Request::is('typography') || Request::is('advancedfeatures') || Request::is('grid') ? 'active' : '') !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['name'] }} <span class="caret"></span></a>
        <ul class="dropdown-menu sub-menu" role="menu">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li><a href="{{ url('menu',['id' => $submenu['id'], 'slug' => $submenu['slug']]) }}">{{ $submenu['name'] }} </a></li>
                @else
                    @include('layouts.menu', [ 'item' => $submenu ])
                @endif
            @endforeach
        </ul>
    </li>
@endif