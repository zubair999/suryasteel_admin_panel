<!DOCTYPE html>
<html lang="en"
   dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible"
         content="IE=edge">
      <meta name="viewport"
         content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Admin | Login</title>
      <!-- Prevent the demo from appearing in search engines -->
      <meta name="robots"
         content="noindex">
      <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7COswald:300,400,500,700%7CRoboto:400,500%7CExo+2:600&display=swap"
         rel="stylesheet">
      <!-- Perfect Scrollbar -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/vendor/perfect-scrollbar.css"
         rel="stylesheet">
      <!-- Material Design Icons -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/css/material-icons.css"
         rel="stylesheet">
      <!-- Font Awesome Icons -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/css/fontawesome.css"
         rel="stylesheet">
      <!-- Preloader -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/vendor/spinkit.css"
         rel="stylesheet">
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/css/preloader.css"
         rel="stylesheet">
      <!-- App CSS -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/css/app.css"
         rel="stylesheet">
      <!-- Dark Mode CSS (optional) -->
      <link type="text/css"
         href="<?php echo ADMIN ?>assets/css/dark-mode.css"
         rel="stylesheet">
   </head>
   <body class="layout-app layout-sticky-subnav ">
      <div class="preloader">
         <div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
         </div>
      </div>
      <div class="mdk-drawer-layout js-mdk-drawer-layout"
         data-push
         data-responsive-width="992px">
         <div class="mdk-drawer-layout__content page-content">
            <!-- Header -->
            <div class="navbar navbar-expand navbar-shadow px-0  pl-lg-16pt navbar-light bg-body"
               id="default-navbar"
               data-primary>
               <!-- Navbar toggler -->
               <button class="navbar-toggler d-block d-lg-none rounded-0"
                  type="button"
                  data-toggle="sidebar">
               <span class="material-icons">menu</span>
               </button>
               <!-- Navbar Brand -->
               <a href="index.html"
                  class="navbar-brand mr-16pt d-lg-none">
               <img class="navbar-brand-icon mr-0 mr-lg-8pt"
                  src="<?php echo ADMIN ?>assets/images/logo/accent-teal-100@2x.png"
                  width="32"
                  alt="<?php echo get_settings('app_name') ?>">
               <span class="d-none d-lg-block"><?php echo get_settings('app_name') ?></span>
               </a>
               <!-- <button class="btn navbar-btn mr-16pt" data-toggle="modal" data-target="#apps">Apps <i class="material-icons">arrow_drop_down</i></button> -->
               <form class="search-form navbar-search d-none d-md-flex mr-16pt"
                  action="index.html">
                  <button class="btn"
                     type="submit"><i class="material-icons">search</i></button>
                  <input type="text"
                     class="form-control"
                     placeholder="Search ...">
               </form>
               <div class="flex"></div>
               <div class="nav navbar-nav flex-nowrap d-none d-lg-flex mr-16pt"
                  style="white-space: nowrap;">
                  <div class="nav-item dropdown d-none d-sm-flex">
                     <a href="#"
                        class="nav-link dropdown-toggle"
                        data-toggle="dropdown">EN</a>
                     <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header"><strong>Select language</strong></div>
                        
                     </div>
                  </div>
               </div>
               <div class="nav navbar-nav flex-nowrap d-flex ml-0 mr-16pt">
                  <div class="nav-item dropdown d-none d-sm-flex">
                     <a href="#"
                        class="nav-link d-flex align-items-center dropdown-toggle"
                        data-toggle="dropdown">
                     <img width="32"
                        height="32"
                        class="rounded-circle mr-8pt"
                        src="<?php echo ADMIN ?>assets/images/people/50/guy-3.jpg"
                        alt="account" />
                     <span class="flex d-flex flex-column mr-8pt">
                     <span class="navbar-text-100">Login</span>
                     <small class="navbar-text-50">Administrator</small>
                     </span>
                     </a>
                     
                  </div>
                  <!-- Notifications dropdown -->
                  <div class="nav-item ml-16pt dropdown dropdown-notifications">
                     <button class="nav-link btn-flush dropdown-toggle"
                        type="button"
                        data-toggle="dropdown"
                        data-dropdown-disable-document-scroll
                        data-caret="false">
                     <i class="material-icons">notifications</i>
                     <span class="badge badge-notifications badge-accent">2</span>
                     </button>
                     <div class="dropdown-menu dropdown-menu-right">
                        <div data-perfect-scrollbar
                           class="position-relative">
                           <div class="dropdown-header"><strong>System notifications</strong></div>
                        </div>
                     </div>
                  </div>
                  <!-- // END Notifications dropdown -->
                  <!-- Notifications dropdown -->
                  <div class="nav-item ml-16pt dropdown dropdown-notifications">
                     <button class="nav-link btn-flush dropdown-toggle"
                        type="button"
                        data-toggle="dropdown"
                        data-dropdown-disable-document-scroll
                        data-caret="false">
                     <i class="material-icons icon-24pt">mail_outline</i>
                     </button>
                     <div class="dropdown-menu dropdown-menu-right">
                        <div data-perfect-scrollbar
                           class="position-relative">
                           <div class="dropdown-header"><strong>Messages</strong></div>
                           
                        </div>
                     </div>
                  </div>
                  <!-- // END Notifications dropdown -->
               </div>
               <div class="dropdown border-left-2 navbar-border">
                  <button class="navbar-toggler navbar-toggler-custom d-block"
                     type="button"
                     data-toggle="dropdown"
                     data-caret="false">
                  <span class="material-icons">business_center</span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                     <div class="dropdown-header"><strong>Select Website</strong></div>
                     <a href="<?php echo base_url(); ?>"
                        class="dropdown-item d-flex align-items-center">
                        <div class="avatar avatar-sm mr-8pt">
                           <span class="avatar-title rounded bg-accent">BB</span>
                        </div>
                        <small class="ml-4pt flex">
                        <span class="d-flex flex-column">
                        <strong class="text-black-100">View Website</strong>
                        <span class="text-black-50"><?php echo get_settings('app_name') ?></span>
                        </span>
                        </small>
                     </a>
                  </div>
               </div>
            </div>

            <?php
                     if($this->session->flashdata('error')){
                        ?>
                           <div class="alert bg-danger text-white border-0" role="alert">
                              <div class="d-flex flex-wrap align-items-start">
                                    <div class="mr-8pt">
                                       <i class="material-icons">access_time</i>
                                    </div>
                                    <div class="flex" style="min-width: 180px">
                                       <small>
                                          <strong>Oops!</strong> <?php echo $this->session->flashdata('error') ?>
                                       </small>
                                    </div>
                              </div>
                           </div>
                        <?php
                     }
                     if($this->session->flashdata('success')){
                        ?>
                           <div class="alert bg-success text-white border-0" role="alert">
                              <div class="d-flex flex-wrap align-items-start">
                                    <div class="mr-8pt">
                                       <i class="material-icons">access_time</i>
                                    </div>
                                    <div class="flex" style="min-width: 180px">
                                       <small>
                                          <strong>Well done!</strong> <?php echo $this->session->flashdata('success') ?>
                                       </small>
                                    </div>
                              </div>
                           </div>
                        <?php
                     }
                  ?>


            <!-- // END Header -->
            <div class=" pt-32pt pt-sm-64pt pb-32pt">
               <div class="container-fluid page__container">
                  <form action="<?php echo base_url('login');  ?>"
                     class="col-md-5 p-0 mx-auto" method="post">
                     <div class="form-group">
                        <label class="form-label"
                           for="email">Email:</label>
                        <input 
                           id="email"
                           name="user_name"
                           type="text"
                           class="form-control"
                           placeholder="Your email address ...">
                     </div>
                     <div class="form-group">
                        <label class="form-label"
                           for="password">Password:</label>
                        <input 
                           id="password"
                           name="password"
                           type="password"
                           class="form-control"
                           placeholder="Your first and last name ...">
                        <p class="text-right"><a href="javascript:void(0)"
                           class="small"></a></p>
                     </div>
                     <div class="text-center">
                        <button class="btn btn-accent" type="submit">Login</button>
                     </div>
                  </form>
               </div>
            </div>
            <div class="page-separator justify-content-center m-0">
               <div class="page-separator__text"></div>
               <div class="page-separator__bg-top "></div>
            </div>
            <div class="bg-body pt-32pt pb-32pt pb-md-64pt text-center">
               <div class="container-fluid page__container">
               </div>
            </div>
            <div class="js-fix-footer footer border-top-2">
               <div class="container-fluid page__container page-section d-flex flex-column">
                  <p class="text-70 brand mb-24pt">
                     <img class="brand-icon"
                        src="<?php echo ADMIN ?>assets/images/logo/black-70@2x.png"
                        width="30"
                        alt="<?php echo get_settings('app_name') ?>"> <?php echo get_settings('app_name') ?>
                  </p>
                  <p class="measure-lead-max text-muted mb-0 small"><?php echo get_settings('about_content') ?></p>
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
                                 <a href="javascript:void(0)"
                                    class="text-white-70 text-underline mr-16pt">Terms</a>
                                 <a href="javascript:void(0)"
                                    class="text-white-70 text-underline">Privacy policy</a>
                              </p>
                              <p class="text-white-50 small mb-0">Copyright 2021 &copy; All rights reserved.</p>
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
                  <a href="<?php echo base_url('secure/admin/login') ?>"
                     class="sidebar-brand ">
                  <img class="sidebar-brand-icon"
                     src="<?php echo ADMIN ?>assets/images/logo/accent-teal-100@2x.png"
                     alt="<?php echo get_settings('app_name') ?>">
                  <span><?php echo get_settings('app_name') ?></span>
                  </a>
                  <div class="sidebar-account mx-16pt mb-16pt dropdown">
                     <a href="#"
                        class="nav-link d-flex align-items-center dropdown-toggle"
                        data-toggle="dropdown"
                        data-caret="false">
                     <img width="32"
                        height="32"
                        class="rounded-circle mr-8pt"
                        src="<?php echo ADMIN ?>assets/images/people/50/guy-3.jpg"
                        alt="account" />
                     <span class="flex d-flex flex-column mr-8pt">
                     <span class="text-black-100">Login</span>
                     <small class="text-black-50">Administrator</small>
                     </span>
                     <i class="material-icons text-black-20 icon-16pt">keyboard_arrow_down</i>
                     </a>
                     
                  </div>
                  <form action="index.html"
                     class="search-form flex-shrink-0 search-form--black sidebar-m-b sidebar-p-l mx-16pt pr-0">
                     <input type="text"
                        class="form-control pl-0"
                        placeholder="Search">
                     <button class="btn"
                        type="submit"><i class="material-icons">search</i></button>
                  </form>
                  <div class="sidebar-heading">Overview</div>
                  <ul class="sidebar-menu">
                     <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                           href="javascript:void(0)">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">insert_chart_outlined</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                        </a>
                     </li>
                  </ul>

                  
               </div>
            </div>
         </div>
         <!-- // END drawer -->
      </div>
      

      <!-- jQuery -->
      <script src="<?php echo ADMIN ?>assets/vendor/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="<?php echo ADMIN ?>assets/vendor/popper.min.js"></script>
      <script src="<?php echo ADMIN ?>assets/vendor/bootstrap.min.js"></script>
      <!-- Perfect Scrollbar -->
      <script src="<?php echo ADMIN ?>assets/vendor/perfect-scrollbar.min.js"></script>
      <!-- DOM Factory -->
      <script src="<?php echo ADMIN ?>assets/vendor/dom-factory.js"></script>
      <!-- MDK -->
      <script src="<?php echo ADMIN ?>assets/vendor/material-design-kit.js"></script>
      <!-- App JS -->
      <script src="<?php echo ADMIN ?>assets/js/app.js"></script>
      <!-- Highlight.js -->
      <script src="<?php echo ADMIN ?>assets/js/hljs.js"></script>
      <!-- App Settings (safe to remove) -->
      <!-- <script src="<?php echo ADMIN ?>assets/js/app-settings.js"></script> -->
   </body>
</html>