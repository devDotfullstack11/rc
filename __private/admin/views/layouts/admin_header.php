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
<link rel="stylesheet" href="<?php echo base_url() ;?>assets/js/datepicker/build/jquery.datetimepicker.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header"> <a href="<?php echo base_url();?>" class="logo"> <img style="height:55px" src="<?php echo base_url() ;?>/images/logo.png" /> </a>
        <nav class="navbar navbar-static-top" role="navigation"> <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> <span class="sr-only">Toggle navigation</span> </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                <!-- <img src="assests/images/user.png" class="user-image" alt="User Image"> -->
                <?php  $role_id = $this->session->userdata("role_id"); ;?>
                <li class=" user user-menu" style="<?php echo ($role_id > 2) ? 'display:none' : "display:none" ;?>">
                <a style="padding-top:10px !important" class="btn btn-app"  href="<?php echo base_url();?>agent/payment" >
                <span class="badge bg-yellow"><?php echo (!empty($this->session->userdata("amount"))) ? $this->session->userdata("amount") : 0 ;?></span>
                <i class="fa fa-money"></i> Wallet Balance
              </a>
                <!-- <a class="" href="<?php echo base_url();?>index.php/user/logout" class="btn btn-default btn-flat"><i class="fa fa-money" ></i> <?php echo $this->session->userdata("amount") ;?></a> -->
                </li>
                
                </li>
                    <li class="dropdown user user-menu"> 
                        <a class="dropdown-toggle" href="<?php echo base_url();?>index.php/user/logout" class="btn btn-primary btn-flat">Sign out</a>
                        <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">  <span class="hidden-xs"><?php echo isset($this->session->name) ? $this->session->name : '' ;?></span> </a> -->
                        <ul class="dropdown-menu">
                            
                            <li class="">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </li>
                            <li class="">
                                
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>