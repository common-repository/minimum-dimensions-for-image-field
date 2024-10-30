jQuery(document).ready(function($) {

  $('.add-image').on('click', function(event) {
    event.preventDefault();
    var field = $(this).parents('.field'),
        acf = false,
        inputvalue = false,
        fieldKey = false;

    // Input is an ACF...
    if (field) {
      acf = true;
      fieldKey = $(field).data('field_name');
      inputvalue = $(field).data('field_key');
      //inputvalue = $('.acf-image-value').attr('name');
      //inputvalue = inputvalue.replace('fields[', '');
      //inputvalue = inputvalue.replace(']', '');
    }
    $.ajax({
      url:  ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'update_user_mod_field',
        acf : acf,
        fieldKey : fieldKey,
        field_type : inputvalue
      },
      success:function(data){
        console.log('success');
      }
    });
  });
  $('#set-post-thumbnail').on('click', function(e) {
    e.preventDefault();
    var fieldKey = 'featured_image';
    var acf = false;
    var field_type = '';
    $.ajax({
      url:  ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'update_user_mod_field',
        acf : acf,
        fieldKey : fieldKey,
        field_type : field_type
      },
      success:function(data){
        console.log('success');
      }
    });
  });

});
