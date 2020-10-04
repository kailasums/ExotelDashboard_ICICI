
$(function () {
  $('body').bootstrapMaterialDesign()
})

$(function () {
  $('#statusOption').slimScroll({
    height: '130px',
    alwaysVisible: true
  })
})

$(function () {
  jQuery('body').on('keyup change input', '.form-group.animated .form-control, .form-group.overlayed .form-control', function (event) {
    if ($(this).val() != '') {
      $(this).parent('.form-group').addClass('active')
    } else {
      $(this).parent('.form-group').removeClass('active')
    }
  })
  jQuery('body').on('focusin', '.form-group.animated .form-control, .form-group.-overlayed .form-control', function (event) {
    $(this).parent('.form-group').addClass('focus')
  })
  jQuery('body').on('focusout', '.form-group.animated .form-control, .form-group.overlayed .form-control', function (event) {
    $(this).parent('.form-group').removeClass('focus')
  })
})

$(document).ready(function (e) {
  $('.btn').click(function (e) {
	  // Remove any old one
	  $('.ripple').remove()

	  // Setup
	  var posX = $(this).offset().left
		  var posY = $(this).offset().top
		  var buttonWidth = $(this).width()
		  var buttonHeight = $(this).height()

	  // Add the element
	  // $(this).prepend("<span class='ripple'></span>");

	 // Make it round!
	  if (buttonWidth >= buttonHeight) {
      buttonHeight = buttonWidth
	  } else {
      buttonWidth = buttonHeight
	  }

	  // Get the center of the element
	  var x = e.pageX - posX - buttonWidth / 2
	  var y = e.pageY - posY - buttonHeight / 2

	  // Add the ripples CSS and start the animation
	  $('.ripple').css({
      width: buttonWidth,
      height: buttonHeight,
      top: y + 'px',
      left: x + 'px'
	  }).addClass('rippleEffect')
  })
})
