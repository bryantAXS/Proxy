<?php 

require_once PATH_THIRD.'proxy/models/proxy_sql.php';

class Proxy_ext {

    var $name       = 'Proxy';
    var $version        = '1.0';
    var $description    = 'Handles template parsing for the proxy module';
    var $settings_exist = 'n';
    var $docs_url       = ''; // 'http://expressionengine.com/user_guide/';
    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->EE =& get_instance();
        $this->sql = new Proxy_sql();

        $this->EE->load->library('logger');
    }

    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see http://codeigniter.com/user_guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    function activate_extension()
    {

        $hooks = array(
        'channel_entries_row'             => 'channel_entries_row',
        'channel_entries_tagdata'         => 'channel_entries_tagdata',
        );

        foreach ($hooks as $hook => $method)
        {
          $priority = 10;

          $data = array(
          'class'   => __CLASS__,
          'method'  => $method,
          'hook'    => $hook,
          'settings'  => '',
          'priority'  => 9,
          'version' => $this->version,
          'enabled' => 'y'
          );

          $this->EE->db->insert('extensions', $data);
        }

    }

    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }

        if ($current < '1.0')
        {
            // Update to version 1.0
        }

        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->update(
                    'extensions',
                    array('version' => $this->version)
        );
    }

    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    function disable_extension()
    {
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->delete('extensions');
    }

    public function pr($var) { print '<pre>'; print_r($var); print '</pre>'; }

    /**
     * Write items to template debugging log
     *
     * @param      string    String to log
     * @param      int       Tab indention
     * @access     private
     * @return     void
     */
    private function _log($string = FALSE, $indent = 1)
    {
       if ($string)
       {
          $tab = str_repeat('&nbsp;', 7 * $indent);
          $this->EE->TMPL->log_item($tab . '- Proxy -> ' . $string);
       }
    }

    /**
     * Our Hook'ed Method, this handles substitution for single variable tags
     * @private 
     * @param $channel_obj : object with all information for channel being used
     * @param $row : entry row data
     * @return returns row data array
    */
    function channel_entries_row($channel_obj, $row){

        $this->EE->load->model('proxy_settings_model', 'proxy_settings');
        $this->EE->load->model('proxy_field_settings_model', 'field_settings');
        $this->settings = $this->EE->proxy_settings->get_proxy_settings();

        //getting all fields we should be substituting
        $active_fields = $this->_get_active_fields();

        //make sure we are supposed to be substituting
        if($this->settings['global_substitution_enabled'] == 'y'){
          
          if(is_array($active_fields)){

            //looping through each individual proxy settings for each of our active fields
            foreach($active_fields as $field_settings)
            {
              
              //if this particular row (entry) is not for the same channel as the field_setting we are looking at, skip this loop iteration.
              if($field_settings['channel_id'] != $row['channel_id']){
                continue;
              }

              //get some field specific proxy vars
              $substitution_type = $field_settings['substitution_type'];
              $substitution_method = $field_settings['substitution_method'];

              $placeholders = $field_settings['placeholders'];
              if($substitution_method == 'placeholder_index'){              
                $placeholder_index = $field_settings['placeholder_index'];
              }

              $field_id = $field_settings['field_id'];

              
              //depending on what substitution type we are using, we need to have different params for the _get_placeholder() method.
              //each of the endpoints here re-write the field_id value for each row, effectively re-writing the data that will be substituted by the template parser.
              switch($substitution_type)
              {              
                case 'all':
                  
                  if($substitution_method == 'random'){
                    $row['field_id_'.$field_id] = $this->_get_placeholder($placeholders);
                  }else{
                    $row['field_id_'.$field_id] = $this->_get_placeholder($placeholders, $placeholder_index);
                  }

                break;

                case 'empty':
                
                  $field_name = 'field_ft_' . $field_id;

                  if( empty($field_name) ){
                    if($substitution_method == 'random'){
                      $row['field_id_'.$field_id] = $this->_get_placeholder();
                    }else{
                      $row['field_id_'.$field_id] = $this->_get_placeholder($placeholders, $placeholder_index);
                    }
                  }

                break;

                case 'no_substition':
                  //obviously nothing really goes here
                break;
              }
            }

          }

        }

      return $row;
    
    }

    /**
    * This method is used when we are substituting data for tag pairs
    * @param  $tagdata : data between the tag pairs 
    * @return returns the data that should be added between tag pairs
    * @access public
    */
    function channel_entries_tagdata($tagdata, $row, $channel_obj)
    {

      $this->EE->load->model('proxy_settings_model', 'proxy_settings');
      $this->EE->load->model('proxy_field_settings_model', 'field_settings');
      $this->settings = $this->EE->proxy_settings->get_proxy_settings();

      //are we enabled?
      if($this->settings['global_substitution_enabled'] == 'y'){

        //get all the field settings
        $field_settings_arr = $this->EE->field_settings->get_field_settings_by_field_name();

        if(is_array($field_settings_arr)){

          //for all the field settings, lets check for values to substitute
          foreach($field_settings_arr as $field_name => $field_settings)
          {
            if($field_settings['channel_id'] == $row['channel_id'])
            {
              
              //this is where we are looking for the specific tag pair to substitute
              if(strpos($tagdata, '{'.$field_name) !== FALSE)
              {
                $field = array("field_name"=>$field_name);
                $this->field = $field_settings;

                //we don't want preg_replace_callback to execute if the field is set to not substitute, because it will remove the tag pair from the tagdata
                if($this->field['substitution_type'] != "no_substitution")
                {
                  $tagdata = preg_replace_callback("/\{({$field['field_name']}(\s+.*?)?)\}(.*?)\{\/{$field['field_name']}\}/s", array(&$this, '_parse_tag_pair'), $tagdata); 
                }
              }
                      
            }

          }

        }

      }

      return $tagdata;

    }

    function _parse_tag_pair($m)
    {

      // prevent {exp:channel:entries} from double-parsing this tag
      unset($this->EE->TMPL->var_pair[$m[1]]);

      //tags between the pair tags we want to replace
      $tagdata = isset($m[3]) ? $m[3] : '';

      //get field specific swap information
      $substitution_type = $this->field['substitution_type'];
      $substitution_method = $this->field['substitution_method'];

      $placeholders = $this->field['placeholders'];
      if($substitution_method == 'placeholder_index'){              
        $placeholder_index = $this->field['placeholder_index'];
      }

      //get number of loops we need to run
      preg_match("/^[0-9]*/", $placeholders, $number_of_loops);
      $number_of_loops = $number_of_loops[0] > 0 ? $number_of_loops[0] : 1;

      $cell_data = array();

      //we loop through the number of times that are specified in the placeholder field
      for($a = 0; $a < $number_of_loops; $a++)
      {
        switch($substitution_type)
        {              
        case 'all':

          if($substitution_method == 'random'){
            $cell_data[] = $this->_get_pair_placeholders($placeholders);
          }else{
            $cell_data[] = $this->_get_pair_placeholders($placeholders, $placeholder_index);
          }

        break;

        case 'empty':
          
          //TODO: this currently does not work -- we need to find a way to check for the row data in this loop.

          //$field_name = $row['field_id'] . $field_id;
          //if( empty($field_name) ){
            if($substitution_method == 'random'){
              $cell_data[] = $this->_get_pair_placeholders($placeholders);
            }else{
              $cell_data[] = $this->_get_pair_placeholders($placeholders, $placeholder_index);
            }
          //}

        break;

        case 'no_substition':
          //nothing goes on in here
        break;
        }
      }

      $new_tagdata = array();

      //at this point cell_data is an array with the length of the number of times we want it to iterate.  We loop over the array and get all of the field names => values, and build the actual tag data string, which gets returned.
      foreach($cell_data as $row_index => $fields){
        
        $tagdata_row = $tagdata;

        foreach($fields as $field_name => $field_value){
           $tagdata_row = str_replace("{".$field_name."}", $field_value, $tagdata_row);
        }

        $new_tagdata[] = $tagdata_row;

      }

      $tagdata = implode(" ", $new_tagdata);

      return $tagdata;

    }

    //TODO: rename this to get single fields
    function _get_active_fields(){
      
      $active_fields = array();
      $field_settings_arr = $this->EE->field_settings->get_field_settings_by_field_name();
      
      if(is_array($field_settings_arr)){

        foreach($field_settings_arr as $field_name => $field_settings)
        {
            if(in_array($field_name, array_keys($this->EE->TMPL->var_single)))
            {
                $active_fields[] = $field_settings;
            }
        }

      }

      $active_fields = empty($active_fields) ? FALSE : $active_fields;
      
      return $active_fields;
        
    }

    /*
    *  This method is used for single tag (non-pair) tags that will return a single value
    *
    * param: $placeholders - a string of all the possible placeholder values for this specific field
    * param: $placeholder_index - if specified, this is the index of the placeholder value the user wants returned. 
    */
    function _get_placeholder($placeholders, $placeholder_index = FALSE)
    {
      
      $placeholders_arr = explode('||', $placeholders);

      if($placeholder_index >= count($placeholders_arr))
      {
        $this->_log('Invalid placeholder index specified', 1);
        return ''; 
      }

      if($placeholder_index !== FALSE)
      {
        return $placeholders_arr[$placeholder_index];
      }
      else
      {
        return $placeholders_arr[array_rand($placeholders_arr)];
      }
    }

    /*
    * This function breaks up the placeholder string used for tag pairs, and sets appropriate values for each field
    */
    function _get_pair_placeholders($placeholders, $placeholder_index = FALSE)
    {      

      //remove "number of loops" from beginning of string
      $placeholders = preg_replace("/^[0-9]*/", "", $placeholders);

      //gets each individual set of fields wrapped in {}
      preg_match_all("/\{[^\{\}]+\}/", $placeholders, $placeholders_temp_arr);
      $placeholders_temp_arr = $placeholders_temp_arr[0];

      $placeholders_arr = array();
      $return_placeholders_arr = array();

      //loops through each set of variable pairs, which were wrapped in {}
      foreach($placeholders_temp_arr as $i => $placeholder_str){
          
         //remove tag boundaries {}
         $placeholder_str = str_replace('{', '', $placeholder_str);
         $placeholder_str = str_replace('}', '', $placeholder_str);
        
         //get each individual field
         $field_arr = array();

         $fields = explode(",,", $placeholder_str);
          
         foreach($fields as $field){
           $field_data = explode('::', $field);
           $field_name = trim($field_data[0]);
           $field_arr[$field_name] = $field_data[1];
         }

         $placeholders_arr[] = $field_arr; 

      }

      // NOTE: We need to be returning an array keyed by the short field name (ie: field_1) with the 
      // fields value as the array value... so an array with two fields (field_1 and field_2) with the values
      // "aa" and "bb" respectively would need to look like this
      // array("field_1" => "aa", "field_2" => "bb")

      //if we have a placeholder index lets use that placeholder value
      if($placeholder_index){
        return $placeholders_arr[$placeholder_index];
      }else{
        return $placeholders_arr[array_rand($placeholders_arr)];
      }

    }
}
// END CLASS