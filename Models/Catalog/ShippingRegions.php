<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ShippingRegions extends Model
{
	protected $table='catalog_shipping_regions';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'shipping_id','region_id'
	];
}
