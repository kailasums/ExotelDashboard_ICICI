$(document).ready(function() {
    function showDatatable(element) {
        const url = createUrlParam('call-record-data', targetSummaryElementArray);
        $('#' + element).DataTable({
            serverSide: false,
            processing: false,
            ajax: url,
            cache: false,
            bDestroy: true
        })
    }


    const targetElementArray = ['zone', 'region', 'branch', 'user', 'call_direction'];
    const targetSummaryElementArray = ['zone_summary', 'region_summary', 'branch_summary', 'call_direction_summary', 'user_summary', 'StartDate', 'EndDate'];

    if (window.location.pathname === '/pie-chart') {
        google.load('visualization', '1', { packages: ['corechart'] })
        google.setOnLoadCallback(selectAjaxOption)
        setTimeout(function() {
            selectAjaxOption('pie-chart', 'call_direction', 'incoming', '');

        }, 1000);
        showDatatable("example");
        setStartDate();
    }

    function setStartDate() {
        const EndDate = $("#datepickerFilter2").val();
        var d = new Date(EndDate);
        var StartDate = formatDate(new Date(d.setDate(d.getDate() - 10)));
        $('#datepickerFilter1').val(StartDate);
        $('#datepickerFilter1').prop('max', EndDate);
        $('#datepickerFilter1').prop('min', StartDate);
    }

    function formatDate(newDate) {
        var month = ('0' + (newDate.getMonth() + 1)).slice(-2);
        var day = ('0' + (newDate.getDate() + 1)).slice(-2);
        var year = newDate.getFullYear();
        return year + '-' + month + '-' + day;
    }

    $("#datepickerFilter2").on('change', function() {
        setStartDate();
    });
    $("select[name='zone']").change(function() {
        selectAjaxOption('pie-chart', 'zone', $(this).val(), targetElementArray);
    })

    $("select[name='region']").change(function() {
        selectAjaxOption('pie-chart', 'region', $(this).val(), targetElementArray);
    })

    $("select[name='branch']").change(function() {
        selectAjaxOption('pie-chart', 'branch', $(this).val(), targetElementArray);
    })

    $("select[name='call_direction']").change(function() {
        selectAjaxOption('pie-chart', 'call_direction', $(this).val(), targetElementArray);
    })

    $("select[name='user']").change(function() {
        selectAjaxOption('pie-chart', 'user', $(this).val(), targetElementArray)
    });

    $("select[name='zone_summary']").change(function() {
        selectAjaxOption('drop-down', 'zone_summary', $(this).val(), targetSummaryElementArray, true)
    });

    $("select[name='region_summary']").change(function() {
        selectAjaxOption('drop-down', 'region_summary', $(this).val(), targetSummaryElementArray, true)
    });

    $("select[name='branch_summary']").change(function() {
        selectAjaxOption('drop-down', 'branch_summary', $(this).val(), targetSummaryElementArray, true)
    });

    $("select[name='user_summary']").change(function() {
        selectAjaxOption('drop-down', 'user_summary', $(this).val(), targetSummaryElementArray, true)
    });

    $("select[name='call_direction_summary']").change(function() {
        selectAjaxOption('drop-down', 'call_direction_summary', $(this).val(), targetSummaryElementArray, true)
    });

    function ajaxGetCall(url) {
        var data = $.ajax({
            url: url,
            method: 'GET',
            async: false,
            success: function(data) {
                return data;
            }
        });

        return data.responseText;
    }

    function selectAjaxOption(url, element, value, targetElement, flag = false) {
        if (url) {
            url = createUrlParam(url, targetElement);
            $.ajax({
                url: url,
                method: 'GET',
                async: false,
                success: function(data) {
                    if (targetElement) {
                        for (let i = 0; i < targetElement.length; i++) {
                            if (element !== targetElement[i]) {
                                $("select[name='" + targetElement[i] + "'").html('')
                                $("select[name='" + targetElement[i] + "'").html('<option value="" selected="selected">Select</option><optgroup label="items">' + createOptions(data[targetElement[i]], data.selectOption ? data.selectOption[targetElement[i]] : '', targetElement[i]) + '</optgroup>');
                            }
                        }
                    }
                    if (!flag) {
                        draw_chart(data.callRecords);
                    } else {
                        showDatatable('example');
                    }
                }
            })
        }
    }

    function createOptions(data, selectOption, key) {
        let optionString = '';
        if (data && data.length === 0) {
            optionString = '<option value="' + 0 + '">' + 'No Data Found' + '</option>'
            return optionString
        }
        for (const property in data) {
            let string = "";
            if (property == selectOption) {
                string += '<option value="' + property + '" selected=selected>' + data[property] + '</option>';
            } else {
                string += '<option value="' + property + '">' + data[property] + '</option>';
            }
            optionString += string;
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
        var urlString = url + "?";
        for (var i = 0; i < elementArray.length; i++) {
            if (i == 0) {
                if (['StartDate', 'EndDate'].includes(elementArray[i])) {
                    urlString += "&" + elementArray[i] + "=" + $("input[name='" + elementArray[i] + "']").val();
                } else {
                    urlString += elementArray[i] + "=" + $("select[name = '" + elementArray[i] + "']").val();
                }
            } else {
                if (['StartDate', 'EndDate'].includes(elementArray[i])) {
                    urlString += "&" + elementArray[i] + "=" + $("input[name='" + elementArray[i] + "']").val();
                } else {
                    urlString += "&" + elementArray[i] + "=" + $("select[name = '" + elementArray[i] + "']").val();
                }
            }
        }
        return urlString;
    }
})