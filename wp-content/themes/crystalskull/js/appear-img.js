  jQuery(document).ready(function() {
    jQuery('.newsb-thumbnail').addClass('no-display');
    jQuery('.newsb-thumbnail').one('inview', function() {
                jQuery(this).addClass('appear');
    });
  });