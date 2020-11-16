<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------------
 *  home
 * ------------------------------------------------------------------------
 * Home page layout of the administration ui. All other elements are loaded in this ui
 * 
 * ------------------------------------------------------------------------ 
 * @author Mukesh MR 
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $sitename; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/validator/css/bootstrapValidator.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/ionicons-2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/css/skins/_all-skins.min.css">
    <link href="<?php echo base_url(); ?>public/plugins/iCheck/line/blue.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/libs/select/css/select2.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/libs/select/css/select2-bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/css/bootstrap-toggle.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/daterangepicker/daterangepicker.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/datatables/dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/datatables/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/leaflet/leaflet.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/leaflet/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/leaflet/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/leaflet/dist/leaflet.fullscreen.css" />

    <!----Tree--------->
    <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/libs/tree/tree1.css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/libs/context-menu/jquery.contextMenu.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


</head>

<body class="skin-black-light sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="<?php echo base_url(); ?>home" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">ES</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>E-Stoc</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs"> <i class="fa fa-user-circle"></i> <?php echo ucfirst($user->user_name); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url(); ?>public/images/user.png" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo ucfirst($user->user_name); ?> - <?php echo ucfirst($user->runit); ?>
                                        <small><?php echo ucfirst($user->role_desc); ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url(); ?>home" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url(); ?>user/logout" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->