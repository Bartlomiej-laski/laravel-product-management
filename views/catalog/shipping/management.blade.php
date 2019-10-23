@extends('backend.layouts.app')
@section('title','Featured products')

@push('after-styles')
    <link rel="stylesheet" href="{{asset('css/common/common.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/backend/catalog/productManagement.css')}}"/>
@endpush
@section('content')
<div class="card">
    <div class="card-header">
        Shipping
    </div>
    <div class="card-body">
        @include("backend/catalog/shipping/content")
    </div>
</div>
@endsection
@push('after-scripts')
    <script src="{{asset("js/backend/catalog/product/shipping.js")}}" type="text/javascript"></script>
    <script>
        Loading.start("#shipping-list");
		$(window).on('load', function(){
			Shipping.init();
		});
    </script>
@endpush
