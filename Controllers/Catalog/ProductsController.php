<?php

namespace App\Http\Controllers\Backend\Admin\Catalog;

use App\Models\Auth\Role;
use App\Models\Backend\Catalog\Product;
use App\Models\Backend\Catalog\ProductCategories;
use App\Models\Backend\Catalog\ProductImages;
use App\Models\Backend\Catalog\ProductLinked;
use App\Models\Backend\Catalog\ProductShipping;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Frontend\store\ProductsList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
	public function index($id = null)
	{
		$shippingTemplate = false;
		if($id) $mode = "Edit";
		else $mode = "Add";
		$categories = Category::all();
		$roles = Role::all();
		return view("backend/catalog/product/management", [
			"mode" => $mode,
			"categories" => $categories,
			"roles" => $roles,
			"editID" => $id,
			"shippingTemplate"=>$shippingTemplate
		]);
	}

	public function _all_products()
	{
		return view("backend/catalog/product/allProducts");
	}

	public function add_product(Request $request)
	{
		if($request->manageMode !== "Edit"){
			$count = Product::where("slug", $request->slug)->count();
			if ($count > 0) {
				return response()->json([
					"status" => false,
					"message" => "Product with this slug already exist, please choose different name!"
				]);
			}
		}

		$title = $request->title;
		$slug = $request->slug;
		$description = $request->description;
		$price = $request->price;
		$salePrice = $request->salePrice;
		$discount = $request->discount;
		if (!empty($discount)) {
			$totalPrice = $salePrice > 0 ? $salePrice - $salePrice * ($discount / 100) : $price - $price * ($discount / 100);
		} else $salePrice > 0 ? $totalPrice = $salePrice : $totalPrice = $price;
		$units = $request->units;
		$publishedDate = date("Ymd", strtotime($request->publishedDate));
		$status = $request->status;
		$visibility = $request->visibility;
		$hasLinked = $request->hasLinked === 'true' ? true : false;
		$isOnline = $request->isOnline === 'true' ? true : false;
		$isUnlimited = $request->isUnlimited === 'true' ? true : false;
		$isTrending = $request->isTrending === 'true' ? true : false;
		$isDraft = $request->isDraft === 'true' ? true : false;
		$categories = $request->categories;
		$shipping = $request->shipping;
		$linkedProducts = $request->linkedProducts;
		//MANAGE MODE
		$mode = $request->manageMode;
		$editID = $request->editID;


		if($status === "published"){
			$now = date("Ymd");
			if($publishedDate > $now){
				$isDraft = true;
				$status = "draft";
			}else{
				$isDraft = false;
				$status = "published";
			}
		}

		/*print_r($now);
		print_r("####");
		print_r($publishedDate);)*/

		$product = new Product;
		if($mode === "Edit"){
			$product =  Product::find($editID);
		}

		$product->title = $title;
		$product->slug = $slug;
		$product->description = $description;
		$product->price = $price;
		$product->sale_price = $salePrice;
		$product->discount = $discount;
		$product->total_price = ceil($totalPrice);
		$product->units = $units;
		$product->published_on = $publishedDate;
		$product->status = $status;
		$product->visibility = $visibility;
		$product->has_linked = $hasLinked;
		$product->is_online = $isOnline;
		$product->is_unlimited = $isUnlimited;
		$product->trending = $isTrending;
		$product->is_draft = $isDraft;
		$product->save();
		$currentID = $product->id;

		if($mode === "Edit") ProductCategories::where("product_id","=",$editID)->where("type","product")->delete();
		foreach ($categories as $category) {
			$productCategories = new ProductCategories;
			$productCategories->product_id = $currentID;
			$productCategories->category_id = $category;
			$productCategories->type = "product";
			$productCategories->save();
		}

		if($mode === "Edit") ProductLinked::where("product_id","=",$editID)->delete();
		if ($hasLinked === true) {
			foreach ($linkedProducts as $linkedProduct) {
				$helpArr = explode(",", $linkedProduct);
				$linked = new ProductLinked;
				$linked->product_id = $currentID;
				$linked->linked_id = $helpArr[1];
				$linked->type = $helpArr[0];
				$linked->save();
			}
		}

		if($mode === "Edit") ProductShipping::where("product_id","=",$editID)->delete();
		if ($isOnline === false) {
			foreach ($shipping as $shipp) {
				$productShipping = new ProductShipping;
				$productShipping->product_id = $currentID;
				$productShipping->shipping_id = $shipp;
				$productShipping->save();
			}
		}

		if($product && $mode !== "Edit"){
			$list  = new ProductsList;
			$list->type = "product";
			$list->product_id = $currentID;
			$list->price = $totalPrice;
			$list->title = $title;
			$list->save();
		}else if($product && $mode === "Edit"){
			ProductsList::where("product_id","=",$editID)->where("type","=","product")
			->update(["price"=>$totalPrice,"title"=>$title]);
		}

		return response()->json([
			'id' => $currentID,
			'status' => $product ? true : false,
			'message' => $product ? "You have successfully ".$mode."ed product !" : "Error, refresh page and try again"
		]);

	}

	public function add_product_images(Request $request)
	{
		$extension = $request->file('file')->getClientOriginalExtension();
		$dir = 'storage/img/products/';
		$filename = uniqid() . '_' . time() . '.' . $extension;
		$request->file('file')->move($dir, $filename);

		if (isset($request->mainImg)){
			ProductImages::where("product_id","=",$request->id)
				->update(['is_main'=>false]);
		}

		$image = new ProductImages;
		$image->image_url = $filename;
		$image->product_id = $request->id;
		$image->is_main = false;
		if (isset($request->mainImg)) $image->is_main = true;
		else $image->is_main = false;
		$image->save();
	}

	public function get_product(Request $request)
	{
		$response = array(); $i = 0; $lastPage=false;
		$data = array(); $products = ""; $linked = array();
		if ($request->mode === "get-all") {
			$page = $request->page-1;
			$limit = $request->limit;
			$total = Product::count();
			$lastPage = ceil($total / $limit);
			$from = $page * $limit;
			$products = Product::skip($from)->take($limit)->get();
		} else if ($request->mode === "Edit") {
			$products = Product::where("id","=",$request->id)->get();
		}else if ($request->mode === "search") {
			$products = Product::where("title","like","%{$request->search}%")->get();
		}
		//Additional data
		foreach ($products as $product) {
			$response[$i] = array();
			$response [$i]["product"] = $product;
			//Linked products
			if($product->has_linked == true){
				$linkedProducts = ProductLinked::select("linked_id","type")->where("product_id","=",$product->id)->get();
				$j = 0;
				foreach($linkedProducts as $linkedProduct){
					if($linkedProduct->type === "product"){
						$data = Product::select("catalog_products.id","title","image_url")
							->leftJoin("catalog_product_images","catalog_products.id","=","catalog_product_images.product_id")
							->where("is_main","=",true)
							->where("catalog_products.id","=",$linkedProduct->linked_id)->get();
					} else if($linkedProduct->type === "course"){
						$data = Course::select("title","course_image","id")->
							where("id","=",$linkedProduct->linked_id)
							->get();
					}else if($linkedProduct["type"] === "bundle"){
						$data = Bundle::select("title","course_image","id")->
							where("id","=",$linkedProduct->linked_id)
							->get();
					}else if($linkedProduct["type"] === "blog"){
						$data = Blog::select("title","category_id","slug","user_id","image","id")->
							where("id","=",$linkedProduct->linked_id)
							->get();
					}
					$data[0]["type"] = $linkedProduct->type;
					$linked[$j] = $data[0];
					$data = array();
					$j++;
				}
				$response[$i]["linked"] = $linked;
				$linked = array();
			}
			//Shipping
			if($product->is_online == false){
				$data = array();
				$shipping = ProductShipping::where("product_id","=",$product->id)
					->leftJoin('catalog_shipping', 'catalog_product_shipping.shipping_id', '=', 'catalog_shipping.id')
					->select('price','title','shipping_id')
					->get();
				$response[$i]["shipping"] = $shipping;
			}
			//Images
			$images = ProductImages::select("image_url","is_main","id")
				->where("product_id","=",$product->id);
			if($images->count() > 0){
				$response[$i]["gallery"] = $images->get();
				$main =  $images->where("is_main", "=",true);
				if($main->count() > 0){
					$response[$i]["mainImage"] = $main->get();
				}
			}
			//Categories
			$categories = ProductCategories::where("product_id","=",$product->id)
				->leftJoin('categories', 'catalog_product_categories.category_id', '=', 'categories.id')
				->where("type","product");
			if($categories->count() > 0){
				$response[$i]["categories"] = $categories->get();
			}
			$i++;
		}

		return response()->json([
			"data" => $response,
			"lastPage"=>$lastPage? $lastPage:""
		]);
	}
	public function delete_product(Request $request){
		$id = $request->id;
		ProductShipping::where("product_id","=",$id)->delete();
		ProductCategories::where("product_id","=",$id)->where("type","product")->delete();
		ProductLinked::where("product_id","=",$id)->delete();
		$images = ProductImages::where("product_id","=",$id)->get();
		foreach($images as $image){
			if(file_exists("storage/img/products/".$image->image_url))
			unlink("storage/img/products/".$image->image_url);
		}
		ProductImages::where("product_id","=",$id)->delete();
		$product = Product::destroy($id);
		ProductsList::where("product_id","=",$id)
			->where("type","=","product")
			->delete();
		return response()->json([
			"status"=>$product?true:false,
			"message"=>$product?"You have successfully delete product":"Error, refresh page and try again !"
		]);
	}

	public function delete_product_image(Request $request){
		$id = $request->id; $delete=""; $count="";
		$images = ProductImages::where("id","=",$id)->get();
		foreach($images as $image){
			if(file_exists("storage/img/products/".$image->image_url))
				unlink("storage/img/products/".$image->image_url);
			$delete = ProductImages::where("id","=",$id)->delete();
			$count = ProductImages::where("product_id","=",$image->product_id)->count();
		}
		return response()->json([
			"status"=>$delete?true:false,
			"count"=>$count
		]);
	}

	public function set_main_image(Request $request){
		$id = $request->id; $img="";
		$images = ProductImages::where("id","=",$id)->get();
		foreach($images as $image){
			ProductImages::where("product_id","=",$image->product_id)
				->update(['is_main'=>false]);
			$img = ProductImages::where("id","=",$image->id)
				->update(['is_main'=>true]);
		}
		return response()->json([
			"status"=>$img?true:false
		]);
	}
}