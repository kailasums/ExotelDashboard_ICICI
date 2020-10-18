<?php $__env->startSection('content'); ?>
<div class="wrap border-bottom">
    <header id="header">
        <nav id="w5" class="navbar-inverse navbar-fixed-top navbar">
            <div class="container">
                <div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w5-collapse"><span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span></button>
                    <a class="navbar-brand" href="/dashboard"><img src="images/logo.png" alt=""></a>
                </div>
                <div id="w5-collapse" class="collapse navbar-collapse">
                    <ul id="w6" class="navbar-nav navbar-right nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icn-user"></i>Welcome <?php echo e(Auth::user()->name); ?></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/reset-password"><i class="icn-key"></i>Change password</a>
                                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <section id="middle">
        <div id="report-listing-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
            <div class="inner_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="summeryLink">
                                <a class="graphSummery" data-toggle="collapse" href="#piechart-graph" aria-expanded="false" aria-controls="piechart-graph">Hide Summary</a>
                            </div>

                            <div style="margin-bottom:10px;margin-top:10px;background: white;/* height:50px; */padding:12px;float: left;width: 100%">

                                <div class="selectType col-md-2">
                                    <div class="select">
                                        <?php echo Form::select('zone', (object) array_merge( [''=>'Zone'], (array) $zone), '',array('class' => 'user-regions-filter','form'=>"type"));; ?>

                                    </div>
                                </div>

                                <div class="selectType col-md-2">
                                    <div class="select">
                                        <?php echo Form::select('region', (object) array_merge( [''=>'Region'], (array) $region), '',array('class' => 'user-regions-filter','form'=>"type"));; ?>

                                    </div>
                                </div>

                                <div class="selectType col-md-2">
                                    <div class="select">
                                        <?php echo Form::select('branch', (object) array_merge( [''=>'Branch'], (array) $branch), '',array('class' => 'user-regions-filter','form'=>"type"));; ?>

                                    </div>
                                </div>

                                <div class="selectType col-md-2">
                                    <div class="select">
                                        <?php echo Form::select('user', (object) array_merge( [''=>'PB'], (array) $user), '',array('class' => 'user-regions-filter','form'=>"type"));; ?>

                                    </div>
                                </div>

                                <div class="selectType col-md-2">
                                    <div class="select">
                                        <?php echo Form::select('call_direction', (object) array_merge( [''=>'Call Direction'], (array) $call_direction), '',array('class' => 'user-regions-filter','form'=>"type"));; ?>

                                    </div>
                                </div>





                            </div>


                            <div id="piechart-graph" class="graph-section in" style="background: white;
    margin-bottom: 20px;">


                                <div class="row custom-padd">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <!--  <input type="hidden" name="googlechart1" value='[["Status","Calls"]]'> -->
                                            <div id="piechart4" class="">
                                                <div id="div-chartw0" >
                                                    <div id="totalrecords" class="completed-ui" style="margin-top: 20%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div id="piechart" class="bigPieChart">
                                                <div id="div-chartw1">
                                                    <div class="panel-body d-inline-block align-items-top" align="center">
                                                        <div id="pie_chart" style="width:700px; height:300px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div id="piechart2" class="">
                                                <div id="div-chartw2" style="margin-top: 20%;">
                                                    <ul>
                                                        <li>
                                                            <p style="font-size: 18px;color: black;/* font-weight: bold; */font-weight: 1000;">Total Calls : <span style="font-weight: normal;" id="totalCalls"></span></p>
                                                        </li>
                                                        <li>
                                                            <p  style="font-size: 18px;color: black;/* font-weight: bold; */font-weight: 1000;">Total Call Duration : <span style="font-weight: normal;" id="totalDurationCalls"></span></p>
                                                        </li>
                                                        <li>
                                                            <p  style="font-size: 18px;color: black;/* font-weight: bold; */font-weight: 1000;">Avg Calls : <span style="font-weight: normal;" id="avgCalls"></span></p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="display:none">
                                        <div class="card">
                                            <div id="piechart3" class="bigPieChart">
                                                <div id="div-chartw3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="calls-listing">
                                <div class="row">
                                    <div id="filters" class="col-md-2">
                                        <div class="filters-listing card">
                                            <a data-toggle="collapse" href="#statusOptionTab" aria-expanded="false" aria-controls="statusOptionTab">Call Status Options<i class="icn-down-arrow"></i></a>
                                            <div class="in" id="statusOptionTab">
                                                <div class="filters-category" id="statusOption">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" class="charecter-filter report-status-filter" name="call_status"  value="completed" id="Cancellations">
                                                        <label for="Cancellations">Complete</label>
                                                    </div>
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" class="charecter-filter report-status-filter" value="busy" name="call_status"  id="Completed">
                                                        <label for="Completed">Busy</label>
                                                    </div>
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" class="charecter-filter report-status-filter" name="call_status"  value="no answered" id="In Progress">
                                                        <label for="In Progress">No Answer</label>
                                                    </div>
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" class="charecter-filter report-status-filter" name="call_status" value="failed" id="Invalid">
                                                        <label for="Invalid">Failed</label>
                                                    </div>

                                                </div>
                                            </div>


                                            <!--  <a data-toggle="collapse" href="#Eta-Ima" aria-expanded="false" aria-controls="woType">ETA/IMA<i class="icn-down-arrow"></i></a>
                            <div class="filters-category in" id="Eta-Ima">
                              <div class="form-group selectType">
                                    <div class="select">
                                        <select name="type" form="type" class="eta-ima-filter form-control">
                                          <option value="">All</option>
                                          <option value='Blank' >Blank</option><option value='ETA' >ETA</option><option value='IMA' >IMA</option>                  </select>
                                    </div>
                                </div>
                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-10 pad-left">
                                        <div class="calls-listing-table card">
                                            <div class="search-box">
                                                <div class="form-group search-field" style="display:none">
                                                    <input class="form-control" type="text" placeholder="Subscriber ID or WO number or Contact No." name="sub_wo_number_filter" id="sub_wo_number_filter" value="">
                                                    <i class="icn-search"></i>
                                                </div>
                                                <div class="hide">
                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <?php echo Form::select('zone_summary', (object) array_merge( [''=>'Select Zone'], (array) $zone), '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?>

                                                        </div>
                                                    </div>



                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <?php echo Form::select('region_summary', (object) array_merge( [''=>'Select Region'], (array) $region), '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?>

                                                        </div>
                                                    </div>


                                                    <div class="form-group selectType col-md-3">
                                                        <div class="select">
                                                            <?php echo Form::select('branch_summary', (object) array_merge( [''=>'Select Branch'], (array) $branch), '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?>

                                                        </div>
                                                    </div>



                                                    <div class="form-group selectType col-md-3">
                                                        <div class="select">
                                                            <?php echo Form::select('user_summary', (object) array_merge( [''=>'Select User'], (array) $user), '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?>

                                                        </div>
                                                    </div>

                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <?php echo Form::select('call_direction_summary', (object) array_merge( [''=>'Select Direction'], (array) $call_direction), '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?>

                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="user-detail" id="userIdHidden" value="" />
                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <!-- <?php echo Form::select('call_status', (Object)[''=>'Select Call Status','completed'=>'Completed','failed'=>'Failed','busy'=>'Busy','No Answer'=>'No Answer'], '',array('class' => 'form-control user-regions-filter','form'=>"type"));; ?> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group date"><input type="text" id="datepickerFilter1" class="form-control" name="StartDate" data-date-container="#datepicker" data-provide="#datepicker"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                                                <div class="input-group date"><input type="text" id="datepickerFilter2" class="form-control" name="EndDate" value="<?php echo e(date('Y-m-d')); ?>" data-date-container="#datepicker1" data-provide="#datepicker1"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                                                <div id="datepicker"></div>
                                                <div id="datepicker1"></div>
                                                <div id="errorDate"><p class="date_diff"></p></div>
                                                <a id="reset-link-filter" class="reset-link" href="javascript:void(0)">Reset All</a>

                                            </div>
                                            <div class="calls-table">
                                                <div class="grid-view">
                                                    <div id="w4" class="list-view">
                                                        <table id="example" class="display" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Agent Name</th>
                                                                    <th>Agent Number</th>
                                                                    <th>Total Calls</th>
                                                                    <th>Total Duration</th>
                                                                    <th>Average Duration</th>
                                                                    <th>Calls Completed</th>
                                                                    <th>No Answer</th>
                                                                    <th>Busy</th>
                                                                    <th>Failed</th>
                                                                    <!-- <th>Call-hangup</th> -->
                                                                </tr>
                                                            </thead>
                                                            <!-- <tfoot>
                                                                <tr>
                                                                    <th>Agent Name</th>
                                                                    <th>Agent Number</th>
                                                                    <th>Total Calls</th>
                                                                    <th>Total Duration</th>
                                                                    <th>Average Duration</th>
                                                                    <th>Calls Completed</th>
                                                                    <th>No Answer</th>
                                                                    <th>Busy</th>
                                                                    <th>Failed</th>
                                                                    <th>Call-hangup</th>
                                                                </tr>
                                                            </tfoot> -->
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>

                                        <div class="calls-listing-table card" style="margin-top:20px;">
                                            <div class="calls-table" style="margin-top:20px;">
                                                <div class="grid-view">
                                                    <div id="w4" class="list-view">
                                                        <table id="example1" class="display" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                <tr>
                                                                    <th>Agent Name</th>
                                                                    <th>Agent Number</th>
                                                                    <th>Customer Number</th>
                                                                    <th>Date</th>
                                                                    <th>Call Type </th>
                                                                    <th>Call Status</th>
                                                                    <th>Call Sid</th>
                                                                    <th>Call Duration</th>
                                                                    <th>Dial Call Duration</th>
                                                                    <th>Call Recording Url</th>
                                                                </tr>
                                                            </thead>
                                                            <!-- <tfoot>
                                                                <tr>
                                                                    <th>Agent Name</th>
                                                                    <th>Agent Number</th>
                                                                    <th>Customer Number</th>
                                                                    <th>Date</th>
                                                                    <th>Call Type </th>
                                                                    <th>Call Status</th>
                                                                    <th>Call Sid</th>
                                                                    <th>Call Duration</th>
                                                                    <th>Dial Call Duration</th>
                                                                    <th>Call Recording Url</th>
                                                                </tr>
                                                            </tfoot> -->
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.common-chart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ISPCalling/resources/views/callRecord/google-pie-chart.blade.php ENDPATH**/ ?>