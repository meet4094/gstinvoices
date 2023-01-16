<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?php echo PLATFORM_NAME; ?> | Admin Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo BASE_URL;?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo BASE_URL;?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo BASE_URL;?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo BASE_URL;?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo BASE_URL;?>assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>favicon.ico" /> </head>
        <style type="text/css">
            .login .logo { margin: 15px auto 20px; padding: 0px; text-align: center; width: 350px; }
            @media only screen and (max-width: 480px) and (min-width: 320px) { 
                .login .logo { width: 210px; }
            }
            @media only screen and (max-width: 320px) { 
                .login .logo { width: 210px; }
            }
        </style>
    <!-- END HEAD -->
    <body class=" login">
        <div class="menu-toggler sidebar-toggler"></div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGO -->
        <div class="logo">
            <img class="img-responsive" src="<?php echo BASE_URL;?>images/logo_wide.png" alt="<?php echo PLATFORM_NAME; ?>" /> 
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="<?php echo BASE_URL."admin/login";?>" method="post">
                <h3 class="form-title">Sign In</h3>
                <?php $iError = "Enter any email and password."; $displayError = "display: none;"; ?>
                <?php if (isset($error_message) && $error_message != '') {
                    $iError = $error_message;
                    $displayError = "display: block";
                } ?>
                <div class="alert alert-danger display-hide" style="<?php echo $displayError; ?>">
                    <button class="close" data-close="alert"></button>
                    <span><?php echo $iError;?></span>
                </div>
                <?php $iInfo = ""; $displayInfo = "display: none;"; ?>
                <?php if (isset($info_message) && $info_message != '') {
                    $iInfo = $info_message;
                    $displayInfo = "display: block";
                } ?>
                <div class="alert alert-success display-hide" style="<?php echo $displayInfo; ?>">
                    <button class="close" data-close="alert"></button>
                    <span><?php echo $iInfo;?></span>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Email</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input value="admin@gmail.com" type="text" autocomplete="off" placeholder="Email address" name="email" class="form-control form-control-solid placeholder-no-fix" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input value="123456" type="password" name="password" class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Password" />
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn green uppercase">Login</button>
                    <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" action="<?php echo BASE_URL.'admin/services/forgot_password'; ?>" method="post">
                <h3 class="form-title">Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" />
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn btn-default">Back</button>
                    <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
        </div>
        <div class="copyright"> <?php echo date("Y"); ?> &copy; <?php echo PLATFORM_NAME; ?>. </div>
        <!--[if lt IE 9]>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/respond.min.js"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo BASE_URL;?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo BASE_URL;?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL;?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo BASE_URL;?>assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <script type="text/javascript">
            var Login = function() {

                var handleLogin = function() {

                    $('.login-form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        rules: {
                            email: {
                                required: true
                            },
                            password: {
                                required: true
                            },
                            remember: {
                                required: false
                            }
                        },

                        messages: {
                            email: {
                                required: "Email is required."
                            },
                            password: {
                                required: "Password is required."
                            }
                        },

                        invalidHandler: function(event, validator) { //display error alert on form submit   
                            $('.alert-danger', $('.login-form')).show();
                        },

                        highlight: function(element) { // hightlight error inputs
                            $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },

                        success: function(label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },

                        errorPlacement: function(error, element) {
                            error.insertAfter(element.closest('.input-icon'));
                        },

                        submitHandler: function(form) {
                            form.submit(); // form validation success, call ajax form submit
                        }
                    });

                    $('.login-form input').keypress(function(e) {
                        if (e.which == 13) {
                            if ($('.login-form').validate().form()) {
                                $('.login-form').submit(); //form validation success, call ajax form submit
                            }
                            return false;
                        }
                    });
                }

                var handleForgetPassword = function() {
                    $('.forget-form').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",
                        rules: {
                            email: {
                                required: true,
                                email: true
                            }
                        },

                        messages: {
                            email: {
                                required: "Email is required."
                            }
                        },

                        invalidHandler: function(event, validator) { //display error alert on form submit   

                        },

                        highlight: function(element) { // hightlight error inputs
                            $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },

                        success: function(label) {
                            label.closest('.form-group').removeClass('has-error');
                            label.remove();
                        },

                        errorPlacement: function(error, element) {
                            error.insertAfter(element.closest('.input-icon'));
                        },

                        submitHandler: function(form) {
                            form.submit();
                        }
                    });

                    $('.forget-form input').keypress(function(e) {
                        if (e.which == 13) {
                            if ($('.forget-form').validate().form()) {
                                $('.forget-form').submit();
                            }
                            return false;
                        }
                    });

                    jQuery('#forget-password').click(function() {
                        jQuery('.login-form').hide();
                        jQuery('.forget-form').show();
                    });

                    jQuery('#back-btn').click(function() {
                        jQuery('.login-form').show();
                        jQuery('.forget-form').hide();
                    });

                }

                return {
                    //main function to initiate the module
                    init: function() {
                        handleLogin();
                        handleForgetPassword();
                    }

                };

            }();

            jQuery(document).ready(function() {
                Login.init();
            });
        </script>
    </body>
</html>