<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


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
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/font-awesome-4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/libs/ionicons-2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/iCheck/square/blue.css">

        <!------Custom styles------------->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/login.css">
         <style>
        
            body {background-color: #1a0000;}
        </style> 

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition">
        <div class="login-box" id="login-box">

            <!-- /.login-logo -->
            <div class="login-box-body">
                
                <div class="row">
                    
                     <div class="col-md-4"><img src="<?php echo  base_url('public/images/logo.png');?>" class="img-responsive"> </div>
                     <div class="col-md-8 pullleft">
                         <h3 class="login-box-msg pull-left" style="font-size:1.2em;font-weight: bold">
                    <p class="login-box-msg" style="color:#E13300; font-size: 1.5em;font-weight: bold">Sign In</p> 
                     <?php echo $sitename ?>
                    </h3>
                         </div>
                </div>
                    
                    
                
                <div class="form-group" id="error-msg">

                </div>
                <?php
                $form_attributes = array(
                    'id' => 'login-form'
                );
                echo form_open('user/checkLogin', $form_attributes);
                ?>

                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off" tabindex="1">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" tabindex="2">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <div id="captcha-img"><?php echo $captcha; ?></div><br />

                    <a href="#" onclick="refreshCaptcha('<?php echo base_url('captcha'); ?>', '#captcha-img', '#captcha')" id="change-image" tabindex="5" >Not readable? Change text.</a><br />
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Security Code" name="captcha" autocomplete="off" tabindex="3">                       
                    <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">

                        <button type="submit" class="btn btn-primary btn-block btn-flat" id="btn-submit" name="submit" tabindex="4">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
                <?php echo form_close(); ?>

            </div>
            <!-- /.login-box-body -->            
        </div>
        <!-- /.login-box -->
        <script>
            var base_url = "<?php echo base_url(); ?>";
        </script>
        <!-- jQuery 2.2.3 -->
        <script src="<?php echo base_url(); ?>public/plugins/jQuery/jquery.js"></script>
        <script src="<?php echo base_url(); ?>public/js/custom.jquery.js"></script>
        <script src="<?php echo base_url(); ?>public/js/jquery.cookie.js"></script>
        <script src="<?php echo base_url(); ?>public/js/jquery.blockUI.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/libs/validator/js/bootstrapValidator.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo base_url(); ?>public/plugins/iCheck/icheck.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/eModal.min.js"></script>
        <script>

            $(document).ready(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
                ///////////////////////////////////////////////////////////////////////
<?php
if ($msg = $this->session->flashdata('user_msg')) {
    ?>
                    eModal.alert("<?php echo $msg; ?>", "Login to continue");
    <?php
}
?>
                ///////////////////////////////////////////////////////////////////////
                $('#login-form')
                        .bootstrapValidator({
                            message: 'This value is not valid',
                            feedbackIcons: {
                                valid: 'glyphicon glyphicon-ok',
                                invalid: 'glyphicon glyphicon-remove',
                                validating: 'glyphicon glyphicon-refresh'
                            },
                            fields: {
                                username: {
                                    message: 'The username is not valid',
                                    validators: {
                                        notEmpty: {
                                            message: 'The username is required and can\'t be empty'
                                        },
                                        stringLength: {
                                            min: 5,
                                            max: 30,
                                            message: 'The username must be more than 6 and less than 30 characters long'
                                        }
                                    }
                                },
                                password: {
                                    validators: {
                                        notEmpty: {
                                            message: 'The password is required and can\'t be empty'
                                        }
                                    }
                                },
                                captcha: {
                                    validators: {
                                        notEmpty: {
                                            message: 'The security code is required and can\'t be empty'
                                        }
                                    }
                                },
                            }
                        })
                        .on('success.form.bv', function (e) {
                            // Prevent form submission
                            e.preventDefault();

                            // Get the form instance
                            var $form = $(e.target);

                            // Get the BootstrapValidator instance
                            var bv = $form.data('bootstrapValidator');

                            // Use Ajax to submit form data
                            /* $.post($form.attr('action'), $form.serialize(), function (result) {
                             console.log(result);
                             }, 'json');
                             */

                            $.ajax({
                                type: "POST",
                                url: $form.attr('action'),
                                cache: false,
                                data: $form.serialize(),
                                dataType: 'json',
                                beforeSend: function () {
                                    $('#login-box').block();
                                },
                                success: function (data) {
                                    if (data.status) {
                                        window.location = data.URL;
                                    } else {
                                        $('#login-box').unblock();
                                        clearForm();
                                        $('#error-msg').html(data.error);
                                        $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(data.csrf);
                                    }

                                }
                            });
                        });


                ////////////////////////////
                $('#captcha').keypress(function (event) {

                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        submitForm();
                    }

                });
            });



            function submitForm() {
                // $('#login-form').form('submit');
                $('#login-form').form('submit', {
                    onSubmit: function () {
                        $.messager.progress();
                        $('#btn-submit').linkbutton('disable');
                        var isValid = $(this).form('validate');
                        if (!isValid) {
                            $('#btn-submit').linkbutton('enable');
                            $.messager.progress('close');	// hide progress bar while the form is invalid
                        }
                        return isValid;	// return false will stop the form submission
                    },
                    success: function (data) {
                        $.messager.progress('close');
                        data = JSON.parse(data);
                        if (data.status) {
                            window.location = data.URL;
                        } else {
                            clearForm();
                            $('#error-msg').html(data.error);
                            $('#captcha-error').html(data.captcha);
                            $('#btn-submit').linkbutton('enable');
                        }
                    },
                });
                return false;
            }

            function clearForm() {
                $('#login-form').data('bootstrapValidator').resetForm(true);
                $('#error-msg').html('');
                refreshCaptcha('<?php echo base_url('captcha'); ?>', '#captcha-img', '#username');
            }

            function refreshCaptcha(URI, id, foc) {
                $.ajax({
                    url: URI,
                    cache: false,
                    beforeSend: function () {
                        $('#btn-submit').disable(true);
                        $('#captcha-img').block();
                    },
                    success: function (response) {
                        $(id).html(response);
                        $(foc).focus();
                        $('#btn-submit').disable(false);
                    }
                });
            }
            ////////////////////////////custom script/////////////////////////////////

        </script>
    </body>
</html>