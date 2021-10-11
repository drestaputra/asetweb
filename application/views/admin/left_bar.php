<?php $cont=$this->uri->segment(2, 0); ?>
<?php $url1=$this->uri->segment(1, 0); ?>
<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Menu
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li <?php if (isset($cont) AND trim($cont) == "dashboard"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('admin/dashboard'); ?>">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>Dashboard</span>
										</a>
									</li>
									<!-- user menu -->
									<li class="nav-parent <?php if (!empty($url1) AND (trim($url1) == 'user')): ?>nav-expanded nav-active<?php endif ?>">
										<a>
											<i class="fa fa-users" aria-hidden="true"></i>
											<span>User</span>
										</a>
										<ul class="nav nav-children">
											<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="user"): ?>
												 class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('user/user/index'); ?>">
													<i class="fa fa-users" aria-hidden="true"></i>
													<span>Android</span>
												</a>
											</li>
											<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="koordinator"): ?>
										 	class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('user/koordinator/index'); ?>">
													<i class="fa fa-users" aria-hidden="true"></i>
													<span>Koordinator</span>
												</a>
											</li>
											<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="pengurus_barang"): ?>
										 	class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('user/pengurus_barang/index'); ?>">
													<i class="fa fa-users" aria-hidden="true"></i>
													<span>Pengurus Barang</span>
												</a>
											</li>
										</ul>
									</li>
								
									
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="opd"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('opd/index'); ?>">
											<i class="fa fa-building-o" aria-hidden="true"></i>
											<span>OPD</span>
										</a>
									</li>
									<li class="nav-parent <?php if (!empty($url1) AND (trim($url1) == 'aset') OR trim($url1) == 'pemanfaatan' OR trim($url1) == "saran_pemanfaatan") : ?>nav-expanded nav-active<?php endif ?>">
										<a>
											<i class="fa fa-book" aria-hidden="true"></i>
											<span>Aset</span>
										</a>
										<ul class="nav nav-children">
											<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="aset"): ?>
												 class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('aset/index'); ?>">
													<i class="fa fa-map-marker" aria-hidden="true"></i>
													<span>Data Aset Tanah</span>
												</a>
											</li>
											<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="pemanfaatan"): ?>
												 class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('pemanfaatan/index'); ?>">
													<i class="fa fa-bars" aria-hidden="true"></i>
													<span>Pemanfaatan</span>
												</a>
											</li>
											<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="saran_pemanfaatan"): ?>
												 class="nav-active"
											<?php endif ?>>
												<a href="<?php echo base_url('saran_pemanfaatan/index'); ?>">
													<i class="fa fa-comments" aria-hidden="true"></i>
													<span>Saran Pemanfaatan</span>
												</a>
											</li>	
										</ul>
									</li>	
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="berita"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('berita/index'); ?>">
											<i class="fa fa-newspaper-o" aria-hidden="true"></i>
											<span>Artikel</span>
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