import dismissable from './enhancements/dismissable';

var enhancements = {

  /**
   * The enabled view enhancements.
   * @type {Array}
   */
  enhancements: [
    dismissable
  ],

  /**
   * Attaches all enhancements.
   */
  compose() {
    var enhancements = _.filter(this.enhancements, (enhancement) => enhancement.applies());

    _.each(enhancements, (enhancement) => enhancement.apply());
  }

};

export default enhancements;
