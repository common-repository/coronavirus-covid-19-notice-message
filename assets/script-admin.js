///////////////////////
// Add Color Picker //
//////////////////////
jQuery(function()
{
 jQuery('.color_picker').wpColorPicker();
 jQuery('input[name=coronavirus_notice_location]').change(function()
 {
  if(jQuery('input[name=coronavirus_notice_location]:checked').val()=='inline') { jQuery('#location_inline_div').slideDown(); }
  else { jQuery('#location_inline_div').slideUp(); }
 });
});

///////////////////////////
// External Link Option //
//////////////////////////
jQuery(function()
{
 jQuery('#coronavirus_notice_web_page').change(function()
 {
  if(jQuery(this).val()=='*external') { jQuery('#web_page_external_link').slideDown(); }
  else { jQuery('#web_page_external_link').slideUp(); }
 });
});