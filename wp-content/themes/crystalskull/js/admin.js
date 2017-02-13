var AddedModal = false;
var ChangingIcon = 0;
jQuery(document).ready(function () {
	BindButtons();
});

jQuery(document).ready(function () {
var empty_themeoptions_item = jQuery('.wp-submenu li a[href="themes.php?page=options-framework"]:eq(1)');
empty_themeoptions_item.remove();
});


function BindButtons() {

	var link = jQuery('#myTab a');
	var content = jQuery('.tab-content');

    link.click(function (e) {
    	//console.log('aaaaaa');
    e.preventDefault()
    var id = jQuery(this).attr('id');
    //console.log(id);
  	content.children().removeClass("active");
    jQuery('.tab-content #'+id).addClass("active");
   });

 jQuery("#userForm .panel-title input[type='checkbox']").change(function () {
  	jQuery(this).closest('div').next().find(".panel-body input[type='checkbox']").prop('checked', this.checked);
  });


jQuery(".pb-cat-cont label span").each(function (e) {
	var tmpcls = jQuery(this).attr('class');
	jQuery(this).parents().eq(1).addClass(tmpcls);
});


}

jQuery(document).ready(function($){
    $('.catcolorpicker').wpColorPicker();
});