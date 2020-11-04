@if ($item['submenu'] == [])
    <li>
        <a href="{{ secure_url($item['slug']) }}">{{ $item['name'] }} </a>
    </li>
@else
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['name'] }} <span class="caret"></span></a>
        <ul class="dropdown-menu sub-menu">
            @foreach ($item['submenu'] as $submenu)
                @if ($submenu['submenu'] == [])
                    <li><a href="{{ url( $submenu['slug']) }}">{{ $submenu['name'] }} </a></li>
                
                @endif
            @endforeach
        </ul>
    </li>
@endif