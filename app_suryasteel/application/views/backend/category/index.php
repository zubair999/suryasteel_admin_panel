<div class="container-fluid page__container page-section pb-0">
   <h1 class="h2 mb-0"><?= ucwords($page_title) ?></h1>
   <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">
         <a href="">Components</a>
      </li>
      <li class="breadcrumb-item active">
         <?= ucwords($page_title) ?>
      </li>
   </ol>
</div>
<div class="container-fluid page__container page-section">
   <div class="page-separator">
      <div class="page-separator__text"><a class="btn btn-primary" href="<?php echo base_url('add-category'); ?>">Add</a></div>
      <!-- <div class="page-separator__text"><a class="btn btn-primary" href="<?php echo base_url('add/category'); ?>">Sub Categories</a></div> -->
   </div>
   <?php if($this->session->flashdata('success')){
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

   <?php if($this->session->flashdata('error')){
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
   ?>


   <div class="row card-group-row" data-toggle="dragula">
      
      <?php
         foreach($category as $c){
            ?>
               <div class="col-sm-6 col-md-4 card-group-row__col">
               <div class="card card-sm card-group-row__card">
                  <div class="position-relative">
                     <div class="card-img-top">
                        <img src="<?php echo $c['actual'] ?>" class="card-img-top card-img-cover" alt="">
                     </div>
                  </div>
                  <div class="card-body d-flex">
                     <a href="" class="text-muted"><i class="material-icons">more_vert</i></a>   
                     <div class="flex">
                        <h5 class="card-title m-0"><a href=""><?php echo $c['category_name'] ?></a></h5>
                     </div>
                  </div>
                  
                  <div class="d-flex m-2">
                     <div class="flex">
                        <a href="<?php echo base_url('edit-category-'.$c['category_id']); ?>">
                           <button type="button" class="btn btn-outline-primary">
                              <i class="material-icons icon--left">build</i> Edit
                           </button>
                        <a href="<?php echo base_url('delete-category-'.$c['category_id']); ?>">
                     </div>
                     <div>
                        <button type="button" class="btn btn-outline-danger">
                        <i class="material-icons icon--left">delete</i> Delete
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <?php
         }
      ?>



   </div>
</div>