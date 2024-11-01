<?php
/*
 * this file renders options page for the plugin
 */
function x33_tag_options_page()
{

	// options names
	$size_list = 'x33_tag_sizes';
	$color_list = 'x33_tag_colors';
	$friendly_urls = 'x33_tag_friendly_urls';
	$auto_tags = 'x33_tag_auto_tag';
	$auto_cloud = 'x33_tag_auto_cloud';
	
	// read in existing options values from database
	$size_list_val = get_option( $size_list );
	$color_list_val = get_option( $color_list );
	$friendly_urls_val = get_option( $friendly_urls );
	$auto_tags_val = get_option( $auto_tags );
	$auto_cloud_val = get_option( $auto_cloud );
	
	$friendly_urls_checked = $friendly_urls_val == '1' ? 'checked' : '';
	$auto_tags_checked = $auto_tags_val == '1' ? 'checked' : '';
	$auto_cloud_checked = $auto_cloud_val == '1' ? 'checked' : '';
	
	$size_list_html = x33_tag_make_size_list($size_list_val);
	$color_list_html = x33_tag_make_color_list($color_list_val);
	
	// if the user has posted us some information
	if( $_POST['action'] == 'save' ):

		$size_list_val = $_POST['size_list'];
		$color_list_val = $_POST['color_list'];
		$friendly_urls_val = $_POST['friendly_urls'];
		$auto_tags_val = $_POST['auto_tags'];
		$auto_cloud_val = $_POST['auto_cloud'];
	
		$size_list_val = x33_tag_validate_size_list($size_list_val);
		$color_list_val = x33_tag_validate_color_list($color_list_val);
		
		if ($size_list_val !== false && $color_list_val !== false):
			update_option( $size_list, $size_list_val );
			update_option( $color_list, $color_list_val );
			update_option( $friendly_urls, $friendly_urls_val == 1 ? '1' : '0' );
			update_option( $auto_tags, $auto_tags_val == 1 ? '1' : '0' );
			update_option( $auto_cloud, $auto_cloud_val == 1 ? '1' : '0' );
			
			$friendly_urls_checked = $friendly_urls_val == '1' ? 'checked' : '';
			$auto_tags_checked = $auto_tags_val == '1' ? 'checked' : '';
			$auto_cloud_checked = $auto_cloud_val == '1' ? 'checked' : '';
			
			$size_list_html = x33_tag_make_size_list($size_list_val);
            $color_list_html = x33_tag_make_color_list($color_list_val);
?>
			<div class="updated">
				<p><strong><?php _e('Options saved.', 'x33_tag_trans_domain' ); ?></strong></p>
			</div>
<?php
		else:
?>
			<div class="error">
				<p><strong><?php _e('Validation failed. Check if all fields are filled and if they have correct values.', 'x33_tag_trans_domain' ); ?></strong></p>
			</div>
<?php
		endif;
	endif;
?>

<div class="wrap">
<?php
		echo "<h2>" . __( 'Xata33 Tag Options', 'x33_tag_trans_domain' ) . "</h2>";
		echo "<h3>" . __( 'Size and Color Options', 'x33_tag_trans_domain' ) . "</h2>";
?>
    
    <div id="loadingBar" style="display:none;">Loading...</div>
    <table class="x33_tag_table">
        <tr>
            <td><?php _e("Add size (in pixels):", 'x33_tag_trans_domain' ); ?></td>
            <td><?php _e("Add color (like #000000):", 'recommended_url_trans_domain' ); ?></td>
        </tr>
        <tr>
            <td>
                <input type="text" value="" maxlength="3" size="30">
                <input type="button" id="x33_tag_add_size" value="Add"> 
                <div id="size_list"><?php echo $size_list_html; ?></div>
            </td>
            <td>
                <input type="text" value="" maxlength="7" size="30">
                <input type="button" id="x33_tag_add_color" value="Add">
                <div id="color_list"><?php echo $color_list_html; ?></div>
            </td>
        </tr>
    </table>
    <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php echo "<h3>" . __( 'Friendly URLs Options', 'x33_tag_trans_domain' ) . "</h2>"; ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" id="size_list_hidden" name="size_list" value="">
        <input type="hidden" id="color_list_hidden" name="color_list" value="">
        <table class="x33_tag_table">
        <tr>
            <td>Use url rewriting for local tag urls:</td>
            <td><input name="friendly_urls" type="checkbox" value="1" <?php echo $friendly_urls_checked; ?>/></td>
        </tr>
        </table>
<?php echo "<h3>" . __( 'Auto tag Options', 'x33_tag_trans_domain' ) . "</h2>"; ?>
        <table class="x33_tag_table">
        <tr>
            <td>Use tags auto position:</td>
            <td><input name="auto_tags" type="checkbox" value="1" <?php echo $auto_tags_checked; ?>/></td>
        </tr>
        <tr>
            <td>Use tag cloud auto position:</td>
            <td><input name="auto_cloud" type="checkbox" value="1" <?php echo $auto_cloud_checked; ?>/></td>
        </tr>
        </table>
        <hr />
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Update Options', 'recommended_url_trans_domain' ) ?>" />
        </p>
    </form>
</div>
<?php	
}
?>