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

 /* Returns a placeholder tag, either with a single piece of data or for a loop iteration
 *
 * @param string $channel_fields - Should be an array of fields for the channel, indexed by the field_id
 * @param string $channel_id - Channel id the fields are from
 * @param string $selected_field_id 
 * @return void
 * @author Bryant Hughes
 */
function get_placeholder_tag($data){
        
  if(is_array($data)){
    
  }else{
    
    $markup = "<span class='placeholder-tag-yellow'><small>".$data."</small><a href='javascript:;' class='remove-tag'>&nbsp;</a></span>";

  }

  return $markup;
  
}














