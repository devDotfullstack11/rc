<div class="content-wrapper">
        <?php
            $this->load->view('layouts/breadcrum'); 
        ;?>
        <section class="content container-fluid">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Add New Groups</h3>
                        </div>
                        <?php echo form_open_multipart('Groups/manage/'.$id,['class' => '' , 'method' => 'post']); ?>
                        <div class="box-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Title</label>
                                  <?php echo form_input(['class' => 'form-control' ,'name' => 'title' ,'value' => (isset($user['title'])) ? $user['title'] :  set_value('title')]);?>
                                  <?php echo form_input(['class' => 'form-control' ,'name' => 'id','type' => 'hidden' ,'value' => $id]);?>
                                  <?php echo form_error('title'); ?>
                                </div>
                              
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Description</label>
                                  <?php echo form_textarea(['class' => 'form-control' ,'name' => 'description','value' => (isset($user['description'])) ? $user['description'] : set_value('description')]);?>
                                  <?php echo form_error('description'); ?>
                                </div>
                             
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Activation Status</label>
                                  <?php
                                $options = [
                                    '1'  => 'Active',
                                    '0'    => 'In-active'
                                ];
                                echo form_dropdown('status', $options, (isset($user['status'])) ? $user['status'] : set_value('status'),['class' => 'form-control']);
                                
                                ?>
                                  <?php echo form_error('status'); ?>
                                </div>
                            

                                
                               
                                
                                                                                           
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                         <?php echo "</form>";?>   
                    </div>
                </div>
            </div>
        </section>
        
         </section>
    </div>
    <script type="text/javascript" src="<?php echo base_url() ;?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url() ;?>assets/js/bootstrap.min.js"></script>  
<script src="<?php echo base_url() ;?>assets/js/main.js"></script>
<script src="<?php echo base_url() ;?>assets/js/common.js"></script>
<script>

</script>