Shipping = {
	searchBreak: false,
	regions: [],
	conditions: [],
	manageMode: "Add new shipping",

	init: function(){
		$(document).delegate('[data-action=price-type-handle]',"click", this.toggle_variable_price);
		$(document).delegate('[data-action=show-condition-type-section]',"change", this.show_condition_type_section);
		$(document).delegate('[data-action=shipping-region-select]',"change", this.handle_region_select);
		$(document).delegate('[data-action=add-region]',"click", this.add_region);
		$(document).delegate('[data-action=delete-region]',"click", this.delete_region);
		$(document).delegate('[data-action=region-search]',"keyup",this.search_region);
		$(document).delegate('[data-action=add-shipping-condition]',"click",this.add_shipping_condition);
		$(document).delegate('[data-action=delete-shipping-condition]',"click",this.delete_shipping_condition);
		$(document).delegate('[data-action=manage-shipping]',"click",this.manage_shipping);
		$(document).delegate("[data-action=edit-shipping]","click",this._edit_shipping);
		$(document).delegate("[data-action=delete-shipping]","click",this.delete_shipping);
		$(document).delegate("[data-action=add-new-shipping-mode]","click",this.add_shipping_mode);

		this.set_manage_mode();
		this.get_shipping();
		this.get_regions();
	},
	//CONTROLLERS
	//Edit or add shipping
	manage_shipping: function(){
		if($(this).attr("data-mode") === "Add new shipping") Shipping.add_shipping();
		else if($(this).attr("data-mode") === "Edit shipping"){
			let shippingID = $(this).attr("data-shipping");
			Shipping.edit_shipping(shippingID);
		}
	},
	set_manage_mode: function(){
		//Set card button and title
		$("#manage-title").html(Shipping.manageMode);
		$("#manage-shipping").attr("data-mode",Shipping.manageMode).html(Shipping.manageMode);
		let obj = $("#edit-info");
		if(Shipping.manageMode === "Edit shipping"){
			if(obj.hasClass("d-none")) obj.removeClass("d-none");
			Shipping.clear_form();
			Shipping.toggle_variable_price();
			Shipping.clear_shipping_arrays();
		}else if(Shipping.manageMode === "Add new shipping"){
			if(!obj.hasClass("d-none")) obj.addClass("d-none");
			Shipping.clear_form();
			Shipping.toggle_variable_price();
			Shipping.clear_shipping_arrays();
		}
	},
	add_shipping_mode: function(){
		Shipping.manageMode = "Add new shipping";
		Shipping.set_manage_mode();
	},
	//SHIPPING
	//Get existing shipping
	get_shipping: function(){
		Loading.start("#shipping-list");
		$.ajax({
			type:"GET",
			url:"/user/ajax/catalog/get-shipping",
			success: function(data){
				Shipping.show_shipping(data.data);
			}
		});
	},
	//Show existing shipping
	show_shipping: function(data){
		if(data.length > 0){
			let list = $("#shipping-list");
			let T = $("#shipping-list-template");
			list.empty();
			$.each(data,function(index,value){
				T.find(".product-shipping").val(value.id).attr("id","shipping"+value.id);
				T.find(".shipping-title").attr("for","shipping"+value.id).html(value.title);
				T.find(".edit-shipping").attr("data-shipping",value.id);
				T.find(".delete-shipping").attr("data-shipping",value.id);
				list.append(T.html());
			});
		}else{
			Messages.alert("#shipping-list","info","No data");
		}
		Loading.stop("#shipping-list");
	},
	//Get shipping data
	_edit_shipping: function(){
		let shippingID = $(this).attr("data-shipping");
		Shipping.manageMode = "Edit shipping";
		Shipping.set_manage_mode();
		$("#manage-shipping").attr("data-shipping",shippingID);
		$.ajax({
			type:"GET",
			url:"/user/ajax/catalog/get-shipping",
			data:{
				id:shippingID
			},
			success: function(data){
				Shipping.data_manage(data.data);
			}
		});
	},
	//Insert shipping data
	data_manage:function(data) {
		let shipping = data.shipping;
		$("#shipping-title").val(shipping.title);
		$("#shipping-description").val(shipping.description);
		$("#shipping-region").val(shipping.region);
		$("#shipping-price").val(shipping.price);
		$('input:radio[name="shipping-price-type"]').filter('[value='+shipping.price_type+']').prop("checked",true);

		if(shipping.price_type === "variable"){
			Shipping.conditions = data.conditions;
			Shipping.show_conditions(Shipping.conditions);
		}
		if(shipping.region === "other"){
			Shipping.regions = data.regions;
			Shipping.show_added_regions(Shipping.regions);
		}
		Shipping.handle_region_select();
		Shipping.toggle_variable_price();
	},
	edit_shipping(id){
		$.ajax({
			type:"DELETE",
			url:"/user/ajax/catalog/delete-shipping",
			data:{
				id: id,
			},
			success:function(){
				Shipping.add_shipping();
			}
		});
	},
	delete_shipping(){
		if(confirm("Are you sure ?")){
			let id=$(this).attr("data-shipping");
			$.ajax({
				type:"DELETE",
				url:"/user/ajax/catalog/delete-shipping",
				data:{
					id: id,
				},
				success:function(){
					Shipping.get_shipping();
					Messages.fixed(true,"You have successfully delete shipping !");
				}
			});
		}
	},
	add_shipping: function() {
		if(Validation.simple("#shipping-manage-body")){
			Loading.start("#shipping-manage-body");
			let region = $("#shipping-region option:selected").val();
			let priceType = $('input[name=shipping-price-type]:checked').val();
			let data = {
				title: $("#shipping-title").val(),
				description: $("#shipping-description").val(),
				region: region,
				customRegions: region === "other" ? Shipping.regions : "",
				priceType: priceType,
				price: $("#shipping-price").val(),
				priceConditions: priceType === "variable" ? Shipping.conditions : ""
			};
			$.ajax({
				type: "POST",
				url: "/user/ajax/catalog/add-shipping",
				data: {
					"data": data,
				},
				success: function (data) {
					Messages.fixed(data.status, data.message);
					Shipping.get_shipping();
					Loading.stop("#shipping-manage-body");
				}
			});
		}
	},
	//REGIONS
	handle_region_select: function(){
		let selectedVal = $("#shipping-region option:selected").val();
		let hiddenSelect = $("#shipping-specific-region-section");
		if(selectedVal === "other"){
			if(hiddenSelect.hasClass("d-none")) hiddenSelect.removeClass("d-none");
		}else{
			if(!hiddenSelect.hasClass("d-none")) hiddenSelect.addClass("d-none");
		}
	},
	get_regions: function(searchValue){
		$.ajax({
			type:"GET",
			url:"/ajax/app/regions/get-all",
			data:{
				search:searchValue
			},
			success: function (data){
				let search = false;
				if(searchValue) search = true;
				Shipping.show_regions(data.data,search);
			}
		})
	},
	show_regions: function(data,search){
		let T = $("#region-list-template");
		let list = $("#modal-regions-list");
		let secondList;
		list.empty();
		if(search === false){
			secondList =$("#condition-region-select");
			secondList.empty();
		}
		$.each(data,function(index,value){
			if(value.continent != "continent"){
				let image = Shipping.create_region_slug(value.code);
				T.find(".region-flag").removeClass("d-none");
				T.find(".region-flag").attr("src",appConfig.flagsURL+image+".svg");
			}
			T.find(".region-name").html(value.name);
			T.find(".add-region-btn").attr("data-region",value.id).attr("data-regionName",value.name);
			//Countries modal
			list.append(T.html());
			// Price conditions
			if(search === false) secondList.append("<option>"+value.name+"</option>");
		});
	},
	add_region: function(){
		let region = {};
		let data = $(this);
		region.id = data.attr("data-region");
		region.name = data.attr("data-regionName");
		Shipping.regions.push(region);
		Shipping.show_added_regions(Shipping.regions);
		Messages.fixed(true,"You have successfully added region");
	},
	show_added_regions: function(data){
		let T = $("#small-region-list-template");
		let list = $("#added-regions");
		list.empty();
		let i=0;
		$.each(data,function(index,value){
			T.find(".sm-region-container").attr("data-index",i);
			T.find(".sm-region-name").html(value.name);
			list.append(T.html());
			i++;
		});
	},
	delete_region: function(){
		if(confirm("Are you sure ?")){
			let container = $(this).parent();
			let index = container.attr("data-index");
			Shipping.regions.splice(index,1);
			Shipping.show_added_regions(Shipping.regions);
		}
	},
	search_region: function(){
		let searchValue = $(this).val();
		if(Shipping.searchBreak === false && searchValue.length > 2){
			Shipping.searchBreak = true;
			Loading.start("#modal-regions-list");
			Shipping.get_regions(searchValue);
			Loading.stop("#modal-regions-list");
			setTimeout(function(){
				Shipping.searchBreak = false;
			},100);
		}else if(searchValue.length === 0){
			Shipping.get_regions();
		}
	},
	create_region_slug: function(str){
		let trimmed = $.trim(str);
		let slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
		replace(/-+/g, '-').
		replace(/^-|-$/g, '');
		return slug.toLowerCase();
	},
	//Conditions
	add_shipping_condition: function(){
		if(Validation.simple("#condition-form")){
			let condition = {};
			//Get data from modal
			let selectedType = $("#condition-type-select option:selected").val();
			if(selectedType === "min-price" || selectedType === "max-price"){
				condition.type = selectedType;
				condition.value = $("#condition-"+selectedType).val();
			}else if(selectedType === "region" || selectedType === "role"){
				condition.type = selectedType;
				condition.value = $("#condition-"+selectedType+"-select option:selected").val();
			}
			condition.discount_type = $('input[name=shipping-discount-type]:checked').val();
			condition.discount_value = $("#shipping-discount").val();

			Shipping.conditions.push(condition);
			Shipping.show_conditions(Shipping.conditions);
			Messages.fixed(true,"You have successfully added condition");
		}
	},
	show_conditions: function(data){
		let i = 0;
		let T = $("#condition-template");
		let list = $("#conditions-list");
		list.empty();
		$.each(data,function(index,value){
			T.find(".condition-type").html(value.type);
			T.find(".condition-value").html(value.value);
			T.find(".condition-discount-type").html(value.discount_type);
			T.find(".condition-discount-value").html(value.discount_value);
			T.find(".condition-delete").attr("data-index",i);
			list.append(T.html());
			i++;
		});
		//Show table if hidden
		let conditionTable = $("#condition-table");
		if(data.length > 0 && conditionTable.hasClass("d-none")) conditionTable.removeClass("d-none");
		else if(data.length === 0 && !conditionTable.hasClass("d-none")) conditionTable.addClass("d-none");
	},
	delete_shipping_condition: function(){
		if(confirm("Are you sure ?")){
			let parent = $(this).parent();
			let index = parent.attr("data-index");
			Shipping.conditions.splice(index,1);
			Shipping.show_conditions(Shipping.conditions);
		}
	},

	//Additional not important
	clear_form: function(){
		Shipping.clear_shipping_arrays();
		$("#shipping-region").val("usa");
		$('#constant-price-radio').prop("checked",true);
		$("#shipping-title").val("");
		$("#shipping-description").val("");
		$("#shipping-price").val("");
	},
	clear_shipping_arrays: function(){
		Shipping.regions = [];
		Shipping.conditions = [];
		$("#conditions-list").empty();
		$("#condition-table").addClass("d-none");
		$("#added-regions").empty();
	},
	//Show hidden variable price section
	toggle_variable_price: function(){
		let priceType = $('input[name=shipping-price-type]:checked').val();
		let settingsHtml = $("#variable-shipping-price-settings");
		if(priceType === "variable"){
			if(settingsHtml.hasClass("d-none"))settingsHtml.removeClass("d-none");
		}else{
			if(!settingsHtml.hasClass("d-none")) settingsHtml.addClass("d-none");
		}
	},
	//Show hidden condition type section #CONDITIONS MODAL
	show_condition_type_section: function(){
		Shipping.hide_condition_type_sections();
		let selectedVal = $("#condition-type-select option:selected").val();
		let section = $("#condition-type-"+selectedVal+"-section");
		section.removeClass("d-none");
		let input = section.find("input");
		if(!input.hasClass("v-data v-required")){
			input.addClass("v-data v-required");
		}
	},
	//Hide hidden condition type section #CONDITIONS MODAL
	hide_condition_type_sections: function(){
		$("#condition-type-sections .condition-type-section").each(function(){
			if(!$(this).hasClass("d-none")){
				$(this).addClass("d-none");
				$(this).find("input").removeClass("v-data v-required");
			}

		});
	},

};
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});