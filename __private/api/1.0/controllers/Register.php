<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends My_Controller {

        //$this->load->model(array('data'));
        
        function __construct() {
            $this->log_flag = 1;  
            parent::__construct();                                              
            $this->load->model("Common_m");
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
		//$this->load->model('Common_m');
		$this->load->library('form_validation');
		//$_POST = json_decode(file_get_contents("php://input"), true);
		//  $postJson = file_get_contents("php://input");
        // if(!empty($postJson)){
        //     $_POST = (array) json_decode($postJson,true);
        // }

		$config  = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required'
			],[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email'
			],[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
            ],[
				'field' => 'confirm_password',
				'label' => 'Confirm Password',
				'rules' => 'trim|required|matches[password]'
			],[
				'field' => 'is_host',
				'label' => 'Is Host',
				'rules' => 'trim|required'
			]
        ];
        
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

		$this->form_validation->set_rules($config);
		//pr($_POST);
        $extra_flag = 0;
        
		if($this->form_validation->run() == TRUE){
            $postData = $this->input->post();            
            $chkEmail = $this->Common_m->get('rc_users', ['email' => $postData['email'],'is_host' => isset($postData['is_host']) ? $postData['is_host'] : 0], -1, true);
            //print_r($chkEmail);
            $addCustomer = [
                'name' => $postData['name'],
                'email' => $postData['email'],
                'password' => md5($postData['password']),
                'is_host' => isset($postData['is_host']) ? $postData['is_host'] : 0,
                'status' => 1
            ];
			if(!empty($chkEmail)){
                if($chkEmail['status'] != 2){
                    $data['error'] = 'Email already registered with us. Please try another email.';
                } else if($chkEmail['status'] == 2){
                    // IF ACCOUNT IS ALREADY DEELETED
                    $this->db->update("rc_users",$addCustomer,['id' => $chkEmail['id'] ]);
                    $customer_id = $chkEmail['id'];
                    if($customer_id){
                        $extra_flag = 1;					
                        /* generate email verification for customer */
                        $verification_code = rand(99999,99999999);
                        $email_token = md5(time());
                        $this->Common_m->save('rc_users_tokens', [
                            'customer_id' => $customer_id,
                            'token' => $verification_code,
                            'token_type' => 'email_verify',
                            'ip_address' => $this->input->ip_address()
                        ]);
    
                        /* Send email to customer */
                        $addCustomer['otp'] = $verification_code;
                        //$addCustomer['new_customer_email_verify_link'] = WEBSITE_DOMAIN.'/emailverify.php?t='.$email_token;
    
                        //$html_mail_data = $this->load->view('email/register', $addCustomer, true);
                        //$this->sendmail($html_mail_data, $postData['email'], 'Welcome to ');
                        $data['success'] = "Thanks for registering with us.";
                        $data['user_id'] = $customer_id;
                        //$data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));    
                }
                    //$customer_id = $this->Common_m->save('rc_users', $addCustomer);
                   
                } else {                    
                    $customer_id = $this->Common_m->save('rc_users', $addCustomer);
                    if($customer_id){
                        $extra_flag = 1;					
                        /* generate email verification for customer */
                        $verification_code = rand(99999,99999999);
                        $email_token = md5(time());
                        $this->Common_m->save('rc_users_tokens', [
                            'customer_id' => $customer_id,
                            'token' => $verification_code,
                            'token_type' => 'email_verify',
                            'ip_address' => $this->input->ip_address()
                        ]);
    
                        /* Send email to customer */
                        $addCustomer['otp'] = $verification_code;
                        //$addCustomer['new_customer_email_verify_link'] = WEBSITE_DOMAIN.'/emailverify.php?t='.$email_token;
    
                        //$html_mail_data = $this->load->view('email/register', $addCustomer, true);
                        //$this->sendmail($html_mail_data, $postData['email'], 'Welcome to ');
                        $data['success'] = "Thanks for registering with us.";
                        $data['user_id'] = $customer_id;
                        //$data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));    
                }
				
				// } else {
				// 	$data['error'] = 'Unable to complete registration. Please try again.';
				// }
            }
         } else {
            // IF NO RECORD FOUND FOR EMAIL CREATINGNEW ACCOUNT
            $customer_id = $this->Common_m->save('rc_users', $addCustomer);
                    if($customer_id){
                        $extra_flag = 1;					
                        /* generate email verification for customer */
                        $verification_code = rand(99999,99999999);
                        $email_token = md5(time());
                        $this->Common_m->save('rc_users_tokens', [
                            'customer_id' => $customer_id,
                            'token' => $verification_code,
                            'token_type' => 'email_verify',
                            'ip_address' => $this->input->ip_address()
                        ]);
    
                        /* Send email to customer */
                        $addCustomer['otp'] = $verification_code;
                        //$addCustomer['new_customer_email_verify_link'] = WEBSITE_DOMAIN.'/emailverify.php?t='.$email_token;
    
                        //$html_mail_data = $this->load->view('email/register', $addCustomer, true);
                        //$this->sendmail($html_mail_data, $postData['email'], 'Welcome to ');
                        $data['success'] = "Thanks for registering with us.";
                        $data['user_id'] = $customer_id;
                        //$data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));    
                }
         }
		} else if($this->form_validation->run() == FALSE){
			$data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));
			//$data['errors'] = $this->form_validation->error_array();
		}


		if(isset($data['errors'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
	}

}



