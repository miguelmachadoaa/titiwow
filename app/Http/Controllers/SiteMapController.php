<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Cache;
use Carbon\Carbon;
use App\Blog;
use DB;

class SiteMapController extends JoshController
{
    private $siteMap;

    public function index()
    {

        /*$siteMapXml = Cache::remember('sitemap', 3, function () {
            $this->siteMap = new SiteMap();*/
            $this->siteMap = new SiteMap();
                            

            $this->addUniqueRoutes();
            $this->addMarcas();
            $this->addCategories();
            $this->addProductos();
            $this->addBlog();

            return response($this->siteMap->build(), 200)
            ->header('Content-Type', 'text/xml');

    }

    private function addUniqueRoutes()
    {
        $iniciodelMes = Carbon::now()->startOfMonth();
        $startOfMonth =$iniciodelMes->format('Y-m-d');
    
        $this->siteMap->add(
            Url::create('/')
                ->lastUpdate($startOfMonth)
                ->frequency('daily')
                ->priority('1.00')
        );
    
        $this->siteMap->add(
            Url::create('/productos')
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                ->priority('0.8')
        );
    
        $this->siteMap->add(
            Url::create('/contacto')
                ->lastUpdate($startOfMonth)
                ->frequency('monthly')
                ->priority('0.7')
        );
    
        $this->siteMap->add(
            Url::create('/marcas')
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                ->priority('0.9')
        );
    
        $this->siteMap->add(
            Url::create('/blog')
                ->lastUpdate($startOfMonth)
                ->frequency('monthly')
                ->priority('0.7')
        );
    
        $this->siteMap->add(
            Url::create('/categorias')
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                ->priority('0.9')
        );
    
    }
    private function addBlog()
    {
        $iniciodelMes = Carbon::now()->startOfMonth();
        $startOfMonth =$iniciodelMes->format('Y-m-d');

        $blogs = DB::table('blogs')
        ->select('blogs.*')
        ->whereNull('blogs.deleted_at')
        ->get();

        foreach ($blogs as $blog) {
            $this->siteMap->add(
                Url::create("/blog/".$blog->slug)
                ->lastUpdate($startOfMonth)
                ->frequency('monthly')
                    ->priority('0.7')
            );
        }
    }
    
    private function addCategories()
    {

        $iniciodelMes = Carbon::now()->startOfMonth();
        $startOfMonth =$iniciodelMes->format('Y-m-d');

        $categorias = DB::table('alp_categorias')
        ->select('alp_categorias.*')
        ->where('alp_categorias.estado_registro','=',1)
        ->whereNull('alp_categorias.deleted_at')
        ->orderBy('order', 'asc')
        ->get();

        foreach ($categorias as $categoria) {
            $this->siteMap->add(
                Url::create("/categoria/".$categoria->slug)
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                    ->priority('0.9')
            );
        }
    }
    

    private function addMarcas()
    {
        $iniciodelMes = Carbon::now()->startOfMonth();
        $startOfMonth =$iniciodelMes->format('Y-m-d');

        $marcas = DB::table('alp_marcas')
        ->select('alp_marcas.*')
        ->where('alp_marcas.estado_registro','=',1)
        //->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString())
        ->whereNull('alp_marcas.deleted_at')
        ->groupBy('alp_marcas.id')
        ->orderBy('order', 'asc')
        ->get();

        foreach ($marcas as $marca) {
            $this->siteMap->add(
                Url::create("/marca/".$marca->slug)
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                    ->priority('0.9')
            );
        }

    }
    private function addProductos()
    {
        $iniciodelMes = Carbon::now()->startOfMonth();
        $startOfMonth =$iniciodelMes->format('Y-m-d');

        $productos = DB::table('alp_productos')
        ->select('alp_productos.*')
        ->where('alp_productos.estado_registro','=',1)
        //->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString())
        ->whereNull('alp_productos.deleted_at')
        ->groupBy('alp_productos.id')
        ->orderBy('order', 'asc')
        ->get();

        foreach ($productos as $producto) {
            $this->siteMap->add(
                Url::create("/producto/".$producto->slug)
                ->lastUpdate($startOfMonth)
                ->frequency('weekly')
                    ->priority('0.8')
            );
        }
    }

}

class SiteMap
{
    const START_TAG = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    const END_TAG = '</urlset>';

    // to build the XML content
    private $content;

    public function add(Url $siteMapUrl)
    {
        $this->content .= $siteMapUrl->build();
    }

    public function build()
    {
        return self::START_TAG . $this->content . self::END_TAG;
    }
}

class Url
{
    private $url;
    private $lastUpdate;
    private $frequency;
    private $priority;

    public static function create($url)
    {
        $newNode = new self();
        $newNode->url = url($url);
        return $newNode;
    }

    public function lastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function frequency($frequency)
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function priority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function build()
    {
        // $url = 'https://programacionymas.com/';
        // $lastUpdate = '2019-07-31T01:06:39+00:00';
        // $frequency = 'monthly';
        // $priority = '1.00';
        return "<url>" .
            "<loc>$this->url</loc>" .
            "<lastmod>$this->lastUpdate</lastmod>" .
            "<changefreq>$this->frequency</changefreq>" .
            "<priority>$this->priority</priority>" .
        "</url>";
    }
}