<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgotpassword extends My_Controller {
        
        function __construct() {
            $this->log_flag = 1;  
            parent::__construct();                                              
            $this->load->model(["Common_m","User_model"]);
            $this->load->helper('url');
            $this->load->library('common');
            $this->load->database();
            $extra_flag = 0;
		$postData = $this->input->post();
            if(empty($postData['version'])){
                $data['error'] ="Please define api version in parameters i.e version : 1.0";
                $this->sendResponse($data, 204, $extra_flag); exit;
            }
        }

       public function index(){
        // Setting UP validations
		$this->load->library('form_validation');
		$config  = [
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email'
			]
        ];
		$this->form_validation->set_rules($config);
        $extra_flag = 0;
        $data = array();
		if($this->form_validation->run() == TRUE){
            // IF DATA IS VALID
            $postData = $this->input->post();            
            $chkEmail = $this->Common_m->get('rc_users', ['email' => $postData['email']], -1, true);
            
            if(!empty($chkEmail)){
                // IF EMAIL RECORD IS FOUND
                // RESETTING PASSWORD AND SENDING MAIL TO USER 
                //$response = $this->User_model->reset_password($chkEmail);
                $r = $this->generateRandomString(8);
                //$data['password'] = password_hash($r, PASSWORD_DEFAULT);
                $Savedata['password'] = md5($r);
                $this->db->update('rc_users', $Savedata , array('id' => $chkEmail['id']));
                $response =  ['password' =>  $r];
                $response['name'] = $chkEmail['name'];
               $is_sent =  $this->sendmail('emails/forgot_password',$response,$chkEmail,'Change Password',[]);
               if($is_sent){
                    $extra_flag = 1;
                    $data['success'] = "Password Sent to registerd Email Address!.";
               }else {
                    $extra_flag = 0;
                    $data['error'] = "Mail not sent,Please contact admin!.";
               }
            } else {
                // IF NO RECORD FOUND FOR EMAIL CREATINGNEW ACCOUNT
                $data['error'] = 'No record found for this email.';
            }
		} else if($this->form_validation->run() == FALSE){
            // IF DATA IS NOT VALID
			$data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));
		}


		if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
	}

}



