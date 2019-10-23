ProductManagement = {
	//Arrays
	linkedProducts:[],
	images:[],
	isImages:false,
	mainImage:0,
	manageMode: "Add",
	editID: "",
	init: function(){
		ProductManagement.manageMode = $("#manage-mode").val();
		if(ProductManagement.manageMode === "Edit"){
			ProductManagement.editID = $("#edit-product-id").val();
			ProductManagement.get_product_data();
		}
		$(document).delegate("[data-action=fp-add-linked-product]","click",this.fp_add_linked_product);
		$(document).delegate("[data-action=fp-delete-linked-product]","click",this.fp_delete_linked_product);
		$(document).delegate('[data-action=upload-images]', 'change', this.image_preview);
		$(document).delegate("[data-action=select-main-image]","click",this.select_main_image);
		$(document).delegate("[data-action=visibility-control]","change",this.set_visibility);
		$(document).delegate("[data-action=publish-product]","click", this.publish_product);
		$(document).on("change","#product-title",this.set_slug);
		$(document).delegate('[data-action=handle-unit-switch]','change',this.handle_unit_switch);
		$(document).delegate("[data-action=change-status]","click",this.change_status);
		$(document).delegate("[data-action=delete-product-image]","click",this.delete_product_image);
		this.ready();
	},
	get_product_data(){
		$.ajax({
			type:"GET",
			url:"/user/ajax/catalog/get-product",
			data:{
				mode: ProductManagement.manageMode,
				id: ProductManagement.editID,
			},
			success: function(response){
				//console.log(response.data);
				ProductManagement.insert_product_data(response.data);
			}
		});
	},
	insert_product_data: function(response){
		let product = response[0].product;
		$("#product-title").val(product.title);
		$("#product-slug").val(product.slug);
		$("#product-description").val(product.description);
		$("#general-price").val(product.price);
		$("#price-discount").val(product.discount);
		$("#sale-price").val(product.sale_price);
		$("#product-units").val(product.units);
		$("#published-date").val(product.published_on);
		ProductManagement.set_status(product.status);
		$("#product-visibility").html(product.visibility);
		product.has_linked? $("#linked-product-switch").prop("checked",true):$("#linked-product-switch").prop("checked",false);
		product.is_online? $("#shipping-switch").prop("checked",true):$("#shipping-switch").prop("checked",false);
		product.is_unlimited? $("#unlimited-switch").prop("checked",true):$("#unlimited-switch").prop("checked",false);
		product.trending? $("#trending-switch").prop("checked",true):$("#trending-switch").prop("checked",false);

		//Categories
		let categories = response[0].categories;
		$.each(categories, function(index,value){
			$("input[name=product-category][value='"+value.category_id+"']").prop("checked",true);
		});
		//Linked products
		let linked = response[0].linked;
		$.each(linked,function(index,value){
			let linkedProduct=[];
			if(value.image){
				linkedProduct["image"] = value.image;
			}else{
				linkedProduct["image"] = value.course_image;
			}
			linkedProduct["title"] = value.title;
			linkedProduct["type"] = value.type;
			linkedProduct["id"] = value.id;
			ProductManagement.linkedProducts.push(linkedProduct);

			/*console.log(value.course_image);
			console.log(appConfig.imagesURL);
			console.log(appConfig.imagesURL+value.course_image);
			console.log(linkedProduct["image"]);*/
		});
		ProductManagement.fp_show_linked_products(ProductManagement.linkedProducts);
		//Shipping
		let shipping = response[0].shipping;
		setTimeout(function(){
			$.each(shipping, function(index,value){
				$("input[name=product-shipping][value='"+value.shipping_id+"']").prop("checked",true);
			});
		},200);
		//Images
		let gallery = response[0].gallery;
		let i=0;
		let T = $("#gallery-images-template");
		let list = $("#image-preview-container");
		if(gallery.length > 0) ProductManagement.isImages = true;
		else ProductManagement.isImages = false;
		$.each(gallery,function(index,value){
			T.find(".img-preview")
			.attr("data-index",i)
			.attr("src",appConfig.imagesURL+value.image_url);
			//Actions
			T.find(".btn-delete").attr("data-mode","exist").attr("data-id",value.id);
			T.find(".btn-main-image").attr("data-mode","exist").attr("data-id",value.id);
			//Main image
			if(value.is_main == true) T.find(".img-preview").addClass("main-image");
			list.append(T.html());
			if(value.is_main == true) T.find(".img-preview").removeClass("main-image");
		});
		Loading.stop("#product-body");
	},
	publish_product: function(){
		if(Validation.simple("#product-body",".v-product")){
			let status;
			let isDraft = $(this).attr("data-mode") === "draft";
			if(isDraft) status = "draft";
			else status = "published";
			//Product table
			let productData = {
				manageMode: ProductManagement.manageMode,
				editID: ProductManagement.editID,
				title:	$("#product-title").val(),
				slug: $("#product-slug").val(),
				description: $("#product-description").val(),
				price: $("#general-price").val(),
				discount: $("#price-discount").val(),
				salePrice: $("#sale-price").val(),
				units: $("#product-units").val(),
				publishedDate: $("#published-date").val(),
				status: status,
				visibility: $("#product-visibility").html(),
				hasLinked: $("#linked-product-switch").prop("checked") ? true : false,
				isOnline: $("#shipping-switch").prop("checked") ? true : false,
				isUnlimited: $("#unlimited-switch").prop("checked") ? true : false,
				isTrending: $("#trending-switch").prop("checked") ? true : false,
				isDraft: isDraft
			};
			productData.shipping = [];
			let shipping = []; 	let categories = []; let linkedProducts = [];  let count=0;

			//Select categories
			$("input:checkbox[name=product-category]:checked").each(function() {
				categories.push($(this).val());
				count++;
			});
			//Categories validate
			if(count === 0){
				Messages.fixed(false,"Add category!");
				return false;
			}
			productData.categories = categories;

			//Shipping
			if(productData.isOnline === false){
				let count =0;
				$("#shipping-list .product-shipping").each(function() {
					if($(this).prop("checked")){
						shipping.push($(this).val());
						count++;
					}
				});
				if(count === 0){
					Messages.fixed(false,"Add any shipping!");
					return false;
				}
				productData.shipping = shipping;
			}
			//Linked products
			if(productData.hasLinked === true){
				count = 0;
				$.each(ProductManagement.linkedProducts,function(index,value){
					linkedProducts[index]=value.type+","+value.id;
					count ++;
				});
				productData.linkedProducts = linkedProducts;
			}

			//Validate images
			if(ProductManagement.images.length === 0 && ProductManagement.isImages === false){
				Messages.fixed(false,"Add any image");
				return false;
			}

			$.ajax({
				type:"post",
				url:"/user/ajax/catalog/add-product",
				data: productData,
				success: function(response){
					Messages.fixed(response.status,response.message);
					if(response.status === true && response.id){
						ProductManagement.add_product_images(response.id,ProductManagement.mainImage);
						setTimeout(function(){
							window.location.href = '/user/catalog/product-management/'+response.id;
						},200);
					}
				}
			});
		}
	},
	add_product_images: function(id,mainImage){
		let i = 0;
		$.each(ProductManagement.images,function(index,value){
			let form_data = new FormData();
			form_data.append('file', value);
			form_data.append("id",id);
			if(i == mainImage) form_data.append("mainImg",mainImage);
			$.ajax({
				type:"post",
				url:"/user/ajax/catalog/add-product-images",
				contentType: false,
				processData: false,
				data: form_data
			});
			i++;
		});
	},
	//Featured Products
	//Add linked product
	fp_add_linked_product: function(){
		let product=[];
		product["image"] = $(this).find("img").attr("src");
		product["title"] = $(this).find(".fp-product-title").html();
		product["type"] = $(this).attr("data-type");
		product["id"] = $(this).attr("data-product");
		ProductManagement.linkedProducts.push(product);
		ProductManagement.fp_show_linked_products(ProductManagement.linkedProducts);
	},
	//Show linked products
	fp_show_linked_products: function(data){
		if(data.length > 0) $("#linked-alert").addClass("d-none");
		else if(data.length === 0) $("#linked-alert").removeClass("d-none");

		let list = $("#linked-products-list");
		list.empty();
		let T = $("#fp-linked-product-template");
		$.each(data,function(index,value){
			let img; let noImage=false;
			if(value.course_image) img = value.course_image;
			else if(value.image) img = value.image;
			else if(value.image_url) img = value.image_url;
			else noImage =true;
			T.find(".fp-product-title").html(value.title);
			if(noImage === false) T.find(".fp-product-image").attr("src",value.image).removeClass("d-none");
			else T.find(".fp-product-image").addClass("d-none");
			T.find(".fp-product-type").html(value.type);
			T.find(".fp-delete-product").attr("data-index",index);
			list.append(T.html());
		});
	},
	fp_delete_linked_product: function () {
		let index = $(this).attr("data-index");
		ProductManagement.linkedProducts.splice(index,1);
		ProductManagement.fp_show_linked_products(ProductManagement.linkedProducts);
	},
	//Gallery, image preview
	image_preview:function(){
		let input = this;
		let files = input.files;
		$.each(files,function(index,value){
			ProductManagement.images.push(value);
		});
		let T = $("#gallery-images-template");
		let list = $("#image-preview-container");
		if (input.files) {
			ProductManagement.isImages = true;
			let filesAmount = input.files.length;
			for (let i = 0; i < filesAmount; i++) {
				let reader = new FileReader();
				let image = T.find(".img-preview");
				let btnDelete = T.find(".btn-delete");
				let btnMainImage = T.find(".btn-main-image");
				reader.onload = function(event) {
					if(i === 0 && ProductManagement.manageMode !== "Edit") image.addClass("main-image");
					//T.find("div").css("order",i);
					image.attr("src",event.target.result);
					image.attr("data-index",i);
					btnDelete.attr("data-id",i).attr("data-mode","new");
					btnMainImage.attr("data-id",i).attr("data-mode","new");
					list.prepend(T.html());
					if(i === 0 && ProductManagement.manageMode !== "Edit")  image.removeClass("main-image");
				};
				reader.readAsDataURL(input.files[i]);
			}
		}else ProductManagement.isImages = false;
	},
	delete_product_image: function(){
		if($(this).attr("data-mode") === "exist") {
			if(confirm("Are you sure?")){
				let btn = $(this);
				let id = $(this).attr("data-id");
				$.ajax({
					type:"get",
					url:"/user/ajax/catalog/delete-product-image",
					data:{
						id: id
					},
					success: function(response){
						if(response.status === true){
							btn.parent().parent().remove();
						}
						if(response.count > 0) ProductManagement.isImages = true;
						else ProductManagement.isImages = false;
					}
				});
			}
		}else if($(this).attr("data-mode") === "new"){
			ProductManagement.images.splice($(this).attr("data-id"),1);
			$(this).parent().parent().remove();
			if(ProductManagement.images.length > 0) ProductManagement.isImages = true;
			else ProductManagement.isImages = false;
		}
	},
	select_main_image: function(){
		let mode = $(this).attr("data-mode");
		if(mode === "new"){
			ProductManagement.mainImage = $(this).attr("data-id");
			$(".img-preview").removeClass("main-image");
			$(this).parent().parent().find(".img-preview").addClass("main-image");
		}else if(mode === "exist"){
			let btn = $(this);
			$.ajax({
				type:"put",
				url:"/user/ajax/catalog/set-main-image",
				data: {
					id: $(this).attr("data-id")
				},
				success: function(response){
					if(response.status === true){
						ProductManagement.mainImage = "";
						$(".img-preview").removeClass("main-image");
						btn.parent().parent().find(".img-preview").addClass("main-image");
					}
				}
			})
		}

	},
	//Visibility
	set_visibility:function(){
		let select = $("#visibility-control option:selected");
		$("#published-settings").find(".product-visibility").html(select.html());
	},
	ready: function(){
		let date = new Date();
		let publishedControl = $("#published-date");
		publishedControl.datepicker({
			dateFormat: "yy-m-d",
			setDate: date,
			showOn: "both",
			buttonText: "<i class='fas fa-calendar'></i>",
		});
	},
	set_slug:function(){
		let str = $(this).val();
		let trimmed = $.trim(str);
		let slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
		replace(/-+/g, '-').
		replace(/^-|-$/g, '');
		$("#product-slug").val(slug.toLowerCase());
	},
	handle_unit_switch: function(){
		let target = $("#product-units");
		let classy = "v-product v-required";
		if($(this).prop("checked") === true){
			target.hasClass(classy)? target.removeClass(classy):"";
		} else{
			!target.hasClass(classy)?target.addClass(classy):"";
		}
	},
	change_status: function(){
		let btn = $("#publish-button");
		if(btn.attr("data-mode") === "publish"){
			$("#product-status").html("Draft");
			btn.attr("data-mode","draft");
		}else if(btn.attr("data-mode") === "draft"){
			$("#product-status").html("Published");
			btn.attr("data-mode","publish");
		}
	},
	set_status: function(status){
		let html = $("#product-status");
		if(status === "published"){
			$("#product-status").html("Published");
		}else if(status === "draft"){
			$("#product-status").html("Draft");
		}
	}
};