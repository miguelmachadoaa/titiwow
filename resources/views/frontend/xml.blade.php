{!! '<'.'?xml version="1.0"?>' !!}
{{'<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">'}}
    {{'<channel>'}}
        {{'<title>Alpina</title>'}}
        {{'<link>http://www.alpinago.com</link>'}}
        {{'<description>Alpinago Store</description>'}}
        @if(count($productos))
            @foreach($productos as $p)
                @if(isset($inventario[$p->id]))
                    {{'<item>'}}
                        {{'<g:id>'.$p->id.'</g:id>'}}
                        {{'<g:title>'.$p->nombre_producto.'</g:title>'}}
                        {{'<g:description>'.$p->descripcion_corta.'</g:description>'}}
                        {{'<g:link>'.secure_url('producto/'.$p->slug).'</g:link>'}}
                        {{'<g:image_link>'.secure_url('/uploads/productos/'.$p->imagen_producto).'</g:image_link>'}}
                        {{'<g:brand>'.$p->nombre_marca.'</g:brand>'}}
                        {{'<g:condition>new</g:condition>'}}
                        {{'<g:availability>in stock</g:availability>'}}
                        {{'<g:price>'.$p->precio_base.' COP</g:price>'}}
                        {{'<g:google_product_category>412</g:google_product_category>'}}
                    {{'</item>'}}
                @endif
            @endforeach
        @endif
    {{'</channel>'}}
{{'</rss>'}}