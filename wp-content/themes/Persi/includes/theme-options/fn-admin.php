<?
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */
 

/**
 * Add theme options menu
 */
function obwp_add_menu()
{
	global $themename, $adminmenuname;
	
	$current_theme = current_theme_info();
	
	add_menu_page($themename, $adminmenuname, 'edit_themes', 'obwp-settings.php');
	add_submenu_page('obwp-settings.php', 'General Settings', 'General Settings', 'edit_themes', 'obwp-settings.php','obwp_general_settings');
	add_submenu_page('obwp-settings.php', 'Banners Settings', '125x125 Settings', 'edit_themes', 'obwp-settings-banners.php', 'obwp_banners_settings');
	add_submenu_page('obwp-settings.php', 'Pages Settings', 'Pages Settings', 'edit_themes', 'obwp-settings-pages.php', 'obwp_pages_settings');
}

/**
 * Save all sittings and call page showing function
 */	
function obwp_add_admin($file) {

    global $themename, $options;

    if ( $_GET['page'] == $file ) {

        if ( 'save' == $_REQUEST['action'] ) {
			
				if(count($_FILES)>0){
					$uploads = wp_upload_dir();
					$dir = $uploads['basedir'].'/'.SHORTNAME.'_images';
					if(!file_exists($dir))
					{
						mkdir($dir,0777);
					}
					foreach ($_FILES as $key=>$value)
					{
						if(is_uploaded_file($_FILES[$key]['tmp_name']))
						{
							$filepath_current = $dir.'/'.basename(obwp_get_meta($key.'_url'));
							@unlink($filepath_current);
							$filepath = upload_file($key, $dir, 1000000*10, array('image/jpeg','image/jpg','image/png','image/gif','image/bmp'));
							$file_url = $uploads['baseurl'].'/'.SHORTNAME.'_images'.'/'.basename($filepath);
							update_option( $key.'_url', $file_url);
						}
					}
				}
				unset($_FILES);

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
						}
					}
				}
				
                $_REQUEST['saved']='true';
				?>
				<script language="javascript" type="text/javascript">
					location.href='<?php echo get_option('home'); ?>/wp-admin/admin.php?page=<?php echo $file; ?>&saved=true';
				</script>
				<?

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
				if($value['type'] != 'multicheck'){
                	delete_option( $value['id'] ); 
					
					$uploads = wp_upload_dir();
					$dir = $uploads['basedir'].'/'.SHORTNAME.'_images';
					$filepath_current = $dir.'/'.basename(obwp_get_meta($value['id'].'_url'));
					@unlink($filepath_current);
                	delete_option( $value['id'].'_url' ); 
				}else{
					foreach($value['options'] as $mc_key => $mc_value){
						$del_opt = $value['id'].'_'.$mc_key;
						delete_option($del_opt);
					}
				}
			}
                $_REQUEST['saved']='true';
				?>
				<script language="javascript" type="text/javascript">
					location.href='<?php echo get_option('home'); ?>/wp-admin/admin.php?page=<?php echo $file; ?>&saved=true';
				</script>
				<?

        }
    }
	
	obwp_admin();
}

/**
 * Show the page content
 */	
function obwp_admin() {

    global $themename, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> options</h2>

<form method="post" enctype="multipart/form-data">

<table class="optiontable" width="100%">

<?php foreach ($options as $value) { 
	
	switch ( $value['type'] ) {
		case 'text':
		option_wrapper_header($value);
		?>
		        <input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
		<?php
		option_wrapper_footer($value);
		break;
		
		case 'text2':
		option_wrapper_header2($value);
		?>
		        <input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
		<?php
		option_wrapper_footer2($value);
		break;
		
		case 'html':
		option_wrapper_header($value);
		echo $value['html'];
		option_wrapper_footer($value);
		break;
		
		case 'html_tags':
		option_wrapper_header_wide($value);
		echo $value['html'];
		option_wrapper_footer_wide($value);
		break;
		
		case 'select':
		option_wrapper_header($value);
		$cur = false;
		?>
	            <select style="width:300px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                <?php foreach ($value['options'] as $option) { ?>
	                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; $cur = true; } elseif ($option == $value['std'] && !$cur) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select>
		<?php
		option_wrapper_footer($value);
		break;
		
		case 'textarea':
		$ta_options = $value['options'];
		option_wrapper_header($value);
		?>
				<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width:400px;height:100px;"><?php 
				if( get_settings($value['id']) != "") {
						echo stripslashes(get_settings($value['id']));
					}else{
						echo $value['std'];
				}?></textarea>
		<?php
		option_wrapper_footer($value);
		break;

		case "radio":
		option_wrapper_header($value);
		
 		foreach ($value['options'] as $key=>$option) { 
				$radio_setting = get_settings($value['id']);
				if($radio_setting != ''){
		    		if ($key == get_settings($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?>&nbsp;&nbsp;
		<?php 
		}
		 
		option_wrapper_footer($value);
		break;
		
		case "checkbox":
		option_wrapper_header($value);
						if(get_settings($value['id'])){
							$checked = "checked=\"checked\"";
						}else{
							$checked = "";
						}
					?>
		            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
		<?php
		option_wrapper_footer($value);
		break;

		case "multicheck":
		option_wrapper_header($value);
		
 		foreach ($value['options'] as $key=>$option) {
	 			$pn_key = $value['id'] . '_' . $key;
				$checkbox_setting = get_settings($pn_key);
				if($checkbox_setting != ''){
		    		if (get_settings($pn_key) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="checkbox" name="<?php echo $pn_key; ?>" id="<?php echo $pn_key; ?>" value="true" <?php echo $checked; ?> /><label for="<?php echo $pn_key; ?>"><?php echo $option; ?></label><br />
		<?php 
		}
		 
		option_wrapper_footer($value);
		break;
		
		case "heading":
		?>
		<tr valign="top"> 
		    <td><h3><?php echo $value['name']; ?></h3></td>
		</tr>
		<?php
		break;
		
		case "line":
		?>
		<tr valign="top"> 
		    <td colspan="2"><div style="border-top:1px solid #ccc;">&nbsp;</div></td>
		</tr>
		<?php
		break;
		
		default:
			if(!empty($value['desc'])) :
			?>
			<tr valign="top">
				<td>&nbsp;</td><td><small><?php echo $value['desc']; ?></small></td>
			</tr>
			<?php endif; ?>
			<tr valign="top">
				<td ><?php echo $value['html']; ?></td>
			</tr>
			<?php 
		break;
	}
}
?>

</table>

<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}


/**
 * Show admin wrapper header
 */	
function option_wrapper_header($values){
	?>
	<tr valign="top"> 
	    <td><?php echo $values['name']; ?>
	<?php
}


/**
 * Show admin wrapper footer
 */	
function option_wrapper_footer($values){
	?>
		<?php if(!empty($values['desc'])) echo '<br />'.$values['desc']; ?><br /><br />
		</td>
	</tr>
	<?php 
}

/* Show admin wrapper header wide row */	
function option_wrapper_header_wide($values){
	?>
	<tr> 
	    <td valign="top">
	<?php
}


/* Show admin wrapper footer wide row */	
function option_wrapper_footer_wide($values){
	?></td>
	</tr>
	<?php 
}


/* Show admin wrapper header 2 */	
function option_wrapper_header2($values){
	?>
	<tr valign="top"> 
		<table width="100%">
			<tr>
				<td scope="row" width="130"><?php echo $values['name']; ?></td>
				<td>
	<?php
}


/* Show admin wrapper footer 2 */	
function option_wrapper_footer2($values){
	?>
					<br /><span class="setting-description"><?php echo $values['desc']; ?></span>
				</td>
			</tr>
		</table>
	</tr>
	<?php 
}

/* Return list of categories for admin settings */	
function obwp_admin_dropdown_categories($name)
{
	return wp_dropdown_categories('orderby=name&show_option_all=none&name='.$name.'&echo=0&selected='.obwp_get_meta($name,'int',-1).'&hierarchical=1&hide_empty=0');
}

?>