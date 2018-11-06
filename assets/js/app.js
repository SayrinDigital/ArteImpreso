/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

$(document).ready(function(){

  $('#main-body').imagesLoaded( function() {
    $('.loader').fadeOut(300, function() { $(this).remove(); });
    $('#main-body').addClass('visible');
    console.log('is it working?');
  });

});
