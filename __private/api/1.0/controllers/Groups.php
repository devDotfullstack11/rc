<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends My_Controller {

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
            if(empty($postData['auth_token'])){
                $extra_flag = 2;
                $data['error'] ="Auth Token missing!!. add token in parameters i.e auth_token : abc ";
                $this->sendResponse($data, 204, $extra_flag); exit;
            } else {
                $extra_flag = 2;
                $this->userData =  $this->verifyToken($postData['auth_token']);
                if(empty($this->userData)){
                    $data['error'] ="Invalid auth token!! ";
                    $this->sendResponse($data, 204, $extra_flag); exit;  
                }

            }
            //verifyToken($token)
        }

       public function index(){
            $extra_flag = 1;
            //$this->load->model('Common_m');
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;

            $this->db->where(['user_id' => $this->userData['user_id'] , 'is_favourite' => 1 ]);
            $favorite_groups = $this->db->get("rc_favourite_groups")->result_array();
            $fGroups = array_column($favorite_groups,'group_id');
            $this->db->select("g.id,g.title,g.description,g.image");
            
            $where = ['status' => 1];
            $groups = $this->Common_m->get('rc_groups g', $where, $offset, $is_single = false, $is_total = false);
            foreach($groups as $key =>  $group){
                $groups[$key]['image'] = BASE_DOMAIN."/images/groups/".$groups[$key]['image'];
                $groups[$key]['trees_panted'] = 546;
                //$groups[$key]['is_favourite'] = empty($groups[$key]['is_favourite'])? 0 : $groups[$key]['is_favourite'];
                $groups[$key]['is_favourite'] = (in_array($groups[$key]['id'],$fGroups)) ? 1 :0;
                $groups[$key]['credit'] = 546;
            }
            $data['groups'] = $groups;
            if(isset($data['errors'])){
                $this->sendResponse($data, 204, $extra_flag);
            } else {
                $this->sendResponse($data, 200, $extra_flag);
            }
    }
    
    function detail(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['group_id']) || empty($postData['group_id']) || $postData['group_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'group_id' is missing.";  
        } else {
            $this->db->select("id,title");
            $group = $this->Common_m->get('rc_groups', $where = ['id' => $postData['group_id'] ], 0, $is_single = true, $is_total = false);
            if(!empty($group)){
                // GETTING LIST OF GROUP TREE DRIVES
                $extra_flag = 1;
                $data['group'] =  $group;
                //$this->db->select("id,short_code,title");
                $this->db->select("td.id,td.short_code,td.title,td.main_image,td.created_at,g.title as group,td.price");
                $this->db->join("rc_groups g","g.id=td.group_id");
                //$rc_tree_drives = $this->Common_m->get('rc_tree_drives', $where = ['group_id' => $postData['group_id'],'status' => 1,'is_draw_declared' => 0 ], 0, $is_single = false, $is_total = false);                   
                //$this->db->select("id,short_code,title,main_image");
                $rc_tree_drives = $this->Common_m->get('rc_tree_drives td', $where = ['group_id' => $postData['group_id'],'td.status' => 1,'is_draw_declared' => 0 ], -1, $is_single = false, $is_total = false);                   
                foreach($rc_tree_drives as $key =>  $td){
                    $rc_tree_drives[$key]['short_code'] =  "TDE".$rc_tree_drives[$key]['id'];
                    $rc_tree_drives[$key]['tree_planted'] = $rc_tree_drives[$key]['id'];
                    $rc_tree_drives[$key]['main_image'] =  BASE_DOMAIN."/images/treedrives/".$td['main_image'];
                }
                $data['tree_drives'] = $rc_tree_drives;
            } else {
                $data['error'] ="Invalid group ID.";
            }

        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
        
    }

    function manage_favourite(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['group_id']) || empty($postData['group_id']) || $postData['group_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'group_id' is missing.";  
        } else if(!isset($postData['is_favourite']) ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'is_favourite' is missing.";  
        } else {
            //$this->db->select("id,title");
            $extra_flag = 1;
            $where = ['group_id' => $postData['group_id'],'user_id' =>  $this->userData['user_id'] ];
            $rc_favourite_groups = $this->Common_m->get('rc_favourite_groups', $where, 0, $is_single = true, $is_total = false);
            $prepareArr = [
                'group_id' => $postData['group_id'],
                'is_favourite' => ($postData['is_favourite'] == 0) ? 1 : 0,
                'user_id' =>  $this->userData['user_id'],
            ];
            //pr($prepareArr);
            if(!empty($rc_favourite_groups)){
                $this->db->update("rc_favourite_groups",$prepareArr,$where);
            } else {
                $this->db->insert("rc_favourite_groups",$prepareArr);             
            }
            $data['message'] ="Updated!";
        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
        
    }
    /* 
    * User's Favourite Groups    
    */

    public function my_groups(){
        $extra_flag = 1;
        //$this->load->model('Common_m');
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $this->db->where("is_favourite",1);
        //$this->db->where("g.user_id",$this->userData['user_id']);
        $this->db->select("g.id,g.title,g.description,g.image,is_favourite");
        $this->db->join("rc_groups g","fg.group_id=g.id","inner");
        $this->db->group_by("g.id");
        $groups = $this->Common_m->get('rc_favourite_groups fg', $where = ['status' => 1], $offset, $is_single = false, $is_total = false);
        foreach($groups as $key =>  $group){
            $groups[$key]['image'] = BASE_DOMAIN."/images/groups/".$groups[$key]['image'];
            $groups[$key]['trees_panted'] = 546;
            $groups[$key]['is_favourite'] = empty($groups[$key]['is_favourite'])? 0 : $groups[$key]['is_favourite'];
            $groups[$key]['credit'] = 546;
        }
        $data['groups'] = $groups;
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    }

    /* 
    * Function for creating / Updating Group
    *     
    */

    function manage(){
        $postData = $this->input->post();
        $this->load->library('form_validation');
        $name = date('Ymdhis').md5(rand(999,9999));
        $config['upload_path']          = BASE_UPLOAD_PATH.'images/groups';
        $config['file_name']          = $name;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'],777);
        }
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
            $config  = [
                [
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required|max_length[100]|min_length[2]'
                ],[
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|max_length[100]|min_length[2]'
                ]
            ];
            
            //$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
    
            $this->form_validation->set_rules($config);

            if($this->form_validation->run() == TRUE){
            // GETTING USER INFO
            $groupInfo =[];
            $where = [];
            if(isset($postData['id']) && !empty($postData['id'])){
                $groupInfo =   $this->db->get_where("rc_groups",['id' => $postData['id']])->row_array();
                $where = ['id' =>  $groupInfo['id'] ];
            }
            $extra_flag = 1;
            //$customerInfo = $this->getUserById($this->userData['user_id']);
           // if(!empty($customerInfo)){
                // 
                $saveArr = [
                    'title' => $postData['title'],
                    'description' => $postData['description'],
                    'user_id' => $this->userData['user_id']
                ];                
                //$this->db->update("rc_users",$saveArr,$where);
                // CHECKING IF IMAGE UPLOADED
                if(isset($_FILES['image'])  && isset($_FILES['image']['name']) && !empty($_FILES['image']['name']) && empty($data['error']) ){
                    if ( ! $this->upload->do_upload('image')) { 
                        //$extra_flag = 0;
                        $inputFileName = isset($groupInfo['image']) ? $groupInfo['image'] : '';
                        //$data['error'] = $this->upload->display_errors();
                        //pr($this->upload->display_errors());
                    } else {
                       $img = $this->upload->data(); 
                       $inputFileName = $img['file_name'];
                       if(isset($groupInfo['image']) &&  !empty($groupInfo['image'])){
                        @unlink(BASE_UPLOAD_PATH.'images/groups/'.$groupInfo['image']);
                       }                       
                    }
                    $saveArr['image'] = $inputFileName;                    
                                     
                }

                if(!empty($where)){
                        $this->db->update("rc_groups",$saveArr,$where); 
                        $data['success'] ="Group Information Updated."; 
                    } else {
                        $this->db->insert("rc_groups",$saveArr); 
                        $data['success'] ="Group Created."; 
                    }

        } else {
            $extra_flag = 0;
            $data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));
        }

        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    
    
    }

    function user_created_groups(){
        $extra_flag = 1;
     
        $this->db->where("user_id",$this->userData['user_id']);
        $this->db->select("g.id,g.title");
       // $this->db->join("rc_groups g","fg.group_id=g.id","inner");
        $this->db->group_by("g.id");
        $groups = $this->Common_m->get('rc_groups', $where = ['status' => 1], -1, $is_single = false, $is_total = false);
        
        $data['groups'] = $groups;
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }  
    }

    function deleted(){
        $id = $_POST['id'];
        $extra_flag =0;
        if(!empty($id)){
            $where = ['id' =>  $id];
            $treedrives = $this->db->get_where("rc_groups",['group_id' =>  $id])->result_array();
            if(empty($treedrives)){
               // $data = ['is_deleted' => 1];
                $this->db->where($where);
                $this->db->delete("rc_groups");
                $extra_flag =1;
                $data['success']="OK";
            } else {
                $extra_flag =0;
                $data['error'] = "Group can not be deleted as it has tree drives in it";
            }
            
            //$result = ['status' => "success" , 'message' => 'Deleted!'];
        } else {
            $data['error'] = "ID parameter is Missing";
            //$result = ['status' => "failed" , 'message' => 'Something went wrong'];
        }
        //echo json_encode($result); exit;
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
      }
   



}



