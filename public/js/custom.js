$(document).ready(function () {
  //   function showDatatable (url, element) {
  //     $('#' + element).DataTable({
  //       draw: 0,
  //       serverSide: true,
  //       ajax: url
  //     })
  //   }
  //   showDatatable("api/call-recording/list", "example");
  const targetElementArray = ['zone', 'region', 'branch', 'user']

  $("select[name='zone']").change(function () {
    selectAjaxOption('pie-chart', 'zone', $(this).val(), targetElementArray)
  })

  $("select[name='region']").change(function () {
    selectAjaxOption('pie-chart', 'region', $(this).val(), targetElementArray)
  })

  $("select[name='branch']").change(function () {
    selectAjaxOption('pie-chart', 'branch', $(this).val(), targetElementArray)
  })

  $("select[name='call_direction']").change(function () {
    selectAjaxOption('pie-chart', 'call_direction', $(this).val(), '')
  })

  $("select[name='user']").change(function () {
    selectAjaxOption('pie-chart', 'user', $(this).val(), '')
  })

  google.load('visualization', '1', { packages: ['corechart'] })
  google.setOnLoadCallback(selectAjaxOption)

  function selectAjaxOption (url, element, value, targetElement) {
    if (url) {
      const callDirection = $('#call_direction').val()
      if (element !== 'call_direction') {
        url += '?' + element + '=' + value + '&call_direction=' + callDirection
      } else {
        const branch = $("select[name='branch']").val()
        const region = $("select[name='region']").val()
        const zone = $("select[name='zone']").val()
        const user = $("select[name='user']").val()
        url += '?' + element + '=' + value + '&group3=' + zone + '&group2=' + region + '&group1=' + branch + '&user=' + user
      }
      $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
          if (targetElement) {
            for (let i = 0; i <= targetElement.length; i++) {
              if (element !== targetElement[i]) {
                $("select[name='" + targetElement[i] + "'").html('')
                $("select[name='" + targetElement[i] + "'").html(createOptions(data[targetElement[i] + 's']))
              }
            }
          }
          draw_chart(data.callRecords)
        }
      })
    }
  }

  function createOptions (data) {
    let optionString = ''
    if (data && data.length === 0) {
      optionString = '<option value="' + 0 + '">' + 'No Data Found' + '</option>'
      return optionString
    }
    for (const property in data) {
      const string = '<option value="' + property + '">' + data[property] + '</option>'
      optionString += string
    }
    return optionString
  }

  function draw_chart (chart_values) {
    var data = google.visualization.arrayToDataTable(JSON.parse(chart_values))
    var options = {
      title: 'Percentage of Call Record Status'
    }
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart'))
    chart.draw(data, options)
  }
})
