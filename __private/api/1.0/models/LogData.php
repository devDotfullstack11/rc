<?php

class LogData extends CI_Model {

        public $title;
        public $content;
        public $date;
          public function __construct()
        {
                parent::__construct();
                $this->load->database();
                // Your own constructor code
        }


        public function get_last_ten_entries()
        {
                $query = $this->db->get('rc_data_log', 10);
                return $query->result();
        }

    

        

        public function insert_entry($data)
        {
                /*$this->first_name    = $_POST['title']; // please read the below note
                $this->_name  = $_POST['content'];
                $this->date     = time();
*/
                $this->db->insert('rc_data_log', $data);
        }

   

         public function delete($id){
             $this->db->where('id', $id);
             $this->db->delete('rc_data_log'); 
            return true;
        }


}


 ;?>