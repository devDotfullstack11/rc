<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Treedrive extends My_Controller {

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

   
    
    function detail(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['id']) || empty($postData['id']) || $postData['id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'id' is missing.";  
        } else {
            $this->db->select("id,title,short_code,price,description,slotes_available,main_image,info_image,info_title,info_description");
            $group = $this->Common_m->get('rc_tree_drives', $where = ['id' => $postData['id'] ], 0, $is_single = true, $is_total = false);
            if(!empty($group)){
                // GETTING LIST OF GROUP TREE DRIVES
                $extra_flag = 1;
                $data['tree_drive'] =  $group;                
                $data['tree_drive']['main_image'] =  BASE_DOMAIN."/images/treedrives/".$group['main_image'];;                
                $data['tree_drive']['prizes'] =  [];
                $this->db->select("prize_text,u.name,prize_position,profile_pic");                
                $this->db->join("rc_users u","u.id=tdp.winner_user_id","LEFT");
                $prizes = $this->Common_m->get('rc_tree_drive_prizes tdp', $where = ['tree_drive_id' => $postData['id'] ], -1, $is_single = false, $is_total = false,'ASC','prize_position'); 
                //  $profile_pic = BASE_DOMAIN."/images/user.png";
                // if(!empty($customerInfo['profile_pic'])){
                //     $profile_pic = BASE_DOMAIN.'/images/users/'.$customerInfo['profile_pic'];
                // }
                foreach ($prizes as $pkey => $pvalue) {
                     $prizes[$pkey]['description'] = $group['description'];
                     $prizes[$pkey]['profile_pic'] = BASE_DOMAIN."/images/user.png";
                if(!empty($customerInfo['profile_pic'])){
                    $prizes[$pkey]['profile_pic'] = BASE_DOMAIN.'/images/users/'.$pvalue['profile_pic'];
                }
                }
                $data['tree_drive']['prizes'] = $prizes ;

                $this->db->select("id,rule_text,is_question,question,answer");
                $treedrive_rules = $this->Common_m->get('rc_tree_drive_rules', $where = ['tree_drive_id' => $postData['id'] ], -1, $is_single = false, $is_total = false,'ASC','id'); 
                // FOR THE INFORMATION SCREEN
                //pr($this->userData);
                foreach($treedrive_rules as $key => $rule){
                    $treedrive_rules[$key]['user_answer'] = '';
                    $answer = $rule['answer'];
                    $treedrive_rules[$key]['answer'] = $answer." ";
                    //$treedrive_rules[$key]['answer'] = rtrim($treedrive_rules[$key]['answer']);
                    //$treedrive_rules[$key]['answer'] = (string) $rule['answer']."";
                    if($rule['is_question']){
                      $answer = $this->Common_m->get('rc_user_rule_answers', $where = ['rule_id' => $rule['id'],'user_id' => $this->userData['user_id'] ], -1, $is_single = true, $is_total = false);
                      if(!empty($answer)){
                        $treedrive_rules[$key]['user_answer'] = "{$answer["answer"]} ";
                        //var_dump($treedrive_rules[$key]['user_answer']);
                      }   
                    }
                }
                $data['tree_drive']['rules'] = $treedrive_rules;

                // FOR THE RULES SCREEN
                $data['tree_drive']['info'] = [
                    'info_title' => $group['info_title'],
                    'info_description' => $group['info_description'],
                    'info_image' => BASE_DOMAIN."/images/treedrives/".$group['info_image'],
                    'prizes' => $data['tree_drive']['prizes']
                ];
            } else {
                $data['error'] ="No Record Found.";
            }

        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
        
    }

    function save_answer(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['rule_id']) || empty($postData['rule_id']) || $postData['rule_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'rule_id' is missing.";  
        } else if(!isset($postData['answer']) || empty($postData['answer']) || $postData['answer'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'answer' is missing.";  
        } else {
            $extra_flag =1;
          
            $answer = $this->Common_m->get('rc_user_rule_answers', $where = ['rule_id' => $postData['rule_id'],'user_id' => $this->userData['user_id'] ], -1, $is_single = true, $is_total = false);
            $saveArr =[
                'user_id' => $this->userData['user_id'],
                'rule_id' => $postData['rule_id'],
                'answer' => $postData['answer']
            ];
            if(!empty($answer)){
                // GETTING LIST OF GROUP TREE DRIVES
                $this->db->update("rc_user_rule_answers",$saveArr,['id' => $answer['id'] ]);
                $data['success'] = 'Answer Updated!';
            } else {
                $this->db->insert("rc_user_rule_answers",$saveArr);
                $data['success'] = 'Answer Saved!';
            }

        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
            
            $this->sendResponse($data, 200, $extra_flag);
		}    
        

    }
    /* 
    * Getting List of ALL TREE DRIVES
    * 
    */
    function all(){
        $extra_flag = 1;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $sort_column = isset($_POST['sort_column']) ? $_POST['sort_column'] : 'created_at';
        $sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'ASC';
        // PRICE , created_at , group
        $this->db->join("rc_groups g","g.id=td.group_id");
        $this->db->select("td.id,td.short_code,td.title,td.main_image,td.created_at,g.title as group,td.price");
        $this->db->order_by($sort_column,$sort_order);
        $rc_tree_drives = $this->Common_m->get('rc_tree_drives td', $where = ['td.status' => 1,'is_draw_declared' => 0 ], $offset, $is_single = false, $is_total = false);                   
        foreach($rc_tree_drives as $key =>  $td){
            $rc_tree_drives[$key]['short_code'] =  "TDE".$rc_tree_drives[$key]['id'];
            $rc_tree_drives[$key]['tree_planted'] =  $rc_tree_drives[$key]['id']; // STATIC FOR NOW
            $rc_tree_drives[$key]['main_image'] =  BASE_DOMAIN."/images/treedrives/".$td['main_image'];
        }
        $data['tree_drives'] = $rc_tree_drives;
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {            
            $this->sendResponse($data, 200, $extra_flag);   
		}
    }

    /* 
    | Minis Listing Under A tree Drive
    |
    */
    
    function get_minis(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['tree_drive_id']) || empty($postData['tree_drive_id']) || $postData['tree_drive_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'tree_drive_id' is missing.";  
        } else {
            $extra_flag = 1;
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
            $this->db->select("id,short_code,description,rank");
            $rc_tree_drives = $this->Common_m->get('rc_minis', $where = ['status' => 1,'is_draw_declared' => 0,'tree_drive_id' => $postData['tree_drive_id']  ], $offset='-1', $is_single = false, $is_total = false);
            $counter = 1;
            foreach($rc_tree_drives as $key =>  $td){
                $rc_tree_drives[$key]['short_code'] =  $rc_tree_drives[$key]['rank']."TDM".$postData['tree_drive_id'];
               // $rc_tree_drives[$key]['main_image'] =  BASE_DOMAIN."/images/treedrives/".$td['main_image'];
            }
            $data['minies'] = $rc_tree_drives;
        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {            
            $this->sendResponse($data, 200, $extra_flag);   
		}
    }

    function get_slots(){
        $postData = $this->input->post(); 
        $extra_flag = 0;

        if(!isset($postData['tree_drive_id']) || empty($postData['tree_drive_id']) || $postData['tree_drive_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'tree_drive_id' is missing.";  
        } else {
            $extra_flag = 1;
            if(!empty($postData['mini_id'])){
                 // MINI SLOTS   
                $miniRecord = $this->db->get_where("rc_minis",['id' => $postData['mini_id'] ])->row_array();
                $totalSlots = $miniRecord['slotes_available'];
                $this->db->select("u.name,rm.slot");
                $this->db->join("rc_users u","u.id=rm.user_id");
                $this->db->where('mini_id',$postData['mini_id']);
                $slots =  $this->db->get("rc_minis_user_slots rm")->result_array();
                //echo $this->db->last_query();
                $tempSlots = [];
                foreach($slots as $slot){
                    $tempSlots[$slot['slot']] = $slot;
                }
                //$slotsOnly = array_column($slots,'slot');
                
            } else {
                // TREE DRIVE SLOTS
                $miniRecord = $this->db->get_where("rc_tree_drives",['id' => $postData['tree_drive_id'] ])->row_array();
                $totalSlots = $miniRecord['slotes_available'];
                $this->db->select("u.name,rm.slot");
                $this->db->join("rc_users u","u.id=rm.user_id");
                $this->db->where('tree_drive_id',$postData['tree_drive_id']);
                $slots =  $this->db->get("rc_tree_drive_user_slots rm")->result_array();
                //echo $this->db->last_query();
                $tempSlots = [];
                foreach($slots as $slot){
                    $tempSlots[$slot['slot']] = $slot;
                }

            }
            $finalSlotsArr = [];
            for($i=1; $i <= $totalSlots; $i++){
               if(isset($tempSlots[$i])){
                   $tempSlots[$i]['is_booked'] =1; 
                   $finalSlotsArr[] = $tempSlots[$i];
               } else {
                   $tempSlots[$i] = [
                        'is_booked' => 0,
                        'name' => '',
                        'slot' =>  $i

                   ];
                   //$tempSlots[$i]['is_booked'] =0; 
                   $finalSlotsArr[] = $tempSlots[$i];
               } 
            }
            $data['slots'] = $finalSlotsArr;
            
        } 

        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {            
            $this->sendResponse($data, 200, $extra_flag);   
		}
    }

    function trees_planted(){
        $postData = $this->input->post(); 
        $extra_flag = 1;
        $sort_by_field = "";
        $sort_order ="DESC";
        if(!empty($sort_by_field)){
            if($sort_by_field == 'group'){
                $this->db->order_by("g.title","DESC");
            } else if($sort_by_field == 'month'){
                $this->db->order_by("MONTH(tp.created_at)","DESC");
            } else if($sort_by_field == 'year'){
                $this->db->order_by("YEAR(tp.created_at)","DESC");
            }
        }
        // SORT BY GROUPS
        // MONTHS
        // YEAR 
        // ALL
        
        $this->db->select("g.id,g.title,g.description,SUM(trees_planted) as trees_planted,tp.group_id");
        $this->db->join("rc_groups g","g.id=tp.group_id");
        $this->db->group_by("tp.group_id");
        //$this->db->where("user_id",$this->userData['user_id']);
        $groups = $this->db->get("rc_tree_planted tp")->result_array();
        foreach($groups as $key =>  $group){
                $groups[$key]['image'] = BASE_DOMAIN."/images/user.png";
                
            }
            $data['groups'] = $groups;
            if(isset($data['errors'])){
                $this->sendResponse($data, 204, $extra_flag);
            } else {
                $this->sendResponse($data, 200, $extra_flag);
            }


    }

    function award_detail(){
        $postData = $this->input->post(); 
        $extra_flag = 0;
        //pr($postData);
        if(!isset($postData['tree_drive_id']) || empty($postData['tree_drive_id']) || $postData['tree_drive_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'tree_drive_id' is missing.";  
        } else {
            $this->db->select("rc_tree_drives.id,title,short_code,price,description,slotes_available,main_image,info_image,info_title,info_description");
            // $this->db->join("rc_tree_drive_draws tdd","tdd.tree_drive_id=rc_tree_drives.id","LEFT");
            $group = $this->Common_m->get('rc_tree_drives', $where = ['rc_tree_drives.id' => $postData['tree_drive_id'] ], 0, $is_single = true, $is_total = false);
            if(!empty($group)){
                // GETTING LIST OF GROUP TREE DRIVES
                $extra_flag = 1;
                $data['tree_drive'] =  $group;                
                $data['tree_drive']['main_image'] =  BASE_DOMAIN."/images/treedrives/".$group['main_image'];;                
                //$data['tree_drive']['prizes'] =  [];
                $this->db->select("prize_text"); 
                //$data['tree_drive']['prizes'] = $this->Common_m->get('rc_tree_drive_prizes', $where = ['tree_drive_id' => $postData['id'] ], -1, $is_single = false, $is_total = false,'ASC','prize_position'); 
                $this->db->select("id,rule_text,is_question,question,answer");
                
            } else {
                $data['error'] ="No Record Found.";
            }

        }
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
        
    }
    

    /* 
    * Function for creating / Updating Tree Drive
    *     
    */

    function manage(){
        $postData = $this->input->post();
        $this->load->library('form_validation');
        $name = date('Ymdhis').md5(rand(999,9999));
        $config['upload_path']          = BASE_UPLOAD_PATH.'images/treedrives';
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
                ,[
                    'field' => 'group_id',
                    'label' => 'Group   ',
                    'rules' => 'trim|required|numeric'
                ],
                [
                    'field' => 'slotes_available',
                    'label' => 'Slots',
                    'rules' => 'trim|required|numeric'
                ]
            ];
            
            //$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
    
            $this->form_validation->set_rules($config);

            if($this->form_validation->run() == TRUE){
            // GETTING USER INFO
            $groupInfo =[];
            $where = [];
            if(isset($postData['id']) && !empty($postData['id'])){
                $groupInfo =   $this->db->get_where("rc_tree_drives",['id' => $postData['id']])->row_array();
                $where = ['id' =>  $groupInfo['id'] ];
            }
            $extra_flag = 1;
            //$customerInfo = $this->getUserById($this->userData['user_id']);
           // if(!empty($customerInfo)){
                // 
                $saveArr = [
                    'title' => $postData['title'],
                    'description' => $postData['description'],
                    'user_id' => $this->userData['user_id'],
                    'group_id' => $postData['group_id'],
                    'slotes_available' => $postData['slotes_available'],
                    'tree_type' => isset($postData['tree_type']) ? $postData['tree_type'] :'timber',
                    'info_title' => isset($postData['info_title']) ? $postData['info_title'] :'',
                    'info_description' => isset($postData['info_description']) ? $postData['info_description'] :'',
                    'draw_date' => isset($postData['draw_date']) ? $postData['draw_date'] : date('Y-m-d', strtotime('+1 month')),
                ];                
                //$this->db->update("rc_users",$saveArr,$where);
                // CHECKING IF IMAGE UPLOADED
                if(isset($_FILES['main_image'])  && isset($_FILES['main_image']['name']) && !empty($_FILES['main_image']['name']) && empty($data['error']) ){
                    if ( ! $this->upload->do_upload('main_image')) { 
                       // $extra_flag = 0;
                        $inputFileName = isset($groupInfo['main_image']) ? $groupInfo['main_image'] : '';
                        //$data['error'] = $this->upload->display_errors();
                        //pr($this->upload->display_errors());
                    } else {
                       $img = $this->upload->data(); 
                       $inputFileName = $img['file_name'];
                       if(isset($groupInfo['main_image']) &&  !empty($groupInfo['main_image'])){
                        @unlink(BASE_UPLOAD_PATH.'images/treedrives/'.$groupInfo['main_image']);
                       }                       
                    }
                    $saveArr['main_image'] = $inputFileName;   
                }

                if(isset($_FILES['info_image'])  && isset($_FILES['info_image']['name']) && !empty($_FILES['info_image']['name']) && empty($data['error']) ){
                    if ( ! $this->upload->do_upload('info_image')) { 
                       // $extra_flag = 0;
                        $inputFileName = isset($groupInfo['info_image']) ? $groupInfo['info_image'] : '';
                        //$data['error'] = $this->upload->display_errors();
                        //pr($this->upload->display_errors());
                    } else {
                       $img = $this->upload->data(); 
                       $inputFileName = $img['file_name'];
                       if(isset($groupInfo['info_image']) &&  !empty($groupInfo['info_image'])){
                        @unlink(BASE_UPLOAD_PATH.'images/treedrives/'.$groupInfo['info_image']);
                       }                       
                    }
                    $saveArr['info_image'] = $inputFileName;                    
                                     
                }


                if(!empty($where)){
                        $this->db->update("rc_tree_drives",$saveArr,$where); 
                        $data['success'] ="Tree Drive Information Updated."; 
                    } else {
                        $this->db->insert("rc_tree_drives",$saveArr); 
                        $data['success'] ="Tree Driveroup Created."; 
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

     /* 
    * Getting List of ALL TREE DRIVES
    * 
    */
    function user_created_tree_drives(){
        $extra_flag = 1;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $offset = ($page * TOTAL_RECORD_PER_PAGE) - TOTAL_RECORD_PER_PAGE;
        $sort_column = isset($_POST['sort_column']) ? $_POST['sort_column'] : 'created_at';
        $sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'ASC';
        // PRICE , created_at , group
        $this->db->join("rc_groups g","g.id=td.group_id");
        $this->db->select("td.id,td.short_code,td.title,td.main_image,td.created_at,g.title as group,td.price");
        $this->db->order_by($sort_column,$sort_order);
        $rc_tree_drives = $this->Common_m->get('rc_tree_drives td', $where = ['td.status' => 1,'is_draw_declared' => 0,'td.user_id' =>$this->userData['user_id'] ], -1, $is_single = false, $is_total = false);                   
        foreach($rc_tree_drives as $key =>  $td){
            $rc_tree_drives[$key]['short_code'] =  "TDE".$rc_tree_drives[$key]['id'];
            $rc_tree_drives[$key]['main_image'] =  BASE_DOMAIN."/images/treedrives/".$td['main_image'];
        }
        $data['tree_drives'] = $rc_tree_drives;
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {            
            $this->sendResponse($data, 200, $extra_flag);   
		}
    }

    function add_review(){
        $postData = $this->input->post();
        if(!isset($postData['rating']) || empty($postData['rating']) || $postData['rating'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'rating' is missing.";  
        
        } if(!isset($postData['tree_drive_id']) || empty($postData['tree_drive_id']) || $postData['tree_drive_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please select Tree Drive.";  
        
        } else {
            // EVERYTHING IS OK PROCEED TO SAVE RATING
            $extra_flag =1;
            $prepareArr =[
                'user_id' => $this->userData['user_id'],
                'rating' => $postData['rating'],
                'tree_drive_id' => $postData['tree_drive_id'],
            ];
            $where = ['user_id' => $this->userData['user_id'], 'tree_drive_id' =>  $postData['tree_drive_id'] ];
            $review = $this->Common_m->get('rc_tree_drive_reviews', $where, 0, $is_single = true, $is_total = false);
            if(!empty($review)){
                $this->db->update("rc_tree_drive_reviews",$prepareArr,$where);
            } else {
                $this->db->insert("rc_tree_drive_reviews",$prepareArr);             
            }
            $data['success'] ='feedback Added!';
            
        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
    
    }




}



