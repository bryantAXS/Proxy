
<?php

  /*
  This view loops through each channel and each channel's fields and 
  sets up all of the per-field settings that are available to the admin.
  */

	//make these arrays visible in javascript
	echo "<script>var proxy_channels = " . json_encode($channels) . "; var proxy_fields = " . json_encode($fields) . ";</script>";
	  
  $this->table->clear();
  $this->table->set_template($cp_table_template);
	$this->table->set_heading('Channel', 'Field');
		
	$field_dropdowns = array();
		
	foreach($fields as $channel_id => $channel_fields)
	{
		$field_dropdowns[] = get_channel_fields_dropdown($channel_fields, $channel_id);
	}
	
	$this->table->add_row(get_channels_dropdown($channels),implode($field_dropdowns) . '<a href="javascript:;" class="inactive add_field"></a>');
	
	echo "<div class='box'>";
	?>

	<p>
		Delimit paceholders with "||" (no-quotes). For tag pairs with child tags (ie: matrix tags) use the following format, where N is the number of times you want the tagpair to loop: 
		<pre>N||{cell_1::value,,cell_2::value}{cell_1::new value,,cell_2::new_value}</pre> 
	</p>

	<?php
	echo "</div>";

	echo "<div id='add_field_table'>";
	echo $this->table->generate();
	echo "</div>";
	
	echo "<div id='field_tables_container'>";
	
	echo form_open($form_action, '', '');
	   
	if( ! empty($fields_settings) ) {

		foreach($fields_settings as $channel_id => $channel_fields){
		      
		    foreach($channel_fields as $field_id => $field_settings_data){
				
				$channel_id = $field_settings_data['channel_id'];
				$field_id = $field_settings_data['field_id'];
						
				$field_data = $fields[$channel_id][$field_id];
				
				$this->table->clear();
		    $this->table->set_template($cp_table_template);
		    $this->table->set_heading($field_data['channel_title'] .' : '. $field_data['field_label'] . '<input type="hidden" channel_id="'.$channel_id.'" field_id="'.$field_id.'" class="hidden_field_data" />' , '<a class="remove_field"></a><a class="minimize_field"></a>');
	      $this->table->add_row('<span>Field Type</span>',$field_data['field_type']);
		     
				//substituion type dropdown
				$previous_setting = $field_settings_data['substitution_type'];
		    $this->table->add_row('<span>Substitution Type</span></a>', get_substitution_type_dropdown($channel_id, $field_id, $previous_setting));
		      
	      //Substitution Methoid 
	     	$previous_setting = $field_settings_data['substitution_method'];
				$previous_setting_1 = $field_settings_data['placeholder_index'];
	      $this->table->add_row('<span>Substitution Method</span>', get_substitution_method_dropdown($channel_id, $field_id, $previous_setting) . get_placeholder_index_input($channel_id, $field_id, $previous_setting_1));
	      
	      //Placeholders
	      $this->table->add_row('<span>Placeholder(s)</span>', '<textarea rows="10" name="field_'.$channel_id.'_'.$field_id.'[placeholders]">'.stripslashes($field_settings_data['placeholders']).'</textarea>');
		
		    echo $this->table->generate();
		 
		    }
		  }
	}
	
	?>
	
	</div>
	
	<script id="field_table" type="text/x-jquery-tmpl">
    		
		<table class="mainTable" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>${channel_title} : ${field_label}</th>
					<th><a class="remove_field"></a><a class="minimize_field"></a></th>
				</tr>
			</thead>
			<tbody>
				<tr class="even">
					<td><span class="">Field Type</span></td><td>${field_type}</td>
				</tr>
				<tr class="odd">
					<td>
						<span class="">Substitution Type</span>
					</td>
					<td>
						<select name="field_${channel_id}_${field_id}[substitution_type]" class="substitution_type">
							<option value="all">Substitute all data</option>
							<option value="empty">Only substitute empty/blank data</option>
							<option value="no_substitution">Do not substitute</option>
						</select>
					</td>
				</tr>
				<tr class="even">
					<td><span class="">Substitution Method</span></td>
					<td>
						<select name="field_${channel_id}_${field_id}[substitution_method]" class="substitution_method">
							<option value="random">Random</option>
							<option value="option">Placeholder Index</option>
						</select>
						<input type="text" name="field_${channel_id}_${field_id}[placeholder_index]" value="" placeholder="Option..." class="mt10 option_number">
					</td>
				</tr>
				<tr class="odd">
					<td><span>Placeholder(s)</span></td><td><textarea rows="10" name="field_${channel_id}_${field_id}[placeholders]"></textarea></td>
				</tr>
			</tbody>
		</table>

  </script>
	
	<?php
  
  echo form_submit(array('name' => 'Submit', 'id' => 'submit', 'value' => 'Update', 'class' => 'submit'));
