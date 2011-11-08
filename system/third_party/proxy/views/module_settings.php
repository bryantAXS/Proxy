<?php

  /*
    This view displays all of the module wide settings that can be 
    edited by the admin.
  */
  
  echo form_open($form_action, '', '');
  
  $this->table->set_template($cp_table_template);
  
  $this->table->set_heading('Proxy Global Settings', '');
  
  $setting_1 = array(
    'n' => 'No'
    ,'y' => 'Yes'
  );
  
  $this->table->add_row('<strong>Substitution Enabled</strong>', form_dropdown('global_substitution_enabled', $setting_1, $settings['global_substitution_enabled']));
  
  echo $this->table->generate();
  
  echo form_submit(array('name' => 'Submit', 'id' => 'submit', 'value' => 'Update', 'class' => 'submit'));
?>