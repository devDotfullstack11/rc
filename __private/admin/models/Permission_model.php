<?php

class Permission_model extends CI_Model {

        public $title;
        public $content;
        public $date;
          public function __construct()
        {
                parent::__construct();
                 $this->load->database();
                // Your own constructor code
        }


        public function getUserModules($user_id)
        {       
                $this->db->where('user_id',$user_id);
                $query = $this->db->get('user_modules');
                return $query->result_array();
        }

        public function getModules()
        {
                $query = $this->db->get('modules');                
                return $query->result_array();
        }
        
        public function verifyPermissions($user_id,$module){
                $this->db->where('user_modules.user_id',$user_id);
                $this->db->join("modules","modules.id=user_modules.module_id");
                $this->db->where('name',$module);
                
                $query = $this->db->get('user_modules');
                //echo $this->db->last_query();
                return $query->result_array();
        
        }




}


 ;?>