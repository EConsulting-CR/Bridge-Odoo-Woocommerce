<?php

namespace App\Console\Commands;

use App\Classes\Odoo\OdooConn;
use App\Classes\WooCommerce\Connector;
use App\Models\Product;
use Illuminate\Console\Command;

class OdooConnector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odoo:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $Odoo = new OdooConn();
        $Products = $Odoo->getAllProducts();
        $this->storeProducts(json_decode($Products));
        $this->syncProducts();


        return Command::SUCCESS;
    }

    function storeProducts(array $Products)
    {
        $Data = array_map(function ($array) {
            return [
                'odoo_id' => $array->id,
                'sku' => $array->default_code,
                'name' => $array->name,
                'price' => round($array->list_price * 1.13, -1),
                'sale_ok' => $array->sale_ok,
            ];
        }, $Products);
        foreach ($Data as $item) {
            if (Product::where('odoo_id', $item['odoo_id'])->count() < 1) {
                Product::create($item);
            } else {
                Product::where('odoo_id', $item['odoo_id'])->update(
                    [
                        'price' => $item["price"],
                        'sale_ok' => $item['sale_ok'],
                        'sync' => false
                    ]);
            }

        }

    }

    function syncProducts()
    {
        $Products = Product::all()->where('sync', false)->toArray();
        foreach ($Products as $product) {
            $Conn = new Connector();
            $Result = $Conn->AddProducts($product);
            $WCProduct = Product::findOrFail($product['id']);
            $WCProduct->wc_id = $Result;
            $WCProduct->sync = true;
            $WCProduct->save();
        }
    }

}
