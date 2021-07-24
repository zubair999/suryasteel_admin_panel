<div class="js-fix-footer footer border-top-2">
   <div class="container-fluid page__container page-section d-flex flex-column">
      <p class="text-70 brand mb-24pt">
         <img class="brand-icon"
            src="<?php echo ADMIN ?>assets/images/logo/black-70@2x.png"
            width="30"> <?php echo get_settings('app_name') ?>
      </p>
      <p class="text-muted mb-0 small"><?php echo get_settings('app_description') ?></p>
   </div>
   <div class="pb-16pt pb-lg-24pt">
      <div class="container-fluid page__container">
         <div class="bg-dark rounded page-section py-lg-32pt px-16pt px-lg-24pt">
            <div class="row">
               <div class="col-md-2 col-sm-4 mb-24pt mb-md-0">
                  <p class="text-white-70 mb-8pt"><strong>Follow us</strong></p>
                  <nav class="nav nav-links nav--flush">
                     <a href="#"
                        class="nav-link mr-8pt"><img src="<?php echo ADMIN ?>assets/images/icon/footer/facebook-square@2x.png"
                        width="24"
                        height="24"
                        alt="Facebook"></a>
                     <a href="#"
                        class="nav-link mr-8pt"><img src="<?php echo ADMIN ?>assets/images/icon/footer/twitter-square@2x.png"
                        width="24"
                        height="24"
                        alt="Twitter"></a>
                     <a href="#"
                        class="nav-link mr-8pt"><img src="<?php echo ADMIN ?>assets/images/icon/footer/vimeo-square@2x.png"
                        width="24"
                        height="24"
                        alt="Vimeo"></a>
                     <!-- <a href="#" class="nav-link"><img src="<?php echo ADMIN ?>assets/images/icon/footer/youtube-square@2x.png" width="24" height="24" alt="YouTube"></a> -->
                  </nav>
               </div>
               <div class="col-md-6 col-sm-4 mb-24pt mb-md-0 d-flex align-items-center">
                  <a href=""
                     class="btn btn-outline-white">English <span class="icon--right material-icons">arrow_drop_down</span></a>
               </div>
               <div class="col-md-4 text-md-right">
                  <p class="mb-8pt d-flex align-items-md-center justify-content-md-end">
                     <a href=""
                        class="text-white-70 text-underline mr-16pt">Terms</a>
                     <a href=""
                        class="text-white-70 text-underline">Privacy policy</a>
                  </p>
                  <p class="text-white-50 small mb-0">Copyright 2019 &copy; All rights reserved.</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- // END drawer-layout__content -->
<!-- drawer -->
<div class="mdk-drawer js-mdk-drawer"
   id="default-drawer">
   <div class="mdk-drawer__content">
      <div class="sidebar sidebar-dark sidebar-left"
         data-perfect-scrollbar>
         <a href="<?php echo base_url('secure/admin/dashboard'); ?>"
            class="sidebar-brand ">
         <img class="sidebar-brand-icon"
            src="<?php echo ADMIN ?>assets/images/logo/accent-teal-100@2x.png">
         <span><?php echo get_settings('app_name') ?></span>
         </a>
         
         
         <div class="sidebar-heading">Overview</div>
         <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
               <a class="sidebar-menu-button"
                  href="<?php echo base_url('dashboard'); ?>">
               <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
               <span class="sidebar-menu-text">Dashboard</span>
               </a>
            </li>

            <?php if(in_array('media', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('media'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Media</span>
                  </a>
               </li>
            <?php endif; ?>
            

            <?php if(in_array('add-category', $this->permission) || in_array('edit-category', $this->permission) || in_array('category', $this->permission) || in_array('delete-category', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('category'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Category</span>
                  </a>
               </li>
            <?php endif; ?>

            

            <li class="sidebar-menu-item">
               <a class="sidebar-menu-button"
                  href="<?php echo base_url('orders'); ?>">
               <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
               <span class="sidebar-menu-text">Orders</span>
               </a>
            </li>

            
            
            <li class="sidebar-menu-item">
               <a class="sidebar-menu-button"
                  href="<?php echo base_url('products'); ?>">
               <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
               <span class="sidebar-menu-text">Products</span>
               </a>
            </li>

            

            


            <?php if(in_array('add-staff', $this->permission) || in_array('edit-staff', $this->permission) || in_array('view-staff', $this->permission) || in_array('delete-staff', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('view-staff'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Staff</span>
                  </a>
               </li>
            <?php endif; ?>
            

            <?php if(in_array('add-customer', $this->permission) || in_array('edit-customer', $this->permission) || in_array('view-customer', $this->permission) || in_array('delete-customer', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('view-customer'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Customer</span>
                  </a>
               </li>
            <?php endif; ?>


            <?php if(in_array('add-roles', $this->permission) || in_array('edit-roles', $this->permission) || in_array('view-roles', $this->permission) || in_array('delete-roles', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('view-roles'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Roles</span>
                  </a>
               </li>
            <?php endif; ?>


            <?php if(in_array('view-logs', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button"
                     href="<?php echo base_url('view-logs'); ?>">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     <span class="sidebar-menu-text">Logs</span>
                  </a>
               </li>
            <?php endif; ?>



            
               
            

            

            <?php if(in_array('system-setting', $this->permission)): ?>
               <li class="sidebar-menu-item">
                  <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#staff_menu" aria-expanded="true">
                     <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                     Settings
                     <span class="ml-auto sidebar-menu-toggle-icon"></span>
                  </a>
                  <ul class="sidebar-submenu sm-indent collapse show" id="staff_menu" style="">
                     <li class="sidebar-menu-item">
                           <a class="sidebar-menu-button" href="<?php echo base_url('system-setting'); ?>">
                              <span class="sidebar-menu-text">System Setting</span>
                           </a>
                     </li>
                  </ul>
               </li>
            <?php endif; ?>



         </ul>
      </div>
   </div>
</div>


<!-- // END drawer -->


</div>




<!-- // END drawer-layout -->
<!-- App Settings FAB -->
<div id="app-settings">
   <app-settings layout-active="app"
      :layout-location="{
         'compact': 'compact-ui-tables.html',
         'mini': 'mini-ui-tables.html',
         'app': 'ui-tables.html',
         'boxed': 'boxed-ui-tables.html',
         'sticky': 'sticky-ui-tables.html',
         'default': 'fixed-ui-tables.html'
      }"
      sidebar-type="light"
      sidebar-variant="bg-body">
   </app-settings>
</div>