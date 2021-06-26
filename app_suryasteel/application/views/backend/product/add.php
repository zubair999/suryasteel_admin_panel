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
         <a href="">Components</a>
      </li>
      <li class="breadcrumb-item active">
         <?= ucwords($page_title) ?>
      </li>
   </ol>
</div>


<div class="container-fluid page__container page-section">
    <?php echo form_open('add-product', ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-8 d-flex align-items-center">
        
      
        
        <div class="flex" style="max-width: 100%">
            <div class="was-validated">

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


               <div class="form-row">
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label" for="validationSample02">Thumbnail</label>
                        <div class="custom-file">
                            <Button type="button" id="selectImg" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Choose File</Button>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3" >
                        <image
                           id="showImg"
                           style="width: 75px;height: 75px;border: 1px solid #ddd;padding: 5px;display:flex"
                           src="<?php echo base_url('upload/'.json_decode(get_settings('default_image'))[0]->image) ?>"
                        />
                        <div class="imgDeleteBtn" id="imgDeleteBtn" style="display:flex">
                           <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span>
                        </div>
                    </div>
                  </div>
                  <input hidden type="text"  id="thumbId" name="thumbnail_id" class="form-control" value="<?php echo json_decode(get_settings('default_image'))[0]->image_id ?>">
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Product Name</label>
                        <input type="text" name="product_name" value="<?php $this->input->post('product_name') ?>" class="form-control" id="validationSample01" placeholder="Enter product name" required>
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('product_name');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">MRP</label>
                        <input type="text" name="mrp" value="<?php $this->input->post('mrp')?>" class="form-control" id="validationSample01" placeholder="Enter mrp" required>
                        <div class="invalid-feedback">Please provide MRP.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('mrp');?>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Sell Price</label>
                        <input type="text" name="sell_price" value="<?php $this->input->post('sell_price') ?>" class="form-control" id="validationSample01" placeholder="Enter Sell Price" required>
                        <div class="invalid-feedback">Please provide Total.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('sell_price');?>
                  </div>
               </div>

               

               

               

               

               

               


               

               

               


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Status</label>
                     <select id="select01"
                        name="status"
                        data-toggle="select"
                        class="form-control">
                        <?php
                           foreach ($status as $s) { 
                              ?>
                                 <option value="<?php echo $s['status_value']; ?>"><?php echo ucwords($s['status_value']) ?></option>';
                              <?php 
                           }

                        ?>



                        


                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample02">Brand</label>
                     <select id="select02"
                        name="brand"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select Brand</option>
                           <?php
                              foreach($brand as $b){
                                 ?>
                                    <option value="<?php echo $b['brand_id']; ?>"><?php echo ucwords($b['brand_name']) ?></option>';
                                 <?php


                              }
                           ?>
                        

                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample03">Category</label>
                     <select id="select03"
                        name="category"
                        data-toggle="select"
                        class="form-control"
                     >
                     <option value="">Select Category</option>
                        <?php
                           foreach($category as $c){
                              

                              ?>
                                 <option value="<?php echo $c['category_id']; ?>"><?php echo ucwords($c['category_name']) ?></option>';
                              <?php

                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample04">Sub Category</label>
                     <select id="select04"
                        name="subcategory"
                        data-toggle="select"
                        class="form-control">
                           <option value="">Select Category First</option>
                        <?php
                           foreach($subcategory as $sc){
                              ?>
                                 <option value="<?php echo $sc['sub_category_id']; ?>"><?php echo ucwords($sc['sub_category_name']) ?></option>';
                              <?php
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample05">Sub Category Type</label>
                     <select id="select05"
                        name="subcategorytype"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select Sub Category First</option>
                        <?php
                           foreach($subcategorytype as $sct){
                              ?>
                                 <option value="<?php echo $sct['sub_category_type_id']; ?>"><?php echo ucwords($sct['sub_category_type_name']) ?></option>';
                              <?php
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               

               

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample07">GST</label>
                     <select id="select07"
                        name="gst"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select GST</option>
                        <?php
                           foreach($gst as $g){
                             



                              ?>
                                 <option value="<?php echo $g['gst_id']; ?>"><?php echo ucwords($g['gst_value']) ?></option>';
                              <?php
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select GST.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample08">Deals Type</label>
                     <select id="select08"
                        name="type"
                        data-toggle="select"
                        class="form-control">
                        <option value="">Select Deal Type</option>
                        <?php
                           foreach($type as $t){
                              



                              ?>
                                 <option value="<?php echo $t['type_id']; ?>"><?php echo ucwords($t['type_value']) ?></option>';
                              <?php
                           }
                        ?>
                    </select>
                     <div class="invalid-feedback">Please select status.</div>
                     <div class="valid-feedback">Looks good!</div>
                     <?php echo form_error('status');?>
                  </div>
               </div>

               



                     
               

               

            

            
            
            <a class="btn btn-primary-dodger-blue" href="<?php echo base_url('products'); ?>">Back</a>
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


<script>
   
</script>


