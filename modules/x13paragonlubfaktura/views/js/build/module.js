!function(e){var r={};function t(n){if(r[n])return r[n].exports;var o=r[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=r,t.d=function(e,r,n){t.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:n})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,r){if(1&r&&(e=t(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(t.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var o in e)t.d(n,o,function(r){return e[r]}.bind(null,o));return n},t.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(r,"a",r),r},t.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},t.p="/views/js/build/",t(t.s="q1KO")}({q1KO:function(e,r,t){"use strict";function n(e,r){if(!(e instanceof r))throw new TypeError("Cannot call a class as a function")}function o(e,r){for(var t=0;t<r.length;t++){var n=r[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}t.r(r);var i=window.$,a=function(){function e(r){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return n(this,e),this.init(r,t)}var r,t,a;return r=e,(t=[{key:"init",value:function(e){var r=this,t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];e.on("click",".ps-togglable-row",(function(e){e.preventDefault();var n=i(e.currentTarget);i.post({url:n.data("toggle-url")}).then((function(o){if(o.success)return showSuccessMessage(o.message),r._toggleButtonDisplay(n),void(t&&t(e));showErrorMessage(o.message)})).catch((function(e){var r=e.responseJSON;showErrorMessage(r.message)}))}))}},{key:"_toggleButtonDisplay",value:function(e){var r=e.hasClass("grid-toggler-icon-valid"),t=r?"grid-toggler-icon-not-valid":"grid-toggler-icon-valid",n=r?"grid-toggler-icon-valid":"grid-toggler-icon-not-valid",o=r?"clear":"check";e.removeClass(n),e.addClass(t),e.text(o)}}])&&o(r.prototype,t),a&&o(r,a),e}(),u=window.$;u((function(){var e=u(".column-x13paragonlubfaktura");new a(e,(function(e){var r=u(e.currentTarget).parents("tr");r.hasClass("preview-open")&&r.find(".preview-toggle").trigger("click").trigger("click")}))}))}});