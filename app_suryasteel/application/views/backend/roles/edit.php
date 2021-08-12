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
   <?php echo form_open('edit-role-'.$this->data['role']->role_id, ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-12 d-flex align-items-center">
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
                  <label class="form-label" for="validationSample01">Role</label>
                  <input type="text" name="role_name" value="<?php echo ($this->input->post('role_name') ? $this->input->post('role_name') : $role->roles_name)?>" class="form-control" id="validationSample01" placeholder="Enter role name" >
                  <div class="invalid-feedback">Please provide Roles Name.</div>
                  <div class="valid-feedback">Looks good!</div>
                  <?php echo form_error('role_name');?>
               </div>
            </div>
            <div class="form-row">
               <div class="col-12 col-md-12 mb-3">
                  <label class="form-label" for="validationSample01">Status</label>
                  <div class="card mb-lg-32pt">
                     <div class="table-responsive">
                        <div id="stafflist_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;">
                                       
                                    
                                       <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 874px; padding-right: 0px;">
                                          
                                       
                                          <table class="table mb-0 thead-border-top-0 table-nowrap dataTable no-footer" style="margin-left: 0px; width: 874px;">
                                             <thead>
                                                <tr role="row">
                                                   <th class="sorting" style="width: 135.266px;">Permission</th>
                                                   <th class="sorting" style="width: 180.422px;">Create</th>
                                                   <th class="sorting" style="width: 180.312px;">Update</th>
                                                   <th class="sorting" style="width: 180.422px;">View</th>
                                                   <th class="sorting" style="width: 180.656px;">Delete</th>
                                                   <th class="sorting" style="width: 180.422px;">Status</th>
                                                   <th class="sorting" style="width: 180.656px;">Change Password</th>
                                                </tr>
                                             </thead>
                                          </table>
                                       
                                       
                                       </div>
                                    
                                    
                                    </div>


                                    <div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 800px; width: 100%;">
                                       
                                    
                                       <table class="table mb-0 thead-border-top-0 table-nowrap dataTable no-footer" id="stafflist" style="width: 100%;">
                                          <tbody class="list">
                                             
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Roles</td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="addRoles" <?php if($user_permission) {
                                                      if(in_array('addRoles', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="editRoles" <?php if($user_permission) {
                                                      if(in_array('editRoles', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="viewRoles" <?php if($user_permission) {
                                                      if(in_array('viewRoles', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="deleteRoles" <?php if($user_permission) {
                                                      if(in_array('deleteRoles', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>

                                             
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Staff</td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="addStaff" <?php if($user_permission) {
                                                      if(in_array('addStaff', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="editStaff" <?php if($user_permission) {
                                                      if(in_array('editStaff', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="viewStaff" <?php if($user_permission) {
                                                      if(in_array('viewStaff', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="deleteStaff" <?php if($user_permission) {
                                                      if(in_array('deleteStaff', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="canChangeStaffStatus" <?php if($user_permission) {
                                                      if(in_array('canChangeStaffStatus', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="changePasswordStaff" <?php if($user_permission) {
                                                      if(in_array('changePasswordStaff', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                             </tr>

                                             <tr class="odd">
                                                <td style="width: 135.266px;">Customer</td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="addCustomer" <?php if($user_permission) {
                                                      if(in_array('addCustomer', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="editCustomer" <?php if($user_permission) {
                                                      if(in_array('editCustomer', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="viewCustomer" <?php if($user_permission) {
                                                      if(in_array('viewCustomer', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="deleteCustomer" <?php if($user_permission) {
                                                      if(in_array('deleteCustomer', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="canChangeCustomerStatus" <?php if($user_permission) {
                                                      if(in_array('canChangeCustomerStatus', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="changePasswordCustomer" <?php if($user_permission) {
                                                      if(in_array('changePasswordCustomer', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                             </tr>

                                             <tr class="odd">
                                                <td style="width: 135.266px;">Category</td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="addCategory" <?php if($user_permission) {
                                                      if(in_array('addCategory', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="editCategory" <?php if($user_permission) {
                                                      if(in_array('editCategory', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="category" <?php if($user_permission) {
                                                      if(in_array('category', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="deleteCategory" <?php if($user_permission) {
                                                      if(in_array('deleteCategory', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>

                                             <tr class="odd">
                                                <td style="width: 135.266px;">Purchase</td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="addPurchase" <?php if($user_permission) {
                                                      if(in_array('addPurchase', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="editPurchase" <?php if($user_permission) {
                                                      if(in_array('editPurchase', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="viewPurchase" <?php if($user_permission) {
                                                      if(in_array('viewPurchase', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="deletePurchase" <?php if($user_permission) {
                                                      if(in_array('deletePurchase', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>

                                             <tr class="odd">
                                                <td style="width: 135.266px;">Media</td>
                                                <td>
                                                   <input type="checkbox" id="permission"  disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="media" <?php if($user_permission) {
                                                      if(in_array('media', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>


                                             <tr class="odd">
                                                <td style="width: 135.266px;">Logs</td>
                                                <td>
                                                   <input type="checkbox" id="permission"  disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="viewLogs" <?php if($user_permission) {
                                                      if(in_array('viewLogs', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>


                                             <tr class="odd">
                                                <td style="width: 135.266px;">System Settings</td>
                                                <td>
                                                   <input type="checkbox" id="permission"  disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="systemSetting" <?php if($user_permission) {
                                                      if(in_array('systemSetting', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>

                                             <tr class="odd">
                                                <td style="width: 135.266px;">Manage Profile</td>
                                                <td>
                                                   <input type="checkbox" id="permission"  disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" name="permission[]" id="permission" value="manageProfile" <?php if($user_permission) {
                                                      if(in_array('manageProfile', $user_permission)) { echo "checked"; } } ?> >
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                                <td>
                                                   <input type="checkbox" id="permission"   disabled>
                                                </td>
                                             </tr>
                                             
                                          </tbody>
                                       </table>
                                    
                                    
                                    </div>


                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <a class="btn btn-primary-dodger-blue" href="<?php echo base_url('view-roles'); ?>">Back</a>
            <button class="btn btn-primary-dodger-blue" type="submit">Submit</button>
         </div>
      </div>
      <?php echo form_open() ?>
   </div>
</div>