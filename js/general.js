String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

function x33_add_tag(node)
{
	var field = document.getElementById('x33_tags');
	var value = field.value.trim();
	if (value != '')
	value += ', ';
    value += node.text;
	field.value = value;
}