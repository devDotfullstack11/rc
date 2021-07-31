<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends MY_Controller {

        //$this->load->model(array('data'));

        function __construct() {
            parent::__construct();
          
            $this->load->model(array('Groups_model','Common_m'));
            $this->load->helper('url');
            $this->load->library('session');
            $this->load->view('common_style');  
            if(empty($this->session->userdata('id'))){
                return redirect("/");
            }   
        }



        /**
         * Index Page for this controller. asdfasd
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
        public function index(){
            //$data = $this->Package_model->get_all();
            //$modules = $this->session->userdata('modules');
            //pr($this->session->userdata());
            $this->load->model(['Permission_model']);
            //echo $this->session->userdata('role_id'); exit;
            if($this->session->userdata('role_id') !== "1" ){
                $permissionData = $this->Permission_model->verifyPermissions($this->session->userdata('id'),'manage_Groups');
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
                $where = [];
                //$total_records = $this->Package_model->get_total();
                $total_records = $this->Common_m->get('rc_groups', $where, $offset = 0, $is_single = false, $is_total = true);
                $params["result"] =[];
                if ($total_records > 0) 
                {
                    // get current page records
                   // $params["result"] = $this->Package_model->get_current_page_records($limit_per_page, $start_index,['is_deleted' => 0]);
                   $params["result"] = $this->Common_m->get('rc_groups', $where, $offset = $start_index, $is_single = false, $is_total = false,'',"");
                    $config['base_url'] = base_url() . 'Groups/index';
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
            $roles = $this->db->get_where("roles",['status' => 1])->result_array();
            $params['roles'] = $roles;
            $params['middle'] = 'groups/index';
            $params['title'] = 'Groups Management';
            $params['b_links'] = [
                    ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                    ['href' => '#', 'fa_icon' => 'fa-align-left', 'link_text' => 'Groups List']

            ];
            $this->load->view('template',$params);
    }

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
                $details = $this->db->get_where('rc_groups', array('id' => $id));
                $record_arr = $details->first_row('array');
                $data['user'] = $record_arr;
            }
            //pr($this->input->post()); exit;
            if(count($this->input->post()) > 0){
                // IF POST DATA AVAILABLE
                $this->load->library('form_validation');
                $this->form_validation->set_rules('title', 'Title', 'required|min_length[5]|max_length[50]');
                
                
                if ($this->form_validation->run() == TRUE) { 
                    $data = array(
                            'title' => $this->input->post('title'),
                            'description' => $this->input->post('description'),
                            'status' => $this->input->post('status'),
                            //'role_id' => $this->input->post('role_id'),
                        );
                        //pr($data); exit;
                        if(!empty($id)){
                            $where = ['id' =>  $id];
                            $this->db->update('rc_groups',$data,$where); // UPDATE C
                            $this->session->set_flashdata('msg', 'Groups Updated!');
                           // $this->saveLog($record_arr,$data,'Groups','Groups Updated!',$this->user_id,$id);
                        } else {
                            $this->db->insert('rc_groups',$data); //INSERT
                            $id = $this->db->insert_id();
                            //$this->saveLog([],$data,'Package','Groups Created!',$this->user_id,$id);
                            $this->session->set_flashdata('msg', 'Groups Created!');
                        }

                        
                        redirect('Groups');

                }

            }
            $roles = $this->db->get_where("roles",['status' => 1])->result_array();
            $data['roles'] = $roles;
            $data['middle'] = 'groups/manage'; // views/groups/manage.php
            $data['result'] = [];
            $data['title'] = 'Add Groups';
            $data['id'] = $id;
            
            $data['b_links'] = [
                    ['href' => base_url(), 'fa_icon' => 'fa-dashboard', 'link_text' => 'Dashboard'],
                    ['href' => base_url().'/Groups', 'fa_icon' => 'fa-align-left', 'link_text' => 'Groups List'],
                    ['href' => '#', 'fa_icon' => 'fa-plus', 'link_text' => 'Add Plan'],

            ];
            $this->load->view('template',$data);
        }

      
     

        public function get_one_by_email($email){

        }

        public function activate(){
            $formdata = $this->input->post();
            //print_r($formdata); die;
            if(count($formdata) > 0){
              $this->Package_model->activate($formdata);     
              $result = ['status' => 'success'];
            }

            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result)); 

        }

        function delete($user_id){
            if(!empty($user_id)){
                $where = ['id' =>  $user_id];
                $data = ['is_deleted' => 1];
                $this->db->where($where);
                $this->db->delete("rc_groups");
                //$this->db->update('rc',$data,$where);
                //$this->saveLog([],['deleted' => 'Yes' ],'Groups','Groups Deleted!',$this->user_id,$user_id);
                $result = ['status' => "success" , 'message' => 'Deleted!'];
            } else {
                $result = ['status' => "failed" , 'message' => 'Something went wrong'];
            }
            echo json_encode($result); exit;
          }

            

}
