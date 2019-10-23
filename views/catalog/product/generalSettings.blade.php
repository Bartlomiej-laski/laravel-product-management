<div class="form-group row">
    <label for="general-price" class="col-sm-2 col-form-label">General price</label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="price-addon">$</span>
            </div>
            <input type="number" class="form-control v-product v-required v-nt" id="general-price" aria-describedby="price-addon">
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="general-price" class="col-sm-2 col-form-label">Discount</label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="discount-addon">%</span>
            </div>
            <input type="number" class="form-control" id="price-discount" aria-describedby="discount-addon">
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="sale-price" class="col-sm-2 col-form-label">Sale price</label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="sale-addon">$</span>
            </div>
            <input type="number" class="form-control" id="sale-price" aria-describedby="sale-addon">
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="custom-control custom-switch unlimited-switch">
        <input type="checkbox" data-action="handle-unit-switch" class="custom-control-input" name="unlimited-switch" id="unlimited-switch" checked>
        <label class="custom-control-label" for="unlimited-switch">Unlimited units</label>
    </div>
    <div class="input-group">
        <label for="product-storage" class="col-sm-2 col-form-label">Units</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="product-units">
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="custom-control custom-switch unlimited-switch">
        <input type="checkbox"  class="custom-control-input" name="trending-switch" id="trending-switch">
        <label class="custom-control-label" for="trending-switch">Trending</label>
    </div>
</div>