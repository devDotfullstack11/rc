<aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <!-- <div class="pull-left image"> <img src="assests/images/user.png" class="img-circle" alt=""/></div> -->
                <div class="pull-left info">
                    <p><?php echo isset($this->session->name) ? $this->session->name : '' ;?></p>
                    <!-- Status --> 
                    <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --> </div>
            </div>

            <ul class="sidebar-menu" data-widget="tree" style="display:none">
                <?php
                    $modules = !empty($this->session->userdata("modules")) ? $this->session->userdata("modules") : [];
                    $role_id = $this->session->userdata("role_id");
                    if(in_array("1",$modules ) || $role_id == 1){ ?>
                        <li>
                            <a href="<?php echo base_url() ;?>/user/sub_admins"><i class="fa fa-user"></i>
                            <span>Sub Admin Management</span></a>
                        </li>
                    <?php } 
                    if(in_array("6",$modules ) || $role_id == 1){ ?>
                        <li>
                            <a href="<?php echo base_url() ;?>/client"><i class="fa fa-users"></i>
                            <span>Users Management</span></a>
                        </li>
                    <?php } ;?>
                

               
                <?php if(in_array("5",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>/user/frontend_settings"><i class="fa fa-gear"></i>
                     <span>Frontend Settings</span></a>
                </li>
                <?php }?>
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>package"><i class="fa fa-align-left"></i>
                     <span>Packages</span></a>
                </li>
                <?php }?>
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>groups"><i class="fa fa-align-left"></i>
                     <span>Groups</span></a>
                </li>
                <?php }?>
                
                <?php if(in_array("2",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>treedrive"><i class="fa fa-align-left"></i>
                     <span>Treedrive</span></a>
                </li>
                <?php }?> 
                
                  <?php if(in_array("8",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>banner"><i class="fa fa-image"></i>
                     <span>Banners</span></a>
                </li>
                <?php }?>



                <?php
                //pr($modules);
                if((in_array("4",$modules ) || in_array("10",$modules ) ) || $role_id == 1){ ?>       
                
                <!-- <li class="treeview">
                <a href="#"><i class="fa fa-address-card"></i> <span>Insurance Leads</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>

                    <ul class="treeview-menu">
                        <?php
                        $leads_modules = [
                            'insurance_fresh_leads' => 11,
                            'insurance_pending_leads' => 13,
                            'insurance_yet_to_decide_leads' => 15,
                            'insurance_callback_leads' => 17,
                            'insurance_documents_pending_leads' => 19,
                            'insurance_approved_leads' => 21,
                            'insurance_rejected_leads' => 23,
                            'insurance_NA_area_leads' => 25,
                            'insurance_assigned_leads' => 27,
                            'insurance_all_leads' => '',
                        ];
                        $base_url = base_url();
                        foreach($leads_modules as $module_slug =>  $module_id){
                            if((in_array($module_id,$modules )) || $role_id == 1){ 
                                $label = str_replace("insurance","",str_replace("_"," ",$module_slug));
                                $caps = ucfirst(ltrim($label));
                                $activeClass = (isset($_GET['lead_slug']) && $_GET['lead_slug'] == $module_slug) ? 'active' : '' ;
                                echo "<li class='{$activeClass}'><a href='{$base_url}/lead/index/?lead_slug={$module_slug}'>{$caps}</a></li>";

                             } 
                        }

                        
                           ?>
                    </ul>
                 </li> -->
                <?php }?>

                <?php
                //pr($modules);
                if((in_array("4",$modules ) || in_array("10",$modules ) ) || $role_id == 1){ ?>       
                
            <!-- <li class="treeview"> 
                 <a href="#"><i class="fa fa-address-card"></i> <span>Agent Leads</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>

                    <ul class="treeview-menu">
                        <?php
                        $leads_modules = [
                            'agent_fresh_leads' => 12,
                            'agent_pending_leads' => 14,
                            'agent_yet_to_decide_leads' => 16,
                            'agent_callback_leads' => 18,
                            'agent_documents_pending_leads' => 20,
                            'agent_approved_leads' => 22,
                            'agent_rejected_leads' => 24,
                            'agent_NA_area_leads' => 26,
                            'agent_assigned_leads' => 28,
                            'agent_all_leads' => '',
                        ];
                        $base_url = base_url();
                        foreach($leads_modules as $module_slug =>  $module_id){
                            if((in_array($module_id,$modules )) || $role_id == 1){ 
                                $label = str_replace("agent","",str_replace("_"," ",$module_slug));
                                $caps = ucfirst(ltrim($label));
                                $activeClass = (isset($_GET['lead_slug']) && $_GET['lead_slug'] == $module_slug) ? 'active' : '' ;
                                echo "<li class='{$activeClass}'><a href='{$base_url}/lead/agent_leads/?lead_slug={$module_slug}'>{$caps}</a></li>";

                             } 
                        }

                        
                           ?>
                    </ul>
                 </li> -->
                <?php }?>        


                <?php if(in_array("3",$modules ) || $role_id == 1){ ?>       
                 <!-- <li>  
                    <a href="<?php echo base_url() ;?>payment"><i class="fa fa-money"></i>
                     <span>Payments Management</span></a>
                </li>
                <?php }?>
             
                <?php if(in_array("9",$modules ) || $role_id == 1){ ?>       
                <li>
                    <a href="<?php echo base_url() ;?>page"><i class="fa fa-file"></i>
                     <span>pages111</span></a>
                </li>
            <?php }?>
                 <li> 
                    <a href="<?php echo base_url() ;?>/user/profile"><i class="fa fa-pencil"></i>
                    <span>My Profile</span></a>
                </li>
                 <li class="treeview">
                 <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>

                    <ul class="treeview-menu">
                        <li><a href="#">Link in level 2</a></li>
                        <li><a href="#">Link in level 2</a></li>
                    </ul>
                </li> -->
            </ul>
        </section>
    </aside>
