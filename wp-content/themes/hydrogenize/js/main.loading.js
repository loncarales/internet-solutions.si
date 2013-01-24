

function paddToggle(classname,value) {
	jQuery(classname).focus(function() {
		if (value == jQuery(classname).val()) {
			jQuery(this).val('');
		}
	});
	jQuery(classname).blur(function() {
		if ('' == jQuery(classname).val()) {
			jQuery(this).val(value);
		}
	});
}

function paddSidebarTabsInit() {
	if (!jQuery("#sidebar-tabs").length) {
		return;
	} else {
		jQuery("#sidebar-tabs").tabs({ cookie: { expires: 30 } });
	}
}

jQuery(document).ready(function() {
	jQuery.noConflict();
	
	jQuery('input#s').val('Search this site');
	paddToggle('input#s','Search this site');

	jQuery('div.search form').click(function () {
		jQuery('input#s').focus();
	});
	
	if (jQuery('div#s3slider').length > 0) {
		jQuery('div#s3slider').s3Slider({
			timeOut: 4000
		});
	}

	jQuery('div#padd-mainmenu ul').superfish({
		hoverClass: 'hover',
		autoArrows: false
	}); 

	paddToggle('input#comment-author','Name');
	paddToggle('input#comment-email','Email');
	paddToggle('input#comment-url','Website');
	paddToggle('textarea#comment-comment','Message');
	
	paddSidebarTabsInit();

});
