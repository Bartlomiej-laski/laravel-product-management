<?php

namespace App\Http\Controllers\Backend\Admin\Catalog;

use App\Models\Auth\Role;
use App\Models\Backend\Catalog\Conditions;
use App\Models\Backend\Catalog\Shipping;
use App\Models\Backend\Catalog\ShippingConditions;
use App\Models\Backend\Catalog\ShippingRegions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index(){
		$shippingTemplate = true;
		$roles = Role::all();
    	return view("backend/catalog/shipping/management",["shippingTemplate"=>$shippingTemplate,"roles"=>$roles]);
	}

	public function get_shipping(Request $request){
    	$shippingID = $request->id;
    	if(!$shippingID) $data = Shipping::all();
		else{
			$data = [];
    		$shipping = Shipping::where("id","=",$shippingID)->first();
			$data["shipping"] = $shipping;
			if($shipping->region === "other"){
    			$regions = ShippingRegions::where("shipping_id","=",$shippingID)
					->leftJoin("data_regions","catalog_shipping_regions.region_id","=","data_regions.id")
					->get();
    			$data["regions"]=$regions;
			}
    		if($shipping->price_type === "variable"){
    			$conditions =  ShippingConditions::where("shipping_id","=",$shippingID)
					->leftJoin("catalog_conditions","catalog_shipping_conditions.condition_id","=","catalog_conditions.id")
					->get();
    			$data["conditions"] = $conditions;
			}
		}
		return response()->json([
			"data"=>$data
		]);
	}

	public function delete_shipping(Request $request){
    		$shippingID = $request->id;
    		$conditions = ShippingConditions::where("shipping_id","=",$shippingID)->get();
			ShippingConditions::where("shipping_id","=",$shippingID)->delete();
    		//Delete condition if it dont use in others
			foreach($conditions as $condition){
    			$count = ShippingConditions::where("condition_id","=",$condition["condition_id"])->count();
    			if($count === 0){
    				Conditions::where("id","=",$condition["condition_id"])->delete();
				}
			}
			ShippingRegions::where("shipping_id","=",$shippingID)->delete();
			Shipping::where("id","=",$shippingID)->delete();
	}

	public function add_shipping(Request $request){
    	$data = $request->data;
    	$title = $data["title"];
    	$description = $data["description"];
    	$region = $data["region"];
    	$price = $data["price"];
    	$priceType = $data["priceType"];

    	$shipping = new Shipping;
    	$shipping->title = $title;
    	$shipping->description = $description;
    	$shipping->price = $price;
    	$shipping->price_type = $priceType;
    	$shipping->region = $region;
    	$shipping->save();
    	$shippingID = $shipping->id;

		if($region === "other"){
			$regions = $data["customRegions"];
			foreach($regions as $region){
				$shippingRegions = new ShippingRegions;
				$shippingRegions->shipping_id = $shippingID;
				$shippingRegions->region_id = $region["id"];
				$shippingRegions->save();
			}
		}
		if($priceType === "variable"){
			$conditions = $data["priceConditions"];
			foreach($conditions as $condition){
				$type = $condition["type"];
				$value = $condition["value"];
				$discountType = $condition["discount_type"];
				$discountValue = $condition["discount_value"];
				$count = Conditions::select("id")
					->where("type","=",$type)
					->where("value","=",$value)
					->where("discount_type","=",$discountType)
					->where("discount_value","=",$discountValue)
					->count();
				if($count === 0){
					$cond = new Conditions;
					$cond->type = $type;
					$cond->value = $value;
					$cond->discount_type = $discountType;
					$cond->discount_value = $discountValue;
					$cond->save();
					$conditionID = $cond->id;
				} else{
					$cond = Conditions::select("id")
						->where("type","=",$type)
						->where("value","=",$value)
						->where("discount_type","=",$discountType)
						->where("discount_value","=",$discountValue)
						->first();
					$conditionID = $cond->id;
				}

				$shippingConditions = new ShippingConditions;
				$shippingConditions->shipping_id = $shippingID;
				$shippingConditions->condition_id = $conditionID;
				$shippingConditions->save();
			}
		}

		return response()->json([
			"status"=> $shipping?true:false,
			"message" => $shipping? "You have successfully added shipping":"Error, refresh page and try again"
		]);
	}
	public function get_roles(){
    	$data = Role::all();
    	return response()->json([
    		"data"=>$data
		]);
	}
}
