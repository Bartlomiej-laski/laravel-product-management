@extends('backend.layouts.app')
@section('title','Featured products')

@push('after-styles')
    <link rel="stylesheet" href="{{asset('css/common/common.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/backend/catalog/productsList.css')}}"/>
    <!--<link rel="stylesheet" href="{{asset('css/includes/bootstrap/css/bootstrap.css')}}">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">
            All products
        </div>
        <div class="card-body" id="main-body">
            <div class="row">
                <div class="col-7">
                    <div class="d-flex align-items-center">
                        Show:
                        <div class="mx-sm-3">
                            <select class="form-control ml-2" data-action="set-limit">
                                <option>10</option>
                                <option>20</option>
                                <option>30</option>
                                <option>40</option>
                                <option>50</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="search-addon"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" data-action="search" placeholder="Search" aria-describedby="search-addon">
                    </div>
                </div>
            </div>
            <table id="products-list-table" class="position-relative table table-striped table-bordered">
                <thead>
                <tr>
                    <th><i class="fas fa-image"></i></th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Categories</th>
                    <th>Published on</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="products-list-body">
                    <!-- Products list table template -->
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <ul class="pagination justify-content-end" id="products-list-pagination">
                <!-- AJAX Pagination -->
            </ul>
        </div>
    </div>

    <!-- Products list table template -->
    <table class="d-none">
        <tbody  id="list-product-template">
        <tr>
            <td><img class="main-image" src="#Main-image" alt="img"/></td>
            <td class="product-name">#Name</td>
            <td class="stock">#Stock</td>
            <td class="price">#Price</td>
            <td class="categories">#Categories</td>
            <td class="date">#Date</td>
            <td class="product-actions">
                <a class="btn btn-outline-primary edit-product" href="">
                    <i class="fas fa-pen"></i>
                </a>
                <button class="btn btn-outline-success" data-action="toggle-description">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <button class="btn btn-outline-danger delete-product" data-action="delete-product" data-id="#product-id">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <tr class="hidden-description d-none">
            <td colspan="7">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Additional info
                            </div>
                            <div class="card-body">
                                <p>Slug: <span class="product-slug">#slug</span></p>
                                <p>Sale price: <span class="product-sale-price">#sale_price</span></p>
                                <p>Discount: <span class="product-discount">#discout</span></p>
                                <p>Status: <span class="product-status">#status</span></p>
                                <p>Visibility: <span class="product-visibility">#visibility</span></p>
                                <p>Draft: <span class="product-draft">#is_draft</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Product Gallery
                            </div>
                            <div class="card-body gallery-body">
                                #Product gallery
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                Shipping
                            </div>
                            <div class="card-body shipping-body">
                                #SHIPPING
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Linked products
                            </div>
                            <ul class="list-group list-group-flush linked-body">
                                #LINKED PRODUCTS
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h2>Description</h2>
                        <span class="product-description">#ProductDescription</span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="d-none"></tr>
        </tbody>
    </table>
    <!--Linked product template -->
    <div class="d-none" id="linked-product-template">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="linked-title">#TITLE</div>
            <button class='btn btn-outline-primary type-btn'>#TYPE</button>
        </li>
    </div>
@endsection
@push('after-scripts')
    <script>
	    Loading.start("#products-list-body");
    </script>
    <script src="{{asset("js/backend/catalog/product/list.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/common/pagination.js")}}" type="text/javascript"></script>
    <script>
		$(window).on('load', function(){
			ProductsList.init();
		});
    </script>
@endpush