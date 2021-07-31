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
                            <a class="btn btn-primary" href="<?php echo base_url() ;?>/Groups/manage">Add new Groups</a>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $counter = 1;
                                    if(count($result) > 0){
                                        $options = [
                                    
                                        ];
                                        foreach($roles  as $role){
                                          $options[$role['id']] =  $role['role'];
                                        }
                                        foreach($result as $data){    
                                          //  pr($data);
                                 ;?>
                                    <tr>
                                        <td><?php echo $counter++;?></td>
                                        <td><?php echo $data['title'] ;?></td>
                                        
                                        <td><?php echo $data['description'] ;?></td>
                                        
                                        
                                        <td><?php echo ($data['status'] == 1) ? "Active" : 'In-active' ;?></td>
                                        <td>
                                          <a title="Manage Groups" href="<?php echo base_url().'/Groups/manage/'.$data['id'] ;?>"> <i class="fa fa-pencil action_link"> </i> </a>
                                          <a title="Delete Groups" class="del_link __del" href="<?php echo base_url().'/Groups/delete/'.$data['id'] ;?>"> <i class="fa fa-trash action_link"> </i> </a>
                                        </td>  
                                       
                                    </tr>
                                <?php }
                                    }
                                ;?>
                                </tbody>
                                    
                            </table>
                            <?php if (isset($links)) { ?>
                                <?php echo $links ?>
                            <?php } ?>
                        </div>
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
<script src="<?php echo base_url() ;?>assets/js/datepicker/build/jquery.datetimepicker.full.js"></script>
<script src="<?php echo base_url() ;?>assets/js/moment.js"></script>
<script src="<?php echo base_url() ;?>assets/js/datepicker.js"></script>