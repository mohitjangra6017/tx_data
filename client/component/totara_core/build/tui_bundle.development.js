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
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/totara_core/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/totara_core/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!****************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./adder/LearningAdder\": \"./client/component/totara_core/src/components/adder/LearningAdder.vue\",\n\t\"./adder/LearningAdder.vue\": \"./client/component/totara_core/src/components/adder/LearningAdder.vue\",\n\t\"./performelement_linked_review/learning/AdminEdit\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue\",\n\t\"./performelement_linked_review/learning/AdminEdit.vue\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue\",\n\t\"./performelement_linked_review/learning/AdminView\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue\",\n\t\"./performelement_linked_review/learning/AdminView.vue\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue\",\n\t\"./performelement_linked_review/learning/ParticipantContent\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue\",\n\t\"./performelement_linked_review/learning/ParticipantContent.vue\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue\",\n\t\"./performelement_linked_review/learning/ParticipantContentPicker\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue\",\n\t\"./performelement_linked_review/learning/ParticipantContentPicker.vue\": \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/totara_core/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/totara_core/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/totara_core/src/components/adder/LearningAdder.vue":
/*!*****************************************************************************!*\
  !*** ./client/component/totara_core/src/components/adder/LearningAdder.vue ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LearningAdder.vue?vue&type=template&id=26f4257e& */ \"./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e&\");\n/* harmony import */ var _LearningAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LearningAdder.vue?vue&type=script&lang=js& */ \"./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _LearningAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _LearningAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _LearningAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_LearningAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_core/src/components/adder/LearningAdder.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LearningAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LearningAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LearningAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./LearningAdder.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LearningAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e&":
/*!************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e& ***!
  \************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LearningAdder.vue?vue&type=template&id=26f4257e& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LearningAdder_vue_vue_type_template_id_26f4257e___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue":
/*!*********************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue ***!
  \*********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdminEdit.vue?vue&type=template&id=65552a22& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22&\");\n/* harmony import */ var _AdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdminEdit.vue?vue&type=script&lang=js& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _AdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AdminEdit.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22&":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22& ***!
  \****************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminEdit.vue?vue&type=template&id=65552a22& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminEdit_vue_vue_type_template_id_65552a22___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue":
/*!*********************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue ***!
  \*********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdminView.vue?vue&type=template&id=95c11bec& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec&\");\n/* harmony import */ var _AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdminView.vue?vue&type=script&lang=js& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./AdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AdminView.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec&":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec& ***!
  \****************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminView.vue?vue&type=template&id=95c11bec& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_95c11bec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue":
/*!******************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=template&id=1ab07040& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040&\");\n/* harmony import */ var _ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=script&lang=js& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ParticipantContent_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ParticipantContent_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ParticipantContent_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ParticipantContent.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContent.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040&":
/*!*************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040& ***!
  \*************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContent.vue?vue&type=template&id=1ab07040& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_1ab07040___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue":
/*!************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue ***!
  \************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=template&id=528d0024& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024&\");\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=script&lang=js& */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ParticipantContentPicker.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024&":
/*!*******************************************************************************************************************************************************!*\
  !*** ./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024& ***!
  \*******************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContentPicker.vue?vue&type=template&id=528d0024& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_528d0024___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_core/src/tui.json":
/*!***************************************************!*\
  !*** ./client/component/totara_core/src/tui.json ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"totara_core\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"totara_core\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"totara_core\")\ntui._bundle.addModulesFromContext(\"totara_core/components\", __webpack_require__(\"./client/component/totara_core/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/totara_core/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"core\": [\n    \"no\",\n    \"yes\",\n    \"xpercent\"\n  ],\n  \"core_completion\": [\n    \"completed\",\n    \"inprogress\",\n    \"statusnotstarted\",\n    \"statusnottracked\"\n  ],\n  \"totara_core\": [\n    \"all\",\n    \"filter_learning\",\n    \"filter_learning_search_label\",\n    \"header_learning_name\",\n    \"header_learning_type\",\n    \"header_learning_progress\",\n    \"learning_type_certification\",\n    \"learning_type_course\",\n    \"learning_type_program\",\n    \"learning_type_unknown\",\n    \"search\",\n    \"select_learning\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_core\": [\n    \"example_learning_fullname\",\n    \"example_learning_description\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"core_completion\": [\"statusnottracked\"],\n  \"totara_core\": [\n    \"learning_type_certification\",\n    \"learning_type_course\",\n    \"learning_type_program\",\n    \"learning_type_unknown\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_core\": [\n    \"add_learning\",\n    \"awaiting_selection_text\",\n    \"remove_learning\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/adder/Adder */ \"tui/components/adder/Adder\");\n/* harmony import */ var tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/datatable/Cell */ \"tui/components/datatable/Cell\");\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/filters/FilterBar */ \"tui/components/filters/FilterBar\");\n/* harmony import */ var tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/datatable/HeaderCell */ \"tui/components/datatable/HeaderCell\");\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/filters/SearchFilter */ \"tui/components/filters/SearchFilter\");\n/* harmony import */ var tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/filters/SelectFilter */ \"tui/components/filters/SelectFilter\");\n/* harmony import */ var tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tui/components/datatable/SelectTable */ \"tui/components/datatable/SelectTable\");\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var totara_core_graphql_user_learning_items__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! totara_core/graphql/user_learning_items */ \"./server/totara/core/webapi/ajax/user_learning_items.graphql\");\n/* harmony import */ var totara_core_graphql_user_learning_items_selected__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! totara_core/graphql/user_learning_items_selected */ \"./server/totara/core/webapi/ajax/user_learning_items_selected.graphql\");\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! tui/util */ \"tui/util\");\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(tui_util__WEBPACK_IMPORTED_MODULE_9__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n// Components\n\n\n\n\n\n\n\n// Queries\n\n\n\n\nconst LEARNING_TYPE_DEFAULT = 'COURSE';\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Adder: (tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0___default()),\n    Cell: (tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1___default()),\n    FilterBar: (tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2___default()),\n    HeaderCell: (tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3___default()),\n    SearchFilter: (tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4___default()),\n    SelectFilter: (tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5___default()),\n    SelectTable: (tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6___default()),\n  },\n\n  props: {\n    existingItems: {\n      type: Array,\n      default: () => [],\n    },\n    open: Boolean,\n    customQuery: Object,\n    /**\n     * custom query key needs to be passed\n     * if customQuery is passed\n     */\n    customQueryKey: String,\n    // Display loading spinner on Add button\n    showLoadingBtn: Boolean,\n    userId: Number,\n  },\n\n  data() {\n    return {\n      learningItems: null,\n      learningSelectedItems: [],\n      searchFilter: '',\n      nextPage: false,\n      skipQueries: true,\n      searchDebounce: '',\n      learningTypeFilter: LEARNING_TYPE_DEFAULT,\n      learningTypeFilterOptions: [\n        {\n          id: 'COURSE',\n          label: this.$str('learning_type_course', 'totara_core'),\n        },\n        {\n          id: 'CERTIFICATION',\n          label: this.$str('learning_type_certification', 'totara_core'),\n        },\n        {\n          id: 'PROGRAM',\n          label: this.$str('learning_type_program', 'totara_core'),\n        },\n      ],\n      progressFilter: null,\n      progressOptions: [\n        { id: null, label: this.$str('all', 'totara_core') },\n        {\n          id: 'COMPLETED',\n          label: this.$str('completed', 'core_completion'),\n        },\n        {\n          id: 'IN_PROGRESS',\n          label: this.$str('inprogress', 'core_completion'),\n        },\n        {\n          id: 'NOT_STARTED',\n          label: this.$str('statusnotstarted', 'core_completion'),\n        },\n        {\n          id: 'NOT_TRACKED',\n          label: this.$str('statusnottracked', 'core_completion'),\n        },\n      ],\n      // Default to course\n      typeValue: 'COURSE',\n    };\n  },\n\n  watch: {\n    /**\n     * On opening of adder, unblock query\n     *\n     */\n    open() {\n      if (this.open) {\n        this.searchDebounce = '';\n        this.skipQueries = false;\n      } else {\n        this.skipQueries = true;\n      }\n    },\n\n    searchDebounce(newValue) {\n      this.updateFilterDebounced(newValue);\n    },\n  },\n\n  /**\n   * Apollo queries have been registered here to provide support for custom queries\n   */\n  created() {\n    /**\n     * All user learning query\n     *\n     */\n    this.$apollo.addSmartQuery('learningItems', {\n      query: this.customQuery ? this.customQuery : totara_core_graphql_user_learning_items__WEBPACK_IMPORTED_MODULE_7__[\"default\"],\n      skip() {\n        return this.skipQueries;\n      },\n      variables() {\n        return {\n          input: {\n            filters: {\n              search: this.searchFilter,\n              type: this.learningTypeFilter,\n              progress: this.progressFilter,\n            },\n            user_id: this.userId,\n          },\n        };\n      },\n      update({\n        [this.customQueryKey\n          ? this.customQueryKey\n          : 'totara_core_user_learning_items']: learning,\n      }) {\n        this.nextPage = learning.next_cursor ? learning.next_cursor : false;\n        return learning;\n      },\n    });\n\n    /**\n     * Selected learning items query\n     *\n     */\n    this.$apollo.addSmartQuery('selectedLearning', {\n      query: this.customQuery ? this.customQuery : totara_core_graphql_user_learning_items_selected__WEBPACK_IMPORTED_MODULE_8__[\"default\"],\n      skip() {\n        return this.skipQueries;\n      },\n      variables() {\n        return {\n          input: {\n            filters: {\n              ids: [],\n            },\n            user_id: this.userId,\n          },\n        };\n      },\n      update({\n        [this.customQueryKey\n          ? this.customQueryKey\n          : 'totara_core_user_learning_items_selected']: selectedLearning,\n      }) {\n        this.learningSelectedItems = selectedLearning.items;\n        return selectedLearning;\n      },\n    });\n  },\n\n  methods: {\n    /**\n     * Load addition items and append to list\n     *\n     */\n    async loadMoreItems() {\n      if (!this.nextPage) {\n        return;\n      }\n\n      this.$apollo.queries.learningItems.fetchMore({\n        variables: {\n          input: {\n            cursor: this.nextPage,\n            filters: {\n              search: this.searchFilter,\n              type: this.learningTypeFilter,\n              progress: this.progressFilter,\n            },\n            user_id: this.userId,\n          },\n        },\n\n        updateQuery: (previousResult, { fetchMoreResult }) => {\n          const oldData = previousResult.totara_core_user_learning_items;\n          const newData = fetchMoreResult.totara_core_user_learning_items;\n          const newList = oldData.items.concat(newData.items);\n\n          return {\n            [this.customQueryKey\n              ? this.customQueryKey\n              : 'totara_core_user_learning_items']: {\n              items: newList,\n              next_cursor: newData.next_cursor,\n            },\n          };\n        },\n      });\n    },\n\n    /**\n     * Close the adder, returning the selected items data\n     *\n     * @param {Array} selection\n     */\n    async closeWithData(selection) {\n      let data;\n\n      this.$emit('add-button-clicked');\n\n      try {\n        data = await this.updateSelectedItems(selection);\n      } catch (error) {\n        console.error(error);\n        return;\n      }\n      this.$emit('added', { ids: selection, data: data });\n    },\n\n    /**\n     * Close adder without saving\n     */\n    closeModal() {\n      this.learningTypeFilter = LEARNING_TYPE_DEFAULT;\n      this.progressFilter = null;\n      this.$emit('cancel');\n    },\n\n    /**\n     * Update the selected items data\n     *\n     * @param {Array} selection\n     */\n    async updateSelectedItems(selection) {\n      const numberOfItems = selection.length;\n\n      try {\n        await this.$apollo.queries.selectedLearning.refetch({\n          input: {\n            filters: {\n              ids: selection,\n            },\n            result_size: numberOfItems,\n            user_id: this.userId,\n          },\n        });\n      } catch (error) {\n        console.error(error);\n      }\n      return this.learningSelectedItems;\n    },\n\n    /**\n     * Update the search filter (which re-triggers the query) if the user stopped typing >500 milliseconds ago.\n     *\n     * @param {String} input Value from search filter input\n     */\n    updateFilterDebounced: Object(tui_util__WEBPACK_IMPORTED_MODULE_9__[\"debounce\"])(function(input) {\n      this.searchFilter = input;\n    }, 500),\n\n    /**\n     * Returns the human readable name for the learning type\n     *\n     * @returns {String}\n     */\n    typeName(type) {\n      if (!type) {\n        return this.$str('learning_type_unknown', 'totara_core');\n      }\n\n      let typeName = this.$str('learning_type_' + type, 'totara_core');\n\n      if (!typeName) {\n        typeName = this.$str('learning_type_unknown', 'totara_core');\n      }\n\n      return typeName;\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {},\n\n  inheritAttrs: false,\n\n  props: {\n    relationships: Array,\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var totara_core_components_performelement_linked_review_learning_ParticipantContent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! totara_core/components/performelement_linked_review/learning/ParticipantContent */ \"totara_core/components/performelement_linked_review/learning/ParticipantContent\");\n/* harmony import */ var totara_core_components_performelement_linked_review_learning_ParticipantContent__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(totara_core_components_performelement_linked_review_learning_ParticipantContent__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ParticipantContent: (totara_core_components_performelement_linked_review_learning_ParticipantContent__WEBPACK_IMPORTED_MODULE_0___default()),\n  },\n\n  props: {\n    data: {\n      type: Object,\n      required: true,\n    },\n  },\n\n  methods: {\n    /**\n     * Set placeholder data for preview view\n     *\n     */\n    getPreviewData() {\n      return {\n        fullname: this.$str('example_learning_fullname', 'totara_core'),\n        description: this.$str('example_learning_description', 'totara_core'),\n        image_src: this.data.content_type_settings.default_image,\n        itemtype: 'course',\n        progress: 25,\n      };\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/card/Card */ \"tui/components/card/Card\");\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_charts_components_PercentageDoughnut__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui_charts/components/PercentageDoughnut */ \"tui_charts/components/PercentageDoughnut\");\n/* harmony import */ var tui_charts_components_PercentageDoughnut__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_charts_components_PercentageDoughnut__WEBPACK_IMPORTED_MODULE_1__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Card: (tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default()),\n    PercentageDoughnut: (tui_charts_components_PercentageDoughnut__WEBPACK_IMPORTED_MODULE_1___default()),\n  },\n\n  /**\n   * These props are setup in ParticipantContentPicker.\n   */\n  props: {\n    content: {\n      type: Object,\n    },\n    createdAt: String,\n    fromPrint: Boolean,\n    isExternalParticipant: Boolean,\n    preview: Boolean,\n    subjectUser: Object,\n  },\n\n  computed: {\n    chartProps() {\n      return {\n        percentage: this.content.progress,\n        showPercentage: true,\n        percentageFontSize: 13,\n        cutout: 80,\n        square: true,\n      };\n    },\n\n    /**\n     * Returns the human readable name for the learning type\n     *\n     * @returns {String}\n     */\n    typeName() {\n      if (!this.content.itemtype) {\n        return this.$str('learning_type_unknown', 'totara_core');\n      }\n\n      let typeName = this.$str(\n        'learning_type_' + this.content.itemtype,\n        'totara_core'\n      );\n\n      if (!typeName) {\n        typeName = this.$str('learning_type_unknown', 'totara_core');\n      }\n\n      return typeName;\n    },\n  },\n\n  methods: {\n    open() {\n      window.open(this.content.url_view);\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var totara_core_components_adder_LearningAdder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! totara_core/components/adder/LearningAdder */ \"totara_core/components/adder/LearningAdder\");\n/* harmony import */ var totara_core_components_adder_LearningAdder__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(totara_core_components_adder_LearningAdder__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! performelement_linked_review/components/SelectContent */ \"performelement_linked_review/components/SelectContent\");\n/* harmony import */ var performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    LearningAdder: (totara_core_components_adder_LearningAdder__WEBPACK_IMPORTED_MODULE_0___default()),\n    SelectContent: (performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1___default()),\n  },\n\n  props: {\n    canShowAdder: {\n      type: Boolean,\n      required: true,\n    },\n    coreRelationship: {\n      type: Array,\n      required: true,\n    },\n    isDraft: Boolean,\n    participantInstanceId: {\n      type: [String, Number],\n      required: true,\n    },\n    previewComponent: [Function, Object],\n    required: Boolean,\n    sectionElementId: String,\n    subjectUser: Object,\n    userId: Number,\n  },\n\n  methods: {\n    /**\n     * Get adder component\n     *\n     * @return {Object}\n     */\n    getAdder() {\n      return totara_core_components_adder_LearningAdder__WEBPACK_IMPORTED_MODULE_0___default.a;\n    },\n\n    /**\n     * Get data for learning preview component\n     *\n     * @param {Object} values\n     * @return {Object}\n     */\n    getItemData(values) {\n      // For now we don't need to map any values\n      return values;\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/adder/LearningAdder.vue?vue&type=template&id=26f4257e& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Adder',{attrs:{\"open\":_vm.open,\"title\":_vm.$str('select_learning', 'totara_core'),\"existing-items\":_vm.existingItems,\"loading\":_vm.$apollo.loading,\"show-load-more\":_vm.nextPage,\"show-loading-btn\":_vm.showLoadingBtn},on:{\"added\":function($event){return _vm.closeWithData($event)},\"cancel\":_vm.closeModal,\"load-more\":_vm.loadMoreItems,\"selected-tab-active\":function($event){return _vm.updateSelectedItems($event)}},scopedSlots:_vm._u([{key:\"browse-filters\",fn:function(){return [_c('FilterBar',{attrs:{\"has-top-bar\":false,\"title\":_vm.$str('filter_learning', 'totara_core')},scopedSlots:_vm._u([{key:\"filters-left\",fn:function(ref){\nvar stacked = ref.stacked;\nreturn [_c('SelectFilter',{attrs:{\"label\":_vm.$str('header_learning_type', 'totara_core'),\"show-label\":true,\"options\":_vm.learningTypeFilterOptions,\"stacked\":stacked},model:{value:(_vm.learningTypeFilter),callback:function ($$v) {_vm.learningTypeFilter=$$v},expression:\"learningTypeFilter\"}}),_vm._v(\" \"),_c('SelectFilter',{attrs:{\"label\":_vm.$str('header_learning_progress', 'totara_core'),\"show-label\":true,\"options\":_vm.progressOptions,\"stacked\":stacked},model:{value:(_vm.progressFilter),callback:function ($$v) {_vm.progressFilter=$$v},expression:\"progressFilter\"}})]}},{key:\"filters-right\",fn:function(ref){\nvar stacked = ref.stacked;\nreturn [_c('SearchFilter',{attrs:{\"label\":_vm.$str('filter_learning_search_label', 'totara_core'),\"show-label\":false,\"placeholder\":_vm.$str('search', 'totara_core'),\"stacked\":stacked},model:{value:(_vm.searchDebounce),callback:function ($$v) {_vm.searchDebounce=$$v},expression:\"searchDebounce\"}})]}}])})]},proxy:true},{key:\"browse-list\",fn:function(ref){\nvar disabledItems = ref.disabledItems;\nvar selectedItems = ref.selectedItems;\nvar update = ref.update;\nreturn [_c('SelectTable',{attrs:{\"large-check-box\":true,\"no-label-offset\":true,\"value\":selectedItems,\"data\":_vm.learningItems && _vm.learningItems.items ? _vm.learningItems.items : [],\"disabled-ids\":disabledItems,\"checkbox-v-align\":\"center\",\"select-all-enabled\":true,\"border-bottom-hidden\":true,\"get-id\":function (row, index) { return ('unique_id' in row ? row.unique_id : index); }},on:{\"input\":function($event){return update($event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"5\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_name', 'totara_core'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_type', 'totara_core'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"3\",\"align\":\"center\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_progress', 'totara_core'))+\"\\n        \")])]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"5\",\"column-header\":_vm.$str('header_learning_name', 'totara_core'),\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(row.fullname)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"4\",\"column-header\":_vm.$str('header_learning_type', 'totara_core'),\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.typeName(row.itemtype))+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"3\",\"column-header\":_vm.$str('header_learning_progress', 'totara_core'),\"align\":\"center\",\"valign\":\"center\"}},[(row.progress || row.progress === 0)?[_vm._v(\"\\n            \"+_vm._s(_vm.$str('xpercent', 'core', row.progress))+\"\\n          \")]:[_vm._v(\"\\n            \"+_vm._s(_vm.$str('statusnottracked', 'core_completion'))+\"\\n          \")]],2)]}}],null,true)})]}},{key:\"basket-list\",fn:function(ref){\nvar disabledItems = ref.disabledItems;\nvar selectedItems = ref.selectedItems;\nvar update = ref.update;\nreturn [_c('SelectTable',{attrs:{\"large-check-box\":true,\"no-label-offset\":true,\"value\":selectedItems,\"data\":_vm.learningSelectedItems,\"disabled-ids\":disabledItems,\"checkbox-v-align\":\"center\",\"border-bottom-hidden\":true,\"select-all-enabled\":true,\"get-id\":function (row, index) { return ('unique_id' in row ? row.unique_id : index); }},on:{\"input\":function($event){return update($event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"5\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_name', 'totara_core'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_type', 'totara_core'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"3\",\"align\":\"center\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_learning_progress', 'totara_core'))+\"\\n        \")])]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"5\",\"column-header\":_vm.$str('header_learning_name', 'totara_core'),\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(row.fullname)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"4\",\"column-header\":_vm.$str('header_learning_type', 'totara_core'),\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.typeName(row.itemtype))+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"3\",\"column-header\":_vm.$str('header_learning_progress', 'totara_core'),\"align\":\"center\",\"valign\":\"center\"}},[(row.progress || row.progress === 0)?[_vm._v(\"\\n            \"+_vm._s(_vm.$str('xpercent', 'core', row.progress))+\"\\n          \")]:[_vm._v(\"\\n            \"+_vm._s(_vm.$str('statusnottracked', 'core_completion'))+\"\\n          \")]],2)]}}],null,true)})]}}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/adder/LearningAdder.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?vue&type=template&id=65552a22& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div')}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminEdit.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?vue&type=template&id=95c11bec& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ParticipantContent',{attrs:{\"content\":_vm.getPreviewData(),\"preview\":true}})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/AdminView.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?vue&type=template&id=1ab07040& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning\"},[(!_vm.preview)?_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__date\"},[_vm._v(\"\\n    \"+_vm._s(_vm.createdAt)+\"\\n  \")]):_vm._e(),_vm._v(\" \"),_c('Card',{staticClass:\"tui-linkedReviewViewCurrentLearning__card\",attrs:{\"clickable\":!_vm.preview},on:{\"click\":_vm.open}},[_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__image\",style:('background-image: url(' + _vm.content.image_src + ');')}),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__content\"},[_c('h3',{staticClass:\"tui-linkedReviewViewCurrentLearning__content-heading\"},[(!_vm.preview)?_c('a',{attrs:{\"href\":_vm.content.url_view,\"target\":\"_blank\"}},[_vm._v(_vm._s(_vm.content.fullname))]):[_vm._v(_vm._s(_vm.content.fullname))]],2),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__content-description\",domProps:{\"innerHTML\":_vm._s(_vm.content.description)}}),_vm._v(\" \"),_c('span',{staticClass:\"tui-linkedReviewViewCurrentLearning__content-type\"},[_vm._v(\"\\n        \"+_vm._s(_vm.typeName)+\"\\n      \")])]),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__chart\"},[(_vm.content.progress !== null)?_c('PercentageDoughnut',_vm._b({},'PercentageDoughnut',_vm.chartProps,false)):_c('div',{staticClass:\"tui-linkedReviewViewCurrentLearning__chart-text\"},[_vm._v(\"\\n        \"+_vm._s(_vm.$str('statusnottracked', 'core_completion'))+\"\\n      \")])],1)])],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContent.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?vue&type=template&id=528d0024& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('SelectContent',{attrs:{\"adder\":_vm.getAdder(),\"add-btn-text\":_vm.$str('add_learning', 'totara_core'),\"can-show-adder\":_vm.canShowAdder,\"cant-add-text\":_vm.$str('awaiting_selection_text', 'totara_core', _vm.coreRelationship[0].name),\"is-draft\":_vm.isDraft,\"participant-instance-id\":_vm.participantInstanceId,\"remove-text\":_vm.$str('remove_learning', 'totara_core'),\"required\":_vm.required,\"section-element-id\":_vm.sectionElementId,\"user-id\":_vm.userId,\"additional-content\":['itemtype'],\"get-id\":function (content) { return ('unique_id' in content ? content.unique_id : null); }},on:{\"unsaved-plugin-change\":function($event){return _vm.$emit('unsaved-plugin-change', $event)},\"update\":function($event){return _vm.$emit('update', $event)}},scopedSlots:_vm._u([{key:\"content-preview\",fn:function(ref){\nvar content = ref.content;\nreturn [_c(_vm.previewComponent,{tag:\"component\",attrs:{\"content\":_vm.getItemData(content),\"subject-user\":_vm.subjectUser}})]}}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_core/src/components/performelement_linked_review/learning/ParticipantContentPicker.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return normalizeComponent; });\n/* globals __VUE_SSR_CONTEXT__ */\n\n// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).\n// This module is a runtime utility for cleaner component module output and will\n// be included in the final webpack user bundle.\n\nfunction normalizeComponent (\n  scriptExports,\n  render,\n  staticRenderFns,\n  functionalTemplate,\n  injectStyles,\n  scopeId,\n  moduleIdentifier, /* server only */\n  shadowMode /* vue-cli only */\n) {\n  // Vue.extend constructor export interop\n  var options = typeof scriptExports === 'function'\n    ? scriptExports.options\n    : scriptExports\n\n  // render functions\n  if (render) {\n    options.render = render\n    options.staticRenderFns = staticRenderFns\n    options._compiled = true\n  }\n\n  // functional template\n  if (functionalTemplate) {\n    options.functional = true\n  }\n\n  // scopedId\n  if (scopeId) {\n    options._scopeId = 'data-v-' + scopeId\n  }\n\n  var hook\n  if (moduleIdentifier) { // server build\n    hook = function (context) {\n      // 2.3 injection\n      context =\n        context || // cached call\n        (this.$vnode && this.$vnode.ssrContext) || // stateful\n        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional\n      // 2.2 with runInNewContext: true\n      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {\n        context = __VUE_SSR_CONTEXT__\n      }\n      // inject component styles\n      if (injectStyles) {\n        injectStyles.call(this, context)\n      }\n      // register component module identifier for async chunk inferrence\n      if (context && context._registeredComponents) {\n        context._registeredComponents.add(moduleIdentifier)\n      }\n    }\n    // used by ssr in case component is cached and beforeCreate\n    // never gets called\n    options._ssrRegister = hook\n  } else if (injectStyles) {\n    hook = shadowMode\n      ? function () {\n        injectStyles.call(\n          this,\n          (options.functional ? this.parent : this).$root.$options.shadowRoot\n        )\n      }\n      : injectStyles\n  }\n\n  if (hook) {\n    if (options.functional) {\n      // for template-only hot-reload because in that case the render fn doesn't\n      // go through the normalizer\n      options._injectStyles = hook\n      // register for functional component in vue file\n      var originalRender = options.render\n      options.render = function renderWithStyleInjection (h, context) {\n        hook.call(context)\n        return originalRender(h, context)\n      }\n    } else {\n      // inject component registration as beforeCreate hook\n      var existing = options.beforeCreate\n      options.beforeCreate = existing\n        ? [].concat(existing, hook)\n        : [hook]\n    }\n  }\n\n  return {\n    exports: scriptExports,\n    options: options\n  }\n}\n\n\n//# sourceURL=webpack:///./node_modules/vue-loader/lib/runtime/componentNormalizer.js?");

/***/ }),

/***/ "./server/totara/core/webapi/ajax/user_learning_items.graphql":
/*!********************************************************************!*\
  !*** ./server/totara/core/webapi/ajax/user_learning_items.graphql ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"items\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"itemtype\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fullname\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"PLAIN\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"description\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"HTML\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"progress\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"image_src\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"url_view\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"unique_id\"},\"arguments\":[],\"directives\":[]}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"next_cursor\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"total\"},\"arguments\":[],\"directives\":[]}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/totara/core/webapi/ajax/user_learning_items.graphql?");

/***/ }),

/***/ "./server/totara/core/webapi/ajax/user_learning_items_selected.graphql":
/*!*****************************************************************************!*\
  !*** ./server/totara/core/webapi/ajax/user_learning_items_selected.graphql ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items_selected\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_core_user_learning_items_selected\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"items\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"itemtype\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fullname\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"PLAIN\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"description\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"HTML\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"progress\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"image_src\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"url_view\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"unique_id\"},\"arguments\":[],\"directives\":[]}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"next_cursor\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"total\"},\"arguments\":[],\"directives\":[]}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/totara/core/webapi/ajax/user_learning_items_selected.graphql?");

/***/ }),

/***/ "performelement_linked_review/components/SelectContent":
/*!*****************************************************************************************!*\
  !*** external "tui.require(\"performelement_linked_review/components/SelectContent\")" ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"performelement_linked_review/components/SelectContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22performelement_linked_review/components/SelectContent\\%22)%22?");

/***/ }),

/***/ "totara_core/components/adder/LearningAdder":
/*!******************************************************************************!*\
  !*** external "tui.require(\"totara_core/components/adder/LearningAdder\")" ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"totara_core/components/adder/LearningAdder\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22totara_core/components/adder/LearningAdder\\%22)%22?");

/***/ }),

/***/ "totara_core/components/performelement_linked_review/learning/ParticipantContent":
/*!*******************************************************************************************************************!*\
  !*** external "tui.require(\"totara_core/components/performelement_linked_review/learning/ParticipantContent\")" ***!
  \*******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"totara_core/components/performelement_linked_review/learning/ParticipantContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22totara_core/components/performelement_linked_review/learning/ParticipantContent\\%22)%22?");

/***/ }),

/***/ "tui/components/adder/Adder":
/*!**************************************************************!*\
  !*** external "tui.require(\"tui/components/adder/Adder\")" ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/adder/Adder\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/adder/Adder\\%22)%22?");

/***/ }),

/***/ "tui/components/card/Card":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/card/Card\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/card/Card\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/card/Card\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/Cell":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/Cell\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/Cell\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/Cell\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/HeaderCell":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/HeaderCell\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/HeaderCell\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/HeaderCell\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/SelectTable":
/*!************************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/SelectTable\")" ***!
  \************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/SelectTable\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/SelectTable\\%22)%22?");

/***/ }),

/***/ "tui/components/filters/FilterBar":
/*!********************************************************************!*\
  !*** external "tui.require(\"tui/components/filters/FilterBar\")" ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/filters/FilterBar\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/filters/FilterBar\\%22)%22?");

/***/ }),

/***/ "tui/components/filters/SearchFilter":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/filters/SearchFilter\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/filters/SearchFilter\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/filters/SearchFilter\\%22)%22?");

/***/ }),

/***/ "tui/components/filters/SelectFilter":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/filters/SelectFilter\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/filters/SelectFilter\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/filters/SelectFilter\\%22)%22?");

/***/ }),

/***/ "tui/util":
/*!********************************************!*\
  !*** external "tui.require(\"tui/util\")" ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/util\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/util\\%22)%22?");

/***/ }),

/***/ "tui_charts/components/PercentageDoughnut":
/*!****************************************************************************!*\
  !*** external "tui.require(\"tui_charts/components/PercentageDoughnut\")" ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui_charts/components/PercentageDoughnut\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui_charts/components/PercentageDoughnut\\%22)%22?");

/***/ })

/******/ });