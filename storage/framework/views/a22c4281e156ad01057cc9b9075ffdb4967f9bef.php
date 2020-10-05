<?php $__env->startSection('content'); ?>
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <ul>
                        <li>
                            <?php echo Form::select('zone', (object) array_merge( [''=>'Select'], (array) $zone), '');; ?>

                        </li>
                        <li>
                            <?php echo Form::select('region', (object) array_merge( [''=>'Select'], (array) $region), '');; ?>

                        </li>
                        <li>
                            <?php echo Form::select('branch', (object) array_merge( [''=>'Select'], (array) $branch), '');; ?>

                        </li>
                        <li>
                            <?php echo Form::select('user', (object) array_merge( [''=>'Select'], (array) $user), '');; ?>

                        </li>
                        <li>
                            <?php echo Form::select('call_direction', (object) array_merge( [''=>'Select'], (array) $call_direction), 'incoming');; ?>

                        </li>
                    </ul>
                </div>
                <div>
                    <div class="panel-body d-inline-block align-items-top" align="center">
                        <div id="pie_chart" style="width:500px; height:450px;">
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <ul>
                    <li>
                        <div class="input-group date"><input type="date" id="datepickerFilter1" class="form-control" name="StartDate" value="" data-date-container="#datepicker" data-provide="#datepicker"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                        <div class="input-group date"><input type="date" id="datepickerFilter2" class="form-control" name="EndDate" value="<?php echo e(date('Y-m-d')); ?>" data-date-container="#datepicker1" data-provide="#datepicker1"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>
                        <div id="datepicker">
                    </li>
                    <li>
                        <?php echo Form::select('zone_summary', (object) array_merge( [''=>'Select'], (array) $zone), '');; ?>

                    </li>
                    <li>
                        <?php echo Form::select('region_summary', (object) array_merge( [''=>'Select'], (array) $region), '');; ?>

                    </li>
                    <li>
                        <?php echo Form::select('branch_summary', (object) array_merge( [''=>'Select'], (array) $branch), '');; ?>

                    </li>
                    <li>
                        <?php echo Form::select('user_summary', (object) array_merge( [''=>'Select'], (array) $user), '');; ?>

                    </li>
                    <li>
                        <?php echo Form::select('call_direction_summary', (object) array_merge( [''=>'Select'], (array) $call_direction), 'incoming');; ?>

                    </li>
                </ul>
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>phone_number</th>
                            <th>name</th>
                            <th>total_call</th>
                            <th>total_duration</th>
                            <th>avg_duration</th>
                            <th>incompleted</th>
                            <th>busy</th>
                            <th>failed</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>id</th>
                            <th>phone_number</th>
                            <th>name</th>
                            <th>total_call</th>
                            <th>total_duration</th>
                            <th>avg_duration</th>
                            <th>incompleted</th>
                            <th>busy</th>
                            <th>failed</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ISPCalling/resources/views/callRecord/google-pie-chart.blade.php ENDPATH**/ ?>