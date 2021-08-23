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
   <?php echo form_open('add-roles', ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-12 d-flex align-items-center">
         <div class="flex" style="max-width: 100%">
            <div>
               <?php
                  if($this->session->flashdata('notification')){
                     ?>
               <div class="alert bg-success text-white border-0" role="alert">
                  <div class="d-flex flex-wrap align-items-start">
                     <div class="mr-8pt">
                        <i class="material-icons">access_time</i>
                     </div>
                     <div class="flex" style="min-width: 180px">
                        <small>
                        <strong>Well done!</strong> <?php echo $this->session->flashdata('notification') ?>
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
                  <input type="text" name="role_name" value="<?php $this->input->post('role_name') ?>" class="form-control" id="validationSample01" placeholder="Enter role name" >
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
                                                </tr>
                                             </thead>
                                          </table>
                                       
                                       
                                       </div>
                                    
                                    
                                    </div>


                                    <div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 800px; width: 100%;">
                                       
                                    
                                       <table class="table mb-0 thead-border-top-0 table-nowrap dataTable no-footer" id="stafflist" style="width: 100%;">
                                          <tbody class="list">
                                             
                                             <tr class="odd" style="background:pink" title="Don't check role permission in any role other than Administrator, otherwise that role would be able to access some Administrator privileages">
                                                <td style="width: 135.266px; color:red; text-decoration:underline">Roles</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addRoles" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="editRoles" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewRoles" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteRoles" ></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Staff</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addStaff" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="updateStaff" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewStaff" ></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteStaff" ></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Customer</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addCustomer"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="edit-custimer"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewCustomer"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteCustomer"></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Category</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addCategory"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="editCategory"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewCategory"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteCategory"></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Product</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addProduct"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="editProduct"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewProduct"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteProduct"></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Purchase</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addPurchase"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="editPurchase"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewPurchase"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deletePurchase"></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Order</td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="addOrder"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="editOrder"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewOrder"></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrder"></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Media</td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="media"></td>
                                                <td><input type="checkbox" disabled></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Logs</td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="viewLogs"></td>
                                                <td><input type="checkbox" disabled></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Systems Settings</td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="systemSetting"></td>
                                                <td><input type="checkbox" disabled></td>
                                             </tr>
                                             <tr class="odd">
                                                <td style="width: 135.266px;">Manage Profile</td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" disabled></td>
                                                <td><input type="checkbox" name="permission[]" id="permission" value="manageProfile"></td>
                                                <td><input type="checkbox" disabled></td>
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