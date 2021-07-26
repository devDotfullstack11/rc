<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {


    public function __construct() {			
		parent::__construct(); 
		$extra_flag = 0;
		$this->log_flag = 1;
		$postData = $this->input->post();
		if(empty($postData['version'])){
			$data['error'] ="Please define api version in parameters i.e version : 1.0";
			$this->sendResponse($data, 204, $extra_flag); exit;
		}       
    }

	public function index(){

		$this->load->model('Common_m');
		//$this->load->model('Wallet_m');
		$this->load->library('form_validation');
		$postData = $this->input->post();
		//log_message('error', 'login post - '.json_encode($postData));
		$extra_flag = 0;
		

		
        		

       //pr($postData); exit;
		if(!empty($postData['login_type'])){
			if($postData['login_type'] == 'app'){
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		        $this->form_validation->set_rules('password', 'Password', 'trim|required');
		        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
		        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required');
				if($this->form_validation->run() == FALSE) {
                        $data['error'] = str_replace("\n", ' ', strip_tags(validation_errors()));
			        	$this->sendResponse($data, 204, $extra_flag);
			        }
				//if(!empty($postData['country_code']) && !empty($postData['phone']) && !empty($postData['password'])){
					
					$cond = [
						'email' => $postData['email'],
						'status != ' => 2,
						'is_host' => 0
					];
					$customerInfo = $this->Common_m->get("rc_users",$cond, -1, true);
					if(!empty($customerInfo)){
						if($customerInfo['status'] == 1){
							/* Check password */
							if(md5($postData['password']) == $customerInfo['password'] ){
								//$app_auth_token = $this->_generateAuthToken($customerInfo, $postData);
								$extra_flag = 1;
								$my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $customerInfo['id'],'is_favourite' => 1])->result_array();
						
								$profile_pic = BASE_DOMAIN."/images/user.png";
								if(!empty($customerInfo['profile_pic'])){
									$profile_pic = BASE_DOMAIN.'/images/users/'.$customerInfo['profile_pic'];
								} 
								$data['customer'] = $this->getUserLoginObj($customerInfo['id']);

								
							} else {
								$data['error'] = 'Incorrect password. Please check and try again.';
							}
						} else if($customerInfo['status'] == 3){
							$extra_flag = 0;
							$data['error'] = 'Please use sign up to become active customer';
						} else {
							$data['error'] = 'Inactive account. Please contact administrator.';
						}
					} else {
						$data['error'] = 'Invalid Email or password.';
					}
				// } else {
				// 	$data['error'] = 'Please provide phone and password.';
				// }
			} else if($postData['login_type'] == 'facebook' || $postData['login_type'] == 'facebookmobile'){
				if(!empty($postData['facebook_id'])){
					$chkFBCustomer = $this->Common_m->get("rc_users",['facebook_id' => $postData['facebook_id'], 'status != ' => 2], -1, true);
					if(!empty($chkFBCustomer)){

						if($chkFBCustomer['status'] == 0){
							$data['error'] = 'Inactive account. Please contact administrator.';
						} else {
							
							/* Save email if not saved yet */
							if(empty($chkFBCustomer['email']) && !empty($postData['email'])){
								$saveCustomerArr['email'] = $chkFBCustomer['email'] = $postData['email'];
							}
							
							//$app_auth_token = $this->_generateAuthToken($chkFBCustomer, $postData);
							//$saveCustomerArr['auth_token'] = $app_auth_token;
							if(isset($saveCustomerArr)){
								
								//pr($saveCustomerArr); exit;
								$this->Common_m->save('rc_users', $saveCustomerArr, ['id' => $chkFBCustomer['id']]);
							}

							
							$extra_flag = 1;

							$profile_pic = BASE_DOMAIN."/images/user.png";
							if(!empty($chkFBCustomer['profile_pic'])){
								$profile_pic = BASE_DOMAIN.'/images/users/'.$chkFBCustomer['profile_pic'];
							} 
							
							$my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $chkFBCustomer['id'],'is_favourite' => 1])->result_array();
                            $data['customer'] = $this->getUserLoginObj($chkFBCustomer['id']);

						
						}
					}  else {
						//echo "55"; exit;
						$_isCustomerFound = false;
						$data['errors'] = [];
						/* check if customer's email registed with us. */
						if(!empty($postData['email'])){
							$emailCond = [
								'email' => $postData['email'],
								'status' => 1
							];
							$emailCustInfo = $this->Common_m->get("rc_users",$emailCond, -1, true);
							if(!empty($emailCustInfo)){
								//$app_auth_token = $this->_generateAuthToken($emailCustInfo, $postData);
								$emailSaveArr = ['facebook_id' => $postData['facebook_id'], 'email_verify' => 1 ];
								/* get facebook image if customer has not set yet. */
								if(isset($emailCustInfo['profile_pic'])  &&$emailCustInfo['profile_pic']==0){
									$fbImgUrl = file_get_contents("https://graph.facebook.com/".$postData['facebook_id']."/picture?width=600&height=600&redirect=false");
			                        $fbImg = json_decode($fbImgUrl, true);
			                        if(isset($fbImg['data']['url'])){
			                            $emailSaveArr['profile_pic'] = $this->getSocialProfileImageId($postData['facebook_id'], $fbImg['data']['url']);
			                        }
								}

								/* save facebook id customer_contacts  */
								$this->Common_m->save('rc_users', $emailSaveArr, ['id' => $emailCustInfo['id']]);

								//$app_auth_token = $this->_generateAuthToken($emailCustInfo, $postData);
								$extra_flag = 1;

								$profile_pic = BASE_DOMAIN."/images/user.png";
								if(!empty($emailCustInfo['profile_pic'])){
									$profile_pic = BASE_DOMAIN.'/images/users/'.$emailCustInfo['profile_pic'];
								}
								$my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $emailCustInfo['id'],'is_favourite' => 1])->result_array();
								$data['customer']  = $this->getUserLoginObj($emailCustInfo['id']);;

								
								$_isCustomerFound =  true;
							}
						} else {
							$data['error'] = 'Please provide email detail with facebook id.';
						}

						if(!$_isCustomerFound){
							$extra_flag = 3;
							$data['error'] = 'Please provide phone detail with facebook id.';							
							$data['message'] = 'Please provide phone detail with facebook id.';
						}
					}
				} else {
					$data['error'] = 'Facebook id is missing.';
				}
			} else if($postData['login_type'] == 'google'){

				if(!empty($postData['google_id'])){
					$chkFBCustomer = $this->Common_m->get("rc_users",['google_id' => $postData['google_id'], 'status != ' => 2], -1, true);
					if(!empty($chkFBCustomer)){

						if($chkFBCustomer['status'] == 0){
							$data['error'] = 'Inactive account. Please contact administrator.';
						} else {
							
							/* Save email if not saved yet */
							if(empty($chkFBCustomer['email']) && !empty($postData['email'])){
								$saveCustomerArr['email'] = $chkFBCustomer['email'] = $postData['email'];
							}
							
							//$app_auth_token = $this->_generateAuthToken($chkFBCustomer, $postData);
							//$saveCustomerArr['auth_token'] = $app_auth_token;
							if(isset($saveCustomerArr)){
								
								//pr($saveCustomerArr); exit;
								$this->Common_m->save('rc_users', $saveCustomerArr, ['id' => $chkFBCustomer['id']]);
							}

							
							$extra_flag = 1;
							$profile_pic = BASE_DOMAIN."/images/user.png";
							if(!empty($chkFBCustomer['profile_pic'])){
								$profile_pic = BASE_DOMAIN.'/images/users/'.$chkFBCustomer['profile_pic'];
							}
							$my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $chkFBCustomer['id'],'is_favourite' => 1])->result_array();
                            $data['customer'] =$this->getUserLoginObj($chkFBCustomer['id']);;

						
						}
					}  else {
						//echo "55"; exit;
						$_isCustomerFound = false;
						$data['errors'] = [];
						/* check if customer's email registed with us. */
						if(!empty($postData['email'])){
							$emailCond = [
								'email' => $postData['email'],
								'status' => 1
							];
							$emailCustInfo = $this->Common_m->get("rc_users",$emailCond, -1, true);
							if(!empty($emailCustInfo)){
								//$app_auth_token = $this->_generateAuthToken($emailCustInfo, $postData);
								$emailSaveArr = ['google_id' => $postData['google_id'], 'email_verify' => 1 ];
								/* get facebook image if customer has not set yet. */
								

								/* save facebook id customer_contacts */
								$this->Common_m->save('rc_users', $emailSaveArr, ['id' => $emailCustInfo['id']]);

								//$app_auth_token = $this->_generateAuthToken($emailCustInfo, $postData);
								$extra_flag = 1;
								$profile_pic = BASE_DOMAIN."/images/user.png";
								if(!empty($emailCustInfo['profile_pic'])){
									$profile_pic = BASE_DOMAIN.'/images/users/'.$emailCustInfo['profile_pic'];
								}
								$my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $emailCustInfo['id'],'is_favourite' => 1])->result_array();
								$data['customer']  = $this->getUserLoginObj($emailCustInfo['id']);;

								
								$_isCustomerFound =  true;
							}
						} else {
							$data['error'] = 'Please provide email detail with Google id.';
						}

						if(!$_isCustomerFound){
							$extra_flag = 3;
							$data['error'] = 'Please provide Email detail with Google id.';							
							$data['message'] = 'Please provide Email detail with Google id.';
						}
					}
				} else {
					$data['error'] = 'Google id is missing.';
				}


            } else {
				$data['error'] = 'Invalid login type.';
			}
		} else {
			$data['error'] = 'Invalid Parameters!';
		}



        //log_message('error', 'login response - '.json_encode($data));

		if(isset($data['error'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
	}


	private function _sendOTP($post, $customer_id){
		$data = [];
		$this->load->library('twilio');
		$insToken = [
			'customer_id' => $customer_id,
			'token' => $post['sms_otp'],
			'token_type' => 'register',
			'ip_address' => $this->input->ip_address()
		];
		/* Mark previous OTP expire if any */
		$this->Common_m->save('customer_tokens', ['status' => 2], ['customer_id' => $customer_id]);
		if($this->Common_m->save('customer_tokens', $insToken)){
			$sms_msg = 'Welcome to '.SITE_NAME.'. Your OTP for account verification is '.$post['sms_otp'].'. Please do not share this with anyone.';
			$Common_mobile = '+'.$post['country_code'].$post['phone'];
			$msgsend = $this->twilio->sms($sms_msg, $Common_mobile);
			$data['success'] = 'Thanks for registering with us. Please check SMS and verify your number.';
		} else {
			$data['error'] = 'Unable to send OTP. Please try again.';
		}
		return $data;
	}

	/* get and save facebook image */
    private function getSocialProfileImageId($social_id, $raw_url){
        $media_id = 0;
        //$fb_id = 1389388707773856;
        
        if(!empty($raw_url)){
            //if(isset($fbImg['data']['url'])){
                $upload_path = 'uploads/profile_pictures/';
                $upload_path = CDN_STORAGE . $upload_path;

                $fb_new_file_name = $social_id.'_'.time().'.jpg';
                $upload_full_path = $upload_path.$fb_new_file_name;
                
                $ch = curl_init($raw_url);
                $fp = fopen($upload_full_path, 'wb');

                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                curl_setopt($ch, CURLOPT_FAILONERROR, true);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                $result = curl_exec($ch);

                fclose($fp);
                curl_close($ch);
                
                
                if($result){
                    
                    $file = [
		                'mime_type' => 'image/jpg',
		                'file_name' => $fb_new_file_name,
		                'file_extension' => '.jpg',
		                'thumb_path' => $fb_new_file_name,
		                'type' => 'customer',
		                'image_width' => 600,
		                'image_height' => 600,
		                'status' => 1
		            ];
		            $this->__resize_image($fb_new_file_name, 'thumb', 400);
		            $media_id = $this->Common_m->save('uploads', $file);
                    
                }
        }
        return $media_id;
    }


    private function __resize_image($image_name = '', $path = '', $width = '') {

        $this->load->library('image_lib');
        $source_full_path = CDN_STORAGE . "uploads/profile_pictures/" . $image_name;
        $thumb_file_name = $image_name;
        $thumb_path = CDN_STORAGE . "uploads/profile_pictures/" . $path . "/";
        $config = array(
            'image_library' => 'gd2',
            'source_image' => $source_full_path,
            'new_image' => $thumb_path . $thumb_file_name,
            'create_thumb' => false,
            'maintain_ratio' => true,
            'width' => $width,
        );
        $this->image_lib->initialize($config);

        if ($this->image_lib->resize()) {
            //chmod($thumb_path, 0644);
            return true;
        } else {
            log_message('error', $this->image_lib->display_errors());
            return false;
        }
    }

	public function verify(){

		$this->load->model('Common_m');
		//$this->load->model('Wallet_m');
		$extra_flag = 0;
		//$_POST = json_decode(file_get_contents("php://input"), true);
		$postJson = file_get_contents("php://input");
        if(!empty($postJson)){
            $_POST = (array) json_decode($postJson,true);
        }
		$postData = $this->input->post();

		
		 if(isset($postData['otp']) && !empty($postData['otp'])){
			$cond = [
				'ct.token' => $postData['otp'],
				'ct.status' => 0
			];
			$tokenInfo = $this->Common_m->getCustomerToken($cond, -1, true);
			if(!empty($tokenInfo)){
				if($tokenInfo['token_type'] == 'register'){
					/* Mark token as used. */
					if($this->Common_m->save('customers', ['status' => 1, 'phone_verify' => 1], ['customer_id' => $tokenInfo['customer_id']])){

						/* get customer default currency */
						$_currencyInfo = $this->Common_m->get('currency', [
							'currency_id' => $tokenInfo['currency_id']
						], -1, true);

						
						$this->Common_m->save('customer_tokens', ['status' => 1], ['id' => $tokenInfo['id']]);
						//$app_auth_token = $this->_generateAuthToken($tokenInfo, $postData);
						$this->Common_m->save('customers', ['auth_token' => $app_auth_token], ['customer_id' => $tokenInfo['customer_id']]);
						//pr($app_auth_token); exit;
						$extra_flag = 1;
						$data['customer'] = $this->getUserLoginObj($tokenInfo['id']);				


					} else {
						$data['error'] = 'Unable to verify customer. Please try again.';
						
					}

					//die;
				} else if($tokenInfo['token_type'] == 'forgot'){
					$customerInfo = $this->Common_m->getInfo(['c.status' => 1, 'c.customer_id' => $tokenInfo['customer_id']], -1, true);
					if(!empty($customerInfo)){

						$this->Common_m->save('customer_tokens', ['status' => 1], ['id' => $tokenInfo['id']]);
						//$app_auth_token = $this->_generateAuthToken($tokenInfo, $postData);

						$this->Common_m->save('customers', ['auth_token' => $app_auth_token], ['customer_id' => $customerInfo['customer_id']]);


						$extra_flag = 1;
						$data['customer'] = $this->getUserLoginObj($tokenInfo['id']);;

						

					} else {
						$data['error'] = 'Unable to verify customer. Please try again.';
					}
				} else if($tokenInfo['token_type'] == 'phone_verify'){
					/* Mark token as used. */
					$tokenLogObj = json_decode($tokenInfo['log']);
					$upArr = [
						'country_code' => $tokenLogObj->country_code,
						'phone' => $tokenLogObj->phone,
						'phone_verify' => 1
					];
					if($this->Common_m->save('customers', $upArr, ['customer_id' => $tokenInfo['customer_id']])){

						$this->Common_m->save('customer_tokens', ['status' => 1], ['id' => $tokenInfo['id']]);
						$extra_flag = 1;

						/* get customer default currency */
						$_currencyInfo = $this->Common_m->get('currency', [
							'currency_id' => $tokenInfo['currency_id']
						], -1, true);

						/* check if contacts synced */
						// $chkContacts = $this->Common_m->getCustomerContacts(['cc.customer_id' => $customerInfo['customer_id']], -1);
						
						$data['customer'] = $this->getUserLoginObj($tokenInfo['id']);

					
					} else {
						$data['error'] = 'Unable to verify customer. Please try again.';
					}
				} else if($tokenInfo['token_type'] == 'guest_login'){
					$customerInfo = $this->Common_m->getInfo(['c.status' => 3, 'c.customer_id' => $tokenInfo['customer_id']], -1, true);
					if(!empty($customerInfo)){

						$this->Common_m->save('customer_tokens', ['status' => 1], ['id' => $tokenInfo['id']]);
						//$app_auth_token = $this->_generateAuthToken($tokenInfo, $postData);
						$extra_flag = 1;

						
						
						$data['customer'] = $this->getUserLoginObj($tokenInfo['id']);
						
						
						
						/*End Wallet Info*/

					} else {
						$data['error'] = 'Unable to verify customer. Please try again.';
					}
				} else {
					$data['error'] = 'Invalid token type.';
				}
			} else {
				$data['error'] = 'OTP was invalid or expire.';
			}
		} else {
			$data['error'] = 'Please enter your one time password.';
		}


		if(isset($data['error'])){
			$this->sendResponse($data, 204, $extra_flag);
		} else {
			$this->sendResponse($data, 200, $extra_flag);
		}
	}

	private function _generateAuthToken($customer, $device){
		$token = md5(sha1($customer['id'].time()));
		$custTokenArr = [
			'user_id' => $customer['id'],
			//'api_version' => 1,
			'device_type' => $device['device_type'],
			'device_id' => $device['device_id'],
			'login_type' => $device['login_type'],
			'app_auth_token' => $token
		];

		//pr($custTokenArr); exit;

		$this->Common_m->setCustomerDevice($custTokenArr);
		return $token;
	}

	

    
    
    

}
