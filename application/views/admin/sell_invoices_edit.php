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
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo BASE_URL; ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/themes/light2.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo BASE_URL; ?>assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>favicon.ico" />
</head>
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
        <?php $iData['SelectedMenu'] = array("MainMenu" => "sellinvoices", "SubMenu" => "sellinvoices"); ?>
        <?php echo $this->load->view('admin/sidebar', $iData, true); ?>
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN PAGE TITLE-->
                <h3 class="page-title"> Edit Sell Invoice
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
                                        <strong>Error!</strong> Failed to edit Sell Invoice. Please try again.
                                    </div>
                                <?php } elseif (isset($statuscode) && $statuscode == 1) { ?>
                                    <div class="alert alert-success">
                                        <strong>Success!</strong> A Sell Invoice has been edited.
                                    </div>
                                <?php } ?>
                                <form action="<?php echo BASE_URL . "admin/sell_invoice/edit/" . $edit_sell_invoices['id']; ?>" role="form" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below.
                                    </div>
                                    <input type="hidden" value="<?php echo $edit_sell_invoices['id']; ?>" name="id">
                                    <div class="form-body">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">User Name</label>
                                            <div class="col-md-10">
                                                <select class="form-control name" name="user_id">
                                                    <?php foreach ($users as $key => $user) { ?>
                                                        <option <?php if ($user['uid'] == $edit_sell_invoices['user_id']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $user['uid']; ?>"><?php echo $user['trade_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Customer Name</label>
                                            <div class="col-md-10">
                                                <select class="form-control name" name="customer_id">
                                                    <?php foreach ($customers as $key => $customer) { ?>
                                                        <option <?php if ($customer['id'] == $edit_sell_invoices['customer_id']) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['trade_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Invoices Date</label>
                                            <div class="col-md-10">
                                                <input type="date" value="<?php echo ($edit_sell_invoices['invoices_date']) ?>" name="invoices_date" class="form-control" placeholder="Enter invoices date">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Invoices No</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['invoices_no']) ?>" name="invoices_no" class="form-control" placeholder="Enter invoices no...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">GST Number</label>
                                            <div class="col-md-10">
                                                <input type="text" value="<?php echo ($edit_sell_invoices['gst']) ?>" name="gst_number" class="form-control" placeholder="Enter your GST number...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Product</label>
                                            <div class="col-md-10">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr role="row" class="heading" style="background-color: #d3d3d3;">
                                                            <th>No</th>
                                                            <th width="25%">Name</th>
                                                            <th>HSN/</th>
                                                            <th>Quantity</th>
                                                            <th>Rate</th>
                                                            <th>Amount</th>
                                                            <th width="2%"><input type="button" value="+" class="add btn btn-primary" onclick="addBox()"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($edit_product_detailes as $key => $edit_product_detaile) { ?>
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <select class="form-control name" name="product_id[]">
                                                                        <?php foreach ($products as $key => $product) { ?>
                                                                            <option <?php if ($edit_product_detaile['product_id'] == $product['id']) {
                                                                                        echo 'selected';
                                                                                    } ?> value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number" value="<?php echo $edit_product_detaile['product_hsn_sac']; ?>" name="product_hsn_sac[]" class="form-control"></td>
                                                                <td><input type="number" value="<?php echo $edit_product_detaile['product_quantity']; ?>" name="product_quantity[]" class="form-control"></td>
                                                                <td><input type="number" value="<?php echo $edit_product_detaile['product_rate']; ?>" name="product_rate[]" class="form-control"></td>
                                                                <td><input type="number" value="<?php echo $edit_product_detaile['total_amount']; ?>" name="total_amount[]" class="form-control"></td>
                                                                <td><input type="button" value="-" onclick="removeBox(this)" class="add btn btn-danger"></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tbody id=tex></tbody>
                                                </table>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Sub Total</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['sub_total']) ?>" name="sub_total" class="form-control" placeholder="Enter sub total...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">SGST</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['product_sgst']) ?>" name="product_sgst" class="form-control" placeholder="Enter SGST...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">CGST</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['product_sgst']) ?>" name="product_sgst" class="form-control" placeholder="Enter CGST...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Round Off</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['round_off']) ?>" name="round_off" class="form-control" placeholder="Enter rorund off...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Total</label>
                                            <div class="col-md-10">
                                                <input type="number" value="<?php echo ($edit_sell_invoices['invoices_total']) ?>" name="invoices_total" class="form-control" placeholder="Enter total...">
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label">Status</label>
                                            <div class="col-md-10">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input value="0" type="radio" id="radio53" name="is_del" <?php if ($edit_sell_invoices['is_del'] == 0) {
                                                                                                                        echo 'checked="checked"';
                                                                                                                    } ?> class="md-radiobtn">
                                                        <label for="radio53">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Enable </label>
                                                    </div>
                                                    <div class="md-radio has-error">
                                                        <input value="1" type="radio" id="radio54" name="is_del" <?php if ($edit_sell_invoices['is_del'] == 1) {
                                                                                                                        echo 'checked="checked"';
                                                                                                                    } ?> class="md-radiobtn">
                                                        <label for="radio54">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Disable </label>
                                                    </div>
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
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/pages/scripts/components-color-pickers.min.js" type="text/javascript"></script>
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
        var counter = 1;
        var textBox = "";
        var tex = document.getElementById('tex');

        function addBox() {
            var div = document.createElement("tr");
            div.setAttribute("id", "text_" + counter);

            textBox = '<tr><td></td><td><select class="form-control name="product_id[]"><?php foreach ($products as $key => $product) { ?><option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?></option><?php } ?></select></td><td><input type="number" name="product_hsn_sac[]" class="form-control"></td><td><input type="number" name="product_quantity[]" class="form-control"></td><td><input type="number" name="product_rate[]" class="form-control"></td><td><input type="number" name="total_amount[]" class="form-control"></td><td><input type="button" value="-" onclick ="removeBox(this)" class="add btn btn-danger"></td></tr>';
            div.innerHTML = textBox;

            tex.appendChild(div);

            counter++;
        }

        function removeBox(ele) {
            ele.parentNode.parentNode.remove();
        }

        var FormValidation = function() {
            var handleValidation = function() {
                $('.form-horizontal').validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    rules: {
                        name: {
                            minlength: 2,
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Name is required."
                        }
                    },

                    invalidHandler: function(event, validator) { //display error alert on form submit              
                        $('.alert-danger', $('.form-horizontal')).show();
                    },
                    highlight: function(element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },
                    unhighlight: function(element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },
                    success: function(label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            }

            return {
                init: function() {
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