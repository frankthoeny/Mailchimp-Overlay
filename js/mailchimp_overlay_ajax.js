(function (Drupal, Cookies) {

  'use strict';

  Drupal.behaviors.mailchimpOverlayAjax = {
    attach: function (context, settings) {
      
      //if the cookie has_enews is not set, then show the drupal.dialog window
      if (!Cookies.get("has_enews")) {

        //Dialog Box. See (http://api.jqueryui.com/dialog/) for all available options.
        let ajaxSettings = {
          url: settings.mailchimp_overlay.path,
          progress: { type: 'throbber' },
          dialogType: 'modal',
          dialog: {
            width: settings.mailchimp_overlay.dialog_width,
            dialogClass: "mailchimp-overlay-dialog",
          },
          httpMethod: 'GET',
        };

        //execute the AJAX Dialog Box.
        Drupal.ajax(ajaxSettings).execute(); 
        
        //then set the cookie, so next time the drupal.dialog won't be displaying again. (name, value, days)
        Cookies.set('has_enews', '1');
      }
    }
  };

})(Drupal, Cookies);

