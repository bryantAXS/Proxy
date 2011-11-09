/**
 * Class to control each field table in the CP
 *
 * @return void
 * @author Bryant Hughes
 **/

var Field = function(fields_cp_controller, table){
	
	var self = this;
	
	self.$table;
	self.fields_cp_controller = fields_cp_controller;
		
	if(arguments.length == 1){
		
		var channel_id = $('select[name=channels]').val();
		var field_id = $('.fields_dropdown:visible').val();

		self.channel_name = proxy_channels[channel_id]['channel_name'];
		self.channel_title = proxy_channels[channel_id]['channel_title'];
		self.channel_id = channel_id;
		self.field_name = proxy_fields[channel_id][field_id]['field_name'];
		self.field_label = proxy_fields[channel_id][field_id]['field_label'];
		self.field_id = field_id;
		self.field_type = proxy_fields[channel_id][field_id]['field_type'];
		
	}else{
		
		self.$table = $(table);
		var $hidden_field_data = self.$table.find('.hidden_field_data');
		self.channel_id = $hidden_field_data.attr('channel_id');
		self.field_id = $hidden_field_data.attr('field_id');
	}
	
}

Field.prototype.initialize_existing_field = function(){
	
	var self = this;
	self.bind_table_events();
	return {
		channel_id : self.channel_id
		,field_id : self.field_id
	}
	
}

Field.prototype.bind_table_events = function(){
	
	var self = this;
	
	var substitution_method = self.$table.find('.substitution_method').val();
	if(substitution_method == 'placeholder_index'){
		self.$table.find('.substitution_method').next().show();
	}
	
	self.$table.find('.substitution_method').bind('change',function(){
    
		var $el = $(this);

    if($el.val() == 'placeholder_index'){
      $el.next().show();
    }else{
      $el.next().hide();
    }
    
  });

	self.$table.find('.minimize_field').bind('click',function(){
		self.$table.find('tbody').toggle();
	});
	
	self.$table.find('.remove_field').bind('click',function(){
		self.fields_cp_controller.turn_on_field_option(self.channel_id, self.field_id);
		$(this).parents('table:eq(0)').remove();
	});
 	
}

Field.prototype.create_table = function(){
	
	var self = this;
	
	//rendering the new select field and adding it to the propper location
  $.tmpl($('#field_table'), {
    
		channel_id: self.channel_id
		,field_id : self.field_id
		,channel_title: self.channel_title
		,field_label: self.field_label
		,field_type: self.field_type
    
		}).appendTo('#field_tables_container form');
	
	self.$table = $('#field_tables_container form').find('table:last-child');
	self.bind_table_events();
	
	return {
		channel_id : self.channel_id
		,field_id : self.field_id
	}
	
}

/**
 * Controls the Add Field input forms
 *
 * @return void
 * @author Bryant Hughes
 **/
var Fields_CP_Controller = function(){
	
	var self = this;
	
	//we want to remove options from the field dropdown at the top of the page and setting display:none on the <option> element
	//does not actually hide it in the drop down.  We are going to have to store the markup here so we can recall it later.
	self.removed_fields = {};
		
}

/**
 * Init events for the adding of fields and also the interaction for the add fields form
 *
 * @return void
 * @author Bryant Hughes
 **/

Fields_CP_Controller.prototype.init = function(){
	
	var self = this;
	
	$('select[name=channels]').bind('change',function(){
		
		var channel_id = $(this).val();
		if(channel_id > 0){
			
			//turn off all other fields and reset their value
			var $fields = $('.fields_dropdown').not('[name=fields_'+channel_id+']');
			$fields.unbind('change');
			$fields.hide();
			$fields.val($('option:first-child', $fields));
				
			//turn on new field
			var $new_field = $('.fields_dropdown[name=fields_'+channel_id+']');
			$new_field.css({display:'inline'});
			self.bind_select($new_field);
			
			$('.add_field').addClass('inactive');
			
		}else{
			
			//turn off fields
			$('.fields_dropdown').hide();
			$('.add_field').addClass('inactive');
			
		}
		
	});
	
	$('.add_field').bind('click',function(){
		
		if( ! $(this).hasClass('inactive')){
			var field = new Field(self);
			var field_data = field.create_table();
			
			self.turn_off_field_option(field_data.channel_id, field_data.field_id);
		}

	});
	
	self.init_existing_field_tables();
	
}

Fields_CP_Controller.prototype.init_existing_field_tables = function(){
	
	var self = this;
	
	$('#field_tables_container table').each(function(){
		
		var el = this;
		var field = new Field(self, el); 
		var field_data = field.initialize_existing_field();
		
		self.turn_off_field_option(field_data.channel_id, field_data.field_id);
		
	});
	
}

Fields_CP_Controller.prototype.turn_off_field_option = function($channel_id, $field_id){
	
	var self = this;
	
	var dropdown = $('select[name=fields_'+$channel_id+']');
	$field = dropdown.find('option[value='+$field_id+']');
	self.removed_fields[$channel_id+'_'+$field_id] = $field;
	$field.remove();
	
}

Fields_CP_Controller.prototype.turn_on_field_option = function($channel_id, $field_id){
	
	var self = this;
	
	var dropdown = $('select[name=fields_'+$channel_id+']');
	$field = self.removed_fields[$channel_id+'_'+$field_id];
	dropdown.append($field);
	
}

/**
 * Each time a new channel is selected, the sister field select is made visible and then a change event is bound
 *
 * @return void
 * @author Bryant Hughes
 **/
Fields_CP_Controller.prototype.bind_select = function($new_field){
	
	var self = this;
	
	$new_field.bind('change',function(){
		
		var field_id = $(this).val();
		if(field_id > 0){
				
			$('.add_field').removeClass('inactive');
		
		}else{
			
			$('.add_field').addClass('inactive');
		}
		
	});
	
}

$(document).ready(function(){
  	
	if($('#add_field_table').length){
		var field_cp_controller = new Fields_CP_Controller();
		field_cp_controller.init();
	}
			
});