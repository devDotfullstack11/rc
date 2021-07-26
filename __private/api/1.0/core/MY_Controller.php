<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {

        //$this->load->model(array('data'));
        public $log_flag = 0;
        function __construct() {
            parent::__construct();
           
            $this->load->model(array('User_model'));
            $this->load->helper('url');
            $this->load->model(array('LogData')); 
            
            $logdata = [
                 'formdata' => json_encode($this->input->post()),
                 'ip' =>  $this->get_client_ip(),
                 'method_name'  =>  $this->router->fetch_method()
            ];
            if($this->log_flag){
                $this->LogData->insert_entry($logdata);
            }
            //
        }

        function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
/*
       public function _remap($method)
                {
                        if ($method === 'index')
                        {
                                 echo "HIIII"       ;
                                //$this->$method();
                        }
                        else
                        {
                                $this->default_method();
                        }
                }*/


        /**
         * Index Page for this controller.
         *
         * Maps to the following URL
         *              http://example.com/index.php/welcome
         *      - or -
         *              http://example.com/index.php/welcome/index
         *      - or -
         * Since this controller is set as the default controller in
         * config/routes.php, it's displayed at http://example.com/
         *
         * So any other public methods not prefixed with an underscore will
         * map to /index.php/welcome/<method_name>
         * @see https://codeigniter.com/user_guide/general/urls.html
         */
        public function index()
        {
                echo 'Hello World!';        
                //$this->load->view('welcome_message');
        }

        function verifyToken($token){
            //rc_users_tokens
            $this->db->where("app_auth_token",$token); 
            $this->db->select("app_auth_token,device_id,device_type,user_id");
            $this->db->join("rc_users u","u.id=ud.user_id","INNER");
            $this->db->where(['u.is_deleted' => 0 , 'u.status' => 1]);
           $userData = $this->db->get("rc_users_devices ud")->row_array();
           //echo $this->db->last_query();
           return $userData;
        }





        public function checkadmin($data){
           // print_r($data); die;
             $current_route = $this->router->fetch_method();          
                if(isset($data['key'])) {
                    $record = $this->User_model->get_one($data['key']);
                    if(count($record) == 0){
                        $result = ['status' => '0','reason' => 'unauthorized' , 'message' => 'Please Login First' ];    
                    } else {
                        //print_r($record); die;
                        //echo $record['role_id']; die;
                        if($record['role_id'] == 1 ) {
                            $result = ['status' => '1','reason' => 'authorized' , 'userdata' => $record ];        
                        } else {
                            $result = ['status' => '0','reason' => 'unauthorized' , 'message' => 'Admin Protected Page' ];    
                        }
                        
                    }   
                } else {
                        $result = ['status' => '0','reason' => 'unauthorized' , 'message' => 'Please Login First' ];
                }    
           /* } else{
                $result = ['status' => '1'];
            }*/
            return $result;
        }




         /*
        |--------------------------------------------------------------------------
        | Function : register
        |--------------------------------------------------------------------------
        | This will be used to display the list of all admins to the super admin
        */

        
          /*
        |--------------------------------------------------------------------------
        | Function : login
        |--------------------------------------------------------------------------
        | Login Through mobile apps.
        */

       

       
        function truncate_float($number, $places) {
                $power = pow(10, $places); 
                if($number > 0){
                    return floor($number * $power) / $power; 
                } else {
                    return ceil($number * $power) / $power; 
                }
            }

            function sendmail1($view,$data,$userdata,$subject,$attachments)
            { 
                // CREATING COMMON FUNCTION FOR SENDING
                $config = Array(
                  'protocol' => 'smtp',
                  'smtp_host' => SMTP_HOST,
                  'smtp_port' => SMTP_PORT,
                  'smtp_user' => SMTP_USERNAME, // change it to yours
                  'smtp_pass' => SMTP_PASSWORD, // change it to yours
                  'mailtype' => 'html',
                  'charset' => 'utf-8',
                  'wordwrap' => TRUE,
                  'send_multipart' => FALSE,
                  'smtp_crypto' => SMTP_PROTOCOL
                );
               
              try {
                  $this->load->library('email');
                  $this->email->initialize($config);
                  $this->email->set_mailtype("html");
                  $message = $this->load->view($view,$data,true); 
                  $subject.=" ( ".SITE_LINK." )"; 
                  $this->email->set_newline("\r\n");
                  $this->email->from(SMTP_FROM); // change it to yours
                  $this->email->to($userdata['email']);// change it to yours
                  $this->email->subject($subject);
                  $this->email->message($message);
                  if(count($attachments) > 0){
                      foreach ($attachments as $a) {
                          $this->email->attach($a);
                      }
                  }
      
                  if($this->email->send()) {
                      $this->email->clear(true);
                      return '1';
                 } else {
                     show_error($this->email->print_debugger());
                      return '0';
                } 
              } catch (\Throwable $th) {
                  //throw $th;
                  return '0';
              }
              
      
            }
           // $view,$data,$userdata,$subject,$attachments

            public function sendmail($view,$data, $userdata, $subject, $attachments)
           {
           
                $this->load->library("PHPMailerLib");
            
                  //Create a new PHPMailer instance
                      $mail = new PHPMailerLib;
                       //Tell PHPMailer to use SMTP
                       $mail->isSMTP();
                       //Enable SMTP debugging
                       // 0 = off (for production use)
                       // 1 = client messages
                       // 2 = client and server messages
                      // $mail->SMTPDebug = 2;
                       //Ask for HTML-friendly debug output
                       $mail->SMTPKeepAlive = true;   
                       $mail->Mailer = "smtp"; // don't change the quotes
                       $mail->Debugoutput = 'html';
                       $mail->Host = SMTP_HOST;
                       $mail->Port = SMTP_PORT;
                       $mail->SMTPSecure = SMTP_PROTOCOL;
                       $mail->SMTPAuth = true;
                       $mail->XMailer = SITE_LINK;
                       $mail->Username = SMTP_USERNAME;
                       $mail->Password = SMTP_PASSWORD;
                       $mail->setFrom(SMTP_FROM, SITE_LINK);
                       $mail->IsHTML(true);
                       //$mail->addReplyTo(SMTP_EMAIL_REPLY_TO, SITE_LINK);
                       $mail->addAddress($userdata['email']);
                       $mail->Subject = $subject. ' | '.SITE_LINK;
                       $mail->msgHTML($this->load->view($view,$data,true));
                    if (!empty($attachments)) {
                        foreach ($attachments as $attachVal) {
                               $mail->addAttachment($attachVal['path']);
                        }
                    }
                        $mail->CharSet='utf-8';

                        //send the message, check for errors
                    if (!$mail->send()) {
                       // print_r($mail->ErrorInfo);
                        log_message('error', $mail->ErrorInfo);
                       // return false;
                    } else {
                        return true;
                    }
          }



    protected function sendResponse($data = null, $code = 400, $extra_flag = 0) {
        //log_message('error', get_called_class());
        // Making Chanages in Response 
        $http_message = [
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
        ];

        if (!is_numeric($code) || !isset($http_message[$code])) {
            $code = 400;
        }

        $collection = [
            'code' => $code,
            'message' => !empty($data['error']) ?  $data['error'] : $http_message[$code],
            'status_code' => $extra_flag
        ];

        if (!empty($data)) {
            if(!isset($data['error']) || empty($data['error']) ){
                $collection['data'] = $data;
            }
        } else {
            $collection['data'] = [];
        }

        $json = json_encode($collection, JSON_NUMERIC_CHECK);
        if (empty($json)) {
            $json = json_encode([
                'code' => 500,
                'message' => !empty($data['error']) ?  $data['error']: $http_message[500]  ,
                'status_code'=>$extra_flag
            ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        }

        $gmdate = gmdate('D, d M Y H:i:s') . ' GMT';
        $current_url = $_SERVER['REQUEST_URI'];
        $prepareReqData = $this->input->post();
        $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
                ->set_header('Cache-Control: post-check=0, pre-check=0')
                ->set_header('Pragma: no-cache')
                ->set_header('Last-Modified: ' . $gmdate)
                ->set_header('Expires: ' . $gmdate)
                //->set_status_header($code, $http_message[$code],$extra_flag)
                ->set_status_header(200, $http_message[$code],$extra_flag)
                ->set_output($json)
                ->_display();

        exit;
    }
    
    function getUserById($user_id){
        $this->db->where("id",$user_id);
        $result = $this->db->get("rc_users")->row_array();
        return $result;
    }

    // function getUserObj($us){

    // }


    function getUserLoginObj($user_id){
       // echo $user_id;
       $postData = $this->input->post();
        $this->db->where("id",$user_id);
        $customerInfo = $this->db->get("rc_users")->row_array();
        $app_auth_token = '';
        if(isset($postData['auth_token'])){
            $app_auth_token = $postData['auth_token'];    
        } else {    
            if(isset($postData['device_type']) && isset($postData['device_id'])){
              $app_auth_token = $this->_generateAuthToken1($customerInfo, $postData);  
            }      
            
        }
        
        $my_groups = $this->db->get_where("rc_favourite_groups",['user_id' => $customerInfo['id'],'is_favourite' => 1])->result_array();
						
        $profile_pic = BASE_DOMAIN."/images/user.png";
        if(!empty($customerInfo['profile_pic'])){
            $profile_pic = BASE_DOMAIN.'/images/users/'.$customerInfo['profile_pic'];
        } 
        $reference_code = $customerInfo['reference_code'];
        if(empty($customerInfo['reference_code'])){
            $reference_code = $this->generateReferenceCode($customerInfo);

        }
        return [
            'id' => $customerInfo['id'],
            'name' => $customerInfo['name'],
            'email' => $customerInfo['email'],
            'auth_token' => $app_auth_token,
            'profile_pic' => $profile_pic,
            'my_groups' => count($my_groups),
            'contribution' => 50,
            'tree_planted' => 250,
            'history_payout' => 450,
            'notification_enable' => $customerInfo['notification_enable'],            
            'email_enable' => $customerInfo['email_enable'],
            'age' => empty($customerInfo['age']) ? '' : "{$customerInfo['age']} ",
            'billing_address' => empty($customerInfo['billing_address']) ? '' : "{$customerInfo['billing_address']} ",
            'account_number' => empty($customerInfo['account_number']) ? '' : "{$customerInfo['account_number']} ",
            'bank_name' => empty($customerInfo['bank_name']) ? '' : "{$customerInfo['bank_name']} ",
            'transit_number' => empty($customerInfo['transit_number']) ? '' : "{$customerInfo['transit_number']} ",
            'branch_address' => empty($customerInfo['branch_address']) ? '' : "{$customerInfo['branch_address']} ",
            'branch_address' => empty($customerInfo['branch_address']) ? '' : "{$customerInfo['branch_address']} ",
            'is_profile_updated' => empty($customerInfo['is_profile_updated']) ? 0 : $customerInfo['is_profile_updated'],
            'is_profile_updated' => empty($customerInfo['is_profile_updated']) ? 0 : $customerInfo['is_profile_updated'],
            'add_reference_flag' => empty($customerInfo['add_reference_flag']) ? 0 : $customerInfo['add_reference_flag'],
            'reference_code' => $reference_code,
            'is_host' => $customerInfo['is_host'],
        ];

    }

    public function generateReferenceCode($customerInfo){
        $twoletters =  substr($customerInfo['name'],0,2);
        $reference_code="RC-{$twoletters}{$customerInfo['id']}";
        $this->db->update("rc_users",['reference_code' => $reference_code],['id' => $customerInfo['id']] ) ;
        return $reference_code;
    }

    private function _generateAuthToken1($customer, $device){
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
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    

   

}
