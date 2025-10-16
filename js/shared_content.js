jQuery( document ).ready(function() {
    jQuery("#shared_content").submit(function (e) {
        e.preventDefault();

        jQuery('.invalid').each(function () {
            jQuery(this).html('');
        });

        var params = {};

        params['action'] = "shared_content";

        if(jQuery("#name").val()) {
            params['name'] = jQuery("#name").val();
        }

        if(jQuery("#email").val()) {
            params['email'] = jQuery("#email").val();
        }

        if(jQuery("#friend-name").val()) {
            params['friend-name'] = jQuery("#friend-name").val();
        }

        if(jQuery("#friend-email").val()) {
            params['friend-email'] = jQuery("#friend-email").val();
        }

        if(jQuery("#subject").val()) {
            params['subject'] = jQuery("#subject").val();
        }

        if(jQuery("#message").val()) {
            params['message'] = jQuery("#message").val();
        }

        params['content_link'] = window.location.href;

        jQuery.ajax({
            type: "POST",
            url: ajax_vars.ajaxurl,
            data: params,
            success: function (result) {
                jQuery("#shared_content_response").html("<script>" + result+"</script>");
            }
        });

    })
// Enviar email
});