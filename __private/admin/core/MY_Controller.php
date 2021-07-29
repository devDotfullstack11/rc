<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {

        //$this->load->model(array('data'));
        protected $user_id;
        function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('url');
            $this->load->database();
            //pr($this->session->userdata());
            if(!empty($this->session->userdata())){
                $this->user_id = $this->session->userdata('id');
                $role_id = $this->session->userdata("role_id");
                if($role_id >= 2){
                    //$this->session->set_usUerdata('some_name', 'some_value');
                    //$user_wallet = $this->db->get_where('user_wallet', array('user_id' =>  $this->session->userdata("id")));
                    $user_walletRecord = [];  
                    //pr($user_walletRecord);
                    if(!empty($user_walletRecord)){
                        $this->session->set_userdata('amount',$user_walletRecord['amount']);  
                    }
                }
            }
            
            $this->load->helper(array('email')); $this->load->library(array('email'));
            //echo $current_route; die;


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

        public function checklogin($data){
           // print_r($data); die;
             $current_route = $this->router->fetch_method();
            $protected_actions = [
                'addexpense',
                'get_expenses',
                'addincome','get_income'
            ];
            //print_r(in_array($current_route, $protected_actions)); die;
            if(in_array($current_route, $protected_actions)){
                if(isset($data['key'])) {
                    $record = $this->User_model->get_one($data['key']);
                    if(count($record) == 0){
                        $result = ['status' => '0','reason' => 'unauthorized' , 'message' => 'Please Login First' ];    
                    } else {
                        $result = ['status' => '1','reason' => 'authorized' , 'userdata' => $record ];    
                    }   
                } else {
                        $result = ['status' => '0','reason' => 'unauthorized' , 'message' => 'Please Login First' ];
                }    
            } else{
                $result = ['status' => '1'];
            }
            return $result;
        }

        public function data(){
                $data = $this->Data_model->get_all();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($data));
        }

        public function checkadmin($data){
           // print_r($data); die;
             $current_route = $this->router->fetch_method();
            /*$protected_actions = [
                'requests',
            ];*/
            //print_r(in_array($current_route, $protected_actions)); die;
           // if(in_array($current_route, $protected_actions)){
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

        public function register(){
            //Including validation library
            $this->load->library('form_validation');
            //$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            //Validating Name Field
            $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');
            //$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[5]|max_length[15]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[15]');

            $this->form_validation->set_rules('equipment', 'Equipment', 'required|min_length[5]|max_length[50]');
            $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]|max_length[200]');
            //Validating Email Field
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            //Validating Mobile no. Field
            $this->form_validation->set_rules('phone', 'Mobile No.', 'required|is_unique[users.phone]|integer',
                array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
         ));

            //Validating Address Field
            //$this->form_validation->set_rules('gender', 'Gender', 'required');

            if ($this->form_validation->run() == FALSE) {
                    //echo json_encode(validation_errors());

                /*$arr = array(
                    'field_name_one' => form_error('field_name_one'),
                    'field_name_two' => form_error('field_name_two')
                );*/
                // errors_array()
                //$result = ['status' => '0','reason' => 'validation' , 'errors' => validation_errors()];
                $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                $ref = md5(rand(999,9999)).'-'.date('YmdHis');
                $data = array(
                'name' => $this->input->post('name'),
                'last_name' => 'None',
                'email' => $this->input->post('email'),
                'equipment' => $this->input->post('equipment'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
               // 'gender' => $this->input->post('gender'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'api_key' => $ref
                );
                //Transfering data to Model
                $this->User_model->insert_entry($data);
                $result = ['status' => '1','message' => 'Registered Successfully!','key' => $ref];
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));

        }

          /*
        |--------------------------------------------------------------------------
        | Function : login
        |--------------------------------------------------------------------------
        | Login Through mobile apps.
        */
       

        public function login(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[15]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                 $data = array(
                'email' => $this->input->post('email'),
                'password' =>$this->input->post('password'),
                );
               $result = $this->User_model->login($data);
                //$data['message'] = 'Data Inserted Successfully';
               // $result = ['status' => '1','message' => 'Registered Successfully!'];
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));
        }

          /*
        |--------------------------------------------------------------------------
        | Function : addexpense
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */


        public function addexpense(){
             $this->load->library('form_validation');
            //$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            //Validating Name Field
            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[5]|max_length[15]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[5]|max_length[15]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[15]');
            //Validating Email Field
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            //Validating Mobile no. Field
            $this->form_validation->set_rules('phone', 'Mobile No.', 'required|is_unique[users.phone]',
                array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
         ));

            //Validating Address Field
            $this->form_validation->set_rules('gender', 'Gender', 'required');

            if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => validation_errors()];
            }  else {
                //Setting values for tabel columns
                $ref = md5(rand(999,9999)).'-'.date('YmdHis');
                $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'api_key' => $ref
                );
                //Transfering data to Model
                $this->User_model->insert_entry($data);
                $result = ['status' => '1','message' => 'Registered Successfully!'];
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));    

        }

        function sendmail($view,$data,$userdata,$subject,$attachments)
      { 
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
           // "newline" => "\r\n",
            'smtp_crypto' => "tls"
            //$config['newline'] = "\r\n";
          );
          pr($config); 

        //    $config = Array(
        //     'protocol' => 'mail',
        //    /* 'smtp_host' => '80.95.186.235',
        //     'smtp_port' => 25,
        //     'smtp_user' => 'noreply@demo.digizap.in', // change it to yours
        //     'smtp_pass' => 'xJ5GUyD_xYit', // change it to yours*/
        //     'mailtype' => 'html',
        //     'charset' => 'utf-8',
        //     'wordwrap' => TRUE
        //   );
        
        // $mail_config['smtp_host'] = SMTP_HOST;
        // $mail_config['smtp_port'] = SMTP_PORT;
        // $mail_config['smtp_user'] = SMTP_USERNAME;
        // $mail_config['_smtp_auth'] = TRUE;
        // $mail_config['smtp_pass'] = 'g@gmail.com';
        // $mail_config['smtp_crypto'] = 'ssl';
        // $mail_config['protocol'] = 'smtp';
        // $mail_config['mailtype'] = 'html';
        // $mail_config['send_multipart'] = FALSE;
        // $mail_config['charset'] = 'utf-8';
        // $mail_config['wordwrap'] = TRUE;
        try {
            $this->load->library('email');
$this->email->initialize($config);       
            
$this->email->set_newline("\r\n");
$this->email->set_mailtype("html");
            //$message = $this->load->view($view,$data,true);             
            $message = "TEST";             
            //$this->load->library('email'); 
            $subject ="Test";
           // $this->email->set_newline("\r\n");
            //$this->email->from($config['smtp_user']); // change it to yours
            $this->email->from('manyder@gmail.com'); // change it to yours
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
               //$this->email->clear(true);
                return '0';
          }
        } catch (\Throwable $th) {
            //throw $th;
            return '0';
        }
        

      }
     // $view,$data,$userdata,$subject,$attachments
      public function sendmail1($view,$data, $userdata, $subject, $attachments)
     {

        require_once(APPPATH.'/third_party/phpmailer/PHPMailerAutoload.php');
        // prepare email message /
        // $emailData = file_get_contents(APPPATH.'/views/emailTemplates/head.html');
        // $emailData .= file_get_contents(APPPATH.'/views/emailTemplates/'.$tempaltename);
        // $emailData .= file_get_contents(APPPATH.'/views/emailTemplates/footer.html');
        // $maildata['SITEURL'] = base_url();
        // $maildata['MAIN_WEBSITE'] = MAIN_WEBSITE;
        // $maildata['SITE_NAME'] = SITE_NAME;
        // foreach($maildata as $mailKey => $mailValue)
        // {
        //   $emailData = str_replace('%%'.$mailKey.'%%', $mailValue, $emailData);
        // }
       
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            $mail->Host = 'ssl://smtp.googlemail.com';
            $mail->Port = '587';
            // $mail->SMTPSecure = true;
            // $mail->SMTPAuth = true;
           // $mail->XMailer = "localhost";
            $mail->Username = 'maninder2singh@gmail.com';
            $mail->Password = 'g@gmail.com';
            $mail->setFrom('maninder2singh@gmail.com');
            //$mail->addReplyTo(SMTP_EMAIL_REPLY_TO, SITE_NAME);
            $mail->addAddress($userdata['email']);
            $mail->Subject = $subject;
            $mail->msgHTML($this->load->view($view,$data,true));
            $mail->CharSet='utf-8';

            //send the message, check for errors
            if(!$mail->send()) {
                pr($mail->ErrorInfo); exit;
                //log_message('error', $mail->ErrorInfo);
                return false;
            } else {
                return true;
            }
    }

    function saveReport($saveArr){
        $this->db->insert("employee_report",$saveArr);
    }

    function saveLog($oldData,$newData,$module,$message,$user_id,$module_table_id=0){
        $LogsaveArr = [
            'user_id' => $user_id,
            'module' => $module,
            'report_message' => $message,
            'json_data' => '',
            'module_table_id' => $module_table_id
        ];
        $json_data = "";
        if(!empty($oldData)){
            // UPDATE QUERY
            foreach($newData as $key =>  $val){
                // echo $val;
                 // echo $data[$key];
                 // echo $val;
                if(isset($oldData[$key])){
                    //echo $oldData[$key];
                    if($oldData[$key] !== $val){
                     if($key == 'is_assigned'){
                         continue;
                     }
                        if($key == 'assigned_user_id'){  
                           if($val > 0){
                               $this->db->where("id",$val);
                               $userData = $this->db->get("users")->row_array();
                               if(!empty($userData)){
                                 $json_data['Assigned To'] = "Lead Assigned to {$userData['name']} ({$userData['phone']})";        
                                // $report_message.="Lead Assigned to {$userData['name']} ({$userData['phone']})<br>";          
                               }
                           }
                        } else {
                         $json_data[$key] = "{$oldData[$key]} (old value) => ($val) (new value)";        
                         //$report_message.="{$key} :: {$record_arr[$key]} (old value) => ($val) (new value) <br>";
                        }
                        
                    }
                } 
             }
        } else {
            // INSERT QUERY
            $json_data = $newData;
        }
        $LogsaveArr['json_data'] = (!empty($json_data)) ? json_encode($json_data) : '';
        //$LogsaveArr['report_message'] = $report_message;                        
        $this->saveReport($LogsaveArr);
    }

}
