<?php
class Data_table_factory_model extends MY_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function drawTableData($counter, $id, $columnFactory, $row){
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
    
    public function staffColumnFactory($row){
      return array(
                    1 => array(
                      0 => ucwords($row->firstname. ' ' . $row->lastname),
                      1 => $row->email,
                      2 => $row->mobile_no,
                      3 => $row->roles_name,
                      4 => $row->is_active

                )
                    );
    }

    public function staffButtonFactory($id){
      $edit =  '<a title="Edit Staff" href="'.base_url('edit-staff-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a title="Delete Staff" href="'.base_url('delete-staff-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';
      $change_password =  '<a title="Change Password" href="'.base_url('update-password-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">lock</span></a>';

      $action =  '<div class="action-buttons">
                                '.$change_password.'
                                '.$edit.'
                                '.$delete.'
                              </div>';
      return $action;
    }



    public function roleColumnFactory($row){
      return array(
                    1 => array(
                      0 => ucwords($row->roles_name),
                )
                    );
    }

    public function roleButtonFactory($id){
      $edit =  '<a href="'.base_url('edit-role-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a href="'.base_url('delete-role-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';

      $action =  '<div class="action-buttons">
                                '.$edit.'
                                '.$delete.'
                              </div>';
      return $action;
    }


    public function orderColumnFactory($row){
      return array(
                    1 => array(
                      0 => $row->order_amount,
                )
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



    public function customerColumnFactory($row){
      return array(
                    1 => array(
                      0 => ucwords($row->firstname. ' ' . $row->lastname),
                      1 => $row->email,
                      2 => $row->mobile_no,
                      3 => $row->customer_company,
                      4 => $row->gst_reg_type,
                      5 => $row->is_active,
                )
                    );
    }

    public function customerButtonFactory($id){
      $change_password =  '<a title="Change Password" href="'.base_url('update-password-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">lock</span></a>';
      $edit =  '<a title="Edit Customer" href="'.base_url('edit-customer-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a title="Delete Customer" href="'.base_url('delete-customer-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';

      $action =  '<div class="action-buttons">
                                '.$change_password.'
                                '.$edit.'
                                '.$delete.'
                              </div>';
      return $action;
    }


    public function productsColumnFactory($row){
      return array(
                    1 => array(
                      0 => ucwords($row->product_name)
                )
                    );
    }

    public function productsButtonFactory($id){
      $edit =  '<a href="'.base_url('edit-order-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">edit</span></a>';
      $delete =  '<a href="'.base_url('edit-order-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">delete</span></a>';

      $action =  '<div class="action-buttons">
                                '.$edit.'
                                '.$delete.'
                              </div>';
      return $action;
    }

    public function logColumnFactory($row){
      return array(
                    1 => array(
                      0 => ucwords($row->firstname. ' ' . $row->lastname),
                      1 => $row->title,
                      2 => $row->description,
                      3 => $row->created_on
                )
            );
    }






















 

   


 


       

        
         

     


    



     
      


      




       


    



    

      

     
      





      


//end
}
