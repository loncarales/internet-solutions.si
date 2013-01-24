var $j = jQuery.noConflict();
$j(function()
{
	// Hide advanced options by default
	$j("#advanced-options").hide();
	
	// The link that hides/shows the advanced section
	var hideShowLink = $j("#wpp-hide-show-advanced a");
	
	hideShowLink.click(function()
	{
		// Show the advanced section
		if (hideShowLink.text() == 'Show Advanced Settings') 
		{
			hideShowLink.text('Hide Advanced Settings');
			$j("#wpp-hide-show-advanced").removeClass('hide');
			$j("#advanced-options").show();
		} 
		
		// Hide the section again
		else 
		{
			$j("#advanced-options").hide();
			$j("#wpp-hide-show-advanced").addClass('hide');
			hideShowLink.text('Show Advanced Settings');
		}
		return false;
	});
	

});