<?php
function x33_tag_ajax_js()
{
	$ajaxurl = get_settings('siteurl') . '/wp-content/plugins/x33-tag/x33-tag-ajax.php';
?>
<script type="text/javascript">
//<![CDATA[
var ajaxurl = '<?php echo $ajaxurl; ?>';
//]]>
</script>
<?php
}
?>