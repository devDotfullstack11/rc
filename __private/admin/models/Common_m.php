<?php

class Common_m extends CI_Model {

          public function __construct()
        {
                parent::__construct();
                 $this->load->database();
                // Your own constructor code
        }

		 public function save($table_name, $data, $where = []){
			if(empty($where)){
				$this->db->insert($table_name, $data);
				return $this->db->insert_id();
			} else {
				$this->db->where($where);
				return $this->db->update($table_name, $data);
			}
		}
		
		public function get($table_name, $where = [], $offset = 0, $is_single = false, $is_total = false,$order_by_feild ='',$order_by='',$getAll = 0){
			$is_having_query = false;
			$this->db->from($table_name);
			
			if(!empty($where)){

				if(isset($where['search_query'])){
					foreach($where['search_query'] as $squery){
						$this->db->where($squery);
					}
					unset($where['search_query']);
				}
				if(isset($where['having_query'])){
					foreach($where['having_query'] as $having_field => $having_val){
						$this->db->having($having_field, $having_val);
					}
					unset($where['having_query']);
					$is_having_query = true;
				}

				$this->db->where($where);
				
				if($is_having_query == true && $is_total == true){
					return count($this->db->get()->result_array());
				}
			}
			
			if(!$is_total){
				if(!empty($order_by_feild) && !empty($order_by)) {
					$this->db->order_by($order_by_feild,$order_by);
				}
				if($is_single){
						return $this->db->get()->row_array();
				} else {
					if($offset >= 0 && $getAll == 0){
						$this->db->limit(10, $offset);
					}
					return $this->db->get()->result_array();
				}
			} else {
				return $this->db->count_all_results();
			}

		}

}


 ;?>