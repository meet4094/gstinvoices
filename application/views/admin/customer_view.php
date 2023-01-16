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
            <?php $iData['SelectedMenu'] = array("MainMenu" => "customer", "SubMenu" => ""); ?>
            <?php echo $this->load->view('admin/sidebar', $iData, true); ?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> Customer
                        <small>listing</small>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-body">
                                    <div class="table-container">
                                        <div class="table-actions-wrapper">
                                            <a href="<?php echo BASE_URL . "admin/customer/add"; ?>" class="btn btn-sm elegant">
                                                <i class="fa fa-plus"></i>
                                                <span class="title">Add Customer</span>
                                            </a>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover" id="customerList">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="2%"> # </th>
                                                    <th width="5%"> Trade Name </th>
                                                    <th width="5%"> Leagel Name </th>
                                                    <th width="5%"> Dealer Name</th>
                                                    <th width="3%"> Mobile </th>
                                                    <th width="3%"> Gst Number </th>
                                                    <th width="3%"> Place Of Supply </th>
                                                    <th width="5%"> Address</th>
                                                    <th width="3%"> Created Date </th>
                                                    <th width="3%"> Status </th>
                                                    <th width="3%"> Actions </th>
                                                    <th width="3%"> Invoices </th>
                                                </tr>
                                                <tr role="row" class="filter">
                                                    <td> </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter" name="trade_name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter" name="leagel_name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter" name="dealer_name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter" name="mobile_number">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-filter" name="gst_number">
                                                    </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>
                                                        <select name="is_del" class="form-control form-filter">
                                                            <option value="">Select...</option>
                                                            <option value="1">Disable</option>
                                                            <option value="0">Enable</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                                            <i class="fa fa-search"></i> Search</button>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- End: life time stats -->
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
        <script type="text/javascript">
            var TableDatatablesAjax = function() {
                var initPickers = function() {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        autoclose: true
                    });
                }

                var handleRecords = function() {
                    var grid = new Datatable();
                    grid.init({
                        src: $("#customerList"),
                        onSuccess: function(grid, response) {
                            // grid:        grid object
                            // response:    json object of server side ajax response
                            // execute some code after table records loaded
                        },
                        onError: function(grid) {
                            // execute some code on network or other general error  
                        },
                        onDataLoad: function(grid) {
                            // execute some code on ajax data load
                        },
                        loadingMessage: 'Loading...',
                        dataTable: {
                            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                            "bSort": false,
                            "lengthMenu": [
                                [10, 20, 50, 100, 150, -1],
                                [10, 20, 50, 100, 150, "All"] // change per page values here
                            ],
                            "pageLength": 10, // default record count per page
                            "responsive": true,
                            "ajax": {
                                "url": "<?php echo BASE_URL; ?>admin/customer/customer_list/<?php echo $id; ?>", // ajax source
                            }
                        }
                    });
                }

                return {
                    init: function() {
                        initPickers();
                        handleRecords();
                    }
                };
            }();

            jQuery(document).ready(function() {
                TableDatatablesAjax.init();
            });
        </script>
    </body>

    </html>