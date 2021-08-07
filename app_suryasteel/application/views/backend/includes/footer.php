            <!-- jQuery -->
        <script src="<?php echo ADMIN ?>assets/vendor/jquery.min.js"></script>

        <!-- Bootstrap -->
        <script src="<?php echo ADMIN ?>assets/vendor/popper.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/vendor/bootstrap.min.js"></script>

        <!-- Perfect Scrollbar -->
        <script src="<?php echo ADMIN ?>assets/vendor/perfect-scrollbar.min.js"></script>

        <!-- DOM Factory -->
        <script src="<?php echo ADMIN ?>assets/vendor/dom-factory.js"></script>

        <!-- MDK -->
        <script src="<?php echo ADMIN ?>assets/vendor/material-design-kit.js"></script>

        <!-- App JS -->
        <script src="<?php echo ADMIN ?>assets/js/app.js"></script>

        <!-- Highlight.js -->
        <script src="<?php echo ADMIN ?>assets/js/hljs.js"></script>

        <!-- Global Settings -->
        <script src="<?php echo ADMIN ?>assets/js/settings.js"></script>

        <!-- Flatpickr -->
        <script src="<?php echo ADMIN ?>assets/vendor/flatpickr/flatpickr.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/flatpickr.js"></script>

        <!-- Moment.js -->
        <script src="<?php echo ADMIN ?>assets/vendor/moment.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/vendor/moment-range.js"></script>

        <!-- Chart.js -->
        <script src="<?php echo ADMIN ?>assets/vendor/Chart.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/chartjs.js"></script>

        <!-- Chart.js Samples -->
        <script src="<?php echo ADMIN ?>assets/js/page.analytics-dashboard.js"></script>

        <!-- Vector Maps -->
        <script src="<?php echo ADMIN ?>assets/vendor/jqvmap/jquery.vmap.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/vector-maps.js"></script>

        <!-- List.js -->
        <script src="<?php echo ADMIN ?>assets/vendor/list.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/list.js"></script>

        <!-- Tables -->
        <script src="<?php echo ADMIN ?>assets/js/toggle-check-all.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/check-selected-row.js"></script>

        



        <!-- Select2 -->
        <script src="<?php echo ADMIN ?>assets/vendor/select2/select2.min.js"></script>
        <script src="<?php echo ADMIN ?>assets/js/select2.js"></script>

        <!-- Select2 -->
        <!-- <script src="<?php echo ADMIN ?>assets/vendor/dropzone.min.js"></script> -->
        <!-- <script src="<?php echo ADMIN ?>assets/js/dropzone.js"></script> -->

        <!-- App Settings (safe to remove) -->
        <script src="<?php echo ADMIN ?>assets/js/app-settings.js"></script>



        <script>
      document.getElementById('selectImg').addEventListener('click', () => {
      
      
      fetch('v1/getallimages', {
         method:'GET'
      }).then(response => response.json())
         .then(json => {
            var imgArr = [];
            json.data.forEach((item) => {
               imgArr += `
                     <image 
                        data-img="${item.image_id}"
                        class="selectImgModal sdImg"
                        src="${item.thumbnail}"
                     />
               `;
            })
            document.getElementById('imgContent').insertAdjacentHTML('afterBegin', imgArr)
            let sdImg = document.querySelectorAll('.sdImg');
            for(let i = 0; i < sdImg.length; i++){
               sdImg[i].addEventListener('click', (e)=>{
                  if(e.target.tagName == 'IMG'){
                     for(let elem of sdImg) {
                        elem.style.background = "#fff";
                     }
                     e.target.style.background = "#ddd";
                  }
                  const image = e.target.getAttribute('data-img');
                  const imageUrl = e.target.getAttribute('src');
                  document.getElementById('showImg').style.display = "block";
                  document.getElementById('showImg').setAttribute('src', imageUrl)
                  document.getElementById('thumbId').setAttribute('value', image)
                  document.getElementById('imgDeleteBtn').style.display = "flex";
               });
            }
         });
   })

   document.getElementById('imgDeleteBtn').addEventListener('click', (e)=>{
      document.getElementById('showImg').setAttribute('src', "");
      document.getElementById('showImg').style.display = "none";
      document.getElementById('thumbId').setAttribute('value', "");
      document.getElementById('imgDeleteBtn').style.display = "none";
   })



   document.getElementById('select03').onchange = (e) => {
      getSubCategoryByParent(e.target.value)

      console.log(e.target.value);
      if(getSubCategoryByParent(e.target.value)){

      }
      else{
         var subCategoryOption = `<option value="">Select Sub Category</option>`;
         document.getElementById('select04').innerHTML = subCategoryOption;
         var subCategoryTypeOption = `<option value="">Select Sub Category Type</option>`;;
         document.getElementById('select05').innerHTML = subCategoryTypeOption;
      }
   }

   document.getElementById('select04').onchange = (e) => {
      getSubCategoryType(e.target.value)
   }

   function getSubCategoryByParent(categoryId){
      const formData = new FormData();
      formData.append('category', categoryId);
      fetch('getsubcategory', {
         method:'post',
         body:formData
      })
         .then(response => response.json())
         .then(json => {
            var subCategoryOption = `<option value="">Select Sub Category</option>`;
            json.forEach((item) => {
               subCategoryOption += `
                  <option value="${item.sub_category_id}">${item.sub_category_name}</option>;
               `; 
            })
            document.getElementById('select04').innerHTML = "";
            document.getElementById('select04').insertAdjacentHTML('afterBegin', subCategoryOption);
         });
   }

   function getSubCategoryType(subCategoryId){
      const formData = new FormData();
      formData.append('subcategory', subCategoryId);
      fetch('getsubcategorytype', {
         method:'post',
         body:formData
      })
         .then(response => response.json())
         .then(json => {
            var subCategoryTypeOption = `<option value="">Select Sub Category Type</option>`;;
            json.forEach((item) => {
               subCategoryTypeOption += `
                  <option value="${item.sub_category_type_id}">${item.sub_category_type_name}</option>;
               `; 
            })
            document.getElementById('select05').innerHTML = "";
            document.getElementById('select05').insertAdjacentHTML('afterBegin', subCategoryTypeOption);
         });
   }


         $("#dateDatePic").flatpickr({
            enableTime: true,
            minDate: "today",
            time_24hr: true,
            altInput: true,
            defaultDate: "2018-04-24 16:57"
         });
        </script>

    </body>

</html>