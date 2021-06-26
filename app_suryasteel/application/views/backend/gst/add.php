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
    <?php echo form_open('add/category', ['method' => 'post']) ?>
   <div class="row mb-32pt">
      <div class="col-lg-8 d-flex align-items-center">
        
        
        <div class="flex" style="max-width: 100%">
            <div class="was-validated">
               <div class="form-row">
                  <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample01">Category Title</label>
                     <input type="text" name="name" class="form-control" id="validationSample01" placeholder="Enter category title" required>
                     <div class="invalid-feedback">Please provide a category name.</div>
                     <div class="valid-feedback">Looks good!</div>
                  </div>
               </div>
               <div class="form-row">
                <div class="col-12 col-md-6 mb-3">
                     <label class="form-label" for="validationSample02">Parent</label>
                     <select id="select01"
                        data-toggle="select"
                        class="form-control" name="parent">
                        <option selected="" value="">None</option>
                        <option value="1">Another option</option>
                        <option value="2">Third option is here</option>
                    </select>
                     <div class="invalid-feedback">Please select parent.</div>
                     <div class="valid-feedback">Looks good!</div>
                  </div>
               </div>

               <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="validationSample02">Parent</label>
                        <div class="custom-file">
                            <input type="file" id="file" name="image" class="custom-file-input">
                            <label for="file" class="custom-file-label">Choose file</label>
                        </div>
                        <div class="invalid-feedback">Please provide a last name.</div>
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