<!-- Nav bar Start -->
<nav class="navbar navbar-default container">

<div class="collapse navbar-collapse" id="collapse">
    <ul class="nav navbar-nav navbar-left">
        <li {!! (Request::is('/') ? 'class="active"' : '') !!}><a href="{{ secure_url('/') }}"> Home</a>
        </li>
        @foreach ($menu as $item)
            <li><a href="{{ $item->url_menu}}">{{ $item->titulo_menu}}</a></li>
        @endforeach
        <li class="dropdown {!! (Request::is('typography') || Request::is('advancedfeatures') || Request::is('grid') ? 'active' : '') !!}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Features</a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ URL::to('typography') }}">Typography</a>
                </li>
                <li><a href="{{ URL::to('advancedfeatures') }}">Advanced Features</a>
                </li>
                <li><a href="{{ URL::to('grid') }}">Grid System</a>
                </li>
            </ul>
        </li>
        <li class="dropdown {!! (Request::is('aboutus') || Request::is('timeline') || Request::is('faq') || Request::is('blank_page')  ? 'active' : '') !!}"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Pages</a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ URL::to('aboutus') }}">About Us</a>
                </li>
                <li><a href="{{ URL::to('timeline') }}">Timeline</a></li>
                <li><a href="{{ URL::to('price') }}">Price</a>
                </li>
                <li><a href="{{ URL::to('404') }}">404 Error</a>
                </li>
                <li><a href="{{ URL::to('500') }}">500 Error</a>
                </li>
                <li><a href="{{ URL::to('faq') }}">FAQ</a>
                </li>
                <li><a href="{{ URL::to('blank_page') }}">Blank</a>
                </li>
            </ul>
        </li>
        <li class="dropdown {!! (Request::is('products') || Request::is('single_product') || Request::is('compareproducts') || Request::is('category')  ? 'active' : '') !!}"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Shop</a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ URL::to('products') }}">Products</a>
                </li>
                <li><a href="{{ URL::to('single_product') }}">Single Product</a>
                </li>
                <li><a href="{{ URL::to('compareproducts') }}">Compare Products</a>
                </li>
                <li><a href="{{ URL::to('category') }}">Categories</a></li>
            </ul>
        </li>
        <li class="dropdown {!! (Request::is('portfolio') || Request::is('portfolioitem') ? 'active' : '') !!}"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Portfolio</a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ URL::to('portfolio') }}">Portfolio</a>
                </li>
                <li><a href="{{ URL::to('portfolioitem') }}">Portfolio Item</a>
                </li>
            </ul>
        </li>
        <li {!! (Request::is('news') || Request::is('news/*') ? 'class="active"' : '') !!}><a
                    href="{{ URL::to('news') }}">News</a>
        </li>

        <li {!! (Request::is('blog') || Request::is('blogitem/*') ? 'class="active"' : '') !!}><a href="{{ URL::to('blog') }}"> Blog</a>
        </li>
         <li {!! (Request::is('productos')  ? 'class="active"' : '') !!}><a href="{{ URL::to('productos') }}"> Productos</a>
        </li>
        <li {!! (Request::is('contact') ? 'class="active"' : '') !!}><a href="{{ URL::to('contact') }}">Contact</a>
        </li>

        {{--based on anyone login or not display menu items--}}
        @if(Sentinel::guest())
            <li><a href="{{ URL::to('login') }}">Login</a>
            </li>
            <li><a href="{{ URL::to('register') }}">Register</a>
            </li>
        @else
            <li {{ (Request::is('my-account') ? 'class=active' : '') }}><a href="{{ URL::to('my-account') }}">My Account</a>
            </li>
            <li><a href="{{ URL::to('logout') }}">Logout</a>
            </li>
        @endif
    </ul>
</div>
</nav>