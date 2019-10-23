<div class="card">
    <div class="card-header">
        Publish
    </div>
    <div class="card-body" id="published-settings">
        <!-- Status -->
        <div>Status:
            <span class="product-status" id="product-status">
                @if($mode === "Add")
                    Draft
                @else
                    Published
                @endif
            </span>
            @if($mode === "Edit")
                <!--
                <button class="small-button" data-action="change-status">
                    <i class="fas fa-pen"></i>
                </button>
                -->
            @endif
        </div>
        <!-- Visibility -->
        <div class="d-flex align-items-center">
            Visibility:
            <span class="product-visibility" id="product-visibility">Public</span>
            <button class="small-button" data-toggle="modal" data-target="#product-visibility-modal">
                <i class="fas fa-pen"></i>
            </button>
        </div>
        <!-- Published date -->
        <div class="d-flex align-items-center">
            <label for="published-date" class="mb-0">Published on:</label>
            <div>
                <input type="text" id="published-date" value="{{date("Y-m-d")}}">
            </div>
        </div>
    </div>
    <div class="card-footer publish-card-footer">
        <div>
            <p class="text-primary" style="cursor:pointer" data-action="publish-product" data-mode="draft"> Create new draft</p>
            <p class="text-danger d-none">Move to trash</p>
        </div>
        <div>
            <button class="btn btn-primary" id="publish-button" data-action="publish-product" data-mode="publish">Publish</button>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Product categories
    </div>
    <div class="card-body product-categories">
        <div class="categories-list">
            @foreach($categories as $category)
                <div>
                    <input type="checkbox" value="{{$category->id}}" name="product-category" id="category-{{$category->id}}">
                    <label for="category-{{$category->id}}">{{$category->name}}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Product Gallery
    </div>
    <div class="card-body" id="image-list">
        <input class="btn btn-outline-primary btn-block" type="file" id="upload-image"
               name="upload_images[]" data-action="upload-images" multiple/>
        <div id="image-preview-container"></div>
    </div>
</div>

<!-- Image template -->
<div class="d-none" id="gallery-images-template">
    <div class="d-flex">
        <img class="img-preview" src="" alt="thumb" data-index='' data-action="select-main-image">
        <div class="img-button-container">
            <button class="btn btn-outline-info btn-main-image" data-id="#inEditMode" data-action="select-main-image" data-mode="new"><i class="fas fa-image"></i></button>
            <button class="btn btn-outline-danger btn-delete" data-id="#inEditMode" data-action="delete-product-image" data-mode="new"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>

<!-- Visibility modal -->
<div class="modal fade" id="product-visibility-modal" tabindex="-1" role="dialog" aria-labelledby="product-visibility-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="product-visibility-modal-label">Set visibility</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="visibility-control">Select target:</label>
                <select class="form-control" id="visibility-control" data-action="visibility-control">
                    <option value="public">public</option>
                    <option value="private">private</option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>