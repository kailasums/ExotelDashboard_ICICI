$(document).ready(function() {
    
    function showDatatable(element, urlParam, flag) {
        
        $.ajax({
            url: flag ? createUrlParam(urlParam, targetSummaryElementArray) : urlParam,
            success: function(data, textStatus, jqXHR) {
                var table_data = JSON.parse(data)
                var table = $('#' + element).DataTable({
                    data: flag ? table_data.callData : table_data,
                    serverSide: false,
                    //processing: false,
                    cache: false,
                    bFilter: false,
                    bInfo: false,
                    bDestroy: true,
                    ordering: true,
                    "oLanguage": {
                        "sEmptyTable": "No data available"
                    },
                })
            }
        })
    }


    function detailDataTable(data) {
        $('#example1').DataTable({
            data: data,
            serverSide: false,
            processing: false,
            cache: false,
            bDestroy: true,
            bFilter: false,
            bInfo: false,
            //ordering: false
        })
    }

    function selectUserDataAjaxOption(url, element, value, targetElement) {
        url = createUrlParam(url, targetElement)
        $('#example1').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            cache: false,
            bDestroy: true,
            bFilter: false,
            bInfo: false,
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            ajax: {
                url: url,
                type: "GET"
            },
            columns: [
                { data: "agent_name" },
                { data: "agent_phone_number" },
                { data: "cust_number" },
                { data: "date_time" },
                { data: "call_direction" },
                { data: "call_status" },
                { data: "call_sid" },
                { data: "call_duration" },
                { data: "dial_call_duration" },
                { data: "link" }
            ]
        });
    }
    const targetElementArray = ['zone', 'region', 'branch', 'user', 'call_direction']
    const targetSummaryElementArray = ['zone_summary', 'region_summary', 'branch_summary', 'call_direction_summary', 'user_summary', 'StartDate', 'EndDate']
    const targetdetailedElementArray = ['call_status', 'zone', 'region', 'branch', 'call_direction', 'user', 'StartDate', 'EndDate']

    if (window.location.pathname === '/dashboard') {
        // $("#div-chartw2").hide();
        // $("#no-data-pie").hide();
        google.load('visualization', '1', { packages: ['corechart'] })
        google.setOnLoadCallback(selectAjaxOption)
        $('#example').DataTable({"oLanguage": {
            "sEmptyTable": "No data available"
        }});
        
        // $('#example1').DataTable({});
        // selectUserDataAjaxOption('user-call-detail', , 'call_direction', 'incoming', targetElement)
        setTimeout(function() {
            selectAjaxOption('dashboard', 'call_direction', 'incoming', targetElementArray)
            showDatatable('example', 'call-record-data', true)
        }, 1000)

        setTimeout(function() {
            // var user_id = $("input[name='user-detail']").val();
            selectUserDataAjaxOption('user-call-detail', 'call_direction', 'incoming', targetdetailedElementArray)
        }, 1000)
        setStartDate();
    }
    d = new Date();
    d.setMonth(d.getMonth() - 3); 
    $('#datepickerFilter1').datepicker({ dateFormat: 'yy-mm-dd' , minDate: d,  maxDate: new Date()});
    $('#datepickerFilter2').datepicker({ dateFormat: 'yy-mm-dd' , minDate: d,  maxDate: new Date()});

    function setStartDate() {
        const EndDate = formatDate(new Date()); //$('#datepickerFilter2').val()        
        var StartDate = EndDate//formatDate(new Date(d.setDate(d.getDate() - 10)))

        $('#datepickerFilter1').val(StartDate)
        $('#datepickerFilter2').val(EndDate)
        $('#datepickerFilter1').prop('max', EndDate)
        $('#datepickerFilter1').prop('min', StartDate)
    }

    $("#reset-link-filter").on("click",function(){
        setStartDate();
        $('#applydatefilter').trigger("click")
    })

    function checkDateDiff(startDate, endDate) {
        const date1 = new Date(startDate);
        const date2 = new Date(endDate);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }

    function formatDate(newDate) {
        var month = ('0' + (newDate.getMonth() + 1)).slice(-2)
        var day = ('0' + (newDate.getDate() )).slice(-2)
        var year = newDate.getFullYear()
        return year + '-' + month + '-' + day
    }

    // $('#apply-date-filter').on('change', function() {
    //     setStartDate()
    //     showDatatable('example', 'call-record-data', true);
    //     selectAjaxOption('user-call-detail', 'endDate', $(this).val(), targetdetailedElementArray, true, false)
    // })

    $('#applydatefilter').on('click', function() {
        const days = checkDateDiff($("#datepickerFilter1").val(), $('#datepickerFilter2').val());
        
        var startDate =$("#datepickerFilter1").val();
        var endDate = $('#datepickerFilter2').val();
        if ((Date.parse(endDate) < Date.parse(startDate))) {
            $(".date_diff").text("End date should be greater than Start date");
            $(".date_diff").addClass("error text-danger");
        }else if (days > 9) {    
            $(".date_diff").text("Date Difference is greater then 10 days");
            $(".date_diff").addClass("error text-danger");
            // setStartDate();
        } else {
                $(".date_diff").text("");
                showDatatable('example', 'call-record-data', true);
                selectUserDataAjaxOption('user-call-detail', 'startDate', $("#datepickerFilter1").val(), targetdetailedElementArray)
            }
        
        
        
    })

    $("select[name='zone']").change(function() {
        selectAjaxOption('dashboard', 'zone', $(this).val(), targetElementArray)
        $("select[name='zone_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'zone_summary', $(this).val(), targetSummaryElementArray, true)
        showDatatable('example', 'call-record-data', true);
        selectUserDataAjaxOption('user-call-detail', 'zone', $(this).val(), targetdetailedElementArray)

        //setTimeout(function() {
        //var user_id = $("input[name='user-detail']").val()
        //userDetailTable('user-call-detail', '', user_id)
        //}, 1000)
    })

    // $("select[name='zone_summary']").change(function() {
    //     alert("11")
    //     $("select[name='zone']").val($(this).val()); 
    //     // selectAjaxOption('pie-chart', 'zone', $(this).val(), targetElementArray)
    //     // $("select[name='zone_summary']").val($(this).val())
    //     // selectAjaxOption('drop-down', 'zone_summary', $(this).val(), targetSummaryElementArray, true)
    //     // selectAjaxOption('user-call-detail', 'zone', $(this).val(), targetdetailedElementArray, true, false)

    //     //setTimeout(function() {
    //     //var user_id = $("input[name='user-detail']").val()
    //     //userDetailTable('user-call-detail', '', user_id)
    //     //}, 1000)
    // })
    $("select[name='region']").change(function() {
        selectAjaxOption('dashboard', 'region', $(this).val(), targetElementArray)
        $("select[name='region_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'region_summary', $(this).val(), targetSummaryElementArray, true)
        showDatatable('example', 'call-record-data', true);
        selectUserDataAjaxOption('user-call-detail', 'region', $(this).val(), targetdetailedElementArray)

    })

    $("select[name='branch']").change(function() {
        selectAjaxOption('dashboard', 'branch', $(this).val(), targetElementArray)
        $("select[name='branch_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'branch_summary', $(this).val(), targetSummaryElementArray, true)
        selectUserDataAjaxOption('user-call-detail', 'branch', $(this).val(), targetdetailedElementArray)

    })

    $("select[name='call_direction']").change(function() {
        selectAjaxOption('dashboard', 'call_direction', $(this).val(), targetElementArray)
        $("select[name='call_direction_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'call_direction_summary', $(this).val(), targetSummaryElementArray, true)
        showDatatable('example', 'call-record-data', true);
        selectUserDataAjaxOption('user-call-detail', 'call_direction', $(this).val(), targetdetailedElementArray)

    })

    $("select[name='user']").change(function() {
        selectAjaxOption('dashboard', 'user', $(this).val(), targetElementArray)
        $("select[name='user_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'user_summary', $(this).val(), targetSummaryElementArray, true)
        showDatatable('example', 'call-record-data', true);
        selectUserDataAjaxOption('user-call-detail', 'user', $(this).val(), targetdetailedElementArray)

    })

    $("input[name='call_status']").on("click", function() {
        selectUserDataAjaxOption('user-call-detail', 'call_direction', $(this).val(), targetdetailedElementArray)
    })

    function selectAjaxOption(url, element, value, targetElement, flag = false, userDetailCall = true) {
        if (url) {
            
            url = createUrlParam(url, targetElement)
            $.ajax({
                url: url,
                method: 'GET',
                async: false,
                success: function(data) {
                    if (targetElement && userDetailCall) {
                        var showName = {
                            zone : "Zone",
                            region : "Region",
                            user : "PB",
                            branch : "Branch",
                            call_direction : "Call Direction"
                        }
                        
                        for (let i = 0; i < targetElement.length; i++) {
                            if (element !== targetElement[i]) {
                                // console.log(data[targetElement[i]])
                                $("select[name='" + targetElement[i] + "'").html('')
                                $("select[name='" + targetElement[i] + "'").html('<option value="" selected="selected">' + showName[targetElement[i]].toUpperCase() + '</option><optgroup label="items">' + createOptions(data[targetElement[i]], data.selectOption ? data.selectOption[targetElement[i]] : '', targetElement[i]) + '</optgroup>')
                            }
                        }
                    }
                    if (!flag) {
                        
                        // if(data.callRecords && Object.keys(JSON.parse(data.callRecords)).length  > 1  ){
                            draw_chart(data.callRecords)
                            calculatePercentage(data.callRecords)
                            $("#totalCalls").text(data.totalCalls);
                            $("#totalDurationCalls").text(data.totalDurationCalls);
                            $("#avgCalls").text(data.avgCalls);
                            
                            if(Object.keys(JSON.parse(data.callRecords)).length  <= 1){
                                $("#pie_chart").find("text").html("No Data Available")
                            }
                            
                            // $("#no-data-pie").hide();
                            // $("#pie-chart-data").show();
                        // }else{
                        //     $("#no-data-pie").show();
                        //     $("#pie-chart-data").hide();
                        // }
                        
                    } else {
                        
                        if (targetElement.indexOf('call_status') > -1) {
                            // detailDataTable(data);
                        } else {
                            
                            showDatatable('example', 'call-record-data', true)
                        }

                    }
                }
            })
        }
    }

    function calculatePercentage(callRecords) {
        const callData = JSON.parse(callRecords)
        quartersum = {}
        totalSum = 0
        callData.map(function(entry, index) {
            if (index != 0) {
                totalSum += entry[1]
            }
        })
        var htmlElement = "<ul>"
        callData.map(function(entry, index) {
            if (index !== 0) {
                const percentage = entry[1]; //(entry[1] / totalSum) * 100
                // htmlElement += "<li><p style='right:100px;'>" + entry[0] + ":" + percentage.toFixed(2).toString() + "</p></li>"
                //const percentage = entry[1]; //(entry[1] / totalSum) * 100
                htmlElement += "<li><p style='font-size: 18px;color: black;/* font-weight: bold; */font-weight: 1000;'>" + entry[0] + ' : <span style="font-weight: normal;">'+percentage+'</span></p></li>'
            }
        })
        htmlElement += "</ul>"
        
        $("#totalrecords").html(htmlElement)
    }

    function createOptions(data, selectOption, key) {
        let optionString = ''
        if (data && data.length === 0) {
            optionString = '<option value="' + 0 + '">' + 'No Data Found' + '</option>'
            return optionString
        }
        for (const property in data) {
            // alert(data[property]);
            let string = ''
            if (property == selectOption) {
                string += '<option value="' + property + '" selected=selected>' + data[property] + '</option>'
            } else {
                string += '<option value="' + property + '">' + data[property] + '</option>'
            }
            optionString += string
        }
        return optionString
    }

    function draw_chart(chart_values) {
        if (chart_values) {
            var data = google.visualization.arrayToDataTable(JSON.parse(chart_values))
            var options = {
                //title: 'Percentage of Call Record Status'
                width: '100%',
                height: '100%',
                legend: {position: 'none'}
            }
            var chart = new google.visualization.PieChart(document.getElementById('pie_chart'))
            chart.draw(data, options)
        }
    }

    function createUrlParam(url, elementArray) {
        var urlString = url + '?'
        for (var i = 0; i < elementArray.length; i++) {
            console.log(elementArray[i]);
            if (i == 0) {
                if (['StartDate', 'EndDate'].includes(elementArray[i])) {
                    urlString += '&' + elementArray[i] + '=' + $("input[name='" + elementArray[i] + "']").val()
                } else if (['call_status'].includes(elementArray[i])) {
                    var params = []
                    $("input:checkbox[name=call_status]:checked").each(function() {
                        params.push($(this).val())
                    });
                    urlString += '&call_status=' + params.join(",")
                } else {
                    urlString += elementArray[i] + '=' + $("select[name = '" + elementArray[i] + "']").val()
                }
            } else {
                if (['StartDate', 'EndDate'].includes(elementArray[i])) {
                    urlString += '&' + elementArray[i] + '=' + $("input[name='" + elementArray[i] + "']").val()
                } else {
                    urlString += '&' + elementArray[i] + '=' + $("select[name = '" + elementArray[i] + "']").val()
                }
            }
        }
        return urlString
    }
})
