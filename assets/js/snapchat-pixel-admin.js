jQuery( document ).on('click', '.snapchat-wrapper .nav-tab' ,function(e){
	e.preventDefault();
	var tab_id = jQuery(this).data('tab-id');
	jQuery(".snapchat-wrapper .nav-tab").removeClass("nav-tab-active");
	jQuery("#" + tab_id).addClass("nav-tab-active");

	jQuery(".snapchat-pixel-wrapper .tab-content").removeClass("active");
	jQuery(".snapchat-pixel-wrapper .tab-content." + tab_id).addClass("active");

	var tab_link = jQuery(this).attr("href");
	history.pushState({}, null, tab_link);
});

