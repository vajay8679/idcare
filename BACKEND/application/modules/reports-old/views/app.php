<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php echo getConfig('site_name'); ?> | <?php
            if (!empty($title) && isset($title)): echo ucwords($title);
            endif;
            ?></title>

        <meta name="description" content="ProUI is a Responsive Bootstrap Admin Template created by pixelcave and published on Themeforest.">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>backend_asset/admin/img/favicon.png">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>backend_asset/admin/img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/bootstrap.min.css">
<!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.css">-->


        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/themes.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/themes/fancy.css">
        <link href="<?php echo base_url(); ?>backend_asset/css/plugins/toastr/toastr.min.css" rel="stylesheet">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/modernizr.min.js"></script>
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/bootstrap.min.js"></script>
        <style>
            .btn-primary {
                /*                background-color: #b22b57;*/
                background: linear-gradient(to right, rgba(71,74,127,1) 0%,rgba(178,43,87,1) 100%);
                border-color: #E47EA0;
                color: #ffffff;
            }

            .btn-primary.btn-alt {
                background-color: #b22b57;
                color: #1bbae1;
            }

            .btn-primary:hover {
                background: linear-gradient(to right, rgba(71,74,127,1) 0%,rgba(178,43,87,1) 100%);
                border-color: #E47EA0;
                color: #ffffff;
            }
            .text-primary, .text-primary:hover, a, a:hover, a:focus, a.text-primary, a.text-primary:hover, a.text-primary:focus {
                color: 
                    #b22b57;
            }
        </style>
    </head>
    <body>
        <div id="page-wrapper">


            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations style-alt">


                <!-- Main Container -->
                <div id="main-container">
                    <!--
                                        <header class="navbar navbar-default">
                                             Left Header Navigation 
                                            <ul class="nav navbar-nav-custom">
                                                 Main Sidebar Toggle Button 
                                                <li>
                                                    <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                                        <i class="fa fa-bars fa-fw"></i>
                                                    </a>
                                                </li>
                                                 END Main Sidebar Toggle Button 
                    
                                                 Template Options 
                                                 Change Options functionality can be found in js/app.js - templateOptions() 
                                                 <li class="dropdown">
                                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="gi gi-settings"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-custom dropdown-options">
                                                        <li class="dropdown-header text-center">Header Style</li>
                                                        <li>
                                                            <div class="btn-group btn-group-justified btn-group-sm">
                                                                <a href="javascript:void(0)" class="btn btn-primary" id="options-header-default">Light</a>
                                                                <a href="javascript:void(0)" class="btn btn-primary" id="options-header-inverse">Dark</a>
                                                            </div>
                                                        </li>
                                                        <li class="dropdown-header text-center">Page Style</li>
                                                        <li>
                                                            <div class="btn-group btn-group-justified btn-group-sm">
                                                                <a href="javascript:void(0)" class="btn btn-primary" id="options-main-style">Default</a>
                                                                <a href="javascript:void(0)" class="btn btn-primary" id="options-main-style-alt">Alternative</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li> 
                                                 END Template Options 
                                            </ul>
                                             END Left Header Navigation 
                    
                                             Search Form 
                                             <form action="page_ready_search_results.html" method="post" class="navbar-form-custom">
                                                <div class="form-group">
                                                    <input type="text" id="top-search" name="top-search" class="form-control" placeholder="Search..">
                                                </div>
                                            </form> 
                                             END Search Form 
                    
                                             Right Header Navigation 
                                            <ul class="nav navbar-nav-custom pull-right">
                                                 Alternative Sidebar Toggle Button 
                                                                            <li>
                                                                                 If you do not want the main sidebar to open when the alternative sidebar is closed, just remove the second parameter: App.sidebar('toggle-sidebar-alt'); 
                                                                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt', 'toggle-other');this.blur();">
                                                                                    <i class="gi gi-share_alt"></i>
                                                                                    <span class="label label-primary label-indicator animation-floating">4</span>
                                                                                </a>
                                                                            </li>
                                                 END Alternative Sidebar Toggle Button 
                    
                                                 User Dropdown 
                                                <li class="dropdown">
                                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                                        <img src="<?php echo base_url() . getConfig('site_logo'); ?>" alt="avatar"> <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                                        <li>
                                                            <a href="javascript:void(0)"><i class="fa fa-user fa-fw pull-right"></i> <?php
                    //$user = getUser($this->session->userdata('user_id'));
                    if (!empty($user)) {
                        echo ucwords($user->first_name . " " . $user->last_name);
                    }
                    ?></a>
                                                            <a href="javascript:void(0)" onclick="logout()"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                                        </li>
                                                         <li>
                                                            <a href="page_ready_timeline.html">
                                                                <i class="fa fa-clock-o fa-fw pull-right"></i>
                                                                <span class="badge pull-right">10</span>
                                                                Updates
                                                            </a>
                                                            <a href="page_ready_inbox.html">
                                                                <i class="fa fa-envelope-o fa-fw pull-right"></i>
                                                                <span class="badge pull-right">5</span>
                                                                Messages
                                                            </a>
                                                            <a href="page_ready_pricing_tables.html"><i class="fa fa-magnet fa-fw pull-right"></i>
                                                                <span class="badge pull-right">3</span>
                                                                Subscriptions
                                                            </a>
                                                            <a href="page_ready_faq.html"><i class="fa fa-question fa-fw pull-right"></i>
                                                                <span class="badge pull-right">11</span>
                                                                FAQ
                                                            </a>
                                                        </li> 
                                                         <li class="divider"></li> 
                                                                                            <li>
                                                                                                 <a href="page_ready_user_profile.html">
                                                                                                    <i class="fa fa-user fa-fw pull-right"></i>
                                                                                                    Profile
                                                                                                </a> 
                                                                                                 Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) 
                                                                                                <a href="#modal-user-settings" data-toggle="modal">
                                                                                                    <i class="fa fa-cog fa-fw pull-right"></i>
                                                                                                    Change Password
                                                                                                </a>
                                                                                            </li>
                                                                                            <li class="divider"></li>
                                                                                            <li>
                                                                                                 <a href="page_ready_lock_screen.html"><i class="fa fa-lock fa-fw pull-right"></i> Lock Account</a> 
                                                                                                <a href="javascript:void(0)" onclick="logout()"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                                                                            </li>
                                                                                            <li class="dropdown-header text-center">Activity</li>
                                                                                            <li>
                                                                                                <div class="alert alert-success alert-alt">
                                                                                                    <small>5 min ago</small><br>
                                                                                                    <i class="fa fa-thumbs-up fa-fw"></i> You had a new sale ($10)
                                                                                                </div>
                                                                                                <div class="alert alert-info alert-alt">
                                                                                                    <small>10 min ago</small><br>
                                                                                                    <i class="fa fa-arrow-up fa-fw"></i> Upgraded to Pro plan
                                                                                                </div>
                                                                                                <div class="alert alert-warning alert-alt">
                                                                                                    <small>3 hours ago</small><br>
                                                                                                    <i class="fa fa-exclamation fa-fw"></i> Running low on space<br><strong>18GB in use</strong> 2GB left
                                                                                                </div>
                                                                                                <div class="alert alert-danger alert-alt">
                                                                                                    <small>Yesterday</small><br>
                                                                                                    <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)" class="alert-link">New bug submitted</a>
                                                                                                </div>
                                                                                            </li>
                                                    </ul>
                                                </li>
                                                 END User Dropdown 
                                            </ul>
                                             END Right Header Navigation 
                                        </header>-->
                    <!-- END Header -->
                    <!-- Page content -->
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form action="<?php echo site_url('setting/add_days'); ?>" name="patientForm" method="post">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Patient Days: </div>
                                        <input type="text" name="total_patient_days" class="form-control" value="<?php echo (getConfig('total_patient_days') <= 0) ? $patients_days->tatal_days : getConfig('total_patient_days'); ?>" disabled=""/>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Days on Antibiotic: </div>
                                        <input type="text" name="total_patient_days1" class="form-control" value="<?php echo $total_antibiotic_days; ?>" disabled=""/>
                                    </div> 
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left">ABx Days Per 1000 Total Patient days:</div>
                                        <input type="text" name="total_patient_day2" class="form-control" value="<?php echo $toatl_patient_days_average; ?>" disabled=""/>
                                    </div>
                                    <!--                                    <div class="col-sm-6 col-lg-2">
                                                                            <div class="text-left"><i class="fa fa-redo"></i></div>
                                                                            <input type="submit" class="btn btn-primary btn-sm form-control" value="Submit"/>
                                                                        </div>-->
                                    <!--                                    <div class="col-sm-6 col-lg-2">
                                                                            <div class="text-left"><i class="fa fa-redo"></i></div>
                                                                            <button type="button" class="btn btn-danger btn-sm form-control" onclick="un_days()"><i class="fa fa-undo"></i> Undo</button>
                                                                        </div>-->
                                </form>
                            </div>
                        </div>
                    </div>  
                    <div id="page-content">
                        <!--        <div id="msg"></div>-->
                        <!-- eShop Overview Block -->
                        <?php
                        $message = $this->session->flashdata('success');
                        if (!empty($message)):
                            ?><div class="alert alert-success">
                                <?php echo $message; ?></div><?php endif; ?>
                        <div class="block full">
                            <div class="row text-center">
                                <div class="col-sm-12 col-lg-12">   
                                    <!--                                    <div class="panel panel-default">
                                                                            <div class="panel-body">                               
                                                                                <div class="col-sm-6 col-lg-6">
                                                                                    <select id="steward" name="steward" class="form-control select-2" onchange="getAntibioticByCareUnitSteward()">
                                                                                        <option value="">Select MD Steward</option>
                                    <?php
                                    if (isset($staward) && !empty($careUnit)) {
                                        foreach ($staward as $row) {
                                            $select = "";
                                            if (isset($careUnitID)) {
                                                if ($careUnitID == $row->id) {
                                                    $select = "selected";
                                                }
                                            }
                                            ?>
                                                                                                                        <option value="<?php echo $row->id; ?>" <?php echo $select; ?>><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                                                                    </select>
                                                                                </div> 
                                                                                <div class="col-sm-6 col-lg-6">
                                                                                    <select id="provider_doctor" name="provider_doctor" class="form-control select-2" onchange="actual_dot_vs_new_dot_by_care_unit_provider_doctor()">
                                                                                        <option value="">Select Provider Doctor</option>
                                    <?php
                                    if (isset($doctors) && !empty($doctors)) {
                                        foreach ($doctors as $row) {
                                            ?>
                                                                                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?></select></div>
                                    
                                                                            </div>
                                                                        </div>-->

                                    <div class="panel panel-default">
                                        <div class="panel-body">    

                                            <div class="col-sm-12 col-lg-3">
                                                <?php
//                                                    if (isset($careUnit) && !empty($careUnit)) {
//                                                        foreach ($careUnit as $row) {
//                                                            
                                                ?>
<!--                                                            <button id="careUnit" value="//<?php echo $row->id; ?>" name="careUnit" type='button' class="btn btn-default" onclick="getAntibioticByCareUnit('<?php echo $row->id; ?>')"><?php echo $row->name; ?></button>-->
                                                <?php
//                                                        }
//                                                    }
                                                ?>
                                                <select id="careUnit" name="careUnit" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                                    <option value="">Select Care Unit</option>
                                                    <?php
                                                    if (isset($careUnit) && !empty($careUnit)) {
                                                        foreach ($careUnit as $row) {
                                                            $select = "";
                                                            if (isset($careUnitID)) {
                                                                if ($careUnitID == $row->id) {
                                                                    $select = "selected";
                                                                }
                                                            }
                                                            ?>
                                                            <option value="<?php echo $row->id; ?>" <?php echo $select; ?>><?php echo $row->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>


                                                <!--                                                                <div class="col-sm-1 col-lg-1">
                                                                                                                    <div class="text-left">Filter:</div>
                                                                                                                </div>-->



                                            </div>
                                            <div class="col-sm-12 col-lg-3">
                                                <select id="provider_doctor" name="provider_doctor" class="form-control select-2" onchange="getAntibioticByCareUnitProviderId()">
                                                    <option value="">Select Provider MD</option>
                                                    <?php
                                                    if (isset($doctors) && !empty($doctors)) {
                                                        foreach ($doctors as $row) {
                                                            ?>
                                                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?></select></div>
                                            <div class="col-sm-12 col-lg-3">
                                                <select id="steward" name="steward" class="form-control select-2" onchange="getAntibioticByCareUnitStewardPrice()">
                                                    <option value="">Select MD Steward</option>
                                                    <?php
                                                    if (!empty($staward)) {
                                                        foreach ($staward as $row) {
                                                            ?>
                                                            <option value="<?php echo $row->id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?></select></div> 
                                            <div class="col-sm-12 col-lg-3">
                                                <select id="RX" name="RX" class="form-control select-2" onchange="getAntibioticByCareUnit('', this.value);getAntibioticByCareUnitProviderId();">
                                                    <option value="">Select Antibiotic</option>
                                                    <?php
                                                    if (!empty($initial_rx)) {
                                                        foreach ($initial_rx as $row) {
                                                            ?>
                                                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?></select></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- eShop Overview Title -->
                            <div class="block-title">

                            </div>
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne">
                                                Antibiotics by Provider MD
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="This section will represent the data of antibiotics days based on selected provider, once you select  any provider that provider will be compared with the average of rest providers."
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>


                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="col-lg-12 col-sm-12">
                                                    <h5><strong>Antibiotics by Provider MD</strong></h5>
                                                    <div id='Graph-chart21' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas21"></canvas>
                                                    </div>
                                                </div>
                                                </br>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Antibiotics by Provider MD Comparison</strong></h5>
                                                    <div id='Graph-chart22' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas22"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>REST</strong></h5>
                                                    <div id='Graph-chart23' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas23"></canvas>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-success">

                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne1">
                                                Antibiotics Price By Provider
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="This section will represent the cost of antibiotics pescribed based on selected Care Unit, Provider and MD Steward. "
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Antibiotics Price By Provider</strong></h5>
                                                    <div id='Graph-chart24' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas24"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Antibiotics Price By MD Steward</strong></h5>
                                                    <div id='Graph-chart25' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas25"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-success">

                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne2">
                                                Antibiotics Percentage by Facility and MD Steward
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="This section will represent antibiotics percentage based on selected facility and steward"
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Antibiotics Percentage By Facility</strong></h5>
                                                    <div id='Graph-chart' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Antibiotics Percentage by MD Steward</strong></h5>
                                                    <div id='Graph-chart1' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas1"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-success">

                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne3">
                                                Days Saved by Facility 
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="This section represents percentage of days saved by facility and comparison of actual dot and Initial DOT."
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne3" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Days Saved by Facility in percentage</strong></h5>
                                                    <div id='Graph-chart2'>
                                                        <canvas id="canvas2"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong>Actual v/s Initial DOT by Facility (Actual Figures)</strong></h5>
                                                    <div id='Graph-chart4'>
                                                        <canvas id="canvas4"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-success">

                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne4">
                                                Actual v/s Initial DOT by MD Steward and Provider MD
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="
                                                   This section represents the comparison of initial DOT and Actual DOT based on MD Steward and Provider MD."
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne4" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong> Actual v/s Initial DOT by MD Steward</strong></h5>
                                                    <div id='Graph-chart5' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas5"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong> Actual v/s Initial DOT by Provider MD</strong></h5>
                                                    <div id='Graph-chart6' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas6"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-success">

                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                data-toggle="collapse"
                                                data-target="#collapseOne5">
                                                Rx and Dx percentage by days
                                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                   title="" data-original-title="This section represents the percentage of Rx and Dx by Days."
                                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne5" class="panel-collapse collapse">

                                            <div class="panel-body">
                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong> Rx % by Days</strong></h5>
                                                    <div id='Graph-chart31' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas31"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <h5><strong> Dx % by Days</strong></h5>
                                                    <div id='Graph-chart32' style="min-width:250px; min-height: 320px;">
                                                        <canvas id="canvas32"></canvas>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Antibiotics by MD Steward</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id='Graph-chart1'>
                                        <canvas id="canvas1"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Days Saved by Facility</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart2'>
                                        <canvas id="canvas2"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Actual v/s Initial DOT by Facility</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart4'>
                                        <canvas id="canvas4"></canvas>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Actual v/s Initial DOT by MD Steward</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart5'>
                                        <canvas id="canvas5"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Actual v/s Initial DOT by Provider Doctor</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart6'>
                                        <canvas id="canvas6"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <!-- remain -->

                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Dx % by Actual DOT</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart3'>
                                        <canvas id="canvas3"></canvas>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="block-title">
                                <h2><strong>Reports:</strong> Total agreement</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="width:50%;" id='Graph-chart7'>
                                        <canvas id="canvas7"></canvas>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- END Page Content -->
                    <script>
                        $('[data-toggle="tooltip"]').tooltip();
                    </script>
