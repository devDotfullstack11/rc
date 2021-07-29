<?php

class Package_model extends CI_Model {

        public $title;
        public $content;
        public $date;
        public function __construct()
        {
                parent::__construct();
                 $this->load->database();
                // Your own constructor code
        }
        public function get_last_ten_entries()
        {
                $query = $this->db->get('packages', 10);
                return $query->result();
        }

        public function insert_entry($data)
        {
                 $this->db->insert('packages', $data);
        }

        public function update_entry()
        {
                $this->title    = $_POST['title'];
                $this->content  = $_POST['content'];
                $this->date     = time();
                $this->db->update('entries', $this, array('id' => $_POST['id']));
        }

         public function get_all()
        {       
                
                $this->db->order_by("id", "desc");
                $query = $this->db->get('packages');
                return $query->result();
        }

        public function get_total()
        {
           return $this->db->count_all("packages");
        }

        public function get_current_page_records($limit, $start,$where=[])
        {   
            $this->db->limit($limit, $start);
            if(!empty($where)){
                $this->db->where($where);
            }
            $query = $this->db->get("packages");
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {   
                    $packages[] = $row;
                }
                return $packages;
            }
            return false;
        }

        /*public function login($data){
            $details = $this->db->get_where('packages', array('email' => $data['email']));
            $record_arr = $details->first_row('array');
            if(count($details) > 0){
                $date = new DateTime($record_arr['created_at']);
                $date->modify("+30 day");
                $validity =  $date->format("Y-m-d H:i:s");
                $today = date("Y-m-d H:i:s");
                if(strtotime($today) > strtotime($validity)){
                    $result = ['status' => '0'  ,'message' => 'Free Subscription Expired' ];
                } else {
                   if (password_verify($data['password'],$record_arr['password'])) {
                       $result = ['status' => '1' , 'key' => $record_arr['api_key'] ,'message' => 'Logged In Successfully!' ];
                    } else {
                        $result = ['status' => '0'  ,'message' => 'Invalid Username or Password', 'reason' => 'Password' ];
                    } 
                }
                
            } else {
                $result = ['status' => '0' ,  'message' => 'Invalid Username or Password', 'reason' => 'Record not found'];
            }
            return $result;

        }*/

          public function login($data){
            $details = $this->db->get_where('packages', array('email' => $data['email'],'is_deleted' => 0));
            $record_arr = $details->first_row('array');
            //pr($record_arr,1);
            if(count($details) > 0){
                $date = new DateTime($record_arr['validity']);
                $date->modify("+30 day");
                $validity =  $date->format("Y-m-d H:i:s");
                $today = date("Y-m-d H:i:s");
                if ($record_arr['status'] == 0) {
                    $result = ['status' => '0'  ,'message' => 'Account is not active, please contact admin', 'reason' => 'Password' ];

                } else if (md5($data['password']) == $record_arr['password'] ) {
                    unset($record_arr['password']);
                    unset($record_arr['created_at']);
                    unset($record_arr['updated_at']);
                    //unset($details['updated_at']);
                   $result = ['status' => '1' , 'key' => $record_arr['api_key'] ,'message' => 'Logged In Successfully!',
                        'profile' =>$record_arr
                    ];
                } else {
                    $result = ['status' => '0'  ,'message' => 'Invalid Username or Password', 'reason' => 'Password' ];
                } 
                                 //print_r(password_verify($data['password'],$record_arr['password'])); die;
                 /*if(password_verify($data['password'],$record_arr['password'])){ 
                    //print_r($validity); die;
                    if (strtotime($today) > strtotime($validity)){

                        //IF USER HAS FREE SUBSCRIPTION OF 30 days
                        unset($record_arr['password']);
                        unset($record_arr['created_at']);
                        unset($record_arr['updated_at']);
                        //unset($details['updated_at']);
                       $result = ['status' => '1' , 'key' => $record_arr['api_key'] ,'message' => 'Logged In Successfully!','profile' =>$record_arr ];
                    } else {
                        // IF 30 DAYS HAS BEEN EXPIRED
                        if($record_arr['has_subscribed'] == 'Yes'){
                            // IF USER HAS SUBSCRIPTION
                            $result = ['status' => '1' , 'key' => $record_arr['api_key'] ,'message' => 'Logged In Successfully!', 'profile' =>$record_arr];
                        } else {
                            // IF USER DOES NOT HAVE SUBSCRIPTION
                            $result = ['status' => '0'  ,'message' => 'Invalid Username or Password', 'reason' => 'Password' ];    
                        }
                        
                    } 
                    //$result = ['status' => '0'  ,'message' => 'Invalid Username or Password' ];
                } else {
                   $result = ['status' => '0'  ,'message' => 'Invalid Username or Password', 'reason' => 'Password' ]; 
                }*/
                
            } else {
                $result = ['status' => '0' ,'errors' => ['message' => 'Invalid Username or Password'], 'reason' => 'Record not found'];
            }
            return $result;

        }


        public function get_one($key){
            $details = $this->db->get_where('packages', array('api_key' => $key));
            $record_arr = $details->first_row('array');
            return $record_arr;
        }

        public function activate($data){
            $details = $this->db->get_where('packages', array('id' => $data['origin']));
            $record_arr = $details->first_row('array');
            $date = new DateTime($record_arr['created_at']);
            $date->modify("+36500 day");
            $validity =  $date->format("Y-m-d H:i:s");
            $this->db->update('packages',['validity' => $validity, 'has_subscribed' => 'Yes'], array('id' => $data['origin']));
            return '1';
        }

        public function change_password($data){            
                 $details = $this->db->get_where('packages', array('email' => $data['email']));
                 $record_arr = $details->first_row('array');
            if (password_verify($data['old_password'],$record_arr['password'])) {
                                unset($record_arr['password']);
                                unset($record_arr['created_at']);
                                unset($record_arr['updated_at']);
                                //unset($details['updated_at']);
                               $result = ['status' => '1' , 'key' => $record_arr['api_key'] ,'message' => 'Logged In Successfully!',
                                    'profile' =>$record_arr
                                ];
                            }
        }

        

        




}


 ;?>