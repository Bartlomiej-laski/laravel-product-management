<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
	protected $table='catalog_shipping';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'title', 'description','price','price_type','region'
	];
}
