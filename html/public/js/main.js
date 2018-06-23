/**
 * main.js
 */


jQuery(function ($) {


  // Growl like alert messages that fade out.
  $('.tk-alert-container .alert').each(function() {
    let a = $(this);
    setTimeout(function () {
      a.fadeOut(1000);
    }, 5000);
  });



});


