function get_product_record( product_id ){
	var $ = jQuery;
	var snappixel_data = 'snap_product_id='+product_id+'&action=snapchat_product_data';
	var return_response = '';
	$.ajax({
		action:  'snapchat_product_data',
		type:    "POST",
		url:     snappixel.ajaxurl,
		data:    snappixel_data,
		success: function( response ) {
			if(response.success){
				return_response = response.data;
				$("body").append("<input type='hidden' id='return_response' value='"+ return_response +"'>")
			}
		}
    });
};