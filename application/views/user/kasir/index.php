<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Kasir | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Admin - <?php echo function_lib::get_config_value('website_name'); ?>" />
		<meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
		<meta name="author" content="Drestaputra - Inolabs">

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
		
		<!-- flexigrid -->

		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/css/flexigrid.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/button/style.css" />

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
						<h2>Kasir</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Kasir</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="row">
						<?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?>
                            
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4><i class="fa fa-search-plus"> Pencarian</i></h4>
								</div>
								<div class="panel-body">
									<form>
                                        <div class="form-group">
                                            <label class="col-md-3">Username</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-sm" name="username" id="username">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">Email</label>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control input-sm" name="email" id="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">Status</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="status" id="status">
                                                    <option value="">Semua</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="blokir">Blokir</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button class="btn btn-primary pull-right" onclick="grid_reload();return false;"><i class="fa fa-search"></i> Cari</button>
                                        </div>

                                       
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">

						<div class="panel-body">
                            <div class="alert " style="display: none;">
                                <p class="msg"></p>
                            </div>
							<table id="gridview" style="display:none;"></table>
						</div>
					</div>
				</section>
			</div>

			<?php $this->load->view('admin/right_bar'); ?>
		</section>

		<!-- Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-appear/jquery.appear.js"></script>
				
		
		
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>		
		<!-- flexigrid -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/flexigrid/js/flexigrid.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/flexigrid/js/json2.js"></script>
        
		<!-- Examples -->
		<script type="text/javascript">
			 
                    $("#gridview").flexigrid({
                        dataType: 'json',
                        colModel: [
						
                            { display: 'No', name: 'no', width: 30, sortable: true, align: 'right' },
                            { display: 'Aksi', name: 'actions', width: 100, sortable: false, align: 'center' },
                            { display: 'Username', name: 'username', width: 150, sortable: true, align: 'center' },                            
                            { display: 'Koperasi', name: 'koperasi', width: 150, sortable: true, align: 'center' },                            
                            { display: 'Email', name: 'email', width: 200, sortable: true, align: 'center' },
                            { display: 'No. Hp', name: 'no_hp', width: 100, sortable: true, align: 'center' },
                            { display: 'Nama', name: 'nama', width: 150, sortable: true, align: 'center' },
                            { display: 'No. KTP', name: 'no_ktp', width: 100, sortable: true, align: 'center' },
                            { display: 'Status', name: 'status', width: 100, sortable: true, align: 'center' },                            
                        ],
                        buttons: [
                            { display: '<i class="fa fa-plus"> Tambah</i>', name: 'add', bclass: '', onpress: tambah },
                            { separator: true },                            
                            
                        ],
                        buttons_right: [
                        ],
                      
                        sortname: "id",
                        sortorder: "asc",
                        usepager: true,
                        title: ' ',
                        useRp: true,
                        rp: 50,
                        showTableToggleBtn: false,
                        showToggleBtn: true,
                        width: 'auto',
                        height: '300',
                        resizable: false,
                        singleSelect: false
                    });
                    function tambah(){
                    	window.location='<?php echo base_url('user/kasir/tambah'); ?>';
                    }
                                       
                    $(document).ready(function() {
                        grid_reload();
                    });

                   

                    function grid_reload() {
                        var username=$("#username").val();
                        var status=$("#status").val();                        
                        var email=$("#email").val();                        
                        
                        var url_service="?username="+username+
                        "&status="+status+
                        "&email="+email;              
                        $("#gridview").flexOptions({url:'<?php echo base_url(); ?>user/kasir/getData'+url_service}).flexReload();
                    }
                    
                    function delete_kasir(id)
                    {
                        if(confirm('Yakin Menghapus?'))
                        {
                             var jqxhr=$.ajax({
                            url:'<?php echo base_url()?>user/kasir/delete/'+id,
                            type:'get',
                            dataType:'json',
                            
                        });
                        jqxhr.success(function(response){
                            $("div.alert").show();

                            if(response['status']==200)
                            {
                                $("div.alert").removeClass('alert-danger');
                                $("div.alert").addClass('alert-success');
                            }
                            else
                            {
                                $("div.alert").removeClass('alert-success');
                                $("div.alert").addClass('alert-danger');
                            }
                            $("div.alert").find('.msg').html(response['msg']);

                            grid_reload();
                            return false;

                        });
                        jqxhr.error(function(){
                            alert('an error has occurred, please try again.');
                            grid_reload();
                            return false;
                        });
                        }
                        return false;
                       
                    }

                    
                </script>  
          
	</body>
</html>