<?php

namespace App\Models\Backend\Catalog;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table='catalog_products';
	public $primaryKey='id';
	public $timestamps = true;
	protected $fillable = [
		'title', 'description','general_price','sale_price','storage','published','status','visibility','has_linked','is_online','is_unlimited','trending','is_draft'
	];

	public function reviews()
	{
		return $this->morphMany('App\Models\Review', 'reviewable')->orderBy("created_at","DESC");
	}

}
