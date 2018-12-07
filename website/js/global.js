/*Stuff all your JavaScript in this file.*/

//Handles messages fading in
$(".msg").hide().fadeIn();

//For all items that require confirmation, handle confirmation
$(".req_confirm button").click(function(e){

  e.preventDefault();
  var r=confirm($(this).parent().children(".confirm_warning").html());
  if (r==true) {
    $(this).parent().submit();
    return true;
  }
  return false;

});

//Put this class on forms so that they are only submitted once.
$(".submit_once").submit(function(){

  $(this).submit(function(){
      return false;
  });

});

//This function sets up JS code for adding in users to presentations via select drop-downs
//Takes in a string that represents the default dropdown HTML.
function set_user_pres_dropdown(default_dropdown)
{
  //When the value of a select is changed, change the name of parent select to whatever is selected
  $(document).on('change',".user_select",function() {
    var name = $(this).children("option:selected").val();
    $(this).attr('name',name.toString());
  });

  $("#add_presenter").on('click',function(){
    $("#presenters").append(default_dropdown);
    $("#presenters").append("<button class='remove' type='button'>Remove</button><br>");
  });

  $(document).on('click','.remove',function(){
    $(this).prev(".user_select").remove();
    $(this).remove();
  });

}

//This function will streamline AJAX by setting all necessary stuff. Just call this.
function set_ajax_form(params) {

  //Grab the submit button of this form
  submit_button = params.ajax_form.find("#submit_button");

  submit_button.on('click', function() {

    submit_area = params.ajax_form.find("#submit_area");
    
    params.ajax_form.submit();
    form = params.ajax_form.serialize();

    //Save old button html
    button_html = submit_area.html();

    //Replace the submit button with a loader
    submit_area.html("<img src='media/ajax-loader.gif'>Submitting...").hide().fadeIn();

    //Do the ajax call
    $.ajax({    
        url: params.url,
        type: 'post',
        data: form,
        success: function(data) {
            //A status code of 0 indicates that the form was successfully sent
            if(data.status_code == "0") {
              submit_area.html("<span class='msg' style='color:green'><i>" + data.msg + "</i></span>").hide().fadeIn();
            }
            //A status code of 1 indicates that the form was not successfully sent
            else if(data.status_code == "1") {
              submit_area.html(button_html);
              $("#msg").html("<span class='msg' style='color:red'><i>" + data.msg + "</i></span>").hide().fadeIn();
            }

            return false;
        },
        error: function(jqXHR, textStatus, errorThrown) {
          submit_area.html(button_html);
          $("#msg").html("INTERNAL SERVER ERROR: " + textStatus).hide().fadeIn();
          return false;
        }

    });

  });
}
