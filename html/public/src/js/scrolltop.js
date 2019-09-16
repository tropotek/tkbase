/**
 * scrolltop.js
 * A simple function to allow a link to scroll to the top of the screen.
 *
 * Date: 2019-09-16
 */
var timeOut;
export default function scrollToTop() {
  if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
    window.scrollBy(0, -50);
    timeOut = setTimeout(scrollToTop, 10);
  } else {
    clearTimeout(timeOut);
  }
}


