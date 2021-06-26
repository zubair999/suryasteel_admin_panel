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





    <div class="page-separator">
        <div class="page-separator__text">Clients</div>
    </div>

    <div class="card mb-lg-32pt">

        <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date" data-lists-sort-desc="true" data-lists-values="[&quot;js-lists-values-name&quot;, &quot;js-lists-values-company&quot;, &quot;js-lists-values-phone&quot;, &quot;js-lists-values-date&quot;]">

            <table class="table mb-0 thead-border-top-0 table-nowrap">
                <thead>
                    <tr>

                        <th style="width: 18px;" class="pr-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#clients" id="customCheckAll" data-domfactory-upgraded="toggle-check-all">
                                <label class="custom-control-label" for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                            </div>
                        </th>

                        <th>
                            <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a>
                        </th>

                        <th style="width: 150px;">
                            <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-company">Company</a>
                        </th>

                        <th style="width: 37px;">Tags</th>

                        <th style="width: 48px;">
                            <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-phone">Phone</a>
                        </th>

                        <th style="width: 120px;">
                            <a href="javascript:void(0)" class="sort desc" data-sort="js-lists-values-date">Added</a>
                        </th>
                        <th style="width: 24px;"></th>
                    </tr>
                </thead>
                <tbody class="list" id="clients">
                    <tr>

                        <td class="pr-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_1" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label" for="customCheck1_1"><span class="text-hide">Check</span></label>
                            </div>
                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-32pt mr-8pt">

                                    <span class="avatar-title rounded-circle">BN</span>

                                </div>
                                <div class="media-body">

                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-name">Billy Nunez</strong></p>
                                        <small class="js-lists-values-email text-50">annabell.kris@yahoo.com</small>
                                    </div>

                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-warning">FM</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-company"><strong>Frontend Matter Inc.</strong></small>
                                        <small class="js-lists-values-location text-50">Leuschkefurt</small>
                                    </div>
                                </div>
                            </div>

                        </td>

                        <td>

                            <a href="" class="chip chip-outline-secondary">User</a>

                        </td>

                        <td>
                            <small class="js-lists-values-phone text-50">239-721-3649</small>
                        </td>

                        <td>
                            <small class="js-lists-values-date text-50">19 February 2019</small>
                        </td>
                        <td class="text-right">
                            <a href="" class="text-50"><i class="material-icons">more_vert</i></a>
                        </td>
                    </tr>
                    <tr>

                        <td class="pr-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_2" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label" for="customCheck1_2"><span class="text-hide">Check</span></label>
                            </div>
                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-32pt mr-8pt">

                                    <span class="avatar-title rounded-circle">TP</span>

                                </div>
                                <div class="media-body">

                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-name">Tony Parks</strong></p>
                                        <small class="js-lists-values-email text-50">vida_glover@gmail.com</small>
                                    </div>

                                </div>
                            </div>

                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-accent">HH</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-company"><strong>Huma Huma Inc.</strong></small>
                                        <small class="js-lists-values-location text-50">Mayerberg</small>
                                    </div>
                                </div>
                            </div>

                        </td>

                        <td>

                            <a href="" class="chip chip-outline-secondary">Admin</a>

                        </td>

                        <td>
                            <small class="js-lists-values-phone text-50">169-769-4821</small>
                        </td>

                        <td>
                            <small class="js-lists-values-date text-50">18 February 2019</small>
                        </td>
                        <td class="text-right">
                            <a href="" class="text-50"><i class="material-icons">more_vert</i></a>
                        </td>
                    </tr>
                    <tr class="selected">

                        <td class="pr-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" checked="" id="customCheck1_3" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label" for="customCheck1_3"><span class="text-hide">Check</span></label>
                            </div>
                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-32pt mr-8pt">

                                    <img src="<?php echo ADMIN ?>assets/images/people/110/guy-1.jpg" alt="Avatar" class="avatar-img rounded-circle">

                                </div>
                                <div class="media-body">

                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-name">Gilbert Barrett</strong></p>
                                        <small class="js-lists-values-email text-50">paolo.zieme@gmail.com</small>
                                    </div>

                                </div>
                            </div>

                        </td>

                        <td>

                        </td>

                        <td>

                            <a href="" class="chip chip-outline-secondary">Admin</a>

                        </td>

                        <td>
                            <small class="js-lists-values-phone text-50">462-060-7408</small>
                        </td>

                        <td>
                            <small class="js-lists-values-date text-50">17 February 2019</small>
                        </td>
                        <td class="text-right">
                            <a href="" class="text-50"><i class="material-icons">more_vert</i></a>
                        </td>
                    </tr>
                    <tr class="selected">

                        <td class="pr-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" checked="" id="customCheck1_4" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label" for="customCheck1_4"><span class="text-hide">Check</span></label>
                            </div>
                        </td>

                        <td>

                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-32pt mr-8pt">

                                    <img src="<?php echo ADMIN ?>assets/images/people/110/guy-2.jpg" alt="Avatar" class="avatar-img rounded-circle">

                                </div>
                                <div class="media-body">

                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-name">Ollie Wallace</strong></p>
                                        <small class="js-lists-values-email text-50">lorna_kirlin@nora.biz</small>
                                    </div>

                                </div>
                            </div>

                        </td>

                        <td>

                        </td>

                        <td>

                            <a href="" class="chip chip-outline-secondary">Manager</a>

                        </td>

                        <td>
                            <small class="js-lists-values-phone text-50">285-626-6050</small>
                        </td>

                        <td>
                            <small class="js-lists-values-date text-50">16 February 2019</small>
                        </td>
                        <td class="text-right">
                            <a href="" class="text-50"><i class="material-icons">more_vert</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer p-8pt">

            <ul class="pagination justify-content-start pagination-xsm m-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                        <span>Prev</span>
                    </a>
                </li>
                <li class="page-item dropdown">
                    <a class="page-link dropdown-toggle" data-toggle="dropdown" href="#" aria-label="Page">
                        <span>1</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="" class="dropdown-item active">1</a>
                        <a href="" class="dropdown-item">2</a>
                        <a href="" class="dropdown-item">3</a>
                        <a href="" class="dropdown-item">4</a>
                        <a href="" class="dropdown-item">5</a>
                    </div>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span>Next</span>
                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- <div class="card-body bordet-top text-right">
  15 <span class="text-50">of 1,430</span> <a href="#" class="text-50"><i class="material-icons ml-1">arrow_forward</i></a>
</div> -->

    </div>























</div>