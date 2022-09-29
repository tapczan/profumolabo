window["createit_accordion"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = ConfirmModal;

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * ConfirmModal component
 *
 * @param {String} id
 * @param {String} confirmTitle
 * @param {String} confirmMessage
 * @param {String} closeButtonLabel
 * @param {String} confirmButtonLabel
 * @param {String} confirmButtonClass
 * @param {Array} customButtons
 * @param {Boolean} closable
 * @param {Function} confirmCallback
 * @param {Function} cancelCallback
 *
 */

function ConfirmModal(params, confirmCallback, cancelCallback) {
  var _this = this;

  // Construct the modal
  var id = params.id,
      closable = params.closable;

  this.modal = Modal(params);

  // jQuery modal object
  this.$modal = $(this.modal.container);

  this.show = function () {
    _this.$modal.modal();
  };

  this.modal.confirmButton.addEventListener('click', confirmCallback);

  this.$modal.modal({
    backdrop: closable ? true : 'static',
    keyboard: closable !== undefined ? closable : true,
    closable: closable !== undefined ? closable : true,
    show: false
  });

  this.$modal.on('hidden.bs.modal', function () {
    document.querySelector('#' + id).remove();
    if (cancelCallback) {
      cancelCallback();
    }
  });

  document.body.appendChild(this.modal.container);
}

/**
 * Modal component to improve lisibility by constructing the modal outside the main function
 *
 * @param {Object} params
 *
 */
function Modal(_ref) {
  var _modal$footer;

  var _ref$id = _ref.id,
      id = _ref$id === undefined ? 'confirm-modal' : _ref$id,
      confirmTitle = _ref.confirmTitle,
      _ref$confirmMessage = _ref.confirmMessage,
      confirmMessage = _ref$confirmMessage === undefined ? '' : _ref$confirmMessage,
      _ref$closeButtonLabel = _ref.closeButtonLabel,
      closeButtonLabel = _ref$closeButtonLabel === undefined ? 'Close' : _ref$closeButtonLabel,
      _ref$confirmButtonLab = _ref.confirmButtonLabel,
      confirmButtonLabel = _ref$confirmButtonLab === undefined ? 'Accept' : _ref$confirmButtonLab,
      _ref$confirmButtonCla = _ref.confirmButtonClass,
      confirmButtonClass = _ref$confirmButtonCla === undefined ? 'btn-primary' : _ref$confirmButtonCla,
      _ref$customButtons = _ref.customButtons,
      customButtons = _ref$customButtons === undefined ? [] : _ref$customButtons;

  var modal = {};

  // Main modal element
  modal.container = document.createElement('div');
  modal.container.classList.add('modal', 'fade');
  modal.container.id = id;

  // Modal dialog element
  modal.dialog = document.createElement('div');
  modal.dialog.classList.add('modal-dialog');

  // Modal content element
  modal.content = document.createElement('div');
  modal.content.classList.add('modal-content');

  // Modal header element
  modal.header = document.createElement('div');
  modal.header.classList.add('modal-header');

  // Modal title element
  if (confirmTitle) {
    modal.title = document.createElement('h4');
    modal.title.classList.add('modal-title');
    modal.title.innerHTML = confirmTitle;
  }

  // Modal close button icon
  modal.closeIcon = document.createElement('button');
  modal.closeIcon.classList.add('close');
  modal.closeIcon.setAttribute('type', 'button');
  modal.closeIcon.dataset.dismiss = 'modal';
  modal.closeIcon.innerHTML = '×';

  // Modal body element
  modal.body = document.createElement('div');
  modal.body.classList.add('modal-body', 'text-left', 'font-weight-normal');

  // Modal message element
  modal.message = document.createElement('p');
  modal.message.classList.add('confirm-message');
  modal.message.innerHTML = confirmMessage;

  // Modal footer element
  modal.footer = document.createElement('div');
  modal.footer.classList.add('modal-footer');

  // Modal close button element
  modal.closeButton = document.createElement('button');
  modal.closeButton.setAttribute('type', 'button');
  modal.closeButton.classList.add('btn', 'btn-outline-secondary', 'btn-lg');
  modal.closeButton.dataset.dismiss = 'modal';
  modal.closeButton.innerHTML = closeButtonLabel;

  // Modal confirm button element
  modal.confirmButton = document.createElement('button');
  modal.confirmButton.setAttribute('type', 'button');
  modal.confirmButton.classList.add('btn', confirmButtonClass, 'btn-lg', 'btn-confirm-submit');
  modal.confirmButton.dataset.dismiss = 'modal';
  modal.confirmButton.innerHTML = confirmButtonLabel;

  // Constructing the modal
  if (confirmTitle) {
    modal.header.append(modal.title, modal.closeIcon);
  } else {
    modal.header.appendChild(modal.closeIcon);
  }

  modal.body.appendChild(modal.message);
  (_modal$footer = modal.footer).append.apply(_modal$footer, [modal.closeButton].concat(_toConsumableArray(customButtons), [modal.confirmButton]));
  modal.content.append(modal.header, modal.body, modal.footer);
  modal.dialog.appendChild(modal.content);
  modal.container.appendChild(modal.dialog);

  return modal;
}

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Copyright since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * NOTICE OF LICENSE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * This source file is subject to the Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * that is bundled with this package in the file LICENSE.md.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * It is also available through the world-wide-web at this URL:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * https://opensource.org/licenses/OSL-3.0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * If you did not receive a copy of the license and are unable to
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * obtain it through the world-wide-web, please send an email
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * to license@prestashop.com so we can send you a copy immediately.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * DISCLAIMER
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * versions in the future. If you wish to customize PrestaShop for your
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * needs please refer to https://devdocs.prestashop.com/ for more information.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @author    PrestaShop SA and Contributors <contact@prestashop.com>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @copyright Since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */

var _modal = __webpack_require__(0);

var _modal2 = _interopRequireDefault(_modal);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var _window = window,
    $ = _window.$;

/**
 * Class SubmitRowActionExtension handles submitting of row action
 */

var SubmitRowActionExtension = function () {
  function SubmitRowActionExtension() {
    _classCallCheck(this, SubmitRowActionExtension);
  }

  _createClass(SubmitRowActionExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      var _this = this;

      grid.getContainer().on('click', '.js-submit-row-action', function (event) {
        event.preventDefault();

        var $button = $(event.currentTarget);
        var confirmMessage = $button.data('confirmMessage');
        var confirmTitle = $button.data('title');

        var method = $button.data('method');

        if (confirmTitle) {
          _this.showConfirmModal($button, grid, confirmMessage, confirmTitle, method);
        } else {
          if (confirmMessage.length && !window.confirm(confirmMessage)) {
            return;
          }

          _this.postForm($button, method);
        }
      });
    }
  }, {
    key: 'postForm',
    value: function postForm($button, method) {
      var isGetOrPostMethod = ['GET', 'POST'].includes(method);

      var $form = $('<form>', {
        action: $button.data('url'),
        method: isGetOrPostMethod ? method : 'POST'
      }).appendTo('body');

      if (!isGetOrPostMethod) {
        $form.append($('<input>', {
          type: '_hidden',
          name: '_method',
          value: method
        }));
      }

      $form.submit();
    }

    /**
     * @param {jQuery} $submitBtn
     * @param {Grid} grid
     * @param {string} confirmMessage
     * @param {string} confirmTitle
     * @param {string} method
     */

  }, {
    key: 'showConfirmModal',
    value: function showConfirmModal($submitBtn, grid, confirmMessage, confirmTitle, method) {
      var _this2 = this;

      var confirmButtonLabel = $submitBtn.data('confirmButtonLabel');
      var closeButtonLabel = $submitBtn.data('closeButtonLabel');
      var confirmButtonClass = $submitBtn.data('confirmButtonClass');

      var modal = new _modal2.default({
        id: grid.getId() + '-grid-confirm-modal',
        confirmTitle: confirmTitle,
        confirmMessage: confirmMessage,
        confirmButtonLabel: confirmButtonLabel,
        closeButtonLabel: closeButtonLabel,
        confirmButtonClass: confirmButtonClass
      }, function () {
        return _this2.postForm($submitBtn, method);
      });

      modal.show();
    }
  }]);

  return SubmitRowActionExtension;
}();

exports.default = SubmitRowActionExtension;

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Class BulkActionSelectCheckboxExtension
 */

var BulkActionCheckboxExtension = function () {
  function BulkActionCheckboxExtension() {
    _classCallCheck(this, BulkActionCheckboxExtension);
  }

  _createClass(BulkActionCheckboxExtension, [{
    key: 'extend',

    /**
     * Extend grid with bulk action checkboxes handling functionality
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      this.handleBulkActionCheckboxSelect(grid);
      this.handleBulkActionSelectAllCheckbox(grid);
    }

    /**
     * Handles "Select all" button in the grid
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'handleBulkActionSelectAllCheckbox',
    value: function handleBulkActionSelectAllCheckbox(grid) {
      var _this = this;

      grid.getContainer().on('change', '.js-bulk-action-select-all', function (e) {
        var $checkbox = $(e.currentTarget);

        var isChecked = $checkbox.is(':checked');

        if (isChecked) {
          _this.enableBulkActionsBtn(grid);
        } else {
          _this.disableBulkActionsBtn(grid);
        }

        grid.getContainer().find('.js-bulk-action-checkbox').prop('checked', isChecked);
      });
    }

    /**
     * Handles each bulk action checkbox select in the grid
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'handleBulkActionCheckboxSelect',
    value: function handleBulkActionCheckboxSelect(grid) {
      var _this2 = this;

      grid.getContainer().on('change', '.js-bulk-action-checkbox', function () {
        var checkedRowsCount = grid.getContainer().find('.js-bulk-action-checkbox:checked').length;

        if (checkedRowsCount > 0) {
          _this2.enableBulkActionsBtn(grid);
        } else {
          _this2.disableBulkActionsBtn(grid);
        }
      });
    }

    /**
     * Enable bulk actions button
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'enableBulkActionsBtn',
    value: function enableBulkActionsBtn(grid) {
      grid.getContainer().find('.js-bulk-actions-btn').prop('disabled', false);
    }

    /**
     * Disable bulk actions button
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'disableBulkActionsBtn',
    value: function disableBulkActionsBtn(grid) {
      grid.getContainer().find('.js-bulk-actions-btn').prop('disabled', true);
    }
  }]);

  return BulkActionCheckboxExtension;
}();

exports.default = BulkActionCheckboxExtension;

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Class ExportToSqlManagerExtension extends grid with exporting query to SQL Manager
 */

var ExportToSqlManagerExtension = function () {
  function ExportToSqlManagerExtension() {
    _classCallCheck(this, ExportToSqlManagerExtension);
  }

  _createClass(ExportToSqlManagerExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      var _this = this;

      grid.getHeaderContainer().on('click', '.js-common_show_query-grid-action', function () {
        return _this.onShowSqlQueryClick(grid);
      });
      grid.getHeaderContainer().on('click', '.js-common_export_sql_manager-grid-action', function () {
        return _this.onExportSqlManagerClick(grid);
      });
    }

    /**
     * Invoked when clicking on the "show sql query" toolbar button
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'onShowSqlQueryClick',
    value: function onShowSqlQueryClick(grid) {
      var $sqlManagerForm = $('#' + grid.getId() + '_common_show_query_modal_form');
      this.fillExportForm($sqlManagerForm, grid);

      var $modal = $('#' + grid.getId() + '_grid_common_show_query_modal');
      $modal.modal('show');

      $modal.on('click', '.btn-sql-submit', function () {
        return $sqlManagerForm.submit();
      });
    }

    /**
     * Invoked when clicking on the "export to the sql query" toolbar button
     *
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'onExportSqlManagerClick',
    value: function onExportSqlManagerClick(grid) {
      var $sqlManagerForm = $('#' + grid.getId() + '_common_show_query_modal_form');

      this.fillExportForm($sqlManagerForm, grid);

      $sqlManagerForm.submit();
    }

    /**
     * Fill export form with SQL and it's name
     *
     * @param {jQuery} $sqlManagerForm
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'fillExportForm',
    value: function fillExportForm($sqlManagerForm, grid) {
      var query = grid.getContainer().find('.js-grid-table').data('query');

      $sqlManagerForm.find('textarea[name="sql"]').val(query);
      $sqlManagerForm.find('input[name="name"]').val(this.getNameFromBreadcrumb());
    }

    /**
     * Get export name from page's breadcrumb
     *
     * @return {String}
     *
     * @private
     */

  }, {
    key: 'getNameFromBreadcrumb',
    value: function getNameFromBreadcrumb() {
      var $breadcrumbs = $('.header-toolbar').find('.breadcrumb-item');
      var name = '';

      $breadcrumbs.each(function (i, item) {
        var $breadcrumb = $(item);

        var breadcrumbTitle = $breadcrumb.find('a').length > 0 ? $breadcrumb.find('a').text() : $breadcrumb.text();

        if (name.length > 0) {
          name = name.concat(' > ');
        }

        name = name.concat(breadcrumbTitle);
      });

      return name;
    }
  }]);

  return ExportToSqlManagerExtension;
}();

exports.default = ExportToSqlManagerExtension;

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Copyright since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * NOTICE OF LICENSE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * This source file is subject to the Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * that is bundled with this package in the file LICENSE.md.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * It is also available through the world-wide-web at this URL:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * https://opensource.org/licenses/OSL-3.0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * If you did not receive a copy of the license and are unable to
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * obtain it through the world-wide-web, please send an email
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * to license@prestashop.com so we can send you a copy immediately.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * DISCLAIMER
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * versions in the future. If you wish to customize PrestaShop for your
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * needs please refer to https://devdocs.prestashop.com/ for more information.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @author    PrestaShop SA and Contributors <contact@prestashop.com>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @copyright Since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */

var _reset_search = __webpack_require__(14);

var _reset_search2 = _interopRequireDefault(_reset_search);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var _window = window,
    $ = _window.$;

/**
 * Class FiltersResetExtension extends grid with filters resetting
 */

var FiltersResetExtension = function () {
  function FiltersResetExtension() {
    _classCallCheck(this, FiltersResetExtension);
  }

  _createClass(FiltersResetExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      grid.getContainer().on('click', '.js-reset-search', function (event) {
        (0, _reset_search2.default)($(event.currentTarget).data('url'), $(event.currentTarget).data('redirect'));
      });
    }
  }]);

  return FiltersResetExtension;
}();

exports.default = FiltersResetExtension;

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Class LinkRowActionExtension handles link row actions
 */

var LinkRowActionExtension = function () {
  function LinkRowActionExtension() {
    _classCallCheck(this, LinkRowActionExtension);
  }

  _createClass(LinkRowActionExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      this.initRowLinks(grid);
      this.initConfirmableActions(grid);
    }

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */

  }, {
    key: 'initConfirmableActions',
    value: function initConfirmableActions(grid) {
      grid.getContainer().on('click', '.js-link-row-action', function (event) {
        var confirmMessage = $(event.currentTarget).data('confirm-message');

        if (confirmMessage.length && !window.confirm(confirmMessage)) {
          event.preventDefault();
        }
      });
    }

    /**
     * Add a click event on rows that matches the first link action (if present)
     *
     * @param {Grid} grid
     */

  }, {
    key: 'initRowLinks',
    value: function initRowLinks(grid) {
      $('tr', grid.getContainer()).each(function initEachRow() {
        var $parentRow = $(this);

        $('.js-link-row-action[data-clickable-row=1]:first', $parentRow).each(function propagateFirstLinkAction() {
          var $rowAction = $(this);
          var $parentCell = $rowAction.closest('td');

          var clickableCells = $('td.clickable', $parentRow).not($parentCell);
          var isDragging = false;
          clickableCells.addClass('cursor-pointer').mousedown(function () {
            $(window).mousemove(function () {
              isDragging = true;
              $(window).unbind('mousemove');
            });
          });

          clickableCells.mouseup(function () {
            var wasDragging = isDragging;
            isDragging = false;
            $(window).unbind('mousemove');

            if (!wasDragging) {
              var confirmMessage = $rowAction.data('confirm-message');

              if (!confirmMessage.length || window.confirm(confirmMessage)) {
                document.location = $rowAction.attr('href');
              }
            }
          });
        });
      });
    }
  }]);

  return LinkRowActionExtension;
}();

exports.default = LinkRowActionExtension;

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

/**
 * Class ReloadListExtension extends grid with "List reload" action
 */
var ReloadListExtension = function () {
  function ReloadListExtension() {
    _classCallCheck(this, ReloadListExtension);
  }

  _createClass(ReloadListExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      grid.getHeaderContainer().on('click', '.js-common_refresh_list-grid-action', function () {
        window.location.reload();
      });
    }
  }]);

  return ReloadListExtension;
}();

exports.default = ReloadListExtension;

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Copyright since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * NOTICE OF LICENSE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * This source file is subject to the Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * that is bundled with this package in the file LICENSE.md.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * It is also available through the world-wide-web at this URL:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * https://opensource.org/licenses/OSL-3.0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * If you did not receive a copy of the license and are unable to
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * obtain it through the world-wide-web, please send an email
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * to license@prestashop.com so we can send you a copy immediately.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * DISCLAIMER
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * versions in the future. If you wish to customize PrestaShop for your
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * needs please refer to https://devdocs.prestashop.com/ for more information.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @author    PrestaShop SA and Contributors <contact@prestashop.com>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @copyright Since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */

var _tableSorting = __webpack_require__(15);

var _tableSorting2 = _interopRequireDefault(_tableSorting);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Class ReloadListExtension extends grid with "List reload" action
 */
var SortingExtension = function () {
  function SortingExtension() {
    _classCallCheck(this, SortingExtension);
  }

  _createClass(SortingExtension, [{
    key: 'extend',

    /**
     * Extend grid
     *
     * @param {Grid} grid
     */
    value: function extend(grid) {
      var $sortableTable = grid.getContainer().find('table.table');

      new _tableSorting2.default($sortableTable).attach();
    }
  }]);

  return SortingExtension;
}();

exports.default = SortingExtension;

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Copyright since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * NOTICE OF LICENSE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * This source file is subject to the Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * that is bundled with this package in the file LICENSE.md.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * It is also available through the world-wide-web at this URL:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * https://opensource.org/licenses/OSL-3.0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * If you did not receive a copy of the license and are unable to
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * obtain it through the world-wide-web, please send an email
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * to license@prestashop.com so we can send you a copy immediately.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * DISCLAIMER
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * versions in the future. If you wish to customize PrestaShop for your
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * needs please refer to https://devdocs.prestashop.com/ for more information.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @author    PrestaShop SA and Contributors <contact@prestashop.com>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @copyright Since 2007 PrestaShop SA and Contributors
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      */

var _modal = __webpack_require__(0);

var _modal2 = _interopRequireDefault(_modal);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var _window = window,
    $ = _window.$;

/**
 * Handles submit of grid actions
 */

var SubmitBulkActionExtension = function () {
  function SubmitBulkActionExtension() {
    var _this = this;

    _classCallCheck(this, SubmitBulkActionExtension);

    return {
      extend: function extend(grid) {
        return _this.extend(grid);
      }
    };
  }

  /**
   * Extend grid with bulk action submitting
   *
   * @param {Grid} grid
   */


  _createClass(SubmitBulkActionExtension, [{
    key: 'extend',
    value: function extend(grid) {
      var _this2 = this;

      grid.getContainer().on('click', '.js-bulk-action-submit-btn', function (event) {
        _this2.submit(event, grid);
      });
    }

    /**
     * Handle bulk action submitting
     *
     * @param {Event} event
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'submit',
    value: function submit(event, grid) {
      var $submitBtn = $(event.currentTarget);
      var confirmMessage = $submitBtn.data('confirm-message');
      var confirmTitle = $submitBtn.data('confirmTitle');

      if (confirmMessage !== undefined && confirmMessage.length > 0) {
        if (confirmTitle !== undefined) {
          this.showConfirmModal($submitBtn, grid, confirmMessage, confirmTitle);
        } else if (window.confirm(confirmMessage)) {
          this.postForm($submitBtn, grid);
        }
      } else {
        this.postForm($submitBtn, grid);
      }
    }

    /**
     * @param {jQuery} $submitBtn
     * @param {Grid} grid
     * @param {string} confirmMessage
     * @param {string} confirmTitle
     */

  }, {
    key: 'showConfirmModal',
    value: function showConfirmModal($submitBtn, grid, confirmMessage, confirmTitle) {
      var _this3 = this;

      var confirmButtonLabel = $submitBtn.data('confirmButtonLabel');
      var closeButtonLabel = $submitBtn.data('closeButtonLabel');
      var confirmButtonClass = $submitBtn.data('confirmButtonClass');

      var modal = new _modal2.default({
        id: grid.getId() + '-grid-confirm-modal',
        confirmTitle: confirmTitle,
        confirmMessage: confirmMessage,
        confirmButtonLabel: confirmButtonLabel,
        closeButtonLabel: closeButtonLabel,
        confirmButtonClass: confirmButtonClass
      }, function () {
        return _this3.postForm($submitBtn, grid);
      });

      modal.show();
    }

    /**
     * @param {jQuery} $submitBtn
     * @param {Grid} grid
     */

  }, {
    key: 'postForm',
    value: function postForm($submitBtn, grid) {
      var $form = $('#' + grid.getId() + '_filter_form');

      $form.attr('action', $submitBtn.data('form-url'));
      $form.attr('method', $submitBtn.data('form-method'));
      $form.submit();
    }
  }]);

  return SubmitBulkActionExtension;
}();

exports.default = SubmitBulkActionExtension;

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Class SubmitGridActionExtension handles grid action submits
 */

var SubmitGridActionExtension = function () {
  function SubmitGridActionExtension() {
    var _this = this;

    _classCallCheck(this, SubmitGridActionExtension);

    return {
      extend: function extend(grid) {
        return _this.extend(grid);
      }
    };
  }

  _createClass(SubmitGridActionExtension, [{
    key: 'extend',
    value: function extend(grid) {
      var _this2 = this;

      grid.getHeaderContainer().on('click', '.js-grid-action-submit-btn', function (event) {
        _this2.handleSubmit(event, grid);
      });
    }

    /**
     * Handle grid action submit.
     * It uses grid form to submit actions.
     *
     * @param {Event} event
     * @param {Grid} grid
     *
     * @private
     */

  }, {
    key: 'handleSubmit',
    value: function handleSubmit(event, grid) {
      var $submitBtn = $(event.currentTarget);
      var confirmMessage = $submitBtn.data('confirm-message');

      if (typeof confirmMessage !== 'undefined' && confirmMessage.length > 0 && !window.confirm(confirmMessage)) {
        return;
      }

      var $form = $('#' + grid.getId() + '_filter_form');

      $form.attr('action', $submitBtn.data('url'));
      $form.attr('method', $submitBtn.data('method'));
      $form.find('input[name="' + grid.getId() + '[_token]"]').val($submitBtn.data('csrf'));
      $form.submit();
    }
  }]);

  return SubmitGridActionExtension;
}();

exports.default = SubmitGridActionExtension;

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Class is responsible for handling Grid events
 */

var Grid = function () {
  /**
   * Grid id
   *
   * @param {string} id
   */
  function Grid(id) {
    _classCallCheck(this, Grid);

    this.id = id;
    this.$container = $('#' + this.id + '_grid');
  }

  /**
   * Get grid id
   *
   * @returns {string}
   */


  _createClass(Grid, [{
    key: 'getId',
    value: function getId() {
      return this.id;
    }

    /**
     * Get grid container
     *
     * @returns {jQuery}
     */

  }, {
    key: 'getContainer',
    value: function getContainer() {
      return this.$container;
    }

    /**
     * Get grid header container
     *
     * @returns {jQuery}
     */

  }, {
    key: 'getHeaderContainer',
    value: function getHeaderContainer() {
      return this.$container.closest('.js-grid-panel').find('.js-grid-header');
    }

    /**
     * Extend grid with external extensions
     *
     * @param {object} extension
     */

  }, {
    key: 'addExtension',
    value: function addExtension(extension) {
      extension.extend(this);
    }
  }]);

  return Grid;
}();

exports.default = Grid;

/***/ }),
/* 11 */,
/* 12 */,
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_grid_grid__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_grid_grid___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_grid_grid__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_grid_extension_reload_list_extension__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_grid_extension_reload_list_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_grid_extension_reload_list_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_grid_extension_export_to_sql_manager_extension__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_grid_extension_export_to_sql_manager_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__components_grid_extension_export_to_sql_manager_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_grid_extension_filters_reset_extension__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_grid_extension_filters_reset_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__components_grid_extension_filters_reset_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_grid_extension_sorting_extension__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_grid_extension_sorting_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4__components_grid_extension_sorting_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__components_grid_extension_link_row_action_extension__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__components_grid_extension_link_row_action_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_5__components_grid_extension_link_row_action_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__components_grid_extension_submit_grid_action_extension__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__components_grid_extension_submit_grid_action_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6__components_grid_extension_submit_grid_action_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__components_grid_extension_submit_bulk_action_extension__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__components_grid_extension_submit_bulk_action_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_7__components_grid_extension_submit_bulk_action_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__components_grid_extension_bulk_action_checkbox_extension__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__components_grid_extension_bulk_action_checkbox_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_8__components_grid_extension_bulk_action_checkbox_extension__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__components_grid_extension_action_row_submit_row_action_extension__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__components_grid_extension_action_row_submit_row_action_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_9__components_grid_extension_action_row_submit_row_action_extension__);
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */












var $ = window.$;

$(function () {
  var quotesGrid = new __WEBPACK_IMPORTED_MODULE_0__components_grid_grid___default.a('createit_accordion');

  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_1__components_grid_extension_reload_list_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_2__components_grid_extension_export_to_sql_manager_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_3__components_grid_extension_filters_reset_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_4__components_grid_extension_sorting_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_5__components_grid_extension_link_row_action_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_6__components_grid_extension_submit_grid_action_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_7__components_grid_extension_submit_bulk_action_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_8__components_grid_extension_bulk_action_checkbox_extension___default.a());
  quotesGrid.addExtension(new __WEBPACK_IMPORTED_MODULE_9__components_grid_extension_action_row_submit_row_action_extension___default.a());
});

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

/**
 * Send a Post Request to reset search Action.
 */

var _window = window,
    $ = _window.$;


var init = function resetSearch(url, redirectUrl) {
  $.post(url).then(function () {
    return window.location.assign(redirectUrl);
  });
};

exports.default = init;

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

var _window = window,
    $ = _window.$;

/**
 * Makes a table sortable by columns.
 * This forces a page reload with more query parameters.
 */

var TableSorting = function () {
  /**
   * @param {jQuery} table
   */
  function TableSorting(table) {
    _classCallCheck(this, TableSorting);

    this.selector = '.ps-sortable-column';
    this.columns = $(table).find(this.selector);
  }

  /**
   * Attaches the listeners
   */


  _createClass(TableSorting, [{
    key: 'attach',
    value: function attach() {
      var _this = this;

      this.columns.on('click', function (e) {
        var $column = $(e.delegateTarget);
        _this.sortByColumn($column, _this.getToggledSortDirection($column));
      });
    }

    /**
     * Sort using a column name
     * @param {string} columnName
     * @param {string} direction "asc" or "desc"
     */

  }, {
    key: 'sortBy',
    value: function sortBy(columnName, direction) {
      var $column = this.columns.is('[data-sort-col-name="' + columnName + '"]');

      if (!$column) {
        throw new Error('Cannot sort by "' + columnName + '": invalid column');
      }

      this.sortByColumn($column, direction);
    }

    /**
     * Sort using a column element
     * @param {jQuery} column
     * @param {string} direction "asc" or "desc"
     * @private
     */

  }, {
    key: 'sortByColumn',
    value: function sortByColumn(column, direction) {
      window.location = this.getUrl(column.data('sortColName'), direction === 'desc' ? 'desc' : 'asc', column.data('sortPrefix'));
    }

    /**
     * Returns the inverted direction to sort according to the column's current one
     * @param {jQuery} column
     * @return {string}
     * @private
     */

  }, {
    key: 'getToggledSortDirection',
    value: function getToggledSortDirection(column) {
      return column.data('sortDirection') === 'asc' ? 'desc' : 'asc';
    }

    /**
     * Returns the url for the sorted table
     * @param {string} colName
     * @param {string} direction
     * @param {string} prefix
     * @return {string}
     * @private
     */

  }, {
    key: 'getUrl',
    value: function getUrl(colName, direction, prefix) {
      var url = new URL(window.location.href);
      var params = url.searchParams;

      if (prefix) {
        params.set(prefix + '[orderBy]', colName);
        params.set(prefix + '[sortOrder]', direction);
      } else {
        params.set('orderBy', colName);
        params.set('sortOrder', direction);
      }

      return url.toString();
    }
  }]);

  return TableSorting;
}();

exports.default = TableSorting;

/***/ })
/******/ ]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZDcxYjg4OWFkMDAyYjVmOWRjODEiLCJ3ZWJwYWNrOi8vLy4vLi4vLi4vLi4vYmFjay1vZmZpY2UvdGhlbWVzL25ldy10aGVtZS9qcy9jb21wb25lbnRzL21vZGFsLmpzIiwid2VicGFjazovLy8uLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9hY3Rpb24vcm93L3N1Ym1pdC1yb3ctYWN0aW9uLWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vYnVsay1hY3Rpb24tY2hlY2tib3gtZXh0ZW5zaW9uLmpzIiwid2VicGFjazovLy8uLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9leHBvcnQtdG8tc3FsLW1hbmFnZXItZXh0ZW5zaW9uLmpzIiwid2VicGFjazovLy8uLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9maWx0ZXJzLXJlc2V0LWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vbGluay1yb3ctYWN0aW9uLWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vcmVsb2FkLWxpc3QtZXh0ZW5zaW9uLmpzIiwid2VicGFjazovLy8uLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9zb3J0aW5nLWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vc3VibWl0LWJ1bGstYWN0aW9uLWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vc3VibWl0LWdyaWQtYWN0aW9uLWV4dGVuc2lvbi5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9ncmlkLmpzIiwid2VicGFjazovLy8uL2NyZWF0ZWl0X2FjY29yZGlvbi9pbmRleC5qcyIsIndlYnBhY2s6Ly8vLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2FwcC91dGlscy9yZXNldF9zZWFyY2guanMiLCJ3ZWJwYWNrOi8vLy4vLi4vLi4vLi4vYmFjay1vZmZpY2UvdGhlbWVzL25ldy10aGVtZS9qcy9hcHAvdXRpbHMvdGFibGUtc29ydGluZy5qcyJdLCJuYW1lcyI6WyJDb25maXJtTW9kYWwiLCJ3aW5kb3ciLCIkIiwicGFyYW1zIiwiY29uZmlybUNhbGxiYWNrIiwiY2FuY2VsQ2FsbGJhY2siLCJpZCIsImNsb3NhYmxlIiwibW9kYWwiLCJNb2RhbCIsIiRtb2RhbCIsImNvbnRhaW5lciIsInNob3ciLCJjb25maXJtQnV0dG9uIiwiYWRkRXZlbnRMaXN0ZW5lciIsImJhY2tkcm9wIiwia2V5Ym9hcmQiLCJ1bmRlZmluZWQiLCJvbiIsImRvY3VtZW50IiwicXVlcnlTZWxlY3RvciIsInJlbW92ZSIsImJvZHkiLCJhcHBlbmRDaGlsZCIsImNvbmZpcm1UaXRsZSIsImNvbmZpcm1NZXNzYWdlIiwiY2xvc2VCdXR0b25MYWJlbCIsImNvbmZpcm1CdXR0b25MYWJlbCIsImNvbmZpcm1CdXR0b25DbGFzcyIsImN1c3RvbUJ1dHRvbnMiLCJjcmVhdGVFbGVtZW50IiwiY2xhc3NMaXN0IiwiYWRkIiwiZGlhbG9nIiwiY29udGVudCIsImhlYWRlciIsInRpdGxlIiwiaW5uZXJIVE1MIiwiY2xvc2VJY29uIiwic2V0QXR0cmlidXRlIiwiZGF0YXNldCIsImRpc21pc3MiLCJtZXNzYWdlIiwiZm9vdGVyIiwiY2xvc2VCdXR0b24iLCJhcHBlbmQiLCJTdWJtaXRSb3dBY3Rpb25FeHRlbnNpb24iLCJncmlkIiwiZ2V0Q29udGFpbmVyIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsIiRidXR0b24iLCJjdXJyZW50VGFyZ2V0IiwiZGF0YSIsIm1ldGhvZCIsInNob3dDb25maXJtTW9kYWwiLCJsZW5ndGgiLCJjb25maXJtIiwicG9zdEZvcm0iLCJpc0dldE9yUG9zdE1ldGhvZCIsImluY2x1ZGVzIiwiJGZvcm0iLCJhY3Rpb24iLCJhcHBlbmRUbyIsInR5cGUiLCJuYW1lIiwidmFsdWUiLCJzdWJtaXQiLCIkc3VibWl0QnRuIiwiZ2V0SWQiLCJCdWxrQWN0aW9uQ2hlY2tib3hFeHRlbnNpb24iLCJoYW5kbGVCdWxrQWN0aW9uQ2hlY2tib3hTZWxlY3QiLCJoYW5kbGVCdWxrQWN0aW9uU2VsZWN0QWxsQ2hlY2tib3giLCJlIiwiJGNoZWNrYm94IiwiaXNDaGVja2VkIiwiaXMiLCJlbmFibGVCdWxrQWN0aW9uc0J0biIsImRpc2FibGVCdWxrQWN0aW9uc0J0biIsImZpbmQiLCJwcm9wIiwiY2hlY2tlZFJvd3NDb3VudCIsIkV4cG9ydFRvU3FsTWFuYWdlckV4dGVuc2lvbiIsImdldEhlYWRlckNvbnRhaW5lciIsIm9uU2hvd1NxbFF1ZXJ5Q2xpY2siLCJvbkV4cG9ydFNxbE1hbmFnZXJDbGljayIsIiRzcWxNYW5hZ2VyRm9ybSIsImZpbGxFeHBvcnRGb3JtIiwicXVlcnkiLCJ2YWwiLCJnZXROYW1lRnJvbUJyZWFkY3J1bWIiLCIkYnJlYWRjcnVtYnMiLCJlYWNoIiwiaSIsIml0ZW0iLCIkYnJlYWRjcnVtYiIsImJyZWFkY3J1bWJUaXRsZSIsInRleHQiLCJjb25jYXQiLCJGaWx0ZXJzUmVzZXRFeHRlbnNpb24iLCJMaW5rUm93QWN0aW9uRXh0ZW5zaW9uIiwiaW5pdFJvd0xpbmtzIiwiaW5pdENvbmZpcm1hYmxlQWN0aW9ucyIsImluaXRFYWNoUm93IiwiJHBhcmVudFJvdyIsInByb3BhZ2F0ZUZpcnN0TGlua0FjdGlvbiIsIiRyb3dBY3Rpb24iLCIkcGFyZW50Q2VsbCIsImNsb3Nlc3QiLCJjbGlja2FibGVDZWxscyIsIm5vdCIsImlzRHJhZ2dpbmciLCJhZGRDbGFzcyIsIm1vdXNlZG93biIsIm1vdXNlbW92ZSIsInVuYmluZCIsIm1vdXNldXAiLCJ3YXNEcmFnZ2luZyIsImxvY2F0aW9uIiwiYXR0ciIsIlJlbG9hZExpc3RFeHRlbnNpb24iLCJyZWxvYWQiLCJTb3J0aW5nRXh0ZW5zaW9uIiwiJHNvcnRhYmxlVGFibGUiLCJUYWJsZVNvcnRpbmciLCJhdHRhY2giLCJTdWJtaXRCdWxrQWN0aW9uRXh0ZW5zaW9uIiwiZXh0ZW5kIiwiU3VibWl0R3JpZEFjdGlvbkV4dGVuc2lvbiIsImhhbmRsZVN1Ym1pdCIsIkdyaWQiLCIkY29udGFpbmVyIiwiZXh0ZW5zaW9uIiwicXVvdGVzR3JpZCIsImFkZEV4dGVuc2lvbiIsIlJlbG9hZExpc3RBY3Rpb25FeHRlbnNpb24iLCJTdWJtaXRHcmlkRXh0ZW5zaW9uIiwiU3VibWl0QnVsa0V4dGVuc2lvbiIsImluaXQiLCJyZXNldFNlYXJjaCIsInVybCIsInJlZGlyZWN0VXJsIiwicG9zdCIsInRoZW4iLCJhc3NpZ24iLCJ0YWJsZSIsInNlbGVjdG9yIiwiY29sdW1ucyIsIiRjb2x1bW4iLCJkZWxlZ2F0ZVRhcmdldCIsInNvcnRCeUNvbHVtbiIsImdldFRvZ2dsZWRTb3J0RGlyZWN0aW9uIiwiY29sdW1uTmFtZSIsImRpcmVjdGlvbiIsIkVycm9yIiwiY29sdW1uIiwiZ2V0VXJsIiwiY29sTmFtZSIsInByZWZpeCIsIlVSTCIsImhyZWYiLCJz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OiJjcmVhdGVpdF9hY2NvcmRpb24uYnVuZGxlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gaWRlbnRpdHkgZnVuY3Rpb24gZm9yIGNhbGxpbmcgaGFybW9ueSBpbXBvcnRzIHdpdGggdGhlIGNvcnJlY3QgY29udGV4dFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5pID0gZnVuY3Rpb24odmFsdWUpIHsgcmV0dXJuIHZhbHVlOyB9O1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHtcbiBcdFx0XHRcdGNvbmZpZ3VyYWJsZTogZmFsc2UsXG4gXHRcdFx0XHRlbnVtZXJhYmxlOiB0cnVlLFxuIFx0XHRcdFx0Z2V0OiBnZXR0ZXJcbiBcdFx0XHR9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAxMyk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gd2VicGFjay9ib290c3RyYXAgZDcxYjg4OWFkMDAyYjVmOWRjODEiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmNvbnN0IHskfSA9IHdpbmRvdztcblxuLyoqXG4gKiBDb25maXJtTW9kYWwgY29tcG9uZW50XG4gKlxuICogQHBhcmFtIHtTdHJpbmd9IGlkXG4gKiBAcGFyYW0ge1N0cmluZ30gY29uZmlybVRpdGxlXG4gKiBAcGFyYW0ge1N0cmluZ30gY29uZmlybU1lc3NhZ2VcbiAqIEBwYXJhbSB7U3RyaW5nfSBjbG9zZUJ1dHRvbkxhYmVsXG4gKiBAcGFyYW0ge1N0cmluZ30gY29uZmlybUJ1dHRvbkxhYmVsXG4gKiBAcGFyYW0ge1N0cmluZ30gY29uZmlybUJ1dHRvbkNsYXNzXG4gKiBAcGFyYW0ge0FycmF5fSBjdXN0b21CdXR0b25zXG4gKiBAcGFyYW0ge0Jvb2xlYW59IGNsb3NhYmxlXG4gKiBAcGFyYW0ge0Z1bmN0aW9ufSBjb25maXJtQ2FsbGJhY2tcbiAqIEBwYXJhbSB7RnVuY3Rpb259IGNhbmNlbENhbGxiYWNrXG4gKlxuICovXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBDb25maXJtTW9kYWwocGFyYW1zLCBjb25maXJtQ2FsbGJhY2ssIGNhbmNlbENhbGxiYWNrKSB7XG4gIC8vIENvbnN0cnVjdCB0aGUgbW9kYWxcbiAgY29uc3Qge2lkLCBjbG9zYWJsZX0gPSBwYXJhbXM7XG4gIHRoaXMubW9kYWwgPSBNb2RhbChwYXJhbXMpO1xuXG4gIC8vIGpRdWVyeSBtb2RhbCBvYmplY3RcbiAgdGhpcy4kbW9kYWwgPSAkKHRoaXMubW9kYWwuY29udGFpbmVyKTtcblxuICB0aGlzLnNob3cgPSAoKSA9PiB7XG4gICAgdGhpcy4kbW9kYWwubW9kYWwoKTtcbiAgfTtcblxuICB0aGlzLm1vZGFsLmNvbmZpcm1CdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBjb25maXJtQ2FsbGJhY2spO1xuXG4gIHRoaXMuJG1vZGFsLm1vZGFsKHtcbiAgICBiYWNrZHJvcDogY2xvc2FibGUgPyB0cnVlIDogJ3N0YXRpYycsXG4gICAga2V5Ym9hcmQ6IGNsb3NhYmxlICE9PSB1bmRlZmluZWQgPyBjbG9zYWJsZSA6IHRydWUsXG4gICAgY2xvc2FibGU6IGNsb3NhYmxlICE9PSB1bmRlZmluZWQgPyBjbG9zYWJsZSA6IHRydWUsXG4gICAgc2hvdzogZmFsc2UsXG4gIH0pO1xuXG4gIHRoaXMuJG1vZGFsLm9uKCdoaWRkZW4uYnMubW9kYWwnLCAoKSA9PiB7XG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcihgIyR7aWR9YCkucmVtb3ZlKCk7XG4gICAgaWYgKGNhbmNlbENhbGxiYWNrKSB7XG4gICAgICBjYW5jZWxDYWxsYmFjaygpO1xuICAgIH1cbiAgfSk7XG5cbiAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZCh0aGlzLm1vZGFsLmNvbnRhaW5lcik7XG59XG5cbi8qKlxuICogTW9kYWwgY29tcG9uZW50IHRvIGltcHJvdmUgbGlzaWJpbGl0eSBieSBjb25zdHJ1Y3RpbmcgdGhlIG1vZGFsIG91dHNpZGUgdGhlIG1haW4gZnVuY3Rpb25cbiAqXG4gKiBAcGFyYW0ge09iamVjdH0gcGFyYW1zXG4gKlxuICovXG5mdW5jdGlvbiBNb2RhbCh7XG4gIGlkID0gJ2NvbmZpcm0tbW9kYWwnLFxuICBjb25maXJtVGl0bGUsXG4gIGNvbmZpcm1NZXNzYWdlID0gJycsXG4gIGNsb3NlQnV0dG9uTGFiZWwgPSAnQ2xvc2UnLFxuICBjb25maXJtQnV0dG9uTGFiZWwgPSAnQWNjZXB0JyxcbiAgY29uZmlybUJ1dHRvbkNsYXNzID0gJ2J0bi1wcmltYXJ5JyxcbiAgY3VzdG9tQnV0dG9ucyA9IFtdLFxufSkge1xuICBjb25zdCBtb2RhbCA9IHt9O1xuXG4gIC8vIE1haW4gbW9kYWwgZWxlbWVudFxuICBtb2RhbC5jb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgbW9kYWwuY29udGFpbmVyLmNsYXNzTGlzdC5hZGQoJ21vZGFsJywgJ2ZhZGUnKTtcbiAgbW9kYWwuY29udGFpbmVyLmlkID0gaWQ7XG5cbiAgLy8gTW9kYWwgZGlhbG9nIGVsZW1lbnRcbiAgbW9kYWwuZGlhbG9nID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gIG1vZGFsLmRpYWxvZy5jbGFzc0xpc3QuYWRkKCdtb2RhbC1kaWFsb2cnKTtcblxuICAvLyBNb2RhbCBjb250ZW50IGVsZW1lbnRcbiAgbW9kYWwuY29udGVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICBtb2RhbC5jb250ZW50LmNsYXNzTGlzdC5hZGQoJ21vZGFsLWNvbnRlbnQnKTtcblxuICAvLyBNb2RhbCBoZWFkZXIgZWxlbWVudFxuICBtb2RhbC5oZWFkZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgbW9kYWwuaGVhZGVyLmNsYXNzTGlzdC5hZGQoJ21vZGFsLWhlYWRlcicpO1xuXG4gIC8vIE1vZGFsIHRpdGxlIGVsZW1lbnRcbiAgaWYgKGNvbmZpcm1UaXRsZSkge1xuICAgIG1vZGFsLnRpdGxlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaDQnKTtcbiAgICBtb2RhbC50aXRsZS5jbGFzc0xpc3QuYWRkKCdtb2RhbC10aXRsZScpO1xuICAgIG1vZGFsLnRpdGxlLmlubmVySFRNTCA9IGNvbmZpcm1UaXRsZTtcbiAgfVxuXG4gIC8vIE1vZGFsIGNsb3NlIGJ1dHRvbiBpY29uXG4gIG1vZGFsLmNsb3NlSWNvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2J1dHRvbicpO1xuICBtb2RhbC5jbG9zZUljb24uY2xhc3NMaXN0LmFkZCgnY2xvc2UnKTtcbiAgbW9kYWwuY2xvc2VJY29uLnNldEF0dHJpYnV0ZSgndHlwZScsICdidXR0b24nKTtcbiAgbW9kYWwuY2xvc2VJY29uLmRhdGFzZXQuZGlzbWlzcyA9ICdtb2RhbCc7XG4gIG1vZGFsLmNsb3NlSWNvbi5pbm5lckhUTUwgPSAnw5cnO1xuXG4gIC8vIE1vZGFsIGJvZHkgZWxlbWVudFxuICBtb2RhbC5ib2R5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gIG1vZGFsLmJvZHkuY2xhc3NMaXN0LmFkZCgnbW9kYWwtYm9keScsICd0ZXh0LWxlZnQnLCAnZm9udC13ZWlnaHQtbm9ybWFsJyk7XG5cbiAgLy8gTW9kYWwgbWVzc2FnZSBlbGVtZW50XG4gIG1vZGFsLm1lc3NhZ2UgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdwJyk7XG4gIG1vZGFsLm1lc3NhZ2UuY2xhc3NMaXN0LmFkZCgnY29uZmlybS1tZXNzYWdlJyk7XG4gIG1vZGFsLm1lc3NhZ2UuaW5uZXJIVE1MID0gY29uZmlybU1lc3NhZ2U7XG5cbiAgLy8gTW9kYWwgZm9vdGVyIGVsZW1lbnRcbiAgbW9kYWwuZm9vdGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gIG1vZGFsLmZvb3Rlci5jbGFzc0xpc3QuYWRkKCdtb2RhbC1mb290ZXInKTtcblxuICAvLyBNb2RhbCBjbG9zZSBidXR0b24gZWxlbWVudFxuICBtb2RhbC5jbG9zZUJ1dHRvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2J1dHRvbicpO1xuICBtb2RhbC5jbG9zZUJ1dHRvbi5zZXRBdHRyaWJ1dGUoJ3R5cGUnLCAnYnV0dG9uJyk7XG4gIG1vZGFsLmNsb3NlQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bicsICdidG4tb3V0bGluZS1zZWNvbmRhcnknLCAnYnRuLWxnJyk7XG4gIG1vZGFsLmNsb3NlQnV0dG9uLmRhdGFzZXQuZGlzbWlzcyA9ICdtb2RhbCc7XG4gIG1vZGFsLmNsb3NlQnV0dG9uLmlubmVySFRNTCA9IGNsb3NlQnV0dG9uTGFiZWw7XG5cbiAgLy8gTW9kYWwgY29uZmlybSBidXR0b24gZWxlbWVudFxuICBtb2RhbC5jb25maXJtQnV0dG9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnYnV0dG9uJyk7XG4gIG1vZGFsLmNvbmZpcm1CdXR0b24uc2V0QXR0cmlidXRlKCd0eXBlJywgJ2J1dHRvbicpO1xuICBtb2RhbC5jb25maXJtQnV0dG9uLmNsYXNzTGlzdC5hZGQoJ2J0bicsIGNvbmZpcm1CdXR0b25DbGFzcywgJ2J0bi1sZycsICdidG4tY29uZmlybS1zdWJtaXQnKTtcbiAgbW9kYWwuY29uZmlybUJ1dHRvbi5kYXRhc2V0LmRpc21pc3MgPSAnbW9kYWwnO1xuICBtb2RhbC5jb25maXJtQnV0dG9uLmlubmVySFRNTCA9IGNvbmZpcm1CdXR0b25MYWJlbDtcblxuICAvLyBDb25zdHJ1Y3RpbmcgdGhlIG1vZGFsXG4gIGlmIChjb25maXJtVGl0bGUpIHtcbiAgICBtb2RhbC5oZWFkZXIuYXBwZW5kKG1vZGFsLnRpdGxlLCBtb2RhbC5jbG9zZUljb24pO1xuICB9IGVsc2Uge1xuICAgIG1vZGFsLmhlYWRlci5hcHBlbmRDaGlsZChtb2RhbC5jbG9zZUljb24pO1xuICB9XG5cbiAgbW9kYWwuYm9keS5hcHBlbmRDaGlsZChtb2RhbC5tZXNzYWdlKTtcbiAgbW9kYWwuZm9vdGVyLmFwcGVuZChtb2RhbC5jbG9zZUJ1dHRvbiwgLi4uY3VzdG9tQnV0dG9ucywgbW9kYWwuY29uZmlybUJ1dHRvbik7XG4gIG1vZGFsLmNvbnRlbnQuYXBwZW5kKG1vZGFsLmhlYWRlciwgbW9kYWwuYm9keSwgbW9kYWwuZm9vdGVyKTtcbiAgbW9kYWwuZGlhbG9nLmFwcGVuZENoaWxkKG1vZGFsLmNvbnRlbnQpO1xuICBtb2RhbC5jb250YWluZXIuYXBwZW5kQ2hpbGQobW9kYWwuZGlhbG9nKTtcblxuICByZXR1cm4gbW9kYWw7XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvbW9kYWwuanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmltcG9ydCBDb25maXJtTW9kYWwgZnJvbSAnQGNvbXBvbmVudHMvbW9kYWwnO1xuXG5jb25zdCB7JH0gPSB3aW5kb3c7XG5cbi8qKlxuICogQ2xhc3MgU3VibWl0Um93QWN0aW9uRXh0ZW5zaW9uIGhhbmRsZXMgc3VibWl0dGluZyBvZiByb3cgYWN0aW9uXG4gKi9cbmV4cG9ydCBkZWZhdWx0IGNsYXNzIFN1Ym1pdFJvd0FjdGlvbkV4dGVuc2lvbiB7XG4gIC8qKlxuICAgKiBFeHRlbmQgZ3JpZFxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICovXG4gIGV4dGVuZChncmlkKSB7XG4gICAgZ3JpZC5nZXRDb250YWluZXIoKS5vbignY2xpY2snLCAnLmpzLXN1Ym1pdC1yb3ctYWN0aW9uJywgKGV2ZW50KSA9PiB7XG4gICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICBjb25zdCAkYnV0dG9uID0gJChldmVudC5jdXJyZW50VGFyZ2V0KTtcbiAgICAgIGNvbnN0IGNvbmZpcm1NZXNzYWdlID0gJGJ1dHRvbi5kYXRhKCdjb25maXJtTWVzc2FnZScpO1xuICAgICAgY29uc3QgY29uZmlybVRpdGxlID0gJGJ1dHRvbi5kYXRhKCd0aXRsZScpO1xuXG4gICAgICBjb25zdCBtZXRob2QgPSAkYnV0dG9uLmRhdGEoJ21ldGhvZCcpO1xuXG4gICAgICBpZiAoY29uZmlybVRpdGxlKSB7XG4gICAgICAgIHRoaXMuc2hvd0NvbmZpcm1Nb2RhbCgkYnV0dG9uLCBncmlkLCBjb25maXJtTWVzc2FnZSwgY29uZmlybVRpdGxlLCBtZXRob2QpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgaWYgKGNvbmZpcm1NZXNzYWdlLmxlbmd0aCAmJiAhd2luZG93LmNvbmZpcm0oY29uZmlybU1lc3NhZ2UpKSB7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5wb3N0Rm9ybSgkYnV0dG9uLCBtZXRob2QpO1xuICAgICAgfVxuICAgIH0pO1xuICB9XG5cbiAgcG9zdEZvcm0oJGJ1dHRvbiwgbWV0aG9kKSB7XG4gICAgY29uc3QgaXNHZXRPclBvc3RNZXRob2QgPSBbJ0dFVCcsICdQT1NUJ10uaW5jbHVkZXMobWV0aG9kKTtcblxuICAgIGNvbnN0ICRmb3JtID0gJCgnPGZvcm0+Jywge1xuICAgICAgYWN0aW9uOiAkYnV0dG9uLmRhdGEoJ3VybCcpLFxuICAgICAgbWV0aG9kOiBpc0dldE9yUG9zdE1ldGhvZCA/IG1ldGhvZCA6ICdQT1NUJyxcbiAgICB9KS5hcHBlbmRUbygnYm9keScpO1xuXG4gICAgaWYgKCFpc0dldE9yUG9zdE1ldGhvZCkge1xuICAgICAgJGZvcm0uYXBwZW5kKCQoJzxpbnB1dD4nLCB7XG4gICAgICAgIHR5cGU6ICdfaGlkZGVuJyxcbiAgICAgICAgbmFtZTogJ19tZXRob2QnLFxuICAgICAgICB2YWx1ZTogbWV0aG9kLFxuICAgICAgfSkpO1xuICAgIH1cblxuICAgICRmb3JtLnN1Ym1pdCgpO1xuICB9XG5cbiAgLyoqXG4gICAqIEBwYXJhbSB7alF1ZXJ5fSAkc3VibWl0QnRuXG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKiBAcGFyYW0ge3N0cmluZ30gY29uZmlybU1lc3NhZ2VcbiAgICogQHBhcmFtIHtzdHJpbmd9IGNvbmZpcm1UaXRsZVxuICAgKiBAcGFyYW0ge3N0cmluZ30gbWV0aG9kXG4gICAqL1xuICBzaG93Q29uZmlybU1vZGFsKCRzdWJtaXRCdG4sIGdyaWQsIGNvbmZpcm1NZXNzYWdlLCBjb25maXJtVGl0bGUsIG1ldGhvZCkge1xuICAgIGNvbnN0IGNvbmZpcm1CdXR0b25MYWJlbCA9ICRzdWJtaXRCdG4uZGF0YSgnY29uZmlybUJ1dHRvbkxhYmVsJyk7XG4gICAgY29uc3QgY2xvc2VCdXR0b25MYWJlbCA9ICRzdWJtaXRCdG4uZGF0YSgnY2xvc2VCdXR0b25MYWJlbCcpO1xuICAgIGNvbnN0IGNvbmZpcm1CdXR0b25DbGFzcyA9ICRzdWJtaXRCdG4uZGF0YSgnY29uZmlybUJ1dHRvbkNsYXNzJyk7XG5cbiAgICBjb25zdCBtb2RhbCA9IG5ldyBDb25maXJtTW9kYWwoe1xuICAgICAgaWQ6IGAke2dyaWQuZ2V0SWQoKX0tZ3JpZC1jb25maXJtLW1vZGFsYCxcbiAgICAgIGNvbmZpcm1UaXRsZSxcbiAgICAgIGNvbmZpcm1NZXNzYWdlLFxuICAgICAgY29uZmlybUJ1dHRvbkxhYmVsLFxuICAgICAgY2xvc2VCdXR0b25MYWJlbCxcbiAgICAgIGNvbmZpcm1CdXR0b25DbGFzcyxcbiAgICB9LCAoKSA9PiB0aGlzLnBvc3RGb3JtKCRzdWJtaXRCdG4sIG1ldGhvZCkpO1xuXG4gICAgbW9kYWwuc2hvdygpO1xuICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vYWN0aW9uL3Jvdy9zdWJtaXQtcm93LWFjdGlvbi1leHRlbnNpb24uanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmNvbnN0IHskfSA9IHdpbmRvdztcblxuLyoqXG4gKiBDbGFzcyBCdWxrQWN0aW9uU2VsZWN0Q2hlY2tib3hFeHRlbnNpb25cbiAqL1xuZXhwb3J0IGRlZmF1bHQgY2xhc3MgQnVsa0FjdGlvbkNoZWNrYm94RXh0ZW5zaW9uIHtcbiAgLyoqXG4gICAqIEV4dGVuZCBncmlkIHdpdGggYnVsayBhY3Rpb24gY2hlY2tib3hlcyBoYW5kbGluZyBmdW5jdGlvbmFsaXR5XG4gICAqXG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKi9cbiAgZXh0ZW5kKGdyaWQpIHtcbiAgICB0aGlzLmhhbmRsZUJ1bGtBY3Rpb25DaGVja2JveFNlbGVjdChncmlkKTtcbiAgICB0aGlzLmhhbmRsZUJ1bGtBY3Rpb25TZWxlY3RBbGxDaGVja2JveChncmlkKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBIYW5kbGVzIFwiU2VsZWN0IGFsbFwiIGJ1dHRvbiBpbiB0aGUgZ3JpZFxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICpcbiAgICogQHByaXZhdGVcbiAgICovXG4gIGhhbmRsZUJ1bGtBY3Rpb25TZWxlY3RBbGxDaGVja2JveChncmlkKSB7XG4gICAgZ3JpZC5nZXRDb250YWluZXIoKS5vbignY2hhbmdlJywgJy5qcy1idWxrLWFjdGlvbi1zZWxlY3QtYWxsJywgKGUpID0+IHtcbiAgICAgIGNvbnN0ICRjaGVja2JveCA9ICQoZS5jdXJyZW50VGFyZ2V0KTtcblxuICAgICAgY29uc3QgaXNDaGVja2VkID0gJGNoZWNrYm94LmlzKCc6Y2hlY2tlZCcpO1xuXG4gICAgICBpZiAoaXNDaGVja2VkKSB7XG4gICAgICAgIHRoaXMuZW5hYmxlQnVsa0FjdGlvbnNCdG4oZ3JpZCk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLmRpc2FibGVCdWxrQWN0aW9uc0J0bihncmlkKTtcbiAgICAgIH1cblxuICAgICAgZ3JpZC5nZXRDb250YWluZXIoKS5maW5kKCcuanMtYnVsay1hY3Rpb24tY2hlY2tib3gnKS5wcm9wKCdjaGVja2VkJywgaXNDaGVja2VkKTtcbiAgICB9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBIYW5kbGVzIGVhY2ggYnVsayBhY3Rpb24gY2hlY2tib3ggc2VsZWN0IGluIHRoZSBncmlkXG4gICAqXG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKlxuICAgKiBAcHJpdmF0ZVxuICAgKi9cbiAgaGFuZGxlQnVsa0FjdGlvbkNoZWNrYm94U2VsZWN0KGdyaWQpIHtcbiAgICBncmlkLmdldENvbnRhaW5lcigpLm9uKCdjaGFuZ2UnLCAnLmpzLWJ1bGstYWN0aW9uLWNoZWNrYm94JywgKCkgPT4ge1xuICAgICAgY29uc3QgY2hlY2tlZFJvd3NDb3VudCA9IGdyaWQuZ2V0Q29udGFpbmVyKCkuZmluZCgnLmpzLWJ1bGstYWN0aW9uLWNoZWNrYm94OmNoZWNrZWQnKS5sZW5ndGg7XG5cbiAgICAgIGlmIChjaGVja2VkUm93c0NvdW50ID4gMCkge1xuICAgICAgICB0aGlzLmVuYWJsZUJ1bGtBY3Rpb25zQnRuKGdyaWQpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGhpcy5kaXNhYmxlQnVsa0FjdGlvbnNCdG4oZ3JpZCk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogRW5hYmxlIGJ1bGsgYWN0aW9ucyBidXR0b25cbiAgICpcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqXG4gICAqIEBwcml2YXRlXG4gICAqL1xuICBlbmFibGVCdWxrQWN0aW9uc0J0bihncmlkKSB7XG4gICAgZ3JpZC5nZXRDb250YWluZXIoKS5maW5kKCcuanMtYnVsay1hY3Rpb25zLWJ0bicpLnByb3AoJ2Rpc2FibGVkJywgZmFsc2UpO1xuICB9XG5cbiAgLyoqXG4gICAqIERpc2FibGUgYnVsayBhY3Rpb25zIGJ1dHRvblxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICpcbiAgICogQHByaXZhdGVcbiAgICovXG4gIGRpc2FibGVCdWxrQWN0aW9uc0J0bihncmlkKSB7XG4gICAgZ3JpZC5nZXRDb250YWluZXIoKS5maW5kKCcuanMtYnVsay1hY3Rpb25zLWJ0bicpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9idWxrLWFjdGlvbi1jaGVja2JveC1leHRlbnNpb24uanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmNvbnN0IHskfSA9IHdpbmRvdztcblxuLyoqXG4gKiBDbGFzcyBFeHBvcnRUb1NxbE1hbmFnZXJFeHRlbnNpb24gZXh0ZW5kcyBncmlkIHdpdGggZXhwb3J0aW5nIHF1ZXJ5IHRvIFNRTCBNYW5hZ2VyXG4gKi9cbmV4cG9ydCBkZWZhdWx0IGNsYXNzIEV4cG9ydFRvU3FsTWFuYWdlckV4dGVuc2lvbiB7XG4gIC8qKlxuICAgKiBFeHRlbmQgZ3JpZFxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICovXG4gIGV4dGVuZChncmlkKSB7XG4gICAgZ3JpZC5nZXRIZWFkZXJDb250YWluZXIoKS5vbignY2xpY2snLCAnLmpzLWNvbW1vbl9zaG93X3F1ZXJ5LWdyaWQtYWN0aW9uJywgKCkgPT4gdGhpcy5vblNob3dTcWxRdWVyeUNsaWNrKGdyaWQpKTtcbiAgICBncmlkLmdldEhlYWRlckNvbnRhaW5lcigpLm9uKFxuICAgICAgJ2NsaWNrJyxcbiAgICAgICcuanMtY29tbW9uX2V4cG9ydF9zcWxfbWFuYWdlci1ncmlkLWFjdGlvbicsXG4gICAgICAoKSA9PiB0aGlzLm9uRXhwb3J0U3FsTWFuYWdlckNsaWNrKGdyaWQpLFxuICAgICk7XG4gIH1cblxuICAvKipcbiAgICogSW52b2tlZCB3aGVuIGNsaWNraW5nIG9uIHRoZSBcInNob3cgc3FsIHF1ZXJ5XCIgdG9vbGJhciBidXR0b25cbiAgICpcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqXG4gICAqIEBwcml2YXRlXG4gICAqL1xuICBvblNob3dTcWxRdWVyeUNsaWNrKGdyaWQpIHtcbiAgICBjb25zdCAkc3FsTWFuYWdlckZvcm0gPSAkKGAjJHtncmlkLmdldElkKCl9X2NvbW1vbl9zaG93X3F1ZXJ5X21vZGFsX2Zvcm1gKTtcbiAgICB0aGlzLmZpbGxFeHBvcnRGb3JtKCRzcWxNYW5hZ2VyRm9ybSwgZ3JpZCk7XG5cbiAgICBjb25zdCAkbW9kYWwgPSAkKGAjJHtncmlkLmdldElkKCl9X2dyaWRfY29tbW9uX3Nob3dfcXVlcnlfbW9kYWxgKTtcbiAgICAkbW9kYWwubW9kYWwoJ3Nob3cnKTtcblxuICAgICRtb2RhbC5vbignY2xpY2snLCAnLmJ0bi1zcWwtc3VibWl0JywgKCkgPT4gJHNxbE1hbmFnZXJGb3JtLnN1Ym1pdCgpKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBJbnZva2VkIHdoZW4gY2xpY2tpbmcgb24gdGhlIFwiZXhwb3J0IHRvIHRoZSBzcWwgcXVlcnlcIiB0b29sYmFyIGJ1dHRvblxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICpcbiAgICogQHByaXZhdGVcbiAgICovXG4gIG9uRXhwb3J0U3FsTWFuYWdlckNsaWNrKGdyaWQpIHtcbiAgICBjb25zdCAkc3FsTWFuYWdlckZvcm0gPSAkKGAjJHtncmlkLmdldElkKCl9X2NvbW1vbl9zaG93X3F1ZXJ5X21vZGFsX2Zvcm1gKTtcblxuICAgIHRoaXMuZmlsbEV4cG9ydEZvcm0oJHNxbE1hbmFnZXJGb3JtLCBncmlkKTtcblxuICAgICRzcWxNYW5hZ2VyRm9ybS5zdWJtaXQoKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBGaWxsIGV4cG9ydCBmb3JtIHdpdGggU1FMIGFuZCBpdCdzIG5hbWVcbiAgICpcbiAgICogQHBhcmFtIHtqUXVlcnl9ICRzcWxNYW5hZ2VyRm9ybVxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICpcbiAgICogQHByaXZhdGVcbiAgICovXG4gIGZpbGxFeHBvcnRGb3JtKCRzcWxNYW5hZ2VyRm9ybSwgZ3JpZCkge1xuICAgIGNvbnN0IHF1ZXJ5ID0gZ3JpZC5nZXRDb250YWluZXIoKS5maW5kKCcuanMtZ3JpZC10YWJsZScpLmRhdGEoJ3F1ZXJ5Jyk7XG5cbiAgICAkc3FsTWFuYWdlckZvcm0uZmluZCgndGV4dGFyZWFbbmFtZT1cInNxbFwiXScpLnZhbChxdWVyeSk7XG4gICAgJHNxbE1hbmFnZXJGb3JtLmZpbmQoJ2lucHV0W25hbWU9XCJuYW1lXCJdJykudmFsKHRoaXMuZ2V0TmFtZUZyb21CcmVhZGNydW1iKCkpO1xuICB9XG5cbiAgLyoqXG4gICAqIEdldCBleHBvcnQgbmFtZSBmcm9tIHBhZ2UncyBicmVhZGNydW1iXG4gICAqXG4gICAqIEByZXR1cm4ge1N0cmluZ31cbiAgICpcbiAgICogQHByaXZhdGVcbiAgICovXG4gIGdldE5hbWVGcm9tQnJlYWRjcnVtYigpIHtcbiAgICBjb25zdCAkYnJlYWRjcnVtYnMgPSAkKCcuaGVhZGVyLXRvb2xiYXInKS5maW5kKCcuYnJlYWRjcnVtYi1pdGVtJyk7XG4gICAgbGV0IG5hbWUgPSAnJztcblxuICAgICRicmVhZGNydW1icy5lYWNoKChpLCBpdGVtKSA9PiB7XG4gICAgICBjb25zdCAkYnJlYWRjcnVtYiA9ICQoaXRlbSk7XG5cbiAgICAgIGNvbnN0IGJyZWFkY3J1bWJUaXRsZSA9ICRicmVhZGNydW1iLmZpbmQoJ2EnKS5sZW5ndGggPiAwXG4gICAgICAgID8gJGJyZWFkY3J1bWIuZmluZCgnYScpLnRleHQoKVxuICAgICAgICA6ICRicmVhZGNydW1iLnRleHQoKTtcblxuICAgICAgaWYgKG5hbWUubGVuZ3RoID4gMCkge1xuICAgICAgICBuYW1lID0gbmFtZS5jb25jYXQoJyA+ICcpO1xuICAgICAgfVxuXG4gICAgICBuYW1lID0gbmFtZS5jb25jYXQoYnJlYWRjcnVtYlRpdGxlKTtcbiAgICB9KTtcblxuICAgIHJldHVybiBuYW1lO1xuICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vZXhwb3J0LXRvLXNxbC1tYW5hZ2VyLWV4dGVuc2lvbi5qcyIsIi8qKlxuICogQ29weXJpZ2h0IHNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBQcmVzdGFTaG9wIGlzIGFuIEludGVybmF0aW9uYWwgUmVnaXN0ZXJlZCBUcmFkZW1hcmsgJiBQcm9wZXJ0eSBvZiBQcmVzdGFTaG9wIFNBXG4gKlxuICogTk9USUNFIE9GIExJQ0VOU0VcbiAqXG4gKiBUaGlzIHNvdXJjZSBmaWxlIGlzIHN1YmplY3QgdG8gdGhlIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqIHRoYXQgaXMgYnVuZGxlZCB3aXRoIHRoaXMgcGFja2FnZSBpbiB0aGUgZmlsZSBMSUNFTlNFLm1kLlxuICogSXQgaXMgYWxzbyBhdmFpbGFibGUgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIgYXQgdGhpcyBVUkw6XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjBcbiAqIElmIHlvdSBkaWQgbm90IHJlY2VpdmUgYSBjb3B5IG9mIHRoZSBsaWNlbnNlIGFuZCBhcmUgdW5hYmxlIHRvXG4gKiBvYnRhaW4gaXQgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIsIHBsZWFzZSBzZW5kIGFuIGVtYWlsXG4gKiB0byBsaWNlbnNlQHByZXN0YXNob3AuY29tIHNvIHdlIGNhbiBzZW5kIHlvdSBhIGNvcHkgaW1tZWRpYXRlbHkuXG4gKlxuICogRElTQ0xBSU1FUlxuICpcbiAqIERvIG5vdCBlZGl0IG9yIGFkZCB0byB0aGlzIGZpbGUgaWYgeW91IHdpc2ggdG8gdXBncmFkZSBQcmVzdGFTaG9wIHRvIG5ld2VyXG4gKiB2ZXJzaW9ucyBpbiB0aGUgZnV0dXJlLiBJZiB5b3Ugd2lzaCB0byBjdXN0b21pemUgUHJlc3RhU2hvcCBmb3IgeW91clxuICogbmVlZHMgcGxlYXNlIHJlZmVyIHRvIGh0dHBzOi8vZGV2ZG9jcy5wcmVzdGFzaG9wLmNvbS8gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gKlxuICogQGF1dGhvciAgICBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnMgPGNvbnRhY3RAcHJlc3Rhc2hvcC5jb20+XG4gKiBAY29weXJpZ2h0IFNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBAbGljZW5zZSAgIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMCBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKi9cblxuaW1wb3J0IHJlc2V0U2VhcmNoIGZyb20gJ0BhcHAvdXRpbHMvcmVzZXRfc2VhcmNoJztcblxuY29uc3QgeyR9ID0gd2luZG93O1xuXG4vKipcbiAqIENsYXNzIEZpbHRlcnNSZXNldEV4dGVuc2lvbiBleHRlbmRzIGdyaWQgd2l0aCBmaWx0ZXJzIHJlc2V0dGluZ1xuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBGaWx0ZXJzUmVzZXRFeHRlbnNpb24ge1xuICAvKipcbiAgICogRXh0ZW5kIGdyaWRcbiAgICpcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqL1xuICBleHRlbmQoZ3JpZCkge1xuICAgIGdyaWQuZ2V0Q29udGFpbmVyKCkub24oJ2NsaWNrJywgJy5qcy1yZXNldC1zZWFyY2gnLCAoZXZlbnQpID0+IHtcbiAgICAgIHJlc2V0U2VhcmNoKCQoZXZlbnQuY3VycmVudFRhcmdldCkuZGF0YSgndXJsJyksICQoZXZlbnQuY3VycmVudFRhcmdldCkuZGF0YSgncmVkaXJlY3QnKSk7XG4gICAgfSk7XG4gIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9maWx0ZXJzLXJlc2V0LWV4dGVuc2lvbi5qcyIsIi8qKlxuICogQ29weXJpZ2h0IHNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBQcmVzdGFTaG9wIGlzIGFuIEludGVybmF0aW9uYWwgUmVnaXN0ZXJlZCBUcmFkZW1hcmsgJiBQcm9wZXJ0eSBvZiBQcmVzdGFTaG9wIFNBXG4gKlxuICogTk9USUNFIE9GIExJQ0VOU0VcbiAqXG4gKiBUaGlzIHNvdXJjZSBmaWxlIGlzIHN1YmplY3QgdG8gdGhlIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqIHRoYXQgaXMgYnVuZGxlZCB3aXRoIHRoaXMgcGFja2FnZSBpbiB0aGUgZmlsZSBMSUNFTlNFLm1kLlxuICogSXQgaXMgYWxzbyBhdmFpbGFibGUgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIgYXQgdGhpcyBVUkw6XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjBcbiAqIElmIHlvdSBkaWQgbm90IHJlY2VpdmUgYSBjb3B5IG9mIHRoZSBsaWNlbnNlIGFuZCBhcmUgdW5hYmxlIHRvXG4gKiBvYnRhaW4gaXQgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIsIHBsZWFzZSBzZW5kIGFuIGVtYWlsXG4gKiB0byBsaWNlbnNlQHByZXN0YXNob3AuY29tIHNvIHdlIGNhbiBzZW5kIHlvdSBhIGNvcHkgaW1tZWRpYXRlbHkuXG4gKlxuICogRElTQ0xBSU1FUlxuICpcbiAqIERvIG5vdCBlZGl0IG9yIGFkZCB0byB0aGlzIGZpbGUgaWYgeW91IHdpc2ggdG8gdXBncmFkZSBQcmVzdGFTaG9wIHRvIG5ld2VyXG4gKiB2ZXJzaW9ucyBpbiB0aGUgZnV0dXJlLiBJZiB5b3Ugd2lzaCB0byBjdXN0b21pemUgUHJlc3RhU2hvcCBmb3IgeW91clxuICogbmVlZHMgcGxlYXNlIHJlZmVyIHRvIGh0dHBzOi8vZGV2ZG9jcy5wcmVzdGFzaG9wLmNvbS8gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gKlxuICogQGF1dGhvciAgICBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnMgPGNvbnRhY3RAcHJlc3Rhc2hvcC5jb20+XG4gKiBAY29weXJpZ2h0IFNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBAbGljZW5zZSAgIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMCBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKi9cblxuY29uc3QgeyR9ID0gd2luZG93O1xuXG4vKipcbiAqIENsYXNzIExpbmtSb3dBY3Rpb25FeHRlbnNpb24gaGFuZGxlcyBsaW5rIHJvdyBhY3Rpb25zXG4gKi9cbmV4cG9ydCBkZWZhdWx0IGNsYXNzIExpbmtSb3dBY3Rpb25FeHRlbnNpb24ge1xuICAvKipcbiAgICogRXh0ZW5kIGdyaWRcbiAgICpcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqL1xuICBleHRlbmQoZ3JpZCkge1xuICAgIHRoaXMuaW5pdFJvd0xpbmtzKGdyaWQpO1xuICAgIHRoaXMuaW5pdENvbmZpcm1hYmxlQWN0aW9ucyhncmlkKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBFeHRlbmQgZ3JpZFxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICovXG4gIGluaXRDb25maXJtYWJsZUFjdGlvbnMoZ3JpZCkge1xuICAgIGdyaWQuZ2V0Q29udGFpbmVyKCkub24oJ2NsaWNrJywgJy5qcy1saW5rLXJvdy1hY3Rpb24nLCAoZXZlbnQpID0+IHtcbiAgICAgIGNvbnN0IGNvbmZpcm1NZXNzYWdlID0gJChldmVudC5jdXJyZW50VGFyZ2V0KS5kYXRhKCdjb25maXJtLW1lc3NhZ2UnKTtcblxuICAgICAgaWYgKGNvbmZpcm1NZXNzYWdlLmxlbmd0aCAmJiAhd2luZG93LmNvbmZpcm0oY29uZmlybU1lc3NhZ2UpKSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogQWRkIGEgY2xpY2sgZXZlbnQgb24gcm93cyB0aGF0IG1hdGNoZXMgdGhlIGZpcnN0IGxpbmsgYWN0aW9uIChpZiBwcmVzZW50KVxuICAgKlxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICovXG4gIGluaXRSb3dMaW5rcyhncmlkKSB7XG4gICAgJCgndHInLCBncmlkLmdldENvbnRhaW5lcigpKS5lYWNoKGZ1bmN0aW9uIGluaXRFYWNoUm93KCkge1xuICAgICAgY29uc3QgJHBhcmVudFJvdyA9ICQodGhpcyk7XG5cbiAgICAgICQoJy5qcy1saW5rLXJvdy1hY3Rpb25bZGF0YS1jbGlja2FibGUtcm93PTFdOmZpcnN0JywgJHBhcmVudFJvdykuZWFjaChmdW5jdGlvbiBwcm9wYWdhdGVGaXJzdExpbmtBY3Rpb24oKSB7XG4gICAgICAgIGNvbnN0ICRyb3dBY3Rpb24gPSAkKHRoaXMpO1xuICAgICAgICBjb25zdCAkcGFyZW50Q2VsbCA9ICRyb3dBY3Rpb24uY2xvc2VzdCgndGQnKTtcblxuICAgICAgICBjb25zdCBjbGlja2FibGVDZWxscyA9ICQoJ3RkLmNsaWNrYWJsZScsICRwYXJlbnRSb3cpLm5vdCgkcGFyZW50Q2VsbCk7XG4gICAgICAgIGxldCBpc0RyYWdnaW5nID0gZmFsc2U7XG4gICAgICAgIGNsaWNrYWJsZUNlbGxzLmFkZENsYXNzKCdjdXJzb3ItcG9pbnRlcicpLm1vdXNlZG93bigoKSA9PiB7XG4gICAgICAgICAgJCh3aW5kb3cpLm1vdXNlbW92ZSgoKSA9PiB7XG4gICAgICAgICAgICBpc0RyYWdnaW5nID0gdHJ1ZTtcbiAgICAgICAgICAgICQod2luZG93KS51bmJpbmQoJ21vdXNlbW92ZScpO1xuICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcblxuICAgICAgICBjbGlja2FibGVDZWxscy5tb3VzZXVwKCgpID0+IHtcbiAgICAgICAgICBjb25zdCB3YXNEcmFnZ2luZyA9IGlzRHJhZ2dpbmc7XG4gICAgICAgICAgaXNEcmFnZ2luZyA9IGZhbHNlO1xuICAgICAgICAgICQod2luZG93KS51bmJpbmQoJ21vdXNlbW92ZScpO1xuXG4gICAgICAgICAgaWYgKCF3YXNEcmFnZ2luZykge1xuICAgICAgICAgICAgY29uc3QgY29uZmlybU1lc3NhZ2UgPSAkcm93QWN0aW9uLmRhdGEoJ2NvbmZpcm0tbWVzc2FnZScpO1xuXG4gICAgICAgICAgICBpZiAoIWNvbmZpcm1NZXNzYWdlLmxlbmd0aCB8fCB3aW5kb3cuY29uZmlybShjb25maXJtTWVzc2FnZSkpIHtcbiAgICAgICAgICAgICAgZG9jdW1lbnQubG9jYXRpb24gPSAkcm93QWN0aW9uLmF0dHIoJ2hyZWYnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgICAgfSk7XG4gICAgfSk7XG4gIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9saW5rLXJvdy1hY3Rpb24tZXh0ZW5zaW9uLmpzIiwiLyoqXG4gKiBDb3B5cmlnaHQgc2luY2UgMjAwNyBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnNcbiAqIFByZXN0YVNob3AgaXMgYW4gSW50ZXJuYXRpb25hbCBSZWdpc3RlcmVkIFRyYWRlbWFyayAmIFByb3BlcnR5IG9mIFByZXN0YVNob3AgU0FcbiAqXG4gKiBOT1RJQ0UgT0YgTElDRU5TRVxuICpcbiAqIFRoaXMgc291cmNlIGZpbGUgaXMgc3ViamVjdCB0byB0aGUgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICogdGhhdCBpcyBidW5kbGVkIHdpdGggdGhpcyBwYWNrYWdlIGluIHRoZSBmaWxlIExJQ0VOU0UubWQuXG4gKiBJdCBpcyBhbHNvIGF2YWlsYWJsZSB0aHJvdWdoIHRoZSB3b3JsZC13aWRlLXdlYiBhdCB0aGlzIFVSTDpcbiAqIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMFxuICogSWYgeW91IGRpZCBub3QgcmVjZWl2ZSBhIGNvcHkgb2YgdGhlIGxpY2Vuc2UgYW5kIGFyZSB1bmFibGUgdG9cbiAqIG9idGFpbiBpdCB0aHJvdWdoIHRoZSB3b3JsZC13aWRlLXdlYiwgcGxlYXNlIHNlbmQgYW4gZW1haWxcbiAqIHRvIGxpY2Vuc2VAcHJlc3Rhc2hvcC5jb20gc28gd2UgY2FuIHNlbmQgeW91IGEgY29weSBpbW1lZGlhdGVseS5cbiAqXG4gKiBESVNDTEFJTUVSXG4gKlxuICogRG8gbm90IGVkaXQgb3IgYWRkIHRvIHRoaXMgZmlsZSBpZiB5b3Ugd2lzaCB0byB1cGdyYWRlIFByZXN0YVNob3AgdG8gbmV3ZXJcbiAqIHZlcnNpb25zIGluIHRoZSBmdXR1cmUuIElmIHlvdSB3aXNoIHRvIGN1c3RvbWl6ZSBQcmVzdGFTaG9wIGZvciB5b3VyXG4gKiBuZWVkcyBwbGVhc2UgcmVmZXIgdG8gaHR0cHM6Ly9kZXZkb2NzLnByZXN0YXNob3AuY29tLyBmb3IgbW9yZSBpbmZvcm1hdGlvbi5cbiAqXG4gKiBAYXV0aG9yICAgIFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9ycyA8Y29udGFjdEBwcmVzdGFzaG9wLmNvbT5cbiAqIEBjb3B5cmlnaHQgU2luY2UgMjAwNyBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnNcbiAqIEBsaWNlbnNlICAgaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqL1xuXG4vKipcbiAqIENsYXNzIFJlbG9hZExpc3RFeHRlbnNpb24gZXh0ZW5kcyBncmlkIHdpdGggXCJMaXN0IHJlbG9hZFwiIGFjdGlvblxuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBSZWxvYWRMaXN0RXh0ZW5zaW9uIHtcbiAgLyoqXG4gICAqIEV4dGVuZCBncmlkXG4gICAqXG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKi9cbiAgZXh0ZW5kKGdyaWQpIHtcbiAgICBncmlkLmdldEhlYWRlckNvbnRhaW5lcigpLm9uKCdjbGljaycsICcuanMtY29tbW9uX3JlZnJlc2hfbGlzdC1ncmlkLWFjdGlvbicsICgpID0+IHtcbiAgICAgIHdpbmRvdy5sb2NhdGlvbi5yZWxvYWQoKTtcbiAgICB9KTtcbiAgfVxufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vLi4vLi4vLi4vYmFjay1vZmZpY2UvdGhlbWVzL25ldy10aGVtZS9qcy9jb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL3JlbG9hZC1saXN0LWV4dGVuc2lvbi5qcyIsIi8qKlxuICogQ29weXJpZ2h0IHNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBQcmVzdGFTaG9wIGlzIGFuIEludGVybmF0aW9uYWwgUmVnaXN0ZXJlZCBUcmFkZW1hcmsgJiBQcm9wZXJ0eSBvZiBQcmVzdGFTaG9wIFNBXG4gKlxuICogTk9USUNFIE9GIExJQ0VOU0VcbiAqXG4gKiBUaGlzIHNvdXJjZSBmaWxlIGlzIHN1YmplY3QgdG8gdGhlIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqIHRoYXQgaXMgYnVuZGxlZCB3aXRoIHRoaXMgcGFja2FnZSBpbiB0aGUgZmlsZSBMSUNFTlNFLm1kLlxuICogSXQgaXMgYWxzbyBhdmFpbGFibGUgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIgYXQgdGhpcyBVUkw6XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjBcbiAqIElmIHlvdSBkaWQgbm90IHJlY2VpdmUgYSBjb3B5IG9mIHRoZSBsaWNlbnNlIGFuZCBhcmUgdW5hYmxlIHRvXG4gKiBvYnRhaW4gaXQgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIsIHBsZWFzZSBzZW5kIGFuIGVtYWlsXG4gKiB0byBsaWNlbnNlQHByZXN0YXNob3AuY29tIHNvIHdlIGNhbiBzZW5kIHlvdSBhIGNvcHkgaW1tZWRpYXRlbHkuXG4gKlxuICogRElTQ0xBSU1FUlxuICpcbiAqIERvIG5vdCBlZGl0IG9yIGFkZCB0byB0aGlzIGZpbGUgaWYgeW91IHdpc2ggdG8gdXBncmFkZSBQcmVzdGFTaG9wIHRvIG5ld2VyXG4gKiB2ZXJzaW9ucyBpbiB0aGUgZnV0dXJlLiBJZiB5b3Ugd2lzaCB0byBjdXN0b21pemUgUHJlc3RhU2hvcCBmb3IgeW91clxuICogbmVlZHMgcGxlYXNlIHJlZmVyIHRvIGh0dHBzOi8vZGV2ZG9jcy5wcmVzdGFzaG9wLmNvbS8gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gKlxuICogQGF1dGhvciAgICBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnMgPGNvbnRhY3RAcHJlc3Rhc2hvcC5jb20+XG4gKiBAY29weXJpZ2h0IFNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBAbGljZW5zZSAgIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMCBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKi9cblxuaW1wb3J0IFRhYmxlU29ydGluZyBmcm9tICdAYXBwL3V0aWxzL3RhYmxlLXNvcnRpbmcnO1xuXG4vKipcbiAqIENsYXNzIFJlbG9hZExpc3RFeHRlbnNpb24gZXh0ZW5kcyBncmlkIHdpdGggXCJMaXN0IHJlbG9hZFwiIGFjdGlvblxuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBTb3J0aW5nRXh0ZW5zaW9uIHtcbiAgLyoqXG4gICAqIEV4dGVuZCBncmlkXG4gICAqXG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKi9cbiAgZXh0ZW5kKGdyaWQpIHtcbiAgICBjb25zdCAkc29ydGFibGVUYWJsZSA9IGdyaWQuZ2V0Q29udGFpbmVyKCkuZmluZCgndGFibGUudGFibGUnKTtcblxuICAgIG5ldyBUYWJsZVNvcnRpbmcoJHNvcnRhYmxlVGFibGUpLmF0dGFjaCgpO1xuICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vc29ydGluZy1leHRlbnNpb24uanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmltcG9ydCBDb25maXJtTW9kYWwgZnJvbSAnQGNvbXBvbmVudHMvbW9kYWwnO1xuXG5jb25zdCB7JH0gPSB3aW5kb3c7XG5cbi8qKlxuICogSGFuZGxlcyBzdWJtaXQgb2YgZ3JpZCBhY3Rpb25zXG4gKi9cbmV4cG9ydCBkZWZhdWx0IGNsYXNzIFN1Ym1pdEJ1bGtBY3Rpb25FeHRlbnNpb24ge1xuICBjb25zdHJ1Y3RvcigpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXh0ZW5kOiAoZ3JpZCkgPT4gdGhpcy5leHRlbmQoZ3JpZCksXG4gICAgfTtcbiAgfVxuXG4gIC8qKlxuICAgKiBFeHRlbmQgZ3JpZCB3aXRoIGJ1bGsgYWN0aW9uIHN1Ym1pdHRpbmdcbiAgICpcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqL1xuICBleHRlbmQoZ3JpZCkge1xuICAgIGdyaWQuZ2V0Q29udGFpbmVyKCkub24oJ2NsaWNrJywgJy5qcy1idWxrLWFjdGlvbi1zdWJtaXQtYnRuJywgKGV2ZW50KSA9PiB7XG4gICAgICB0aGlzLnN1Ym1pdChldmVudCwgZ3JpZCk7XG4gICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogSGFuZGxlIGJ1bGsgYWN0aW9uIHN1Ym1pdHRpbmdcbiAgICpcbiAgICogQHBhcmFtIHtFdmVudH0gZXZlbnRcbiAgICogQHBhcmFtIHtHcmlkfSBncmlkXG4gICAqXG4gICAqIEBwcml2YXRlXG4gICAqL1xuICBzdWJtaXQoZXZlbnQsIGdyaWQpIHtcbiAgICBjb25zdCAkc3VibWl0QnRuID0gJChldmVudC5jdXJyZW50VGFyZ2V0KTtcbiAgICBjb25zdCBjb25maXJtTWVzc2FnZSA9ICRzdWJtaXRCdG4uZGF0YSgnY29uZmlybS1tZXNzYWdlJyk7XG4gICAgY29uc3QgY29uZmlybVRpdGxlID0gJHN1Ym1pdEJ0bi5kYXRhKCdjb25maXJtVGl0bGUnKTtcblxuICAgIGlmIChjb25maXJtTWVzc2FnZSAhPT0gdW5kZWZpbmVkICYmIGNvbmZpcm1NZXNzYWdlLmxlbmd0aCA+IDApIHtcbiAgICAgIGlmIChjb25maXJtVGl0bGUgIT09IHVuZGVmaW5lZCkge1xuICAgICAgICB0aGlzLnNob3dDb25maXJtTW9kYWwoJHN1Ym1pdEJ0biwgZ3JpZCwgY29uZmlybU1lc3NhZ2UsIGNvbmZpcm1UaXRsZSk7XG4gICAgICB9IGVsc2UgaWYgKHdpbmRvdy5jb25maXJtKGNvbmZpcm1NZXNzYWdlKSkge1xuICAgICAgICB0aGlzLnBvc3RGb3JtKCRzdWJtaXRCdG4sIGdyaWQpO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLnBvc3RGb3JtKCRzdWJtaXRCdG4sIGdyaWQpO1xuICAgIH1cbiAgfVxuXG4gIC8qKlxuICAgKiBAcGFyYW0ge2pRdWVyeX0gJHN1Ym1pdEJ0blxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICogQHBhcmFtIHtzdHJpbmd9IGNvbmZpcm1NZXNzYWdlXG4gICAqIEBwYXJhbSB7c3RyaW5nfSBjb25maXJtVGl0bGVcbiAgICovXG4gIHNob3dDb25maXJtTW9kYWwoJHN1Ym1pdEJ0biwgZ3JpZCwgY29uZmlybU1lc3NhZ2UsIGNvbmZpcm1UaXRsZSkge1xuICAgIGNvbnN0IGNvbmZpcm1CdXR0b25MYWJlbCA9ICRzdWJtaXRCdG4uZGF0YSgnY29uZmlybUJ1dHRvbkxhYmVsJyk7XG4gICAgY29uc3QgY2xvc2VCdXR0b25MYWJlbCA9ICRzdWJtaXRCdG4uZGF0YSgnY2xvc2VCdXR0b25MYWJlbCcpO1xuICAgIGNvbnN0IGNvbmZpcm1CdXR0b25DbGFzcyA9ICRzdWJtaXRCdG4uZGF0YSgnY29uZmlybUJ1dHRvbkNsYXNzJyk7XG5cbiAgICBjb25zdCBtb2RhbCA9IG5ldyBDb25maXJtTW9kYWwoe1xuICAgICAgaWQ6IGAke2dyaWQuZ2V0SWQoKX0tZ3JpZC1jb25maXJtLW1vZGFsYCxcbiAgICAgIGNvbmZpcm1UaXRsZSxcbiAgICAgIGNvbmZpcm1NZXNzYWdlLFxuICAgICAgY29uZmlybUJ1dHRvbkxhYmVsLFxuICAgICAgY2xvc2VCdXR0b25MYWJlbCxcbiAgICAgIGNvbmZpcm1CdXR0b25DbGFzcyxcbiAgICB9LCAoKSA9PiB0aGlzLnBvc3RGb3JtKCRzdWJtaXRCdG4sIGdyaWQpKTtcblxuICAgIG1vZGFsLnNob3coKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBAcGFyYW0ge2pRdWVyeX0gJHN1Ym1pdEJ0blxuICAgKiBAcGFyYW0ge0dyaWR9IGdyaWRcbiAgICovXG4gIHBvc3RGb3JtKCRzdWJtaXRCdG4sIGdyaWQpIHtcbiAgICBjb25zdCAkZm9ybSA9ICQoYCMke2dyaWQuZ2V0SWQoKX1fZmlsdGVyX2Zvcm1gKTtcblxuICAgICRmb3JtLmF0dHIoJ2FjdGlvbicsICRzdWJtaXRCdG4uZGF0YSgnZm9ybS11cmwnKSk7XG4gICAgJGZvcm0uYXR0cignbWV0aG9kJywgJHN1Ym1pdEJ0bi5kYXRhKCdmb3JtLW1ldGhvZCcpKTtcbiAgICAkZm9ybS5zdWJtaXQoKTtcbiAgfVxufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vLi4vLi4vLi4vYmFjay1vZmZpY2UvdGhlbWVzL25ldy10aGVtZS9qcy9jb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL3N1Ym1pdC1idWxrLWFjdGlvbi1leHRlbnNpb24uanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmNvbnN0IHskfSA9IHdpbmRvdztcblxuLyoqXG4gKiBDbGFzcyBTdWJtaXRHcmlkQWN0aW9uRXh0ZW5zaW9uIGhhbmRsZXMgZ3JpZCBhY3Rpb24gc3VibWl0c1xuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBTdWJtaXRHcmlkQWN0aW9uRXh0ZW5zaW9uIHtcbiAgY29uc3RydWN0b3IoKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGV4dGVuZDogKGdyaWQpID0+IHRoaXMuZXh0ZW5kKGdyaWQpLFxuICAgIH07XG4gIH1cblxuICBleHRlbmQoZ3JpZCkge1xuICAgIGdyaWQuZ2V0SGVhZGVyQ29udGFpbmVyKCkub24oJ2NsaWNrJywgJy5qcy1ncmlkLWFjdGlvbi1zdWJtaXQtYnRuJywgKGV2ZW50KSA9PiB7XG4gICAgICB0aGlzLmhhbmRsZVN1Ym1pdChldmVudCwgZ3JpZCk7XG4gICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogSGFuZGxlIGdyaWQgYWN0aW9uIHN1Ym1pdC5cbiAgICogSXQgdXNlcyBncmlkIGZvcm0gdG8gc3VibWl0IGFjdGlvbnMuXG4gICAqXG4gICAqIEBwYXJhbSB7RXZlbnR9IGV2ZW50XG4gICAqIEBwYXJhbSB7R3JpZH0gZ3JpZFxuICAgKlxuICAgKiBAcHJpdmF0ZVxuICAgKi9cbiAgaGFuZGxlU3VibWl0KGV2ZW50LCBncmlkKSB7XG4gICAgY29uc3QgJHN1Ym1pdEJ0biA9ICQoZXZlbnQuY3VycmVudFRhcmdldCk7XG4gICAgY29uc3QgY29uZmlybU1lc3NhZ2UgPSAkc3VibWl0QnRuLmRhdGEoJ2NvbmZpcm0tbWVzc2FnZScpO1xuXG4gICAgaWYgKHR5cGVvZiBjb25maXJtTWVzc2FnZSAhPT0gJ3VuZGVmaW5lZCcgJiYgY29uZmlybU1lc3NhZ2UubGVuZ3RoID4gMCAmJiAhd2luZG93LmNvbmZpcm0oY29uZmlybU1lc3NhZ2UpKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgY29uc3QgJGZvcm0gPSAkKGAjJHtncmlkLmdldElkKCl9X2ZpbHRlcl9mb3JtYCk7XG5cbiAgICAkZm9ybS5hdHRyKCdhY3Rpb24nLCAkc3VibWl0QnRuLmRhdGEoJ3VybCcpKTtcbiAgICAkZm9ybS5hdHRyKCdtZXRob2QnLCAkc3VibWl0QnRuLmRhdGEoJ21ldGhvZCcpKTtcbiAgICAkZm9ybS5maW5kKGBpbnB1dFtuYW1lPVwiJHtncmlkLmdldElkKCl9W190b2tlbl1cIl1gKS52YWwoJHN1Ym1pdEJ0bi5kYXRhKCdjc3JmJykpO1xuICAgICRmb3JtLnN1Ym1pdCgpO1xuICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2NvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vc3VibWl0LWdyaWQtYWN0aW9uLWV4dGVuc2lvbi5qcyIsIi8qKlxuICogQ29weXJpZ2h0IHNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBQcmVzdGFTaG9wIGlzIGFuIEludGVybmF0aW9uYWwgUmVnaXN0ZXJlZCBUcmFkZW1hcmsgJiBQcm9wZXJ0eSBvZiBQcmVzdGFTaG9wIFNBXG4gKlxuICogTk9USUNFIE9GIExJQ0VOU0VcbiAqXG4gKiBUaGlzIHNvdXJjZSBmaWxlIGlzIHN1YmplY3QgdG8gdGhlIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqIHRoYXQgaXMgYnVuZGxlZCB3aXRoIHRoaXMgcGFja2FnZSBpbiB0aGUgZmlsZSBMSUNFTlNFLm1kLlxuICogSXQgaXMgYWxzbyBhdmFpbGFibGUgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIgYXQgdGhpcyBVUkw6XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjBcbiAqIElmIHlvdSBkaWQgbm90IHJlY2VpdmUgYSBjb3B5IG9mIHRoZSBsaWNlbnNlIGFuZCBhcmUgdW5hYmxlIHRvXG4gKiBvYnRhaW4gaXQgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIsIHBsZWFzZSBzZW5kIGFuIGVtYWlsXG4gKiB0byBsaWNlbnNlQHByZXN0YXNob3AuY29tIHNvIHdlIGNhbiBzZW5kIHlvdSBhIGNvcHkgaW1tZWRpYXRlbHkuXG4gKlxuICogRElTQ0xBSU1FUlxuICpcbiAqIERvIG5vdCBlZGl0IG9yIGFkZCB0byB0aGlzIGZpbGUgaWYgeW91IHdpc2ggdG8gdXBncmFkZSBQcmVzdGFTaG9wIHRvIG5ld2VyXG4gKiB2ZXJzaW9ucyBpbiB0aGUgZnV0dXJlLiBJZiB5b3Ugd2lzaCB0byBjdXN0b21pemUgUHJlc3RhU2hvcCBmb3IgeW91clxuICogbmVlZHMgcGxlYXNlIHJlZmVyIHRvIGh0dHBzOi8vZGV2ZG9jcy5wcmVzdGFzaG9wLmNvbS8gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gKlxuICogQGF1dGhvciAgICBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnMgPGNvbnRhY3RAcHJlc3Rhc2hvcC5jb20+XG4gKiBAY29weXJpZ2h0IFNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBAbGljZW5zZSAgIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMCBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKi9cblxuY29uc3QgeyR9ID0gd2luZG93O1xuXG4vKipcbiAqIENsYXNzIGlzIHJlc3BvbnNpYmxlIGZvciBoYW5kbGluZyBHcmlkIGV2ZW50c1xuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBHcmlkIHtcbiAgLyoqXG4gICAqIEdyaWQgaWRcbiAgICpcbiAgICogQHBhcmFtIHtzdHJpbmd9IGlkXG4gICAqL1xuICBjb25zdHJ1Y3RvcihpZCkge1xuICAgIHRoaXMuaWQgPSBpZDtcbiAgICB0aGlzLiRjb250YWluZXIgPSAkKGAjJHt0aGlzLmlkfV9ncmlkYCk7XG4gIH1cblxuICAvKipcbiAgICogR2V0IGdyaWQgaWRcbiAgICpcbiAgICogQHJldHVybnMge3N0cmluZ31cbiAgICovXG4gIGdldElkKCkge1xuICAgIHJldHVybiB0aGlzLmlkO1xuICB9XG5cbiAgLyoqXG4gICAqIEdldCBncmlkIGNvbnRhaW5lclxuICAgKlxuICAgKiBAcmV0dXJucyB7alF1ZXJ5fVxuICAgKi9cbiAgZ2V0Q29udGFpbmVyKCkge1xuICAgIHJldHVybiB0aGlzLiRjb250YWluZXI7XG4gIH1cblxuICAvKipcbiAgICogR2V0IGdyaWQgaGVhZGVyIGNvbnRhaW5lclxuICAgKlxuICAgKiBAcmV0dXJucyB7alF1ZXJ5fVxuICAgKi9cbiAgZ2V0SGVhZGVyQ29udGFpbmVyKCkge1xuICAgIHJldHVybiB0aGlzLiRjb250YWluZXIuY2xvc2VzdCgnLmpzLWdyaWQtcGFuZWwnKS5maW5kKCcuanMtZ3JpZC1oZWFkZXInKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBFeHRlbmQgZ3JpZCB3aXRoIGV4dGVybmFsIGV4dGVuc2lvbnNcbiAgICpcbiAgICogQHBhcmFtIHtvYmplY3R9IGV4dGVuc2lvblxuICAgKi9cbiAgYWRkRXh0ZW5zaW9uKGV4dGVuc2lvbikge1xuICAgIGV4dGVuc2lvbi5leHRlbmQodGhpcyk7XG4gIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuLy4uLy4uLy4uL2JhY2stb2ZmaWNlL3RoZW1lcy9uZXctdGhlbWUvanMvY29tcG9uZW50cy9ncmlkL2dyaWQuanMiLCIvKipcbiAqIDIwMDctMjAyMCBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnNcbiAqXG4gKiBOT1RJQ0UgT0YgTElDRU5TRVxuICpcbiAqIFRoaXMgc291cmNlIGZpbGUgaXMgc3ViamVjdCB0byB0aGUgQWNhZGVtaWMgRnJlZSBMaWNlbnNlIDMuMCAoQUZMLTMuMCkuXG4gKiBJdCBpcyBhbHNvIGF2YWlsYWJsZSB0aHJvdWdoIHRoZSB3b3JsZC13aWRlLXdlYiBhdCB0aGlzIFVSTDogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9BRkwtMy4wXG4gKi9cblxuaW1wb3J0IEdyaWQgZnJvbSAnQGNvbXBvbmVudHMvZ3JpZC9ncmlkJztcbmltcG9ydCBSZWxvYWRMaXN0QWN0aW9uRXh0ZW5zaW9uIGZyb20gJ0Bjb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL3JlbG9hZC1saXN0LWV4dGVuc2lvbic7XG5pbXBvcnQgRXhwb3J0VG9TcWxNYW5hZ2VyRXh0ZW5zaW9uIGZyb20gJ0Bjb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL2V4cG9ydC10by1zcWwtbWFuYWdlci1leHRlbnNpb24nO1xuaW1wb3J0IEZpbHRlcnNSZXNldEV4dGVuc2lvbiBmcm9tICdAY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9maWx0ZXJzLXJlc2V0LWV4dGVuc2lvbic7XG5pbXBvcnQgU29ydGluZ0V4dGVuc2lvbiBmcm9tICdAY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9zb3J0aW5nLWV4dGVuc2lvbic7XG5pbXBvcnQgTGlua1Jvd0FjdGlvbkV4dGVuc2lvbiBmcm9tICdAY29tcG9uZW50cy9ncmlkL2V4dGVuc2lvbi9saW5rLXJvdy1hY3Rpb24tZXh0ZW5zaW9uJztcbmltcG9ydCBTdWJtaXRHcmlkRXh0ZW5zaW9uIGZyb20gJ0Bjb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL3N1Ym1pdC1ncmlkLWFjdGlvbi1leHRlbnNpb24nO1xuaW1wb3J0IFN1Ym1pdEJ1bGtFeHRlbnNpb24gZnJvbSAnQGNvbXBvbmVudHMvZ3JpZC9leHRlbnNpb24vc3VibWl0LWJ1bGstYWN0aW9uLWV4dGVuc2lvbic7XG5pbXBvcnQgQnVsa0FjdGlvbkNoZWNrYm94RXh0ZW5zaW9uIGZyb20gJ0Bjb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL2J1bGstYWN0aW9uLWNoZWNrYm94LWV4dGVuc2lvbic7XG5pbXBvcnQgU3VibWl0Um93QWN0aW9uRXh0ZW5zaW9uIGZyb20gJ0Bjb21wb25lbnRzL2dyaWQvZXh0ZW5zaW9uL2FjdGlvbi9yb3cvc3VibWl0LXJvdy1hY3Rpb24tZXh0ZW5zaW9uJztcblxuY29uc3QgJCA9IHdpbmRvdy4kO1xuXG4kKCgpID0+IHtcbiAgY29uc3QgcXVvdGVzR3JpZCA9IG5ldyBHcmlkKCdjcmVhdGVpdF9hY2NvcmRpb24nKTtcblxuICBxdW90ZXNHcmlkLmFkZEV4dGVuc2lvbihuZXcgUmVsb2FkTGlzdEFjdGlvbkV4dGVuc2lvbigpKTtcbiAgcXVvdGVzR3JpZC5hZGRFeHRlbnNpb24obmV3IEV4cG9ydFRvU3FsTWFuYWdlckV4dGVuc2lvbigpKTtcbiAgcXVvdGVzR3JpZC5hZGRFeHRlbnNpb24obmV3IEZpbHRlcnNSZXNldEV4dGVuc2lvbigpKTtcbiAgcXVvdGVzR3JpZC5hZGRFeHRlbnNpb24obmV3IFNvcnRpbmdFeHRlbnNpb24oKSk7XG4gIHF1b3Rlc0dyaWQuYWRkRXh0ZW5zaW9uKG5ldyBMaW5rUm93QWN0aW9uRXh0ZW5zaW9uKCkpO1xuICBxdW90ZXNHcmlkLmFkZEV4dGVuc2lvbihuZXcgU3VibWl0R3JpZEV4dGVuc2lvbigpKTtcbiAgcXVvdGVzR3JpZC5hZGRFeHRlbnNpb24obmV3IFN1Ym1pdEJ1bGtFeHRlbnNpb24oKSk7XG4gIHF1b3Rlc0dyaWQuYWRkRXh0ZW5zaW9uKG5ldyBCdWxrQWN0aW9uQ2hlY2tib3hFeHRlbnNpb24oKSk7XG4gIHF1b3Rlc0dyaWQuYWRkRXh0ZW5zaW9uKG5ldyBTdWJtaXRSb3dBY3Rpb25FeHRlbnNpb24oKSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2NyZWF0ZWl0X2FjY29yZGlvbi9pbmRleC5qcyIsIi8qKlxuICogQ29weXJpZ2h0IHNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBQcmVzdGFTaG9wIGlzIGFuIEludGVybmF0aW9uYWwgUmVnaXN0ZXJlZCBUcmFkZW1hcmsgJiBQcm9wZXJ0eSBvZiBQcmVzdGFTaG9wIFNBXG4gKlxuICogTk9USUNFIE9GIExJQ0VOU0VcbiAqXG4gKiBUaGlzIHNvdXJjZSBmaWxlIGlzIHN1YmplY3QgdG8gdGhlIE9wZW4gU29mdHdhcmUgTGljZW5zZSAoT1NMIDMuMClcbiAqIHRoYXQgaXMgYnVuZGxlZCB3aXRoIHRoaXMgcGFja2FnZSBpbiB0aGUgZmlsZSBMSUNFTlNFLm1kLlxuICogSXQgaXMgYWxzbyBhdmFpbGFibGUgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIgYXQgdGhpcyBVUkw6XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjBcbiAqIElmIHlvdSBkaWQgbm90IHJlY2VpdmUgYSBjb3B5IG9mIHRoZSBsaWNlbnNlIGFuZCBhcmUgdW5hYmxlIHRvXG4gKiBvYnRhaW4gaXQgdGhyb3VnaCB0aGUgd29ybGQtd2lkZS13ZWIsIHBsZWFzZSBzZW5kIGFuIGVtYWlsXG4gKiB0byBsaWNlbnNlQHByZXN0YXNob3AuY29tIHNvIHdlIGNhbiBzZW5kIHlvdSBhIGNvcHkgaW1tZWRpYXRlbHkuXG4gKlxuICogRElTQ0xBSU1FUlxuICpcbiAqIERvIG5vdCBlZGl0IG9yIGFkZCB0byB0aGlzIGZpbGUgaWYgeW91IHdpc2ggdG8gdXBncmFkZSBQcmVzdGFTaG9wIHRvIG5ld2VyXG4gKiB2ZXJzaW9ucyBpbiB0aGUgZnV0dXJlLiBJZiB5b3Ugd2lzaCB0byBjdXN0b21pemUgUHJlc3RhU2hvcCBmb3IgeW91clxuICogbmVlZHMgcGxlYXNlIHJlZmVyIHRvIGh0dHBzOi8vZGV2ZG9jcy5wcmVzdGFzaG9wLmNvbS8gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gKlxuICogQGF1dGhvciAgICBQcmVzdGFTaG9wIFNBIGFuZCBDb250cmlidXRvcnMgPGNvbnRhY3RAcHJlc3Rhc2hvcC5jb20+XG4gKiBAY29weXJpZ2h0IFNpbmNlIDIwMDcgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzXG4gKiBAbGljZW5zZSAgIGh0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvT1NMLTMuMCBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKi9cblxuLyoqXG4gKiBTZW5kIGEgUG9zdCBSZXF1ZXN0IHRvIHJlc2V0IHNlYXJjaCBBY3Rpb24uXG4gKi9cblxuY29uc3QgeyR9ID0gd2luZG93O1xuXG5jb25zdCBpbml0ID0gZnVuY3Rpb24gcmVzZXRTZWFyY2godXJsLCByZWRpcmVjdFVybCkge1xuICAkLnBvc3QodXJsKS50aGVuKCgpID0+IHdpbmRvdy5sb2NhdGlvbi5hc3NpZ24ocmVkaXJlY3RVcmwpKTtcbn07XG5cbmV4cG9ydCBkZWZhdWx0IGluaXQ7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi8uLi8uLi8uLi9iYWNrLW9mZmljZS90aGVtZXMvbmV3LXRoZW1lL2pzL2FwcC91dGlscy9yZXNldF9zZWFyY2guanMiLCIvKipcbiAqIENvcHlyaWdodCBzaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogUHJlc3RhU2hvcCBpcyBhbiBJbnRlcm5hdGlvbmFsIFJlZ2lzdGVyZWQgVHJhZGVtYXJrICYgUHJvcGVydHkgb2YgUHJlc3RhU2hvcCBTQVxuICpcbiAqIE5PVElDRSBPRiBMSUNFTlNFXG4gKlxuICogVGhpcyBzb3VyY2UgZmlsZSBpcyBzdWJqZWN0IHRvIHRoZSBPcGVuIFNvZnR3YXJlIExpY2Vuc2UgKE9TTCAzLjApXG4gKiB0aGF0IGlzIGJ1bmRsZWQgd2l0aCB0aGlzIHBhY2thZ2UgaW4gdGhlIGZpbGUgTElDRU5TRS5tZC5cbiAqIEl0IGlzIGFsc28gYXZhaWxhYmxlIHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViIGF0IHRoaXMgVVJMOlxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9PU0wtMy4wXG4gKiBJZiB5b3UgZGlkIG5vdCByZWNlaXZlIGEgY29weSBvZiB0aGUgbGljZW5zZSBhbmQgYXJlIHVuYWJsZSB0b1xuICogb2J0YWluIGl0IHRocm91Z2ggdGhlIHdvcmxkLXdpZGUtd2ViLCBwbGVhc2Ugc2VuZCBhbiBlbWFpbFxuICogdG8gbGljZW5zZUBwcmVzdGFzaG9wLmNvbSBzbyB3ZSBjYW4gc2VuZCB5b3UgYSBjb3B5IGltbWVkaWF0ZWx5LlxuICpcbiAqIERJU0NMQUlNRVJcbiAqXG4gKiBEbyBub3QgZWRpdCBvciBhZGQgdG8gdGhpcyBmaWxlIGlmIHlvdSB3aXNoIHRvIHVwZ3JhZGUgUHJlc3RhU2hvcCB0byBuZXdlclxuICogdmVyc2lvbnMgaW4gdGhlIGZ1dHVyZS4gSWYgeW91IHdpc2ggdG8gY3VzdG9taXplIFByZXN0YVNob3AgZm9yIHlvdXJcbiAqIG5lZWRzIHBsZWFzZSByZWZlciB0byBodHRwczovL2RldmRvY3MucHJlc3Rhc2hvcC5jb20vIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICpcbiAqIEBhdXRob3IgICAgUHJlc3RhU2hvcCBTQSBhbmQgQ29udHJpYnV0b3JzIDxjb250YWN0QHByZXN0YXNob3AuY29tPlxuICogQGNvcHlyaWdodCBTaW5jZSAyMDA3IFByZXN0YVNob3AgU0EgYW5kIENvbnRyaWJ1dG9yc1xuICogQGxpY2Vuc2UgICBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL09TTC0zLjAgT3BlbiBTb2Z0d2FyZSBMaWNlbnNlIChPU0wgMy4wKVxuICovXG5cbmNvbnN0IHskfSA9IHdpbmRvdztcblxuLyoqXG4gKiBNYWtlcyBhIHRhYmxlIHNvcnRhYmxlIGJ5IGNvbHVtbnMuXG4gKiBUaGlzIGZvcmNlcyBhIHBhZ2UgcmVsb2FkIHdpdGggbW9yZSBxdWVyeSBwYXJhbWV0ZXJzLlxuICovXG5jbGFzcyBUYWJsZVNvcnRpbmcge1xuICAvKipcbiAgICogQHBhcmFtIHtqUXVlcnl9IHRhYmxlXG4gICAqL1xuICBjb25zdHJ1Y3Rvcih0YWJsZSkge1xuICAgIHRoaXMuc2VsZWN0b3IgPSAnLnBzLXNvcnRhYmxlLWNvbHVtbic7XG4gICAgdGhpcy5jb2x1bW5zID0gJCh0YWJsZSkuZmluZCh0aGlzLnNlbGVjdG9yKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBBdHRhY2hlcyB0aGUgbGlzdGVuZXJzXG4gICAqL1xuICBhdHRhY2goKSB7XG4gICAgdGhpcy5jb2x1bW5zLm9uKCdjbGljaycsIChlKSA9PiB7XG4gICAgICBjb25zdCAkY29sdW1uID0gJChlLmRlbGVnYXRlVGFyZ2V0KTtcbiAgICAgIHRoaXMuc29ydEJ5Q29sdW1uKCRjb2x1bW4sIHRoaXMuZ2V0VG9nZ2xlZFNvcnREaXJlY3Rpb24oJGNvbHVtbikpO1xuICAgIH0pO1xuICB9XG5cbiAgLyoqXG4gICAqIFNvcnQgdXNpbmcgYSBjb2x1bW4gbmFtZVxuICAgKiBAcGFyYW0ge3N0cmluZ30gY29sdW1uTmFtZVxuICAgKiBAcGFyYW0ge3N0cmluZ30gZGlyZWN0aW9uIFwiYXNjXCIgb3IgXCJkZXNjXCJcbiAgICovXG4gIHNvcnRCeShjb2x1bW5OYW1lLCBkaXJlY3Rpb24pIHtcbiAgICBjb25zdCAkY29sdW1uID0gdGhpcy5jb2x1bW5zLmlzKGBbZGF0YS1zb3J0LWNvbC1uYW1lPVwiJHtjb2x1bW5OYW1lfVwiXWApO1xuXG4gICAgaWYgKCEkY29sdW1uKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoYENhbm5vdCBzb3J0IGJ5IFwiJHtjb2x1bW5OYW1lfVwiOiBpbnZhbGlkIGNvbHVtbmApO1xuICAgIH1cblxuICAgIHRoaXMuc29ydEJ5Q29sdW1uKCRjb2x1bW4sIGRpcmVjdGlvbik7XG4gIH1cblxuICAvKipcbiAgICogU29ydCB1c2luZyBhIGNvbHVtbiBlbGVtZW50XG4gICAqIEBwYXJhbSB7alF1ZXJ5fSBjb2x1bW5cbiAgICogQHBhcmFtIHtzdHJpbmd9IGRpcmVjdGlvbiBcImFzY1wiIG9yIFwiZGVzY1wiXG4gICAqIEBwcml2YXRlXG4gICAqL1xuICBzb3J0QnlDb2x1bW4oY29sdW1uLCBkaXJlY3Rpb24pIHtcbiAgICB3aW5kb3cubG9jYXRpb24gPSB0aGlzLmdldFVybChcbiAgICAgIGNvbHVtbi5kYXRhKCdzb3J0Q29sTmFtZScpLFxuICAgICAgKGRpcmVjdGlvbiA9PT0gJ2Rlc2MnKSA/ICdkZXNjJyA6ICdhc2MnLFxuICAgICAgY29sdW1uLmRhdGEoJ3NvcnRQcmVmaXgnKSxcbiAgICApO1xuICB9XG5cbiAgLyoqXG4gICAqIFJldHVybnMgdGhlIGludmVydGVkIGRpcmVjdGlvbiB0byBzb3J0IGFjY29yZGluZyB0byB0aGUgY29sdW1uJ3MgY3VycmVudCBvbmVcbiAgICogQHBhcmFtIHtqUXVlcnl9IGNvbHVtblxuICAgKiBAcmV0dXJuIHtzdHJpbmd9XG4gICAqIEBwcml2YXRlXG4gICAqL1xuICBnZXRUb2dnbGVkU29ydERpcmVjdGlvbihjb2x1bW4pIHtcbiAgICByZXR1cm4gY29sdW1uLmRhdGEoJ3NvcnREaXJlY3Rpb24nKSA9PT0gJ2FzYycgPyAnZGVzYycgOiAnYXNjJztcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIHRoZSB1cmwgZm9yIHRoZSBzb3J0ZWQgdGFibGVcbiAgICogQHBhcmFtIHtzdHJpbmd9IGNvbE5hbWVcbiAgICogQHBhcmFtIHtzdHJpbmd9IGRpcmVjdGlvblxuICAgKiBAcGFyYW0ge3N0cmluZ30gcHJlZml4XG4gICAqIEByZXR1cm4ge3N0cmluZ31cbiAgICogQHByaXZhdGVcbiAgICovXG4gIGdldFVybChjb2xOYW1lLCBkaXJlY3Rpb24sIHByZWZpeCkge1xuICAgIGNvbnN0IHVybCA9IG5ldyBVUkwod2luZG93LmxvY2F0aW9uLmhyZWYpO1xuICAgIGNvbnN0IHBhcmFtcyA9IHVybC5zZWFyY2hQYXJhbXM7XG5cbiAgICBpZiAocHJlZml4KSB7XG4gICAgICBwYXJhbXMuc2V0KGAke3ByZWZpeH1bb3JkZXJCeV1gLCBjb2xOYW1lKTtcbiAgICAgIHBhcmFtcy5zZXQoYCR7cHJlZml4fVtzb3J0T3JkZXJdYCwgZGlyZWN0aW9uKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcGFyYW1zLnNldCgnb3JkZXJCeScsIGNvbE5hbWUpO1xuICAgICAgcGFyYW1zLnNldCgnc29ydE9yZGVyJywgZGlyZWN0aW9uKTtcbiAgICB9XG5cbiAgICByZXR1cm4gdXJsLnRvU3RyaW5nKCk7XG4gIH1cbn1cblxuZXhwb3J0IGRlZmF1bHQgVGFibGVTb3J0aW5nO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vLi4vLi4vLi4vYmFjay1vZmZpY2UvdGhlbWVzL25ldy10aGVtZS9qcy9hcHAvdXRpbHMvdGFibGUtc29ydGluZy5qcyJdLCJzb3VyY2VSb290IjoiIn0=