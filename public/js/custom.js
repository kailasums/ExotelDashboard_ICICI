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
                    ordering: false
                })
            }
        })
    }

    $("#reset-link-filter").on("click",function(){
        
    });

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
    const targetElementArray = ['zone', 'region', 'branch', 'user', 'call_direction']
    const targetSummaryElementArray = ['zone_summary', 'region_summary', 'branch_summary', 'call_direction_summary', 'user_summary', 'StartDate', 'EndDate']
    const targetdetailedElementArray = ['call_status', 'zone', 'region', 'branch', 'call_direction', 'user', 'StartDate', 'EndDate']

    if (window.location.pathname === '/dashboard') {
        google.load('visualization', '1', { packages: ['corechart'] })
        google.setOnLoadCallback(selectAjaxOption)
        $('#example').DataTable({ordering:false});
        $('#example1').DataTable();
        setTimeout(function() {
            selectAjaxOption('dashboard', 'call_direction', 'incoming', targetElementArray)
            showDatatable('example', 'call-record-data', true)
        }, 1000)

        //userDetailTable('user-call-detail', '', user_id);
        setTimeout(function() {
            // var user_id = $("input[name='user-detail']").val();
            selectAjaxOption('user-call-detail', 'call_direction', 'incoming', targetdetailedElementArray, true, false)
        }, 1000)
        setStartDate();
    }
    $('#datepickerFilter1').datepicker({ dateFormat: 'yy-mm-dd' });
    $('#datepickerFilter2').datepicker({ dateFormat: 'yy-mm-dd' });

    function setStartDate() {
        const EndDate = $('#datepickerFilter2').val()
        var d = new Date(EndDate)
        var StartDate = EndDate//formatDate(new Date(d.setDate(d.getDate() - 10)))
        $('#datepickerFilter1').val(StartDate)
        $('#datepickerFilter1').prop('max', EndDate)
        $('#datepickerFilter1').prop('min', StartDate)
    }

    function checkDateDiff(startDate, endDate) {
        const date1 = new Date(startDate);
        const date2 = new Date(endDate);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }

    function formatDate(newDate) {
        var month = ('0' + (newDate.getMonth() + 1)).slice(-2)
        var day = ('0' + (newDate.getDate() + 1)).slice(-2)
        var year = newDate.getFullYear()
        return year + '-' + month + '-' + day
    }

    $('#datepickerFilter2').on('change', function() {
        setStartDate()
        showDatatable('example', 'call-record-data', true);
        selectAjaxOption('user-call-detail', 'endDate', $(this).val(), targetdetailedElementArray, true, false)
    })

    $('#datepickerFilter1').on('change', function() {
        const days = checkDateDiff($(this).val(), $('#datepickerFilter2').val());
        if (days > 10) {
            $(".date_diff").text("Date Difference is greater then 10 days");
            $(".date_diff").addClass("error text-danger");
            setStartDate();
        } else {
            $("#errorDate").text("");
            showDatatable('example', 'call-record-data', true);
            selectAjaxOption('user-call-detail', 'startDate', $(this).val(), targetdetailedElementArray, true, false)
        }
    })

    $("select[name='zone']").change(function() {
        selectAjaxOption('dashboard', 'zone', $(this).val(), targetElementArray)
        $("select[name='zone_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'zone_summary', $(this).val(), targetSummaryElementArray, true)
        selectAjaxOption('user-call-detail', 'zone', $(this).val(), targetdetailedElementArray, true, false)

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
        selectAjaxOption('user-call-detail', 'region', $(this).val(), targetdetailedElementArray, true,false)

    })

    $("select[name='branch']").change(function() {
        selectAjaxOption('dashboard', 'branch', $(this).val(), targetElementArray)
        $("select[name='branch_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'branch_summary', $(this).val(), targetSummaryElementArray, true)
        selectAjaxOption('user-call-detail', 'branch', $(this).val(), targetdetailedElementArray, true, false)

    })

    $("select[name='call_direction']").change(function() {
        selectAjaxOption('dashboard', 'call_direction', $(this).val(), targetElementArray)
        $("select[name='call_direction_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'call_direction_summary', $(this).val(), targetSummaryElementArray, true)
        selectAjaxOption('user-call-detail', 'call_direction', $(this).val(), targetdetailedElementArray, true, false)

    })

    $("select[name='user']").change(function() {
        selectAjaxOption('dashboard', 'user', $(this).val(), targetElementArray)
        $("select[name='user_summary']").val($(this).val())
        selectAjaxOption('drop-down', 'user_summary', $(this).val(), targetSummaryElementArray, true)
        selectAjaxOption('user-call-detail', 'user', $(this).val(), targetdetailedElementArray, true, false)

    })

    $("input[name='call_status']").on("click", function() {
        selectAjaxOption('user-call-detail', 'call_direction', $(this).val(), targetdetailedElementArray, true, false)
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
                        for (let i = 0; i < targetElement.length; i++) {
                            if (element !== targetElement[i]) {
                                $("select[name='" + targetElement[i] + "'").html('')
                                $("select[name='" + targetElement[i] + "'").html('<option value="" selected="selected">' + targetElement[i].toUpperCase() + '</option><optgroup label="items">' + createOptions(data[targetElement[i]], data.selectOption ? data.selectOption[targetElement[i]] : '', targetElement[i]) + '</optgroup>')
                            }
                        }
                    }
                    if (!flag) {
                        draw_chart(data.callRecords)
                        calculatePercentage(data.callRecords)
                        $("#totalCalls").text(data.totalCalls);
                        $("#totalDurationCalls").text(data.totalDurationCalls);
                        $("#avgCalls").text(data.avgCalls);
                    } else {

                        if (targetElement.indexOf('call_status') > -1) {
                            detailDataTable(data);
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
                htmlElement += "<li><p style='right:100px; font-size: 18px;color: black;/* font-weight: bold; */font-weight: 1000;'>" + entry[0] + ' : <span style="font-weight: normal;">'+percentage+'</span></p></li>'
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
                title: 'Percentage of Call Record Status'
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
