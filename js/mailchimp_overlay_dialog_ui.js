(function ($, Drupal) {

    'use strict';
    
    //override the normal behavior of the dialog buttons relocating to the button action bar.
    Drupal.behaviors.dialog.prepareDialogButtons = function prepareDialogButtons($dialog) {
      return [];
    }
})(jQuery, Drupal);