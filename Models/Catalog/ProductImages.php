<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
	protected $table='catalog_product_images';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'product_id', 'image_url','is_main'
	];
}
