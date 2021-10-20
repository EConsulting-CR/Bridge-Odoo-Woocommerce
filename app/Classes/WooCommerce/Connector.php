<?php

namespace App\Classes\WooCommerce;

use Codexshaper\WooCommerce\Models\Product;

class Connector
{
    function getCategories()
    {
        //TODO Add Categories to Database
    }

    function AddProducts(array $data)
    {
        $Product =
            [
                'name' => $data['name'],
                'type' => 'simple',
                'regular_price' => strval(intval($data['price'])),
                'sku'=> strval($data['sku']),
                'status'=>'draft'
            ];
        $wcResult = Product::create($Product);
        return $wcResult["id"];

    }

    function UpdateProducts()
    {
        //TODO Update synced products
    }
}
