<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <title>Dashboard Admin | <?php echo function_lib::get_config_value('website_name'); ?></title>
    <meta name="keywords" content="Dashboard Admin - <?php echo function_lib::get_config_value('website_name'); ?>" />
    <meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/morris/morris.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">

    <!-- Head Libs -->
    <script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
    <section class="body">

        <?php function_lib::getHeader(); ?>

        <div class="inner-wrapper">
            <!-- start: sidebar -->
            <?php function_lib::getLeftMenu(); ?>
            <!-- end: sidebar -->

            <section role="main" class="content-body">
                <header class="page-header">
                    <h2>Dashboard</h2>
                    
                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="<?php echo base_url('admin/dashboard'); ?>">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li><span>Dashboard</span></li>
                        </ol>

                        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
                    </div>
                </header>

                <div class="row m-l-5 m-r-5">

                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Site Configuration</h3>
                    </div>
                    <div class="panel panel-body">
                        <?php if (trim($this->input->get('status'))!=""): ?>
                            <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                        <?php endif ?>
                        <form id="form" method="POST" class="form-horizontal">
                            <section class="panel">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Website Name <span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="website_name" value="<?php echo ($this->input->post('website_name')!="")?$this->input->post('website_name'):$website_name; ?>" class="form-control" placeholder="Website Name.." required/>

                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Website SEO <span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="website_seo" value="<?php echo ($this->input->post('website_seo')!="")?$this->input->post('website_seo'):$website_seo; ?>" class="form-control" placeholder="Website SEO.." required/>

                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">App Name <span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="app_name" value="<?php echo ($this->input->post('app_name')!="")?$this->input->post('app_name'):$app_name; ?>" class="form-control" placeholder="App name.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">App Description <span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="app_description" value="<?php echo ($this->input->post('app_description')!="")?$this->input->post('app_description'):$app_description; ?>" class="form-control" placeholder="App description.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">App Developer <span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="app_dev" value="<?php echo ($this->input->post('app_dev')!="")?$this->input->post('app_dev'):$app_dev; ?>" class="form-control" placeholder="App developer.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">App Developer Website<span class="required">*</span></label>
                                        <div class="col-sm-10">

                                            <input type="text" name="app_dev_web" value="<?php echo ($this->input->post('app_dev_web')!="")?$this->input->post('app_dev_web'):$app_dev_web; ?>" class="form-control" placeholder="App developer Website.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instagram<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_ig" value="<?php echo ($this->input->post('app_contact_ig')!="")?$this->input->post('app_contact_ig'):$app_contact_ig; ?>" class="form-control" placeholder="Username Instagram.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Facebook<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_fb" value="<?php echo ($this->input->post('app_contact_fb')!="")?$this->input->post('app_contact_fb'):$app_contact_fb; ?>" class="form-control" placeholder="Username Facebook.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Twitter<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_twitter" value="<?php echo ($this->input->post('app_contact_twitter')!="")?$this->input->post('app_contact_twitter'):$app_contact_twitter; ?>" class="form-control" placeholder="Username Twitter.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Email<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_mail" value="<?php echo ($this->input->post('app_contact_mail')!="")?$this->input->post('app_contact_mail'):$app_contact_mail; ?>" class="form-control" placeholder="Username whatsapp.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Whatsapp<span class="required">* Gunakan kode negara, 62xxxxx</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_wa" value="<?php echo ($this->input->post('app_contact_wa')!="")?$this->input->post('app_contact_wa'):$app_contact_wa; ?>" class="form-control" placeholder="Nomor Whatsapp.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">No. Telp<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="app_contact_phone" value="<?php echo ($this->input->post('app_contact_phone')!="")?$this->input->post('app_contact_phone'):$app_contact_phone; ?>" class="form-control" placeholder="Nomor HP.." required/>
                                        </div>                                            
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Alamat<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="app_contact_address"><?php echo ($this->input->post('app_contact_address')!="")?$this->input->post('app_contact_address'):$app_contact_address; ?></textarea>
                                        </div>                                            
                                    </div>
                                </div>
                                <footer class="panel-footer">
                                    <div class="row">
                                        <div class="col-sm-9 col-sm-offset-3">
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </footer>
                            </section>
                        </form>
                    </div>

                </div>
                <!-- end: page -->
            </section>
        </div>

        <?php $this->load->view('admin/right_bar'); ?>
    </section>

    <!-- Vendor -->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>



    <!-- Theme Base, Components and Settings -->
    <script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>

    <!-- Theme Custom -->
    <script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>

    <!-- Theme Initialization Files -->
    <script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>

</body>
</html>