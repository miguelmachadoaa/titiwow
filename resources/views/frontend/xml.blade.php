<?php header('Content-Type: text/xml'); ?>
{!! '<'.'?xml version="1.0" encoding="utf-8" ?>' !!}
{!!'<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0" xmlns:atom="http://www.w3.org/2005/Atom" >'!!}
    {!!'<channel>'!!}
        {!!'<title>Alpina</title>'!!}
        {!!'<link>http://www.alpinago.com</link>'!!}
        {!!'<description>Alpinago Store {{$id}}</description>'!!}
        {!!'<atom:link href="https://www.alpinago.com/xml" rel="self" type="application/rss+xml"'!!} />
        @if(count($prods))
            @foreach($prods as $p)
                @if(isset($inventario[$p->id]))
                    @if($inventario[$p->id]>0)
                        {!!'<item>'!!}
                            {!!'<g:item_group_id>'.$p->id.'</g:item_group_id>'!!}
                            {!!'<g:gtin>'.$p->referencia_producto.'</g:gtin>'!!}
                            {!!'<g:google_product_category>412</g:google_product_category>'!!}
                            {!!'<g:id>'.$p->id.'</g:id>'!!}
                            {!!'<g:title>'.$p->nombre_producto.'</g:title>'!!}
                            {!!'<g:description>'.$p->descripcion_corta.'</g:description>'!!}
                            {!!'<g:link>'.secure_url('producto/'.$p->slug).'</g:link>'!!}
                            {!!'<g:image_link>'.secure_url('/uploads/productos/'.$p->imagen_producto).'</g:image_link>'!!}
                            {!!'<g:brand>'.$p->nombre_marca.'</g:brand>'!!}
                            {!!'<g:condition>new</g:condition>'!!}
                            {!!'<g:availability>in stock</g:availability>'!!}
                            {!!'<g:price>'.$p->precio_base.' COP</g:price>'!!}
                            {!!'<g:sale_price>'.$p->precio_oferta.' COP</g:sale_price>'!!}
                            {!!'<g:offer_price>'.$p->precio_oferta.' COP</g:offer_price>'!!}
                        {!!'</item>'!!}
                    @endif
                @endif
            @endforeach
        @endif
    {!!'</channel>'!!}
{!!'</rss>'!!}