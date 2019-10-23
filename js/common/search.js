let SEARCHBREAK = false;
Search = {
	init: function(search,url,mode){
		if(search.length > 2 && SEARCHBREAK === false){
			Search.break_start();
			return Promise.resolve(
			$.ajax({
				type:"GET",
				url:url,
				data:{
					search: search,
					mode: mode
				}
			}));
		}else if(search.length <= 2){
			return false;
		}
	},
	break_start: function(){
		SEARCHBREAK = true;
	},

	break_stop: function(){
		setTimeout(function(){
			SEARCHBREAK = false;
		},100);
	}
};


