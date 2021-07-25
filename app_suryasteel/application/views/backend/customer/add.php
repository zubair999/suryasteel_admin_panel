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
         <a href="">Customer</a>
      </li>
      <li class="breadcrumb-item active">
         <?= ucwords($page_title) ?>
      </li>
   </ol>
</div>


<div class="container-fluid page__container page-section">
    <?php echo form_open('add-customer', ['method' => 'post']) ?>
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
                        <label class="form-label" for="validationSample01">Username(Email)</label>
                        <input type="text" name="username" value="<?php echo ( $this->input->post('username') ? $this->input->post('username') : null) ?>" class="form-control" id="validationSample01" placeholder="Enter username">
                        <div class="invalid-feedback">Please provide username.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('username');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample02">Password</label>
                        <input type="password" name="password" value="<?php echo ( $this->input->post('password') ? $this->input->post('password') : null )?>" class="form-control" id="validationSample02" placeholder="Enter password" >
                        <div class="invalid-feedback">Please provide Total.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('password');?><span>

                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample03">First Name</label>
                        <input type="text" name="firstname" value="<?php echo ($this->input->post('firstname') ? $this->input->post('firstname') : null ) ?>" class="form-control" id="validationSample03" placeholder="Enter first name" >
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('firstname');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample04">Last Name</label>
                        <input type="text" name="lastname" value="<?php echo ($this->input->post('lastname') ? $this->input->post('lastname') : null )?>" class="form-control" id="validationSample04" placeholder="Enter last name" >
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('lastname');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample05">Mobile No.</label>
                        <input type="text" name="mobileno" value="<?php echo ($this->input->post('mobileno') ? $this->input->post('mobileno') : null) ?>" class="form-control" id="validationSample05" placeholder="Enter mobile no" >
                        <div class="invalid-feedback">Please provide Total.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('mobileno');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample06">Company Name</label>
                        <input type="text" name="companyName" value="<?php echo ($this->input->post('companyName') ? $this->input->post('companyName') : null )?>" class="form-control" id="validationSample06" placeholder="Enter a company name" >
                        <div class="invalid-feedback">Please provide a company name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('companyName');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample07">Gst Reg. Type</label>
                     <select id="select01"
                        name="gstRegType"
                        data-toggle="select"
                        class="form-control">
                        <option>Select gst reg type</option>
                        <?php
                           foreach ($gst_reg_type as $grt) { 
                              $selected = ($grt['gst_reg_type_id'] == $this->input->post('gstRegType')) ? ' selected="selected"' : "";
                              echo '<option value="'.$grt['gst_reg_type_id'].'" '.$selected.'>'.ucwords($grt['gst_reg_type_value']).'</option>';
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select gst reg type.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <span class="text-danger"><?php echo form_error('gstRegType');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample08">GST No.</label>
                        <input type="text" name="gst_no" value="<?php echo ($this->input->post('gst_no') ? $this->input->post('gst_no') : null )?>" class="form-control" id="validationSample08" placeholder="Enter gst no." >
                        <div class="invalid-feedback">Please provide a company name.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('gst_no');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample09">Plot/Factory No.</label>
                        <input type="text" name="plotFactoryNo" value="<?php echo ($this->input->post('plotFactoryNo') ? $this->input->post('plotFactoryNo') : null )?>" class="form-control" id="validationSample09" placeholder="Enter plot/factory no." >
                        <div class="invalid-feedback">Please provide a plot/factory no.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('plotFactoryNo');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample10">Complete Address.</label>
                        <input type="text" name="fullAddress" value="<?php echo ($this->input->post('fullAddress') ? $this->input->post('fullAddress') : null )?>" class="form-control" id="validationSample10" placeholder="Enter complete address." >
                        <div class="invalid-feedback">Please provide a full address.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('fullAddress');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample11">Landmark</label>
                        <input type="text" name="landmark" value="<?php echo ($this->input->post('landmark') ? $this->input->post('landmark') : null )?>" class="form-control" id="validationSample11" placeholder="Enter a landmark." >
                        <div class="invalid-feedback">Please provide a landmark.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('landmark');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample12">State</label>
                     <select id="select01"
                        name="state"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select state</option>
                        <?php
                           foreach ($state as $s) {
                              $selected = ($s['state_id'] == $this->input->post('state')) ? ' selected="selected"' : "";
                              echo '<option value="'.$s['state_id'].'" '.$selected.'>'.ucwords($s['state_name']).'</option>';
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select state.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <span class="text-danger"><?php echo form_error('state');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample12">Is Allowed To View Product</label>
                     <select id="select01"
                        name="yesno"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select Yes or No</option>
                        <?php
                           foreach ($yesOrNo as $yn) {
                              $selected = ($yn['c_id'] == $this->input->post('yesno')) ? ' selected="selected"' : "";
                              echo '<option value="'.$yn['c_id'].'" '.$selected.'>'.ucwords($yn['c_value']).'</option>';
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select yes or no.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <span class="text-danger"><?php echo form_error('yesno');?><span>
                  </div>
               </div>


               

               

               

            
            <a class="btn btn-primary-dodger-blue" href="<?php echo base_url('view-customer'); ?>">Back</a>
            <button class="btn btn-primary-dodger-blue" type="submit">Submit</button>
            
            
         </div>
      </div>
      <?php echo form_open() ?>
   </div>


   



</div>


