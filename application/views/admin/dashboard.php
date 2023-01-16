<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title><?php echo PLATFORM_NAME; ?> | Dashboard</title>
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
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
        <?php $iData['SelectedMenu'] = array("MainMenu" => "dashboard", "SubMenu" => ""); ?>
        <?php echo $this->load->view('admin/sidebar', $iData, true); ?>
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN PAGE TITLE-->
                <h3 class="page-title"> Dashboard
                    <small>dashboard & statistics</small>
                </h3>
                <!-- END PAGE TITLE-->
                <!-- END PAGE HEADER-->
                <!-- BEGIN DASHBOARD STATS -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat blue" href="<?php echo BASE_URL . "admin/user/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iUserCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of registered users </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat green" href="<?php echo BASE_URL . "admin/product/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-comments"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iproductCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of product </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat yellow" href="<?php echo BASE_URL . "admin/shopper/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-bar-chart-o"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iShoppersCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of shopper </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat red-pink" href="<?php echo BASE_URL . "admin/customer/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-bar-chart-o"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iCustomerCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of customer </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat purple" href="<?php echo BASE_URL . "admin/purchase_invoice/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-bar-chart-o"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iPurchaseInvoicesCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of purchase invoice</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat grey" href="<?php echo BASE_URL . "admin/sell_invoice/view"; ?>">
                            <div class="visual">
                                <i class="fa fa-bar-chart-o"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value="<?php echo $iSellInvoicesCount; ?>">0</span>
                                </div>
                                <div class="desc"> # no of sell invoice </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- BEGIN ROW -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN CHART PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-bar-chart font-blue"></i>
                                    <span class="caption-subject bold font-blue"> User Registered</span>
                                    <span class="caption-helper">30 days(daily stats..)</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="user_statistics_loading">
                                    <img src="<?php echo BASE_URL; ?>assets/global/img/loading.gif" alt="loading" />
                                </div>
                                <div id="user_statistics_content" class="display-none">
                                    <div id="user_statistics" class="chart"> </div>
                                </div>
                            </div>
                        </div>
                        <!-- END CHART PORTLET-->
                    </div>
                </div>
                <!-- END ROW -->
                <!-- END DASHBOARD STATS -->
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
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
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
        var Dashboard = function() {
            return {
                initUsersCharts: function() {
                    if (!jQuery.plot) {
                        return;
                    }

                    function showChartTooltip(x, y, xValue, yValue) {
                        $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                            position: 'absolute',
                            display: 'none',
                            top: y - 40,
                            left: x - 40,
                            border: '0px solid #ccc',
                            padding: '2px 6px',
                            'background-color': '#fff'
                        }).appendTo("body").fadeIn(200);
                    }

                    var data = [];
                    var totalPoints = 250;

                    // random data generator for plot charts

                    function getRandomData() {
                        if (data.length > 0) data = data.slice(1);
                        // do a random walk
                        while (data.length < totalPoints) {
                            var prev = data.length > 0 ? data[data.length - 1] : 50;
                            var y = prev + Math.random() * 10 - 5;
                            if (y < 0) y = 0;
                            if (y > 100) y = 100;
                            data.push(y);
                        }
                        // zip the generated y values with the x values
                        var res = [];
                        for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                        return res;
                    }

                    function randValue() {
                        return (Math.floor(Math.random() * (1 + 50 - 20))) + 10;
                    }

                    var visitors = [<?php foreach ($iUserRegState as $key => $iUser) { ?>['<?php echo $iUser['day']; ?>/<?php echo $iUser['month']; ?>/<?php echo $iUser['year']; ?>', <?php echo $iUser['user']; ?>] <?php if ($key != count($iUserRegState) - 1) { ?>, <?php }
                                                                                                                                                                                                                                                                    } ?>];


                    if ($('#user_statistics').size() != 0) {
                        $('#user_statistics_loading').hide();
                        $('#user_statistics_content').show();

                        var plot_statistics = $.plot($("#user_statistics"), [{
                                data: visitors,
                                lines: {
                                    fill: 0.6,
                                    lineWidth: 0
                                },
                                color: ['#3598dc']
                            }, {
                                data: visitors,
                                points: {
                                    show: true,
                                    fill: true,
                                    radius: 5,
                                    fillColor: "#3598dc",
                                    lineWidth: 3
                                },
                                color: '#fff',
                                shadowSize: 0
                            }],

                            {
                                xaxis: {
                                    tickLength: 0,
                                    tickDecimals: 0,
                                    mode: "categories",
                                    min: 0,
                                    font: {
                                        lineHeight: 14,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                yaxis: {
                                    ticks: 5,
                                    tickDecimals: 0,
                                    tickColor: "#eee",
                                    font: {
                                        lineHeight: 14,
                                        style: "normal",
                                        variant: "small-caps",
                                        color: "#6F7B8A"
                                    }
                                },
                                grid: {
                                    hoverable: true,
                                    clickable: true,
                                    tickColor: "#eee",
                                    borderColor: "#eee",
                                    borderWidth: 1
                                }
                            });

                        var previousPoint = null;
                        $("#user_statistics").bind("plothover", function(event, pos, item) {
                            $("#x").text(pos.x.toFixed(2));
                            $("#y").text(pos.y.toFixed(2));
                            if (item) {
                                if (previousPoint != item.dataIndex) {
                                    previousPoint = item.dataIndex;

                                    $("#tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2),
                                        y = item.datapoint[1].toFixed(2);

                                    showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' users');
                                }
                            } else {
                                $("#tooltip").remove();
                                previousPoint = null;
                            }
                        });
                    }
                },

                init: function() {
                    this.initUsersCharts();
                }
            };
        }();

        if (App.isAngularJsApp() === false) {
            jQuery(document).ready(function() {
                Dashboard.init(); // init metronic core componets
            });
        }
    </script>
</body>

</html>