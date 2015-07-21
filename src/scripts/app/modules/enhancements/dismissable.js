var dismissable = {

  /**
   * Check if the page needs color pickers.
   * @return {Boolean}
   */
  applies() {
    if (this.dismissables == null) {
      this.dismissables = $('[data-dismissable]');
    }

    return this.dismissables.length > 0;
  },

  /**
   * Apply the dismissables.
   * @param  {Object} dismissables
   */
  apply(dismissables) {
    if (dismissables == null && this.dismissables == null) {
      this.dismissables = $('[data-dismissable]');
    }

    dismissables = dismissables ? dismissables : this.dismissables;
    (dismissables ? dismissables : this.dismissables).each(this.hook);
  },

  /**
   * Hooks the dismiss event.
   */
  hook() {
    var $this = $(this);
    var method = $this.data('dismissable');

    switch (method) {
      case 'click':
        $this.css('cursor', 'pointer');
        $this.append('<span class="dismiss">&times;</span>');
        $this.on('click', () => $this.remove());

        break;
    }
  }

};

export default dismissable;
