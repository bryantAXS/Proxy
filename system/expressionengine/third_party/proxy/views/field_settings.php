
<?php

	class Field{
		var $channel_id = null;
		var $field_id = null;
		var $placeholder_type = null;
		var $placeholders = null;
	}

	$loop_placeholders_arr = array();
	$loop_placeholders_arr[]= array('cell_1' => 'aa', 'cell_2' => 'bb');
	$loop_placeholders_arr[]= array('cell_1' => 'cc', 'cell_2' => 'dd');

  /*
  This view loops through each channel and each channel's fields and 
  sets up all of the per-field settings that are available to the admin.
  */

	//make these arrays visible in javascript
	echo "<script>var proxy_channels = " . json_encode($channels) . "; var proxy_fields = " . json_encode($fields) . ";</script>";
	
	//setting up our first table
  $this->table->clear();
  $this->table->set_template($cp_table_template);
	$this->table->set_heading('Channel', 'Field');
			
	//setting up the field dropdown data
	$field_dropdowns = array();
	foreach($fields as $channel_id => $channel_fields)
	{
		$field_dropdowns[] = get_channel_fields_dropdown($channel_fields, $channel_id);
	}
	
	//adding top most row, to select channel and channel fields
	$this->table->add_row(get_channels_dropdown($channels),implode($field_dropdowns) . '<a href="javascript:;" class="inactive add_field"></a>');
	
	?>
	
	<div class='box'>
		<p> 
			Delimit paceholders with "||" (no-quotes). For tag pairs with child tags (ie: matrix tags) use the following format, where N is the number of times you want the tagpair to loop: 
			<pre>N||{cell_1::value,,cell_2::value}{cell_1::new value,,cell_2::new_value}</pre> 
		</p>
	</div>

	<div id='add_field_table'>
		<?php echo $this->table->generate(); ?>
	</div>

	<div id='field_tables_container'>

	<?php

	echo form_open($form_action, '', '');
	   
	if( ! empty($fields_settings) ) {

		foreach($fields_settings as $channel_id => $channel_fields){
		      
		    foreach($channel_fields as $field_id => $field_settings_data){
				
				$channel_id = $field_settings_data['channel_id'];
				$field_id = $field_settings_data['field_id'];

				$field_data = $fields[$channel_id][$field_id];
				
				
				//creating new field object -- this will eventually be moved out of this view and into its own model
				$field = new Field();
				$field->channel_id = $channel_id;
				$field->field_id = $field_id;
				
				$field->placeholder_type = $field_settings_data['placeholder_type'];
				$field->placeholders = $field_settings_data['placeholders'];
				
				$field->channel_title = $field_data['channel_title'];
				$field->field_label = $field_data['field_label'];
				$field->field_type = $field_data['field_type'];

				$field->substitution_type = $field_settings_data['substitution_type'];
				$field->substitution_method = $field_settings_data['substitution_method'];
				$field->placeholder_index = $field_settings_data['placeholder_index'];
				$field->placeholder_type = $field_settings_data['placeholder_type'];
				$field->number_of_loops = $field_settings_data['number_of_loops'];

				$field->placeholders = $field_settings_data['placeholders'];

				//start field table
				$this->table->clear();
		    $this->table->set_template($cp_table_template);
		    $this->table->set_heading($field->channel_title .' : '. $field->field_label . '<input type="hidden" channel_id="'.$field->channel_id.'" field_id="'.$field->field_id.'" class="hidden_field_data" />' , '<a class="remove_field"></a><a class="minimize_field"></a>');
	      
	      //field type
	      $this->table->add_row('<span>Field Type</span>',$field->field_type);
		     
				//substituion type dropdown
		    $this->table->add_row('<span>Substitution Type</span></a>', get_substitution_type_dropdown($field->channel_id, $field->field_id, $field->substitution_type));
		      
	      //Substitution Methoid 
	      $this->table->add_row('<span>Substitution Method</span>', get_substitution_method_dropdown($field->channel_id, $field->field_id, $field->substitution_method) . get_placeholder_index_input($field->channel_id, $field->field_id, $field->placeholder_index));

	      //Placeholder Type
	      $this->table->add_row('<span>Placeholder Type</span>', get_placeholder_type_dropdown($field->channel_id, $field->field_id, $field->placeholder_type));

	      //Number of Loops
	      $number_of_loops = isset($field->number_of_loops) ? $field->number_of_loops : 0;
	      $this->table->add_row('<span>Number of Loops</span>', get_number_of_loops_field($field->channel_id, $field->field_id, $number_of_loops));
	      
	      $single_input_container_class = $field->placeholder_type == "single" ? "on" : "";
	      $loop_input_container_class = $field->placeholder_type == "loop" ? "on" : "";

	      //Add Data Row
	      $this->table->add_row('<span>Add Data</span>', '<div class="'.$single_input_container_class.' single_input_container has-layout"><textarea rows="5" class="placeholder_input"></textarea><a href="javascript:;" class="button block mt10 mb5">Add Placeholder</a></div><div class="'.$loop_input_container_class.' loop_input_container has-layout"><label class="placeholder_label">Tag Name</label><input type="text" class="tag_names_input" /><label class="placeholder_label">Placeholder</label><textarea class="placeholder_input" rows="5" name=""></textarea><a href="javascript:;" class="button block mt10 mb5">Add Placeholder</a></div>');	
		
				//create placeholder tags
				if($field->placeholder_type == 'single'){
					$placeholder_tags = array();

					//here after we decode, we are sending in actual string data
					foreach(json_decode($field->placeholders) as $placeholder)
					{
						$placeholder_tags[] = get_placeholder_tag($placeholder, $field->channel_id, $field->field_id);
					}	
				}else{
					$placeholder_tags = array();

					//here we are decoding an array of objects and passing the objects into the tag builder
					foreach(json_decode($field->placeholders) as $single_placeholder_loop_data)
					{
						$placeholder_tags[] = get_placeholder_tag($single_placeholder_loop_data, $field->channel_id, $field->field_id);
					}
				}
				
				//Placeholders Row 
				$this->table->add_row('<span>Placeholders</span>', "<div class='placeholder_tags_container'>".implode($placeholder_tags)."</div>");

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
							<option value="placeholder_index">Placeholder Index</option>
						</select>
						<input type="text" name="field_${channel_id}_${field_id}[placeholder_index]" value="0" class="mt10 option_number">
					</td>
				</tr>
				<tr class="odd">
					<td><span>Placeholder Type</span></td><td><select name="field_${channel_id}_${field_id}[placeholder_type]" class="placeholder_type">
					<option value="single" selected="selected">Single Tag</option>
					<option value="loop">Loop</option>
					</select></td>
				</tr>
				<tr class="even" style="display: table-row; ">
					<td>
						<span>Number of Loops</span>
					</td>
					<td>
						<input type="text" name="field_${channel_id}_${field_id}[number_of_loops]" value="0" placeholder="Number of Loops" class="">
					</td>
				</tr>
				<tr class="odd">
					<td>
						<span>Add Data</span>
					</td>
					<td>
						<div class="on single_input_container has-layout">
							<textarea rows="5" class="placeholder_input"></textarea>
							<a href="javascript:;" class="button block mt10 mb5">Add Placeholder</a>
						</div>
						<div class="loop_input_container has-layout">
							<label class="placeholder_label">Tag Name</label>
							<input type="text" class="tag_names_input">
							<label class="placeholder_label">Placeholder</label>
							<textarea class="placeholder_input" rows="5" name=""></textarea>
							<a href="javascript:;" class="button block mt10 mb5">Add Placeholder</a>
						</div>
					</td>
				</tr>
				<tr class="even">
					<td><span>Placeholders</span></td><td><div class='placeholder_tags_container'></div></td>
				</tr>

			</tbody>
		</table>

  </script>

  <script id='single_tag' type="text/x-jquery-tmpl">
  	
    <span class='placeholder-tag-yellow'>
    	<small>${placeholder}</small>
    	<a href='javascript:;' class='remove-tag'>&nbsp;</a>
    	<input value='${placeholder}' name='field_${channel_id}_${field_id}[placeholders][]' type='hidden' />
    </span>

  </script>
	
	<script id='loop_tag' type='text/x-jquery-tmpl'>
		
		<span class='placeholder-tag-green'>
	  	<small>${placeholder_str}</small>
	  	<a href='javascript:;' class='remove-tag'>&nbsp;</a>
	  	<input value='${placeholder_json}' name='field_${channel_id}_${field_id}[placeholders][]' type='hidden' />
	  </span>
	
	</script>

	<?php
  
  echo form_submit(array('name' => 'Submit', 'id' => 'submit', 'value' => 'Update', 'class' => 'submit'));
