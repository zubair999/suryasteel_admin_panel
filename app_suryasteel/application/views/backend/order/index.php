

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

$component = '';


  $theadObj = new TableFactory();
  $theadObj->renderTableHead($drawTable, $page_title, $tableId, $pl, $component);
?>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>




<script>
    $.noConflict();
    jQuery(document).ready(function($) {

        $('#orderlist').DataTable({
              dom: 
                    "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
              buttons: [
                  'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              processing: false,
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
                  url :"<?= base_url('get-order')?>",
                  type: "get",
                  error: function(response){
                      console.log("table error")
                      console.log(response);
                      $(".contacts-grid-error").html("");
                      $("#contacts-grid").append('<tbody class="contacts-grid-error"><tr><th align="center" colspan="5">No data found in the server</th></tr></tbody>');
                      $("#contacts-grid_processing").css("display","none");
                  }
              },
              initComplete: function () {
                  var api = this.api();
                  api.$('td').click( function () {
                      api.search( this.innerHTML ).draw();
                  } );
              }
          });


          

          
      
      //JQUERY ENDS
    });

    

</script>