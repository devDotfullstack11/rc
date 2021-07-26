<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends My_Controller {
        public $rowCount = 1;
        public $userData =array();
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

        }

    /* 
    * Adding Review from the APP.
    */

    function add_review(){
        $postData = $this->input->post();
        if(!isset($postData['rating']) || empty($postData['rating']) || $postData['rating'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Parameter 'rating' is missing.";  
        
        } if(!isset($postData['group_id']) || empty($postData['group_id']) || $postData['group_id'] == 0 ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please select group.";  
        
        } else {
            // EVERYTHING IS OK PROCEED TO SAVE RATING
            $extra_flag =1;
            $prepareArr =[
                'user_id' => $this->userData['user_id'],
                'review_1' => (isset($postData['review_1']) && !empty($postData['review_1'])) ? $postData['review_1'] : '',
                'review_2' => (isset($postData['review_2']) && !empty($postData['review_2'])) ? $postData['review_2'] : '',
                'review_3' => (isset($postData['review_3']) && !empty($postData['review_3'])) ? $postData['review_3'] : '',
                'rating' => $postData['rating'],
                'group_id' => $postData['group_id'],
            ];
            $where = ['user_id' => $this->userData['user_id'], 'group_id' =>  $postData['group_id'] ];
            $review = $this->Common_m->get('rc_reviews', $where, 0, $is_single = true, $is_total = false);
            if(!empty($review)){
                $this->db->update("rc_reviews",$prepareArr,$where);
            } else {
                $this->db->insert("rc_reviews",$prepareArr);
                
            }
            $data['success'] ='feedback Added!';
            
        }
        if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
    
    }

    function get_review(){
        $extra_flag = 1;
            $where = ['user_id' => $this->userData['user_id'] ];
            $review = $this->Common_m->get('rc_reviews', $where, 0, $is_single = true, $is_total = false);
            $data['review'] = $review;
            if(isset($data['errors'])){
                $this->sendResponse($data, 204, $extra_flag);
            } else {
                $this->sendResponse($data, 200, $extra_flag);
            }
    
    }

    function update_profile(){
        $postData = $this->input->post();
        $name = date('Ymdhis').md5(rand(999,9999));
        $config['upload_path']          = BASE_UPLOAD_PATH.'images/users';
        $config['file_name']          = $name;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'],777);
        }
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!isset($postData['name']) || empty($postData['name'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please enter your name.";  
        
        } else  if(!isset($postData['email']) || empty($postData['email'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please enter your email.";  
        
        } else {
            // GETTING USER INFO
            $extra_flag = 1;
            $customerInfo = $this->getUserById($this->userData['user_id']);
            if(!empty($customerInfo)){
                // 
                $saveArr = [
                    'name' => $postData['name'],
                    'email' => $postData['email'],
                    'age'   => isset($postData['age']) ? $postData['age'] : 0,
                    'billing_address'   => isset($postData['billing_address']) ? $postData['billing_address'] : '',
                    'account_number'   => isset($postData['account_number']) ? $postData['account_number'] : '',
                    'bank_name'   => isset($postData['bank_name']) ? $postData['bank_name'] : '',
                    'transit_number'   => isset($postData['transit_number']) ? $postData['transit_number'] : '',
                    'branch_address'   => isset($postData['branch_address']) ? $postData['branch_address'] : ''
                ];
                $saveArr['is_profile_updated'] = 0;
                if(!empty($saveArr['age']) && $saveArr['age'] > 0 &&
                    !empty($saveArr['billing_address']) &&   
                    !empty($saveArr['account_number']) &&   
                    !empty($saveArr['bank_name']) &&   
                    !empty($saveArr['transit_number']) &&   
                    !empty($saveArr['branch_address'])
                ){
                    $saveArr['is_profile_updated'] = 1;
                }
                
                $where = ['id' =>  $this->userData['user_id'] ];
                if($postData['email'] !== $customerInfo['email']){
                    // IF EMAIL DOES NOT MATCH WITH POSTED EMAIL
                    // GOING TO CHECK IF IT IS UNIQUE EMAIL
                    $emailRecord = $this->db->get_where("rc_users",['email' => $postData['email']])->result_array();
                    if(empty($emailRecord)){
                        $this->db->update("rc_users",$saveArr,$where);
                        $data['success'] ="Profile Updated.";
                    } else {
                        $data['error'] ="Email already taken.";  
                    }
                } else {
                    // Email not changed
                    $this->db->update("rc_users",$saveArr,$where);
                    $data['success'] ="Profile Updated.";
                }
                // CHECKING IF IMAGE UPLOADED
                if(isset($_FILES['profile_pic'])  && isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']) && empty($data['error']) ){
                    if ( ! $this->upload->do_upload('profile_pic')) { 
                        $extra_flag = 0;
                        $inputFileName = $customerInfo['profile_pic'];
                        $data['error'] = $this->upload->display_errors();
                        //pr($this->upload->display_errors());
                    } else {
                       $img = $this->upload->data(); 
                       $inputFileName = $img['file_name'];
                       if(!empty($customerInfo['profile_pic'])){
                        @unlink(BASE_UPLOAD_PATH.'images/users/'.$customerInfo['profile_pic']);
                       }                       
                    }
                    $saveArr['profile_pic'] = $inputFileName; 
                    $this->db->update("rc_users",$saveArr,$where); 
                    $data['success'] ="Profile Updated.";                   
                }


            } else {
                $extra_flag = 2;
                $data['error'] ="Invalid Auth Token.";  
            }
            // Checking if Email is unique
            

        }

        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }

    }

    function logout(){
        $extra_flag = 1; 
        //pr($this->userData); 
        $this->db->where("app_auth_token",$this->userData['app_auth_token']); 
        $this->db->delete("rc_users_devices");
        $data['success'] = "Logged Out!";
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    
    }

    /* 
        notification_status
    */
    function notification_settings(){
        $postData = $this->input->post();
        //pr($postData);
        if(!isset($postData['notification_enable'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="'notification_enable' Parameter is missing.";  
        
        } else if(!isset($postData['email_enable'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="'email_enable' Parameter is missing.";  
        
        } else {
            $extra_flag = 1;
            $saveArr = [
                'notification_enable' => ($postData['notification_enable'] == 1) ? 0 :1,
                'email_enable' => ($postData['email_enable'] == 1) ? 0 :1,
            ];
            $where = ['id' =>  $this->userData['user_id'] ];
            $this->db->update("rc_users",$saveArr,$where); 
            $data['success'] ="Profile Updated."; 

        }
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    }

    function changepassword(){
        $postData = $this->input->post();
         $customerInfo = $this->getUserById($this->userData['user_id']);
        //pr($postData);
        if(!isset($postData['old_password']) || empty($postData['old_password'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please enter old password.";  
        
        } else if(!isset($postData['new_password']) || empty($postData['new_password'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please enter new password.";  
        
        }  else if(!isset($postData['confirm_password']) || empty($postData['confirm_password']) ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Please enter Confirm password.";  
        
        } else if($postData['new_password'] !== $postData['confirm_password'] ){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="new password & confirm password should be same.";  
        
        } else if($customerInfo['password'] !== md5($postData['old_password'])){
            $extra_flag = 0;
            // REQUIRED PARAMETERS NOT PASSED
            $data['error'] ="Incorrect Old password.";  
        
        } else {
            $extra_flag = 1;
            $saveArr = [
                'password' => md5($postData['new_password'])
            ];
            $where = ['id' =>  $this->userData['user_id'] ];
            $this->db->update("rc_users",$saveArr,$where); 
            $data['success'] ="Password Updated."; 

        }
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }   
    
    }

    /* 
    | SAVE REFERENCE
    |
    */

    function save_reference(){
        $postData = $this->input->post();
        $userSaveArr = [
            'add_reference_flag' => 1
        ];
        //$extra_flag =1;
        $extra_flag =1;
        $data['success'] ="Reference Code Added!";
        if(!empty($postData['reference_code'])){
            // FINDING USER BY REFERENCE CODE
            $userByReference = $this->db->get_where("rc_users",['reference_code' => $postData['reference_code']])->row_array();
            if(empty($userByReference)){
            	//echo "HJKSDF"; exit;
                $extra_flag =0;
                $data['error'] = "Invalid Reference Code!";
                //$data['sfsdf'] = "Invalid Reference Code!";
            } else if($userByReference['id'] == $this->userData['user_id']){
                $extra_flag =0;
                $data['error'] = "You can not add your own referal code";
            }  else {
                $extra_flag =1;
                $loggedinData = $this->getUserById($this->userData['user_id']);
                $userSaveArr['reffered_by'] = $userByReference['id'];
                $this->db->update("rc_users",$userSaveArr,['id' => $this->userData['user_id']]);
                
                // Reference Code is valid
                // UPDATE IN USERS TABLE REFFERED BY
                // Check if user has refered 2 Users and credit 5 dollors in his account.
                $allReferences = $this->db->get_where("rc_users",['reffered_by' => $userByReference['id'] ])->result_array();
                //pr($allReferences);exit;
                if(count($allReferences) == 2 && $loggedinData['add_reference_flag'] == 0){
                    $user_wallet = $this->db->get_where('rc_user_wallet', array('user_id' => $this->userData['user_id']));
                    $user_walletRecord = $user_wallet->first_row('array');
                    if(empty($user_walletRecord)){
                        // IF WALLET RECORD IS EMPTY
                        // CREATING NEW RECORD FOR USER
                        $walletArr = [
                            'user_id' => $this->userData['user_id'],
                            'amount' => 0
                        ];
                        $this->db->insert('rc_user_wallet',$walletArr);
                        $user_wallet = $this->db->get_where('rc_user_wallet', array('user_id' => $this->userData['user_id']));
                        $user_walletRecord = $user_wallet->first_row('array');
                    }

                    $postData =$this->input->post();
                    
                    $transaction_amount = 5;
                    $transactionArr = [
                        'user_id' => $this->userData['user_id'],
                        'transaction_type' => 'credit',
                        'credit_debit_type' => "Reference Bonus",
                        'remarks' => "",
                        'opening_balance' => $user_walletRecord['amount'],
                        'transaction_amount' => $transaction_amount,
                        'closing_amount' => ($user_walletRecord['amount'] + $transaction_amount),
                    ];

                     $this->db->insert('rc_user_transactions',$transactionArr);
                     $where = ['user_id' =>  $this->userData['user_id']];
                     $data =  ['amount' =>  $transactionArr['closing_amount'] ];
                     $this->db->update('rc_user_wallet',$data,$where);

                }
                // MAKE ENTRY INTO TRANSACTION TABLE.                
                $data['success'] ="Reference Code Added!";
            }
            //$data['success'] ="Reference Code Added!";
        } 
        // UPDATING CURRENT LOGGED IN USER INFORMATION
        $this->db->update("rc_users",$userSaveArr,['id' => $this->userData['user_id']]);
        //echo $extra_flag; exit;
        if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }

    }

    function get_userprofile(){
            $extra_flag =1;
            $userData = $this->getUserLoginObj($this->userData['user_id']);
            $data['customer'] = $userData;
            $data['success'] ="OK";
            
            if(isset($data['errors'])){
                $this->sendResponse($data, 204, $extra_flag);
            } else {
                $this->sendResponse($data, 200, $extra_flag);
            }   
    
    
    }

    function reward_history(){
    	$postData = $this->input->post();
    	$duration ='all';
    	if(isset($postData['duration'])){
    		$duration =$postData['duration'];    		
    	}
    	$condition=[];
    	if($duration == 'week'){
    		$monday = strtotime("last monday");
			$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
			$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
			//$start_date = date("Y-m-d 00:00:00",$monday); //  WORKING =====
			$start_date = date("Y-01-d 00:00:00",$monday);
			$end_date = date("Y-m-d 23:59:59",$sunday);
			$condition=['r.created_at >=' => $start_date,'r.created_at <=' => $end_date];
			//echo "Current week range from $this_week_sd to $this_week_ed ";
    	} else if($duration == 'month'){
   //  		$monday = strtotime("last monday");
			// $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
			// $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
			//$start_date = date("Y-m-01 00:00:00"); // WORKING +++++++
			$start_date = date("Y-01-01 00:00:00");
			$end_date = date("Y-m-t 23:59:59");
			$condition=['r.created_at >=' => $start_date,'r.created_at <=' => $end_date];
			//echo "Current week range from $start_date to $end_date ";
    	}
    	//pr($condition);
    	if(!empty($condition)) {
    		$this->db->where($condition);
    	}
    	$extra_flag =1;
    	$this->db->select("r.*,td.short_code as tree_short_code,td.main_image as tree_drive_image");
    	$this->db->join("rc_tree_drives td","td.id=r.tree_drive_id");
    	$this->db->order_by("r.id","DESC");
    	$rewards = $this->db->get("rc_rewards r")->result_array();
    	
    	$finalArr =[];
    	foreach($rewards as $key =>  $group){
        	$finalArr[$key] =[];
        	$finalArr[$key]['image'] = BASE_DOMAIN."/images/treedrives/".$rewards[$key]['tree_drive_image'];
        		$finalArr[$key]['title'] = $rewards[$key]['tree_short_code'];
        		$finalArr[$key]['description'] = $rewards[$key]['message'];
        		$finalArr[$key]['created_at'] = date_create($rewards[$key]['created_at'])->format("h:m A, d M Y");
        		$finalArr[$key]['id'] = $rewards[$key]['id'];
        }
        $data['rewards'] =$finalArr;
    	if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    }

    function rewards(){
    	$postData = $this->input->post();
    	$duration ='all';
    	if(isset($postData['duration'])){
    		$duration =$postData['duration'];    		
    	}
    	$condition=[];
    	if($duration == 'week'){
    		$monday = strtotime("last monday");
			$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
			$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
			//$start_date = date("Y-m-d 00:00:00",$monday); //  WORKING =====
			$start_date = date("Y-01-d 00:00:00",$monday);
			$end_date = date("Y-m-d 23:59:59",$sunday);
			$condition=['r.created_at >=' => $start_date,'r.created_at <=' => $end_date];
			//echo "Current week range from $this_week_sd to $this_week_ed ";
    	} else if($duration == 'month'){
   //  		$monday = strtotime("last monday");
			// $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
			// $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
			//$start_date = date("Y-m-01 00:00:00"); // WORKING +++++++
			$start_date = date("Y-01-01 00:00:00");
			$end_date = date("Y-m-t 23:59:59");
			$condition=['r.created_at >=' => $start_date,'r.created_at <=' => $end_date];
			//echo "Current week range from $start_date to $end_date ";
    	}
    	//pr($condition);
    	if(!empty($condition)) {
    		$this->db->where($condition);
    	}
    	$extra_flag =1;
    	$this->db->select("r.*,td.short_code as tree_short_code,td.main_image as tree_drive_image");
    	$this->db->join("rc_tree_drives td","td.id=r.tree_drive_id");
    	$this->db->order_by("r.id","DESC");
    	$rewards = $this->db->get("rc_rewards r")->result_array();
    	
    	$finalArr =[];
    	foreach($rewards as $key =>  $group){
        	$finalArr[$key] =[];
        	$finalArr[$key]['image'] = BASE_DOMAIN."/images/treedrives/".$rewards[$key]['tree_drive_image'];
        		$finalArr[$key]['title'] = $rewards[$key]['tree_short_code'];
        		$finalArr[$key]['description'] = $rewards[$key]['message'];
        		$finalArr[$key]['created_at'] = date_create($rewards[$key]['created_at'])->format("h:m A, d M Y");
        		$finalArr[$key]['id'] = $rewards[$key]['id'];
        }
        $data['rewards'] =$finalArr;
    	if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
    }
 

 function host_request(){
 	$extra_flag =1;
 	$prepareArr=['is_hosting_requested' => 1];
 	$where =['id' => $this->userData['user_id']];
 	$this->db->update("rc_users",$prepareArr,$where);
 	//echo $this->db->last_query();
 	$data['message'] ="Request Sent to Admin";
 	if(isset($data['errors'])){
            $this->sendResponse($data, 204, $extra_flag);
        } else {
            $this->sendResponse($data, 200, $extra_flag);
        }
 }

}



