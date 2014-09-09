jQuery(document).ready(function( $ ){

    
    $('#wp_social_popup_button_fb').change(function() {
        if($(this).is(":checked")) {
            $( ".wrap_inputs_fb" ).fadeIn( "slow", function() {
                $(this).css("display","block");
            });
        }else{
            $( ".wrap_inputs_fb" ).fadeOut( 300, function() {
                //$(this).css("display","none");
            });
        }
    });

    $('#wp_social_popup_button_tw').change(function() {
        if($(this).is(":checked")) {
            $( ".wrap_inputs_tw" ).fadeIn( "slow", function() {
                $(this).css("display","block");
            });
        }else{
            $( ".wrap_inputs_tw" ).fadeOut( 300, function() {
                //$(this).css("display","none");
            });
        }
    });

    $('#wp_social_popup_button_go').change(function() {
        if($(this).is(":checked")) {
            $( ".wrap_inputs_go" ).fadeIn( "slow", function() {
                $(this).css("display","block");
            });
        }else{
            $( ".wrap_inputs_go" ).fadeOut( 300, function() {
                //$(this).css("display","none");
            });
        }
    });



// validate init options
if($('#wp_social_popup_button_fb').is(":checked")) {
    $( ".wrap_inputs_fb" ).fadeIn( "slow", function() {
        $(this).css("display","block");
    });
}else{
    $( ".wrap_inputs_fb" ).fadeOut( 300, function() {
        //$(this).css("display","none");
    });
}
if($('#wp_social_popup_button_tw').is(":checked")) {
    $( ".wrap_inputs_tw" ).fadeIn( "slow", function() {
        $(this).css("display","block");
    });
}else{
    $( ".wrap_inputs_tw" ).fadeOut( 300, function() {
        //$(this).css("display","none");
    });
}
if($('#wp_social_popup_button_go').is(":checked")) {
    $( ".wrap_inputs_go" ).fadeIn( "slow", function() {
        $(this).css("display","block");
    });
}else{
    $( ".wrap_inputs_go" ).fadeOut( 300, function() {
        //$(this).css("display","none");
    });
}

});

 
function clearCookie(name, domain, path){
    var domain = domain || document.domain;
    var path = path || "/";
    document.cookie = name + "=; expires=" + +new Date + ";  path=/";
    alert('Cookies deleted!');
    return false;
}