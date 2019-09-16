/**
 * scrolltop.js
 * A simple function to allow a link to scroll to the top of the screen.
 *
 * Date: 2019-09-16
 */
import $ from 'jquery';

$('.scroll-to-top').each(function () {

  //var timeOut;
  var btn = $(this);

  var btnCss = {
    position: 'fixed',
    bottom: '15px',
    right: '20px',
    width: '50px',
    height: '50px',
    cursor: 'pointer',
    display: 'none',
    border: '1px solid #CCC'
  };
  var iconCss = {
    textAlign: 'center',
    display: 'block',
    fontSize: '20px',
    paddingTop: '15px'
  };
  btn.css(btnCss);
  btn.find('i').css(iconCss);


  $(window).scroll(function(){
    if ($(this).scrollTop() > 300) {
      btn.fadeIn('600');
    }else{
      btn.fadeOut('700');
    }
  });


  $(document).ready(function() {
    btn.on('click', function(){
      $('html, body').animate({scrollTop: 0}, 'slow');
    });
  });


});




// var timeOut;
// function scrollToTop() {
//   if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
//     window.scrollBy(0, -50);
//     timeOut = setTimeout(scrollToTop, 10);
//   } else {
//     clearTimeout(timeOut);
//   }
// }
//
//
// export default scrollToTop;