/**
 * HAWK module for Craft CMS
 *
 * EmailSupport Widget JS
 *
 * @author    HAWK
 * @copyright Copyright (c) 2019 HAWK
 * @link      http://hawk.ca
 * @package   HAWKModule
 * @since     1.0.0
 */
jQuery(document).ready(function($) {
    var form = $("#communicator__email_support__widget form");

    if (window.Garnish.NiceText) {
        new Garnish.NiceText('#communicator__email_support__widget .nicetext', null);
    }

    form.on('submit', function(e) {
        e.preventDefault();

        // Get the details
        var data = {
            message: $('message').val(),
            browserInfo: {
                "Device Pixel Ratio": window.devicePixelRatio || 1,
                "Viewport Width": screen.availWidth+"px",
                "Viewport Height": screen.availHeight+"px",
                "Window Width": window.outerWidth+"px",
                "Window Height": window.outerHeight+"px"
            }
        };

        Craft.postActionRequest(
            'hawk/email-support/send-email',
            data,
            function(response, status) {
                if (status === 'success') {
                    if (response.status) {
                        Craft.cp.displayNotice("Message sent successfully, you will hear from us within 48 business hours");
                    } else if (response.errors) {
                        Craft.cp.displayNotice("An error occurred sending email, please contact your account rep.");
                    }
                }
            })
    });
});