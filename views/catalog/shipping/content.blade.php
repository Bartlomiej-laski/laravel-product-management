<div class="row">
    <!-- Left column / Select shipping-->
    <div class="col-5">
        @if(!$shippingTemplate)
            <div class="d-flex mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="shipping-switch" id="shipping-switch" checked>
                    <label class="custom-control-label" for="shipping-switch">Online product</label>
                </div>
            </div>
        @endif
        <!-- Shipping list -->
        <ul class="list-group" id="shipping-list">
            <!-- Shipping -->
        </ul>
    </div>
    <!-- Right column / Add shipping -->
    <div class="col-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span id="manage-title">#MANAGE TITLE</span>
                <button class="btn btn-outline-primary d-none" id="edit-info" data-action="add-new-shipping-mode">add new</button>
            </div>
            <div class="card-body" id="shipping-manage-body">
                <div class="form-group">
                    <label for="shipping-title">Title</label>
                    <input type="text" class="form-control v-data v-required" id="shipping-title">
                </div>
                <div class="form-group">
                    <label for="shopping-description">Description</label>
                    <input type="text" class="form-control v-data v-required" id="shipping-description">
                </div>
                <!-- Select target region -->
                <div class="form-group">
                    <label for="shopping-region">Select region</label>
                    <select class="form-control" id="shipping-region" data-action="shipping-region-select">
                        "all","africa","antarctica","asia","europe","north-america","south-america","australia","other"
                        <option value="usa">USA</option>
                        <option value="all">All regions</option>
                        <option value="other">Select specific region</option>
                        <option value="africa">Africa</option>
                        <option value="antarctica">Antarctica</option>
                        <option value="asia">Asia</option>
                        <option value="europe">Europe</option>
                        <option value="north-america">North America</option>
                        <option value="south-america">South America</option>
                        <option value="australia">Australia</option>
                    </select>
                </div>
                <div class="form-group d-none" id="shipping-specific-region-section">
                    <button class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#add-region-modal">Add region</button>
                    <div class="alert alert-info" id="added-regions">

                    </div>
                </div>
                <!-- Select price type -->
                <div class="switches-container mt-2 mb-2">
                    <div class="d-flex align-items-center mr-3">
                        <label class="switch switch-label switch-primary">
                            <input class="switch-input" type="radio" name="shipping-price-type" id="constant-price-radio" data-action="price-type-handle" value="constant" checked>
                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                        Constant price
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="switch switch-label switch-primary">
                            <input class="switch-input" type="radio" name="shipping-price-type" data-action="price-type-handle" value="variable">
                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                        Variable price
                    </div>
                </div>
                <div id="shipping-price-type-sections">
                    <!-- Constant price settings -->
                    <div id="constant-shipping-price-settings" class="mt-3 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="constant-shipping-price-label">$</span>
                            </div>
                            <input type="number" class="form-control  v-data v-required" aria-describedby="constant-shipping-price-label" id="shipping-price" value="1">
                        </div>
                    </div>
                    <!-- Variable price settings -->
                    <div class="card mt-3 d-none" id="variable-shipping-price-settings">
                        <div class="card-header flex-center-between">
                            <div>Price Conditions </div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#add-condition-modal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body flush-card-body">
                            <!-- Conditions list -->
                            <table class="d-none table mb-0" id="condition-table">
                                <thead>
                                <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">+ / -</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="conditions-list">
                                <!-- Added conditions -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg" id="manage-shipping" data-action="manage-shipping" data-mode="" data-shipping="">Add shipping</button>
            </div>
        </div>
    </div>
</div>

<!--TEMPLATES-->
<!-- Existing shipping -->
<div id="shipping-list-template" class="d-none">
    <li class="list-group-item flex-center-between">
        @if(!$shippingTemplate)
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input product-shipping" name="product-shipping" value="#ShippingID">
                <label class="custom-control-label shipping-title" for=""></label>
            </div>
        @endif
        <div class="shipping-actions">
            <button class="btn btn-outline-info edit-shipping" data-toggle="tooltip" data-placement="top" title="Edit shipping" data-action="edit-shipping" data-shipping="#shippingID">
                <i class="fas fa-pen"></i>
            </button>
            <button class="btn btn-outline-danger delete-shipping" data-toggle="tooltip" data-placement="top" title="Delete shipping" data-action="delete-shipping" data-shipping="#shippingID">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </li>
</div>
<!-- Conditions -->
<table class="d-none">
    <tbody id="condition-template">
    <tr>
        <td class="condition-type">#conditionType</td>
        <td class="condition-value">#Value</td>
        <td class="condition-discount-type">#DiscountType</td>
        <td class="condition-discount-value">#DiscountValue</td>
        <td class="condition-delete" data-index="#conditionIndex"><i data-action="delete-shipping-condition" class="fas fa-trash text-danger"></i></td>
    </tr>
    </tbody>
</table>
<!-- Region template -->
<div id="region-list-template" class="d-none">
    <li class="list-group-item flex-center-between">
        <div>
            <img class="region-flag d-none" src="#regionFlag" alt="region-flag">
            <span class="region-name">#Region name</span>
        </div>
        <button class="btn btn-success add-region-btn" data-region="#regionID" data-regionName="#regionName" data-action="add-region"><i class="fas fa-plus"></i></button>
    </li>
</div>
<!-- Small region list -->
<div id="small-region-list-template" class="d-none">
    <div class="d-inline-flex sm-region-container" data-index="#tabIndex">
        <div class="sm-region-name mr-1">
            #Region name
        </div>
        <i class="fas fa-times text-danger pointer" data-action="delete-region"></i>
    </div>
</div>

<!-- MODALS -->
<!-- Add condition modal -->
<div class="modal fade" id="add-condition-modal" tabindex="-1" role="dialog" aria-labelledby="add-condition-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-condition-label">Add condition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="condition-form">
                <!-- Condition type -->
                <div class="form-group">
                    <label for="condition-type">Condition type</label>
                    <select class="form-control" id="condition-type-select" data-action="show-condition-type-section">
                        <option value="min-price">Min price</option>
                        <option value="max-price">Max price</option>
                        <option value="region">Region</option>
                        <option value="role">Role</option>
                    </select>
                    <div class="form-group mt-3 mb-3" id="condition-type-sections">
                        <!-- Min condition -->
                        <div class="input-group condition-type-section" id="condition-type-min-price-section">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="condition-type-min-label">Min $</span>
                            </div>
                            <input type="number" class="form-control  v-data v-required" aria-describedby="condition-type-min-label" id="condition-min-price">
                        </div>
                        <!-- Max condition -->
                        <div class="input-group d-none condition-type-section" id="condition-type-max-price-section">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="condition-type-max-label">Max $</span>
                            </div>
                            <input type="number" class="form-control" aria-describedby="condition-type-max-label" id="condition-max-price">
                        </div>
                        <!-- Region select -->
                        <div class="d-none condition-type-section" id="condition-type-region-section">
                            <select class="form-control" id="condition-region-select">
                                <!-- Regions list -->
                            </select>
                        </div>
                        <!-- Role select -->
                        <div class="d-none condition-type-section" id="condition-type-role-section">
                            <select class="form-control" id="condition-role-select">
                                @foreach($roles as $role)
                                    <option value='{{$role->id}}'>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <h2 class="mb-2">Discount</h2>
                    <div class="row">
                        <div class="col">
                            <!-- Select price type -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="shipping-discount-type" id="add-to-price-radio" value="add" checked>
                                <label class="form-check-label" for="add-to-price-radio">Add to shipping price</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="shipping-discount-type" id="subtract-from-price-radio" value="subtract">
                                <label class="form-check-label" for="subtract-from-price-radio">Subtract from shipping price</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="shipping-discount-label">%</span>
                                </div>
                                <input type="number" class="form-control v-data v-required" aria-describedby="shipping-discount-label" id="shipping-discount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-action="add-shipping-condition">Add condition</button>
            </div>
        </div>
    </div>
</div>
<!-- Region Modal -->
<div class="modal fade" id="add-region-modal" tabindex="-1" role="dialog" aria-labelledby="add-region-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-region-modal-label">Add region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="region-search"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search" aria-describedby="region-search" data-action="region-search">
                </div>
                <ul class="list-group list-group-flush position-relative" id="modal-regions-list">
                    <!-- Regions list -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
