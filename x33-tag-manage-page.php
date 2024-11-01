<?php
function x33_tag_manage_page()
{
	$tags = x33_tag_get_tags();
	$tag_list = x33_tag_make_tag_list($tags);
?>
<div class="wrap">
<?php
        echo "<h2>" . __( 'Manage Xata33 Tags', 'x33_tag_trans_domain' ) . "</h2>";
?>
        <div id="loadingBar" style="display:none;">Loading...</div>
        <div id="message" style="display:none;"></div>
        <table class="x33_tag_table">
        <tr>
            <td><?php _e("Add tags(comma separated list):", 'x33_tag_trans_domain' ); ?></td>
        </tr>
        <tr>
            <td>
                <input type="text" id="x33_tag_tag_field" name="x33_tag_tag_field" value="" maxlength="" size="100">
                <input type="button" id="x33_tag_add_tag" value="Add"> 
                <p><?php echo $tag_list; ?></p>
                <div id="tag_info"></div>
            </td>
        </tr>
        </table>
        <hr />
</div>
<?php
}
?>