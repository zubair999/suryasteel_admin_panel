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
        $nestedData[$i] = $this->columnDesign($i,$id,$colArr[$j],$row);
        $i++;
        $j++;
      }
      return $nestedData;
    }


    public function mediaColumnFactory($row){
      return array(
                    1 => array(
                      0 => $row->image_name,
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
                          <small class="js-lists-values-email text-50"><span class="badge badge-primary">'.ucwords($row->brand_name).'</span>, 
                            <span class="text-accent">Size:'.$row->size_value .'</span>, 
                            <span class="text-accent">Quality: '.$row->quality_value.'</span>
                          </small>
                      </div>
                      
                  </div>
              </div>';
      }
      else{


        return '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                  <div class="media-body">

                      <div class="d-flex flex-column">
                          <p class="mb-0"><strong class="js-lists-values-name">'.ucwords($data).'</strong></p>
                      </div>

                  </div>
              </div>';

      }

      
    }


    private function createButton($url,$id,$title,$btnClr, $btnType){
        return '<a href="'.base_url($url.'-'.$id).'"><span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">'.$btnType.'</span></a>';
    }

    public function productsColumnFactory($row){
      $resultTableCol = array(
          1 => array(
            0 => $row->product_name,
            1 => $row->mrp,
            2 => $row->dp,
            3 => '%'.$row->gst_value,
            4 => $row->total,
          )
      );
      return $resultTableCol;
    }

    public function productsButtonFactory($id){
      $delete = $this->createButton('delete-product',$id,'edit','warning', 'delete');
      $edit = $this->createButton('edit-product',$id,'edit','warning', 'edit');

      $studentBtn =  '<div class="action-buttons">
                        '.$delete.'
                     </div>';

      $adminbtn =  '<div class="action-buttons">
                        '.$edit.'
                        '.$delete.'
                     </div>';

      return $adminbtn;
      
    }

    public function quizColumnFactory($row){
      switch ($this->level) {
            case 1:
                return $this->quizAdminTableCol($row);
                break;
            case 3:
                return $this->quizStudentTableCol($row);
                break;
      }
    }


    private function quizAdminTableCol($row){
        return array(
          '1' => array(
            '0' => $row->quiz_name,
            '1' => $row->description,
            '2' => $row->type_name,
            '3' => $row->noq,
            '4' => $row->correct_score,
            '5' => $row->incorrect_score,
            '6' => $row->duration,
            '7' => $row->pass_percentage
          )
        );
    }
    private function quizStudentTableCol($row){
        return array(
          '1' => array(
            '0' => $row->quiz_name,
            '1' => $row->description,
            '2' => $row->type_name,
            '3' => $row->noq,
            '4' => $row->correct_score,
            '5' => $row->incorrect_score,
            '6' => $row->duration,
            '7' => $row->pass_percentage,
            '8' => $row->purchase_date,
            '9' => $row->purchase_date,
            '10' => $row->purchase_date
          )
      );
    }

    public function qbankColumnFactory($row){
      return array(
                    '1' => array(
                      '0' => $row->question,
                      '1' => $row->question_type
                    )
                );
    }


    public function qbankButtonFactory($id){
      $edit = $this->createButton('edit-question',$id,'edit','warning');

      $action[1] =  '<div class="action-buttons">
                                '.$edit.'
                              </div>';

      // if(isset($this->roleId)){
            $tableArr = array(
                $admin = array(
                    'btn' => $action[1]
                ),
                $business = array('btn' => $action[1])
            );
      // }

      return $tableArr[0];
    }

    public function quizqbankColumnFactory($row){
      return array(
                    '1' => array(
                      '0' => $row->question,
                      '1' => $row->question_type,
                      '2' => $row->cname,
                      '3' => $row->subject_name
                    ),
                );
    }

    public function quizqbankButtonFactory($quid,$qid){
      $add =  '<a data-quid='.$quid.' data-qid='.$qid.' class="btn btn-primary btn-xs text-capitalize">Add</a>';
      $action[1] =  '<div class="action-buttons">
                                '.$add.'
                              </div>';
      $tableArr = array(
          $admin = array(
              'btn' => $action[1]
          ),
          $business = array('btn' => $action[1])
      );
      return $tableArr[0];
    }


    private function check_if_question_added($quid,$qid){
      $decrypt_quid = $this->outh_m->Encryptor('decrypt', $quid);
      $decrypt_qid = $this->outh_m->Encryptor('decrypt', $qid);
      $qids = $this->db->get_obj('quiz','qids',array('quid'=>$decrypt_quid))->row()->qids;
      $qid_arr = explode(",",$qids);
      foreach ($qid_arr as $key => $oldqid) {
        if($oldqid == $decrypt_qid){
          $add = '<a data-quid='.$quid.' data-qid='.$qid.' class="btn btn-primary btn-xs text-capitalize">Added</a>';
          return $add;
        }
        else{
          $add =  '<a data-quid='.$quid.' data-qid='.$qid.' class="btn btn-primary btn-xs text-capitalize">Add</a>';
          return $add;
        }
      }

    }


    public function getquizquestionColumnFactory($row){
      return array(
                    '1' => array(
                      '0' => $row->question,
                      '1' => $row->question_type,
                      '2' => $row->cname,
                      '3' => $row->subject_name
                    ),
                );
    }

    public function getquizquestionButtonFactory($quid,$qid){
      $edit = $this->createButton('edit-question',$qid,'edit','warning');
      $remove = '<a data-removeQid='.$qid.' data-quizquid='.$quid.' class="btn btn-danger btn-xs text-capitalize">Remove</a>';
      $action[1] =  '<div class="action-buttons">
                                '.$edit.'
                                '.$remove.'
                              </div>';
      $tableArr = array(
          $admin = array(
              'btn' => $action[1]
          ),
          $business = array('btn' => $action[1])
      );
      return $tableArr[0];
    }

    public function classColumnFactory($row){
      return array(
                    '1' => array(
                      '0' => $row->cname
                      // '1' => $row->eid

                    )
                );
    }


    public function classButtonFactory($id){
      $edit = $this->createButton('edit-class',$id,'edit','warning');

      $action[1] =  '<div class="action-buttons">

                                '.$edit.'
                              </div>';

      // if(isset($this->roleId)){
            $tableArr = array(
                $admin = array(
                    'btn' => $action[1]
                ),
                $business = array('btn' => $action[1])
            );
      // }

      return $tableArr[0];
    }

    public function subjectColumnFactory($row){
      return array(
                    '1' => array(
                      '0' => $row->subject_name
                    ),
                );
    }

    public function subjectButtonFactory($id){
       $edit = $this->createButton('edit-subject',$id,'edit','warning');
      //$edit =  '<a href="'.base_url('edit-subject/'.$id).'" class="btn btn-warning btn-xs text-capitalize">edit</a>';
      $action[1] =  '<div class="action-buttons">

                                '.$edit.'
                              </div>';

      // if(isset($this->roleId)){
            $tableArr = array(
                $admin = array(
                    'btn' => $action[1]
                ),
                $business = array('btn' => $action[1])
            );
      // }

      return $tableArr[0];
    }


  //syllabus

      public function syllabusColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->pdf_name,
                        '1' => $row->cname,
                        '2'=> $row->subject_name
                      ),
                  );
      }

      public function syllabusButtonFactory($id){

         $edit = $this->createButton('edit-syllabus',$id,'edit','warning');
        //$edit =  '<a href="'.base_url('edit-subject/'.$id).'" class="btn btn-warning btn-xs text-capitalize">edit</a>';
        $action[1] =  '<div class="action-buttons">

                                  '.$edit.'
                                </div>';

        // if(isset($this->roleId)){
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        // }

        return $tableArr[0];
      }
      //video

          public function videoColumnFactory($row){
           return array(
                         '1' => array(
                           '0' => $row->cname,
                           '1' => $row->subject_name,
                           '2' => $row->topic_name,
                           // '3' => $row->video_link
                         ),
                     );
         }

         public function videoButtonFactory($id){
            $edit = $this->createButton('edit-video',$id,'edit','warning');
            $watch_video =  '<a data-vid='.$id.' class="btn btn-primary btn-xs text-capitalize">Watch Video</a>';
            $action[1] =  '<div class="action-buttons">
                                     '.$edit.'
                                     '.$watch_video.'
                                   </div>';
           // if(isset($this->roleId)){
                 $tableArr = array(
                     $admin = array(
                         'btn' => $action[1]
                     ),
                     $business = array('btn' => $action[1])
                 );
           // }
           return $tableArr[0];
         }

         //dictionary

              public function dictionaryColumnFactory($row){
                $img = $row->img;
               return array(
                             '1' => array(
                               '0' => '<img src=upload/dictionary/'.$img.' style="width:50px; height=50px;">',
                               '1' => $row->topic_name,
                               '2' => $row->text
                             ),
                         );
             }

             public function dictionaryButtonFactory($id){

                $edit = $this->createButton('edit-dictionary',$id,'edit','warning');
               //$edit =  '<a href="'.base_url('edit-subject/'.$id).'" class="btn btn-warning btn-xs text-capitalize">edit</a>';
               $action[1] =  '<div class="action-buttons">
                                         '.$edit.'
                                       </div>';
               // if(isset($this->roleId)){
                     $tableArr = array(
                         $admin = array(
                             'btn' => $action[1]
                         ),
                         $business = array('btn' => $action[1])
                     );
               // }
               return $tableArr[0];
             }

     /*State */
       public function stateColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->state_name,
                      ),
                  );
      }

      public function stateButtonFactory($id){

         $edit = $this->createButton('edit-state',$id,'edit','warning');
         $delete = $this->createButton('delete-state',$id,'delete','danger');
        //$edit =  '<a href="'.base_url('edit-subject/'.$id).'" class="btn btn-warning btn-xs text-capitalize">edit</a>';
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
        // if(isset($this->roleId)){
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        // }
        return $tableArr[0];
      }


    /*City */
      public function cityColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->city_name,
                       '1' => $row->state_name
                     ),
                 );
     }

     public function cityButtonFactory($id){
        $edit = $this->createButton('edit-city',$id,'edit','warning');
        $delete = $this->createButton('delete-city',$id,'delete','danger');
       //$edit =  '<a href="'.base_url('edit-subject/'.$id).'" class="btn btn-warning btn-xs text-capitalize">edit</a>';
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.' '.$delete.'
                               </div>';
       // if(isset($this->roleId)){
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       // }
       return $tableArr[0];
     }



     /*Student */
       public function studentColumnFactory($row){
        switch ($this->level) {
          case 1:
            return $this->studentAdminTableCol($row);
            break;
            case 2:
              return $this->studentSchoolTableCol($row);
              break;
        }
      }

      private function studentAdminTableCol($row){
          return array(
            '1' => array(
              '0' => $row->full_name,
              '1' => $row->phone_no,
              '2' => $row->state_name,
              '3' => $row->city_name,
              '4' => $row->cname,
            )
          );
      }
      private function studentSchoolTableCol($row){
          return array(
            '1' => array(
              '0' => $row->full_name,
              '1' => $row->phone_no,
              '2' => $row->state_name,
              '3' => $row->city_name,
              '4' => $row->cname,
            )
        );
      }

      public function studentButtonFactory($id){
        $edit = $this->createButton('view-student-detail',$id,'view','primary');

        $schoolBtn =  '<div class="action-buttons">
                          '.$edit.'
                       </div>';
        $adminbtn =  '<div class="action-buttons">
                          '.$edit.'
                       </div>';
        switch ($this->level) {
              case 1:
                  return $adminbtn;
                  break;
              case 2:
                  return $schoolBtn;
                  break;
        }
      }


      /*School*/
      public function schoolColumnFactory($row){
          return array(
            '1' => array(
              '0' => $row->full_name,
              '1' => $row->phone_no,
              '2' => $row->state_name,
              '3' => $row->city_name,
            )
          );
      }

      public function schoolButtonFactory($id){
        $edit = $this->createButton('view-school-detail',$id,'view','primary');

        $schoolBtn =  '<div class="action-buttons">
                          '.$edit.'
                       </div>';
        $adminbtn =  '<div class="action-buttons">
                          '.$edit.'
                       </div>';
        switch ($this->level) {
              case 1:
                  return $adminbtn;
                  break;
              case 2:
                  return null;
                  break;
        }
      }




       /*Brand */
       public function brandColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->brand_name,
                        '1' => $row->phone1,
                        '2' => $row->address1,
                        '3' => $row->brand_technical_code,
                      ),
                  );
      }

      public function brandButtonFactory($id){

         $edit = $this->createButton('edit-brand',$id,'edit','warning');
         $delete = $this->createButton('delete-brand',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
      }


    /*Pack */
       public function packColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->pack_name,
                      ),
                  );
      }

      public function packButtonFactory($id){

         $edit = $this->createButton('edit-pack',$id,'edit','warning');
         $delete = $this->createButton('delete-pack',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
      }



    /*Color */
       public function colorColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->color_name,
                        '1' => $row->color_code,
                      ),
                  );
      }

      public function colorButtonFactory($id){

         $edit = $this->createButton('edit-color',$id,'edit','warning');
         $delete = $this->createButton('delete-color',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
      }

      /*category*/

      public function categoryColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->cname,
                       '1' => $row->cname_desc,
                     ),
                 );
      }

      public function categoryButtonFactory($id){

        $edit = $this->createButton('edit-category',$id,'edit','warning');
        $delete = $this->createButton('delete-category',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.' '.$delete.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }
      /*product*/

      public function productColumnFactory($row){
        if($row->is_active == 0)
        {
          $status = 'no';
        }
        else{
          $status = 'yes';
        }
       return array(
                     '1' => array(
                       '0' => '<img src='.UPLOAD.'/products/'.$row->default_image.' width="50px" height="50px">',
                       '1' => $row->pname,
                       '2' => $status ,
                       '3' => $row->product_sku_code,
                     ),
                 );
      }

      public function productButtonFactory($id){
        $edit = $this->createButton('edit-product',$id,'edit','warning');
        // $delete = $this->createButton('delete-product',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }

      /*components*/

      public function componentColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->component_name,
                       '1' => $row->position,
                       '2' => $row->is_active,
                       '3' => $row->discount,
                     ),
                 );
      }

      public function componentButtonFactory($id){
        $edit = $this->createButton('edit-component',$id,'edit','warning');
        // $delete = $this->createButton('delete-component',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }



      /*order*/
      public function orderColumnFactory($row){
      if($row->delivery_exe_it == 0)
      {
        $status = '<span class="label label-success">Not Deliver</span>';
      }
      else{
        $status = '<span class="label" style="background-color:#00a65a !important">Deliverd</span>';
      }
       return array(
                     '1' => array(
                       '0' => $row->first_name,
                       '1' => $row->amount,
                       '2' => $row->order_date_time,
                       '3' => $row->first_name,
                       '4' => $status,
                     ),
                 );
      }

      public function orderButtonFactory($id){
        $invoice = $this->createButton('edit-order',$id,'invoice','primary');
        $detail  = $this->createButton('delete-order',$id,'detail ','primary');
        $delass  =  '<button id="assign_delivery" class="btn btn-primary btn-xs text-capitalize">assign delivery boy</button>';

       $action[1] =  '<div class="action-buttons">
                                 '.$delass.' '.$invoice.' '.$detail .'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }



      /*deliveryboyorder*/
      public function delBoyorderColumnFactory($row){
      if($row->delivery_exe_it == 0)
      {
        $status = '<span class="label" style="background-color:#00a65a !important">Not Deliver</span>';
      }
      else{
        $status = '<span class="label" style="background-color:#00a65a !important">Deliverd</span>';
      }
       return array(
                     '1' => array(
                       '0' => $row->first_name,
                       '1' => $status,
                     ),
                 );
      }

      public function delBoyorderButtonFactory($id){
        $delass  =  '<button id="delivery_order_modal" class="btn btn-primary btn-xs text-capitalize">change status</button>';
       $action[1] =  '<div class="action-buttons">
                                 '.$delass.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }


      /*shipper*/

      public function shipperColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->shipper_name,
                      '1' => $row->phone_no,
                     ),
                 );
      }

      public function shipperButtonFactory($id){

        $edit = $this->createButton('edit-shipper',$id,'edit','warning');
        $delete = $this->createButton('delete-shipper',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.' '.$delete.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }

      /*warehouse*/

      public function warehouseColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->warehouse_name,
                       '1' => $row->phone_no,
                       '2' => $row->address,
                     ),
                 );
      }

      public function warehouseButtonFactory($id){

        $edit = $this->createButton('edit-warehouse',$id,'edit','warning');
        // $delete = $this->createButton('delete-warehouse',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }

      /*route*/

      public function routeColumnFactory($row){
       return array(
                     '1' => array(
                       '0' => $row->route_name,
                       '1' => $row->pincode,
                     ),
                 );
      }

      public function routeButtonFactory($id){

        $edit = $this->createButton('edit-route',$id,'edit','warning');
        $delete = $this->createButton('delete-route',$id,'delete','danger');
       $action[1] =  '<div class="action-buttons">
                                 '.$edit.''.$delete.'
                               </div>';
             $tableArr = array(
                 $admin = array(
                     'btn' => $action[1]
                 ),
                 $business = array('btn' => $action[1])
             );
       return $tableArr[0];
      }


            /*vehicle*/

            public function vehicleColumnFactory($row){
             return array(
                           '1' => array(
                             '0' => $row->type,
                             '1' => $row->vehicle_no,
                             '2' => $row->brand,
                             '3' => $row->model_name,
                             '4' => $row->warehouse_id,
                           ),
                       );
            }

            public function vehicleButtonFactory($id){

              $edit = $this->createButton('edit-vehicle',$id,'edit','warning');
              $delete = $this->createButton('delete-vehicle',$id,'delete','danger');
             $action[1] =  '<div class="action-buttons">
                                       '.$edit.''.$delete.'
                                     </div>';
                   $tableArr = array(
                       $admin = array(
                           'btn' => $action[1]
                       ),
                       $business = array('btn' => $action[1])
                   );
             return $tableArr[0];
            }

      /*Price tag*/
       public function price_tagColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->price_tag,
                      ),
                  );
      }

      public function price_tagButtonFactory($id){
         $edit = $this->createButton('edit-price-tag',$id,'edit','warning');
         $delete = $this->createButton('delete-price-tag',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
      }


    /*Promos*/
       public function promoColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->promo_name,
                        '1' => $row->start_date,
                        '2' => $row->end_date,
                        '3' => $row->promo_percent,
                      ),
                  );
      }

      public function promoButtonFactory($id){
         $edit = $this->createButton('edit-promos',$id,'edit','warning');
         $delete = $this->createButton('delete-promos',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
      }


      /*Discount*/
         public function discountColumnFactory($row){
          return array(
                        '1' => array(
                          '0' => $row->value,
                        ),
                    );
        }

        public function discountButtonFactory($id){
           $edit = $this->createButton('edit-discount',$id,'edit','warning');
           $delete = $this->createButton('delete-discount',$id,'delete','danger');
          $action[1] =  '<div class="action-buttons">
                                    '.$edit.' '.$delete.'
                                  </div>';
                $tableArr = array(
                    $admin = array(
                        'btn' => $action[1]
                    ),
                    $business = array('btn' => $action[1])
                );
          return $tableArr[0];
        }


        /*Level*/
         public function levelColumnFactory($row){
          return array(
                        '1' => array(
                          '0' => $row->roles,
                        ),
                    );
        }

        public function levelButtonFactory($id){
           $edit = $this->createButton('edit-level',$id,'edit','warning');
           $delete = $this->createButton('delete-level',$id,'delete','danger');
          $action[1] =  '<div class="action-buttons">
                                    '.$edit.' '.$delete.'
                                  </div>';
                $tableArr = array(
                    $admin = array(
                        'btn' => $action[1]
                    ),
                    $business = array('btn' => $action[1])
                );
          return $tableArr[0];
        }


         /*Level*/
         public function countryColumnFactory($row){
          return array(
                        '1' => array(
                          '0' => $row->country_name,
                        ),
                    );
        }

        public function countryButtonFactory($id){
           $edit = $this->createButton('edit-country',$id,'edit','warning');
           $delete = $this->createButton('delete-country',$id,'delete','danger');
          $action[1] =  '<div class="action-buttons">
                                    '.$edit.' '.$delete.'
                                  </div>';
                $tableArr = array(
                    $admin = array(
                        'btn' => $action[1]
                    ),
                    $business = array('btn' => $action[1])
                );
          return $tableArr[0];
        }


        /*Level*/
        public function pickupboyColumnFactory($row){
         return array(
                       '1' => array(
                         '0' => $row->first_name,
                         '1' => $row->last_name,
                         '2' => $row->email,
                         '3' => $row->phone,
                         '4' => $row->warehouse_name,
                       ),
                   );
       }

       public function pickupboyButtonFactory($id){
          $edit = $this->createButton('edit-pickupboy',$id,'edit','warning');
          $delete = $this->createButton('delete-pickupboy',$id,'delete','danger');
         $action[1] =  '<div class="action-buttons">
                                   '.$edit.' '.$delete.'
                                 </div>';
               $tableArr = array(
                   $admin = array(
                       'btn' => $action[1]
                   ),
                   $business = array('btn' => $action[1])
               );
         return $tableArr[0];
       }

       /*Level*/
       public function deliveryboyColumnFactory($row){
        return array(
                      '1' => array(
                        '0' => $row->first_name,
                        '1' => $row->last_name,
                        '2' => $row->email,
                        '3' => $row->phone,
                        '4' => $row->vehicle_no,
                      ),
                  );
       }

       public function deliveryboyButtonFactory($id){
         $edit = $this->createButton('edit-deliveryboy',$id,'edit','warning');
         $delete = $this->createButton('delete-deliveryboy',$id,'delete','danger');
        $action[1] =  '<div class="action-buttons">
                                  '.$edit.' '.$delete.'
                                </div>';
              $tableArr = array(
                  $admin = array(
                      'btn' => $action[1]
                  ),
                  $business = array('btn' => $action[1])
              );
        return $tableArr[0];
       }


//end
}
