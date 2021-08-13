<?php $cont=$this->uri->segment(2, 0); ?>
<?php $url1=$this->uri->segment(1, 0); ?>
<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Navigation
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="dashboard"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('super_admin/dashboard'); ?>">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>Dashboard</span>
										</a>
									</li>
									<!-- user menu -->
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="admin"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('admin/index'); ?>">
											<i class="fa fa-users" aria-hidden="true"></i>
											<span>Admin</span>
										</a>
									</li>
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="laporan"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('laporan/index'); ?>">
											<i class="fa fa-file-excel-o" aria-hidden="true"></i>
											<span>Laporan</span>
										</a>
									</li>
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="rekening"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('rekening/index'); ?>">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<span>Aset Tanah</span>
										</a>
									</li>	
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="informasi_program"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('informasi_program/index'); ?>">
											<i class="fa fa-newspaper-o" aria-hidden="true"></i>
											<span>Artikel</span>
										</a>
									</li>	
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="kontak"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('kontak/index'); ?>">
											<i class="fa fa-phone" aria-hidden="true"></i>
											<span>Kontak</span>
										</a>
									</li>	
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="slider"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('slider/index'); ?>">
											<i class="fa fa-image" aria-hidden="true"></i>
											<span>Slider</span>
										</a>
									</li>		
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="pengaturan"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('pengaturan/edit'); ?>">
											<i class="fa fa-cog" aria-hidden="true"></i>
											<span>Pengaturan</span>
										</a>
									</li>	
									
									
								

								</ul>
							</nav>				
							
						</div>
				
					</div>
				
				</aside>