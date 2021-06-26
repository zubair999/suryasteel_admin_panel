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
    <?php echo form_open('edit-subcategory-'.$sub_category_id, ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-8 d-flex align-items-center">
        
        
        <div class="flex" style="max-width: 100%">
            <div class="was-validated">


               <div class="form-row">
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
                     <label class="form-label" for="validationSample01">Sub Category Title</label>
                     <input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $category->sub_category_name) ?>" class="form-control" id="validationSample01" placeholder="Enter category title" required>
                     <div class="invalid-feedback">Please provide a category name.</div>
                     <div class="valid-feedback">Looks good!</div>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Select Category</label>
                   
                     <select name="parent" class="form-control">
                        <option value="">Select Category</option>
                        <?php
                        foreach ($main_categories as $each) { ?>
                      
                        <option value="<?php echo $each['sub_category_id']; ?>"<?= $category->sub_category_id == $each['sub_category_id'] ? ' selected="selected"' : '';?>><?php echo $each['category_name']; ?></option>

                        <?php }
                        ?>
                     </select>
                     <div class="invalid-feedback">Please provide a category name.</div>
                     <div class="valid-feedback">Looks good!</div>
                  </div>
               </div>



            
               
            </div>

            
            
            
            <button class="btn btn-primary-dodger-blue" type="submit">Submit</button>


            
         </div>
      </div>
      <?php echo form_open() ?>
   </div>


   



</div>










<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Choose Images</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid page__container page-section">
      
	   	
      <div class='content' id="imgContent" style="max-height: 400px;
    overflow: hidden scroll;">
         

         

      </div> 

</div>
      </div>
      <div class="modal-footer">
         <a href="<?php echo base_url('media') ?>" class="btn btn-info">Add More Images</a>
         <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="removeSelectedImg">Close</button>
      </div>
    </div>

  </div>
</div>