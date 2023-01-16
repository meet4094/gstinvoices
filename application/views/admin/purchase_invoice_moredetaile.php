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
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
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
        <?php $iData['SelectedMenu'] = array("MainMenu" => "purchaseinvoice", "SubMenu" => ""); ?>
        <?php echo $this->load->view('admin/sidebar', $iData, true); ?>
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Begin: life time stats -->
                        <div class="invoice-box">
                            <table cellpadding="0" cellspacing="0">
                                <tr class="top">
                                    <td>
                                        <h1 class="margin-bottom-5 margin-top-10"><?php echo $ShopperLists['trade_name'];
                                                                                    ?></h1>
                                        <h5 class="address"><?php echo $ShopperLists['address'];
                                                            ?></h5>
                                        <h4 class="mobile margin-bottom-5">Mo. +91-<?php echo $ShopperLists['mobile_number']
                                                                                    ?></h4>
                                    </td>
                                </tr>
                            </table>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="invoiceto">
                                        <div class="col-md-6">
                                            <h2>Invoice to:</h2>
                                            <!-- <hr> -->
                                            <h3 class="customer"><?php echo $UserLists['trade_name']
                                                                    ?></h3>
                                            <p class="address"><?php echo $UserLists['address']
                                                                ?></p>
                                            <div class="details">
                                                <h5>Place of Supply : </h5>
                                                <h5 class="supply"><?php //echo $customerDetailes['place_of_supply']
                                                                    ?></h5>
                                            </div>
                                            <div class="details">
                                                <h5>GSTIN No : </h5>
                                                <h5 class="gstno"><?php echo $UserLists['gst_number']
                                                                    ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="invoicedate">
                                                <div>
                                                    <h5 class="bold">Date :</h5>
                                                    <h5 class="bold">Invoice :</h5>
                                                </div>
                                                <div>
                                                    <h5 class="date"><?php echo $PurchaseInvoices['invoices_date']
                                                                        ?></h5>
                                                    <h5 class="date">1212<?php echo $PurchaseInvoices['invoices_no']
                                                                            ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="table">
                                    <table cellpadding="0" cellspacing="0">
                                        <tr class="heading">
                                            <td class="bold">No</td>
                                            <td class="bold">Details</td>
                                            <td class="bold">HSN/SAC</td>
                                            <td class="bold">Qty</td>
                                            <td class="bold">Rate</td>
                                            <td class="bold">GST%</td>
                                            <td class="bold">Amount</td>
                                        </tr>
                                        <tr class="item">
                                            <td>1.</td>
                                            <?php foreach ($ProductLists as $key => $ProductList) {
                                            ?>
                                                <td><?php echo $ProductList['product_name']
                                                    ?></td>
                                                <td><?php echo $ProductList['product_code']
                                                    ?></td>
                                            <?php }
                                            ?>
                                            <?php //foreach ($PurchaseInvoicesProducts as $key => $PurchaseInvoicesProduct) {
                                            ?>
                                            <td><?php echo $PurchaseInvoicesProducts['product_quantity']
                                                ?></td>
                                            <td><?php echo $PurchaseInvoicesProducts['product_rate']
                                                ?></td>
                                            <td><?php echo $PurchaseInvoices['gst']
                                                ?></td>
                                            <td><?php echo $PurchaseInvoicesProducts['total_amount']
                                                ?></td>
                                            <?php //}
                                            ?>
                                        </tr>
                                        <tr class="item blank">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
                                <hr>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="upper">
                                        <div class="col-lg-6 one">
                                            <div class="gst">
                                                <h5>GSTIN No :</h5>
                                                <h5 class="gstnumber"> <?php echo $ShopperLists['gst_number'] 
                                                                        ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 subtotal">
                                            <div class="total">
                                                <h5>Subtotal :</h5>
                                                <h5>CGST :</h5>
                                                <h5>SGST :</h5>
                                                <h5>Round Off :</h5>
                                                <h5>Total :</h5>
                                            </div>
                                            <div class="price">
                                                <h5 class="total"><?php echo $PurchaseInvoices['sub_total']
                                                                    ?></h5>
                                                <h5 class="total"><?php echo $PurchaseInvoices['product_cgst']
                                                                    ?>%</h5>
                                                <h5 class="total"><?php echo $PurchaseInvoices['product_sgst']
                                                                    ?>%</h5>
                                                <h5 class="total"><?php echo $PurchaseInvoices['round_off']
                                                                    ?></h5>
                                                <h5 class="total"><?php echo $PurchaseInvoices['invoices_total']
                                                                    ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
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
    <script src="<?php echo BASE_URL; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?php echo BASE_URL; ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?php echo BASE_URL; ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>