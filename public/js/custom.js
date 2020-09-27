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
    selectAjaxOption('pie-chart', 'region', $(this).val(), 'branch')
  })

  $("select[name='branch']").change(function () {
    selectAjaxOption('pie-chart', 'branch', $(this).val(), 'user')
  })

  $("select[name='call_direction']").change(function () {
    selectAjaxOption('pie-chart', 'call_direction', $(this).val(), '')
  })

  google.load('visualization', '1', { packages: ['corechart'] })
  google.setOnLoadCallback(selectAjaxOption)

  function selectAjaxOption (url, element, value, targetElement) {
    if (url) {
      const callDirection = $('#call_direction').val()
      if (element !== 'call_direction') {
        url += '?' + element + '=' + value + '&call_direction=' + callDirection
      } else {
        url += '?' + element + '=' + value
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
    showDatatable("api/call-recording/list","example");

    
});
