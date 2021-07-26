<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends My_Controller {

        //$this->load->model(array('data'));
        private $userData = []; 
        function __construct() {
            $this->log_flag = 1;  
            parent::__construct();                                              
            $this->load->model("Common_m");
            $this->load->library('common');
            $this->load->database();
            $extra_flag = 0;
		    $postData = $this->input->post();
            if(empty($postData['version'])){
                $data['error'] ="Please define api version in parameters i.e version : 1.0";
                $this->sendResponse($data, 204, $extra_flag); exit;
            }
           
            //verifyToken($token)
        }

       public function short_rules(){
        $extra_flag = 1;
        //$this->load->model('Common_m');
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        //$offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $this->db->select("id,rule_text,sort_order");
        $short_rules = $this->Common_m->get('rc_site_rules', $where = ['status' => 1,'is_long_rule' => 0], $offset ='-1', $is_single = false, $is_total = false,'ASC','sort_order');
        
        $data['short_rules'] = $short_rules;
		if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
    }

    public function long_rules(){
        $extra_flag = 1;
        //$this->load->model('Common_m');
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        //$offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $this->db->select("id,rule_text,sort_order");
        $short_rules = $this->Common_m->get('rc_site_rules', $where = ['status' => 1,'is_long_rule' => 1], $offset ='-1', $is_single = false, $is_total = false,'ASC','sort_order');
        
        $data['long_rules'] = $short_rules;
		if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
    }

    public function appinfo(){
        $extra_flag = 1;
        $postData = $this->input->post();
        if(!empty($postData['auth_token'])){
            $this->userData =  $this->verifyToken($postData['auth_token']);            
            if(!empty($this->userData)){
                //pr($this->userData); exit;
                $customerInfo = $this->getUserById($this->userData['user_id']);
                // pr($customerInfo);
                $my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $customerInfo['id'],'is_favourite' => 1])->result_array();         
                //$customerInfo['profile_pic']
                $profile_pic = BASE_DOMAIN."/images/user.png";
                if(!empty($customerInfo['profile_pic'])){
                    $profile_pic = BASE_DOMAIN.'/images/users/'.$customerInfo['profile_pic'];
                } 
                $reference_code = $customerInfo['reference_code'];
                if(empty($customerInfo['reference_code'])){
                    $reference_code = $this->generateReferenceCode($customerInfo);

                }
                $data['customer'] =$this->getUserLoginObj($customerInfo['id']);
            } else {
                $data['customer'] =[]; 
            }
            
        } else {
            $data['customer'] =[]; 
        }
        $this->db->where_in("setting_key",['reward_program','privacy_policy']);
        $content = $this->db->get("rc_global_settings")->result_array();
        $finalContentArr = [];
        foreach($content as $c){
            $finalContentArr[$c['setting_key']]= $c['setting_value'];
        }
        $data['site_content'] = $finalContentArr;
        // GETTING PAGE INFO.
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}   
    
    }

    function announcements(){
        $extra_flag =1;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
         $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $this->db->select("a.*,g.id,g.title as group_title,td.short_code as tree_short_code,g.image as group_image,td.main_image as tree_drive_image,a.description as a_description,a.title as a_title,type,a.main_id");
        $this->db->join("rc_groups g","g.id=a.main_id");
        $this->db->join("rc_tree_drives td","td.id=a.main_id");
        $this->db->order_by("created_at","DESC");

        //$announcements = $this->db->get("rc_announcements a")->result_array();
        $announcements = $this->Common_m->get('rc_announcements a', $where = [], $offset, $is_single = false, $is_total = false);
        
        $fGroups =[];
        if(!empty($this->userData)){
            $this->db->where(['user_id' => $this->userData['user_id'] , 'is_favourite' => 1 ]);
            $favorite_groups = $this->db->get("rc_favourite_groups")->result_array();
            $fGroups = array_column($favorite_groups,'group_id');
        }
        $finalArr = [];
        foreach($announcements as $key =>  $group){
        	$finalArr[$key] =[];
        	$finalArr[$key]['trees_drives'] = 546;
        	if($group['type'] == 'tree_drive'){
        		$finalArr[$key]['image'] = BASE_DOMAIN."/images/treedrives/".$announcements[$key]['tree_drive_image'];
        		$finalArr[$key]['title'] = $announcements[$key]['tree_short_code'];
        		$finalArr[$key]['description'] = $announcements[$key]['a_description'];
        	} else if($group['type'] == 'group'){
        		$finalArr[$key]['image'] = BASE_DOMAIN."/images/groups/".$announcements[$key]['group_image'];
        		$finalArr[$key]['title'] = $announcements[$key]['group_title'];
        		$finalArr[$key]['description'] = $announcements[$key]['a_description'];
        		$td = $this->db->get_where("rc_tree_drives",['group_id' => $group['main_id'] ])->result_array();
        		$finalArr[$key]['trees_drives'] = count($td);
        	} 
            
            
            $finalArr[$key]['type'] = $announcements[$key]['type'];
            $finalArr[$key]['main_id'] = $announcements[$key]['main_id'];
            //$groups[$key]['is_favourite'] = empty($groups[$key]['is_favourite'])? 0 : $groups[$key]['is_favourite'];
           // $announcements[$key]['is_favourite'] = (in_array($announcements[$key]['id'],$fGroups)) ? 1 :0;
           // $announcements[$key]['credit'] = 546;
        }
        $data['announcements'] = $finalArr;
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
        
    
    
    }

     function notifications(){
        $extra_flag =1;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
         $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $this->db->select("a.*,g.id,g.title as group_title,td.short_code as tree_short_code,g.image as group_image,td.main_image as tree_drive_image,a.description as a_description,a.title as a_title,type");
        $this->db->join("rc_groups g","g.id=a.main_id");
        $this->db->join("rc_tree_drives td","td.id=a.main_id");
        $this->db->order_by("created_at","DESC");

        //$announcements = $this->db->get("rc_announcements a")->result_array();
        $announcements = $this->Common_m->get('rc_notifications a', $where = [], $offset, $is_single = false, $is_total = false);
        
        $fGroups =[];
        if(!empty($this->userData)){
            $this->db->where(['user_id' => $this->userData['user_id'] , 'is_favourite' => 1 ]);
            $favorite_groups = $this->db->get("rc_favourite_groups")->result_array();
            $fGroups = array_column($favorite_groups,'group_id');
        }
        $finalArr = [];
        foreach($announcements as $key =>  $group){
        	$finalArr[$key] =[];
        	if($group['type'] == 'tree_drive'){
        		$finalArr[$key]['image'] = BASE_DOMAIN."/images/treedrives/".$announcements[$key]['tree_drive_image'];
        		$finalArr[$key]['title'] = $announcements[$key]['tree_short_code'];
        		$finalArr[$key]['description'] = $announcements[$key]['a_description'];
        	} else if($group['type'] == 'group'){
        		$finalArr[$key]['image'] = BASE_DOMAIN."/images/groups/".$announcements[$key]['group_image'];
        		$finalArr[$key]['title'] = $announcements[$key]['group_title'];
        		$finalArr[$key]['description'] = $announcements[$key]['a_description'];
        	} 
            
            //$finalArr[$key]['trees_panted'] = 546;
            $finalArr[$key]['type'] = $announcements[$key]['type'];
            $finalArr[$key]['main_id'] = $announcements[$key]['main_id'];
            //$groups[$key]['is_favourite'] = empty($groups[$key]['is_favourite'])? 0 : $groups[$key]['is_favourite'];
           // $announcements[$key]['is_favourite'] = (in_array($announcements[$key]['id'],$fGroups)) ? 1 :0;
           // $announcements[$key]['credit'] = 546;
        }
        $data['notifications'] = $finalArr;
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
        
    
    
    }
    
    
   

}



