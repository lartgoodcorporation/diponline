$(".button-collapse").sideNav();

$('#get_form').click(function () {
    $('#logForm').show()
    $('#info_block').hide()
    $('#login_block').show()
})
$('#btn_up').click(function () {
    $('#login_block').hide()
    $('#reg_block').show()
})
$('#btn_in').click(function () {
    $('#login_block').show()
    $('#reg_block').hide()
})

function myFunction() {
var x = document.getElementById("sign_in_password");

    if (x.type === "password") {
        x.type = "text";
        $('#show').show()
        $('#not_show').hide()

    } else {
        x.type = "password";
        $('#show').hide()
        $('#not_show').show()
        
    }
}
$(document).ready(function() {
    var remember = $.cookie('remember');
    if (remember == 'true') 
    {
        var email = $.cookie('sign_in_email');
        var password = $.cookie('sign_in_password');
        // autofill the fields
        $('#sign_in_email').val(email);
        $('#sign_in_password').val(password);
    }
    $("#btn_login").submit(function() {
        if ($('#remember').is(':checked')) {
            var email = $('#sign_in_email').val();
            var password = $('#sign_in_password').val();

            // set cookies to expire in 14 days
            $.cookie('sign_in_email', email, { expires: 14 });
            $.cookie('sign_in_password', password, { expires: 14 });
            $.cookie('remember', true, { expires: 14 });                
        }
        else
        {
            // reset cookies
            $.cookie('sign_in_email', null);
            $.cookie('sign_in_password', null);
            $.cookie('remember', null);
        }
    });
});

$('#office').click(function () {
    $('#profil_form').hide()
    $('#edit_form').hide()
    $('#edit_project').hide()
    $('#news_form').hide()
    $('#tabs_office').show()
})

$('#profile').click(function () {
    $('#rof_off').show()
    $('#profil_form').show()
    $('#tabs_office').hide()
    $('#edit_form').hide()
    $('#edit_project').hide()
    $('#news_form').hide()
})

$('#news').click(function () {
    $('#news_form').show()
    // $('#tabs_office').hide()
    // $('#profil_form').hide()
    $('#edit_form').hide()
    $('#rof_off').hide()
    $('#edit_project').hide()
})

$(".button-collapse").sideNav();

$('#getEditInput').click(function () {
    $('#adminDataEdit').show()
    $('#adminData').hide()
    $('#progress_check').hide()
})
$('#upp').click(function () {
    $('#adminDataEdit').hide()
    $('#adminData').show()
    $('#progress_check').show()
})

$(':checkbox').change(function() {
    if (this.checked) {
        var lastChecked = $(this).prevAll(":checked:first");
        if (this.value == lastChecked.val()) {
            alert("previous checked box has same value");
        }
    }
});

function wpb_set_post_viewss($postID) {

    $currentWeekNumber = date('W');

    $count_key = 'wpb_post_views_counts';
    $count = get_post_meta($postID, $count_key, true);
    $weekView = get_post_meta($postID, 'weekView', true);

    if($weekView != $currentWeekNumber){
        update_post_meta($postID, 'weekView', $currentWeekNumber);
        update_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}