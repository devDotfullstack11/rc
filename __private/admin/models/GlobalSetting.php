<?php

class GlobalSetting extends CI_Model {

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
                $query = $this->db->get('global_settings', 10);
                return $query->result();
        }

        public function get_all()
        {
                $query = $this->db->get('global_settings');
                return $query->result();
        }

        

        public function insert_entry($data)
        {
                /*$this->first_name    = $_POST['title']; // please read the below note
                $this->_name  = $_POST['content'];
                $this->date     = time();
                */
                $this->db->insert('global_settings', $data);
        }

        public function update_entry()
        {
                $this->title    = $_POST['title'];
                $this->content  = $_POST['content'];
                $this->date     = time();
                $this->db->update('global_settings', $this, array('id' => $_POST['id']));
        }

        public function update_entry_by_key($key,$data)
        { 
               
                $this->db->update('global_settings', $data, array('setting_key' => $key ) );
        }

        

        public function get_current_page_records($limit, $start,$conditons)
        {   
            //echo $limit;
            $this->db->limit($limit, $start);
            if(isset($conditons['start_date']) && isset($conditons['end_date'])){
                $this->db->where('expense_date >=',$conditons['start_date']);
                $this->db->where('expense_date <=', $conditons['end_date']);
            }
            $query = $this->db->get("global_settings");
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $data[] = $row;
                }
                 
                return $data;
            }
          
            return false;
        }

        public function get_total()
        {   
             if(isset($conditons['start_date']) && isset($conditons['end_date'])){
                $this->db->where('expense_date >=',$conditons['start_date']);
                $this->db->where('expense_date <=', $conditons['end_date']);
            }
            $this->db->from("global_settings");
            $total = $this->db->count_all_results();
            return $total;

            //return $this->db->count_all("global_settings");
        }

         public function get_one($id){
            $details = $this->db->get_where('global_settings', array('id' => $id));
            $record_arr = $details->first_row('array');
            return $record_arr;
        }

        public function get_one_by_key($key){
            $details = $this->db->get_where('global_settings', array('setting_key' => $key));
            $record_arr = $details->first_row('array');
            return $record_arr;
        }

       

        


}


 ;?>