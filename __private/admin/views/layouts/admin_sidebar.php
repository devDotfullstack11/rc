<aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <!-- <div class="pull-left image"> <img src="assests/images/user.png" class="img-circle" alt=""/></div> -->
                <div class="pull-left info">
                    <p><?php echo isset($this->session->name) ? $this->session->name : '' ;?></p>
                    <!-- Status --> 
                    <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --> </div>
            </div>

            <ul class="sidebar-menu" data-widget="tree" >
                <?php
                    $modules = !empty($this->session->userdata("modules")) ? $this->session->userdata("modules") : [];
                    $role_id = $this->session->userdata("role_id");
                    if(in_array("1",$modules ) || $role_id == 1){ ?>
                       
                    <?php } 
                    if(in_array("6",$modules ) || $role_id == 1){ ?>
                        <li style="display:none">
                            <a href="<?php echo base_url() ;?>/client"><i class="fa fa-users"></i>
                            <span>Users Management</span></a>
                        </li>
                    <?php } ;?>
                

               
                <?php if(in_array("5",$modules ) || $role_id == 1){ ?>       
                <li style="display:none" >
                    <a href="<?php echo base_url() ;?>/user/frontend_settings"><i class="fa fa-gear"></i>
                     <span>Frontend Settings</span></a>
                </li>
                <?php }?>
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li style="display:none">
                    <a href="<?php echo base_url() ;?>package"><i class="fa fa-align-left"></i>
                     <span>Packages</span></a>
                </li>
                <?php }?>
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li >
                    <a href="<?php echo base_url() ;?>groups"><i class="fa fa-align-left"></i>
                     <span>Groups</span></a>
                </li>
                <?php }?>
                
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li style="display:none" >
                    <a href="<?php echo base_url() ;?>treedrive"><i class="fa fa-align-left"></i>
                     <span>Treedrive</span></a>
                </li>
                <?php }?> 
                
                  <?php if(in_array("8",$modules ) || $role_id == 1){ ?>       
                <li style="display:none">
                    <a href="<?php echo base_url() ;?>banner"><i class="fa fa-image"></i>
                     <span>Banners</span></a>
                </li>
                <?php }?>
           
                 <!-- <li> 
                    <a href="<?php echo base_url() ;?>/user/profile"><i class="fa fa-pencil"></i>
                    <span>My Profile</span></a>
                </li> -->
                 <li class="treeview" style="display:none">
                 <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>

                    <ul class="treeview-menu">
                        <li><a href="#">Link in level 2</a></li>
                        <li><a href="#">Link in level 2</a></li>
                    </ul>
                </li> -->
            </ul>
        </section>
    </aside>
