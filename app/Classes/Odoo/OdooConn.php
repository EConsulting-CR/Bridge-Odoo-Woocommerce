<?php


namespace App\Classes\Odoo;


use Obuchmann\LaravelOdooApi\Odoo;

class OdooConn
{
    private $Conn;

    public function __construct()
    {
        $this->Conn = new Odoo();
        $this->Conn->database(env('LARAVEL_ODOO_API_DB_NAME'));
        $this->Conn->host(env('LARAVEL_ODOO_API_HOST'));
        $this->Conn->username(env('LARAVEL_ODOO_API_USER_NAME'));
        $this->Conn->password(env('LARAVEL_ODOO_API_USER_PASSWORD'));
    }
    function getAllCategories(){
        return $this->Conn->model('product.category')->fields('name')->get();
    }
    function getAllProducts()
    {
        return
            $this->Conn->model('product.product')
//                ->where('sale_ok','=',true)
                ->where('default_code','like',"-%")
                ->fields('default_code','name','list_price',"categ_id",'sale_ok')
                ->get();
    }
}
