
CRM.$(function($) {

  sync = {

    $blt: null,
    $first: null,

    init: function() {
      if (CRM.vars.emailprofile.has_blt) {
        $('.email-' + CRM.vars.emailprofile.bltID + '-section').remove();
      }
      else {
        this.$blt = $('input[name="email-' + CRM.vars.emailprofile.bltID + '"]');
        if (!this.$blt.val()) {
          this.$first = $('#' + CRM.vars.emailprofile.first).change($.proxy(this.change, this));
          this.$first.change();
        }
      }
    },
    change: function() {
      this.$blt.val(this.$first.val());
    }
  }

  sync.init();

});