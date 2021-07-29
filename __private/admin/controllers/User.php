<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

        //$this->load->model(array('data'));
        
        function __construct() {
            parent::__construct();
          
            $this->load->model(array('User_model'));
            $this->load->helper('url');
            
            $this->load->view('common_style'); 
            //pr($this->session->userdata());exit;
            //echo $this->router->fetch_method(); exit;
            if(empty($this->session->userdata('id')) && 
                     $this->router->fetch_method() !== 'index' && 
                     $this->router->fetch_method() !== 'login' &&
                     $this->router->fetch_method() !== 'logout' &&
                     $this->router->fetch_method() !== 'forgot_password'
                     
                     ){
                return redirect("/");
            }   
        }



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
            
			$this->load->library('session');
			if($this->session->has_userdata('email')){
                $this->load->model(['Permission_model']);
                $user_id = $this->session->userdata('id');
                $role_id = $this->session->userdata('role_id');
                $data['packages'] =[];
                if($role_id >= 2){
                    if($role_id > 3){
                        $role_id =7; 
                    } 
                    // $this->db->where(['status' => 1,'role_id' =>  $role_id,'is_deleted' => 0]);
                    // $this->db->order_by("sort_order",'ASC');
                   // $result = $this->db->get("packages");
                    $data['packages'] = array();
                }
                $this->db->where_in("setting_key",['admin_bank_name','admin_account_holder_name','admin_account_number','admin_ifsc_code','admin_branch_city']);
                $bank_details = $this->db->get("rc_global_settings")->result_array();
                $data['bank_details'] = $bank_details;
                //echo $user_id;
                //$modules = $this->Permission_model->getUserModules($user_id);
                //pr($modules);
                //$allowed_modules = array_column($modules,'module_id');
                //pr($allowed_modules); 
                //$this->load->view('home');
                //$this->session->set_userdata('modules', $allowed_modules);
				$data['middle'] = 'home';
				$data['result'] = [];
				$this->load->view('template',$data);
			} else {
				$this->load->helper('form');				
				$this->load->view('login');    
			}
        }

        public function data(){
                $data = $this->Data_model->get_all();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($data));
        }

        public function change_password(){
           // echo "Test";
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $this->load->helper('form');
            $this->load->library('upload', $config);
            if(count($this->input->post()) > 0){
                $this->load->library('form_validation');
                //Validating Name Field
                $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[5]|max_length[30]');
                $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[5]|max_length[30]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[5]|max_length[30]|matches[new_password]');
                //Validating Email Field            
                if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => validation_errors()];
                }  else {
                    //Setting values for tabel columns
                    $ref = md5(rand(999,9999)).'-'.date('YmdHis');
                    $data = array(
                    'old_password' => $this->input->post('old_password'),
                    'new_password' => $this->input->post('new_password'),
                    'confirm_password' => $this->input->post('confirm_password'),
                    //'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    );
                    //Transfering data to Model
                    $is_updated = $this->User_model->change_password($data,$this->session->userdata('id'));
                    //echo $is_updated;
                    if($is_updated == 1){
                        $this->session->set_flashdata('msg', 'Password Updated!');
                    } else {
                        $this->session->set_flashdata('msg', 'Old Password did not match!'); 
                    }
                    //$result = ['status' => '1','message' => 'Registered Successfully!'];
            }
            }
            $data['middle'] = 'users/change_password';
            $data['title'] = "Change Password";
            $data['result'] = [];
            $data['b_links'] = [
                ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                ['href' => '#', 'fa_icon' => 'fa-user', 'link_text' => 'Update Password']

        ];
            $this->load->view('template',$data);
            //print_r($product);

        }

        public function changepassword(){
           

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));
        }

         /*
        |--------------------------------------------------------------------------
        | Function : register
        |--------------------------------------------------------------------------
        | This will be used to display the list of all admins to the super admin
        */

        public function register(){
            //echo "Test"; die;
            //Including validation library
            $this->load->library('form_validation');
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
                //'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'password' => md5($this->input->post('password')),
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
          /*
        |--------------------------------------------------------------------------
        | Function : Add SUb Admin
        |--------------------------------------------------------------------------
        | To Add SUb admin
        */

        public function manage($id =""){
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $this->load->helper('form');
            $this->load->library('upload', $config);
            $data['user'] =[];
            if(!empty($id)){
                $details = $this->db->get_where('users', array('id' => $id));
                $record_arr = $details->first_row('array');
                $data['user'] = $record_arr;
            }
            if(count($this->input->post()) > 0){
                // IF POST DATA AVAILABLE
                $this->load->library('form_validation');
                $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');
                
                if(empty($id)){
                    // INSERT
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
                    
                } else {
                    // UPDATE
                    if($data['user']['email'] !== $this->input->post('email')){
                        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
                    } else {
                        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                    }

                    if($data['user']['phone'] !== $this->input->post('phone')){
                        $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[users.phone]');
                    } else {
                        $this->form_validation->set_rules('phone', 'Phone', 'required');
                    }
                }
                
               // print_r($this->input->post());
                if ($this->form_validation->run() == TRUE) { 
                    $data = array(
                            'name' => $this->input->post('name'),
                            'email' => $this->input->post('email'),
                            'phone' => $this->input->post('phone'),
                            'status' => $this->input->post('status'),
                            'role_id' => $this->input->post('role_id'),
                            //'password' => md5($this->input->post('password')),
                            'api_key' => md5($this->input->post('email'))
                        );
                    if(!empty($this->input->post('password'))){
                        $data['password'] = md5($this->input->post('password'));
                    }
                       // pr($data);
                        //Transfering data to Model
                        
                        if(!empty($id)){
                            $where = ['id' =>  $id];
                            $this->db->update('users',$data,$where);
                            $this->session->set_flashdata('msg', 'Sub Admin Updated!');
                        } else {
                            $this->db->insert('users',$data);
                            $this->session->set_flashdata('msg', 'Sub Admin Created!');
                        }
                        
                        redirect('user/sub_admins');

                }

            }

            $data['middle'] = 'users/manage';
            $data['result'] = [];
            $data['title'] = 'Add Sub Admin';
            $data['id'] = $id;
            
            $data['b_links'] = [
                    ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                    ['href' => base_url().'/user/sub_admins', 'fa_icon' => 'fa-user', 'link_text' => 'Sub Admins List'],
                    ['href' => '#', 'fa_icon' => 'fa-plus', 'link_text' => 'Add Sub Admin'],

            ];
            $this->load->view('template',$data);
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
            $this->form_validation->set_rules('email', 'Email', 'required');
            if ($this->form_validation->run() == FALSE) {
                    $this->load->helper('form');
                    $this->load->view('login'); 
                    //$result = ['status' => '0','reason' => 'validation' , 'errors' => validation_errors() ];
            }  else {
                //Setting values for tabel columns

                $data = array(
                        'email' => $this->input->post('email'),
                        'password' =>$this->input->post('password'),
                    );
                
               $result = $this->User_model->login($data);
               //echo 'test';
            //   print_r($result); 
               if($result['status'] == '1'){
                    //$this->db->select("users.*");
                    //$this->db->join("user_wallet","user_wallet.user_id = users.id","left");
                    $userdata = $this->User_model->get_one($result['key']);
                   // print_r($userdata); die;
                    $newdata = array(
                            'email'   	=> $this->input->post('email'),
                            'name' 		=>  $userdata['name'],
                            'id' 		=>  $userdata['id'],
                            'role_id' 		=>  $userdata['role_id'],
                            //'package_id' 	=>  $userdata['package_id'],
                            'logged_in' => true,
                            'api_key' 	=> $result['key'],
                            'amount' => 0
                    );
                    if($userdata['role_id'] == 2){
                        // $dateNow = date("Y-m-d H:i:s");
                        // $saveArr = [
                        //     'user_id' => $userdata['id'],
                        //     'module' => 'login',
                        //     'report_message' => "{$userdata['name']} ({$userdata['email']}) Logged in at {$dateNow}"
                        // ];
                        // $this->saveReport($saveArr);
                    }
                    
                    //print_r( $newdata ); die;
                    $this->session->set_userdata($newdata);
                    redirect('/');
               } else {
                   // pr($result);
                    $this->session->set_flashdata('error', $result['reason']);
                    $this->load->helper('form');
                    $this->load->view('login'); 
               }
                //$data['message'] = 'Data Inserted Successfully';
               // $result = ['status' => '1','message' => 'Registered Successfully!'];
        }

       /* return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));*/
        }


         public function logout(){
            $newdata = array(
                            'email',
                            'logged_in' ,
                            'api_key'
                    );
                $this->session->unset_userdata($newdata);
                redirect('/');
        }



         public function sub_admins(){
                //$data = $this->User_model->get_all();
                //$modules = $this->session->userdata('modules');
                //pr($this->session->userdata());
                
                $this->load->model(['Permission_model','Common_m']);
                //echo $this->session->userdata('role_id'); exit;
                if($this->session->userdata('role_id') !== "1" ){
                    $permissionData = $this->Permission_model->verifyPermissions($this->session->userdata('id'),'manage_subadmin');
                    if(empty($permissionData)){
                        //pr($permissionData);
                        return redirect("/");
                    }
                }
                
                //pr();
                $this->load->library('pagination');

                   $params = array();
                    $limit_per_page = 10;
                    $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                    //$total_records = $this->User_model->get_total();
                    $where = ['role_id' => 2,'is_deleted' => 0];
                     $total_records = $this->Common_m->get('users', $where, $offset = 0, $is_single = false, $is_total = true);
                    //echo $total_records;
                    $params["result"] =[];
                    if ($total_records > 0) 
                    {
                        // get current page records
                        //$params["result"] = $this->User_model->get_current_page_records($limit_per_page, $start_index,$where);
                         $params["result"] = $this->Common_m->get('users', $where, $offset = $start_index, $is_single = false, $is_total = false,'created_at',"DESC");
                        $config['base_url'] = base_url() . 'index.php/user/sub_admins';
                        $config['total_rows'] = $total_records;
                        $config['per_page'] = $limit_per_page;
                        $config["uri_segment"] = 3;
                        $config['full_tag_open'] = '<ul class="pagination">';
                        $config['full_tag_close'] = '</ul>';
                        $config['first_link'] = false;
                        $config['last_link'] = false;
                        $config['first_tag_open'] = '<li>';
                        $config['first_tag_close'] = '</li>';
                        $config['prev_link'] = '&laquo';
                        $config['prev_tag_open'] = '<li class="prev">';
                        $config['prev_tag_close'] = '</li>';
                        $config['next_link'] = '&raquo';
                        $config['next_tag_open'] = '<li>';
                        $config['next_tag_close'] = '</li>';
                        $config['last_tag_open'] = '<li>';
                        $config['last_tag_close'] = '</li>';
                        $config['cur_tag_open'] = '<li class="active"><a href="#">';
                        $config['cur_tag_close'] = '</a></li>';
                        $config['num_tag_open'] = '<li>';
                        $config['num_tag_close'] = '</li>';
                        $this->pagination->initialize($config);
                        // build paging links
                        $params["links"] = $this->pagination->create_links();
                    }

                $params['middle'] = 'users/list';
                $params['title'] = 'Sub Admin Management';
                $params['b_links'] = [
                        ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                        ['href' => '#', 'fa_icon' => 'fa-user', 'link_text' => 'Sub Admins List']

                ];
                $this->load->view('template',$params);
        }

        public function get_one_by_email($email){

        }

        public function activate(){
            $formdata = $this->input->post();
            //print_r($formdata); die;
            if(count($formdata) > 0){
              $this->User_model->activate($formdata);     
              $result = ['status' => 'success'];
            }

            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result)); 

        }
        /* 
        
        FRONT END SETTINGs
        */
        public function frontend_settings(){
            $this->load->helper('form');

            // $this->load->library('ckeditor');
            //         $this->load->helper('ckeditor');
            if(count($this->input->post())){
               // pr($this->input->post());
               $postData = $this->input->post();
               foreach($postData as $k =>  $v){
                 $this->db->where("setting_key", $k);
                 $record_arr = $this->db->get("global_settings")->row_array();
                 $updateData = [
                    'setting_value' => $v
                 ];
                 $this->saveLog($record_arr,$updateData,'Global Settings','Settings Updated!',$this->user_id,$record_arr['id']);
                 $this->db->where("setting_key", $k);
                 $this->db->update("global_settings",$updateData);                   
                 $this->session->set_flashdata('msg', 'Settins Updated');
               }
            }

                $global_settings = $this->db->get("global_settings");
                $setting_data = $global_settings->result_array();
                //pr($setting_data);
                    $data['middle'] = 'users/frontend_settings';
                    $data['title'] = 'Front End Settings';
                    $data['b_links'] = [
                            ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                            ['href' => '#', 'fa_icon' => 'fa-gear', 'link_text' => 'Front End Settings']
    
                    ];
                    $data['result'] = $setting_data;
                    $this->load->view('template',$data);

         }



        public function global_settings(){
            
            //$this->load->library('ckeditor');
            //$this->load->helper('ckeditor');

            
            $this->load->model(array('GlobalSetting'));
            $admin_email = $this->GlobalSetting->get_one_by_key('admin_email');
            $contact_phone = $this->GlobalSetting->get_one_by_key('contact_phone');

            $facebook_page = $this->GlobalSetting->get_one_by_key('facebook_page');
            $pininterst_url = $this->GlobalSetting->get_one_by_key('pininterst_url');
            $twitter_url = $this->GlobalSetting->get_one_by_key('twitter_url');
            $youtube_url = $this->GlobalSetting->get_one_by_key('youtube_url');
            $about_us = $this->GlobalSetting->get_one_by_key('about_us');
            $terms = $this->GlobalSetting->get_one_by_key('terms');
            $privacy = $this->GlobalSetting->get_one_by_key('privacy');
            // $our_drivers = $this->GlobalSetting->get_one_by_key('our_drivers');
            // $career = $this->GlobalSetting->get_one_by_key('career');
            // $admin_page_content = $this->GlobalSetting->get_one_by_key('admin_page_content');
            
            // $opening_time = $this->GlobalSetting->get_one_by_key('opening_time');
            // $closing_time = $this->GlobalSetting->get_one_by_key('closing_time');
            // $opening_weekday = $this->GlobalSetting->get_one_by_key('opening_weekday');
            // $closing_weekday = $this->GlobalSetting->get_one_by_key('closing_weekday');
            
            // $track_shipment_content = $this->GlobalSetting->get_one_by_key('track_shipment_content');


            $this->load->helper('form');
            $current_user = $this->session->userdata();
            //$this->checklogin($current_user);
            $data['middle'] = 'users/global_settings';
            $data['result'] = [
                    'admin_email' => (count($admin_email)) ? $admin_email : [],
                    'contact_phone' => (count($contact_phone)) ? $contact_phone : [] ,
                    'facebook_page' =>(count($facebook_page)) ? $facebook_page : [] ,
                    'pininterst_url' =>(count($pininterst_url)) ? $pininterst_url : [],
                    'twitter_url' =>(count($twitter_url)) ? $twitter_url : [] ,
                    'youtube_url' =>(count($youtube_url)) ? $youtube_url : [] ,
                    'about_us' =>(count($about_us)) ? $about_us : [] ,
                    'terms' =>(count($terms)) ? $terms : [] ,
                    'privacy' =>(count($privacy)) ? $privacy : [] ,
                    // 'our_drivers' =>(count($our_drivers)) ? $our_drivers : [] ,
                    // 'career' =>(count($career)) ? $career : [],
                    // 'admin_page_content' =>(count($admin_page_content)) ? $admin_page_content : [],
                    // 'opening_time' =>(count($opening_time)) ? $opening_time : [],
                    // 'closing_time' =>(count($closing_time)) ? $closing_time : [],
                    // 'opening_weekday' =>(count($opening_weekday)) ? $opening_weekday : [],
                    // 'closing_weekday' =>(count($closing_weekday)) ? $closing_weekday : [],
                    // 'track_shipment_content' =>(count($track_shipment_content)) ? $track_shipment_content : [],
            ];
            $this->load->view('template',$data);
          }

      public function save_global_settings(){

                    //echo "asdf"; die;
             $this->load->library('form_validation');
             $this->load->model(array('GlobalSetting'));
             $this->form_validation->set_rules('admin_email', 'Admin Email', 'required|min_length[5]|max_length[255]');
             $this->form_validation->set_rules('contact_phone', 'Admin Phone Number', 'required|min_length[5]|max_length[255]');
             $this->form_validation->set_rules('facebook_page', 'Facebook Page URL', 'required');
             $this->form_validation->set_rules('pininterst_url', 'Pininterest URL', 'required');
             $this->form_validation->set_rules('twitter_url', 'Twitter URL', 'required');
             $this->form_validation->set_rules('youtube_url', 'Youtube URL', 'required');
             $this->form_validation->set_rules('about_us', 'About Us', 'required');       
             // $this->form_validation->set_rules('our_drivers', 'Our Drivers', 'required|min_length[5]|max_length[1500]');       
             // $this->form_validation->set_rules('career', 'Career Page Content', 'required|min_length[5]|max_length[1000]');       
             // $this->form_validation->set_rules('admin_page_content', 'Administration Page Content', 'required|min_length[5]|max_length[1000]');       
             
             // $this->form_validation->set_rules('opening_time', 'Opening Time', 'required|max_length[700]');       
             // $this->form_validation->set_rules('closing_time', 'Closing Time', 'required|max_length[700]');       
             // $this->form_validation->set_rules('opening_weekday', 'Opening Weekday', 'required|max_length[700]');       
             // $this->form_validation->set_rules('closing_weekday', 'Closing Weekday', 'required|max_length[700]');       
             // $this->form_validation->set_rules('track_shipment_content', 'Track Shipment Content', 'required|max_length[700]');       
            $admin_email = $this->GlobalSetting->get_one_by_key('admin_email');
            $contact_phone = $this->GlobalSetting->get_one_by_key('contact_phone');
            //print_r($this->input->post('contact_phone')); die;
            $facebook_page = $this->GlobalSetting->get_one_by_key('facebook_url');
            $pininterst_url = $this->GlobalSetting->get_one_by_key('pininterst_url');
            $twitter_url = $this->GlobalSetting->get_one_by_key('twitter_url');
            $youtube_url = $this->GlobalSetting->get_one_by_key('youtube_url');
            $about_us = $this->GlobalSetting->get_one_by_key('about_us');
            $terms = $this->GlobalSetting->get_one_by_key('terms');
            $privacy = $this->GlobalSetting->get_one_by_key('privacy');
            // $our_drivers = $this->GlobalSetting->get_one_by_key('our_drivers');
            // $career = $this->GlobalSetting->get_one_by_key('career');
            // $admin_page_content = $this->GlobalSetting->get_one_by_key('admin_page_content');

            // $opening_time = $this->GlobalSetting->get_one_by_key('opening_time');
            // $closing_time = $this->GlobalSetting->get_one_by_key('closing_time');
            // $opening_weekday = $this->GlobalSetting->get_one_by_key('opening_weekday');
            // $closing_weekday = $this->GlobalSetting->get_one_by_key('closing_weekday');
            // $admin_page_content = $this->GlobalSetting->get_one_by_key('admin_page_content');

             
           if ($this->form_validation->run() == FALSE) {
                    //print_r($this->form_validation->error_array()); die;
                    $this->load->library('ckeditor');
                    $this->load->helper('ckeditor');
                    $data['middle'] = 'users/global_settings';
                    $data['result'] = [
                    'admin_email' => (count($admin_email)) ? $admin_email : [],
                    'contact_phone' => (count($contact_phone)) ? $contact_phone : [] ,
                    'facebook_page' =>(count($facebook_page)) ? $facebook_page : [] ,
                    'pininterst_url' =>(count($pininterst_url)) ? $pininterst_url : [],
                    'twitter_url' =>(count($twitter_url)) ? $twitter_url : [] ,
                    'youtube_url' =>(count($youtube_url)) ? $youtube_url : [] ,
                    'about_us' =>(count($about_us)) ? $about_us : [] ,
                    'terms' =>(count($terms)) ? $terms : [] ,
                   
            ];
                    $this->load->view('template',$data);
            }  else {
            
           
             //print_r($about_us);die();
            if(count($admin_email) > 0 ){
                $this->GlobalSetting->update_entry_by_key('admin_email',['setting_key' => 'admin_email', 'setting_value' => $this->input->post('admin_email')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'admin_email', 'setting_value' => $this->input->post('admin_email')]);   
            }

            if(count($contact_phone) > 0 ){
                $this->GlobalSetting->update_entry_by_key('contact_phone',['setting_key' => 'contact_phone', 'setting_value' => $this->input->post('contact_phone')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'contact_phone', 'setting_value' => $this->input->post('contact_phone')]);   
            }

            if(count($facebook_page) > 0 ){
                $this->GlobalSetting->update_entry_by_key('facebook_page',['setting_key' => 'facebook_page', 'setting_value' => $this->input->post('facebook_page')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'facebook_page', 'setting_value' => $this->input->post('facebook_page')]);   
            }

            if(count($pininterst_url) > 0 ){
                $this->GlobalSetting->update_entry_by_key('pininterst_url',['setting_key' => 'pininterst_url', 'setting_value' => $this->input->post('pininterst_url')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'pininterst_url', 'setting_value' => $this->input->post('pininterst_url')]);   
            }

             if(count($twitter_url) > 0 ){
                $this->GlobalSetting->update_entry_by_key('twitter_url',['setting_key' => 'twitter_url', 'setting_value' => $this->input->post('twitter_url')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'twitter_url', 'setting_value' => $this->input->post('twitter_url')]);   
            }

             if(count($youtube_url) > 0 ){
                $this->GlobalSetting->update_entry_by_key('youtube_url',['setting_key' => 'youtube_url', 'setting_value' => $this->input->post('youtube_url')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'youtube_url', 'setting_value' => $this->input->post('youtube_url')]);   
            }
            if(count($about_us) > 0 ){
                $this->GlobalSetting->update_entry_by_key('about_us',['setting_key' => 'about_us', 'setting_value' => $this->input->post('about_us')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'about_us', 'setting_value' => $this->input->post('about_us')]);   
            }

            if(count($terms) > 0 ){
                $this->GlobalSetting->update_entry_by_key('terms',['setting_key' => 'terms', 'setting_value' => $this->input->post('terms')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'terms', 'setting_value' => $this->input->post('terms')]);   
            }

             if(count($privacy) > 0 ){
                $this->GlobalSetting->update_entry_by_key('privacy',['setting_key' => 'privacy', 'setting_value' => $this->input->post('privacy')]);   
            } else {
                $this->GlobalSetting->insert_entry(['setting_key' => 'privacy', 'setting_value' => $this->input->post('privacy')]);   
            }
            

            // if(count($our_drivers) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('our_drivers',['setting_key' => 'our_drivers', 'setting_value' => $this->input->post('our_drivers')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'our_drivers', 'setting_value' => $this->input->post('our_drivers')]);   
            // }

            // if(count($career) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('career',['setting_key' => 'career', 'setting_value' => $this->input->post('career')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'career', 'setting_value' => $this->input->post('career')]);   
            // }

            // if(count($admin_page_content) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('admin_page_content',['setting_key' => 'admin_page_content', 'setting_value' => $this->input->post('admin_page_content')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'admin_page_content', 'setting_value' => $this->input->post('admin_page_content')]);   
            // }

            // if(count($opening_time) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('opening_time',['setting_key' => 'opening_time', 'setting_value' => $this->input->post('opening_time')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'opening_time', 'setting_value' => $this->input->post('opening_time')]);   
            // }


            // if(count($closing_time) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('closing_time',['setting_key' => 'closing_time', 'setting_value' => $this->input->post('closing_time')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'closing_time', 'setting_value' => $this->input->post('closing_time')]);   
            // }


            // if(count($opening_weekday) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('opening_weekday',['setting_key' => 'opening_weekday', 'setting_value' => $this->input->post('opening_weekday')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'opening_weekday', 'setting_value' => $this->input->post('opening_weekday')]);   
            // }


            // if(count($closing_weekday) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('closing_weekday',['setting_key' => 'closing_weekday', 'setting_value' => $this->input->post('closing_weekday')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'closing_weekday', 'setting_value' => $this->input->post('closing_weekday')]);   
            // }
            // if(count($track_shipment_content) > 0 ){
            //     $this->GlobalSetting->update_entry_by_key('track_shipment_content',['setting_key' => 'track_shipment_content', 'setting_value' => $this->input->post('track_shipment_content')]);   
            // } else {
            //     $this->GlobalSetting->insert_entry(['setting_key' => 'track_shipment_content', 'setting_value' => $this->input->post('track_shipment_content')]);   
            // }
            
            redirect('user/global_settings');
            }

      }
      
      function delete($user_id){
        if(!empty($user_id)){
            $where = ['id' =>  $user_id];
            $data = ['is_deleted' => 1];
            $this->db->update('users',$data,$where);
            $result = ['status' => "success" , 'message' => 'Deleted!'];
        } else {
            $result = ['status' => "failed" , 'message' => 'Something went wrong'];
        }
        echo json_encode($result); exit;
      }

      function profile($id =''){
        $params['middle'] = 'users/profile';
        $id = $this->session->userdata('id');
        $params['id'] = $id;
        $params['title'] = 'Update Profile';
        $params['result'] = 'Update Profile';
        $params['user'] = $this->db->get_where('users',['id' => $this->session->userdata('id') ])->row_array();
        $this->load->helper('form');

        if(!empty($id)){
            $details = $this->db->get_where('users', array('id' => $id));
            $record_arr = $details->first_row('array');
            $params['user'] = $record_arr;
        }
        if(count($this->input->post()) > 0){
            // IF POST DATA AVAILABLE
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'required|min_length[1]|max_length[50]');
            if($params['user']['email'] !== $this->input->post('email')){
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            } else {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            }

            if($params['user']['phone'] !== $this->input->post('phone')){
                $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[users.phone]');
            } else {
                $this->form_validation->set_rules('phone', 'Phone', 'required');
            }
            
           // print_r($this->input->post());
            if ($this->form_validation->run() == TRUE) {
                $postData =$this->input->post();
                $ref = md5($this->input->post('email')).'-'.date('YmdHis');
                $data = array(
                        'name' => $this->input->post('name'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        'city' => (isset($postData['city']) && !empty($postData['city'])) ? $postData['city'] : '',
                        'district' => (isset($postData['district']) && !empty($postData['district'])) ? $postData['district'] : '',
                        'pincode' => (isset($postData['pincode']) && !empty($postData['pincode'])) ? $postData['pincode'] : '',
                        'qualification' => (isset($postData['qualification']) && !empty($postData['qualification'])) ? $postData['qualification'] : '',
                        'branch_code' => (isset($postData['branch_code']) && !empty($postData['branch_code'])) ? $postData['branch_code'] : '',
                        'branch_address' => (isset($postData['branch_address']) && !empty($postData['branch_address'])) ? $postData['branch_address'] : '',
                        'dob' => (isset($postData['dob']) && !empty($postData['dob'])) ? $postData['dob'] : '',
                        'designation' => (isset($postData['designation']) && !empty($postData['designation'])) ? $postData['designation'] : '',
                        
                    );;
                    
         
                   // pr($data);
                    //Transfering data to Model
                    
                    if(!empty($id)){
                        $where = ['id' =>  $id];
                        $this->db->update('users',$data,$where);
                        $this->session->set_flashdata('msg', 'Profile Updated!');
                    } 
                    
                    redirect('user/profile');

            } else {
               // echo validation_errors(); exit;
            }

        }


        if(empty($params['user'])){
            return redirect("/");
        }
        $params['b_links'] = [
                ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                ['href' => '#', 'fa_icon' => 'fa-user', 'link_text' => 'Update User Profile']

        ];
        $this->load->view('template',$params); 
      }
      /* 
      * Forgot Passowrd

      */

      function forgot_password(){
        $params['middle'] = 'users/profile';
        $id = $this->session->userdata('id');
        
       
        $this->load->helper('form');

        
        if(count($this->input->post()) > 0){
            // IF POST DATA AVAILABLE
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required');

            
            
           // print_r($this->input->post());
            if ($this->form_validation->run() == TRUE) {
                $this->db->where("email",$this->input->post('email'));
                $this->db->or_where("phone",$this->input->post('email'));
                $details = $this->db->get('users')->first_row('array');
                if(!empty($details)){
                    $response = $this->User_model->reset_password($details);
                    $response['name'] = $details['name'];
                    $this->sendmail('emails/forgot_password',$response,$details,'Change Password',[]);
                    
                    $this->session->set_flashdata('success', 'Password Sent to registerd Email Address!');
                    redirect('user/login');
                } else {
                    $this->session->set_flashdata('error', 'No Details found for this email address!'); 
                }

                $postData =$this->input->post();
                 
                    
                

            } else {
               // echo validation_errors(); exit;
            }

        }


        
        $this->load->view('forgot_password'); 
      }

      /* 
      * REPORTS FUNCTION
      */


      function user_report($user_id){
        $this->load->model(["Common_m"]);
        if($this->session->userdata('role_id') !== "1" ){
            return redirect("/");
        }
        $this->load->library('pagination');
        $this->load->helper('form');
        $params = array(); //
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-01");
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d",strtotime("last day of this month"));
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        //echo $status." : status";
        $params['start_date'] = $start_date;
        $params['end_date'] = $end_date;
        $params['status'] = $status;
        $this->db->select("Distinct(module)");
        $all_modules = $this->db->get("employee_report")->result_array();
        $params['all_modules'] = $all_modules;
        $params['user_id'] = $user_id;
        //pr($all_modules);
        $limit_per_page = 10;        
        $where = ['user_id' => $user_id, 'DATE(employee_report.created_at) >' => $start_date, 'DATE(employee_report.created_at) <=' => $end_date];
        if(!empty($status)){
            $where['module'] =$status;  
        }
        if(isset($_GET['submit_type']) && $_GET['submit_type'] == 'xls') {
           // pr($_POST); exit;
           $this->db->select('employee_report.*,users.name,users.email,leads.name as lead_name,leads.email as lead_email,leads.city as lead_city,leads.pincode as lead_pincode,leads.phone lead_phone');
            $this->db->join("users","users.id=employee_report.user_id");
            $this->db->join("leads","leads.id=employee_report.lead_id","left");
            $all_records = $this->Common_m->get('employee_report', $where, $offset = 0, $is_single = false, $is_total = false,"created_at","Desc");
            $this->export_csv($all_records);

        }
        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $total_records = $this->Common_m->get('employee_report', $where, $offset = 0, $is_single = false, $is_total = true);
        $params["result"] =[];
        if ($total_records > 0) 
        {
            // get current page records
            $this->db->select('employee_report.*,users.name,users.email');
            $this->db->join("users","users.id=employee_report.user_id");
            $params["result"] = $this->Common_m->get('employee_report', $where, $offset = $start_index, $is_single = false, $is_total = false,"created_at","Desc");
            $config['base_url'] = base_url() . '/user/user_report/'.$user_id;
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 4;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = false;
            $config['last_link'] = false;
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['suffix'] = '?start_date='.$start_date."&end_date=".$end_date."&status=".$status;
            $this->pagination->initialize($config);
            // build paging links
            $params["links"] = $this->pagination->create_links();
        }

    $params['middle'] = 'users/user_report';
    $params['title'] = 'Activity Log';
    $params['b_links'] = [
            ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
            ['href' => '#', 'fa_icon' => 'fa-user', 'link_text' => 'Sub Admins List']

    ];
    $this->load->view('template',$params);
      }

      function export_csv($data){
        $this->load->library("excel");
        $objPHPExcel = $this->excel;
        $sheet = $objPHPExcel->getActiveSheet();
        //$sheet->setCellValue('A1', 'Hello World !'); 
        //$objPHPExcel->getActiveSheet()->SetCellValue('A1','Hello World !' ); 
        $rowCount = 1;
        $endColumn='M';
        $sheet = cellColor("A$rowCount:{$endColumn}$rowCount",THEME_COLOR,$sheet);  
        $sheet =  setcellborder("A$rowCount:{$endColumn}$rowCount",'00000',$sheet);
        $sheet->getRowDimension($rowCount)->setRowHeight(25);
        $sheet->getStyle("A$rowCount:{$endColumn}$rowCount")->getFont()->setBold( true );
        $sheet->getStyle("A$rowCount:{$endColumn}$rowCount")->getFont()->setSize(10);
        $sheet->getStyle("A$rowCount:{$endColumn}$rowCount")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        //$sheet->getStyle("A$rowCount:{$endColumn}$rowCount")->getFont()->setColor('#877a44');
        // SETTING TITLE , Source, Date, Call From, Call To,Location, Call Status, Seconds,Tags,Recording Url,Notes,Value
        $sheet->SetCellValue('A'.$rowCount, 'Sub Admin Name');
        $sheet->SetCellValue('B'.$rowCount, 'Sub Admin Email');
        $sheet->SetCellValue('C'.$rowCount, 'Module');
        $sheet->SetCellValue('D'.$rowCount, 'Message');
        $sheet->SetCellValue('E'.$rowCount, 'Activity Time');  
        $sheet->SetCellValue('F'.$rowCount, 'Lead Name');
        $sheet->SetCellValue('G'.$rowCount, 'Lead Email');
        $sheet->SetCellValue('H'.$rowCount, 'Lead Phone');
        $sheet->SetCellValue('I'.$rowCount, 'Lead City');
        $sheet->SetCellValue('J'.$rowCount, 'Amount');
        $sheet->SetCellValue('K'.$rowCount, 'Data Changed');
        // SETTING TITLE
        $title = "Lead Report";
        $sheet->setTitle($title);
        // SETTING COLUMNS WIDTH
        $sheet->getColumnDimension('A')->setWidth(15); 
        $sheet->getColumnDimension('B')->setWidth(20); 
        $sheet->getColumnDimension('C')->setWidth(15); 
        $sheet->getColumnDimension('D')->setWidth(20); 
        $sheet->getColumnDimension('E')->setWidth(15); 
        $sheet->getColumnDimension('F')->setWidth(20); 
        $sheet->getColumnDimension('G')->setWidth(10); 
        $sheet->getColumnDimension('H')->setWidth(25); 
        $sheet->getColumnDimension('I')->setWidth(20); 
        $sheet->getColumnDimension('J')->setWidth(20); 
        $sheet->getColumnDimension('K')->setWidth(50); 
        $sheet->getColumnDimension('L')->setWidth(20); 
        // SETTING COLUMNS WIDTH
        // ADDING RECORDS TO SHEET
        foreach($data  as $lead) {
        //	pr($call,1);
                $rowCount++;
                // FORMATTING NUMBER
              


                $sheet->SetCellValue('A'.$rowCount, $lead['name']);
                //$sheet->SetCellValue('B'.$rowCount, 'Widget');
                $sheet->getRowDimension($rowCount)->setRowHeight(50);
                $sheet->getStyle("A$rowCount:{$endColumn}$rowCount")->getFont()->setSize(9);			
                $sheet->SetCellValue('B'.$rowCount,$lead['email'] );
                $sheet->SetCellValue('C'.$rowCount, $lead['module']);
                $sheet->SetCellValue('D'.$rowCount, $lead['report_message']);
                $sheet->SetCellValue('E'.$rowCount, date_create($lead['created_at'])->format("Y-M-d g:i A"));
                $sheet->SetCellValue('F'.$rowCount, $lead['lead_name']);
                $sheet->SetCellValue('G'.$rowCount, $lead['lead_email']);
                $sheet->SetCellValue('H'.$rowCount, $lead['lead_phone']);
                $sheet->SetCellValue('I'.$rowCount, $lead['lead_city']);
                $sheet->SetCellValue('J'.$rowCount, $lead['amount']);
                $json_data = json_decode($lead['json_data'],true);
                $chandedData ="";                     
                if(!empty($json_data)){
                    foreach($json_data as $key =>  $jdata) {
                        if($key == 'user_id'){
                            continue;
                        }
                        if(!empty($jdata)){
                            $chandedData.="{$key}:{$jdata}\n";
                        }
                       
                    }
                
                
            }
            $sheet->SetCellValue('K'.$rowCount, $chandedData);
                 
        }
        // ADDING RECORDS TO SHEET
        // CREATING ANOTHER SHEET FOR THE SMS
        
                
                // CREATING ANOTHER SHEET FOR THE SMS ENDED
                $objPHPExcel->setActiveSheetIndex(0); // SETTING FIRST SHEET AS ACTIVE BY DEFAULT
    
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $filename_only = "lead-report-".date('m-d-Y').".xls";
                $filename = APPPATH."tmp-reports/".$filename_only;
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename={$filename_only}");
            header("Pragma: no-cache");
            header("Expires: 0");
            $objWriter->save('php://output');
            exit;
    
    
         }

      

}
