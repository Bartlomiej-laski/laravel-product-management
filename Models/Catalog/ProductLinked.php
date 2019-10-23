<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductLinked extends Model
{
	protected $table='catalog_product_linked';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'product_id', 'linked_id','type'
	];
}
