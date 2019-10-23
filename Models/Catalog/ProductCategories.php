<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
	protected $table='catalog_product_categories';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'product_id', 'category_id'
	];
}
