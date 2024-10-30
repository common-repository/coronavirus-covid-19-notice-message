<?php
/*
 * @wordpress-plugin
 * Plugin Name:       Coronavirus (COVID-19) Notice Message
 * Plugin URI:        https://www.gallagherwebsitedesign.com/plugin/coronavirus-covid-19-notice-message/
 * Description:       Simple plugin to quickly display a notice message on your website about the Coronavirus (COVID-19), and include an optional web page for more info.
 * Version:           1.1.2
 * Author:            Gallagher Website Design
 * Author URI:        https://www.gallagherwebsitedesign.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

########################
# Register Form Fields #
########################
function register_coronavirus_notice_plugin()
{
 register_setting('coronavirus-notice-settings','coronavirus_notice_enable');
 register_setting('coronavirus-notice-settings','coronavirus_notice_location');
 register_setting('coronavirus-notice-settings','coronavirus_notice_headline');
 register_setting('coronavirus-notice-settings','coronavirus_notice_banner_color');
 register_setting('coronavirus-notice-settings','coronavirus_notice_text_color');
 register_setting('coronavirus-notice-settings','coronavirus_notice_message');
 register_setting('coronavirus-notice-settings','coronavirus_notice_read_more_text');
 register_setting('coronavirus-notice-settings','coronavirus_notice_web_page');
 register_setting('coronavirus-notice-settings','coronavirus_notice_web_page_external_link');
 register_setting('coronavirus-notice-settings','coronavirus_notice_display_web_pages');
}

####################
# Add Admin Assets #
####################
function coronavirus_notice_admin_assets($hook)
{
 if(is_admin())
 {
  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('coronavirus_notice_admin_script',plugin_dir_url(__FILE__).'assets/script-admin.js?v2=1.0.5.9',array('jquery','wp-color-picker'),false,true);
 }
}
add_action('admin_enqueue_scripts','coronavirus_notice_admin_assets');

#######################
# Add Frontend Assets #
#######################
function coronavirus_notice_assets($hook)
{
 if(!is_admin())
 {
  $coronavirus_notice_enable = (get_option('coronavirus_notice_enable')=='Y') ? true : false;
  if($coronavirus_notice_enable)
  {
   wp_enqueue_style('coronavirus_notice_style',plugin_dir_url(__FILE__).'assets/style.css?v2=1.0.5.9.6');
   wp_enqueue_script('coronavirus_notice_script',plugin_dir_url(__FILE__).'assets/script.js?v2=1.0.5.9.6',array('jquery'),false,true);
  }
 }
}
add_action('init','coronavirus_notice_assets');
add_action('wp_head','coronavirus_notice_assets_head');
function coronavirus_notice_assets_head()
{
 if(!is_admin())
 {
  $coronavirus_notice_enable = (get_option('coronavirus_notice_enable')=='Y') ? true : false;
  if($coronavirus_notice_enable)
  {
   $locations = array('top','top-static','bottom','bottom-static','inline');
   $coronavirus_notice_location = (in_array(esc_html(get_option('coronavirus_notice_location')),$locations)) ? esc_html(get_option('coronavirus_notice_location')) : 'top';
   $coronavirus_notice_banner_color = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_banner_color')));
   $coronavirus_notice_text_color = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_text_color')));
   echo '<style>
   #coronavirus_notice{background:'.$coronavirus_notice_banner_color.' !important;color:'.$coronavirus_notice_text_color.' !important;}
   a.cnm_btn{border:1px solid '.$coronavirus_notice_text_color.' !important;color:'.$coronavirus_notice_text_color.' !important;}
   a.cnm_close{color:'.$coronavirus_notice_text_color.' !important;}
   </style>'."\n".'<script>';
   switch($coronavirus_notice_location)
   {
    case 'top':
     echo 'jQuery(function() { jQuery(\'#coronavirus_notice\').prependTo(\'body\'); });';
     break;
    case 'top-static':
     echo 'jQuery(function() { jQuery(\'#coronavirus_notice\').clone().removeClass(\'coronavirus_top-static\').removeClass(\'coronavirus_notice_w_adminbar\').addClass(\'coronavirus_top\').prependTo(\'body\'); });';
     break;
    case 'bottom':
     break;
    case 'bottom-static':
     echo 'jQuery(function() { jQuery(\'#coronavirus_notice\').clone().removeClass(\'coronavirus_bottom-static\').addClass(\'coronavirus_bottom\').appendTo(\'body\'); });';
     break;
    case 'inline':
     break;
   }
   echo '</script>'."\n";
  }
 }
}

###################
# Admin Menu Page #
###################
function coronavirus_notice_plugin_menu_page()
{
 add_menu_page('Coronavirus Notice Message','Coronavirus Notice Message','manage_options','coronavirus-covid-19-notice-message','add_admin_coronavirus_notice_plugin','data:image/svg+xml;base64,'.base64_encode('<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="virus" class="svg-inline--fa fa-virus fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="black" d="M483.55,227.55H462c-50.68,0-76.07-61.27-40.23-97.11L437,115.19A28.44,28.44,0,0,0,396.8,75L381.56,90.22c-35.84,35.83-97.11,10.45-97.11-40.23V28.44a28.45,28.45,0,0,0-56.9,0V50c0,50.68-61.27,76.06-97.11,40.23L115.2,75A28.44,28.44,0,0,0,75,115.19l15.25,15.25c35.84,35.84,10.45,97.11-40.23,97.11H28.45a28.45,28.45,0,1,0,0,56.89H50c50.68,0,76.07,61.28,40.23,97.12L75,396.8A28.45,28.45,0,0,0,115.2,437l15.24-15.25c35.84-35.84,97.11-10.45,97.11,40.23v21.54a28.45,28.45,0,0,0,56.9,0V462c0-50.68,61.27-76.07,97.11-40.23L396.8,437A28.45,28.45,0,0,0,437,396.8l-15.25-15.24c-35.84-35.84-10.45-97.12,40.23-97.12h21.54a28.45,28.45,0,1,0,0-56.89ZM224,272a48,48,0,1,1,48-48A48,48,0,0,1,224,272Zm80,56a24,24,0,1,1,24-24A24,24,0,0,1,304,328Z"></path></svg>'));
 add_action('admin_init','register_coronavirus_notice_plugin');
}
add_action('admin_menu','coronavirus_notice_plugin_menu_page');

##################
# Add Admin Page #
##################
function add_admin_coronavirus_notice_plugin()
{
 // Process Form
 if(@$_POST['action']=='save_coronavirus_notice_plugin')
 {
  // Check Nonce
  if(!isset($_POST['coronavirus_notice_field']) || !wp_verify_nonce($_POST['coronavirus_notice_field'],'coronavirus_notice_action')) { echo '<div class="notice notice-warning is-dismissible"><p>Sorry, your nonce did not verify. Please try again.</p></div>'; }
  else
  {
   // Sanitize Input
   $locations = array('top','top-static','bottom','bottom-static','inline');
   $coronavirus_notice_enable = (@$_POST['coronavirus_notice_enable']=='Y') ? 'Y' : '';
   $coronavirus_notice_location = (in_array(sanitize_text_field(@$_POST['coronavirus_notice_location']),$locations)) ? sanitize_text_field(@$_POST['coronavirus_notice_location']) : 'top';
   $coronavirus_notice_headline = sanitize_text_field(sanitize_coronavirus_notice($_POST['coronavirus_notice_headline']));
   $coronavirus_notice_banner_color = sanitize_text_field($_POST['coronavirus_notice_banner_color']);
   $coronavirus_notice_text_color = sanitize_text_field($_POST['coronavirus_notice_text_color']);
   $coronavirus_notice_message = sanitize_textarea_field(sanitize_coronavirus_notice($_POST['coronavirus_notice_message']));
   $coronavirus_notice_read_more_text = sanitize_text_field(sanitize_coronavirus_notice($_POST['coronavirus_notice_read_more_text']));
   $coronavirus_notice_web_page = (@$_POST['coronavirus_notice_web_page']=='*external') ? esc_url_raw($_POST['web_page_external_link']) : esc_url_raw($_POST['coronavirus_notice_web_page']);
   $coronavirus_notice_web_page_external_link = (@$_POST['coronavirus_notice_web_page']=='*external') ? esc_url_raw($_POST['web_page_external_link']) : '';
   $coronavirus_notice_display_web_pages = sanitize_text_field(sanitize_coronavirus_notice(implode(',',$_POST['coronavirus_notice_display_web_pages'])));
   // Check Blanks
   if($coronavirus_notice_enable=='Y' && (!$coronavirus_notice_location || (!$coronavirus_notice_headline && !$coronavirus_notice_message)))
   { echo '<div class="notice notice-error is-dismissible"><p>Error, one or more fields was left blank. Please fill in all fields and try again.</p></div>'; }
   if($coronavirus_notice_enable=='Y' && $coronavirus_notice_read_more_text && !$coronavirus_notice_web_page)
   { echo '<div class="notice notice-error is-dismissible"><p>Error, you have to choose a web page for the "read more" link.</p></div>'; }
   // Save Options
   else
   {
    update_option('coronavirus_notice_enable',$coronavirus_notice_enable);
    update_option('coronavirus_notice_location',$coronavirus_notice_location);
    update_option('coronavirus_notice_headline',$coronavirus_notice_headline);
    update_option('coronavirus_notice_banner_color',$coronavirus_notice_banner_color);
    update_option('coronavirus_notice_text_color',$coronavirus_notice_text_color);
    update_option('coronavirus_notice_message',$coronavirus_notice_message);
    update_option('coronavirus_notice_read_more_text',$coronavirus_notice_read_more_text);
    update_option('coronavirus_notice_web_page',$coronavirus_notice_web_page);
    update_option('coronavirus_notice_web_page_external_link',$coronavirus_notice_web_page_external_link);
    update_option('coronavirus_notice_display_web_pages',$coronavirus_notice_display_web_pages);
    echo '<div class="notice notice-success is-dismissible"><p>Settings have been saved!</p></div>';
   }
  }
 }
 // Get All Posts & Pages
 $posts_pages = array();
 $args = array(
  'post_type' => array('post','page'),
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'ignore_sticky_posts' => true,
  'orderby' => 'post_title',
  'order' => 'ASC',
 );
 $qry = new WP_Query($args);
 foreach($qry->posts as $p)
 {
  $posts_pages[($p->ID)] = $p->post_title;
 }
 wp_reset_postdata();
 // Display Page
 $coronavirus_notice_enable = (@$_POST['action']=='save_coronavirus_notice_plugin') ? $coronavirus_notice_enable : ((get_option('coronavirus_notice_enable')=='Y') ? 'Y' : '');
 $coronavirus_notice_location = (@$_POST['action']=='save_coronavirus_notice_plugin') ? $coronavirus_notice_location : ((get_option('coronavirus_notice_location')=='bottom') ? 'bottom' : 'top');
 $coronavirus_notice_headline = (@$_POST['action']=='save_coronavirus_notice_plugin') ? unsanitize_coronavirus_notice($coronavirus_notice_headline) : esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_headline')));
 $coronavirus_notice_banner_color = (@$_POST['action']=='save_coronavirus_notice_plugin') ? $coronavirus_notice_banner_color : ((get_option('coronavirus_notice_banner_color')) ? esc_html(get_option('coronavirus_notice_banner_color')) : '#CC0000');
 $coronavirus_notice_text_color = (@$_POST['action']=='save_coronavirus_notice_plugin') ? $coronavirus_notice_text_color : ((get_option('coronavirus_notice_text_color')) ? esc_html(get_option('coronavirus_notice_text_color')) : '#ffffff');
 $coronavirus_notice_message = (@$_POST['action']=='save_coronavirus_notice_plugin') ? unsanitize_coronavirus_notice($coronavirus_notice_message) : esc_textarea(unsanitize_coronavirus_notice(get_option('coronavirus_notice_message')));
 $coronavirus_notice_read_more_text = (@$_POST['action']=='save_coronavirus_notice_plugin') ? unsanitize_coronavirus_notice($coronavirus_notice_read_more_text) : esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_read_more_text')));
 $coronavirus_notice_web_page = (@$_POST['action']=='save_coronavirus_notice_plugin') ? esc_url($coronavirus_notice_web_page) : esc_url(get_option('coronavirus_notice_web_page'));
 $coronavirus_notice_web_page_external_link = (@$_POST['action']=='save_coronavirus_notice_plugin') ? esc_url($coronavirus_notice_web_page_external_link) : esc_url(get_option('coronavirus_notice_web_page_external_link'));
 if($coronavirus_notice_web_page_external_link) { $coronavirus_notice_web_page = '*external'; }
 else { $coronavirus_notice_web_page_external_link = ''; }
 $coronavirus_notice_display_web_pages = (@$_POST['action']=='save_coronavirus_notice_plugin') ? explode(',',unsanitize_coronavirus_notice($coronavirus_notice_display_web_pages)) : explode(',',esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_display_web_pages'))));
 echo '
 <div class="wrap">
 <h1>Coronavirus (COVID-19) Notice Message</h1>
 <form method="post">
 <input type="hidden" name="action" value="save_coronavirus_notice_plugin">
 <table class="form-table" role="presentation">
 <tbody>
 <tr>
  <td><div class="gwd_shadow_box"><h2>Settings</h2>
  <fieldset><label for="coronavirus_notice_enable" class="gwd_norm_label"><input name="coronavirus_notice_enable" type="checkbox" id="coronavirus_notice_enable" value="Y"'.(($coronavirus_notice_enable=='Y') ? ' CHECKED' : '').'> Enable Coronavirus (COVID-19) Notice Message</fieldset>
  <fieldset><label>Message location:</label>
  <ul class="gwd_radio_list">
   <li><input id="location_top" type="radio" name="coronavirus_notice_location" value="top"'.(($coronavirus_notice_location=='top') ? ' CHECKED' : '').'><label for="location_top" class="gwd_norm_label"><br><img src="'.plugin_dir_url(__FILE__).'images/location-top.png" alt=""><br>Top</label></li>
   <li><input id="location_top_static" type="radio" name="coronavirus_notice_location" value="top-static"'.(($coronavirus_notice_location=='top-static') ? ' CHECKED' : '').'><label for="location_top_static" class="gwd_norm_label"><br><img src="'.plugin_dir_url(__FILE__).'images/location-top-static.png" alt=""><br>Top Static</label></li>
   <li><input id="location_bottom" type="radio" name="coronavirus_notice_location" value="bottom"'.(($coronavirus_notice_location=='bottom') ? ' CHECKED' : '').'><label for="location_bottom" class="gwd_norm_label"><br><img src="'.plugin_dir_url(__FILE__).'images/location-bottom.png" alt=""><br>Bottom</label></li>
   <li><input id="location_bottom_static" type="radio" name="coronavirus_notice_location" value="bottom-static"'.(($coronavirus_notice_location=='bottom-static') ? ' CHECKED' : '').'><label for="location_bottom_static" class="gwd_norm_label"><br><img src="'.plugin_dir_url(__FILE__).'images/location-bottom-static.png" alt=""><br>Bottom Static</label></li>
   <li><input id="location_inline" type="radio" name="coronavirus_notice_location" value="inline"'.(($coronavirus_notice_location=='inline') ? ' CHECKED' : '').'><label for="location_inline" class="gwd_norm_label"><br><img src="'.plugin_dir_url(__FILE__).'images/location-inline.png" alt=""><br>Inline</label></li>
  </ul>
  <div class="gwd_clear"></div>
  <div id="location_inline_div"><div class="location_inline_div_inner"><label>Copy &amp; Paste Code</label>Copy &amp; paste this code into your web page to display the message.<br><input type="text" name="code" value="[coronavirus_notice]"></div></div></fieldset>
  <fieldset><label>Colors:</label> Background <input class="color_picker" name="coronavirus_notice_banner_color" type="input" id="coronavirus_notice_headline" value="'.$coronavirus_notice_banner_color.'" style="width:400px;"> <span class="gwd_pad15_left">Text <input class="color_picker" name="coronavirus_notice_text_color" type="input" id="coronavirus_notice_headline" value="'.$coronavirus_notice_text_color.'" style="width:400px;"></span></fieldset>
  <fieldset><label>Display On Web Page/Post:</label> <select name="coronavirus_notice_display_web_pages[]"><option value="">(ALL)</option>';
  foreach($posts_pages as $post_id => $post_title)
  {
   echo '<option value="'.$post_id.'"'.((in_array($post_id,$coronavirus_notice_display_web_pages)) ? ' SELECTED' : '').'>'.$post_title.'</option>';
  }
  echo '</select></fieldset>
  </td>
 </tr>
 <tr>
  <td><div class="gwd_shadow_box"><h2>Content</h2>
  <fieldset><label>Enter notice headline:</label><input name="coronavirus_notice_headline" type="input" id="coronavirus_notice_headline" value="'.$coronavirus_notice_headline.'" style="width:400px;"></fieldset>
  <fieldset><label>Enter notice message:</label><textarea name="coronavirus_notice_message" style="width:500px;height:100px;">'.$coronavirus_notice_message.'</textarea></fieldset>
  <fieldset><label>Text for read more link:</label><span class="gwd_gray">(leave blank to not show link)</span><div><input name="coronavirus_notice_read_more_text" type="input" id="coronavirus_notice_read_more_text" value="'.$coronavirus_notice_read_more_text.'" style="width:400px;"></fieldset>
  <fieldset><label>Web page for read more link:</label><select id="coronavirus_notice_web_page" name="coronavirus_notice_web_page"><option value=""></option>';
  //wp_editor($coronavirus_notice_web_page,'coronavirus_notice_web_page',array('teeny' => true,'textarea_rows' => 15,'tabindex' => 1));
  foreach($posts_pages as $post_id => $post_title)
  {
   $link = get_page_link($post_id);
   echo '<option value="'.$link.'"'.(($coronavirus_notice_web_page==$link) ? ' SELECTED' : '').'>'.$post_title.'</option>';
  }
  echo '<option value="*external"'.(($coronavirus_notice_web_page=='*external') ? ' SELECTED' : '').'>(External website/link)</option>';
  echo '</select>
  <div id="web_page_external_link">External URL: <input type="text" name="web_page_external_link" value="'.$coronavirus_notice_web_page_external_link.'"></div></fieldset></td>
 </tr>
 </tbody>
 </table>
 '.wp_nonce_field('coronavirus_notice_action','coronavirus_notice_field').'
 '.get_submit_button().'
 </form>
 <style>
 .gwd_shadow_box{padding:15px 20px;background:#ffffff;border:1px solid #e5e5e5;-webkit-border-radius:2px;border-radius:2px;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.2);box-shadow:0 1px 2px rgba(0,0,0,.2);}
 .gwd_shadow_box h2{margin:0px -20px 5px -20px;padding:0px 20px 18.2px 20px;font-size:18.2px;border-bottom:1px solid #ccc;}
 .gwd_shadow_box fieldset{margin:25px 0px;padding:10px 20px;border-left:5px solid #ccc;-webkit-border-radius:2px;border-radius:2px;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.2);box-shadow:0 1px 2px rgba(0,0,0,.2);}
 .gwd_gray{color:#9d9c9c;}
 .gwd_clear{clear:both;}
 .gwd_shadow_box label{margin-bottom:15px;display:block !important;font-weight:bold;}
 label.gwd_norm_label{;display:inline-block !important;font-weight:normal;}
 .gwd_radio_list{margin:0px;padding:0px;list-style-type:none !important;}
 .gwd_radio_list li{margin:0px 20px;padding:10px;list-style-type:none !important;width:92px;float:left;text-align:center !important;}
 .gwd_pad15_left{padding-left:15px;}
 #location_inline_div{'.(($coronavirus_notice_location=='inline') ? '' : 'display:none;').'}
 .location_inline_div_inner{margin:10px 20px;padding:15px;line-height:32px;background:#d2f1e4;display:inline-block;}
 #location_inline_div label{margin-bottom:5px;}
 #location_inline_div input{width:200px;}
 #web_page_external_link{padding-top:15px;'.(($coronavirus_notice_web_page=='*external') ? '' : 'display:none;').'}
 #web_page_external_link input{width:350px;}
 </style>
 </div>';
}

######################################
# Display Coronavirus Notice Message #
######################################
function display_coronavirus_notice_plugin($is_shortcode)
{
 if(!is_admin())
 {
  $html = '';
  $locations = array('top','top-static','bottom','bottom-static','inline');
  $coronavirus_notice_enable = (get_option('coronavirus_notice_enable')=='Y') ? true : false;
  $coronavirus_notice_location = (in_array(esc_html(get_option('coronavirus_notice_location')),$locations)) ? esc_html(get_option('coronavirus_notice_location')) : 'top';
  $coronavirus_notice_headline = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_headline')));
  $coronavirus_notice_banner_color = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_banner_color')));
  $coronavirus_notice_text_color = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_text_color')));
  $coronavirus_notice_message = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_message')));
  $coronavirus_notice_read_more_text = esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_read_more_text')));
  $coronavirus_notice_web_page = esc_url(get_option('coronavirus_notice_web_page'));
  $coronavirus_notice_web_page_external_link = esc_url(get_option('coronavirus_notice_web_page_external_link'));
  $coronavirus_notice_display_web_pages = array_filter(explode(',',esc_html(unsanitize_coronavirus_notice(get_option('coronavirus_notice_display_web_pages')))));
  if($coronavirus_notice_display_web_pages) { $display_on_this_page = (in_array(get_the_ID(),$coronavirus_notice_display_web_pages)) ? : false; }
  else { $display_on_this_page = true; }
  if($coronavirus_notice_enable && $display_on_this_page && ($coronavirus_notice_location!='inline' || ($coronavirus_notice_location=='inline' && $is_shortcode=='shortcode')))
  {
   $html = '<div id="coronavirus_notice" class="coronavirus_'.$coronavirus_notice_location.((is_admin_bar_showing() && $coronavirus_notice_location=='top-static') ? ' coronavirus_notice_w_adminbar' : '').'"><div class="cnm_inner"><div class="cnm_h2">'.$coronavirus_notice_headline.'</div><div class="cnm_txt">'.$coronavirus_notice_message.'</div>'.(($coronavirus_notice_read_more_text) ? '<div class="cnm_btn_row"><a class="cnm_btn" href="'.$coronavirus_notice_web_page.'"'.(($coronavirus_notice_web_page_external_link) ? ' target="_blank"' : '').'>'.$coronavirus_notice_read_more_text.'</a></div>' : '').(($coronavirus_notice_location!='inline') ? '<a href="#" class="cnm_close" onclick="return close_coronavirus_notice();">X</a>' : '').'</div></div>';
   if($coronavirus_notice_location!='inline') { echo $html; } else { return $html; }
  }
  elseif(!$coronavirus_notice_enable && $display_on_this_page && $coronavirus_notice_location=='inline' && $is_shortcode=='shortcode') { return '[coronavirus_notice]'; }
 }
}
function display_coronavirus_notice_plugin_shortcode() { return display_coronavirus_notice_plugin('shortcode'); }
add_action('wp_footer','display_coronavirus_notice_plugin');

####################################
# Shortcode for Coronavirus Notice #
####################################
add_shortcode('coronavirus_notice','display_coronavirus_notice_plugin_shortcode');

#################
# Sanitize Data #
#################
function sanitize_coronavirus_notice($data)
{
 $data = str_replace(array("\'","'"),"[[SQ]]",$data);
 $data = str_replace(array('\"','"'),'[[DQ]]',$data);
 return $data;
}

###################
# Unsanitize Data #
###################
function unsanitize_coronavirus_notice($data)
{
 $data = str_replace("[[SQ]]","'",str_replace('[[DQ]]','"',$data));
 return $data;
}
?>