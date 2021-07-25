<style>
   .modal-dialog{
      max-width:1100px;
   }
</style>

<div class="container-fluid page__container page-section pb-0">
   <h1 class="h2 mb-0"><?= ucwords($page_title) ?></h1>
   <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">
         <a href=""><?= ucwords($page_title) ?></a>
      </li>
      <li class="breadcrumb-item active">
         <?= ucwords($page_title) ?>
      </li>
   </ol>
</div>


<div class="container-fluid page__container page-section">
    <?php echo form_open('system-setting', ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-8 d-flex align-items-center">
        
      
        
        <div class="flex" style="max-width: 100%">
               <div>
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
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Company Name</label>
                        <input type="text" name="app_name" value="<?php echo get_settings('app_name');  ?>" class="form-control" id="validationSample01" placeholder="Enter app name" >
                        <div class="invalid-feedback">Please provide a app name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('app_name');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Company Description</label>
                        <textarea 
                           class="form-control" 
                           style="height:200px; width:700px"
                           name="app_description"   
                        ><?php echo get_settings('app_description');  ?>
                        </textarea>
                        <div class="invalid-feedback">Please provide a lastname.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('lastname');?><span>
                  </div>
               </div>

               
               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Company Address</label>
                        <input type="text" name="address" value="<?php echo get_settings('address');  ?>" class="form-control" id="validationSample01" placeholder="Enter company address" >
                        <div class="invalid-feedback">Please provide a app name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('address');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Author Name</label>
                        <input type="text" name="author_name" value="<?php echo get_settings('author_name');  ?>" class="form-control" id="validationSample01" placeholder="Enter an author name" >
                        <div class="invalid-feedback">Please provide a app name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('author_name');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Mobile App Whatsapp No</label>
                        <input type="text" name="whatsappno" value="<?php echo get_settings('APP_WHATSAPP_NO');  ?>" class="form-control" id="validationSample01" placeholder="Enter a whatsapp no" >
                        <div class="invalid-feedback">Please provide a whatsapp no.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('whatsappno');?><span>
                  </div>
               </div>

            
            <a class="btn btn-primary-dodger-blue" href="<?php echo base_url('dashboard'); ?>">Back</a>
            <button class="btn btn-primary-dodger-blue" type="submit">Submit</button>
            
            
         </div>
      </div>
      <?php echo form_open() ?>
   </div>


   



</div>


