<?php
		 $role_id = $this->session->userdata("role_id");
		 $this->load->view('layouts/admin_header');
		 if($role_id > 2){
			$this->load->view('layouts/user_sidebar');
		 } else {
			$this->load->view('layouts/admin_sidebar');
		 }
	 	
    	$this->load->view($middle,$result);
    	$this->load->view('layouts/admin_footer'); 
	
 ;?>