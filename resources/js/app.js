import './bootstrap';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'select2/dist/css/select2.min.css';
import Select2 from 'select2'; // Import the Select2 module itself

// Explicitly attach Select2 to jQuery's prototype if it's not already there.
// This ensures that `$(...).select2()` is available for global jQuery.
if (jQuery && !jQuery.fn.select2) {
  Select2(jQuery);
}
