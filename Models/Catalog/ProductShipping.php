<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductShipping extends Model
{
	protected $table='catalog_product_shipping';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'product_id', 'shipping_id'
	];
}
