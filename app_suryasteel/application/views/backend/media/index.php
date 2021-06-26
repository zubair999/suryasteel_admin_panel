<style>
    tbody.list {
    display: grid;
    grid-template-columns: repeat(6,1fr);
}
.dropzone {
    position: relative;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
}
.modal-body {
    position: relative;
    flex: 1 1 auto;
    max-height: 500px;
    overflow: hidden scroll;
}



</style>


<script src='https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js' type='text/javascript'></script>


<script>
		// Add restrictions
		Dropzone.options.fileupload = {
		    acceptedFiles: 'image/*',
		    maxFilesize: 1 // MB,
			  renameFile: function (file) {
				  let newName = new Date().getTime() + '_' + file.name;
				  return newName;
			  }
		};
</script>

<div class="container-fluid page__container page-section pb-0">
   <h1 class="h2 mb-0"><?= ucwords($page_title) ?></h1>
   <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a >Home</a></li>
      <li class="breadcrumb-item">
         <a href="">Components</a>
      </li>
      <li class="breadcrumb-item active">
         Brand      </li>
   </ol>
</div>


<?php

$component = '<div class="page-separator">
<div class="page-separator__text"><a class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add New</a></div>
<div class="page-separator__text"><a class="btn btn-danger" id="deleteImage">Delete</a></div>
</div>';


  $theadObj = new TableFactory();
  $theadObj->renderTableHead($drawTable, $page_title, $tableId, $pl, $component);
?>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>




<script>
    $.noConflict();
    jQuery(document).ready(function($) {

        $('#medialist').DataTable({
              
              processing: true,
              responsive: true,
              serverSide: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
              scrollY:        "800px",
              scrollCollapse: true,
              stateSave: true,
              pagingType: "full_numbers",
              deferRender: true,
              ajax:{
                  url :"<?= base_url('get-media')?>",
                  type: "get",
                  error: function(response){
                      console.log("table error")
                      console.log(response);
                      $(".contacts-grid-error").html("");
                      $("#contacts-grid").append('<tbody class="contacts-grid-error"><tr><th align="center" colspan="5">No data found in the server</th></tr></tbody>');
                      $("#contacts-grid_processing").css("display","none");
                  }
              },
          });

          var table = $('#medialist').DataTable();
          var imageIdArr = [];
          $('#medialist').on( 'click', 'tr', function (e) {
            if(e.target.tagName === 'IMG'){
              imageIdArr.push(parseInt(e.target.getAttribute('data-imgId')))
              e.target.style.background = '#000';
              e.target.style.padding = '2px';
              if(e.target.getAttribute('data-selected') == 'Selected'){
                e.target.style.background = '#fff';
                e.target.style.padding = '0';
                e.target.removeAttribute('data-selected')
                imageIdArr = imageIdArr.filter(val => val !== parseInt(e.target.getAttribute('data-imgId')));
              }
              else{
                e.target.setAttribute('data-selected', 'Selected')
              }
            }
          });
          
          document.getElementById('deleteImage').addEventListener('click', (e) => {    
            

            const formData = new FormData();
            for(var key in imageIdArr){
              formData.append(`image[${key}]`, imageIdArr[key]);
            }
            fetch(location.origin+"/v1/delete-image",{
              method:'post',
              body:formData
            })
              .then(response => response.json())
              .then(json => {
                if(json.message == 'success'){
                  window.open(location.origin+'/media', "_self");
                }
              })
              .catch(error => {
                console.log(error)
              })

          })
          
      
      //JQUERY ENDS
    });

    

</script>



<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Upload Image</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid page__container page-section">
      
	   	
      <div class='content'>
         <!-- Dropzone -->
            <form action="<?= base_url('media-upload') ?>" class="dropzone" id='fileupload'>
            </form> 
      </div> 

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="reloadWindow()">Close</button>
      </div>
    </div>

  </div>
</div>


<script >
  function reloadWindow(){
    window.open(location.origin+'/media', "_self");
  }
</script>