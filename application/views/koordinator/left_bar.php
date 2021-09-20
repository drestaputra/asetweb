<?php $cont=$this->uri->segment(2, 0); ?>
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
										<a href="<?php echo base_url('koordinator/dashboard'); ?>">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>Dashboard</span>
										</a>
									</li>

									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="aset"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('aset/index'); ?>">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<span>Aset Tanah</span>
										</a>
									</li>	
									<li <?php if (isset($url1) AND trim($url1)!="" AND $url1=="verifikasi_aset"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('aset/verifikasi'); ?>">
											<i class="fa fa-check" aria-hidden="true"></i>
											<span>Verifikasi Aset Tanah</span>
										</a>
									</li>	
									
								

								</ul>

							</nav>
				
							
				
							
				
							
						</div>
				
					</div>
				
				</aside>