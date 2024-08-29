/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/games/edit.js":
/*!***************************!*\
  !*** ./src/games/edit.js ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./src/games/editor.scss");

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */



/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
function Edit({
  attributes,
  setAttributes
}) {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...(0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.InspectorControls, {
    key: "settings"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Next Games / Last Results of the club', 'rmse-vat-results')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "instructions"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'rmse-vat-results')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Flex, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexBlock, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
    isShiftStepEnabled: true,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Last Results', 'rmse-vat-results'),
    shiftStep: 5,
    value: attributes.results,
    onChange: val => setAttributes({
      results: parseInt(val)
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FlexBlock, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
    isShiftStepEnabled: true,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Scheduled Games', 'rmse-vat-results'),
    shiftStep: 5,
    value: attributes.scheduled,
    onChange: val => setAttributes({
      scheduled: parseInt(val)
    })
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date Format', 'rmse-vat-results'),
    value: attributes.dateformat,
    onChange: val => setAttributes({
      dateformat: val
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show Header?', 'rmse-vat-results'),
    checked: attributes.header,
    onChange: val => setAttributes({
      header: val
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show Group?', 'rmse-vat-results'),
    checked: attributes.group,
    onChange: val => setAttributes({
      group: val
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show Venue?', 'rmse-vat-results'),
    checked: attributes.venue,
    onChange: val => setAttributes({
      venue: val
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show Results?', 'rmse-vat-results'),
    checked: attributes.with_result,
    onChange: val => setAttributes({
      with_result: val
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("table", {
    className: "rmse-vat-results-table"
  }, attributes.header && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("thead", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-date"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date / Time', 'rmse-vat-results')), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-group"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Group', 'rmse-vat-results')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-hometeam"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Home', 'rmse-vat-results')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-guestteam"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Guest', 'rmse-vat-results')), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Venue', 'rmse-vat-results')), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", {
    className: "rmse-vat-results-result"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Result', 'rmse-vat-results')))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tbody", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-played rmse-vat-results-game-club-internal"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Club Internal"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  }, "33:30 (16:17)")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-played  rmse-vat-results-game-win"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Sieg"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  }, "33:30 (16:17)")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-played  rmse-vat-results-game-draw"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Unentschieden"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "\xC7"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  }, "33:33 (16:17)")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-played  rmse-vat-results-game-loss"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Niederlage"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team B"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team A"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  }, "30:33 (16:17)")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-planned rmse-vat-results-game-home"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Heimspiel"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-planned rmse-vat-results-game-away"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Ausw\xE4rtsspiel"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-planned rmse-vat-results-game-type-cup"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-tygrouppe"
  }, "Cupspiel"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
    className: "rmse-vat-results-game-planned rmse-vat-results-game-type-ms"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-date"
  }, "21.10.23 14:25"), attributes.group && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-group"
  }, "Meisterschaftsspiel"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-hometeam"
  }, "Team A"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-guestteam"
  }, "Team B"), attributes.venue && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-venue"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#"
  }, "Halle X")), attributes.with_result && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
    className: "rmse-vat-results-result"
  })))));
}

/***/ }),

/***/ "./src/games/index.js":
/*!****************************!*\
  !*** ./src/games/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/games/style.scss");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/games/edit.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/games/block.json");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * Internal dependencies
 */



/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  /**
   * @see ./edit.js
   */
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"]
});

/***/ }),

/***/ "./src/games/editor.scss":
/*!*******************************!*\
  !*** ./src/games/editor.scss ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/games/style.scss":
/*!******************************!*\
  !*** ./src/games/style.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./src/games/block.json":
/*!******************************!*\
  !*** ./src/games/block.json ***!
  \******************************/
/***/ ((module) => {

module.exports = JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"rmse-vat-results/games","version":"1.0.7","title":"Club Games and Results","category":"widgets","icon":"list-view","description":"Show the next games / last results of the club as a whole","supports":{"align":true},"attributes":{"results":{"type":"integer","default":10},"scheduled":{"type":"integer","default":10},"header":{"type":"boolean","default":true},"venue":{"type":"boolean","default":true},"group":{"type":"boolean","default":true},"with_result":{"type":"boolean","default":true},"dateformat":{"type":"string","default":"d.m.y H:i"}},"textdomain":"rmse-vat-results","editorScript":"file:./index.js","editorStyle":"file:./index.css","viewScript":"file:./view.js","render":"file:./render.php"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"games/index": 0,
/******/ 			"games/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkrmse_vat_results"] = self["webpackChunkrmse_vat_results"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["games/style-index"], () => (__webpack_require__("./src/games/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map