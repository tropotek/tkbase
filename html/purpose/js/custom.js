/**
 * Created by mifsudm on 19/07/16.
 *
 * Add Css:
 *   <link href="js/form/fileinput/jquery.fileinput.css" rel="stylesheet" />
 *
 * Add JS:
 *   <script src="js/form/fileinput/jquery.fileinput.js"></script>
 *   <script src="js/form/dualListBox/dual-list-box.js"></script>
 *   <script src="/vendor/ttek/tk-table/js/jquery.tableOrderBy.js"></script>
 *   <script src="js/custom.js"></script>
 *
 *
 */

$(document).ready(function() {

  // Style file inputs nicer
  if ($.fn.fileinput != undefined) {
    $('input[type=file].fileinput').fileinput({dataUrl: config.dataUrl});
  }

  // Blur tabs after click to look better
  $('a[role=tab]').click(function() { $(this).blur(); });

  // Manage date fields
  if ($.fn.datepicker != undefined) {
    $('input.date').each(function (i, el) {
      el = $(el);
      var tpl = $('<div class="input-group col-md-2">' +
        '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
        '</div>');
      var parent = el.parent();

      el.after(tpl);
      el.detach();
      tpl.prepend(el);
    }).datepicker({
      dateFormat: 'dd/mm/yy'
    });
  }

  // Dual select list box renderer
  if ($.fn.DualListBox != undefined) {
    $('select.tk-dualSelect').DualListBox();
  }

});
