<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler"> </div>
            </li>
            <li class="nav-item start <?php echo $SelectedMenu['MainMenu'] == "dashboard" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/welcome/dashboard"; ?>" class="nav-link">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item start <?php echo $SelectedMenu['MainMenu'] == "user" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/user/view"; ?>" class="nav-link">
                    <i class="fa fa-user-secret" aria-hidden="true"></i>
                    <span class="title">Users</span>
                </a>
            </li>
            <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "shopper" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/shopper/view"; ?>" class="nav-link ">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <span class="title">Shoppers</span>
                </a>
            </li>
            <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "customer" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/customer/view"; ?>" class="nav-link ">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span class="title">Customers</span>
                </a>
            </li>
            <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "product" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/product/view"; ?>" class="nav-link ">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <span class="title">Product</span>
                </a>
            </li>
            <li class="nav-item <?php echo in_array($SelectedMenu['MainMenu'], array('sellchallan', 'purchasechallan')) ? "active" : ""; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <span class="title">Challan</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "sellchallan" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/sell_challan/view";
                                    ?>" class="nav-link ">
                            <span class="title">Sell Challan</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "purchasechallan" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/purchase_challan/view"; ?>" class="nav-link ">
                            <span class="title">Purchase Challan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item <?php echo in_array($SelectedMenu['MainMenu'], array('sellinvoices', 'purchaseinvoices')) ? "active" : ""; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <span class="title">Invoices</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "sellinvoices" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/sell_invoice/view";
                                    ?>" class="nav-link ">
                            <span class="title">Sell Invoices</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "purchaseinvoices" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/purchase_invoice/view"; ?>" class="nav-link ">
                            <span class="title">Purchase Invoices</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item <?php echo in_array($SelectedMenu['MainMenu'], array('sellinvoicespayment', 'purchaseinvoicespayment')) ? "active" : ""; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span class="title">Invoices</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "sellinvoicespayment" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/sell_invoices_payment/view";
                                    ?>" class="nav-link ">
                            <span class="title">Sell Invoices Payment</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "purchaseinvoicespayment" ? "active" : ""; ?>">
                        <a href="<?php echo BASE_URL . "admin/purchase_invoices_payment/view"; ?>" class="nav-link ">
                            <span class="title">Purchase Invoices Payment</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "report" ? "active" : ""; ?>">
                <a href="<?php echo BASE_URL . "admin/report/view"; 
                            ?>" class="nav-link ">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <span class="title">Reports</span>
                </a>
            </li>
            <!-- <li class="nav-item <?php echo $SelectedMenu['MainMenu'] == "settings" ? "active" : ""; ?>">
                <a href="<?php //echo BASE_URL . "admin/user/view"; 
                            ?>" class="nav-link ">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span class="title">Settings</span>
                </a>
            </li> -->
        </ul>
    </div>
</div>