<?php
/**
 * Functions and code specifically relating to just the thumbnailer.
 */



/**
 * Fetch a custom thumbnail URL and resize it to match the maximum dimensions defined by the required thumbnail sizes.
 * @param $imageurl The image URL.
 * @param $size_override If specified, the standard size to use to resize the custom thumbnail.
 * @param $forceUpdate If true, force the update of the cached thumbnail.
 * @return String The full path of the image to render.
 */
function WPPortfolio_getAdjustedCustomThumbnail($imageurl, $size_override = false, $forceUpdate = false) 
{
	// Determine cache directory
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
			
	// Create cache directory if it doesn't exist
	WPPortfolio_createCacheDirectory();

	// Get all the options from the database for the thumbnail handling
    $setting_fetch_method   = stripslashes(get_option('WPPortfolio_setting_fetch_method'));

    // If $size_override is specified, then use that size rather than the size stored in the settings.
    if ($size_override) {
    	$maxsize = $size_override;
    } else { 
    	$maxsize = stripslashes(get_option('WPPortfolio_setting_stw_thumb_size'));
    }   

    // How are we going to scale the image?
    $setting_scale_type = get_option('WPPortfolio_setting_scale_type');
        
    // Use arguments to work out the target filename
	$filename = md5($imageurl.$maxsize.$setting_scale_type).".jpg";
	$filepath = trailingslashit($actualThumbPath).$filename;
		
	// Work out if we need to update the cached and resized thumbnail
	if ($forceUpdate || WPPortfolio_cacheFileExpired($filepath))
	{		
		WPPortfolio_downloadRemoteImageToLocalPath($imageurl, $filepath, $setting_fetch_method);
		
		// Now we've got the image, resize it.
		if (file_exists($filepath))
		{
			// Turn the standard sizes into actual pixel sizes for the resizing function.	
			switch($maxsize) {
				case "sm":
					$maxWidth = 120;
					$maxHeight = 90;
					break;
					
				case "lg":
					$maxWidth = 200;
					$maxHeight = 150;
					break;
					
				// xlg
				default:
					$maxWidth = 320;
					$maxHeight = 240;
					break;
			}
			
			// Resize the image based on settings to scale to width, scale to height, or scale to both.
			switch ($setting_scale_type)
			{
				case 'scale-height':
						$maxWidth = 0;
					break;
				case 'scale-width':
						$maxHeight = 0;
					break;
					
				// scale-both
				default:
						$resizeOption = 'scale-both';
					break;
			}
			
			WPPortfolio_resizeImage($imageurl, $filepath, $maxWidth, $maxHeight);
		}
	}
    
	// File downloaded successfully
	if (file_exists($filepath)) {
		$webFilePath = WPPortfolio_getThumbPathURL();		
		return "$webFilePath/$filename";
	}
	// Something went wrong, so return default image 
	else {
		$pendingThumbPath = WPPortfolio_getPendingThumbURLPath();
		return "$pendingThumbPath$maxsize.jpg";
	}
}

/**
 * Determine if specified file has expired from the cache.
 * @param $filepath The full path of the file to check for in the cache.
 * @return Boolean True if the file has expired or no longer exists, or false if the file is still valid.
 */
function WPPortfolio_cacheFileExpired($filepath)
{
	// Use setting to check age of files.
	$setting_cache_days	= stripslashes(get_option('WPPortfolio_setting_cache_days')) + 0;
	
	// If cached thumbnails never expire, then just check if file exists or not.
	if ($setting_cache_days == 0) {
		return (!file_exists($filepath));
	} 
	// If thumbnails are allowed to expire, then check age of files and if file exists.
	else {	
		$cutoff = time() - 3600 * 24 * $setting_cache_days;
		return (!file_exists($filepath) || filemtime($filepath) <= $cutoff);	
	}
}

/**
 * Resize the specified image using the maximum dimensions provided as arguments.
 * @param $imagepath The actual file path of the image to resize.
 * @param $maxWidth The maximum width to resize the image to.
 * @param $maxHeight The maximum height to resize the image to.
 * @return unknown_type
 */
function WPPortfolio_resizeImage($originalImage, $imagepath, $maxWidth = 120, $maxHeight = 80)
{
	// What sort of image? Check the extension from the original image URL to determine the original 
	// image format, as we can't use the extension of the file in the cache, as that has already been 
	// changed to jpg.  
	$type = strtolower(substr(strrchr($originalImage, '.'), 1));
	
	$quality = 0;  
	switch ($type)  
	{     
	case 'png':  
		$image_create_func = 'ImageCreateFromPNG';  
		$image_save_func = 'ImagePNG';  
 	
		// Compression Level: from 0  (no compression) to 9  
		$quality = 0;  
		break;  
		
	case 'gif':  
		$image_create_func = 'ImageCreateFromGIF';  
		$image_save_func = 'ImageGIF';   
		break;   
	 
	// Also handles jpeg
	default:  
		$image_create_func = 'ImageCreateFromJPEG';  
		$image_save_func = 'ImageJPEG';    

		// Best Quality: 100  
		$quality = 100;  
	}  
	
	// Load image into GD (now that it's stored locally)
	$img = $image_create_func($imagepath); 
	
	// Resize image to max size 
	$newimg = WPPortfolio_resizeImageResource($img, $maxWidth, $maxHeight);
		
	// Save using quality parameter if available
	if (isset($quality)) {  
		$image_save_func($newimg, $imagepath, $quality);  
	}  
	else {  
		$image_save_func($newimg, $imagepath);  
	}  	
	
	// All done, clean up
	imagedestroy($img);
	imagedestroy($newimg);
		
}

/**
 * Resize the specified image resource to the maximum desired width and height.
 * @param $imgresource The image resource to resize to the maximum desired width and height.
 * @param $desired_max_width The desired maximum width.
 * @param $desired_max_height The desired maximum height.
 * @return Image Resource The resized image resource.
 */
function WPPortfolio_resizeImageResource($imgresource, $desired_max_width, $desired_max_height)
{
	$old_width  = imageSX($imgresource);
	$old_height = imageSY($imgresource);
	$new_width  = $old_width;
	$new_height = $old_height;
	
	// Scale width to match rescaled height.
	if ($desired_max_width == 0)
	{
		// Work out the aspect ratio, as we need to ensure the image stays in 
		// the correct dimensions
		$aspect_ratio = $old_height / $old_width;
		
		// Use $desired_max_height as the new_height, and change new_width using 
		// the aspect ratio
		$new_height = $desired_max_height;
		$new_width = $new_height / $aspect_ratio;
	}
	
	else if ($desired_max_height == 0) {
		// Work out the aspect ratio, as we need to ensure the image stays in 
		// the correct dimensions
		$aspect_ratio = $old_width / $old_height;
		
		// Use $desired_max_width as the new_width, and change new_height using 
		// the aspect ratio
		$new_width = $desired_max_width;
		$new_height = $new_width / $aspect_ratio;
	}
	
	// Check that image is already larger than needed, i.e. so needs resizing.
	else if ($old_width > $desired_max_width  || $old_height > $desired_max_height)
	{
		// Work out the aspect ratio, as we need to ensure the image stays in 
		// the correct dimensions
		$aspect_ratio = $old_width / $old_height;
		
		// Use $desired_max_width as the new_width, and change new_height using 
		// the aspect ratio
		$new_width = $desired_max_width;
		$new_height = $new_width / $aspect_ratio;
		
		// Just check that the new height is actually below the max height, if not
		// adjust the sizes again to ensure it is
		if ($new_height > $desired_max_height)
		{
			
			$new_height = $desired_max_height;
			$new_width = $new_height * $aspect_ratio;
		}
	}
		
	$image_resized = ImageCreateTrueColor($new_width, $new_height);
	imagecopyresampled($image_resized, $imgresource, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
	
	return $image_resized;
}


/**
 * Gets the thumbnail for the specified website, stores it in the cache, and then returns the 
 * relative path to the cached image.
 * 
 * @param $url The URL to get the thumbnail for.
 * @param $size_override If specified, use this size rather than the size in the settings.
 * @param $capture_internal_page If true, capture an internal page rather than just a home page. Requires the pro feature for Shrink The Web to 'Capture Specific Page'.
 * @return String The full URL for the thumbnail stored in the cache.
 */
function WPPortfolio_getThumbnail($url, $size_override = false, $capture_internal_page = false)
{
	// Determine cache directory
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
			
	// Create cache directory if it doesn't exist
	WPPortfolio_createCacheDirectory();
	
	// Get all the options from the database for the thumbnail    
    $args["stwaccesskeyid"] = stripslashes(get_option('WPPortfolio_setting_stw_access_key'));
    $args["stwu"] 			= stripslashes(get_option('WPPortfolio_setting_stw_secret_key'));
    $setting_fetch_method   = stripslashes(get_option('WPPortfolio_setting_fetch_method'));
    
    // Allowing internal links?
    if ($capture_internal_page) {
    	$args["stwinside"] = 1;
    }
        
    // If $size_override is specified, then use that size rather than the size stored in the settings.
    if ($size_override) {
    	$args["stwsize"] = $size_override;
    } else { 
    	$args["stwsize"] = stripslashes(get_option('WPPortfolio_setting_stw_thumb_size'));
    }
    
    // Try to grab the thumbnail
    $imagefile = WPPortfolio_getCachedThumbnail($url, 
    								$actualThumbPath, 
    								WPPortfolio_getThumbPathURL(),  
    								WPPortfolio_getPendingThumbURLPath(), 
    								$args, 
    								false, 
    								$setting_fetch_method);    
        
    return $imagefile;
}


/**
 * Get a thumbnail, caching it first if possible.
 * 
 * @param $url The URL to get a thumbnail for.
 * @param $actualThumbPath The actual path on the server to store the cached thumbnail.
 * @param $webFilePath The web URL path for the thumbnails that relates to the actual file path.
 * @param $pendingThumbPath The path for images when a thumbnail cannot be loaded.
 * @param $args The arguments that contain the access ID and size info to get the thumbnail.
 * @param $forceUpdate If true, force the retrieval over the web of the thumbnail.
 * @param $fetchMethod The method used to fetch a thumbnail (either curl or fopen).
 * 
 * @return String The full path of the image to render.
 */
function WPPortfolio_getCachedThumbnail($url, $actualThumbPath, $webFilePath, $pendingThumbPath,
							$args = null, $forceUpdate = false, $fetchMethod = "curl")
{
	// Don't try something if not enough arguements.
	if (!$args || !$url || !$actualThumbPath || !$webFilePath)
		return false;

	// Use arguements to work out the target filename
	$filename = md5($url.serialize($args)).".jpg";
	$filepath = trailingslashit($actualThumbPath).$filename;
	$size = $args['stwsize'];
	
	// Work out if we need to update the cached and resized thumbnail	
	$usingCache = true;
	if ($forceUpdate || WPPortfolio_cacheFileExpired($filepath))
	{
		// Try to download the file
		if ($jpgurl = WPPortfolio_checkWebsiteThumbnailCaptured($url, $args, $fetchMethod)) {		
			WPPortfolio_downloadRemoteImageToLocalPath($jpgurl, $filepath, $fetchMethod);
		}
		
		$usingCache = false;
	}		

	// The URL to return.
	$returnName = false;
	 
	// File downloaded successfully
	if (file_exists($filepath)) {
		$returnName = "$webFilePath/$filename";
	}
	// Something went wrong, so return default image 
	else {
		$returnName = "$pendingThumbPath$size.jpg";
	}
	
	// Log what happened
	if ($usingCache) {
		// Param 1: Requested thumbnail URL
		// Param 2: Type - request or cache
		// Param 3: Did the operation succeed?
		// Param 4: Further Information
		WPPortfolio_debugLogThumbnailRequest($url, 'cache', (file_exists($filepath)), $filename);
	}
	
	return $returnName;
}

/**
 * Method that checks that the thumbnail for the specified website exists. 
 * @param $url The website to get the thumbnail for.
 * @param $args The arguements used for the HTTP request.
 * @param $fetchMethod The method used to fetch a thumbnail (either curl or fopen). 
 * 
 * @return The URL of the image file from the server if the request was successful, false otherwise.
 */
function WPPortfolio_checkWebsiteThumbnailCaptured($url, $args = null, $fetchMethod = "curl")
{
	$args = is_array($args) ? $args : array();	
	
	// Don't try something if not enough arguements.
	if (!$args || !$url)
		return false;	
		
    $args["Service"] = "ShrinkWebUrlThumbnail";
    $args["Action"] = "Thumbnail";		
	$args["stwurl"] = $url; // now url is added to the parameters
	
	$request_url = urldecode("http://images.shrinktheweb.com/xino.php?".http_build_query($args,'','&'));	// avoid &amp;
	
	// Check that the thumbnail exists, from the STW server
	// Use cURL if possible
	if ($fetchMethod == "curl" && function_exists('curl_init')) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		$remotedata = curl_exec($ch);
		curl_close($ch);		
	}
	// This may be disabled as a security measure
	else
	{
		$remotedata = file_get_contents($request_url);
	}

	$imageURL = false;
	
	// Extract image URL to download
	$regex = '/<[^:]*:Thumbnail\\s*(?:Exists=\"((?:true)|(?:false))\")?[^>]*>([^<]*)<\//';
	if (preg_match($regex, $remotedata, $matches) == 1 && $matches[1] == "true") {
		$imageURL = $matches[2];
	}
	
	// Param 1: Requested thumbnail URL
	// Param 2: Type - request or cache
	// Param 3: Did the operation succeed?
	// Param 4: Further Information		
	WPPortfolio_debugLogThumbnailRequest($url, 'web', ($imageURL != false), $request_url);
		
	return $imageURL;
}


/**
 * Method to get image at the specified remote URL and attempt to save it to the specifed local path.
 * @param $remoteURL The URL of the remote image to download.
 * @param $localPath The path to use to store the image locally.
 * @param $fetchMethod The method used to fetch a thumbnail (either curl or fopen). 
 */
function WPPortfolio_downloadRemoteImageToLocalPath($remoteURL, $localPath, $fetchMethod = "curl")
{
	if ($fetchMethod == "curl" && function_exists('curl_init'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $remoteURL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout in 10 seconds		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		$imagedata = curl_exec($ch);		
	}
	else {		
		$imagedata = file_get_contents($remoteURL);
	}

	// Only save data if we managed to get the file contents
	if ($imagedata) {
		$localFileHandle = fopen($localPath, "w+");
		fputs($localFileHandle, $imagedata);
		fclose($localFileHandle);
	} else {	
		// Try to delete file if download failed.
		if (file_exists($localPath)) {	
			@unlink($localPath);
		}
	}
}

/**
 * Logs a thumbnail request to the debug log.
 * @param $url The URL being requested
 * @param $requestType The type of request, namely cache or request.
 * @param $requestSuccess If true, the event succeeded.
 * @param $detail Any additional debug information.
 */
function WPPortfolio_debugLogThumbnailRequest($url, $requestType, $requestSuccess, $detail)
{
	// Escape if debug logging not enabled
	if (get_option('WPPortfolio_setting_enable_debug') != 'on') {
		return false;
	}
	
	global $wpdb;
	$table_debug = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	
	$data = array();
	$data['request_url'] 		= $url;
	$data['request_type'] 		= $requestType;
	$data['request_result'] 	= ($requestSuccess ? '1' : '0');
	$data['request_detail'] 	= $detail;
	$data['request_date'] 		= date( 'Y-m-d H:i:s');
	
	$SQL = arrayToSQLInsert($table_debug, $data);
	$wpdb->query($SQL);
}


?>
