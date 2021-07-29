 <style>
 .widget-user-2 .widget-user-username, .widget-user-2 .widget-user-desc {
    margin-left: 0px;
}
 </style>
 
 <div class="content-wrapper">
        <section class="content-header">
            <!-- <h1> Page Header <small>Optional description</small> </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol> -->
        </section>
        <section class="content container-fluid">
        
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h1 class="">Welcome <b><?php echo $this->session->userdata("name") ;?></b>!
                            <?php  $role_id = $this->session->userdata("role_id");
                             $package_id = $this->session->userdata("package_id");
                            //pr($this->session->userdata()) ;?>
                            <small class="label label-primary" style="float: right;<?php echo ($role_id > 2) ? '' : "display:none" ;?>"  >&#8377; Wallet Balance : <?php echo (!empty($this->session->userdata("amount"))) ? $this->session->userdata("amount") : 0 ;?></small>
                            </h1>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="row">
                        <?php if(!empty($packages)){ ?>
                          <div class="col-md-12">
                         <h2 class="page-header">Our Plans</h2>                         
                         </div>
                         <?php foreach($packages as $package){
                          
                            
                           ?>
                          <div class="col-md-4 col-sm-6 col-xs-12" title="<?php echo $package['description'] ;?>" >

                            <div class="info-box <?php echo  ($package['id'] == $package_id) ?"bg-theme3" : 'bg-theme' ;?> ">
                              <span class="info-box-icon pt-30"><i class="fa fa-rupee"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text"><?php echo  $package['name'] ?> <?php echo  ($package['id'] == $package_id) ?"(SELECTED)" : '' ;?> </span>
                                <span class="info-box-number"><?php echo $package['price'] ;?></span>
                                <div class="progress">
                                  <div class="progress-bar" style="width: 0%"></div>
                                </div>
                                <span class="progress-description"  >
                                <?php echo $package['description'] ;?>
                                </span>
                              </div>
                            </div>
                            <!-- /.info-box -->
                          </div>
                         <?php }  ;?>
                        
                        
                        
                        
                        
                        
                        
                        <?php }  ;?>
                        
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
          <!-- Bank Details Section -->
          <?php  if(!empty($bank_details)&& $this->session->userdata('role_id') > 2 ){ ;?>
          <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="row">
                        
                          <div class="col-md-12">
                         <h2 class="page-header">Our Bank Details</h2>                         
                         </div>
                         <div class="col-md-12">
                         <table class="table bordered">
                         <?php foreach($bank_details as $setting){
                          
                            
                          ?>
                          <tr>
                            <th style="font-size:20px;"><?php echo ucfirst(str_replace("_" ," ",$setting['setting_key'])) ;?></th>
                            <th style="font-size:20px;" ><b><?php echo $setting['setting_value'] ;?></b></th>
                          </tr>
                          <?php } ;?>
                         </table>
                         </div>
                        
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
        <?php } ;?>
          <!-- Bank Details Section -->
          <!-- QR CODE -->
          
          <!-- QR CODE -->



        
         </section>
    </div>
    <script type="text/javascript" src="<?php echo base_url() ;?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url() ;?>assets/js/bootstrap.min.js"></script>  
<script src="<?php echo base_url() ;?>assets/js/main.js"></script>
<script src="<?php echo base_url() ;?>assets/js/common.js"></script>
<script src="<?php echo base_url() ;?>assets/js/datepicker/build/jquery.datetimepicker.full.js"></script>
<script src="<?php echo base_url() ;?>assets/js/moment.js"></script>
<script src="<?php echo base_url() ;?>assets/js/datepicker.js"></script>