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
         <a href="">Staff</a>
      </li>
      <li class="breadcrumb-item active">
         <?= ucwords($page_title) ?>
      </li>
   </ol>
</div>


<div class="container-fluid page__container page-section">
    <?php echo form_open('edit-staff-'.$user->user_id, ['method' => 'post']) ?>
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
                     <label class="form-label" for="validationSample03">Role</label>
                     <select
                        name="role"
                        data-toggle="select"
                        class="form-control"
                     >
                     <option value="">Select Role</option>
                        <?php
                           foreach($role as $value => $r){
                              $selected = ($r['role_id'] == $user->role_id) ? ' selected="selected"' : "";
                              echo '<option value="'.$r['role_id'].'" '.$selected.'>'.ucwords($r['roles_name']).'</option>';

                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <span class="text-danger"><?php echo form_error('role');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">First Name</label>
                        <input type="text" name="firstname" value="<?php echo ($this->input->post('firstname') ? $this->input->post('firstname') : $user->firstname ) ?>" class="form-control" id="validationSample01" placeholder="Enter first name" >
                        <div class="invalid-feedback">Please provide a firstname.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('firstname');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Last Name</label>
                        <input type="text" name="lastname" value="<?php echo ($this->input->post('lastname') ? $this->input->post('lastname') : $user->lastname )?>" class="form-control" id="validationSample01" placeholder="Enter last name" >
                        <div class="invalid-feedback">Please provide a lastname.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('lastname');?><span>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Mobile No.</label>
                        <input type="text" name="mobileno" value="<?php echo ($this->input->post('mobileno') ? $this->input->post('mobileno') : $user->mobile_no) ?>" class="form-control" id="validationSample01" placeholder="Enter mobile no" >
                        <div class="invalid-feedback">Please provide mobile no.</div>
                        <div class="valid-feedback">Looks good!</div>
                        <span class="text-danger"><?php echo form_error('mobileno');?><span>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Status</label>
                     <select id="select01"
                        name="status"
                        data-toggle="select"
                        class="form-control">
                        <option>Select Status</option>
                        <?php
                           foreach ($status as $s) { 

                              
                              $selected = ($s['status_value'] == $user->is_active) ? ' selected="selected"' : "";
                              
                              echo '<option value="'.$s['status_id'].'" '.$selected.'>'.ucwords($s['status_value']).'</option>';
                           }

                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <span class="text-danger"><?php echo form_error('status');?><span>
                  </div>
               </div>

            
            <a class="btn btn-primary-dodger-blue" href="<?php echo base_url('view-staff'); ?>">Back</a>
            <button class="btn btn-primary-dodger-blue" type="submit">Submit</button>
            
            
         </div>
      </div>
      <?php echo form_open() ?>
   </div>


   



</div>


