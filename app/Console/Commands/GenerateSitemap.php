<?php

namespace App\Console\Commands;

use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use App\Models\AlpMarcas;
use App\Models\AlpCms;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            // create new sitemap object
        $sitemap = \App::make("sitemap");

        //config
        $siteUrl = 'https://alpinago.com';
        $maxCount = 1000;


        // get all products from db (or wherever you store them)
        $products = AlpProductos::take(1000)->where('estado_registro','=',1)->get()->toArray();

        $categorias = AlpCategorias::take(100)->where('estado_registro','=',1)->get()->toArray();

        $marcas = AlpMarcas::take(100)->where('estado_registro','=',1)->get()->toArray();

        $cmss = AlpCms::take(100)->where('estado_registro','=',1)->get()->toArray();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        if ($counter == 0 && $sitemapCounter == 0) {
            $sitemap->add($siteUrl, date('Y-m-d H:i:s'), '1.0', 'weekly');
        }

        // add every product to multiple sitemaps with one sitemapindex
        foreach ($products as $product) {
            if ($counter == $maxCount) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-product-' . $sitemapCounter, '/var/www/html/sitemap-sn');
                // add the file to the sitemaps array
                $sitemap->addSitemap($siteUrl . '/sitemap-sn/' . 'sitemap-product-' . $sitemapCounter . '.xml', date('Y-m-d H:i:s'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            $final_slug = $siteUrl . '/producto/' . $product['slug'];

            // add product to items array
            $sitemap->add($final_slug, date('Y-m-d H:i:s'), '1.0', 'weekly');
            // count number of elements
            $counter++;
        }

        foreach ($categorias as $category) {
            if ($counter == $maxCount) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-category-' . $sitemapCounter, '/var/www/html/sitemap-sn');
                // add the file to the sitemaps array
                $sitemap->addSitemap($siteUrl . '/sitemap-sn/' . 'sitemap-category-' . $sitemapCounter . '.xml', date('Y-m-d H:i:s'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            $final_slug = $siteUrl . '/categoria/' . $category['slug'];

            // add product to items array
            $sitemap->add($final_slug, date('Y-m-d H:i:s'), '1.0', 'monthly');
            // count number of elements
            $counter++;
        }

        foreach ($marcas as $marca) {
            if ($counter == $maxCount) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-marcas-' . $sitemapCounter, '/var/www/html/sitemap-sn');
                // add the file to the sitemaps array
                $sitemap->addSitemap($siteUrl . '/sitemap-sn/' . 'sitemap-marcas-' . $sitemapCounter . '.xml', date('Y-m-d H:i:s'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            $final_slug = $siteUrl . '/marcas/' . $marca['slug'];

            // add product to items array
            $sitemap->add($final_slug, date('Y-m-d H:i:s'), '1.0', 'monthly');
            // count number of elements
            $counter++;
        }

        foreach ($cmss as $cms) {
            if ($counter == $maxCount) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-paginas-' . $sitemapCounter, '/var/www/html/sitemap-sn');
                // add the file to the sitemaps array
                $sitemap->addSitemap($siteUrl . '/sitemap-sn/' . 'sitemap-paginas-' . $sitemapCounter . '.xml', date('Y-m-d H:i:s'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            $final_slug = $siteUrl . '/paginas/' . $cms['slug'];

            // add product to items array
            $sitemap->add($final_slug, date('Y-m-d H:i:s'), '1.0', 'monthly');
            // count number of elements
            $counter++;
        }

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-' . $sitemapCounter, '/var/www/html/sitemap-sn');
            // add sitemap to sitemaps array
            $sitemap->addSitemap($siteUrl . '/sitemap-sn/' . 'sitemap-product-' . $sitemapCounter . '.xml', date('Y-m-d H:i:s'));
            // reset items array
            $sitemap->model->resetItems();
        }
    }

}
