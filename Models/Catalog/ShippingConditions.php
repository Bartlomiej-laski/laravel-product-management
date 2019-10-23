<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class ShippingConditions extends Model
{
	protected $table='catalog_shipping_conditions';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'shipping_id','condition_id','type'
	];
}
