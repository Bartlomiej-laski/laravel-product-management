ProductsList = {
	searchBreak: false,
	limit:10,
	page:1,
	init: function(){
		ProductsList.get_products(1,"get-all");

		$(document).delegate("[data-action=delete-product]","click",this.delete_product);
		$(document).delegate("[data-action=toggle-description]","click",this.toggle_description);

		$(document).delegate('[data-action=pagination]', 'click', this.pagination_controller);
		$(document).delegate('[data-action=search]', 'keyup', this.search_controller);
		$(document).delegate('[data-action=set-limit]','change',this.set_limit);
	},
	set_limit: function(){
		Loading.start("#products-list-body");
		ProductsList.limit = $(this).val();
		ProductsList.get_products(1,"get-all");
	},
	search_controller: function(){
		let value = $(this).val();
		if(value.length > 2 && ProductsList.searchBreak === false){
			Loading.start("#products-list-body");
			ProductsList.searchBreak = true;

			$("#products-list-pagination").empty();
			ProductsList.get_products(ProductsList.page,"search",value);

			setTimeout(function(){
				ProductsList.searchBreak = false;
			},200);
		}else if(value.length === 0){
			Loading.start("#products-list-body");
			ProductsList.get_products(ProductsList.page,"get-all");
		}
	},
	pagination_controller:function(){
		ProductsList.page = $(this).data("page");
		ProductsList.get_products($(this).data("page"),$(this).data("mode"));
	},
	get_products: function(page,mode,search){
		$.ajax({
			type:"GET",
			url:"/user/ajax/catalog/get-product",
			data:{
				mode: mode,
				page:page,
				limit:ProductsList.limit,
				search:search?search:"",
			},
			success: function(response){
				if(response.data.length > 0){
					ProductsList.clear_table();
					ProductsList.create_table(response.data);
					if(mode !== "search")Pagination.show("#products-list-pagination",mode,response.lastPage,page);
				}else{
					Messages.alert("#main-body",'info',"No products");
					Loading.stop("#products-list-body");
				}
			}
		})
	},
	create_table: function(response) {
		let T = $("#list-product-template");
		let list = $("#products-list-body");

		$.each(response, function (index,value) {
			let product,linkedProducts,shipping,categories,mainImage,gallery,price;
			product = value.product;
			if(value.linked) linkedProducts = value.linked;
			if(value.shipping) shipping = value.shipping;
			if(value.mainImage) mainImage = value.mainImage[0].image_url;
			if(value.categories) categories = value.categories;
			if(value.gallery) gallery = value.gallery;

			T.find(".main-image").attr("src",appConfig.imagesURL+mainImage);
			T.find(".product-name").html(product.title);
			//Units
			if(product.is_unlimited == true) T.find(".stock").html("unlimited");
			else T.find(".stock").html(product.units);
			//Price
			if(product.discount || product.sale_price){
				price = "<span class='old-price'>"+product.price+"</span> "+product.total_price;
			}else price = product.price;

			T.find(".price").html(price);

			//Categories
			T.find(".categories").html("");
			$.each(categories,function(index,value){
				T.find(".categories").append(value.name+", ");
			});
			T.find(".date").html(product.published_on);
			//Actions
			T.find(".delete-product").attr("data-id",product.id);
			T.find(".edit-product").attr("href","/user/catalog/product-management/"+product.id);

			//ADDITIONAL INFO
			if(product.description) T.find(".product-description").html(product.description);
			else T.find(".product-description").html("No description");
			T.find(".product-slug").html(product.slug);
			if(product.sale_price) T.find(".product-sale-price").html(product.sale_price);
			else T.find(".product-sale-price").html("none");
			if(product.discount) T.find(".product-discount").html(product.discount);
			else T.find(".product-discount").html("none");
			T.find(".product-status").html(product.status);
			T.find(".product-visibility").html(product.visibility);
			T.find(".product-draft").html(product.is_draft);

			//Shipping
			T.find(".shipping-body").empty();
			if(product.is_online != true){
				$.each(shipping,function(index,value){
					T.find(".shipping-body").append("<p>"+value.title+" "+value.price+"$</p>");
				});
			}else {
				T.find(".shipping-body").html("Online product");
			}
			//Linked products
			T.find(".linked-body").empty();
			let SList = T.find('.linked-body');
			let ST = $("#linked-product-template");
			$.each(linkedProducts, function(index,value){
				ST.find(".type-btn").html(value.type);
				ST.find(".linked-title").html(value.title);
				SList.append(ST.html());
			});
			//Gallery
			T.find(".gallery-body").empty();
			$.each(gallery,function(index,value){
				let img = "<img style='width:50px;' alt='gallery-image' src='"+appConfig.imagesURL+value.image_url+"'>";
				T.find(".gallery-body").append(img);
			});
			list.append(T.html());
		});
		//ProductsList.data_table();
		Loading.stop("#products-list-body");
	},
	delete_product: function(){
		if(confirm("Are you sure ?")){
			let ID = $(this).attr("data-id");
			$.ajax({
				type:"get",
				url:"/user/ajax/catalog/delete-product",
				data:{
					id:ID
				},
				success: function(response){
					Loading.start("#products-list-body");
					Messages.fixed(response.status,response.message);
					ProductsList.get_products(ProductsList.page,"get-all")
				}
			});
		}
	},
	toggle_description: function(){
		let desc = $(this).parent().parent().next(".hidden-description");
		if(desc.hasClass("d-none")){
			$(this).find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");
			desc.removeClass("d-none");
		} else{
			$(this).find("i").removeClass("fa-chevron-up").addClass("fa-chevron-down");
			desc.addClass("d-none");
		}
	},
	data_table: function() {
		$(document).ready(function() {
			$('#products-list-body').DataTable({
				"paging":   false,
				"ordering": false,
				"info":     false
			});
		} );
	},
	clear_table:function(){
		$("#products-list-body").empty();
	}
};