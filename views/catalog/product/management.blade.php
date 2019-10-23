@extends('backend.layouts.app')
@section('title','Product management')

@push('after-styles')
    <link rel="stylesheet" href="{{asset('css/common/common.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/backend/catalog/productManagement.css')}}"/>
@endpush
@section('content')
    <input type="hidden" id="manage-mode" value="{{$mode}}">
    <input type="hidden" id="edit-product-id" value="{{$editID}}">
    <div class="row" id="product-body">
        <div class="col-9">
            <!-- Title, slug, description -->
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="page-title mb-0">{{$mode}} product</h3>
                    @if($mode === "Edit")
                        <a class="btn btn-info" href="/user/catalog/product-management">Add new product</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="product-title">Title</label>
                        <input type="text" class="form-control v-product v-required" id="product-title">
                    </div>
                    <div class="form-group">
                        <label for="product-slug">Slug</label>
                        <input type="text" class="form-control v-product v-required" id="product-slug" disabled>
                    </div>
                    <div class="form-group">
                        <label for="product-description">Product Description</label>
                        <textarea class="form-control v-product v-required" id="product-description"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
           @include("backend/catalog/product/sidebar")
        </div>
        <div class="col-12">
            <!-- Linked products, shipping, extra content -->
            <div class="card">
                <div class="card-header">
                    Extra information's
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="list-group" id="list-tab" role="tablist">
                                <a class="list-group-item list-group-item-action active" id="list-generalSettings-list" data-toggle="list" href="#list-generalSettings" role="tab" aria-controls="generalSettings">General settings</a>
                                <a class="list-group-item list-group-item-action" id="list-shipping-list" data-toggle="list" href="#list-shipping" role="tab" aria-controls="shipping">Shipping</a>
                                <a class="list-group-item list-group-item-action" id="list-linked-products-list" data-toggle="list" href="#list-linked-products" role="tab" aria-controls="linked-products">Linked Products</a>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="list-generalSettings" role="tabpanel" aria-labelledby="list-generalSettings-list">
                                    @include("backend/catalog/product/generalSettings")
                                </div>
                                <div class="tab-pane fade" id="list-shipping" role="tabpanel" aria-labelledby="list-shipping-list">
                                    @include("backend/catalog/shipping/content")
                                </div>
                                <div class="tab-pane fade" id="list-linked-products" role="tabpanel" aria-labelledby="list-linked-products-list">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex mb-3">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="linked-products-switch" id="linked-product-switch">
                                                    <label class="custom-control-label" for="linked-product-switch">Turn on linked product</label>
                                                </div>
                                            </div>
                                            <div class="alert alert-info" id="linked-alert">There is no product, add something</div>
                                            <ul class="list-group" id="linked-products-list">
                                                <!-- Linked products list -->
                                            </ul>
                                        </div>
                                        <div class="col">
                                            @include("backend/cms/featuredProductsList")
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Templates -->
    <!-- Linked product -->
    <div id="fp-linked-product-template" class="d-none">
        <li href="#" class="list-group-item ui-state-default flex-center-between">
            <div>
                <img src="" class="fp-product-image">
                <span class="fp-product-title">#Product title</span>
            </div>
            <div class="actions">
                <button class="btn btn-outline-info fp-product-type">#Product type</button>
                <button class="btn btn-outline-danger fp-delete-product" data-index="#arrayIndex" data-action="fp-delete-linked-product"><i class="fas fa-trash"></i></button>
            </div>
        </li>
    </div>
@endsection
@push('after-scripts')
    @if($mode === "Edit")
        <script>
		    Loading.start("#product-body");
        </script>
    @endif
    <script src="{{asset("js/backend/catalog/product/management.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/backend/catalog/product/shipping.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/common/pagination.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/backend/cms/featuredProductsList.js")}}" type="text/javascript"></script>
    <script>
		$(window).on('load', function(){
			ProductManagement.init();
			Shipping.init();
            FpList.init();
		});
    </script>
@endpush
