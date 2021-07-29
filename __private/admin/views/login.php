<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>Admin</title>
<link rel="stylesheet" href="<?php echo base_url() ;?>assets/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url() ;?>assets/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url() ;?>assets/css/main.css">

</head>
<body class="hold-transition login-page">
<div class="login-box">
    
    <div class="login-box-body">
    <div class="login-logo"> <a href="#"><img style="width:100px" src="<?php echo base_url() ;?>/images/logo.png" /></a> </div>
    <?php if($this->session->flashdata('error')){ ?>
            <div class="alert alert-error"><p><?php echo $this->session->flashdata('error');?></p></div>
            <?php }?>
            <?php if($this->session->flashdata('success')){ ?>
                <div class="alert alert-success"><p><?php echo $this->session->flashdata('success');?></p></div>
            <?php }?>
    	<?php echo form_open('index.php/user/login',['class' => '' , 'method' => 'post']); ?>
    		<div class="form-group has-feedback">
    			<?php echo form_input(['class' => 'form-control' ,'name' => 'email','placeholder' => 'Email or Phone' ]);?>
                <!-- <input type="email" class="form-control" placeholder="Email"> -->
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span> 
                <?php echo form_error('email'); ?>
             </div>		
             <div class="form-group has-feedback">
                <!-- <input type="password" class="form-control" placeholder="Password"> -->
                <?php echo form_password(['class' => 'form-control','name' => 'password' ,'placeholder' => 'Password dssdfgsdfg']);?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span> 
                <?php echo form_error('password'); ?>
              </div>
              <div class="row">
                <div class="col-xs-12 text-center">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
            </form>

        <!-- <form method="post">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span> </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </form> -->
        <!-- <a href="<?php echo base_url(); ;?>forgot_password">I forgot my password</a> -->
        <!-- <a class="pull-right" href="<?php echo str_replace("/admin","",base_url()); ;?>register">Register</a> -->
         </div>
</div>
<script src="<?php echo base_url() ;?>assets/js/jquery.min.js"></script> 
<script src="<?php echo base_url() ;?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url() ;?>assets/js/main.js"></script>
</body>
</html>