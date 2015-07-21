import attribution from './modules/attribution';
import enhancements from './modules/enhancements';

var subscription;

// On page load callback.
var onPageLoad = function () {
  attribution();
  enhancements.compose();

  subscription.unsubscribe();
};

// Subscribe to `page.load` topic.
subscription = postal.subscribe({
  channel: 'page',
  topic: 'load',
  callback: onPageLoad
});

window.jQuery = $;
window.$ = $;

// Export modules to public object.
export { postal, jQuery };
