<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class Conditions extends Model
{
	protected $table='catalog_conditions';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'type','value','discount_type','discount_value'
	];
}
