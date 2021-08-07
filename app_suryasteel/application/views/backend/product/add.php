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
                     <label class="form-label" for="validationSample03">Category</label>
                     <select id="select03"
                        name="category"
                        data-toggle="select"
                        class="form-control"
                     >
                     <option value="">Select Category</option>
                        <?php
                           foreach($category as $value => $c){
                              $selected = ($c['category_id'] == $this->input->post('category')) ? ' selected="selected"' : "";
                              echo '<option value="'.$c['category_id'].'" '.$selected.'>'.ucwords($c['category_name']).'</option>';
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
                        <label class="form-label" for="validationSample01">Product Name</label>
                        <input type="text" name="product_name" value="<?php $this->input->post('product_name') ?>" class="form-control" id="validationSample01" placeholder="Enter product name" required>
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('product_name');?>
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
                        <label class="form-label" for="validationSample01">Length</label>
                        <input type="text" name="product_length" value="<?php $this->input->post('product_length') ?>" class="form-control" id="validationSample01" placeholder="Enter product length" required>
                        <div class="invalid-feedback">Please provide a product length.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('product_length');?>
                  </div>
               </div>
               
               
               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample01">Weight Per Piece</label>
                        <input type="text" name="perpieceweight" value="<?php $this->input->post('perpieceweight') ?>" class="form-control" id="validationSample01" placeholder="Enter weight per piece" required>
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('perpieceweight');?>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Unit</label>
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
                        <label class="form-label" for="validationSample01">Size</label>
                        <input type="text" name="product_name" value="<?php $this->input->post('product_name') ?>" class="form-control" id="validationSample01" placeholder="Enter product name" required>
                        <div class="invalid-feedback">Please provide a product name.</div>
                        <div class="valid-feedback">Looks good!</div>
                           <?php echo form_error('product_name');?>
                  </div>
               </div>


               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Capacity</label>
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
                     <label class="form-label" for="validationSample01">Zinc or Without Zinc</label>
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
                     <label class="form-label" for="validationSample01">Kunda or Without Kunda</label>
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
                     <label class="form-label" for="validationSample01">Nut or Without Nut</label>
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


