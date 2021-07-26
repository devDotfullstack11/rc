<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends My_Controller {

        //$this->load->model(array('data'));
        public $rowCount = 1;
        function __construct() {
            // header('Access-Control-Allow-Origin: *');
            // header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            parent::__construct();
            $this->load->model(array('Data_model','Income_model'));
            $this->load->model(array('Expense_model'));
            $this->load->model(array('User_model'));
            $this->load->model(array('Event_model'));
            $this->load->model(array('Group_model'));                       
            $this->load->helper('url');
            $this->load->library('common');

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
                echo 'Hello World!';        
                //$this->load->view('welcome_message');
        }

        public function data(){
                $data = $this->Data_model->get_all();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($data));
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
            
            //$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[5]|max_length[15]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[15]');

            $this->form_validation->set_rules('equipment', 'Equipment', 'required|min_length[5]|max_length[50]');
            $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]|max_length[200]');
            //Validating Email Field
            $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            //Validating Mobile no. Field
            $this->form_validation->set_rules('phone', 'Mobile No.', 'required|is_unique[users.phone]|integer',
                array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
         ));

            //Validating Address Field            
            if ($this->form_validation->run() == FALSE) {
                $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                $p =  password_hash($this->input->post('password'), PASSWORD_DEFAULT); 
                $ref = md5(rand(999,9999)).'-'.date('YmdHis');
                $date = new DateTime();
                $date->modify("+30 day");
                $validity =  $date->format("Y-m-d H:i:s");
                $code = $this->input->post('code');

                $data = array(
                'name' => $this->input->post('name'),
                'last_name' => 'None',
                'email' => $this->input->post('email'),
                'equipment' => $this->input->post('equipment'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
               // 'gender' => $this->input->post('gender'),
                'password' => $p,
                'role_id' => 2,
                'api_key' => $ref,
                'validity' => $validity,
                'code'=>$code
                );
                
                
                //Transfering data to Model
                $this->User_model->insert_entry($data);
                unset($data['password']);
                unset($data['last_name']);
                unset($data['gender']);
                $result = ['status' => '1','message' => 'Registered Successfully!','key' => $ref,
                            'profile' => $data
                ];
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
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
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('email', 'Email Or Phone Number', 'required');
            if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                 $data = array(
                'email' => $this->input->post('email'),
                'password' =>$this->input->post('password'),
                );
               $result = $this->User_model->login($data);
                
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
        }

          /*
        |--------------------------------------------------------------------------
        | Function : Verify Account
        |--------------------------------------------------------------------------
        | This will be used to Verify Account
        */


        public function verify(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('api_key', 'Somthing went wrong', 'required');
            $this->form_validation->set_rules('otp', 'Otp', 'required');
            if ($this->form_validation->run() == FALSE) {
                    $msg = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                 $data = array(
                        'code' => $this->input->post('otp'),
                        'api_key' =>$this->input->post('api_key'),
                );
               $this->db->where($data);
               $details = $this->db->get('users');
               $result = $details->first_row('array');
               if(empty($result)){
                 $msg = ['status' => '0','reason' => 'verification' , 'errors' => 'Invalid Code'];
               }else{
                $this->db->update('users', ['status'=>1,'code'=>0], array('api_key' => $data['api_key']));
                $msg = ['status' => 'success','reason' => 'verification'];
               }
                
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($msg));
        }

          /*
        |--------------------------------------------------------------------------
        | Function : If Not Verify Account
        |--------------------------------------------------------------------------
        | This will be used to Verify Account
        */
        public function update_code(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'Somthing went wrong', 'required');
            $this->form_validation->set_rules('api_key', 'Somthing went wrong', 'required');
            if ($this->form_validation->run() == FALSE) {
                    $msg = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
            }  else {
                //Setting values for tabel columns
                 $data = array(
                        'code' => $this->input->post('code'),
                );
                $this->db->update('users', $data, array('api_key' => $this->input->post('api_key')));
                $msg = ['status' => '1','reason' => 'validation'];
                
           }

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($msg));
        }
          /*
        |--------------------------------------------------------------------------
        | Function : addexpense
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */


        public function addexpense(){
             $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $user_id =  $checklogin['userdata']['id'];

                    $this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
                    //$this->form_validation->set_rules('salary', 'Salary', 'required|integer');
//                    $this->form_validation->set_rules('green_fodder', 'Green_fodder', 'required|integer');
//                    $this->form_validation->set_rules('dry_fodder', 'Dry fodder', 'required|integer');
  //                  $this->form_validation->set_rules('concentrate', 'Concentrate', 'required|integer');
//                    $this->form_validation->set_rules('electricity', 'Electricity', 'required|integer');
  //                  $this->form_validation->set_rules('medicine', 'Medicine', 'required|integer');
    //                $this->form_validation->set_rules('atrificial_insemination', 'Atrificial Insemination', 'required|integer');
                    //$this->form_validation->set_rules('others', 'Others', 'required|integer');
      //              $this->form_validation->set_rules('machines_maintenance', 'Machines Maintenance', 'required|integer');
        //            $this->form_validation->set_rules('diesel', 'Diesel', 'required|integer');
          //          $this->form_validation->set_rules('cattle_purchase', 'Cattle Purchase Amount', 'integer');
            //        $this->form_validation->set_rules('farm_milk_consumption', 'Milk Consumption In Farm', 'required|integer');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
                        $date = date_create($this->input->post('expense_date'))->format('Y-m-d');                        
						$salary = !empty($this->input->post('salary')) ? $this->input->post('salary') : 0;
						$green_fodder = !empty($this->input->post('green_fodder')) ? $this->input->post('green_fodder') : 0;
						$dry_fodder = !empty($this->input->post('dry_fodder')) ? $this->input->post('dry_fodder') : 0;
						$concentrate = !empty($this->input->post('concentrate')) ? $this->input->post('concentrate') : 0;
						$medicine = !empty($this->input->post('medicine')) ? $this->input->post('medicine') : 0;
						$atrificial_insemination = !empty($this->input->post('atrificial_insemination')) ? $this->input->post('atrificial_insemination') : 0;
						$machines_maintenance = !empty($this->input->post('machines_maintenance')) ? $this->input->post('machines_maintenance') : 0;
						$diesel = !empty($this->input->post('diesel')) ? $this->input->post('diesel') : 0;
						$farm_milk_consumption = !empty($this->input->post('farm_milk_consumption')) ? $this->input->post('farm_milk_consumption') : 0;
						$cattle_purchase = !empty($this->input->post('cattle_purchase')) ? $this->input->post('cattle_purchase') : 0;
						$others = !empty($this->input->post('others')) ? $this->input->post('others') : 0;
						$electricity = !empty($this->input->post('electricity')) ? $this->input->post('electricity') : 0;
                        $total = array_sum(array(
                                $salary,$green_fodder,
                                $dry_fodder,$concentrate,
                                $medicine,$atrificial_insemination,
                                $machines_maintenance,$diesel,
                                $farm_milk_consumption,
                                $cattle_purchase,
								$electricity,
								$others
                            ));
                        $data = array(
                            'user_id' => $user_id,
                            'salary' => $salary,
                            'green_fodder' => $green_fodder,
                            'dry_fodder' => $dry_fodder,
                            'concentrate' => $concentrate,
                            'electricity' => $electricity,
                            'medicine' => $medicine,
                            'atrificial_insemination' => $atrificial_insemination,
                            'others' => (!empty($this->input->post('others')) && is_numeric($this->input->post('others')) ) ? $this->input->post('others') : 0,
                            'expense_date' => $date,
                            'total' => $total,
                            'machines_maintenance' => $machines_maintenance,
                            'diesel' => $diesel,
                            'farm_milk_consumption' => $farm_milk_consumption,
                            'cattle_purchase' => $cattle_purchase,
                            'purchased_cattle_tag_id' => !empty($this->input->post('purchased_cattle_tag_id')) ? $this->input->post('purchased_cattle_tag_id') : ''
                        );
                        ////print_r($data); die;
                        $today_expense = $this->Expense_model->get_expense_by_date($date,$checklogin['userdata']);
                        if(count($today_expense) > 0){
                             $data['salary']+=$today_expense['salary'];   
                             $data['green_fodder']+=$today_expense['green_fodder'];   
                             $data['dry_fodder']+=$today_expense['dry_fodder'];   
                             $data['concentrate']+=$today_expense['concentrate'];   
                             $data['electricity']+=$today_expense['electricity'];   
                             $data['medicine']+=$today_expense['medicine'];   
                             $data['atrificial_insemination']+=$today_expense['atrificial_insemination'];   
                             $data['others']+=$today_expense['others'];   
                             $data['others']+=$today_expense['others'];   
                             $data['diesel']+=$today_expense['diesel'];   
                             $data['machines_maintenance']+=$today_expense['machines_maintenance'];   
                             $data['farm_milk_consumption']+=$today_expense['farm_milk_consumption'];   
                             $data['cattle_purchase']+=$today_expense['cattle_purchase'];   
                             $old_arr = (!empty($today_expense['purchased_cattle_tag_id'])) ? explode(',', $today_expense['purchased_cattle_tag_id']) : [] ;
                             $new_arr = (!empty($data['purchased_cattle_tag_id'])) ? explode(',', $data['purchased_cattle_tag_id']) : [] ;
                             $final_arr = array_merge($old_arr,$new_arr);                             
                             $data['purchased_cattle_tag_id']=(count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                             $this->Expense_model->update_entry($today_expense['id'],$data);
                        } else {
                            $this->Expense_model->insert_entry($data);    
                        }
                        
                        $result = ['status' => '1','message' => 'Expense Added Successfully!'];
                }
             }
            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }

        /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 

        public function get_expenses(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                          $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 10;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $total_records = $this->Expense_model->get_total($checklogin['userdata']);
                         
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    $params["result"] = $this->Expense_model->get_all($checklogin['userdata']);
                                    $config['base_url'] = base_url() . 'index.php/product/list';
                                    $config['total_rows'] = $total_records;
                                    $config['per_page'] = $limit_per_page;
                                    $this->pagination->initialize($config);
                                    // build paging links
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => []];
                                }
                    }

             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));    
            
        }

         /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 


        public function get_one_expense(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense = $this->Expense_model->get_one($this->input->post('id'));
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => $expense];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    
            
        }


         /*
        |--------------------------------------------------------------------------
        | Function : update Expense
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 

         public function update_expense(){
             $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             ////print_r($checklogin); die;
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    
                    $user_id =  $checklogin['userdata']['id'];
                    $this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
                    $this->form_validation->set_rules('salary', 'Salary', 'required|integer');
                    $this->form_validation->set_rules('green_fodder', 'Green_fodder', 'required|integer');
                    $this->form_validation->set_rules('dry_fodder', 'Dry fodder', 'required|integer');
                    $this->form_validation->set_rules('concentrate', 'Concentrate', 'required|integer');
                    $this->form_validation->set_rules('electricity', 'Electricity', 'required|integer');
                    $this->form_validation->set_rules('medicine', 'Medicine', 'required|integer');
                    $this->form_validation->set_rules('atrificial_insemination', 'Atrificial Insemination', 'required|integer');
                    $this->form_validation->set_rules('others', 'Others', 'required|integer');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns                        
                        $date = date_create($this->input->post('expense_date'))->format('Y-m-d');                        
                        $total = array_sum(array(
                                $this->input->post('salary'),$this->input->post('green_fodder'),
                                $this->input->post('dry_fodder'),$this->input->post('concentrate'),
                                $this->input->post('medicine'),$this->input->post('atrificial_insemination'),
                                $this->input->post('machines_maintenance'),$this->input->post('diesel'),
                                $this->input->post('farm_milk_consumption'),$this->input->post('others'),
                                $this->input->post('farm_milk_consumption'),$this->input->post('others'),
                                !empty($this->input->post('cattle_purchase')) ? $this->input->post('cattle_purchase') : 0
                            ));
                        $data = array(
                            'user_id' => $user_id,
                            'salary' => $this->input->post('salary'),
                            'green_fodder' => $this->input->post('green_fodder'),
                            'dry_fodder' => $this->input->post('dry_fodder'),
                            'concentrate' => $this->input->post('concentrate'),
                            'electricity' => $this->input->post('electricity'),
                            'medicine' => $this->input->post('medicine'),
                            'atrificial_insemination' => $this->input->post('atrificial_insemination'),
                            'others' => (!empty($this->input->post('others')) && is_numeric($this->input->post('others')) ) ? $this->input->post('others') : 0,
                            'expense_date' => $date,
                            'total' => $total,
                            'machines_maintenance' => $this->input->post('machines_maintenance'),
                            'diesel' => $this->input->post('diesel'),
                            'farm_milk_consumption' => $this->input->post('farm_milk_consumption'),
                            'cattle_purchase' => !empty($this->input->post('cattle_purchase')) ? $this->input->post('cattle_purchase') : 0,
                            'purchased_cattle_tag_id' => !empty($this->input->post('purchased_cattle_tag_id')) ? $this->input->post('purchased_cattle_tag_id') : ''
                        );
                        //$this->Expense_model->update_entry($data);
                        $this->Expense_model->update_entry($this->input->post('id'),$data);
                        $result = ['status' => '1','message' => 'Expense Updated Successfully!'];
                }
             }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }





        /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 




          public function addincome(){
             $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  // //print_r( $checklogin['userdata']) ; die;
                   $user_id =  $checklogin['userdata']['id'];
                    //$this->form_validation->set_rules('user_id', 'User Name', 'required');
                    $this->form_validation->set_rules('income_date', 'Income Date', 'required');
                    //$this->form_validation->set_rules('milk_sale_rate', 'Milk Sale Rate', 'required|integer');
	//                    $this->form_validation->set_rules('manure_sale_rate', 'Manuare Sale Rate', 'required|integer');
//                    $this->form_validation->set_rules('milk_sale', 'Milk Sale', 'required|integer');
  //                  $this->form_validation->set_rules('manure_sale', 'Manuare Sale', 'required|integer');
//                    $this->form_validation->set_rules('others', 'others', 'required|integer');
  //                  $this->form_validation->set_rules('cattle_sale', 'Cattle Sale Amount', 'integer');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
						$a = !empty($this->input->post('milk_sale')) ? $this->input->post('milk_sale') : 0;
						$manure_sale_rate = (!empty($this->input->post('manure_sale_rate')) ? $this->input->post('manure_sale_rate') : 0);
						$milk_sale_rate = !empty($this->input->post('milk_sale_rate')) ? $this->input->post('milk_sale_rate') : 
						$manure_sale = !empty($this->input->post('manure_sale')) ? $this->input->post('manure_sale') : 0;
						$others = !empty( $this->input->post('others')) ?  $this->input->post('others') : 0;
						$cattle_sale =!empty($this->input->post('cattle_sale')) ? $this->input->post('cattle_sale') : 0;
                        $total = array_sum(array(
                                ($milk_sale_rate) * $a ,
                                ($manure_sale_rate) * $manure_sale,
								$others,
                                $cattle_sale,
                            ));

                        $date = date_create($this->input->post('income_date'))->format('Y-m-d');
                        $data = array(
                        'user_id' => $user_id,
                        'milk_sale_rate' => $milk_sale_rate,
                        'manure_sale_rate' =>  $manure_sale_rate,
                        'milk_sale' => $a,
                        'manure_sale' => $manure_sale,
                        'others' => $others,
                        'sold_cattle_ids' => !empty($this->input->post('sold_cattle_ids')) ? $this->input->post('sold_cattle_ids') : '',
                        'cattle_sale' => !empty($this->input->post('cattle_sale')) ? $this->input->post('cattle_sale') : 0,
                        'total' => $total,
                        'income_date' => $date,
                        );
                        $today_income = $this->Income_model->get_income_by_date($date,$checklogin['userdata']);
                         if(count($today_income) > 0){
                             $data['milk_sale_rate']+=$today_income['milk_sale_rate'];   
                             $data['manure_sale_rate']+=$today_income['manure_sale_rate'];   
                             $data['milk_sale']+=$today_income['milk_sale'];  
                             $data['manure_sale']+=$today_income['manure_sale'];   
                             $data['cattle_sale']+=$today_income['cattle_sale']; 
                             $data['others']+=$today_income['others'];   
                             $data['total']+=$today_income['total']; 
                             $old_arr = (!empty($today_income['sold_cattle_ids'])) ? explode(',', $today_income['sold_cattle_ids']) : [] ;
                             $new_arr = (!empty($data['sold_cattle_ids'])) ? explode(',', $data['sold_cattle_ids']) : [] ;
                             $final_arr = array_merge($old_arr,$new_arr);                             
                             $data['sold_cattle_ids']= (count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                             $this->Income_model->update_entry($today_income['id'],$data);
                        } else {
                            $this->Income_model->insert_entry($data);    
                        }
                       // $this->Income_model->insert_entry($data);
                        $result = ['status' => '1','message' => 'Income Added Successfully!'];
                }
             }
          

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }

        public function get_income(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                            $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 10;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $total_records = $this->Income_model->get_total($formdata, $checklogin['userdata']);
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    $params["result"] = $this->Income_model->get_all($checklogin['userdata']);
                                    $config['base_url'] = base_url() . 'index.php/product/list';
                                    $config['total_rows'] = $total_records;
                                    $config['per_page'] = $limit_per_page;
                                    $this->pagination->initialize($config);
                                    // build paging links
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => []];
                                }

                    }
             }

             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));    
            
        }


           /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 
        
        public function get_one_income(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense = $this->Income_model->get_one($this->input->post('id'));
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => $expense];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    
            
        }


         /*
        |--------------------------------------------------------------------------
        | Function : update Expense
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 

         public function update_income(){
             $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  // //print_r( $checklogin['userdata']) ; die;
                   $user_id =  $checklogin['userdata']['id'];
                    //$this->form_validation->set_rules('user_id', 'User Name', 'required');
                   $this->form_validation->set_rules('id', 'ID', 'required');
                    $this->form_validation->set_rules('income_date', 'Income Date', 'required');
                    $this->form_validation->set_rules('milk_sale_rate', 'Milk Sale Rate', 'required|integer');
                    $this->form_validation->set_rules('manure_sale_rate', 'Manuare Sale Rate', 'required|integer');
                    $this->form_validation->set_rules('milk_sale', 'Milk Sale', 'required|integer');
                    $this->form_validation->set_rules('manure_sale', 'Manuare Sale', 'required|integer');
                    $this->form_validation->set_rules('others', 'others', 'required|integer');
                    $this->form_validation->set_rules('cattle_sale', 'Cattle Sale Amount', 'integer');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
                        $total = array_sum(array(
                                $this->input->post('milk_sale_rate') * $this->input->post('milk_sale'),
                                $this->input->post('manure_sale_rate') * $this->input->post('manure_sale'),
                                $this->input->post('others'),
                                !empty($this->input->post('cattle_sale')) ? $this->input->post('cattle_sale') : 0,
                            ));

                        $date = date_create($this->input->post('income_date'))->format('Y-m-d');
                        $data = array(
                        'user_id' => $user_id,
                        'milk_sale_rate' => $this->input->post('milk_sale_rate'),
                        'manure_sale_rate' => $this->input->post('manure_sale_rate'),
                        'milk_sale' => $this->input->post('milk_sale'),
                        'manure_sale' => $this->input->post('manure_sale'),
                        'others' => $this->input->post('others'),
                        'total' => $total,
                        'income_date' => $date,
                        'sold_cattle_ids' => !empty($this->input->post('sold_cattle_ids')) ? $this->input->post('sold_cattle_ids') : '',
                        'cattle_sale' => !empty($this->input->post('cattle_sale')) ? $this->input->post('cattle_sale') : 0,
                        );
                        //Transfering data to Model
                        $this->Income_model->update_entry($data);
                        $result = ['status' => '1','message' => 'Updated Successfully!'];
                }
             }
          

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));  
        }

        public function add_request(){
             $this->load->library('form_validation');
            $this->load->model(array('Request_model'));
                $formdata = $this->input->post();
               // //print_r($formdata); die;
                    $this->form_validation->set_rules('name', 'Name', 'required');
                    $this->form_validation->set_rules('phone', 'Phone', 'required|integer');
                    $this->form_validation->set_rules('type', 'Type', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
                        $data = array(
                        'name' =>$this->input->post('name'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        'type' => $this->input->post('type'),
                        );
                        //Transfering data to Model
                        $this->Request_model->insert_entry($data);
                        $result = ['status' => '1','message' => 'Request Added Successfully!'];
                }

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    


        }


             /*
        |--------------------------------------------------------------------------
        | Function : add_cattle
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */


        public function add_cattle(){
             $this->load->library('form_validation');
             $this->load->model(array('Cattle_model','Income_model','Group_model'));
             $this->load->model(array('History_model'));
             $this->load->model(array('UpcomingEvent_model'));
             $this->load->model(array('BreedingProcess'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  ////print_r($checklogin); die;
                   $user_id =  $checklogin['userdata']['id'];
                    $this->form_validation->set_rules('tag_id', 'Tag ID', 'required|max_length[250]');
                    $this->form_validation->set_rules('breed', 'Breed', 'required|max_length[250]');
                    $this->form_validation->set_rules('type', 'Cattle Type', 'required|max_length[250]');
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        // FINDING RECORD FROM DB ON THE BASIS OF CURRENT TAG
                        
                        $tagidcheck = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('tag_id'),$checklogin['userdata']);
                        if(count($tagidcheck) > 0 ){
                          //WE NEED TO MAKE TAG ID UNIQUE USERWISE
                          //IF WE FIND THAT DB TAGID RECORD CATTLE OWNER == CURRENT USER
                          //WHICH MEANS CURRENT USER HAS CATTLE WITH THE SAME TAG ALREADY
                          // WE WILL GENERATE AN ERROR
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => ['tag_id' => 'You have already alloted that tag to one of your cattles'] ];
                        } else {                          
                            //Setting values for tabel columns
                        //$date = date_create($this->input->post('expense_date'))->format('Y-m-d');
                        $ai_date = !empty($this->input->post('ai_date')) ?  date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s') : '';
                        $is_sold = 'No';
                        $is_purchased = 'No';
                        $last_expense_insert_id = 0;
                        $final_tag_ids_for_expenses = [];
                        $sale_date = empty($this->input->post('sale_date')) ? '' : $this->input->post('sale_date');
                        if(!empty($this->input->post('sale_price')) && $this->input->post('sale_price') !== 0){
                            //IF SALE PRICE OF A CATTLE IS NOT EMPTY
                            //MEANS CATTLE IS SOLD TO SOME ONE.
                            //SOLD AND GOT INCOME.
                            //INSERTING DATA IN INCOME TABLE
                            $data = array(
                                'user_id' => $user_id,
                                'milk_sale_rate' => 0,
                                'manure_sale_rate' => 0,
                                'milk_sale' => 0,
                                'manure_sale' => 0,
                                'others' => 0,
                                'cattle_sale' => $this->input->post('sale_price'),
                                'total' => $this->input->post('sale_price'),
                                'income_date' => date('Y-m-d'),
                                'sold_cattle_ids' => $this->input->post('tag_id')
                            );
                             $is_sold = 'Yes';
                             $today_income = $this->Income_model->get_income_by_date(date('Y-m-d'),$checklogin['userdata']);
                             if(count($today_income) > 0){
                                 $data['milk_sale_rate']+=$today_income['milk_sale_rate'];   
                                 $data['manure_sale_rate']+=$today_income['manure_sale_rate'];   
                                 $data['milk_sale']+=$today_income['milk_sale'];  
                                 $data['manure_sale']+=$today_income['manure_sale'];   
                                 $data['others']+=$today_income['others'];   
                                 $data['cattle_sale']+=$today_income['cattle_sale']; 
                                 $data['total']+=$today_income['total']; 
                                 $old_arr = (!empty($today_income['sold_cattle_ids'])) ? explode(',', $today_income['sold_cattle_ids']) : [] ;
                                 $old_arr[] = $this->input->post('tag_id');
                                 //$new_arr = (!empty($data['sold_cattle_ids'])) ? explode(',', $data['sold_cattle_ids']) : [] ;
                                 $final_arr = $old_arr;                             
                                 $data['sold_cattle_ids']= (count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                                 $this->Income_model->update_entry($today_income['id'],$data);
                            } else {
                                $this->Income_model->insert_entry($data);    
                            }
                            //Transfering data to Model
                            //$this->Income_model->insert_entry($data);
                            $sale_date = date('Y-m-d');
                           
                       }else if(!empty($this->input->post('purchase_price')) && $this->input->post('purchase_price') !== 0){
                            //IF PURCHASE PRICE IS NOT EMPTY
                            // MEANS CATTLE IS PURCHASED FROM SOME ONE.
                            // PURCHASED AND EXPENSE WAS THERE
                            // ADDING DATA TO CURRENT USER'S EXPENSES
                             $data = array(
                                'user_id' => $user_id,
                                'salary' => 0,
                                'green_fodder' => 0,
                                'dry_fodder' => 0,
                                'concentrate' => 0,
                                'electricity' => 0,
                                'medicine' => 0,
                                'atrificial_insemination' => 0,
                                'others' => 0,
                                'cattle_purchase' => $this->input->post('purchase_price'),
                                'expense_date' => date('Y-m-d'),
                                'total' => $this->input->post('purchase_price'),
                                'machines_maintenance' => 0,
                                'purchased_cattle_tag_id' => $this->input->post('tag_id'),
                                'diesel' => 0,
                                'farm_milk_consumption' => 0,
                            );
                             $today_expense = $this->Expense_model->get_expense_by_date(date('Y-m-d'),$checklogin['userdata']);
                            if(count($today_expense) > 0){
                                 $data['salary']+=$today_expense['salary'];   
                                 $data['green_fodder']+=$today_expense['green_fodder'];   
                                 $data['dry_fodder']+=$today_expense['dry_fodder'];   
                                 $data['concentrate']+=$today_expense['concentrate'];   
                                 $data['electricity']+=$today_expense['electricity'];   
                                 $data['medicine']+=$today_expense['medicine'];   
                                 $data['atrificial_insemination']+=$today_expense['atrificial_insemination'];   
                                 $data['others']+=$today_expense['others'];   
                                 $data['total']+=$today_expense['total'];   
                                 $data['machines_maintenance']+=$today_expense['machines_maintenance'];   
                                 $data['farm_milk_consumption']+=$today_expense['farm_milk_consumption'];   
                                 $data['cattle_purchase']+=$today_expense['cattle_purchase'];   
                                 $old_arr = (!empty($today_expense['purchased_cattle_tag_id'])) ? explode(',', $today_expense['purchased_cattle_tag_id']) : [] ;
                                 $old_arr[] = $this->input->post('tag_id');
                                 //$new_arr = (!empty($data['tag_id'])) ? explode(',', $data['tag_id']) : [] ;
                                 $final_arr = $old_arr;
                                 //$final_tag_ids_for_expenses = $final_arr;                          
                                 $data['purchased_cattle_tag_id']=(count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                                 $this->Expense_model->update_entry($today_expense['id'],$data);
                                 $last_expense_insert_id = $today_expense['id'];
                            } else {
                                $last_expense_insert_id = $this->Expense_model->insert_entry($data);                                    
                            }

                           // $this->Expense_model->insert_entry($data);
                            $is_purchased = 'Yes';
                       }
                       $parent_id = 0;
                       if(!empty($this->input->post('cattle_group'))){
                            //$groupdata = $this->Group_model->get_by_title($this->input->post('cattle_group'),);
                            $groupdata = $this->Group_model->get_by_title_and_user($this->input->post('cattle_group'),$checklogin['userdata']);
                            if(count($groupdata) > 0){
                               $parent_id = $groupdata['group_id'];
                            } else {
                                $thedata = array(
                                    'user_id' => $user_id,
                                    'group_title' => $this->input->post('cattle_group'),
                                    'group_slug' => $this->generateslug($this->input->post('cattle_group'),0),
                                    'parent_id' => 0  ,
                                    'sort_order' => rand(20,100),
                                    'description' => $this->input->post('cattle_group'),
                                    'status' => 1,
                                    'cattle_id' => '',
                                    );
                                    //Transfering data to Model
                                $parent_id = $this->Group_model->insert_entry($thedata);  
                                $groupdata = $this->Group_model->get_by_id($parent_id);
                            }


                       }
                       if(!empty($sale_date)){
                           $is_sold = 'Yes'; 
                        }
                        $data = array(
                            'tag_id' => $this->input->post('tag_id'),
                            'dam_id' => $this->input->post('dam_id'),
                            'dob' => $this->input->post('dob'),
                            'dop' => $this->input->post('dop'),
                            'purchase_price' => $this->input->post('purchase_price'),
                            'weight' => $this->input->post('weight'),
                            'ai_date' => $ai_date,
                            'is_pregnant' => $this->input->post('is_pregnant'),
                            'calving_date' => empty($this->input->post('calving_date')) ? '' : $this->input->post('calving_date'),
                            'sale_date' => $sale_date,
                            'death_date' => empty($this->input->post('death_date')) ? '' : $this->input->post('death_date'),
                            'is_dead' => empty($this->input->post('death_date')) ? 'No' : 'Yes',
                            'sale_price' => empty($this->input->post('sale_price')) ? 0 : $this->input->post('sale_price'),
                            'owner_id ' => $user_id,
                            'sire_id' => $this->input->post('sire_id'),
                            'lactation' => empty($this->input->post('lactation')) ? 1 : $this->input->post('lactation'),
                            'per_day_milk' => empty($this->input->post('per_day_milk')) ? 0 : $this->input->post('per_day_milk'),
                            'insurance_id' => empty($this->input->post('insurance_id')) ? '' : $this->input->post('insurance_id'),
                            'type' => $this->input->post('type'),
                            'breed' => $this->input->post('breed'),
                            'is_sold' => $is_sold,
                            'is_purchased' => $is_purchased,
                            'cattle_group' => $this->input->post('cattle_group'),
                            'parent_group' => $parent_id,
                            'sub_group' => 0,
                        );
                       $insert_id =  $this->Cattle_model->insert_entry($data);
                       if($parent_id !== 0){
                          $cattle_ids_arr = (count($groupdata) > 0) ?  explode(',', $groupdata['cattle_id']) : [];   
                          $cattle_ids_arr[] = $insert_id;                 
                           $cattle_ids_string = (count($cattle_ids_arr) > 0) ?  implode(',',array_unique( $cattle_ids_arr) ) :'';                    
                            $thedata = array(                                    
                                        'cattle_id' => $cattle_ids_string,
                                        );
                            //print_r($thedata); die;
                            $this->Group_model->update_entry($parent_id, $thedata);       
                       }                      
                       

                        if(!empty($ai_date) && $this->input->post('type') !== 'Calf'){
                             // AI PROCESS WILL TAKE EFFECT ONLY IF NEWLY ADDED RECORD CATTLE IS NOT CALF                              
                             $default = $this->BreedingProcess->get_one(1);

                                $event_date1 =new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                                $event_date1->modify("-1 day");
                                $event_message1 = 'Heat is going to take place on '.$event_date1->format('Y-m-d');
                                $alertdata = [
                                  'cattle_id' =>  $insert_id,
                                  'event_type' => 'heat',
                                  'message' => $event_message1,
                                  'event_date' => $event_date1->format('Y-m-d')
                                ];
                                $this->UpcomingEvent_model->delete_by_type($insert_id,'heat');
                                $this->UpcomingEvent_model->insert_entry($alertdata);

                              $data1 = $default;
                              $data1['ai_on'] = 0;
                              $data1['ai_date'] = date_create($ai_date)->format('Y-m-d H:i:s');
                              $data1['cattle_id'] = $insert_id;
                              $data1['current_state'] = 'ai';
                              $event_date = new DateTime($data['ai_date']);
                              $event_type = 'ai';
                              $event_message = 'Artificial Insemination is going to take place on '.$event_date->format('Y-m-d'); 
                              $this->UpcomingEvent_model->delete_by_type($insert_id,'ai');  
                              $alertdata = [
                                'cattle_id' => $insert_id,
                                'event_type' => $event_type,
                                'message' => $event_message,
                                'event_date' => $event_date->format('Y-m-d 00:00:00')
                               ];
                                ////print_r($event_date); die;
                              $this->UpcomingEvent_model->insert_entry($alertdata); 
                              if(!empty($this->input->post('first_pd_type'))){
                                $first_pd_type = $this->input->post('first_pd_type');
                              } else {
                                  $first_pd_type = 'UltraSound';
                              }

                              if(empty($this->input->post('first_pd_on'))){
                                  $first_pd_on = $default['first_pd_on'];
                                  if($first_pd_type == 'UltraSound'){
                                    $first_pd_on = $default['first_pd_on'];
                                  }                                
                              } else {
                                  $first_pd_on = $this->input->post('first_pd_on');
                              }

                              $event_date = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                              ////print_r($event_date); die;
                              $event_date->modify("+".$first_pd_on." day");
                              $event_type = 'first_pd';
                              $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');

                              ////print_r($event_message); die;

                              $data1['first_pd_type'] = $first_pd_type;
                              $data1['first_pd_on'] = $first_pd_on;
                              $data1['first_pd_date'] = $event_date->format('Y-m-d 00:00:00');
                            

                            $result =['status' => '1','result' => $data ];   

                            $alertdata = [
                                  'cattle_id' => $insert_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                              ];

                            $this->UpcomingEvent_model->insert_entry($alertdata);    
                            unset($data1['id']);

                            $this->BreedingProcess->insert_entry($data1);
                            $default['ai_date'] = $ai_date;
                            $default['cattle_id'] = $insert_id;
                            $this->update_event($default,$event_type, $event_date, $insert_id,[],'No');                             
                         } else if($this->input->post('type') == 'Calf'){
                           //IF NEW RECORD IS A CALF
                           //CREATING AN EVENT THAT CALF WILL BE ON HEAT ON 15 MONTHS FROM BIRTH OF CALF FOR HEAT
                          $event_date = new DateTime(date_create($this->input->post('dob'))->format('Y-m-d H:i:s'));
                          $event_date->modify("+14 months");
                          $event_message = 'Heat is going to take place on '.$event_date->format('Y-m-d');
                             $alertdata = [
                                  'cattle_id' => $insert_id,
                                  'event_type' => 'heat',
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                              ];
                            $this->UpcomingEvent_model->insert_entry($alertdata);   
                         }

                          $result = ['status' => '1','message' => 'Cattle Data Added Successfully!','cattle_id' => $insert_id];
                        }
                }
             }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }



               /*
        |--------------------------------------------------------------------------
        | Function : update_cattle
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */


        public function update_cattle(){
             $this->load->library('form_validation');
             $this->load->model(array('Cattle_model'));
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('UpcomingEvent_model'));             
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  ////print_r($checklogin); die;
                   $user_id =  $checklogin['userdata']['id'];
                   $cattle = $this->Cattle_model->get_one($this->input->post('id'));

                   if($cattle['tag_id'] !== $this->input->post('tag_id')){
                    // IF TAG ID MATCHES OTHER ID THAN THE CURRENT RECORD IMPLEMENT UNIQUE
                    $this->form_validation->set_rules('tag_id', 'Tag ID', 'required|max_length[250]');
                   } else {
                     $this->form_validation->set_rules('tag_id', 'Tag ID', 'required|max_length[250]');
                   }                   
                    $this->form_validation->set_rules('id', 'ID', 'required|integer');
                    //$this->form_validation->set_rules('sire_id', 'Sire ID', 'required|max_length[250]');
                    $this->form_validation->set_rules('breed', 'Breed', 'required|max_length[250]');
                    $this->form_validation->set_rules('type', 'Cattle Type', 'required|max_length[250]');
                    //$this->form_validation->set_rules('is_pregnant','Is Cow Pregnant', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                         //Setting values for tabel columns
                        $tagidcheck = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('tag_id'),$checklogin['userdata']);
                        ////print_r($tagidcheck); die;
                        if(count($tagidcheck) > 0 && $tagidcheck['id'] !== $this->input->post('id') ){
                          //WE NEED TO MAKE TAG ID UNIQUE USERWISE
                          //IF WE FIND THAT DB TAGID RECORD CATTLE OWNER == CURRENT USER
                          //WHICH MEANS CURRENT USER HAS CATTLE WITH THE SAME TAG ALREADY
                          // WE WILL GENERATE AN ERROR
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => ['tag_id' => 'You have already alloted that tag to one of your cattles'] ];
                        } else {
                            
                            $thecattlerecord = $this->Cattle_model->get_one($this->input->post('id'));
                            $is_sold = $thecattlerecord['is_sold'];
                            $is_purchased = $thecattlerecord['is_purchased'];
                            $sale_date = empty($this->input->post('sale_date')) ? '' : $this->input->post('sale_date');                            
                            $is_dead = ($thecattlerecord['is_dead'] == 'Yes') ? $thecattlerecord['is_dead'] : (empty($this->input->post('death_date')) ? 'No' : 'Yes') ;
                            //'is_dead' => empty($this->input->post('death_date')) ? 'No' : 'Yes',
                            if(!empty($this->input->post('sale_price')) && $this->input->post('sale_price') !== 0 && $thecattlerecord['is_sold'] == 'No'){
                                //IF SALE PRICE OF A CATTLE IS NOT EMPTY
                                //MEANS CATTLE IS SOLD TO SOME ONE.
                                //SOLD AND GOT INCOME.
                                //INSERTING DATA IN INCOME TABLE
                                $data = array(
                                    'user_id' => $user_id,
                                    'milk_sale_rate' => 0,
                                    'manure_sale_rate' => 0,
                                    'milk_sale' => 0,
                                    'manure_sale' => 0,
                                    'others' => 0,
                                    'cattle_sale' => $this->input->post('sale_price'),
                                    'total' => $this->input->post('sale_price'),
                                    'income_date' => date('Y-m-d'),
                                    'sold_cattle_ids' => $this->input->post('tag_id'),
                                );
                                 $is_sold = 'Yes';
                                //Transfering data to Model
                                 $today_income = $this->Income_model->get_income_by_date(date('Y-m-d'),$checklogin['userdata']);
                                 if(count($today_income) > 0){
                                     $data['milk_sale_rate']+=$today_income['milk_sale_rate'];   
                                     $data['manure_sale_rate']+=$today_income['manure_sale_rate'];   
                                     $data['milk_sale']+=$today_income['milk_sale'];  
                                     $data['manure_sale']+=$today_income['manure_sale'];   
                                     $data['others']+=$today_income['others'];   
                                     $data['cattle_sale']+=$today_income['cattle_sale']; 
                                     $data['total']+=$today_income['total']; 
                                     $old_arr = (!empty($today_income['sold_cattle_ids'])) ? explode(',', $today_income['sold_cattle_ids']) : [] ;
                                     $old_arr[] = $this->input->post('tag_id');
                                     //$new_arr = (!empty($data['sold_cattle_ids'])) ? explode(',', $data['sold_cattle_ids']) : [] ;
                                     $final_arr = $old_arr;                             
                                     $data['sold_cattle_ids']= (count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                                     $this->Income_model->update_entry($today_income['id'],$data);
                                } else {
                                    $this->Income_model->insert_entry($data);    
                                }
                                //$this->Income_model->insert_entry($data);
                               $sale_date = date('Y-m-d');
                           }else if(!empty($this->input->post('purchase_price')) && $this->input->post('purchase_price') !== 0 && $thecattlerecord['is_purchased'] == 'No'){
                                //IF PURCHASE PRICE IS NOT EMPTY
                                // MEANS CATTLE IS PURCHASED FROM SOME ONE.
                                // PURCHASED AND EXPENSE WAS THERE
                                // ADDING DATA TO CURRENT USER'S EXPENSES
                                  $data = array(
                                    'user_id' => $user_id,
                                    'salary' => 0,
                                    'green_fodder' => 0,
                                    'dry_fodder' => 0,
                                    'concentrate' => 0,
                                    'electricity' => 0,
                                    'medicine' => 0,
                                    'atrificial_insemination' => 0,
                                    'others' => 0,
                                    'cattle_purchase' => $this->input->post('purchase_price'),
                                    'expense_date' => date('Y-m-d'),
                                    'total' => $this->input->post('purchase_price'),
                                    'machines_maintenance' => 0,
                                    'purchased_cattle_tag_id' => $this->input->post('tag_id'),
                                    'diesel' => 0,
                                    'farm_milk_consumption' => 0,
                                );
                                 $today_expense = $this->Expense_model->get_expense_by_date(date('Y-m-d'),$checklogin['userdata']);
                                if(count($today_expense) > 0){
                                     $data['salary']+=$today_expense['salary'];   
                                     $data['green_fodder']+=$today_expense['green_fodder'];   
                                     $data['dry_fodder']+=$today_expense['dry_fodder'];   
                                     $data['concentrate']+=$today_expense['concentrate'];   
                                     $data['electricity']+=$today_expense['electricity'];   
                                     $data['medicine']+=$today_expense['medicine'];   
                                     $data['atrificial_insemination']+=$today_expense['atrificial_insemination'];   
                                     $data['others']+=$today_expense['others'];   
                                     $data['total']+=$today_expense['total'];   
                                     $data['machines_maintenance']+=$today_expense['machines_maintenance'];   
                                     $data['farm_milk_consumption']+=$today_expense['farm_milk_consumption'];   
                                     $data['cattle_purchase']+=$today_expense['cattle_purchase'];   
                                     $old_arr = (!empty($today_expense['purchased_cattle_tag_id'])) ? explode(',', $today_expense['purchased_cattle_tag_id']) : [] ;
                                     $old_arr[] = $this->input->post('tag_id');
                                     //$new_arr = (!empty($data['tag_id'])) ? explode(',', $data['tag_id']) : [] ;
                                     $final_arr = $old_arr;
                                     //$final_tag_ids_for_expenses = $final_arr;                          
                                     $data['purchased_cattle_tag_id']=(count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                                     $this->Expense_model->update_entry($today_expense['id'],$data);
                                     $last_expense_insert_id = $today_expense['id'];
                                } else {
                                    $last_expense_insert_id = $this->Expense_model->insert_entry($data);                                    
                                }
                                $is_purchased = 'Yes';
                           }
                           $parent_id = 0;
                           if(!empty($this->input->post('cattle_group'))){
                            //$groupdata = $this->Group_model->get_by_title($this->input->post('cattle_group'),);
                            $groupdata = $this->Group_model->get_by_title_and_user($this->input->post('cattle_group'),$checklogin['userdata']);
                            //print_r($groupdata); die;
                            if(count($groupdata) > 0){
                               $parent_id = $groupdata['group_id'];
                            } else {
                                $thedata = array(
                                    'user_id' => $user_id,
                                    'group_title' => $this->input->post('cattle_group'),
                                    'group_slug' => $this->generateslug($this->input->post('cattle_group'),0),
                                    'parent_id' => 0  ,
                                    'sort_order' => rand(20,100),
                                    'description' => $this->input->post('cattle_group'),
                                    'status' => 1,
                                    'cattle_id' => '',
                                    );
                                    //Transfering data to Model
                                $parent_id = $this->Group_model->insert_entry($thedata);  
                                $groupdata = $this->Group_model->get_by_id($parent_id);
                            }
                       }
                        if(!empty($sale_date)){
                           $is_sold = 'Yes'; 
                        }

                        $date = date_create($this->input->post('expense_date'))->format('Y-m-d');
                        $data = array(
                            'tag_id' => $this->input->post('tag_id'),
                            'dam_id' => $this->input->post('dam_id'),
                            'dob' => $this->input->post('dob'),
                            'dop' => $this->input->post('dop'),
                            'purchase_price' => $this->input->post('purchase_price'),
                            'weight' => $this->input->post('weight'),
                            'ai_date' => $this->input->post('ai_date'),
                            'is_pregnant' => $this->input->post('is_pregnant'),
                            'calving_date' => $this->input->post('calving_date'),
                            'sale_date' => $sale_date,
                            'death_date' => $this->input->post('death_date'),
                            'sale_price' => empty($this->input->post('sale_price')) ? 0 : $this->input->post('sale_price'),
                            'owner_id ' => $user_id,
                            'sire_id' => $this->input->post('sire_id'),
                            'lactation' => (empty($this->input->post('lactation')) || $this->input->post('type') == 'Calf')  ? 1 : $this->input->post('lactation'),
                            'per_day_milk' => (empty($this->input->post('per_day_milk')) || $this->input->post('type') == 'Calf') ? 0 : $this->input->post('per_day_milk'),
                            'type' => $this->input->post('type'),
                            'breed' => $this->input->post('breed'),
                            'is_sold' => $is_sold,
                            'is_dead' => $is_dead,
                            'is_purchased' => $is_purchased,
                            'cattle_group' => $this->input->post('cattle_group'),
                            'parent_group' => $parent_id,
                            'insurance_id' => empty($this->input->post('insurance_id')) ? '' : $this->input->post('insurance_id'),
                        );
                       // $this->Cattle_model->insert_entry($data);
                        
                        $ai_date = !empty($this->input->post('ai_date')) ?  date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s') : '';
                        $insert_id = $this->input->post('id');

                        if($parent_id !== 0){
                          $cattle_ids_arr = (count($groupdata) > 0) ?  explode(',', $groupdata['cattle_id']) : [];   
                          $cattle_ids_arr[] = $insert_id;                 
                           $cattle_ids_string = (count($cattle_ids_arr) > 0) ?  implode(',',array_unique( $cattle_ids_arr) ) :'';                    
                            $thedata = array(                                    
                                         'cattle_id' => $cattle_ids_string,
                                        );
                            $this->Group_model->update_entry($parent_id, $thedata);       
                       }


                        if(!empty($ai_date) && $this->input->post('type') !== 'Calf'){                              
                             $default = $this->BreedingProcess->get_one(1);
                              $exists = $this->BreedingProcess->get_one_by_cattle_id($this->input->post('id'));
                              if(count($exists) >0){                                    
                                    $data1 = $exists;
                                    $already = $exists; // Being Lazy
                                    if((date_create($formdata['ai_date'])->format('Y-m-d') !== date_create($already['ai_date'])->format('Y-m-d') ) || $cattle['type'] == 'Calf'){                                  
                                        //WE WILL MAKE CHANGES ONLY IF AI DATE IS DIFFERENT FROM PREVIOUSE ONE
                                        //HEAT EVENT GENERATION
                                        //echo $ai_date;
                                        $event_date = new DateTime($formdata['ai_date']);
                                        $event_date->modify("-1 day");
                                        $event_message1 = 'Heat is going to take place on '.$event_date->format('Y-m-d');
                                        $alertdata = [
                                          'cattle_id' => $insert_id,
                                          'event_type' => 'heat',
                                          'message' => $event_message1,
                                          'event_date' => $event_date->format('Y-m-d')
                                        ];
                                        $this->UpcomingEvent_model->delete_by_type($insert_id,'heat');
                                        $this->UpcomingEvent_model->insert_entry($alertdata);
                                        //END AI EVENT GENERATION
                                        $data1['ai_on'] = 0;
                                        $data1['ai_date'] = date_create($ai_date)->format('Y-m-d H:i:s');
                                        $data1['cattle_id'] = $insert_id;
                                        $data1['current_state'] = 'ai';
                                        //$event_date = new DateTime($data['ai_date']);
                                        $event_date->modify("+1 day");
                                        $event_type = 'ai';
                                        $event_message = 'Artificial Insemination is going to take place on '.$event_date->format('Y-m-d'); 
                                        $this->UpcomingEvent_model->delete_by_type($insert_id,'ai');  
                                        $alertdata = [
                                          'cattle_id' => $insert_id,
                                          'event_type' => $event_type,
                                          'message' => $event_message,
                                          'event_date' => $event_date->format('Y-m-d 00:00:00')
                                         ];
                                        $this->UpcomingEvent_model->insert_entry($alertdata); 

                                        if(!empty($this->input->post('first_pd_type'))){
                                          $first_pd_type = $this->input->post('first_pd_type');
                                        } else {
                                            $first_pd_type = 'UltraSound';
                                        }
                                        if(empty($this->input->post('first_pd_on'))){
                                            $first_pd_on = $default['first_pd_on'];
                                            if($first_pd_type == 'UltraSound'){
                                              $first_pd_on = $default['first_pd_on'];
                                            }                                
                                        } else {
                                            $first_pd_on = $this->input->post('first_pd_on');
                                        }
                                        $event_date = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                                        $event_date->modify("+".$first_pd_on." day");
                                        $event_type = 'first_pd';
                                        $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');
                                        $data1['first_pd_type'] = $first_pd_type;
                                        $data1['first_pd_on'] = $first_pd_on;
                                        $data1['first_pd_date'] = $event_date->format('Y-m-d 00:00:00');
                                        $result =['status' => '1','result' => $data ];
                                        $alertdata = [
                                              'cattle_id' => $insert_id,
                                              'event_type' => $event_type,
                                              'message' => $event_message,
                                              'event_date' => $event_date->format('Y-m-d')
                                           ];
                                         $this->UpcomingEvent_model->delete_by_type($insert_id,'first_pd');  
                                         $this->UpcomingEvent_model->insert_entry($alertdata); 

                                            // $event_date1 = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                                            // $event_date1->modify("-1 day");
                                            // $event_message1 = 'Heat is going to take place on '.$event_date1->format('Y-m-d');
                                            // $alertdata = [
                                            //   'cattle_id' => $insert_id,
                                            //   'event_type' => 'heat',
                                            //   'message' => $event_message1,
                                            //   'event_date' => $event_date1->format('Y-m-d')
                                            // ];
                                            // $this->UpcomingEvent_model->delete_by_type($insert_id,'heat');
                                            // $this->UpcomingEvent_model->insert_entry($alertdata);

                                            $this->update_event($data1,$event_type, $event_date, $insert_id,[],'No');                             
                                          } //ENDIF DATE AI DATE COMPARISON
                                   } else{ // ENDIF BREEDING PROCESS RECORD CHECKING
                                      //IF THERE IS NO RECORD FOUND IN THE BREEDING PROCESS TABLE
                                      // WE WILL BE MAKING ENTERIES IN THE BREEDING PROCESS TABLE 
                                      // WE WILL BE MAKING ENTERIES IN THE UPCOMING EVENTS TABLE
                                        $data1 = $default;
                                        $data1['ai_on'] = 0;
                                        $data1['ai_date'] = date_create($ai_date)->format('Y-m-d H:i:s');
                                        $data1['cattle_id'] = $insert_id;
                                        $data1['current_state'] = 'ai';
                                        $event_date = new DateTime($data['ai_date']);
                                        $event_type = 'ai';
                                        $event_message = 'Artificial Insemination is going to take place on '.$event_date->format('Y-m-d'); 
                                        $this->UpcomingEvent_model->delete_by_type($insert_id,'ai');  
                                        $alertdata = [
                                          'cattle_id' => $insert_id,
                                          'event_type' => $event_type,
                                          'message' => $event_message,
                                          'event_date' => $event_date->format('Y-m-d 00:00:00')
                                         ];
                                        $this->UpcomingEvent_model->insert_entry($alertdata); 

                                        $event_date1 = new DateTime($data['ai_date']);
                                        $event_date1->modify("-1 day");
                                        $event_message1 = 'Heat is going to take place on '.$event_date1->format('Y-m-d');
                                        $alertdata = [
                                          'cattle_id' => $insert_id,
                                          'event_type' => 'heat',
                                          'message' => $event_message1,
                                          'event_date' => $event_date1->format('Y-m-d')
                                        ];
                                        $this->UpcomingEvent_model->delete_by_type($insert_id,'heat');
                                        $this->UpcomingEvent_model->insert_entry($alertdata);


                                        if(!empty($this->input->post('first_pd_type'))){
                                          $first_pd_type = $this->input->post('first_pd_type');
                                        } else {
                                            $first_pd_type = 'UltraSound';
                                        }
                                        if(empty($this->input->post('first_pd_on'))){
                                            $first_pd_on = $default['first_pd_on'];
                                            if($first_pd_type == 'UltraSound'){
                                              $first_pd_on = $default['first_pd_on'];
                                            }                                
                                        } else {
                                            $first_pd_on = $this->input->post('first_pd_on');
                                        }
                                        $event_date = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                                        $event_date->modify("+".$first_pd_on." day");
                                        $event_type = 'first_pd';
                                        $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');
                                        $data1['first_pd_type'] = $first_pd_type;
                                        $data1['first_pd_on'] = $first_pd_on;
                                        $data1['first_pd_date'] = $event_date->format('Y-m-d 00:00:00');
                                        $result =['status' => '1','result' => $data ];
                                        $alertdata = [
                                              'cattle_id' => $insert_id,
                                              'event_type' => $event_type,
                                              'message' => $event_message,
                                              'event_date' => $event_date->format('Y-m-d')
                                           ];
                                         $this->UpcomingEvent_model->delete_by_type($insert_id,'first_pd');  
                                         $this->UpcomingEvent_model->insert_entry($alertdata);   
                                         $this->update_event($data1,$event_type, $event_date, $insert_id,[],'No'); 
                                        
                                    }
                                        //$current_state = $already['current_state'];
                                        $result =['status' => '1' ,'message' => 'Updated Successfully!' ]; //IF NOTHING CHANGED SENDING STATUS AS IT IS
                                        $data1['ai_date'] = $ai_date;
                                        $data1['cattle_id'] = $insert_id;
                            if(count($exists) >0){
                                $this->BreedingProcess->update_entry_by_cattle_id($insert_id,$data1);
                              } else{
                                unset($data1['id']);
                                $this->BreedingProcess->insert_entry($data1);                            
                              }
                         } else {  // IF AI DATE IS NOT EMPTY and type is not equal to "CALF"
                              //IF AI DATE IS EMPTY OR TYPE IS CALF
                            if($this->input->post('type') == 'Calf' ){ 
                                  //IF CATTLE IS JUST CHANGED TO CALF BUT IT WAS NOT EARLIER CALF
                                  //CREATING AN EVENT THAT CALF WILL BE ON HEAT ON 15 MONTHS FROM BIRTH OF CALF FOR HEAT
                              if($this->input->post('dob') !== '' ){
                                log_message('error', 'Here landed'.$cattle['id']);
                                log_message('error', 'DOB'.$this->input->post('dob'));
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'heat'); 
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'ai');  
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'first_pd');  
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'second_pd'); 
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'dry');  
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'steam_up');  
                                $this->UpcomingEvent_model->delete_by_type($cattle['id'],'delivery'); 
                                  $event_date = new DateTime(date_create($this->input->post('dob'))->format('Y-m-d H:i:s'));
                                  $event_date->modify("+14 months");
                                  $event_message = 'Heat is going to take place on '.$event_date->format('Y-m-d');
                                     $alertdata = [
                                          'cattle_id' => $cattle['id'],
                                          'event_type' => 'heat',
                                          'message' => $event_message,
                                          'event_date' => $event_date->format('Y-m-d')
                                      ];
                                    $this->UpcomingEvent_model->insert_entry($alertdata); 
                              }
                                  

                            }
                            //print_r($cattle);
                            // Die("Here");   
                         }  
                        $this->Cattle_model->update_entry( $this->input->post('id'), $data);
                        $result = ['status' => '1','message' => 'Cattle Updated Successfully!']; 
                      } // IF TAG ID UNIQUE USERWISE VALIDATION PASSES
                } // ENDIF FORM VALIDATION PASSES
             } //ENDIF LOGIN CHECK 
        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }


        /*
        |--------------------------------------------------------------------------
        | Function : get_cattle_from_tag
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */


          public function get_cattle_from_tag(){
            $this->load->library('form_validation');
             $this->load->model(array('Cattle_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('tag_id', 'Tag ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        ////print_r($checklogin['userdata']); die;
                        //$expense = $this->Cattle_model->get_one_from_tag($this->input->post('tag_id'));
                         $cattle = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('tag_id'),$checklogin['userdata']);

                        if(count($cattle) > 0){
                            $result =['status' => '1','result' => $cattle];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    
            
        }


           /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 

        public function get_cattles(){
            $this->load->library('form_validation');
            $this->load->model(array('Cattle_model'));
            $formdata = $this->input->post();
            $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                          $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 10;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $total_records = $this->Cattle_model->get_total($formdata,$checklogin['userdata']);
                         
                                if ($total_records > 0) 
                                {
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    if(($this->input->post('search_type') !== null ) && ($this->input->post('search_type') !== 'all')){
                                         $keyword = urldecode($this->input->post('keyword') );
                                         $this->db->like($this->input->post('search_type'), rtrim(ltrim($keyword)));
                                    }
                                    $params["result"] = $this->Cattle_model->get_all($checklogin['userdata'],$formdata);
                                    $config['base_url'] = base_url() . '/get_cattles';
                                    $config['total_rows'] = $total_records;
                                    $config['per_page'] = $limit_per_page;
                                    $this->pagination->initialize($config);
                                    // build paging links
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => [],'total' => 0 ];
                                }
                    }

             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));    
            
        }

        /*
        |--------------------------------------------------------------------------
        | Function : get_one_income
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 


        public function get_one_cattle(){
            $this->load->library('form_validation');
            $this->load->model(array('Cattle_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense = $this->Cattle_model->get_one($this->input->post('id'));
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => $expense];            
                        } else {
                            $result =['status' => '0','result' => 'Cattle record does not exist' ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    
            
        }


        /*
        |--------------------------------------------------------------------------
        | Function : update Expenses
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 

        public function products(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                          $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 2;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                  if(($this->input->post('keyword') !== null ) && (!empty($this->input->post('keyword')) )){
                                         $keyword = urldecode($this->input->post('keyword') );
                                         $this->db->like('name', rtrim(ltrim($keyword)));
                                    }   
                                     $total_records = $this->Data_model->get_total();
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                     if(($this->input->post('keyword') !== null ) && (!empty($this->input->post('keyword')) )){
                                         $keyword = urldecode($this->input->post('keyword') );
                                         $this->db->like('name', rtrim(ltrim($keyword)));
                                    }
                                    $params["result"] = $this->Data_model->get_all();
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => []];
                                }
                    }

             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));    
            
        }


          /*
        |--------------------------------------------------------------------------
        | Function : get_one_product
        |--------------------------------------------------------------------------
        | This will be used to add expenses.
        */ 


        public function get_one_product(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense = $this->Data_model->get_one($this->input->post('id'));
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => $expense];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));    
            
        }


        public function get_breeding_process(){
           $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('History_model'));
             $this->load->model(array('Cattle_model','LactationWiseMilk'));
             ////print_r($checklogin); die;
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        //$expense = $this->BreedingProcess->get_one($this->input->post('id'));
                        $this->db->where('type !=','Calf');
                        $cattle = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('cattle_id'),$checklogin['userdata']);                       
                        if(count($cattle) > 0){
                            $expense = $this->BreedingProcess->get_one_by_cattle_id($cattle['id']);
                            $all_history = $this->History_model->get_all($cattle['id']);
                            $lactation_history = $this->LactationWiseMilk->get_all($cattle['id']);

                            if(count($expense) > 0){
                                 $expense['history'] = count($all_history) ? $all_history : [];
                                 $expense['lactation_history'] = count($lactation_history) ? $lactation_history : [];
                                $result =['status' => '1','result' => $expense];            
                            } else {
                                $expense = $this->BreedingProcess->get_one(1);
                                $expense['id'] = 0;
                                $expense['cattle_id'] = $cattle['id'];
                                $expense['ai_date'] = date('Y-m-d');
                                $expense['history'] = [];
                                $expense['lactation_history'] = count($lactation_history) ? $lactation_history : [];
                                $result =['status' => '1','result' => $expense ];            
                            }
                        } else {
                            $result = ['status' => '0','reason' => 'validation' , 'errors' => ['cattle_id' => 'Record Does not Exist' ] ];
                        }
                                                
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
        }

        public function set_breeding_process(){
            $this->load->library('form_validation');
             $this->load->model(array('BreedingProcess'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  ////print_r($checklogin); die;
                   $user_id =  $checklogin['userdata']['id'];
                   //$cattle = $this->BreedingProcess->get_one($this->input->post('id'));
                   
                    $this->form_validation->set_rules('ai_on', 'Arificial Insemination', 'required');
                    $this->form_validation->set_rules('first_us_pd_on', 'First Ultra Sound PD Date', 'required');
                    $this->form_validation->set_rules('first_manual_pd_on', 'First Manual PD Date', 'required');
                    $this->form_validation->set_rules('second_us_pd_on', 'Second Ultra Sound PD Date', 'required');
                    $this->form_validation->set_rules('second_manual_pd_on', 'Second Manual PD Date', 'required');
                    $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    $this->form_validation->set_rules('dry_on', 'Second Manual PD Date', 'required');
                    $this->form_validation->set_rules('steam_up_on', 'Purchase Price', 'required|integer');
                    $this->form_validation->set_rules('delivery_on', 'Weight', 'required|integer');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
                       // $date = date_create($this->input->post('expense_date'))->format('Y-m-d');
                        $data = array(
                            'ai_on' => $this->input->post('ai_on'),
                            'first_us_pd_on' => $this->input->post('first_us_pd_on'),
                            'first_manual_pd_on' => $this->input->post('first_manual_pd_on'),
                            'second_us_pd_on' => $this->input->post('second_us_pd_on'),
                            'second_manual_pd_on' => $this->input->post('second_manual_pd_on'),
                            'cattle_id' => $this->input->post('cattle_id'),
                            'dry_on' => $this->input->post('dry_on'),
                            'steam_up_on' => $this->input->post('steam_up_on'),
                            'delivery_on' => $this->input->post('delivery_on'),
                        );
                       // $this->Cattle_model->insert_entry($data);
                        if($this->input->post('id') == 0){
                          $this->BreedingProcess->insert_entry($data);
                        } else {
                          $this->BreedingProcess->update_entry($this->input->post('id'), $data);
                        }
                        
                        $result = ['status' => '1','message' => 'Breeding Process Updated Successfully!'];
                }
             }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));  
        }


        public function update_breeding_process(){
            //'ai','first_pd','second_pd','pregnancy_confirmation','dry','steam_up','delivery',
              $this->load->library('form_validation');
             $post = $this->input->post();
             $checklogin = $this->checklogin($post);             
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('UpcomingEvent_model'));
             $this->load->model(array('History_model'));
             $this->load->model(array('Cattle_model'));
             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                ////print_r($post); die;
                    //$this->form_validation->set_rules('ai_on', 'Arificial Insemination', 'required');
                    $this->form_validation->set_rules('ai_date', 'Arificial Insemination', 'required');
                    $this->form_validation->set_rules('first_pd_on', 'First  PD Days', 'required');
                    $this->form_validation->set_rules('first_pd_type', 'First PD Type', 'required');
                    $this->form_validation->set_rules('second_pd_on', 'Second  PD Date', 'required');
                    $this->form_validation->set_rules('second_pd_type', 'Second  PD Type', 'required');
                    $this->form_validation->set_rules('heat_after_pd_not_successful', 'Next Heat After (If PD is not Successfull)', 'required');
                    $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    $this->form_validation->set_rules('dry_on', 'Second Manual PD Date', 'required');
                    $this->form_validation->set_rules('steam_up_on', 'Purchase Price', 'required|integer');
                    $this->form_validation->set_rules('delivery_on', 'Weight', 'required|integer');

                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {

                       $cattle_id = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('cattle_id'),$checklogin['userdata'])['id'];
                       //echo $cattle_id; die;
                        $data = array(
                                      'ai_on' => 0,
                                      'first_pd_on' => $this->input->post('first_pd_on'),
                                      'first_pd_type' => $this->input->post('first_pd_type'),
                                      'second_pd_on' => $this->input->post('second_pd_on'),
                                      'second_pd_type' => $this->input->post('second_pd_type'),
                                      'heat_after_pd_not_successful' => $this->input->post('heat_after_pd_not_successful'),
                                      'cattle_id' => $cattle_id,
                                      'dry_on' => $this->input->post('dry_on'),
                                      'steam_up_on' => $this->input->post('steam_up_on'),
                                      'delivery_on' => $this->input->post('delivery_on'),
                                  );
                       
                        $formdata = $this->input->post();
                        $already = $this->BreedingProcess->get_one_by_cattle_id($cattle_id);
                        $default = $this->BreedingProcess->get_one(1);
                        $is_already_record_there ='Yes';
                        if(count($already) == 0){
                            $already = $default;
                            $is_already_record_there ='No';
                        }
                        //echo "if"; die;
                        if(count($already) > 0){
                            //echo "already"; die;
                            // IF BREEDING PROCESS IS ALREADY THERE
                            $current_state = $already['current_state'];
                            $result =['status' => '1' ,'message' => 'Updated Successfully!' ]; //IF NOTHING CHANGED SENDING STATUS AS IT IS
                            if(date_create($formdata['ai_date'])->format('Y-m-d') !== date_create($already['ai_date'])->format('Y-m-d')){
                              // IF POSTED AI DATE DOES NOT MATCHED THE DATE IN DB
                              // DELETED OLD BREEDING PROCESS RECORD AND WILL CREATE NEW
                                if($is_already_record_there == 'Yes'){
                                    $this->BreedingProcess->delete($already['id']);      
                                }
                              $data['ai_on'] = 0;
                              $data['ai_date'] = date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s');
                              $data['cattle_id'] = $cattle_id;
                              $data['current_state'] = 'ai';
                              $event_date = new DateTime($data['ai_date']);
                              $event_type = 'ai';
                              $event_message = 'Artificial Insemination is going to take place on '.$event_date->format('Y-m-d');                               
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'ai');  
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'first_pd');  
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'second_pd'); 
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'dry');  
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'steam_up');  
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'delivery');  

                              $alertdata = [
                                'cattle_id' => $cattle_id,
                                'event_type' => $event_type,
                                'message' => $event_message,
                                'event_date' => $event_date->format('Y-m-d 00:00:00')
                               ];
                                ////print_r($event_date); die;
                              $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('ai',$cattle_id);
                              $this->UpcomingEvent_model->delete($u['id']); 
                              $this->UpcomingEvent_model->insert_entry($alertdata); 

                              //INSERTING RECORD FOR THE HEAT EVENT
                              $event_date1 = new DateTime($data['ai_date']);
                                $event_date1->modify("-1 day");
                                $event_message1 = 'Heat is going to take place on '.$event_date1->format('Y-m-d');
                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => 'heat',
                                  'message' => $event_message1,
                                  'event_date' => $event_date1->format('Y-m-d')
                                ];
                                //DELETING OLD HEAT EVENT RECORD
                                $heat = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('heat',$cattle_id);
                                $this->UpcomingEvent_model->delete($heat['id']); 
                                //INSERTING HEAT EVENT IN DB
                                $this->UpcomingEvent_model->insert_entry($alertdata);

                              if(!empty($this->input->post('first_pd_type'))){
                                $first_pd_type = $this->input->post('first_pd_type');
                              } else {
                                  $first_pd_type = 'UltraSound';
                              }

                              if(empty($this->input->post('first_pd_on'))){
                                  $first_pd_on = $default['first_pd_on'];
                                  if($first_pd_type == 'UltraSound'){
                                    $first_pd_on = $default['second_pd_on'];
                                  }                                
                              } else {
                                  $first_pd_on = $this->input->post('first_pd_on');
                              }
                              
                              $event_date = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                              $event_date->modify("+".$first_pd_on." day");
                              $event_type = 'first_pd';
                              $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');

                              $data['first_pd_type'] = $first_pd_type;
                              $data['first_pd_on'] = $first_pd_on;
                              $data['first_pd_date'] = $event_date->format('Y-m-d 00:00:00');
                              $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('first_pd',$cattle_id);
                              $this->UpcomingEvent_model->delete($u['id']);
                               $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                                  ]; 
                              

                              $this->UpcomingEvent_model->insert_entry($alertdata);
                              $this->BreedingProcess->insert_entry($data);
                              $this->update_event($post,$event_type, $event_date, $cattle_id,[],'No');
                              $result =['status' => '1','result' => $data ]; 
                            } else {
                              // IF POSTED AI DATE MATCHED THE DATE IN DB
                              // MEANS BREEDING PROCESSING IS MOVING FORWARD
                              if( $post['first_pd_on'] !== $already['first_pd_on']){
                                // IF AI IS DONE BUT FIRST PD CHANGED BEFORE GETTING DONE
                                $first_pd_type = $post['first_pd_type'];
                                if(empty($this->input->post('first_pd_on'))){
                                    $first_pd_on = $default['first_manual_pd_on'];
                                    if($first_pd_type == 'UltraSound'){
                                      $first_pd_on = $default['first_pd_on'];
                                    }                                
                                } else {
                                    $first_pd_on = $this->input->post('first_pd_on');
                                }
                                $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('first_pd',$cattle_id);
                                $this->UpcomingEvent_model->delete($u['id']);
                                $event_date = new DateTime($already['ai_date']);
                                $event_date->modify("+".$first_pd_on." day");
                                $event_type = 'first_pd';
                                $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');
                                //SAVING DATA TO BREEDING TABLE
                                $data['first_pd_on'] = $first_pd_on;
                                $data['first_pd_date'] = $event_date->format('Y-m-d H:i:s');
                                $data['cattle_id'] = $cattle_id;
                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                                  ];
                            
                                $this->UpcomingEvent_model->insert_entry($alertdata);
                                $this->BreedingProcess->update_entry($already['id'], $data);
                                //$this->update_event($post,$event_type, $event_date, $this->input->post('cattle_id'),$already);
                                $result =['status' => '1','result' => $data ];
                              } 

                              if($post['second_pd_on'] !== $already['second_pd_on']) {
                                // IF AI IS DONE BUT Second PD CHANGED BEFORE GETTING DONE
                                $second_pd_type = $post['second_pd_type'];

                                if(empty($this->input->post('second_pd_on'))){
                                    $second_pd_on = $default['second_manual_pd_on'];
                                    if($second_pd_type == 'UltraSound'){
                                      $second_pd_on = $default['second_pd_on'];
                                    }                                
                                } else {
                                    $second_pd_on = $this->input->post('second_pd_on');
                                }
                                $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('second_pd',$cattle_id);
                                $this->UpcomingEvent_model->delete($u['id']);
                                $event_date = new DateTime($already['ai_date']);
                                $event_date->modify("+".$second_pd_on." day");
                                $event_type = 'second_pd';
                                $event_message = 'Second PD is going to take place on '.$event_date->format('Y-m-d');

                                $data['second_pd_on'] = $second_pd_on;
                                $data['second_pd_date'] = $event_date->format('Y-m-d H:i:s');
                                $data['cattle_id'] = $cattle_id;


                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                            ];
                            
                                $this->UpcomingEvent_model->insert_entry($alertdata);
                                $this->BreedingProcess->update_entry($already['id'], $data);
                                //$this->update_event($post,$event_type, $event_date, $this->input->post('cattle_id'),$already);
                                $result =['status' => '1','result' => $data ];
                              } 

                              if( $post['dry_on'] !== $already['dry_on']) {
                                  $dry_on = $post['dry_on'];
                                if(empty($this->input->post('dry_on'))){
                                    $dry_on = $default['dry_on'];
                                } else {
                                    $dry_on = $this->input->post('dry_on');
                                }
                               // //print_r($dry_on); die;
                                $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('dry',$cattle_id);
                                $this->UpcomingEvent_model->delete($u['id']);
                                $event_date = new DateTime($already['ai_date']);
                                $event_date->modify("+".$dry_on." day");
                                $event_type = 'dry';
                                $event_message = 'Putting Cattle on Dry is going to take place on '.$event_date->format('Y-m-d'); 

                                $data['dry_on'] = $dry_on;
                                $data['dry_date'] = $event_date->format('Y-m-d H:i:s');
                                $data['cattle_id'] = $cattle_id;

                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                                ];

                                $this->UpcomingEvent_model->insert_entry($alertdata);
                                $this->BreedingProcess->update_entry($already['id'], $data);
                               // $this->update_event($post,$event_type, $event_date, $this->input->post('cattle_id'),$already);
                                $result =['status' => '1','result' => $data ];
                              } 
                               if( $post['steam_up_on'] !== $already['steam_up_on']) {
                                  $steam_up_on = $post['steam_up_on'];
                                if(empty($this->input->post('steam_up_on'))){
                                    $steam_up_on = $default['steam_up_on'];
                                } else {
                                    $steam_up_on = $this->input->post('steam_up_on');
                                }
                                $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('steam_up',$cattle_id);
                                if(count($u) > 0){
                                  $this->UpcomingEvent_model->delete_by_type($cattle_id,'steam_up');  
                                }
                                
                                $event_date = new DateTime($already['ai_date']);                                
                                $event_date->modify("+".$steam_up_on." day");
                                ////print_r($event_date); die;
                                $event_type = 'steam_up';
                                $event_message = 'Putting Cattle on Steam  is going to take place on  '.$event_date->format('Y-m-d'); 

                                $data['steam_up_on'] = $steam_up_on;
                                $data['steam_up_date'] = $event_date->format('Y-m-d H:i:s');
                                $data['cattle_id'] = $cattle_id;

                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                                ];                            
                                $this->UpcomingEvent_model->insert_entry($alertdata);
                                $this->BreedingProcess->update_entry($already['id'], $data);
                                //$this->update_event($post,$event_type, $event_date, $this->input->post('cattle_id'),$already);
                                $result =['status' => '1','result' => $data ];
    
                              } 

                               if( $post['delivery_on'] !== $already['delivery_on']) {
                                  $delivery_on = $post['delivery_on'];
                                if(empty($this->input->post('delivery_on'))){
                                    $delivery_on = $default['delivery_on'];
                                } else {
                                    $delivery_on = $this->input->post('delivery_on');
                                }
                                $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('delivery',$cattle_id);
                                $this->UpcomingEvent_model->delete($u['id']);
                                $event_date = new DateTime($already['ai_date']);
                                $event_date->modify("+".$delivery_on." day");
                                $event_type = 'delivery';
                                $event_message = 'Putting Calf Delivery is going to take place on '.$event_date->format('Y-m-d'); 
                                //$this->UpcomingEvent_model->delete_by_type($this->input->post('cattle_id'),'steam_up');  
                                $data['delivery_on'] = $delivery_on;
                                $data['delivery_date'] = $event_date->format('Y-m-d H:i:s');
                                $data['cattle_id'] = $cattle_id;
                                $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                                 ];
                                
                                $this->UpcomingEvent_model->insert_entry($alertdata);
                                $this->BreedingProcess->update_entry($already['id'], $data);
                               // $this->update_event($post,$event_type, $event_date, $this->input->post('cattle_id'),$already);
                                $result =['status' => '1','result' => $data ];
                              } 
                              
                              
                            }
                            
                        } else {
                              //IF THERE IS NO BREEDING PROCESS RECORD IN DB
                              // WE WILL BE INSERTING NEW RECORD TO DB
                              //echo "new"; die;
                              $data['ai_on'] = 0;
                              $data['ai_date'] = date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s');
                              $data['cattle_id'] = $cattle_id;
                              $data['current_state'] = 'ai';

                              $event_date = new DateTime($data['ai_date']);
                              //$event_date->modify("+".$on." day");
                              $event_type = 'ai';
                              $event_message = 'Artificial Insemination is going to take place on '.$event_date->format('Y-m-d'); 
                              $this->UpcomingEvent_model->delete_by_type($cattle_id,'ai');  
                              $alertdata = [
                                'cattle_id' => $cattle_id,
                                'event_type' => $event_type,
                                'message' => $event_message,
                                'event_date' => $event_date->format('Y-m-d 00:00:00')
                               ];
                                ////print_r($event_date); die;
                              $this->UpcomingEvent_model->insert_entry($alertdata); 
                              if(!empty($this->input->post('first_pd_type'))){
                                $first_pd_type = $this->input->post('first_pd_type');
                              } else {
                                  $first_pd_type = 'UltraSound';
                              }

                              if(empty($this->input->post('first_pd_on'))){
                                  $first_pd_on = $default['first_manual_pd_on'];
                                  if($first_pd_type == 'UltraSound'){
                                    $first_pd_on = $default['second_us_pd_on'];
                                  }                                
                              } else {
                                  $first_pd_on = $this->input->post('first_pd_on');
                              }

                              $event_date = new DateTime(date_create($this->input->post('ai_date'))->format('Y-m-d H:i:s'));
                              ////print_r($event_date); die;
                              $event_date->modify("+".$first_pd_on." day");
                              $event_type = 'first_pd';
                              $event_message = 'First PD is going to take place on '.$event_date->format('Y-m-d');

                              ////print_r($event_message); die;

                              $data['first_pd_type'] = $first_pd_type;
                              $data['first_pd_on'] = $first_pd_on;
                              $data['first_pd_date'] = $event_date->format('Y-m-d 00:00:00');
                            

                            $result =['status' => '1','result' => $data ];   

                            $alertdata = [
                                  'cattle_id' => $cattle_id,
                                  'event_type' => $event_type,
                                  'message' => $event_message,
                                  'event_date' => $event_date->format('Y-m-d')
                              ];
                            ////print_r($event_date); die;                           

                            $this->UpcomingEvent_model->insert_entry($alertdata);    
                            ////print_r($data); die;
                            $this->BreedingProcess->insert_entry($data);
                            $this->update_event($post,$event_type, $event_date, $cattle_id,[],'No');     
                        }
                             // $this->form_validation->set_rules('first_pd_type', 'First PD ty', 'required|integer|is_unique[cattles.tag_id]');
                            
                        
                    }
             }
                 return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));


        }

        public function update_state(){
            $this->load->library('form_validation');
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('UpcomingEvent_model'));
             $this->load->model(array('History_model','DeliveryData','Cattle_model','LactationWiseMilk'));
             $formdata = $this->input->post();
             ////print_r($_POST); die;
             $checklogin = $this->checklogin($formdata);
             
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                   $user_id =  $checklogin['userdata']['id'];
                    $this->form_validation->set_rules('type', 'Breeding Process Type', 'required');
                    $this->form_validation->set_rules('cattle_id[]', 'Cattle ID(s)', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        $cattles = $this->input->post('cattle_id');
                        if(count($cattles) > 0){
                          foreach ($cattles as $cattle) {
                                $already = $this->BreedingProcess->get_one_by_cattle_id($cattle);
                                $default = $this->BreedingProcess->get_one(1);

                                $save_data = [];
                                $update_delivery_date = 'No';
                              if($formdata['type'] == 'heat'){
                                 $history_event_type = 'heat';
                                 $history_event_date = date('Y-m-d');
                                 $history_ai_date = date('Y-m-d');
                                 $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                if(count($u) > 0){
                                    $history_message = 'Cattle was on Heat on '.date_create($u['event_date'])->format('d/m/Y');
                                    $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                                } else {
                                    $history_message = 'First PD Done on '. date('d/m/Y');
                                    //$history_event_date = date('Y-m-d 00:00:00');
                                }
                                 //$history_message = 'Cattle was on Heat on '. date('d/m/Y'); 
                                 $cattle_record =  $this->Cattle_model->get_one($cattle);                                                                 
                                 if(count($cattle_record) > 0){
                                    if($cattle_record['type'] == 'Calf'){
                                         $cattle_save_data = [
                                            'type' => 'Heifer',
                                        ];
                                        $this->Cattle_model->update_entry($cattle,$cattle_save_data); 
                                    }
                                 }
                                
                              } else if($formdata['type'] == 'ai'){
                                    $new_date = date('Y-m-d 00:00:00');
                                    if(isset($formdata['new_ai_date'])){
                                        $new_date = date_create($formdata['new_ai_date']);
                                        $already['ai_date'] = $new_date->format('Y-m-d 00:00:00');
                                        $save_data['ai_date'] = $new_date->format('Y-m-d 00:00:00');
                                        $cattle_save_data = [
                                            'ai_date' => $new_date->format('Y-m-d 00:00:00')
                                        ];
                                        $this->Cattle_model->update_entry($cattle,$cattle_save_data); 
                                    } 
                                    //IN CASE OF AI WE WILL UPDATE REST OF EVENTS DONE STATUS TO "No"   
                                    $save_data['is_ai_done'] = 'Yes';                                    
                                    $save_data['is_delivery_done'] = 'No';                                    
                                    $save_data['is_first_pd_done'] = 'No';
                                    $save_data['is_second_pd_done'] = 'No';
                                    $save_data['is_dry_done'] = 'No';
                                    $save_data['is_steam_up_done'] = 'No';
                                    $save_data['current_state'] = 'ai';
                                    //PREPARING DATA FOR SAVING IN DELIVERY EVENT
                                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                    if(count($u) > 0){
                                        $history_message = 'AI was Done on '.$new_date->format('d/m/Y');
                                        $history_event_date = $new_date->format('Y-m-d 00:00:00');
                                    } else {
                                        $history_message = 'AI was Done on '. date('d/m/Y');
                                        $history_event_date = date('Y-m-d 00:00:00');;
                                    }
                                    $history_event_type = 'ai';
                                   // $history_event_date = date('Y-m-d 00:00:00');
                                    $history_ai_date = $already['ai_date'];
                                    //$history_message = 'AI was Done on '. date('d-m-Y');
                                    $on = $already['first_pd_on'];  
                                    $event_date = new DateTime($already['ai_date']);
                                    $event_date->modify("+".$on." day");                                
                                    $event_type = 'first_pd';
                                    $event_message = 'First PD is going to take place on '.$event_date->format('d/m/Y'); 
                                    $this->update_event((count($already) > 0) ? $already : $default ,$already['ai_date'], $event_date, $cattle,[], 'No'); 
                              } else if($formdata['type'] == 'first_pd'){
                                    $save_data['is_first_pd_done'] = 'Yes';
                                    $save_data['first_pd_date'] = date('Y-m-d 00:00:00');
                                    $save_data['current_state'] = 'first_pd';
                                    $history_event_type = 'first_pd';
                                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                    if(count($u) > 0){
                                        $history_message = 'First PD Done on '.date_create($u['event_date'])->format('d/m/Y');
                                        $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                                    } else {
                                        $history_message = 'First PD Done on '. date('d/m/Y');
                                        $history_event_date = $save_data['first_pd_date'];
                                    }
                                   
                                    $history_ai_date = $already['ai_date'];
                                    
                                    $on = $already['second_pd_on'];
                                    //echo $already['ai_date']; die;
                                    $event_date = new DateTime($already['ai_date']);
                                    $event_date->modify("+".$on." day");
                                    $event_type = 'second_pd';

                                    $event_message = 'Second PD is going to take place on '.$event_date->format('d/m/Y'); 
                                    $update_delivery_date = 'Yes';
                                    //UPDATING TENTATIVE DELIVERY DATE

                                     $event_date1 = new DateTime($already['ai_date']);
                                     $event_date1->modify("+".$already['delivery_on']." day");
                                        $cattle_save_data = [
                                            'calving_date' => $event_date1->format('Y-m-d 00:00:00'),
                                            'is_pregnant' => 'Yes'
                                        ];
                                        $this->Cattle_model->update_entry($cattle,$cattle_save_data); 


                              } else if($formdata['type'] == 'second_pd'){
                                    $save_data['is_second_pd_done'] = 'Yes';
                                    $save_data['second_pd_date'] = date('Y-m-d 00:00:00');
                                    $save_data['current_state'] = 'second_pd';
                                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                    if(count($u) > 0){
                                        $history_message = 'Second PD Done on '.date_create($u['event_date'])->format('d/m/Y');
                                        $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                                    } else {
                                        $history_message = 'Second PD Done on '. date('d/m/Y');   
                                        $history_event_date =  $save_data['second_pd_date'];  
                                    }
                                    $history_event_type = 'second_pd';                                    
                                    $history_ai_date = $already['ai_date'];
                                    $on = $already['dry_on'];

                                    $event_date = new DateTime($already['ai_date']);
                                    $event_date->modify("+".$on." day");
                                    $event_type = 'dry';
                                    $event_message = 'Putting Cattle on Dry is going to take place on '.$event_date->format('d-m-Y'); 
                                    $update_delivery_date = 'Yes';
                                    //UPDATING TENTATIVE DELIVERY DATE
                                    // //print_r($update_delivery_date); die;
                                    //if($update_delivery_date == 'Yes'){
                                    $event_date1 = new DateTime($already['ai_date']);
                                    $event_date1->modify("+".$already['delivery_on']." day");
                                        $cattle_save_data = [
                                            'calving_date' => $event_date1->format('Y-m-d 00:00:00'),
                                            'is_pregnant' => 'Yes'
                                        ];
                                        $this->Cattle_model->update_entry($cattle,$cattle_save_data);    
                                    //}
                              } else if($formdata['type'] == 'dry'){
                                    $save_data['is_dry_done'] = 'Yes';
                                    $save_data['dry_date'] = date('Y-m-d 00:00:00');
                                    $save_data['current_state'] = 'dry';
                                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                    if(count($u) > 0){
                                        $history_message = 'Cattle Put On Dry Done on '.date_create($u['event_date'])->format('d/m/Y');
                                        $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                                    } else {
                                        $history_message = 'Cattle Put On Dry Done on '. date('d/m/Y');
                                        $history_event_date = $save_data['dry_date'];
                                    }
                                    $history_event_type = 'dry';
                                    
                                    $history_ai_date = $already['ai_date'];
                                   
                                    $on = $already['steam_up_on'];

                                    $event_date = new DateTime($already['ai_date']);
                                    $event_date->modify("+".$on." day");
                                    $event_type = 'steam_up';
                                    $event_message = 'Putting Cattle on Steam Up is going to take place on '.$event_date->format('d-m-Y'); 
                                    //IF ANIMAL IS ON DRY 
                                    //UPDATING A KEY TO BE CHECKED WHILE SHOWING ANIMALS IN MILKDATA 
                                    //ANIMALS ON DRY WILL NOT BE COMMING IN THE MILKDATA PAGE.
                                     $cattle_save_data = [
                                            'is_animal_on_dry' => 'Yes'
                                        ];
                                        $this->Cattle_model->update_entry($cattle,$cattle_save_data); 
                              } else if($formdata['type'] == 'steam_up'){
                                    $save_data['is_steam_up_done'] = 'Yes';
                                    $save_data['steam_up_date'] = date('Y-m-d 00:00:00');
                                    $save_data['current_state'] = 'steam_up';

                                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);                                    
                                    if(count($u) > 0){
                                        $history_message = 'Steam Up Done on '.date_create($u['event_date'])->format('d/m/Y');
                                        $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                                    } else {
                                        $history_message = 'Steam Up Done on '. date('d/m/Y');
                                        $history_event_date = $save_data['steam_up_date'];
                                    }
                                    $history_event_type = 'steam_up';
                                    
                                    $history_ai_date = $already['ai_date'];
                                    
                                    $on = $already['delivery_on'];

                                    $event_date = new DateTime($already['ai_date']);
                                    $event_date->modify("+".$on." day");
                                    $event_type = 'delivery';
                                    $event_message = 'Delivery is going to take place on '.$event_date->format('Y-m-d'); 
                              } else if($formdata['type'] == 'delivery'){
                                    //BEFORE UPDATING DELIVER DATA
                                    //WE NEED TO MAKE SURE IF USER HAS ENTERED THE DELIVERY DATA OR NOT
                                    //IF DELIVERY DATA IS NOT ENTERED WILL BE GENERATING ERROR
                                    //$result = ['status' => '0','reason' => 'validation' , 'errors' => ['tag_id' => 'You have already alloted that tag to one of your cattles'] ];
                                    if($already['is_delivery_data_entered'] == 'No'){
                                        $result = ['status' => '0','reason' => 'validation' , 'errors' => ['delivery_date' => 'Please Enter Delivery Data First!'] ]; 
                                         return $this->output
                                                ->set_content_type('application/json')
                                                ->set_status_header(200)
                                                ->set_output(json_encode($result));  
                                    }
                                    $delivery_event = $this->DeliveryData->find_upcomming_by_date_and_cattle_id($already['delivery_date'],$cattle);
                                    $save_data['is_delivery_done'] = 'Yes';
                                    $save_data['is_ai_done'] = 'No';
                                    $save_data['delivery_date'] =  (count($delivery_event) > 0) ?  $delivery_event['delivery_date'] :  date('Y-m-d 00:00:00');
                                    $save_data['current_state'] = 'delivery';
                                    $history_event_type = 'delivery';
                                    $history_event_date = $save_data['delivery_date'];
                                    //print_r($already); die;
                                    //echo $already['ai_date']; die;
                                    $history_ai_date = $already['ai_date'];
                                        if(count($delivery_event) > 0){
                                            $history_message = 'Delivery Done on '.date_create( $delivery_event['delivery_date'])->format('d/m/Y').'.<br>Male: '. $delivery_event['male_count'] .'  <br>Female: '. $delivery_event['female_count'] .' <br>Died: '. $delivery_event['dead_count'] .'';
                                        } else {
                                            $history_message = 'Delivery Done on '. date('d/m/Y');   
                                        }
                                    $cattle_record = $this->Cattle_model->get_one($cattle);
                                    if(count($cattle_record) > 0){
                                        //LactationWiseMilk
                                        $lactation_wise_milk = [
                                            'cattle_id' => $cattle_record['id'],
                                            'milk' => $cattle_record['per_day_milk'],
                                            'lactation' => $cattle_record['lactation'],
                                        ];                                        
                                        $this->LactationWiseMilk->insert_entry( $lactation_wise_milk); 
                                        $update_lactation = [
                                            'lactation' => $cattle_record['lactation'] + 1,
                                            'per_day_milk' => 0,
                                            'is_animal_on_dry' => 'No', //RESETTING PREVIOUSLY CHANGED KEY
                                            'type' => (($cattle_record['type'] == 'Heifer') ? 'Cow' :  $cattle_record['type'])
                                        ];
                                      $this->Cattle_model->update_entry( $cattle, $update_lactation);    
                                    }
                                    $on = 40;                                  
                                    $event_date1 = new DateTime($already['delivery_date']);
                                    $event_date1->modify("+39 day");
                                    $event_message1 = 'Heat is going to take place on '.$event_date1->format('d/m/Y');
                                    $alertdata = [
                                      'cattle_id' => $cattle,
                                      'event_type' => 'heat',
                                      'message' => $event_message1,
                                      'event_date' => $event_date1->format('Y-m-d')
                                    ];
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'heat');
                                    $this->UpcomingEvent_model->insert_entry($alertdata);
                                    // IF DELIVER IS DONE CREATING NEW EVENT FOR AI AGAIN
                                    // WHOLE PROCESS WILL BE REGENERATED AFTER AI IS MARKED DONE.
                                    // NEED DAT TO FIND DATA FROM DELIVERY DATA
                                    $event_date2 = new DateTime($already['delivery_date']);                                    
                                    $event_date2->modify("+".$on." day");
                                    $event_date = $event_date1->modify("+1 day");
                                    $event_type = 'ai';
                                    $event_message = 'Artificial Insemination is going to take place on '.$event_date2->format('Y-m-d');   
                                    $save_data['ai_date'] = $event_date2->format('Y-m-d H:i:s');
                                    $this->BreedingProcess->update_entry_by_cattle_id($cattle, $save_data);
                                    $update_lactation = [
                                            'ai_date' => $event_date2->format('Y-m-d H:i:s'),
                                        ];
                                    $this->Cattle_model->update_entry( $cattle, $update_lactation);
                                    //AS DELIVERY IS DONE SO IS ALL THE BREEDING PROCESS
                                    //DELETING ALL THE DATA FROM THE UPCOMMING EVENTS TABLE TO THE RELATED CATTLE
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'ai');  
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'first_pd');  
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'second_pd'); 
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'dry');  
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'steam_up');  
                                    $this->UpcomingEvent_model->delete_by_type($cattle,'delivery');  
                                    $already = $this->BreedingProcess->get_one_by_cattle_id($cattle);
                                    $this->update_event((count($already) > 0) ? $already : $default ,$event_type, $event_date2, $cattle,[], $update_delivery_date);     
                                //$this->BreedingProcess->delete($cattle);
                              }

                              $history = [
                                    'cattle_id' => $cattle,
                                    'event_type' => $history_event_type,
                                    'event_date' => $history_event_date,
                                    'ai_date' => $history_ai_date,
                                    'message' => $history_message
                                ];
                                //print_r($history); die;
                                  if($formdata['type'] == 'heat'){
                                      $this->History_model->insert_entry($history);
                                      $this->UpcomingEvent_model->delete_by_type($cattle, $formdata['type']);  
                                  } else {
                                    $alertdata = [
                                        'cattle_id' => $cattle,
                                        'event_type' => $event_type,
                                        'message' => $event_message,
                                        'event_date' => $event_date->format('Y-m-d')
                                     ];
                                     $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($event_type,$cattle);
                                     if(count($u) > 0){
                                          $this->UpcomingEvent_model->delete($u['id']);
                                     }
                                      $this->UpcomingEvent_model->insert_entry($alertdata);                                    
                                      $this->History_model->insert_entry($history);
                                      
                                      $this->BreedingProcess->update_entry_by_cattle_id($cattle, $save_data);
                                      if($formdata['type'] !== 'delivery'){
                                         $this->UpcomingEvent_model->delete_by_type($cattle, $formdata['type']);    
                                      }
                                  }
                             } // END CATTLES FOREACH

                        } //END  if(count($cattles) > 0)
                        $result = ['status' => '1','message' => 'Breeding Process Updated Successfully!'];
                } //END VALIDATION ELSE
             } //END LOGIN CHECK ELSE
        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));  
        }


        public function get_events_count(){
             $this->load->library('form_validation');
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('UpcomingEvent_model','Cattle_model','DailyMilkData','GlobalSetting','Group_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                   $heat = $this->UpcomingEvent_model->get_by_type('heat',$checklogin['userdata']);
                   $ai = $this->UpcomingEvent_model->get_by_type('ai',$checklogin['userdata']);
                   $first_pd = $this->UpcomingEvent_model->get_by_type('first_pd',$checklogin['userdata']);
                   $second_pd = $this->UpcomingEvent_model->get_by_type('second_pd',$checklogin['userdata']);
                   $dry = $this->UpcomingEvent_model->get_by_type('dry',$checklogin['userdata']);
                   $steam_up = $this->UpcomingEvent_model->get_by_type('steam_up',$checklogin['userdata']);
                   $delivery = $this->UpcomingEvent_model->get_by_type('delivery',$checklogin['userdata']);
                   $total_cattles = $this->Cattle_model->get_total([], $checklogin['userdata']);
                   $mycattles = $this->Cattle_model->get_all_cattle_ids($checklogin['userdata']);
                   $this->db->where('is_sold','No');
                   $this->db->where('is_dead','No');     
                   $milking_cattles = $this->Cattle_model->get_all_cattles_execpt_dry($checklogin['userdata']);
                   $total_groups = $this->Group_model->get_total([],$checklogin['userdata']);
                   $admin_email = $this->GlobalSetting->get_one_by_key('admin_email');
                   $contact_phone = $this->GlobalSetting->get_one_by_key('contact_phone');
                   $final_milk_production= 0;
                   if(count($mycattles) > 0){
                      foreach ($mycattles as  $cattle) {
                         $today_milk = $this->DailyMilkData->get_by_cattle_and_date($cattle['id']);
                         if(count($today_milk) > 0){
                            $final_milk_production += $today_milk['total'];
                         }
                      }
                   } else {
                      //NO CATTLES NO MILK PRODUCTION
                      $final_milk_production += 0;
                   }
                   //$total_milk_production = 
                   $result['heat'] = ['total' => count($heat),'IDs' => count($heat) ? $heat : [] ];
                   $result['ai'] = ['total' => count($ai),'IDs' => count($ai) ? $ai : [] ];
                   $result['first_pd'] = ['total' => count($first_pd),'IDs' => count($first_pd) ? $first_pd : [] ];
                   $result['second_pd'] = ['total' => count($second_pd),'IDs' => count($second_pd) ? $second_pd : [] ];
                   $result['dry'] = ['total' => count($dry),'IDs' => count($dry) ? $dry : [] ];
                   $result['steam_up'] = ['total' => count($steam_up),'IDs' => count($steam_up) ? $steam_up : [] ];
                   $result['delivery'] = ['total' => count($delivery),'IDs' => count($delivery) ? $delivery : [] ];
                   $result['total_cattles'] = $total_cattles;
                   $result['today_milk_production'] = $final_milk_production;
                   $result['admin_email'] = (isset($admin_email)) ? $admin_email : [];
                   $result['contact_phone'] = (isset($contact_phone)) ? $contact_phone : [];                       
                   $result['milking_cattles_count'] = (isset($milking_cattles) && $milking_cattles !== null) ? count($milking_cattles) : 0;                       
                   $result['total_groups'] = (isset($total_groups)) ? $total_groups : 0;                       
                   $result = ['status' => '1','result' => $result];
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
        }

        public function cattle_history(){
             $this->load->library('form_validation');
             $this->load->model(array('BreedingProcess'));
             $this->load->model(array('History_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                   $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    $this->form_validation->set_rules('page', 'Page', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                       $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                                $params = array();
                                $limit_per_page = 10;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $total_records = $this->History_model->get_total($formdata);                         
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    $params["result"] = $this->History_model->get_current_page_records($limit_per_page, $start_index,$formdata);
                                    $config['base_url'] = base_url() . 'product/list';
                                    $config['total_rows'] = $total_records;
                                    $config['per_page'] = $limit_per_page;
                                    $this->pagination->initialize($config);
                                    //build paging links
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => []];
                                }
                        $result = $params;
                    }          
                   
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

        }

         public function delete_cattle(){
             $this->load->library('form_validation');
             $this->load->model(array('Cattle_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->Cattle_model->soft_delete($this->input->post('cattle_id'));
                        $result = ['status' => '1','message' => 'Deleted Successfully!'];
                    }
                   //$result['delivery'] = date_create();
                   
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

         }
          public function delete_group(){
             $this->load->library('form_validation');
             $this->load->model(array('Group_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('group_id', 'Group ID', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->Group_model->delete($this->input->post('group_id'));
                        $result = ['status' => '1','message' => 'Deleted Successfully!'];
                    }
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

         }

         

         public function delete_expense(){
             $this->load->library('form_validation');
             $this->load->model(array('Expense_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('id', 'ID', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->Expense_model->delete($this->input->post('id'));
                        $result = ['status' => '1','message' => 'Deleted Successfully!'];
                    }
                   //$result['delivery'] = date_create();
                   
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

         }

             public function delete_income(){
             $this->load->library('form_validation');
             $this->load->model(array('Income_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('id', 'ID', 'required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->Income_model->delete($this->input->post('id'));
                        $result = ['status' => '1','message' => 'Deleted Successfully!'];
                    }
                   //$result['delivery'] = date_create();
                   
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

         }


         public function product_request(){
            $this->load->library('form_validation');
             $this->load->model(array('ProductRequest_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('name', 'Name', 'required');
                    $this->form_validation->set_rules('phone', 'Phone', 'required');
                    $this->form_validation->set_rules('sku', 'Sku', 'required');
                    $this->form_validation->set_rules('quantity', 'Quantity', 'required');
                    $this->form_validation->set_rules('quote_Message', 'Quote Message', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required');

                    $savedata = [
                        'name' => $this->input->post('name'),
                        'contact' => $this->input->post('phone'),
                        'sku' => $this->input->post('sku'),
                        'quantity' => $this->input->post('quantity'),
                        'message' => $this->input->post('quote_Message'),
                        'email' => $this->input->post('email'),
                    ];
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->ProductRequest_model->insert_entry($savedata);
                        $result = ['status' => '1','message' => 'Quote Sent Successfully!'];
                    }
                   //$result['delivery'] = date_create();
                   
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));


         }

  public function get_fat_price(){
             $this->load->library('form_validation');
             $this->load->model(array('FatPrice','Cattle_model'));
             //$this->load->model(array('ProductRequest_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('tag_id', 'Tag ID', 'required');
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('tag_id'),$checklogin['userdata']);                         
                        if(count($expense) > 0){
                            $fatprice = $this->FatPrice->get_one_by_cattle_id($expense['id']);
                               $result = ['status' => '1','result' => [] ];
                            if(count($fatprice) > 0){
                               $result = ['status' => '1','result' => $fatprice];
                            }                          
                        } else {
                              $result = ['status' => '0','result' => ['tag_id' => 'Cattle Record Not Found'] ];
                        }
                    }
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));

  }

 
 public function add_milk_income(){
             $this->load->library('form_validation');
             $this->load->model(array('FatPrice','Income_model','MilkIncentive','MilkData'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('fat_price', 'Fat Price', 'required');                    
                    $this->form_validation->set_rules('fat_quantity', 'Fat Quantity', 'required');                    
                    $this->form_validation->set_rules('snf_quantity', 'SNF Quantity', 'required');                    
                    $this->form_validation->set_rules('milk_volume', 'Milk Volume', 'required');
                    $this->form_validation->set_rules('fat_incentive_1', 'Fat Incentive 1', 'required');
                    $this->form_validation->set_rules('fat_incentive_2', 'Fat Incentive 2', 'required');
                    $this->form_validation->set_rules('snf_incentive_1', 'SNF Incentive 1', 'required');
                    $this->form_validation->set_rules('snf_incentive_2', 'SNF Incentive 2', 'required');
                    //$this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    
                    //$this->form_validation->set_rules('cattle_id[]', 'Cattle ID(s)', 'required');
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {                       

                        $fat_price = $formdata['fat_price_'];                        
                        //echo $fat_price; die;
                        $snf_price = $formdata['snf_price'];
                        $final_fat_price = $this->truncate_float($fat_price * $formdata['fat_quantity'], 2);
                        $final_snf_price = $this->truncate_float($snf_price * $formdata['snf_quantity'], 2);
                        $final_milk_price = $this->truncate_float($final_fat_price + $final_snf_price, 2);
                        $incentive_amount = !empty($formdata['incentive_amount'][0]) ? $formdata['incentive_amount'][0] : 0 ;
                        if(isset($formdata['management_charges'])){
                          $incentive_amount+=$formdata['management_charges'];
                        }
                        if(isset($formdata['maintenance_charges'])){
                          $incentive_amount+=$formdata['maintenance_charges'];
                        }

                        //echo $final_milk_price;
                        $incentive =  $formdata['milk_volume'] * $incentive_amount;
                        $user_id =  $checklogin['userdata']['id'];
                        $milk_amount = $final_milk_price  * $formdata['milk_volume'];
                        $date = date_create($this->input->post('income_date'))->format('Y-m-d');
                        //echo $formdata['milk_volume']; die;
                        $data = array(
                          'user_id' => $user_id,
                          'milk_sale_rate' => $final_milk_price,
                          'manure_sale_rate' => 0,
                          'milk_sale' => $formdata['milk_volume'],
                          'manure_sale' => 0,
                          'others' => 0,
                          'milk_incentive' => $incentive,
                          'total' => $milk_amount + $incentive,
                          'cattle_sale' => 0,
                          'income_date' => $date,                          
                        );
                        $today_income = $this->Income_model->get_income_by_date($date,$checklogin['userdata']);
                         if(count($today_income) > 0){
                             $data['milk_sale_rate']+=$today_income['milk_sale_rate'];   
                             $data['manure_sale_rate']+=$today_income['manure_sale_rate'];   
                             $data['milk_sale']+=$today_income['milk_sale'];  
                             $data['manure_sale']+=$today_income['manure_sale'];   
                             $data['cattle_sale']+=$today_income['cattle_sale']; 
                             $data['others']+=$today_income['others'];   
                             $data['total']+=$today_income['total']; 
                             $old_arr = (!empty($today_income['sold_cattle_ids'])) ? explode(',', $today_income['sold_cattle_ids']) : [] ;
                             $new_arr = (!empty($data['sold_cattle_ids'])) ? explode(',', $data['sold_cattle_ids']) : [] ;
                             $final_arr = array_merge($old_arr,$new_arr);                             
                             $data['sold_cattle_ids']= (count($final_arr) > 0) ?  implode(',',array_unique($final_arr)) : '' ; 
                             $this->Income_model->update_entry($today_income['id'],$data);
                        } else {
                            $this->Income_model->insert_entry($data);    
                        }
                        //Transfering data to Model
                        //$this->Income_model->insert_entry($data);

                        //$fatprice = $this->FatPrice->get_one_by_cattle_id($formdata['cattle_id']);                          
                        $fatdata = [
                              'fat_price'        => $formdata['fat_price'],
                              'fat_incentive_1'  => $formdata['fat_incentive_1'],
                              'fat_incentive_1'  => $formdata['fat_incentive_1'],
                              'snf_incentive_1'  => $formdata['snf_incentive_1'],
                              'snf_incentive_2'  => $formdata['snf_incentive_2'],
                              'milk_volume'      => $formdata['milk_volume'],
                              //'cattle_id' => $formdata['cattle_id'],
                           ];
                       
                        unset($formdata['incentive_quantity']);
                        unset($formdata['incentive_amount']);
                        unset($formdata['key']);
                        unset($formdata['fat_price']);
                        unset($formdata['snf_price']);
                        unset($formdata['fat_price_']);
                        unset($formdata['maintenance_charges']);
                        unset($formdata['management_charges']);
                        $formdata['incentive'] = $incentive_amount;
                        $this->MilkData->insert_entry($formdata);
                        $result = ['status' => '1','message' =>'Updated Successfully' ]; 

                    }
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
  }

     function daily_report(){
              $fileType = 'Excel5';              
              $this->load->model(array('Income_model','User_model'));
              //load our new PHPExcel library
              $this->load->library('excel');
              $users = $this->User_model->get_all();
              foreach($users as $user){
                    $fileName = APPPATH.'uploads/daily_report'.$user['id'].'.xls';
                    $objPHPExcel->setActiveSheetIndex(0);
                    $rowCount = 1;
                    $objPHPExcel->getActiveSheet()->setTitle('Weekly Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Milk Sale Price');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Milk Volume');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Manuare Sale Price');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Manuare Volume');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Others');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Total');      
                    $income = $this->Income_model->get_all_today($user);
                    $grand_total = 0;
                    if(!empty($income)){
                        foreach($income as $i){
                           $rowCount++;
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i['income_date'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['milk_sale_rate'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['milk_sale'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['manure_sale_rate'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $i['manure_sale'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['others'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['total'] );
                           $grand_total+=$i['total'];
                        }
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Grand Total:');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $grand_total);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                    $objWriter->save($fileName); 
                    chmod($fileName, 0777); 
                    $response['name'] = $user['name'];
                    $response['date'] = date('Y-m-d');
                    $pre_date = date("Y-m-d" , strtotime("-1 day") );
                    $this->sendmail('emails/daily_report',$response,$user,'Daily Income Report('.$pre_date.')',[$fileName]);
                    $objPHPExcel->disconnectWorksheets();
                    $objPHPExcel->createSheet();
              }

              $result = ['status' => '1'];
               return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));  
    }

function weekly_milk_report(){
              $fileType = 'Excel5';              
              $this->load->model(array('DailyMilkData','User_model','Cattle_model','History_model','DeliveryData'));
              //load our new PHPExcel library
              $this->db->where('is_on_testing','Yes');              
              $this->load->library('excel');
              $users = $this->User_model->get_all();
              //print_r($users); die;
              $date = new \DateTime();
            $week = $date->format("W");
            $year = $date->format("o");
            $last_week = $this->etStartAndEndDate($week-1,$year);
            $current_week = $this->etStartAndEndDate($week,$year);
            $last_week_start =date_create($last_week['week_start'])->format('d-m-Y');            
            $last_week_end = date_create($last_week['week_end'])->format('d-m-Y'); 
            $objPHPExcel = $this->excel;
            foreach($users as $user){
                    //print_r($user);
                    $fileName = APPPATH.'uploads/weekly_report'.$user['id'].'.xls';
                    $rowCount = 1;
                    //FORMATTING
                    $this->cellColor("A$rowCount:Z$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                    $this->setcellborder("A$rowCount:Z$rowCount",'00000',$objPHPExcel->getActiveSheet());
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:Z$rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:Z$rowCount")->getFont()->setSize(7);
                    //FORMATTING
                    $objPHPExcel->getActiveSheet()->setTitle("Milk Report");
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Sr.No');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Animal Tag ID');
                    
                    $first_day_of_week = $last_week['week_start'];
                    $formated_first_day = date_create($first_day_of_week)->format('d-m-Y');
                    $_formated_first_day = date_create($first_day_of_week)->format('Y-m-d');
                    $this->meargecells($objPHPExcel->getActiveSheet(),"D$rowCount:F$rowCount","D$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_first_day.'',true);
                    $first_day_of_week = date_create($first_day_of_week)->format('d-m-Y');
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+1 day");
                    $formated_second_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_second_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"G$rowCount:I$rowCount","G$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_second_day.'',true);
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+2 day");
                    $formated_third_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_third_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"J$rowCount:L$rowCount","J$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_third_day.'',true);
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+3 day");
                    $formated_forth_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_forth_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"M$rowCount:O$rowCount","M$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_forth_day.'',true);
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+4 day");
                    $formated_fifth_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_fifth_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"P$rowCount:R$rowCount","P$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_fifth_day.'',true);
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+5 day");
                    $formated_sixth_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_sixth_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"S$rowCount:U$rowCount","S$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_sixth_day.'',true);
                    $nex_day_of_week = new DateTime($first_day_of_week);
                    $nex_day_of_week->modify("+6 day");
                    $formated_seventh_day = $nex_day_of_week->format('d-m-Y');                    
                    $_formated_seventh_day = $nex_day_of_week->format('Y-m-d');                    
                    $this->meargecells($objPHPExcel->getActiveSheet(),"V$rowCount:X$rowCount","V$rowCount",'Morning,Afternoon,Evening Milk on '.$formated_seventh_day.'',true);

                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(9); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15); 
                    $objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, 'Total Milk');
                    $objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, 'Average Weekly Milk');

                    //#f49242
                    $this->db->where('is_deleted','No');
                    $this->db->where('is_animal_on_dry','No');
                    $this->db->where('type <>','Calf');
                    $this->db->where('type <>','Heifer');
                    $mycattles = $this->Cattle_model->_get_all_cattle($user);
                    $cattle_ids = array_column($mycattles, 'id');                    
                    $last_week_milk ='';
                    $sno = 1;
                    $grand_total = 0;
                    if(count($mycattles) > 0){
                        //print_r($mycattles); die;
                       foreach($mycattles as $cattle_id){
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(20);
                        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sno++ );        
                        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $cattle_id['tag_id'] );        
                        //FIRST DAY MILK  
                          $first_day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_first_day);
                          $week_total = 0; 
                          $morning = $afternoon = $evening = 0;
                          if(count($first_day_milk) >0){
                                $morning = $first_day_milk['morning'];
                                $afternoon = $first_day_milk['afternoon'];
                                $evening = $first_day_milk['evening'];
                          } //if(count($first_day_milk)
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $evening );  
                        //SECOND DAY
                        $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_second_day);
                          $week_total = 0; 
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          } //if(count($day_milk) >0)
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $evening );
                        //THIRD DAY MILK
                         $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_third_day);                      
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          } //if(count($day_milk) >0)
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $evening );    
                         //FORTH DAY MILK
                         $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_forth_day);
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          }
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $evening ); 
                          //FIFTH DAY MILK
                         $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_fifth_day);
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          }
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $evening );
                        //SIXTH DAY MILK
                        $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_sixth_day);
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          }
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $evening );
                        //SEVENTH DAY
                        $day_milk = $this->DailyMilkData->get_one_by_cattle_and_date($cattle_id['id'],$_formated_seventh_day);
                          $morning = $afternoon = $evening = 0;
                          if(count($day_milk) >0){
                                $morning = $day_milk['morning'];
                                $afternoon = $day_milk['afternoon'];
                                $evening = $day_milk['evening'];
                          }
                          $week_total+=($morning+$afternoon+$evening);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $morning );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $afternoon );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $evening );

                           $objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $week_total );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $this->truncate_float(($week_total / 7), 2)  );
                        $grand_total+=$week_total;
                       } //foreach($mycattles as $cattle_id)
                    } //count mycattles

                    $rowCount++;                                      
                    $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:F$rowCount","A$rowCount",'Grand Total',true);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $grand_total);
                    
                    $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                    $this->setcellborder("A$rowCount:H$rowCount",'00000',$objPHPExcel->getActiveSheet());
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
             // } //foreach($users as $user){
              
              /*===================INCOME AND EXPENSE SHEET=========================*/
              $inc=0;
              //$users = $this->User_model->get_all();
              $date = new \DateTime();
              $week = $date->format("W");
              $year = $date->format("o");
              $last_week = $this->etStartAndEndDate($week-1,$year);
              $current_week = $this->etStartAndEndDate($week,$year);
              $last_week_start =date_create($last_week['week_start'])->format('d-m-Y');            
              $last_week_end = date_create($last_week['week_end'])->format('d-m-Y'); 
              $objWorkSheet = $objPHPExcel->createSheet($inc); //Setting index when creating           
              $objPHPExcel->setActiveSheetIndex($inc);
              $objPHPExcel->getActiveSheet()->setTitle("Income and Expense Report");
                //foreach($users as $user){
                      $rowCount = 1;
                      //FORMATTING
                      $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:M$rowCount","A$rowCount",'Income Details',true);
                      $this->cellColor("A$rowCount",'777474',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                      $rowCount++;
                      $this->cellColor("A$rowCount:M$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                      $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:M$rowCount")->getFont()->setBold( true );
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:M$rowCount")->getFont()->setSize(8);
                      //FORMATTING
                      //$objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Sr.No');                    
                     $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Milk Sale Price');
                      $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Milk Sale Volume');
                      $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Milk Incentive');
                      $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Total Milk Income');

                      $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Manuare Sale Rate');
                      $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Manuare Sale Volume');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Total Manure Income');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Others Income');
                      $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, 'Cattle Sale Income');
                      $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'Sold Cattle Tag IDs');
                      $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, 'Date');
                      $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, 'Total');                     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5); 
                      //$income = $this->Income_model->get_all_today($user);                    
                      $this->db->where('income_date >=',$last_week['week_start']);
                      $this->db->where('income_date <=', $last_week['week_end']);  
                      $last_week_income = $this->Income_model->get_all($user);
                      
                      //print_r($last_week_income); die;
                      $grand_total = 0;
                      if(!empty($last_week_income)){
                          $sno = 1;
                          $grand_total = 0;
                          foreach($last_week_income as $i){
                             $rowCount++;
                             $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(20);
                             $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sno++ );        
                             $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getAlignment()->setIndent(1);                           
                             $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['milk_sale_rate'] );        
                             $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['milk_sale'] );        
                             $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['milk_incentive']);        
                             $total_milk_income = (($i['milk_sale_rate'] * $i['milk_sale']) + $i['milk_incentive']);
                             $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $total_milk_income);        
                             $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['manure_sale_rate'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['manure_sale'] );
                             $total_manure_income = ($i['manure_sale_rate'] * $i['manure_sale']);
                             $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $total_manure_income );
                             $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $i['others'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $i['cattle_sale'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $i['sold_cattle_ids'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, date_create($i['income_date'])->format('d-m-Y') );                               
                             $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $i['total'] );
                             $grand_total+=$i['total'];
                             $this->setcellborder("A$rowCount:L$rowCount",'00000',$objPHPExcel->getActiveSheet());
                          } // foreach($last_week_income as $i)
                      } // if(!empty($last_week_income))
                      $rowCount++;                                      
                      $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:L$rowCount","A$rowCount",'Grand Total',true);
                      $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $grand_total);                    
                      $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                      
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                      
                      $rowCount++;                        
                      $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:L$rowCount","A$rowCount",'Average Income:',true);
                      $avg =  $this->truncate_float(($grand_total / 7), 2);
                      $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $avg );
                      $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                      //==================EXPENSE DETAILS=====================================//
                      $rowCount++;                        
                      $rowCount++;                        
                      //CREATING HEADING
                      $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:O$rowCount","A$rowCount",'Expense Details',true);
                      $this->cellColor("A$rowCount",'777474',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                      //CREATING TH
                      $rowCount++;
                      $this->cellColor("A$rowCount:O$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                      $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:O$rowCount")->getFont()->setBold( true );
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:O$rowCount")->getFont()->setSize(8);
                      //FORMATTING
                      //$objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Sr.No');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Salary');
                      $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Green Fodder');
                      $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Dry Fodder');
                      $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Concentrate');
                      $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Electricity');
                      $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Medicine');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Artificial Insemination');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Machines Maintenance');
                      $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, 'Diesel');
                      $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'Farm Milk Consumption');
                      $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, 'Cattle Purchase');
                      $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, 'Purchased Cattle Tag IDs');
                      $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, 'Date');
                      $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, 'Total');                     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(5); 
                      $this->db->where('expense_date  >=',$last_week['week_start']);
                      $this->db->where('expense_date  <=', $last_week['week_end']);  
                      $last_week_expense = $this->Expense_model->get_all($user);
                      //print_r($last_week_expense); die;
                      $grand_total_expense = 0;
                      if(!empty($last_week_expense)){
                          $sno = 1;
                          $grand_total_expense = 0;
                          foreach($last_week_expense as $i){
                             $rowCount++;
                             $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(20);
                             $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sno++ );        
                             $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getAlignment()->setIndent(1);                           
                             $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['salary'] );        
                             $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['green_fodder'] );        
                             $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['dry_fodder']);         
                             $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $i['concentrate']);         
                             $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['electricity'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['medicine'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $i['atrificial_insemination'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $i['machines_maintenance'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $i['diesel'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $i['farm_milk_consumption'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $i['cattle_purchase'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $i['purchased_cattle_tag_id'] );
                             $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, date_create($i['expense_date'])->format('d-m-Y') );                               
                             $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $i['total'] );
                             $grand_total_expense+=$i['total'];
                             $this->setcellborder("A$rowCount:L$rowCount",'00000',$objPHPExcel->getActiveSheet());
                          } //foreach($last_week_expense as $i)
                      } //if(!empty($last_week_expense))

                      $rowCount++;                                      
                      $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:N$rowCount","A$rowCount",'Grand Total',true);
                      $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $grand_total_expense);                    
                      $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                      
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                      
                      $rowCount++;                        
                      $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:N$rowCount","A$rowCount",'Average Expense:',true);
                      $avg =  $this->truncate_float(($grand_total_expense / 7), 2);
                      $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $avg );
                      $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );

                      //==================EXPENSE DETAILS=====================================//
                      
                      //==================Final Report=====================================//
                      //CREATING HEADING
                      $rowCount++;                        
                      $rowCount++; 
                      $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$rowCount:B$rowCount","A$rowCount",'Final Report',true);
                      $this->cellColor("A$rowCount",'777474',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                      $rowCount++; 
                      //$this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                      $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setSize(8);
                      //FORMATTING                   
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Total Income');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $grand_total );
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

                      $rowCount++; 
                      //$this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                      $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setSize(8);
                      //FORMATTING                   
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Total Expenses');                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $grand_total_expense );
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                      $profit_or_loss = ($grand_total > $grand_total_expense) ? 'Profit' : (($grand_total ==  $grand_total_expense) ? 'No Profit/Loss' : 'Loss');
                      $cell_color = ($grand_total > $grand_total_expense) ? '4f9157' : (($grand_total ==  $grand_total_expense) ? 'eaf924' : 'f92525');
                      $amount = ($grand_total > $grand_total_expense) ? ($grand_total - $grand_total_expense) : ($grand_total_expense - $grand_total);

                      $rowCount++; 
                      $this->cellColor("A$rowCount:B$rowCount",$cell_color,$objPHPExcel->getActiveSheet());  
                      $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                      $styleArray = array(
                              'font'  => array(
                                  'bold'  => true,
                                  'color' => array('rgb' => 'ffffff'),
                                  'size'  => 8,
                                  'name'  => 'Verdana'
                              ));
                      $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->applyFromArray($styleArray);
                      //FORMATTING                   
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $profit_or_loss);                    
                      $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $amount );
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                      //==================Final Report=====================================//

               // } //foreach($users as $user){
                /*****************************INCOME AND EXPENSE REPORT GENERATION ENDED*****************************************/
                  $inc++;

              /**************************************************Herd Report******************************/
                $date = new \DateTime();
              $week = $date->format("W");
              $year = $date->format("o");
              $last_week = $this->etStartAndEndDate($week-1,$year);
              $current_week = $this->etStartAndEndDate($week,$year);
              $last_week_start = date_create($last_week['week_start'])->format('d-m-Y');            
              $last_week_end = date_create($last_week['week_end'])->format('d-m-Y');            
              $objWorkSheet = $objPHPExcel->createSheet($inc); //Setting index when creating           
              $objPHPExcel->setActiveSheetIndex($inc);
                //foreach($users as $user){

                      $this->rowCount = 1;
                      //FORMATTING
                      $event_arr = ['heat','ai','first_pd','second_pd','dry','steam_up','delivery']; 
                      $index = 0;
                      $this->generate_excel_eventwise($event_arr,$objPHPExcel,$this->rowCount,$last_week,$user,$index,$current_week,'last_week','No');
                      $this->rowCount++;
                      $this->rowCount++;
                      $this->rowCount++;
                      $this->rowCount++;
                      $objPHPExcel = $objPHPExcel;
                      $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $this->meargecells($objPHPExcel->getActiveSheet(),"A$this->rowCount:E$this->rowCount","A$this->rowCount","Last Week Calves Details (From $last_week_start  to $last_week_end)",true);
                      $this->cellColor("A$this->rowCount",'777474',$objPHPExcel->getActiveSheet()); 
                      $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(30);
                      $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount")->getFont()->setBold( true );
                      $this->rowCount++;
                      $this->cellColor("A$this->rowCount:E$this->rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                      $this->setcellborder("A$this->rowCount:E$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                      $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(25);
                      $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:E$this->rowCount")->getFont()->setBold( true );
                      $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:E$this->rowCount")->getFont()->setSize(8);
                      //FORMATTING
                      $objPHPExcel->getActiveSheet()->setTitle("Herd Activity Report");
                      $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, 'Sr.No');                                                           
                      $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, 'Date');
                      $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, 'Male Calves');
                      $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount, 'Female Calves');
                      $objPHPExcel->getActiveSheet()->SetCellValue('E'.$this->rowCount, 'Dead Calves');
                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50); 
                      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30); 
                      
                     
                      $mycattles = $this->Cattle_model->get_all_cattle_ids($user);
                      $cattle_ids = array_column($mycattles, 'id'); 
                      $last_week_delivery_data = '';
                      if(count($cattle_ids) > 0){
                          $this->db->where('delivery_date  >=',$last_week['week_start']);
                          $this->db->where('delivery_date  <=', $last_week['week_end']);  
                          $last_week_delivery_data = $this->DeliveryData->get_all_by_cattle_ids($cattle_ids);     
                      }
                      
                      
                      if(!empty($last_week_delivery_data)){
                          $sno = 1;
                          $grand_total = 0;
                          foreach($last_week_delivery_data as $i){
                              //echo $this->rowCount;
                             $this->rowCount++;
                             $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(20);
                             $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, $sno++ );        
                             $objPHPExcel->getActiveSheet()->getStyle('A'.$this->rowCount)->getAlignment()->setIndent(1);                           
                             $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, date_create($i['delivery_date'])->format('d-m-Y') );        
                             $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, $i['male_count']);        
                             $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount, $i['female_count']);        
                             $objPHPExcel->getActiveSheet()->SetCellValue('E'.$this->rowCount, $i['dead_count']);                                   
                             $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                          }
                      } //if(!empty($last_week_delivery_data))

                    //print_r($user); die;  
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                    $objWriter->save($fileName); 
                    chmod($fileName, 0777); 
                    $response['name'] = $user['name'];
                    $response['date'] = date('Y-m-d');
                    $pre_date = date("Y-m-d" , strtotime("-1 day") );
                    $subject = "DAWO: Weekly Report (From $last_week_start  to $last_week_end)";
                    $this->sendmail('emails/daily_report',$response,$user,$subject,[$fileName]);
                    //echo "Weekly Report"; die;
                    $objPHPExcel->disconnectWorksheets();
                    $objPHPExcel->createSheet();

                    die("done");
                } //User foreach

              /**************************************************Herd Report******************************/
                   

              /*====================================================*/
              //die("done");
              $result = ['status' => '1'];
               return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));  
    }

    function weekly_income_report(){
              $fileType = 'Excel5';              
              $this->load->model(array('DailyMilkData','User_model','Cattle_model','Income_model','Expense_model'));
              //load our new PHPExcel library
              //$this->db->where('is_on_testing','Yes');              
              $this->load->library('excel');
              $users = $this->User_model->get_all();
              $date = new \DateTime();
            $week = $date->format("W");
            $year = $date->format("o");
            $last_week = $this->etStartAndEndDate($week-1,$year);
            $current_week = $this->etStartAndEndDate($week,$year);
            $last_week_start =date_create($last_week['week_start'])->format('d-m-Y');            
            $last_week_end = date_create($last_week['week_end'])->format('d-m-Y'); 
             // print_r($users); die;
              foreach($users as $user){
                    $fileName = APPPATH.'uploads/weekly_income_report'.$user['id'].'.xls';
                    $objPHPExcel->getActiveSheet(0);
                    $rowCount = 1;
                    //FORMATTING
                     $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:M$rowCount","A$rowCount",'Income Details',true);
                    $this->cellColor("A$rowCount",'777474',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                    $rowCount++;
                    $this->cellColor("A$rowCount:M$rowCount",'f49242',$objPHPExcel);  
                    $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:M$rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:M$rowCount")->getFont()->setSize(8);
                    //FORMATTING
                    $objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Sr.No');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Milk Sale Price');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Milk Sale Volume');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Milk Incentive');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Total Milk Income');

                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Manuare Sale Rate');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Manuare Sale Volume');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Total Manure Income');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Others Income');
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, 'Cattle Sale Income');
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'Sold Cattle Tag IDs');
                    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, 'Total');                     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5); 
                    //$income = $this->Income_model->get_all_today($user);                    
                    $this->db->where('income_date >=',$last_week['week_start']);
                    $this->db->where('income_date <=', $last_week['week_end']);  
                    $last_week_income = $this->Income_model->get_all($user);
                    
                    //print_r($last_week_income); die;
                    $grand_total = 0;
                    if(!empty($last_week_income)){
                        $sno = 1;
                        $grand_total = 0;
                        foreach($last_week_income as $i){
                           $rowCount++;
                           $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(20);
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sno++ );        
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getAlignment()->setIndent(1);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['milk_sale_rate'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['milk_sale'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['milk_incentive']);        
                           $total_milk_income = (($i['milk_sale_rate'] * $i['milk_sale']) + $i['milk_incentive']);
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $total_milk_income);        
                           $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['manure_sale_rate'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['manure_sale'] );
                           $total_manure_income = ($i['manure_sale_rate'] * $i['manure_sale']);
                           $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $total_manure_income );
                           $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $i['others'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $i['cattle_sale'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $i['sold_cattle_ids'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, date_create($i['income_date'])->format('d-m-Y') );                               
                           $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $i['total'] );
                           $grand_total+=$i['total'];
                           $this->setcellborder("A$rowCount:L$rowCount",'00000',$objPHPExcel);
                        }
                    }
                    $rowCount++;                                      
                    $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:L$rowCount","A$rowCount",'Grand Total',true);
                    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $grand_total);                    
                    $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                    
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                    
                    $rowCount++;                        
                    $this->setcellborder("A$rowCount:M$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:L$rowCount","A$rowCount",'Average Income:',true);
                    $avg =  $this->truncate_float(($grand_total / 7), 2);
                    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $avg );
                    $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                    //==================EXPENSE DETAILS=====================================//
                    $rowCount++;                        
                    $rowCount++;                        
                    //CREATING HEADING
                    $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:O$rowCount","A$rowCount",'Expense Details',true);
                    $this->cellColor("A$rowCount",'777474',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                    //CREATING TH
                    $rowCount++;
                    $this->cellColor("A$rowCount:O$rowCount",'f49242',$objPHPExcel);  
                    $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:O$rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:O$rowCount")->getFont()->setSize(8);
                    //FORMATTING
                    $objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Sr.No');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Salary');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Green Fodder');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Dry Fodder');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Concentrate');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Electricity');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Medicine');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Artificial Insemination');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Machines Maintenance');
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, 'Diesel');
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'Farm Milk Consumption');
                    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, 'Cattle Purchase');
                    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, 'Purchased Cattle Tag IDs');
                    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, 'Total');                     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(5); 
                    $this->db->where('expense_date  >=',$last_week['week_start']);
                    $this->db->where('expense_date  <=', $last_week['week_end']);  
                    $last_week_expense = $this->Expense_model->get_all($user);
                    //print_r($last_week_expense); die;
                    $grand_total_expense = 0;
                    if(!empty($last_week_expense)){
                        $sno = 1;
                        $grand_total_expense = 0;
                        foreach($last_week_expense as $i){
                           $rowCount++;
                           $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(20);
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $sno++ );        
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getAlignment()->setIndent(1);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['salary'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['green_fodder'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['dry_fodder']);         
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $i['concentrate']);         
                           $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['electricity'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['medicine'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $i['atrificial_insemination'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $i['machines_maintenance'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $i['diesel'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $i['farm_milk_consumption'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $i['cattle_purchase'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $i['purchased_cattle_tag_id'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, date_create($i['expense_date'])->format('d-m-Y') );                               
                           $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $i['total'] );
                           $grand_total_expense+=$i['total'];
                           $this->setcellborder("A$rowCount:L$rowCount",'00000',$objPHPExcel);
                        }
                    }

                    $rowCount++;                                      
                    $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:N$rowCount","A$rowCount",'Grand Total',true);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $grand_total_expense);                    
                    $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                    
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                    
                    $rowCount++;                        
                    $this->setcellborder("A$rowCount:O$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:N$rowCount","A$rowCount",'Average Expense:',true);
                    $avg =  $this->truncate_float(($grand_total_expense / 7), 2);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $avg );
                    $this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );

                    //==================EXPENSE DETAILS=====================================//
                    
                    //==================Final Report=====================================//
                    //CREATING HEADING
                    $rowCount++;                        
                    $rowCount++; 
                    $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$rowCount:B$rowCount","A$rowCount",'Final Report',true);
                    $this->cellColor("A$rowCount",'777474',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount")->getFont()->setBold( true );
                    $rowCount++; 
                    //$this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                    $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setSize(8);
                    //FORMATTING                   
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Total Income');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $grand_total );
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

                    $rowCount++; 
                    //$this->cellColor("A$rowCount:B$rowCount",'f49242',$objPHPExcel);  
                    $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->getFont()->setSize(8);
                    //FORMATTING                   
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Total Expenses');                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $grand_total_expense );
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                    $profit_or_loss = ($grand_total > $grand_total_expense) ? 'Profit' : (($grand_total ==  $grand_total_expense) ? 'No Profit/Loss' : 'Loss');
                    $cell_color = ($grand_total > $grand_total_expense) ? '4f9157' : (($grand_total ==  $grand_total_expense) ? 'eaf924' : 'f92525');
                    $amount = ($grand_total > $grand_total_expense) ? ($grand_total - $grand_total_expense) : ($grand_total_expense - $grand_total);

                    $rowCount++; 
                    $this->cellColor("A$rowCount:B$rowCount",$cell_color,$objPHPExcel);  
                    $this->setcellborder("A$rowCount:B$rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'color' => array('rgb' => 'ffffff'),
                                'size'  => 8,
                                'name'  => 'Verdana'
                            ));
                    $objPHPExcel->getActiveSheet()->getStyle("A$rowCount:B$rowCount")->applyFromArray($styleArray);
                    //FORMATTING                   
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $profit_or_loss);                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $amount );
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                    //==================Final Report=====================================//

                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                    $objWriter->save($fileName); 
                    chmod($fileName, 0777); 
                    $response['name'] = $user['name'];
                    $response['date'] = date('Y-m-d');
                    $pre_date = date("Y-m-d" , strtotime("-1 day") );
                    $subject = "DAWO: Weekly Income and Expense Report (From $last_week_start  to $last_week_end)";
                    $this->sendmail('emails/daily_report',$response,$user,$subject,[$fileName]);
                    //echo "Weekly Report"; die;
                    $objPHPExcel->disconnectWorksheets();
                    $objPHPExcel->createSheet();
              }

              $result = ['status' => '1'];
               return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));  
    }

       function weekly_herd_report(){
              $fileType = 'Excel5';              
              $this->load->model(array('User_model','Cattle_model','History_model','DeliveryData'));
              //load our new PHPExcel library
              //$this->db->where('is_on_testing','Yes');              
              $this->load->library('excel');
              $users = $this->User_model->get_all();
            $date = new \DateTime();
            $week = $date->format("W");
            $year = $date->format("o");
            $last_week = $this->etStartAndEndDate($week-1,$year);
            $current_week = $this->etStartAndEndDate($week,$year);
            $last_week_start = date_create($last_week['week_start'])->format('d-m-Y');            
            $last_week_end = date_create($last_week['week_end'])->format('d-m-Y');            
             // print_r($users); die;
              foreach($users as $user){
                    $fileName = APPPATH.'uploads/weekly_herd_report'.$user['id'].'.xls';
                    $objPHPExcel->setActiveSheetIndex(0);
                    $this->rowCount = 1;
                    //FORMATTING
                    $event_arr = ['heat','ai','first_pd','second_pd','dry','steam_up','delivery']; 
                    $index = 0;
                    $this->generate_excel_eventwise($event_arr,$objPHPExcel,$this->rowCount,$last_week,$user,$index,$current_week,'last_week','No');
                    $this->rowCount++;
                    $this->rowCount++;
                    $this->rowCount++;
                    $this->rowCount++;
                    $objPHPExcel = $objPHPExcel;
                    $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel);
                    $this->meargecells($objPHPExcel,"A$this->rowCount:E$this->rowCount","A$this->rowCount","Last Week Calves Details (From $last_week_start  to $last_week_end)",true);
                    $this->cellColor("A$this->rowCount",'777474',$objPHPExcel); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount")->getFont()->setBold( true );
                    $this->rowCount++;
                    $this->cellColor("A$this->rowCount:E$this->rowCount",'f49242',$objPHPExcel);  
                    $this->setcellborder("A$this->rowCount:E$this->rowCount",'00000',$objPHPExcel);
                    $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:E$this->rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:E$this->rowCount")->getFont()->setSize(8);
                    //FORMATTING
                    $objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, 'Sr.No');                                                           
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, 'Male Calves');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount, 'Female Calves');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$this->rowCount, 'Dead Calves');
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50); 
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30); 
                    
                   
                    $mycattles = $this->Cattle_model->get_all_cattle_ids($user);
                    $cattle_ids = array_column($mycattles, 'id'); 
                    $last_week_delivery_data = '';
                    if(count($cattle_ids) > 0){
                        $this->db->where('delivery_date  >=',$last_week['week_start']);
                        $this->db->where('delivery_date  <=', $last_week['week_end']);  
                        $last_week_delivery_data = $this->DeliveryData->get_all_by_cattle_ids($cattle_ids);     
                    }
                    
                    
                    if(!empty($last_week_delivery_data)){
                        $sno = 1;
                        $grand_total = 0;
                        foreach($last_week_delivery_data as $i){
                            //echo $this->rowCount;
                           $this->rowCount++;
                           $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(20);
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, $sno++ );        
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$this->rowCount)->getAlignment()->setIndent(1);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, date_create($i['delivery_date'])->format('d-m-Y') );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, $i['male_count']);        
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount, $i['female_count']);        
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$this->rowCount, $i['dead_count']);                                   
                           $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel);
                        }
                    }

                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                    $objWriter->save($fileName); 
                    chmod($fileName, 0777); 
                    $response['name'] = $user['name'];
                    $response['date'] = date('Y-m-d');
                    $pre_date = date("Y-m-d" , strtotime("-1 day") );
                    $subject = "DAWO: Weekly Herd Activity Report (From $last_week_start  to $last_week_end)";
                    //echo $subject; die;
                    //$this->sendmail('emails/daily_report',$response,$user,$subject,[$fileName]);
                    $this->sendmail('emails/daily_report',$response,$user,$subject,[$fileName]);

                    $objPHPExcel->disconnectWorksheets();
                    $objPHPExcel->createSheet();
              }
              //die("Done");
              $result = ['status' => '1'];
               return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));  
    }








    function monthly_report(){
              $fileType = 'Excel5';              
              $this->load->model(array('Income_model','User_model'));
              //load our new PHPExcel library
              $this->load->library('excel');
              $users = $this->User_model->get_all();
              // read data to active sheet
              foreach($users as $user){
                    $fileName = APPPATH.'uploads/monthly_report'.$user['id'].'.xls';
                    $objPHPExcel->setActiveSheetIndex(0);
                    //name the worksheet
                    $rowCount = 1;
                    $objPHPExcel->getActiveSheet()->setTitle('Monthly Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Milk Sale Price');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Milk Volume');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Manuare Sale Price');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Manuare Volume');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Others');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Total');      
                    $income = $this->Income_model->get_all_current_month($user);
                    
                    $grand_total = 0;
                    if(!empty($income)){

                        foreach($income as $i){
                           $rowCount++;
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i['income_date'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $i['milk_sale_rate'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $i['milk_sale'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $i['manure_sale_rate'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $i['manure_sale'] );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $i['others'] );
                           $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $i['total'] );
                           $grand_total+=$i['total'];
                        }
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, '');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Grand Total:');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $grand_total);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
                    $objWriter->save($fileName); 
                    chmod($fileName, 0777); 
                    $response['name'] = $user['name'];
                    $response['date'] = date('Y-m-d');
                    $month = date('M', strtotime('-1 months'));
                    $year = (int) date('Y', strtotime('-1 months')); 
                    $this->sendmail('emails/daily_report',$response,$user,'Monthly Income Report('.$month.'  '.$year.')',[$fileName]);                    
                    $objPHPExcel->disconnectWorksheets();
                    $objPHPExcel->createSheet();
              }

              $result = ['status' => '1'];
               return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($result));  
    }






    public function reset_password(){
        $this->load->model(array('User_model'));
         $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email ID', 'required');
        if ($this->form_validation->run() == FALSE) {
            $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
        } else {
          $user = $this->User_model->get_one_by_email($this->input->post('email'));
          if(count($user) > 0){
            $response = $this->User_model->reset_password($user);
            $response['name'] = $user['name'];
            $this->sendmail('emails/forgot_password',$response,$user,'Change Password',[]);
            $result = ['status' => '1','message' => 'Password has been sent to your email. Kindly check your email.'];
          } else {            
            $result = ['status' => '0','reason' => 'validation' , 'errors' => ['email' => 'No record found']  ];
          }
        }

        return $this->output
          ->set_content_type('application/json')    
          ->set_status_header(200)
          ->set_output(json_encode($result));  
    }

    public function add_milk(){
             $this->load->library('form_validation');
             $this->load->model(array('FatPrice','Cattle_model','DailyMilkData'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('tag_id[]', 'Tag ID(s)', 'required');
                    $this->form_validation->set_rules('morning[]', 'Morning Milkdata', 'required');
                    $this->form_validation->set_rules('afternoon[]', 'Afternoon Milkdata', 'required');
                    $this->form_validation->set_rules('evening[]', 'Evening Milkdata', 'required');
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $today = date('Y-m-d');
                       if(count($formdata['tag_id']) > 0){
                           for($i=0; $i < count($formdata['tag_id']); $i++) { 
                            //IF WE FOUND CATTLE RECORD AS PER TAG ID AND CURRENT LOGGED IN USER
                            $cattle = $this->Cattle_model->get_one_from_tag_and_user($formdata['tag_id'][$i],$checklogin['userdata']);
                            if(count($cattle) > 0){
                              // IF WE FOUND RECORD FOR THE CATTLE
                              $today_milk_record = $this->DailyMilkData->get_one_by_cattle_and_date($cattle['id'],$today);
                              ////print_r($today_milk_record); die;
                              $data = [
                                    'milk_date' => $today,
                                    'cattle_id' => $cattle['id'],
                                    'morning' => (isset($formdata['morning'][$i])) ? $formdata['morning'][$i] : 0 ,
                                    'afternoon' => (isset($formdata['afternoon'][$i])) ? $formdata['afternoon'][$i] : 0,
                                    'evening' => (isset($formdata['evening'][$i])) ? $formdata['evening'][$i] : 0,
                                    'total' => $formdata['morning'][$i] + $formdata['afternoon'][$i] + $formdata['evening'][$i],
                                ];
                              if(count($today_milk_record) == 0){
                                // IF THERE IS NO RECORD FOR THE MILK RECORD FOR THE CURRENT CATTLE                             
                                // WE WILL BE INSERTING RECORD IN THE DAILY MILK DATA RECORD TABLE   
                                $this->DailyMilkData->insert_entry($data);
                                $cattle_data =[
                                    'per_day_milk' => $cattle['per_day_milk'] + $data['total']
                                ];
                                $this->Cattle_model->update_entry($cattle['id'],$cattle_data);
                              } else {
                                $this->DailyMilkData->update_entry( $data,$today_milk_record['id'] );
                                // IF THERE IS ALREADY RECORD IN THE TABLE WE WILL UPDATE THE RECORD.
                                $minus_today_milk = $cattle['per_day_milk'] - $today_milk_record['total'];
                                $cattle_data =[
                                    'per_day_milk' => $minus_today_milk + $data['total']
                                ];
                                $this->Cattle_model->update_entry($cattle['id'],$cattle_data);
                              }
                            }
                           }
                       }
                       $result = ['status' => '1' ,'message' => 'Milk Data Updated Successfully!'];                         
                    }
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
    }

    public function get_milk_data(){
            $this->load->library('form_validation');
             $this->load->model(array('FatPrice','Cattle_model','DailyMilkData'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                 $this->db->where('is_sold','No');   
                 $this->db->where('is_dead','No');   
                 $cattles = $this->Cattle_model->get_all_cattles($checklogin['userdata']);
                 $final_arr= [];
                 $today = date('Y-m-d');
                 if(count($cattles) > 0  && (is_array($cattles))){
                   foreach($cattles as $cattle){
                        ////print_r($cattle); die;
                       $today_milk_record = $this->DailyMilkData->get_one_by_cattle_and_date($cattle['id'],$today);
                       $cattle['milk_data'] = (count($today_milk_record) > 0) ? $today_milk_record : [];
                       //BEFORE SENDING DATA TO ADD MILK FOR A PARTICULAR CATTLE
                       //WE WILL BE CHECKING THAT CATTLE SHOULD NOT BE ON DRY OR 
                       //ON EVENTS AFTER DRY
                       //IF SO , NO MILKDATA CAN BE ADDED FOR THAT PARITCULAR CATTLE
                       if($cattle['is_animal_on_dry'] == 'No'){
                            $final_arr[] = $cattle;
                       }
                   }
                 }
                 $result = ['status' => '1' , 'result' => $final_arr ];
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
    }


    public function add_delivery_data(){
             $this->load->library('form_validation');
             $this->load->model(array('DeliveryData','Cattle_model','DailyMilkData','UpcomingEvent_model','BreedingProcess'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                    $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
                    $this->form_validation->set_rules('male_count', 'Number of Males', 'required');
                    $this->form_validation->set_rules('female_count', 'Number of Females', 'required');
                    $this->form_validation->set_rules('alive_count', 'Number of Alive Calves', 'required');
                    $this->form_validation->set_rules('dead_count', 'Number of Dead Calves', 'required');
                    $this->form_validation->set_rules('del_date', 'Delivery Date', 'required');
                    if ($this->form_validation->run() == FALSE) {
                        $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array()];
                    } else {
                        //$cattle = $this->Cattle_model->get_one_from_tag_and_user($this->input->post('tag_id'),$checklogin['userdata']);
                        $cattle = $this->Cattle_model->get_one($formdata['cattle_id']);
                        
                        $delivery_event = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id('delivery',$cattle['id']);
                        /*if($formdata['cattle_id']  == 53){
                            //print_r($delivery_event); die;
                         }*/
                        if(count($cattle) > 0){
                             $data = [
                                        'cattle_id' => $cattle['id'],
                                        'male_count' => $formdata['male_count'],
                                        'female_count' => $formdata['female_count'],
                                        'alive_count' => $formdata['alive_count'],
                                        'dead_count' => $formdata['dead_count'],
                                        'born_cattle_id' => $formdata['born_cattle_id'],
                                        'delivery_date' => (!empty($formdata['del_date'])) ? date_create($formdata['del_date'])->format('Y-m-d H:i:s') : $delivery_event['event_date']
                                     ];

                                    /*if($formdata['cattle_id']  == 53){
                                        //print_r($data); die;
                                     }*/
                           $this->DeliveryData->insert_entry($data);
                           //UPDATING CALVING DATE INTO CATTLE TABLE
                           $cattle_save_data = [
                                'calving_date' => (!empty($formdata['del_date'])) ? date_create($formdata['del_date'])->format('Y-m-d H:i:s') : $delivery_event['event_date']
                           ];
                           $this->Cattle_model->update_entry($cattle['id'],$cattle_save_data);
                           //UPGRADING BREEDING PROCESS ALSO.
                           $already = $this->BreedingProcess->get_one_by_cattle_id($cattle['id']);
                           $breedingprocess_save_data = [
                                'delivery_date' =>  (!empty($formdata['del_date'])) ? date_create($formdata['del_date'])->format('Y-m-d H:i:s') : $delivery_event['event_date'] , 
                                'is_delivery_data_entered' => 'Yes',
                           ];

                           if(count($already) > 0){
                                $this->BreedingProcess->update_entry($already['id'],$breedingprocess_save_data);
                           }

                        }
                         $result = ['status' => '1','message' => 'Information Saved!'];
                    }
                
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result)); 
    }

     function aztro() {
        //$checklogin = $this->checklogin($formdata);
            $formdata = $this->input->post();          
           
            $response =  $this->post_curl('http://stellarastrology.in/api/v1/horoscope','POST',$formdata);

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response)); 
            // do anything you want with your response
            //var_dump($response); die;
    }

    function getheroscope(){
       $formdata = $this->input->post();
       $response =  $this->post_curl("http://horoscope-api.herokuapp.com/horoscope/".$formdata['duration']."/".$formdata['current_sign']."",'GET',$formdata);

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response)); 
        
    }

    function save_style(){
        $formdata = $this->input->post();
        $this->load->model(array('Data_model'));
        $this->Data_model->update_product($formdata['id'],$formdata);
    }



     public function confirm_pregnency(){
        $this->load->model(array('Cattle_model','UpcomingEvent_model','BreedingProcess','History_model'));
         $this->load->library('form_validation');
        //$this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('is_pregnant', 'Is Cattle Pregnant', 'required');
        $this->form_validation->set_rules('cattle_id', 'Cattle ID', 'required');
        if ($this->form_validation->run() == FALSE) {
            $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
        } else {
           $cattle_record = $this->Cattle_model->get_one($this->input->post('cattle_id')); 
           if(count($cattle_record) > 0){
                $cattle =$cattle_record['id'];
                if($this->input->post('is_pregnant') == 'Yes'){
                     $formdata = $this->input->post();    
                     $arr = [];
                     $arr[] = $formdata['cattle_id'];
                     $formdata['cattle_id'] = $arr;
                } else {
                    //IF CATTLE IS NOT PREGNANT
                    $formdata = $this->input->post();    
                    $default = $this->BreedingProcess->get_one(1);
                    $already = $this->BreedingProcess->get_one_by_cattle_id($cattle);
                    if(count($already) > 0){
                        $next_heat_date = intval($already['heat_after_pd_not_successful']);
                    } else {
                        $next_heat_date = 21;
                    }
                    $u = $this->UpcomingEvent_model->find_upcomming_by_type_and_cattle_id($formdata['type'],$cattle);
                    $type = ($formdata['type'] == 'first_pd') ? 'First PD' : 'Second PD';
                    if(count($u) > 0){
                        $history_message = $type.' Done on '.date_create($u['event_date'])->format('d/m/Y');
                        $history_event_date = date_create($u['event_date'])->format('Y-m-d 00:00:00');
                        $event_date1 = new DateTime($u['event_date']);  
                        $event_date1->modify("+$next_heat_date day");                        
                    } else {
                        $event_date1 = new DateTime(date('Y-m-d'));  
                        $event_date1->modify("+$next_heat_date day");
                        $history_message = $type.' Done on '. date('d/m/Y');
                        $history_event_date = date('Y-m-d');
                    }
                    $history_event_type = $formdata['type'];
                    $history_ai_date = $already['ai_date'];                        
                    $this->UpcomingEvent_model->delete_by_type($cattle,'heat');  
                    $this->UpcomingEvent_model->delete_by_type($cattle,'ai');  
                    $this->UpcomingEvent_model->delete_by_type($cattle,'first_pd');  
                    $this->UpcomingEvent_model->delete_by_type($cattle,'second_pd'); 
                    $this->UpcomingEvent_model->delete_by_type($cattle,'dry');  
                    $this->UpcomingEvent_model->delete_by_type($cattle,'steam_up');  
                    $this->UpcomingEvent_model->delete_by_type($cattle,'delivery');                      
                    $history =  [
                                    'cattle_id' => $cattle,
                                    'event_type' => $history_event_type,
                                    'event_date' => $history_event_date,
                                    'ai_date' => $history_ai_date,
                                    'message' => $history_message
                                ];    

                    $this->History_model->insert_entry($history);                                 
                    $event_message1 = 'Heat is going to take place on '.$event_date1->format('Y-m-d');
                    $alertdata = [
                      'cattle_id' => $cattle,
                      'event_type' => 'heat',
                      'message' => $event_message1,
                      'event_date' => $event_date1->format('Y-m-d')
                    ];
                    $this->UpcomingEvent_model->insert_entry($alertdata);                   
                    //GENERATING AI EVENT 
                    $event_date1->modify("+1 day");
                    $ai_date = $event_date1->format('Y-m-d 00:00:00');
                    $event_type = 'ai';
                    $event_message = 'Artificial Insemination is going to take place on '.$event_date1->format('Y-m-d');
                    $alertdata = [
                      'cattle_id' => $cattle,
                      'event_type' => 'ai',
                      'message' => $event_message,
                      'event_date' => $event_date1->format('Y-m-d')
                    ];
                    $this->UpcomingEvent_model->insert_entry($alertdata);
                     $already['ai_date'] = $event_date1->format('Y-m-d 00:00:00');
                     if(count($already) > 0){
                         $this->BreedingProcess->update_entry($already['id'], ['ai_date' => $event_date1->format('Y-m-d 00:00:00') ]);
                     }
                    //GENERATING OTHER EVENTS
                    $this->update_event((count($already) > 0) ? $already : $default ,$event_type, $event_date1, $cattle,[],'No');     
                    //IF CATTLE IS NOT PREGNENT RESETTING VALUES IN CATTLE TABLE
                    //CALVING DATE AND PREGENCY CONFIRMATION FLAG CHANGED
                    $cattle_save_data = [
                        'calving_date' => null,
                        'is_pregnant' => 'No',
                        'ai_date' => $ai_date
                   ];
                   $this->Cattle_model->update_entry($cattle,$cattle_save_data);
                }
           }
        }
        $result = ['status' => '1','message' => 'Information Saved!'];
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($result));  
    }

    public function get_profile(){
            $this->load->library('form_validation');
             $this->load->model(array('FatPrice','Cattle_model','DailyMilkData','Deliveryaddress_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                //print_r($checklogin); die;
                 $delivery_address = $this->Deliveryaddress_model->get_one($checklogin['userdata']['id']); 
                 //print_r($delivery_address); die;                 
                 $result = ['status' => '1' , 'result' => $checklogin['userdata'] ,'delivery_address' => (isset($delivery_address['id'])) ? $delivery_address : [] ];
             }
                   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));
    }
    
    
    
    public function save_order(){
        
            $checklogin = $this->checklogin($this->input->post());            
             $this->load->model(array('Deliveryaddress_model'));
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                 
                $this->load->model('Product_model');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');
                $this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[12]');
                $this->form_validation->set_rules('phone', 'Phone', 'required|min_length[10]|max_length[12]');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
                $this->form_validation->set_rules('address_1', 'Address 1', 'required'); 
                $this->form_validation->set_rules('address_2', 'Address 2', 'required'); 
                $this->form_validation->set_rules('district', 'District', 'required'); 
                $this->form_validation->set_rules('state', 'State', 'required'); 
                $this->form_validation->set_rules('pin_code', 'Pin Code', 'required'); 
                $this->form_validation->set_rules('country', 'Country', 'required'); 
                
                if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                } else {
                    ////print_r($this->input->post()); die;
                    $order_info = [];
                    $order_info = [     
                                    'user_id' => $checklogin['userdata']['id'] ,       
                                    'delivery_pin_code' => $this->input->post('pin_code',true),     
                                    'delivery_address' => $this->input->post('address_1',true).' '.$this->input->post('address_2',true),                
                                    'address_2' => $this->input->post('address_2',true),        
                                    'address_1' => $this->input->post('address_1',true),        
                                    'district' => $this->input->post('district',true),      
                                    'state' => $this->input->post('state',true),        
                                    'country' => $this->input->post('country',true),        
                                    'ordering_name' => $this->input->post('name',true),     
                                    'ordering_email' => $this->input->post('email',true),       
                                    'ordering_phone' => $this->input->post('phone',true),       
                                    'payment_status' => 'Pending',      
                                    'payment_method' => 'card',     
                                    'order_status' =>'Not_Confirmed',       
                                  ];
                                  
                    $order_id = $this->Product_model->save_order($order_info);
                    if($order_id){
                        $productARTT = [];
                        $product_id = $quantity = $per_unit_price = $product_name = $product_description = $product_image = $order_status = $weight = $unique_key = '';
                        $subtotal = 0;
                        
                        $cart_detail = $this->input->post('cart',true);
                        $cart_detail = json_decode($cart_detail,true);
                
                        if(!empty($cart_detail)){
                            foreach($cart_detail as $detail){
                                
                                $product_id             = $detail['product_id'];    
                                $quantity               = $detail['product_quantity'];  
                                $per_unit_price         = $detail['product_price']; 
                                $product_name           = $detail['product_name'];  
                                $product_description    = $detail['product_desc'];  
                                $product_image          = $detail['product_image']; 
                                $order_status           = 'Not_Confirmed';  
                                $weight                 = $detail['product_weight'];    
                                $unique_key             = $detail['unique_key'];
                                $subtotal               += $per_unit_price; 
                                    
                              $productARTT[] = [
                                         'order_id'             => $order_id,   
                                         'product_id'           => $product_id,
                                         'quantity'             => $quantity,
                                         'per_unit_price'       => $per_unit_price,
                                         //'subtotal'               => $subtotal,
                                         'product_name'         => $product_name,
                                         'product_description'  => $product_description,
                                         'product_image'        => $product_image,
                                         'order_status'         => $order_status,
                                         'weight'               => $weight,
                                         'unique_key'           => $unique_key  
                                       ];           
                            }
                        //Saving Delivery Address
                            //print_r($checklogin); die;
                        $delivery_address = $this->Deliveryaddress_model->get_one($checklogin['userdata']['id']); 
                        
                        $save_delivery_address_arr = [
                                    'user_id' => $checklogin['userdata']['id'],
                                    'name' => $this->input->post('name',true),     
                                    'email' => $this->input->post('email',true),     
                                    'phone' => $this->input->post('phone',true),     
                                    'pin_code' => $this->input->post('pin_code',true), 
                                    'address_2' => $this->input->post('address_2',true),        
                                    'address_1' => $this->input->post('address_1',true),        
                                    'district' => $this->input->post('district',true),      
                                    'state' => $this->input->post('state',true),        
                                    'country' => $this->input->post('country',true),
                            ];
                        if(isset($delivery_address['id'])){
                            $this->Deliveryaddress_model->update_entry($delivery_address['id'], $save_delivery_address_arr); 
                        } else {                            
                            $this->Deliveryaddress_model->insert_entry($save_delivery_address_arr); 
                        }
                         if($this->Product_model->save_product_arr($productARTT)){
                             $order_amount = [];
                             $order_amount = ['amount'=>$subtotal];
                             $order_condi   = ['id'=>$order_id];
                             $this->Product_model->save_order($order_amount,$order_condi);
                             $result = ['status' => '1','message' => 'Order sumbit Successfully!','order_id'=>$order_id];                           
                         }else{
                             $result = ['status' => '0','message' => 'Sorry,Order is failed'];
                         }
                            
                        }
                        
                    }else{
                         $result = ['status' => '0','message' => 'Sorry,Order is failed'];
                    }
                                  
               
                }
                
    }
        //$result = ['status' => '1','message' => 'Order sumbit Successfully!','order_id'=> 111];
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($result)); 

    }
    
    
    
public function process_order(){
        
            $checklogin = $this->checklogin($this->input->post());
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                 
                $this->load->model('Product_model');
                $this->load->library('Crypto');
                $this->load->library('form_validation');
                $this->form_validation->set_rules('order_id', 'Order_id', 'required|callback_check_order');
          
                
                if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                } else {
                    ////print_r($this->input->post()); die;
                    $order_id = $this->input->post('order_id',true);
                    $getOrder = [];
                    $getOrder = $this->Product_model->getOrder($order_id);
                    if(!empty($getOrder)){
                        $orderData = [
                                       'tid'            => time(),
                                       'merchant_id'    => MARCHANT_ID,
                                       'order_id'       => $order_id,
                                       'amount'         => $getOrder['amount'],
                                       'currency'       => 'INR',
                                       'redirect_url'   => webapp_url.redirect_url,
                                       'cancel_url'     => webapp_url.cancel_url,
                                       'language'       => 'EN',
                                       'billing_name' => $getOrder['ordering_name'],
                                       'billing_address' => trim($getOrder['address_1'].' '.$getOrder['address_2']),
                                       'billing_city' => $getOrder['state'],
                                       'billing_state' => $getOrder['district'],
                                       'billing_zip' => $getOrder['delivery_pin_code'],
                                       'billing_country' => $getOrder['country'],
                                       'billing_tel' => $getOrder['ordering_phone'],
                                       'billing_email' => $getOrder['ordering_email'],
                                       'integration_type' => 'iframe_normal'
                                    ];
                                    
                                    //die(//print_r($orderData));
                                    $merchant_data = '';
                                    foreach ($orderData as $key => $value){
                                        $merchant_data.= $key.'='.urlencode($value).'&';
                                    }
                                    $encrypted_data= @$this->crypto->encrypt($merchant_data,LOCAL_WORKING_KEY);
                                    
                                    
                                    $result = [
                                                'status' => '1',
                                                'key_1' => $encrypted_data,
                                                'key_2'=> LOCAL_ACCESS_CODE,
                                                'action'=> LOCAL_PAYMENT_REDIRECT_URL
                                              ];                                    
                                }
        
                                  
                                
                }
                
    }
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($result)); 

    }

    public function save_profile(){
              $checklogin = $this->checklogin($this->input->post());
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                 $this->load->model(array('User_model'));
                 $this->load->library('form_validation');
                //$this->form_validation->set_rules('type', 'Type', 'required');
                $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');
                if($this->input->post('email') == $checklogin['userdata']['email']){
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');    
                } else {
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
                }
                //echo $this->input->post('phone');
                //echo "<br>";
                ////print_r($checklogin['userdata']['phone']); die;
                if($this->input->post('phone') == $checklogin['userdata']['phone']){
                     $this->form_validation->set_rules('phone', 'Mobile No.', 'required|integer',
                                array(
                                'required'      => 'You have not provided %s.',
                                
                         ));
                } else {
                     $this->form_validation->set_rules('phone', 'Mobile No.', 'required|is_unique[users.phone]|integer',
                                array(
                                'required'      => 'You have not provided %s.',
                                'is_unique'     => 'This %s already exists.'
                         ));
                }
                //Validating Mobile no. Field
               
                if ($this->form_validation->run() == FALSE) {
                    $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                } else {
                   $this->User_model->save_profile($checklogin['userdata'],[
                        'name' => $this->input->post('name'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                   ]);
                   $result = ['status' => '1','message' => 'Profile Updated!'];
                }
                
    }
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($result)); 

    }
    
    
    /*Get All Events Data*/

      public function events(){
            $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                         // $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 2;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $this->db->where('status','1');
                                $total_records = $this->Event_model->get_total();
                         
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    $this->db->where('status','1');
                                    $params["result"] = $this->Event_model->get_all();
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => []];
                                }
                    }

             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));    
            
        }




    /*Get All Parent Group Data*/

      public function get_parent_group_data(){
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->db->where(['status' =>'1','parent_id' =>'0']);
                $group_data = $this->Group_model->get_all($checklogin['userdata']);
                if(count($group_data) > 0){
                    $params =['status' => '1','result' => $group_data];            
                } else {
                    $error[]='Parent group does not exist';
                    $params =['status' => '0','errors' => $error  ];            
                } 
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));   
            
        }

        public function get_parent_groups(){
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->db->where(['status' =>'1','parent_id' =>'0']);
                $group_data = $this->Group_model->get_all($checklogin['userdata']);
                if(count($group_data) > 0){
                    $params =['status' => '1','result' => $group_data];            
                } else {
                    $error[]='Parent group does not exist';
                    $params =['status' => '1','result' => []  ];            
                } 
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));   
            
        }
        
        /*Get All Sub Group Data*/

      public function get_sub_group_data(){
          $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                ////print_r($checklogin); die;
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->db->where(['status' =>'1','parent_id' =>$this->input->post('id')]);
                        $group_data = $this->Group_model->get_all($checklogin['userdata']);
                        if(count($group_data) > 0){
                            $result =['status' => '1','result' => $group_data];            
                        } else {
                            $result =['status' => '1','result' => []];
                        } 
      //                   else {
                        //  $error[]='Sub group does not exist';
                        //  $result =['status' => '0','errors' => $error ];            
                        // }  
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));  
        }
        
        
          public function events_detail(){
          $this->load->library('form_validation');
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $this->db->where(['status' =>'1','event_id' =>$this->input->post('id')]);
                         $event_data= $this->Event_model->get_all();
                        if(count($event_data) > 0){
                            $result =['status' => '1','result' => $event_data];            
                        } else {
                            $error[]='Data does not exist';
                            $result =['status' => '0','errors' => $error ];            
                        } 
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));  
        }
        
    
        
        
         public function check_order($id){
             $getOrder = [];
             $getOrder = $this->Product_model->getOrder($id);
                if (empty($getOrder)){
                    $this->form_validation->set_message('check_order', 'Invalid order');
                    return FALSE;
                }
                else{
                    return TRUE;
                }
        }

        public function get_groups(){
            $this->load->library('form_validation');
            $this->load->model(array('Group_model'));
            $formdata = $this->input->post();
            $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $params = $checklogin;
             } else{
                $this->form_validation->set_rules('page', 'Page', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $params = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                          $user_id =  $checklogin['userdata']['id'];
                            $this->load->library('pagination');
                              $params = array();
                                $limit_per_page = 10;
                                $start_index = ($this->input->post('page') ==1 ) ? 0 : $this->input->post('page') * $limit_per_page - $limit_per_page ;
                                $total_records = $this->Group_model->get_total($formdata,$checklogin['userdata']);
                         
                                if ($total_records > 0) 
                                {
                                    // get current page records
                                    $params["status"] = '1';
                                    $params["total"] = $total_records;
                                    $params["per_page"] = $limit_per_page;
                                    $params["result"] = $this->Group_model->get_all($checklogin['userdata']);
                                    $config['base_url'] = base_url() . '/get_cattles';
                                    $config['total_rows'] = $total_records;
                                    $config['per_page'] = $limit_per_page;
                                    $this->pagination->initialize($config);
                                    // build paging links
                                    $params["links"] = $this->pagination->create_links();
                                } else {
                                    $params = ['status'=> '1','result' => [],'total' => 0 ];
                                }
                    }

             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($params));  
        }

        public function add_group(){
              $this->load->library('form_validation');
               $this->load->model(array('Group_model','Cattle_model'));
             $formdata = $this->input->post();
             ////print_r($formdata); die;
             $checklogin = $this->checklogin($formdata);
             if ($checklogin['status'] == '0') {
                    $result = $checklogin;
             } else{
                  // //print_r( $checklogin['userdata']) ; die;
                   $user_id =  $checklogin['userdata']['id'];
                    //$this->form_validation->set_rules('user_id', 'User Name', 'required');
                    if(($this->input->post('group_id') !== null) && $this->input->post('group_id') > 0 ){
                        $this->form_validation->set_rules('group_title', 'title', 'trim|required');
                    } else {
                        //$this->form_validation->set_rules('group_title', 'title', 'trim|required|is_unique[group_data.group_title]');
                        $this->form_validation->set_rules('group_title', 'title', 'trim|required');
                    }
                    
                    $this->form_validation->set_rules('sort_order', 'Sort Order', 'trim|required');
                    //Validating Address Field
                    if ($this->form_validation->run() == FALSE) {
                          $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    }  else {
                        //Setting values for tabel columns
                        //print_r($this->input->post()); die;
                        $cattle_ids = [];
                        if($this->input->post('parent_id') == 0){
                            if(!empty($this->input->post('cattle_id'))){
                               $arr = explode(',', $this->input->post('cattle_id'));
                               foreach ($arr as $key => $value) {
                                    $cattle = $this->Cattle_model->get_one_from_tag_and_user($value,$checklogin['userdata']);       
                                    $cattle_ids[] = $cattle['id'];
                                    
                                }

                            }        
                        }                       

                        $cattle_ids_string = (count($cattle_ids) > 0) ?  implode(',', $cattle_ids) : '';                        
                        //$date = date_create($this->input->post('income_date'))->format('Y-m-d');
                        $data = array(
                        'user_id' => $user_id,
                        'group_title' => $this->input->post('group_title'),
                        'group_slug' => $this->generateslug($this->input->post('group_title'),0),
                        'parent_id' => (($this->input->post('parent_id') !== null) && !empty($this->input->post('parent_id'))) ? $this->input->post('parent_id') : $this->input->post('parent_id')  ,
                        'sort_order' => $this->input->post('sort_order'),
                        'description' => (!empty($this->input->post('description'))) ? $this->input->post('description') : '',
                        'status' => 1,
                        'cattle_id' => $cattle_ids_string
                        );
                        //Transfering data to Model
                        $group_id = 0;
                        if(!empty($this->input->post('group_title'))){
                            $groupdata = $this->Group_model->get_by_title_and_user($this->input->post('group_title'),$checklogin['userdata']);
                            //print_r($groupdata); die;
                                if(count($groupdata) > 0) {
                                     if(($groupdata['group_id'] == $this->input->post('group_id') ) && ($this->input->post('group_id') > 0)) {
                                        //USER HAS EDITED THE TITLE OF THE SAME RECORD FROM HIS GROUPS
                                        $this->Group_model->update_entry($this->input->post('group_id'), $data) ;
                                        $group_id = $this->input->post('group_id');
                                        $result = ['status' => '1','message' => 'Group Updated Successfully!'];                                    
                                     } else {
                                        $result = ['status' => '0','reason' => 'validation' , 'errors' => ['group_title' => 'Group Title is already Taken!'] ];    
                                     }
                                } else {
                                     if(($this->input->post('group_id') > 0)) {
                                        //USER HAS EDITED THE TITLE OF THE SAME RECORD FROM HIS GROUPS
                                        $this->Group_model->update_entry($this->input->post('group_id'), $data) ;
                                        $group_id = $this->input->post('group_id'); 
                                        $result = ['status' => '1','message' => 'Group Updated Successfully!'];                                   
                                     } else {
                                        $group_id = $this->Group_model->insert_entry($data);
                                        $result = ['status' => '1','message' => 'Group Added Successfully!'];
                                     }
                                }
                       } else {
                            $result = ['status' => '0','reason' => 'validation' , 'errors' => ['group_title' => 'Group title can not be empty!'] ];
                       }
                                               

                        if(count($cattle_ids) > 0  && ($group_id > 0)){
                                foreach ($cattle_ids as $key => $value) {
                                       $cattle_save_data = [
                                            'parent_group' => $group_id,
                                        ];
                                        $this->Cattle_model->update_entry($value,$cattle_save_data);             
                                    }    
                            } 

                        
                        
                }
             }
          

        return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result)); 


        }

        public function get_one_group(){
            $this->load->library('form_validation');
            $this->load->model(array('Group_model','Cattle_model'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('id', 'ID', 'required|integer');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {                        
                        $expense = $this->Group_model->get_by_id($this->input->post('id'));
                         $cattle_ids = [];

                        if($expense['parent_id'] !== 0){
                            if(!empty($expense['cattle_id'])){
                               $arr = explode(',', $expense['cattle_id']);
                               foreach ($arr as $key => $value) {
                                    $cattle = $this->Cattle_model->get_one($value);       
                                    $cattle_ids[] = $cattle['tag_id'];
                                }    
                            }        
                        }
                        $expense['cattle_id'] =  implode(',', $cattle_ids);
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => $expense];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }

        public function get_page_content(){
            $this->load->library('form_validation');
            $this->load->model(array('GlobalSetting'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('type', 'Type', 'required');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense =  $this->GlobalSetting->get_one_by_key($this->input->post('type'));
                        if(count($expense) > 0){
                            $result =['status' => '1','page_content' => (count($expense) > 0) ? nl2br($expense['setting_value']) : '' ];            
                        } else {
                            $result =['status' => '1','page_content' => '' ];            
                        }
                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result));   
        }

public function get_order_status(){
     $this->load->library('form_validation');
            $this->load->model(array('Order_m'));
             $formdata = $this->input->post();
             $checklogin = $this->checklogin($formdata);             
             if ($checklogin['status'] == '0') {
                $result = $checklogin;
             } else{
                 $this->form_validation->set_rules('order_id', 'Order ID', 'required');
                 if ($this->form_validation->run() == FALSE) {
                         $result = ['status' => '0','reason' => 'validation' , 'errors' => $this->form_validation->error_array() ];
                    } else {
                        $expense =  $this->Order_m->get_by_id($this->input->post('order_id'));
                        if(count($expense) > 0){
                            $result =['status' => '1','result' => (count($expense) > 0) ? $expense : '' ];            
                        } else {
                            $result =['status' => '1','result' => [] ];            
                        }                        
                    }
             }
             return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($result)); 
}

function etStartAndEndDate($week, $year) {
  $dto = new \DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d 00:00:00');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d 23:59:59');
  return $ret;
}

function cellColor($cells,$color,$objPHPExcel){
     $objPHPExcel;

    $objPHPExcel->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

function setcellborder($cells,$color,$objPHPExcel){
  // $border_style= array('borders' => array('right' => array('style' => 
  // PHPExcel_Style_Border::BORDER_THICK,'color' => array('argb' => '766f6e'),)));
  // $sheet = $objPHPExcel;
  // $sheet->getStyle("A2:A40")->applyFromArray($border_style);

    $objPHPExcel->getStyle($cells)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => $color)
                )
            )
        )
   );
}
function meargecells($objPHPExcel,$cells,$start_cell,$cell_value,$is_center){
            $objPHPExcel->mergeCells($cells);
            $objPHPExcel
            ->getCell($start_cell)
            ->setValue($cell_value);
            if($is_center){
                $objPHPExcel
                //->getActiveSheet()
                ->getStyle($start_cell)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
            }            

}

function generate_excel_eventwise($event_arr,$objPHPExcel,$rowCount,$last_week,$user,$index,$current_week,$type,$is_last){
                    $event = $event_arr[$index];
                    $total_events = count($event_arr);
                    $last_week_start = date_create($last_week['week_start'])->format('d-m-Y');            
                    $last_week_end = date_create($last_week['week_end'])->format('d-m-Y');
                    $uppercase_event = strtoupper($event);
                    $week_text = ($type == 'last_week') ? 'Last Week' : 'Current Week';
                    $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                    $this->meargecells($objPHPExcel->getActiveSheet(),"A$this->rowCount:D$this->rowCount","A$this->rowCount","$week_text $uppercase_event Animal Details (From $last_week_start  to $last_week_end)",true);
                    $this->cellColor("A$this->rowCount",'777474',$objPHPExcel->getActiveSheet()); 
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount")->getFont()->setBold( true );
                    $this->rowCount++;
                    $this->cellColor("A$this->rowCount:D$this->rowCount",'f49242',$objPHPExcel->getActiveSheet());  
                    $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(25);
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:D$this->rowCount")->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->getStyle("A$this->rowCount:D$this->rowCount")->getFont()->setSize(8);
                    //FORMATTING
                    $objPHPExcel->getActiveSheet()->setTitle('Daily Report');
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, 'Sr.No');                                                           
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, 'Date');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, 'Animals Count');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount, 'Animals Tag Ids');          
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);     
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);                       
                    $mycattles = $this->Cattle_model->get_all_cattle_ids($user);
                    $cattle_ids = array_column($mycattles, 'id');                     
                    $ai_done_last_week = '';
                    if(count($cattle_ids) > 0){
                        $this->db->where('event_date >=',$last_week['week_start']);
                        $this->db->where('event_date <=', $last_week['week_end']);
                        $this->db->where('event_type',$event);
                        $ai_done_last_week = $this->History_model->get_all_by_cattle_ids($cattle_ids);    
                    }
                    $grand_total = 0;
                    if(!empty($ai_done_last_week)){
                        $sno = 1;
                        $grand_total = 0;
                        foreach($ai_done_last_week as $i){
                            //echo $this->rowCount;
                           $this->rowCount++;
                           $objPHPExcel->getActiveSheet()->getRowDimension($this->rowCount)->setRowHeight(20);
                           $objPHPExcel->getActiveSheet()->SetCellValue('A'.$this->rowCount, $sno++ );        
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$this->rowCount)->getAlignment()->setIndent(1);                           
                           $objPHPExcel->getActiveSheet()->SetCellValue('B'.$this->rowCount, date_create($i['event_date'])->format('d-m-Y') );        
                           $objPHPExcel->getActiveSheet()->SetCellValue('C'.$this->rowCount, $i['total_cattles']);        
                           $this->db->where_in('event_type', $event);                           
                           $this->db->where_in('event_date', $i['event_date']);
                           $event_and_day = $this->History_model->get_all_by_cattle_ids_only($cattle_ids);
                           $tag_ids_arr = array_column($event_and_day, 'tag_id');
                           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$this->rowCount,implode(',', $tag_ids_arr));       
                           $this->setcellborder("A$this->rowCount:D$this->rowCount",'00000',$objPHPExcel->getActiveSheet());
                        }
                    }
                    $index+=1;                 
                    if($index < $total_events){                                              
                           $this->rowCount++;
                           $this->rowCount++;
                           $this->generate_excel_eventwise($event_arr,$objPHPExcel,$this->rowCount,$last_week,$user,$index,$current_week,$type,$is_last);     
                    } else {
                        //ALL LAST WEEK EVENTS DONE
                        $index = 0;
                        $this->rowCount++;
                        $this->rowCount++;
                        $this->rowCount++;
                        $this->rowCount++;
                        $this->rowCount++;
                        $type = 'current_week';
                        if($is_last == 'No'){                            
                            $this->generate_excel_eventwise($event_arr,$objPHPExcel,$this->rowCount,$current_week,$user,$index,$current_week,$type,'Yes'); 
                        }
                        
                  }  
                    
                   
            }

function testmail(){

  $data = [
      'name' => 'Maninder',
      'email' => 'manyder@gmail.com'
  ];

  $this->sendmail('emails/test_mail',$data,$data,'Test Mail'.date('d-m-Y'),[]);
   return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 1 ]));   
}


}



