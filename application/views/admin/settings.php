<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title><?php echo PLATFORM_NAME; ?> | Admin Panel</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo BASE_URL; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo BASE_URL; ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/themes/light2.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-closed">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <img width="165" src="<?php echo BASE_URL; ?>images/logo_wide.png" alt="logo" class="logo-default" />
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="<?php echo BASE_URL; ?>assets/layouts/layout/img/avatar.png" />
                                <span class="username username-hide-on-mobile"> Admin </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?php echo BASE_URL . "admin/services/logout"; ?>">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <?php $iData['SelectedMenu'] = array("MainMenu" => "settings", "SubMenu" => ""); ?>
            <?php echo $this->load->view('admin/sidebar',$iData,true); ?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Profile Information
                        <small>form</small>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                <?php if (isset($statuscode) && $statuscode == 0) { ?>
                                    <div class="alert alert-danger">
                                        <strong>Error!</strong> Failed to add settings. Please try again.
                                    </div>
                                <?php } elseif (isset($statuscode) && $statuscode == 1) { ?>
                                    <div class="alert alert-success">
                                        <strong>Success!</strong> Settings has been added. 
                                    </div>
                                <?php } ?>
                                <?php
                                    if (isset($iSettings['logo']) && !empty($iSettings['logo']))
                                        $iSettings['logo'] = '<img width="200" src="'.BASE_URL."images/".$iSettings['logo'].'"/>';
                                    if (isset($iSettings['logo_wide']) && !empty($iSettings['logo_wide']))
                                        $iSettings['logo_wide'] = '<img width="312" src="'.BASE_URL."images/".$iSettings['logo_wide'].'"/>';
                                    if (isset($iSettings['favicon']) && !empty($iSettings['favicon']))
                                        $iSettings['favicon'] = '<img width="25" src="'.BASE_URL.$iSettings['favicon'].'"/>';
                                ?>
                                    <form action="<?php echo BASE_URL."admin/settings/view" ;?>" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> You have some form errors. Please check below. 
                                        </div>
                                        <div class="form-body">
                                            <h3 class="form-section">Site Information</h3>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Site Name</label>
                                                <div class="col-md-10">
                                                    <input value="<?php echo $iSettings['site_name']; ?>"  name="site_name" type="text" class="form-control" placeholder="Site Name">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Site Status</label>
                                                <div class="col-md-10">
                                                    <select class="form-control" name="site_status">
                                                        <option <?php if($iSettings['site_status'] == 'online') { echo "selected"; } ?> value="online">Online</option>
                                                        <option <?php if($iSettings['site_status'] == 'offline') { echo "selected"; } ?> value="Offline">offline</option>
                                                    </select>
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>
                                            <h3 class="form-section">Site Logo</h3>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Logo</label>
                                                <div class="col-md-5">
                                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                                <label class="col-md-2 control-label">Minimum image size is 500 x 500px.</label>
                                                <div class="col-md-3">
                                                    <?php if (isset($iSettings['logo']) && !empty($iSettings['logo'])) echo $iSettings['logo']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Logo Wide</label>
                                                <div class="col-md-5">
                                                    <input type="file" name="logo_wide" class="form-control" accept="image/*">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                                <label class="col-md-2 control-label">Minimum image size is 1280 x 312px.</label>
                                                <div class="col-md-3">
                                                    <?php if (isset($iSettings['logo_wide']) && !empty($iSettings['logo_wide'])) echo $iSettings['logo_wide']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Favicon Icon</label>
                                                <div class="col-md-5">
                                                    <input type="file" name="favicon" class="form-control" accept="image/*">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                                <label class="col-md-2 control-label">Minimum icon size is 16px x 16px.</label>
                                                <div class="col-md-3">
                                                    <?php if (isset($iSettings['favicon']) && !empty($iSettings['favicon'])) echo $iSettings['favicon']; ?>
                                                </div>
                                            </div>
                                            <h3 class="form-section">Security Control</h3>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Unlock Login Interval (Minutes)</label>
                                                <div class="col-md-10">
                                                    <input value="<?php echo $iSettings['unlock_login_interval']; ?>"  name="unlock_login_interval" type="number" min="1" max="60" step="1" class="form-control" placeholder="Interval">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">No of false Login Attempt</label>
                                                <div class="col-md-10">
                                                    <input value="<?php echo $iSettings['no_of_login_attampt']; ?>" name="no_of_login_attampt" type="number" min="1" max="60" step="1" class="form-control" placeholder="Login Attempt">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>
                                            <h3 class="form-section">Admin Information</h3>
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-2 control-label">Admin Email</label>
                                                <div class="col-md-10">
                                                    <div class="input-icon">
                                                        <input value="<?php echo $iSettings['admin_email']; ?>"  name="admin_email" type="email" class="form-control" placeholder="Admin Email">
                                                        <div class="form-control-focus"> </div>
                                                        <i class="fa fa-envelope-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <button name="submit" type="submit" class="btn elegant">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> <?php echo date("Y"); ?> &copy; <?php echo PLATFORM_NAME; ?>.</div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/respond.min.js"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
         <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo BASE_URL; ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo BASE_URL; ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">
            var FormValidation = function () {
                var handleValidation = function() {
                    $('.form-horizontal').validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        rules: {
                            admin_email: {
                                required: true,
                                email: true  
                            }
                        },

                        invalidHandler: function (event, validator) { //display error alert on form submit              
                            $('.alert-danger', $('.form-horizontal')).show();
                        },
                        highlight: function (element) { // hightlight error inputs
                            $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },
                        unhighlight: function (element) { // revert the change done by hightlight
                            $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                        },
                        success: function (label) {
                            label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                }

                return {
                    init: function () {
                        handleValidation();
                    }
                };
            }();

            jQuery(document).ready(function() {
                FormValidation.init();
            });
        </script>
    </body>

</html>