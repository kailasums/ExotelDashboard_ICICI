@extends('layouts.common-auth')
@section('content')
<div class="wrap border-bottom">

    <header id="header">
        <nav id="w5" class="navbar-inverse navbar-fixed-top navbar">
            <div class="container">
                <div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w5-collapse"><span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span></button><a class="navbar-brand" href="/dashboard"><img src="{{URL::asset('images/logo.png')}}" alt=""></a></div>
                <div id="w5-collapse" class="collapse navbar-collapse">
                    <ul id="w6" class="navbar-nav navbar-right nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icn-user"></i>Welcome NSM</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="/site/change-password"><i class="icn-key"></i>Change password</a>
                                <a class="dropdown-item" href=""><i class="icn-logout"></i>
                                    <form action="/site/logout" method="post">
                                        <input type="hidden" name="_csrf" value="ZBlQvltMSYMH-bPSq60J11SjQaixAeFCcj_BXw1jQ3YAYSaHayEEsmGSg57JnWGYIPIU0eJnths8WKlqPyt3OA=="><button type="submit">Logout</button></form>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="middle">
        <div id="report-listing-pjax" data-pjax-container="" data-pjax-push-state data-pjax-timeout="1000">
            <div class="inner_bg">
                <div class="container">


                    <div class="row">
                        <div class="col-md-12">
                            <div class="summeryLink">
                                <a class="graphSummery" data-toggle="collapse" href="#piechart-graph" aria-expanded="false" aria-controls="piechart-graph">Hide Summary</a>
                            </div>
                            <div id="piechart-graph" class="graph-section in">
                                <div class="row custom-padd">
                                    <div class="col-md-3">
                                        <div id="filters" class="col-md-12">
                                            <div class="total-calls card">
                                                <i class="icn-call"></i>
                                                <h2>0</h2>
                                                <p>Total Calls</p>
                                            </div>
                                            <div class="filters-listing card">
                                                <a data-toggle="collapse" href="#statusOptionTab" aria-expanded="false" aria-controls="statusOptionTab">WO Status Options<i class="icn-down-arrow"></i></a>
                                                <div class="filters-category in" id="statusOptionTab">
                                                    <div class="form-group selectType">
                                                        <div class="select">
                                                           
                                                            {!! Form::select('zone', (object) array_merge( [''=>'Select'], (array) $zone), '',array('class' => 'wo-type-filter form-control','form'=>"type")); !!}
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group selectType">
                                                        <div class="select">
                                                            <select name="type" form="type" class="wo-sub-type-filter form-control">
                                                                <option value="">All sub types</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <a data-toggle="collapse" href="#woType" aria-expanded="false" aria-controls="woType">WO Type<i class="icn-down-arrow"></i></a>
                                                <div class="filters-category in" id="woType">
                                                    <div class="form-group selectType">
                                                        <div class="select">
                                                            <select name="type" form="type" class="wo-type-filter form-control">
                                                                <option value="">All Types</option>
                                                                <option value='ASC'>ASC</option>
                                                                <option value='Field Repair'>Field Repair</option>
                                                                <option value='Installation'>Installation</option>
                                                                <option value='MORE OTT Installation'>MORE OTT Installation</option>
                                                                <option value='MORE OTT Recovery'>MORE OTT Recovery</option>
                                                                <option value='MORE OTT Repair'>MORE OTT Repair</option>
                                                                <option value='MPEG4 Migration'>MPEG4 Migration</option>
                                                                <option value='Other Services'>Other Services</option>
                                                                <option value='Project'>Project</option>
                                                                <option value='Relocation'>Relocation</option>
                                                                <option value='Upgrade'>Upgrade</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group selectType">
                                                        <div class="select">
                                                            <select name="type" form="type" class="wo-sub-type-filter form-control">
                                                                <option value="">All sub types</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <a data-toggle="collapse" href="#wo-priority" aria-expanded="false" aria-controls="woType">WO Priority<i class="icn-down-arrow"></i></a>
                                                <div class="filters-category in" id="wo-priority">
                                                    <div class="form-group selectType">
                                                        <div class="select">
                                                            <select name="type" form="type" class="wo-priority-filter form-control">
                                                                <option value="">All</option>
                                                                <option value='1-ASAP'>1-ASAP</option>
                                                                <option value='2-High'>2-High</option>
                                                                <option value='3-Medium'>3-Medium</option>
                                                                <option value='4-Low'>4-Low</option>
                                                                <option value='5-Escalation'>5-Escalation</option>
                                                                <option value='6-CSSO'>6-CSSO</option>
                                                                <option value='4-Interim Escltn'>4-Interim Escltn</option>
                                                            </select>
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
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div id="piechart" class="bigPieChart">
                                                    <div id="div-chartw1"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div id="piechart2" class="bigPieChart">
                                                    <div id="div-chartw2"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="calls-listing">
                                <div class="row">


                                    <div class="col-md-10 pad-left">
                                        <div class="calls-listing-table card">
                                            <div class="search-box">
                                                <div class="export-btn">
                                                    <div class="form-grou btn-group">

                                                        <a class="btn export-call-log-data" href="javascript:void(0)" style="" data-exportdataurl="">Export</a>
                                                    </div>
                                                </div>

                                                <div class="form-group search-field">
                                                    <input class="form-control" type="text" placeholder="Subscriber ID or WO number or Contact No." name="sub_wo_number_filter" id="sub_wo_number_filter" value="" />
                                                    <i class="icn-search"></i>
                                                </div>

                                                <div class="input-group date"><input type="text" id="datepickerFilter1" class="form-control" name="StartDate" value="23-09-2020" data-date-container="#datepicker" data-provide="#datepicker"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                                                <div class="input-group date"><input type="text" id="datepickerFilter2" class="form-control" name="EndDate" value="23-09-2020" data-date-container="#datepicker1" data-provide="#datepicker1"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                                                <div id="datepicker">
                                                </div>
                                                <div id="datepicker1">
                                                </div>
                                                <a id="reset-link-filter" class="reset-link" href="javascript:void(0)">Reset All</a>

                                                <a class="adv-search-link collapsed" data-toggle="collapse" href="#advSearch" aria-expanded="false" aria-controls="advSearch">Advanced Search<i class="icn-down-arrow"></i></a>
                                                <div id="advSearch" class="advance-search collapse">



                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <select class="form-control user-regions-filter" name="tupe" form="type">
                                                                <option value="">Regions</option>
                                                                <option value='10'>East</option>
                                                                <option value='14'>North</option>
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <select class="form-control user-circle-filter" name="tupe" form="type">
                                                                <option value="">Circles</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group selectType col-md-3">
                                                        <div class="select">
                                                            <select class="form-control user-asm-filter" name="tupe" form="type">
                                                                <option value="">Area Service Managers</option>
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <div class="form-group selectType col-md-3">
                                                        <div class="select">
                                                            <select class="form-control user-asi-filter" name="tupe" form="type">
                                                                <option value="">Area Service Incharge</option>
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <div class="form-group selectType col-md-2">
                                                        <div class="select">
                                                            <select class="form-control user-isp-provider" name="tupe" form="type">
                                                                <option value="">ISP Provider </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <a id="reset-advance-filter" class="reset-link" href="javascript:void(0)">Reset Advance Search</a>
                                                </div>
                                            </div>

                                            <div class="calls-table">
                                                <div class="grid-view">
                                                    <div id="w4" class="list-view">
                                                        <div class="empty">no data</div>
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
@endsection
