@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                      <ul>
                            <li>
                            {!! Form::select('zone', $zones, ''); !!}
                            </li>
                            <li>
                            {!! Form::select('region', $regions, ''); !!}
                            </li>
                            <li>
                            {!! Form::select('branch', $branchs, ''); !!}
                            </li>
                            <li>
                                {!! Form::select('user', $pb, ''); !!}
                            </li>
                            <li><select name="call_direction" id="call_direction"><option value="incoming">Incoming</option><option value="outgoing">Outgoing</option></select></li>
                      </ul>
                    </div>
                    <div class="panel-body" align="center">
                        <div id="pie_chart" style="width:750px; height:450px;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var analytics = <?php echo $callRecords; ?>

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Percentage of Call Record Status'
            };
            var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            chart.draw(data, options);
        }
    </script>
@endsection
