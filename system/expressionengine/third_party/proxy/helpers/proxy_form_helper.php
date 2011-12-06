<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helps generate form elements for the Proxy CP
 */

/**
 * Returns a dropdown of Channels
 *
 * @param string $channel_data 
 * @param string $selected_channel_id 
 * @return void
 * @author Bryant Hughes
 */
function get_channels_dropdown($channel_data, $selected_channel_id = false){
    
  $channel_names = array();
  $channel_names[''] = 'Please select a Channel';
	foreach($channel_data as $key => $value){
    $channel_names[$value['channel_id']] = $value['channel_title'];
  }
  
  $class = "class='channel_dropdown'";
  
  return form_dropdown('channels', $channel_names, $selected_channel_id, $class);
  
}

/**
 * Returns a drop down of the provided channel id's fields.
 *
 * @param string $channel_fields - Should be an array of fields for the channel, indexed by the field_id
 * @param string $channel_id - Channel id the fields are from
 * @param string $selected_field_id 
 * @return void
 * @author Bryant Hughes
 */
function get_channel_fields_dropdown($channel_fields, $channel_id, $selected_field_id = false){
        
  $field_names = array();
  $field_names[''] = 'Please select a Field';

  $class = "class='fields_dropdown'";
  
  foreach($channel_fields as $field => $field_data){
    $field_names[$field_data['field_id']] = $field_data['field_label'];
  }
  
  return form_dropdown('fields_'.$channel_id, $field_names, $selected_field_id, $class);
  
}

function get_substitution_type_dropdown($channel_id, $field_id, $previous_setting = FALSE)
{
	$setting_name = 'field_'.$channel_id.'_'.$field_id.'[substitution_type]';  
	
	$settings = array();
	$settings['all'] = 'Substitute all data';
  $settings['empty'] = 'Only substitute empty/blank data';
  $settings['no_substitution'] = 'Do not substitute';
  
	$dropdown_class = 'class="substitution_type"';
		
  return form_dropdown($setting_name, $settings, $previous_setting ,$dropdown_class);
}

function get_substitution_method_dropdown($channel_id, $field_id, $previous_setting = FALSE)
{
	$setting_name = 'field_'.$channel_id.'_'.$field_id.'[substitution_method]';
	
	$settings = array();
  $settings['random'] = "Random";
  $settings['placeholder_index'] = "Placeholder Index";
  
	$dropdown_class = 'class="substitution_method"';
   
  return form_dropdown($setting_name, $settings, $previous_setting, $dropdown_class);
}


function get_placeholder_index_input($channel_id, $field_id, $previous_setting = '')
{
	$placeholder_index_input = array(
    'name' => 'field_'.$channel_id.'_'.$field_id.'[placeholder_index]'
  );

  $additional_properties = 'placeholder="Placeholder Index..." class="mt10 option_number"';

	return form_input($placeholder_index_input, $previous_setting, $additional_properties);
}

function get_placeholder_type_dropdown($channel_id, $field_id, $previous_setting = FALSE)
{
  
  $setting_name = 'field_'.$channel_id.'_'.$field_id.'[placeholder_type]';
  
  $settings = array();
  $settings['single'] = "Single Tag";
  $settings['loop'] = "Loop";
  
  $dropdown_class = 'class="placeholder_type"';
   
  return form_dropdown($setting_name, $settings, $previous_setting, $dropdown_class);
}

 /* Returns a placeholder tag, either with a single piece of data or for a loop iteration.
 *  
 *
 * @param string/array $data 
 * @return void
 */
function get_placeholder_tag($placeholder, $channel_id, $field_id){
        
  if(is_object($placeholder)){
    
    $placeholder_str = '';              //placeholder string, which gets html characters remove and length trimmed
    $tool_tip_markup = '<div>';         //markup for the tool tip when you hover above the tag
    $i = 0;                     
    
    //loop over each placeholder tag
    foreach($placeholder as $cell_name => $value){
      
      if($i > 0) $placeholder_str .= '  ,  ';
      
      $clean_value = strip_tags($value);
      
      //creating the placeholder string from the clean value.  trimming to 10 characters if necessary.
      $placeholder_str .= strlen($clean_value) > 10 ? $cell_name . ' : ' . trim(substr($clean_value, 0, 10)) . ".." : $cell_name . ' : ' . $clean_value;

      //building tooltip markup. 2 versions, one for a placeholder with html and another for one without
      if($value != strip_tags($value)){
        //contains HTML
        $tool_tip_markup .= "<label>".$cell_name.":</label><code>".htmlspecialchars($value)."</code>";
      }else{
        $tool_tip_markup .= "<label>".$cell_name.":</label><p>".$value."</p>";
      }
      
      $i += 1;
    }

    $tool_tip_markup .= "</div>";

    $markup = "<span class='placeholder-tag-green' title='".$tool_tip_markup."'><small>".$placeholder_str."</small><a href='javascript:;' class='remove-tag'>&nbsp;</a><input value='".json_encode($placeholder)."' name='field_".$channel_id."_".$field_id."[placeholders][]' type='hidden' data-channel_id='".$channel_id."' data-field_id='".$field_id."' /></span>";

  }else{
    
    $clean_placeholder = strip_tags($placeholder);
    $clean_trimmed_placeholder = strlen($clean_placeholder) > 20 ? trim(substr($clean_placeholder, 0, 20)) : $clean_placeholder;

    //tooltip markup
    if($placeholder != strip_tags($placeholder)){
      //contains HTML
      $tool_tip_markup = "<div><code>".htmlspecialchars($placeholder)."</code></div>";
    }else{
      $tool_tip_markup = "<div><p>".$placeholder."</p></div>";
    }

    $markup = "<span class='placeholder-tag-yellow' title='".$tool_tip_markup."'><small>".$clean_trimmed_placeholder."</small><a href='javascript:;' class='remove-tag'>&nbsp;</a><input value='".$placeholder."' name='field_".$channel_id."_".$field_id."[placeholders][]' type='hidden' data-channel_id='".$channel_id."' data-field_id='".$field_id."' /></span>";

  }

  return $markup;
  
}

function get_number_of_loops_field($channel_id, $field_id, $previous_setting = '')
{
  $number_of_loops_input = array(
    'name' => 'field_'.$channel_id.'_'.$field_id.'[number_of_loops]'
  );

  $additional_properties = 'placeholder="Number of Loops" class=""';

  return form_input($number_of_loops_input, $previous_setting, $additional_properties);
}