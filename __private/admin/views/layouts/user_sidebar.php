<aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <!-- <div class="pull-left image"> <img src="assests/images/user.png" class="img-circle" alt=""/></div> -->
                <div class="pull-left info">
                    <p><?php echo isset($this->session->name) ? $this->session->name : '' ;?></p>
                    <!-- Status --> 
                    <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --> </div>
            </div>
    
            <ul class="sidebar-menu" data-widget="tree">
                <?php
                    $modules = $this->session->userdata("modules");
                    $role_id = $this->session->userdata("role_id");
                   ;?>
                    
                <li>
                    <a href="<?php echo base_url() ;?>agent/lead"><i class="fa fa-address-card"></i>
                     <span>Leads</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ;?>agent/payment"><i class="fa fa-money"></i>
                     <span>Payments</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ;?>/user/profile"><i class="fa fa-pencil"></i>
                    <span>My Profile</span></a>
                </li>
                <li>
                    <a href="#">
                    <img src="<?php echo base_url() ;?>/images/qr_code.jpeg" style="width:100%;bottom:50px;"  />                    
                    
                    </a>
                    <span class="text-center upi-txt" style="color:#fff;margin-left: 15px;">Pay with any UPI application</span>
                </li>
           

                <!-- <li class="active">
                    <a href="<?php echo base_url() ;?>/product/list"><i class="fa fa-link"></i> 
                    <span>Products</span></a></li>
                <li>
                    <a href="<?php echo base_url() ;?>/product/add"><i class="fa fa-link"></i>
                     <span>Product Add</span></a>
                </li>

                <li>
                    <a href="<?php echo base_url() ;?>/product/requests"><i class="fa fa-link"></i>
                     <span>Service Requests</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ;?>/cattles/breeding_process"><i class="fa fa-link"></i>
                     <span>BreedingProcess</span></a>
                </li> -->
                
				<!-- <li>
                    <a href="<?php echo base_url() ;?>event/manage"><i class="fa fa-gear"></i>
                     <span>Add Event</span></a>
                </li>
				<li>
                    <a href="<?php echo base_url() ;?>event/"><i class="fa fa-gear"></i>
                     <span>Event List</span></a>
                </li> -->
				
				<!-- <li>
                    <a href="<?php echo base_url() ;?>group/manage"><i class="fa fa-gear"></i>
                     <span>Add Group</span></a>
                </li>
				<li>
                    <a href="<?php echo base_url() ;?>group/"><i class="fa fa-gear"></i>
                     <span>Group List</span></a>
                </li> -->
                <?php if(in_array("5",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>/user/global_settings"><i class="fa fa-gear"></i>
                     <span>Frontend Settings</span></a>
                </li>
                <?php }?>
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>package"><i class="fa fa-align-left"></i>
                     <span>Packages</span></a>
                </li>
                <?php }?>
                <?php if(in_array("4",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>lead"><i class="fa fa-address-card"></i>
                     <span>Leads</span></a>
                </li>
                <?php }?>
                <!-- <li class="treeview">
                 <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>

                    <ul class="treeview-menu">
                        <li><a href="#">Link in level 2</a></li>
                        <li><a href="#">Link in level 2</a></li>
                    </ul>
                </li> -->
            </ul>
        </section>
    </aside>
