<?php
class Data_table_factory_model extends MY_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function drawTableData($counter, $id, $columnFactory,$row){
      $colArr = $columnFactory[1];
      $nestedData[0] = '<div class="nameID_'.$id.'">'.$counter.'</div>';
      
      $i = 1;
      $j = 0;
      foreach ($colArr as $key => $value) {
        $nestedData[$i] = '<div class="nameID_'.$id.'">'.ucwords($colArr[$j]).'</div>';

        $i++;
        $j++;
      }
      return $nestedData;
    }
    
    public function drawCustomTableData($counter, $id, $columnFactory,$row){
      $colArr = $columnFactory[1];
      $nestedData[0] = '<div class="nameID_'.$id.'">'.$counter.'</div>';
      
      $i = 1;
      $j = 0;
      foreach ($colArr as $key => $value) {
        $nestedData[$i] = $this->columnDesign($i,$id,$colArr[$j],$row);

        $i++;
        $j++;
      }
      return $nestedData;
    }

    public function drawMediaTableData($counter, $id, $columnFactory,$row){
      $colArr = $columnFactory[1];
      
      $i = 0;
      $j = 0;
      foreach ($colArr as $key => $value) {
        $nestedData[$i] = $this->columnDesign($i,$id,$colArr[$j],$row);

        $i++;
        $j++;
      }
      return $nestedData;
    }

    


    public function mediaColumnFactory($row){

      $data = '<div class="card card-sm card-group-row__card">
                     <div class="position-relative">
                        <div class="card-img-top">
                           <img src="'.base_url().'upload/'.$row->thumbnail.'" class="card-img-top card-img-cover" alt="" data-imgId='.$row->image_id.'>
                        </div>
                     </div>
                     
                  </div>';




      return array(
                    1 => array(
                      0 => $data
                    )
                );
    }


    private function columnDesign($i,$id,$data,$row){
      if($i == 1){
        return '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                  <div class="avatar avatar-32pt mr-8pt">

                      <img src="'.base_url().'upload/'.$row->thumbnail.'" style="width:25px"/>

                  </div>
                  <div class="media-body">

                      <div class="d-flex flex-column">
                          <p class="mb-0">
                            <strong class="js-lists-values-name">
                              
                              <a class="text-accent" href="'.base_url('edit-product-'.$id).'">'.ucwords($data).'</a>
                            </strong>
                          </p>
                          <small class="js-lists-values-email text-50">
                            <span class="badge badge-primary ">'.ucwords($row->brand_name).'</span>, 
                            <span class="badge badge-primary ">'.ucwords($row->category_name).'</span>, 
                            <span class="badge badge-info ">'.ucwords($row->mrp).'</span>,
                            <span class="badge badge-danger ">'.ucwords($row->sell_price).'</span>,
                          </small>
                      </div>
                      
                  </div>
              </div>';
      }
      else{


        return '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;" >
                  <div class="media-body">

                      <div class="d-flex flex-column">
                          <p class="mb-0"><strong class="js-lists-values-name">'.ucwords($data).'</strong></p>
                      </div>

                  </div>
              </div>';

      }

      
    }

    public function productsColumnFactory($row){
      return array(
                    1 => array(
                      0 => $row->product_name,
                      // 1 => $row->mrp
                    ),
                );
    }

    public function productsButtonFactory($id){
      $edit =  '<a href="'.base_url('edit-product-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a href="'.base_url('edit-product-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';



      $action =  '<div class="action-buttons">
                                '.$edit.'
                                '.$delete.'
                              </div>';

      
      
                
      

      return $action;
    }



    public function orderColumnFactory($row){
      return array(
                    1 => array(
                      0 => '#'.$row->order_id,
                      1 => $row->name,
                      2 => $row->total_mrp,
                      3 => $row->total_sell_price,
                      4 => $row->discount,
                      5 => $row->paid,
                      6 => $row->status
                    ),
                );
    }

    public function orderButtonFactory($id){
      $edit =  '<a href="'.base_url('edit-order-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a href="'.base_url('edit-order-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';

      $action =  '<div class="action-buttons">
                                '.$edit.'
                                '.$delete.'
                              </div>';
      return $action;
    }
























 

   


 


       

        
         

     


    



     
      


      




       


    



    

      

     
      





      


//end
}
