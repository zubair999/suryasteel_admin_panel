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
      <div class="page-separator__text"><a class="btn btn-primary" href="<?php echo base_url('add-brand'); ?>">Add</a></div>
   </div>




   <div class="row card-group-row" data-toggle="dragula">
      <?php
         foreach($brand as $b){
            ?>
               <div class="col-sm-6 col-md-4 card-group-row__col">
                  <div class="card card-sm card-group-row__card">
                     <div class="position-relative">
                        <div class="card-img-top">
                           <img src="<?php echo $b['actual'] ?>" class="card-img-top card-img-cover" alt="">
                        </div>
                     </div>
                     <div class="card-body d-flex">
                        <a href="" class="text-muted"><i class="material-icons">more_vert</i></a>   
                        <div class="flex">
                           <h5 class="card-title m-0"><a href=""><?php echo $b['brand_name'] ?></a></h5>
                           <small class="text-muted"><?php echo $b['product_count'] ?> Products</small>
                        </div>
                     </div>
                     
                     <div class="d-flex m-2">
                        <div class="flex">
                           <a href="<?php echo base_url('edit-brand-'.$b['brand_id']); ?>">
                              <button type="button" class="btn btn-outline-primary">
                                 <i class="material-icons icon--left">build</i> Edit
                              </button>
                           </a>
                        </div>
                        <div>
                           <a href="<?php echo base_url('delete-brand-'.$b['brand_id']); ?>">
                              <button type="button" class="btn btn-outline-danger">
                                 <i class="material-icons icon--left">build</i> Delete
                              </button>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            <?php
         }
      ?>




   </div>
</div>