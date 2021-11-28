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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/totara_evidence/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/totara_evidence/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!********************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./adder/EvidenceAdder\": \"./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue\",\n\t\"./adder/EvidenceAdder.vue\": \"./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue\",\n\t\"./performelement_linked_review/AdminView\": \"./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue\",\n\t\"./performelement_linked_review/AdminView.vue\": \"./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue\",\n\t\"./performelement_linked_review/ParticipantContent\": \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue\",\n\t\"./performelement_linked_review/ParticipantContent.vue\": \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue\",\n\t\"./performelement_linked_review/ParticipantContentPicker\": \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue\",\n\t\"./performelement_linked_review/ParticipantContentPicker.vue\": \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/totara_evidence/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/totara_evidence/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue":
/*!*********************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EvidenceAdder.vue?vue&type=template&id=5ab6c870& */ \"./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870&\");\n/* harmony import */ var _EvidenceAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EvidenceAdder.vue?vue&type=script&lang=js& */ \"./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _EvidenceAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _EvidenceAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _EvidenceAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_EvidenceAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_evidence/src/components/adder/EvidenceAdder.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EvidenceAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EvidenceAdder_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_EvidenceAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./EvidenceAdder.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_EvidenceAdder_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870&":
/*!****************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870& ***!
  \****************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EvidenceAdder.vue?vue&type=template&id=5ab6c870& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EvidenceAdder_vue_vue_type_template_id_5ab6c870___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue":
/*!****************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdminView.vue?vue&type=template&id=5f3e3a96& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96&\");\n/* harmony import */ var _AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdminView.vue?vue&type=script&lang=js& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./AdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***************************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AdminView.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96&":
/*!***********************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96& ***!
  \***********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdminView.vue?vue&type=template&id=5f3e3a96& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminView_vue_vue_type_template_id_5f3e3a96___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue":
/*!*************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=template&id=671b8ff5& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5&\");\n/* harmony import */ var _ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=script&lang=js& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ParticipantContent.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ParticipantContent.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContent.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5&":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5& ***!
  \********************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContent.vue?vue&type=template&id=671b8ff5& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContent_vue_vue_type_template_id_671b8ff5___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue":
/*!*******************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=template&id=2ed943e3& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3&\");\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=script&lang=js& */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!******************************************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ParticipantContentPicker.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ParticipantContentPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3&":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3& ***!
  \**************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ParticipantContentPicker.vue?vue&type=template&id=2ed943e3& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ParticipantContentPicker_vue_vue_type_template_id_2ed943e3___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?");

/***/ }),

/***/ "./client/component/totara_evidence/src/tui.json":
/*!*******************************************************!*\
  !*** ./client/component/totara_evidence/src/tui.json ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"totara_evidence\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"totara_evidence\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"totara_evidence\")\ntui._bundle.addModulesFromContext(\"totara_evidence/components\", __webpack_require__(\"./client/component/totara_evidence/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"core\": [\n    \"all\"\n  ],\n  \"totara_core\": [\n    \"search\"\n  ],\n  \"totara_evidence\": [\n    \"date_created\",\n    \"evidence_type\",\n    \"filter_evidence\",\n    \"filter_evidence_items_search_label\",\n    \"header_evidence\",\n    \"select_evidence\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_evidence\": [\n    \"admin_placeholder_field_checkbox\",\n    \"admin_placeholder_field_date\",\n    \"admin_placeholder_field_location\",\n    \"admin_placeholder_field_menu\",\n    \"admin_placeholder_field_text_area\",\n    \"admin_placeholder_field_text_input\",\n    \"admin_placeholder_field_url\",\n    \"admin_placeholder_label_checkbox\",\n    \"admin_placeholder_label_date\",\n    \"admin_placeholder_label_file\",\n    \"admin_placeholder_label_location\",\n    \"admin_placeholder_label_menu\",\n    \"admin_placeholder_label_text_area\",\n    \"admin_placeholder_label_text_input\",\n    \"admin_placeholder_label_url\",\n    \"admin_placeholder_title\",\n    \"admin_placeholder_type\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_evidence\": [\n    \"add_evidence\",\n    \"awaiting_selection_text\",\n    \"remove_evidence\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/adder/Adder */ \"tui/components/adder/Adder\");\n/* harmony import */ var tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/datatable/Cell */ \"tui/components/datatable/Cell\");\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/filters/FilterBar */ \"tui/components/filters/FilterBar\");\n/* harmony import */ var tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/datatable/HeaderCell */ \"tui/components/datatable/HeaderCell\");\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/filters/SearchFilter */ \"tui/components/filters/SearchFilter\");\n/* harmony import */ var tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/filters/SelectFilter */ \"tui/components/filters/SelectFilter\");\n/* harmony import */ var tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tui/components/datatable/SelectTable */ \"tui/components/datatable/SelectTable\");\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var totara_evidence_graphql_user_evidence_items__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! totara_evidence/graphql/user_evidence_items */ \"./server/totara/evidence/webapi/ajax/user_evidence_items.graphql\");\n/* harmony import */ var totara_evidence_graphql_user_evidence_types__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! totara_evidence/graphql/user_evidence_types */ \"./server/totara/evidence/webapi/ajax/user_evidence_types.graphql\");\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! tui/util */ \"tui/util\");\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(tui_util__WEBPACK_IMPORTED_MODULE_9__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n// Components\n\n\n\n\n\n\n\n\n//Queries\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Adder: (tui_components_adder_Adder__WEBPACK_IMPORTED_MODULE_0___default()),\n    Cell: (tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_1___default()),\n    FilterBar: (tui_components_filters_FilterBar__WEBPACK_IMPORTED_MODULE_2___default()),\n    HeaderCell: (tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_3___default()),\n    SearchFilter: (tui_components_filters_SearchFilter__WEBPACK_IMPORTED_MODULE_4___default()),\n    SelectFilter: (tui_components_filters_SelectFilter__WEBPACK_IMPORTED_MODULE_5___default()),\n    SelectTable: (tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_6___default()),\n  },\n\n  props: {\n    existingItems: {\n      type: Array,\n      default: () => [],\n    },\n    open: Boolean,\n    // Display loading spinner on Add button\n    showLoadingBtn: Boolean,\n    userId: Number,\n  },\n\n  data() {\n    return {\n      evidence: null,\n      evidenceSelectedItems: [],\n      evidenceTypes: [],\n      selectedEvidenceTypeId: 0,\n      filters: {\n        search: '',\n        typeId: '',\n      },\n      nextPage: false,\n      skipQueries: true,\n      searchDebounce: '',\n    };\n  },\n\n  computed: {\n    evidenceTypeFilterOptions() {\n      return this.mapFilterOptions(this.evidenceTypes);\n    },\n  },\n\n  watch: {\n    /**\n     * On opening of adder, unblock query\n     */\n    open() {\n      if (this.open) {\n        this.searchDebounce = '';\n        this.skipQueries = false;\n      } else {\n        this.skipQueries = true;\n      }\n    },\n\n    searchDebounce(newValue) {\n      this.updateFilterDebounced(newValue);\n    },\n\n    selectedEvidenceTypeId(newValue) {\n      this.filters.typeId = newValue;\n    },\n  },\n\n  created() {\n    this.$apollo.addSmartQuery('evidence', {\n      query: totara_evidence_graphql_user_evidence_items__WEBPACK_IMPORTED_MODULE_7__[\"default\"],\n      skip() {\n        return this.skipQueries;\n      },\n      variables() {\n        return {\n          input: {\n            filters: {\n              search: this.filters.search,\n              type_id: this.filters.typeId,\n            },\n            user_id: this.userId,\n          },\n        };\n      },\n      update({ ['evidence']: evidence }) {\n        this.nextPage = evidence.next_cursor ? evidence.next_cursor : false;\n        return evidence;\n      },\n    });\n\n    /**\n     * Selected evidence query\n     */\n    this.$apollo.addSmartQuery('selectedEvidence', {\n      query: totara_evidence_graphql_user_evidence_items__WEBPACK_IMPORTED_MODULE_7__[\"default\"],\n      skip() {\n        return this.skipQueries;\n      },\n      variables() {\n        return {\n          input: {\n            filters: {\n              ids: [],\n            },\n            user_id: this.userId,\n          },\n        };\n      },\n      update({ ['evidence']: selectedEvidence }) {\n        this.evidenceSelectedItems = selectedEvidence.items;\n        return selectedEvidence;\n      },\n    });\n\n    /**\n     * Evidence types\n     */\n    this.$apollo.addSmartQuery('evidenceTypes', {\n      query: totara_evidence_graphql_user_evidence_types__WEBPACK_IMPORTED_MODULE_8__[\"default\"],\n      skip() {\n        return this.skipQueries;\n      },\n      variables() {\n        return {\n          input: {\n            user_id: this.userId,\n          },\n        };\n      },\n      update({ ['types']: evidenceTypes }) {\n        return evidenceTypes.items;\n      },\n    });\n  },\n\n  methods: {\n    /**\n     * Load addition items and append to list\n     *\n     */\n    async loadMoreItems() {\n      if (!this.nextPage) {\n        return;\n      }\n      try {\n        this.$apollo.queries.evidence.fetchMore({\n          variables: {\n            input: {\n              cursor: this.nextPage,\n              filters: {\n                search: this.filters.search,\n                type_id: this.filters.typeId,\n              },\n              user_id: this.userId,\n            },\n          },\n\n          updateQuery: (previousResult, { fetchMoreResult }) => {\n            const oldData = previousResult.evidence;\n            const newData = fetchMoreResult.evidence;\n            const newList = oldData.items.concat(newData.items);\n\n            return {\n              ['evidence']: {\n                items: newList,\n                next_cursor: newData.next_cursor,\n              },\n            };\n          },\n        });\n      } catch (error) {\n        console.error(error);\n      }\n    },\n\n    /**\n     * Update the selected items data\n     *\n     * @param {Array} selection\n     */\n    async updateSelectedItems(selection) {\n      const numberOfItems = selection.length;\n\n      try {\n        await this.$apollo.queries.selectedEvidence.refetch({\n          input: {\n            filters: {\n              ids: selection,\n            },\n            result_size: numberOfItems,\n            user_id: this.userId,\n          },\n        });\n      } catch (error) {\n        console.error(error);\n      }\n      return this.evidenceSelectedItems;\n    },\n\n    /**\n     * Close the adder, returning the selected items data\n     *\n     * @param {Array} selection\n     */\n    async closeWithData(selection) {\n      let data;\n\n      this.$emit('add-button-clicked');\n\n      try {\n        data = await this.updateSelectedItems(selection);\n        this.selectedEvidenceTypeId = 0;\n      } catch (error) {\n        console.error(error);\n        return;\n      }\n      this.$emit('added', { ids: selection, data: data });\n    },\n\n    /**\n     * Cancel the adder\n     */\n    cancelAdder() {\n      this.selectedEvidenceTypeId = 0;\n      this.$emit('cancel');\n    },\n\n    /**\n     * Map filter options to required format\n     *\n     * @param {Object} source\n     * @return {Object}\n     */\n    mapFilterOptions(source) {\n      let filters = source;\n\n      filters = filters.map(f => {\n        return {\n          id: f.id,\n          label: f.name,\n        };\n      });\n\n      filters.unshift({\n        id: 0,\n        label: this.$str('all', 'core'),\n      });\n\n      return filters;\n    },\n\n    /**\n     * Update the search filter (which re-triggers the query) if the user stopped typing >500 milliseconds ago.\n     *\n     * @param {String} input Value from search filter input\n     */\n    updateFilterDebounced: Object(tui_util__WEBPACK_IMPORTED_MODULE_9__[\"debounce\"])(function(input) {\n      this.filters.search = input;\n    }, 500),\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var totara_evidence_components_performelement_linked_review_ParticipantContent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! totara_evidence/components/performelement_linked_review/ParticipantContent */ \"totara_evidence/components/performelement_linked_review/ParticipantContent\");\n/* harmony import */ var totara_evidence_components_performelement_linked_review_ParticipantContent__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(totara_evidence_components_performelement_linked_review_ParticipantContent__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ParticipantContent: (totara_evidence_components_performelement_linked_review_ParticipantContent__WEBPACK_IMPORTED_MODULE_0___default()),\n  },\n\n  data() {\n    return {\n      data: {\n        display_name: 'Example evidence',\n        id: 0,\n        type: {\n          name: 'Evidence type',\n        },\n        content_type: '',\n        created_at: 0,\n        fields: [\n          {\n            label: this.$str('admin_placeholder_label_file', 'totara_evidence'),\n            type: 'file',\n            content: JSON.stringify({\n              file_size: 12345678,\n              file_name: 'filename.pdf',\n              url: '',\n              html: '',\n            }),\n          },\n          {\n            label: this.$str('admin_placeholder_label_date', 'totara_evidence'),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_date',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str(\n              'admin_placeholder_label_location',\n              'totara_evidence'\n            ),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_location',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str('admin_placeholder_label_menu', 'totara_evidence'),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_menu',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str(\n              'admin_placeholder_label_text_area',\n              'totara_evidence'\n            ),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_text_area',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str(\n              'admin_placeholder_label_text_input',\n              'totara_evidence'\n            ),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_text_input',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str(\n              'admin_placeholder_label_checkbox',\n              'totara_evidence'\n            ),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str(\n                'admin_placeholder_field_checkbox',\n                'totara_evidence'\n              ),\n            }),\n          },\n          {\n            label: this.$str('admin_placeholder_label_url', 'totara_evidence'),\n            type: '',\n            content: JSON.stringify({\n              html: this.$str('admin_placeholder_field_url', 'totara_evidence'),\n            }),\n          },\n        ],\n      },\n    };\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_file_FileCard__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/file/FileCard */ \"tui/components/file/FileCard\");\n/* harmony import */ var tui_components_file_FileCard__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_file_FileCard__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    FileCard: (tui_components_file_FileCard__WEBPACK_IMPORTED_MODULE_0___default()),\n  },\n\n  /**\n   * These props are setup in ParticipantContentPicker.\n   */\n  props: {\n    content: {\n      type: Object,\n      default: () => ({\n        display_name: '',\n        id: '',\n        type: {\n          id: '0',\n          name: '',\n        },\n        content_type: '',\n        created_at: '',\n        fields: [],\n      }),\n    },\n  },\n\n  data() {\n    return {\n      fields: this.content.fields.map(field => {\n        return Object.assign({}, field, {\n          content: JSON.parse(field.content),\n        });\n      }),\n    };\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var totara_evidence_components_adder_EvidenceAdder__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! totara_evidence/components/adder/EvidenceAdder */ \"totara_evidence/components/adder/EvidenceAdder\");\n/* harmony import */ var totara_evidence_components_adder_EvidenceAdder__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(totara_evidence_components_adder_EvidenceAdder__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! performelement_linked_review/components/SelectContent */ \"performelement_linked_review/components/SelectContent\");\n/* harmony import */ var performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    EvidenceAdder: (totara_evidence_components_adder_EvidenceAdder__WEBPACK_IMPORTED_MODULE_0___default()),\n    SelectContent: (performelement_linked_review_components_SelectContent__WEBPACK_IMPORTED_MODULE_1___default()),\n  },\n\n  props: {\n    canShowAdder: {\n      type: Boolean,\n      required: true,\n    },\n    contentType: String,\n    coreRelationship: Array,\n    isDraft: Boolean,\n    participantInstanceId: {\n      type: [String, Number],\n      required: true,\n    },\n    previewComponent: [Function, Object],\n    required: Boolean,\n    sectionElementId: String,\n    subjectUser: Object,\n    userId: Number,\n  },\n\n  computed: {\n    cantAddText() {\n      return this.$str(\n        'awaiting_selection_text',\n        'totara_evidence',\n        this.coreRelationship[0].name\n      );\n    },\n\n    /**\n     * Get adder component\n     *\n     * @return {Object}\n     */\n    adder() {\n      return totara_evidence_components_adder_EvidenceAdder__WEBPACK_IMPORTED_MODULE_0___default.a;\n    },\n  },\n\n  methods: {\n    /**\n     * Get data for competency preview component\n     *\n     * @param {Object} values\n     * @return {Object}\n     */\n    getItemData({ created_at, fields, id, name, type }) {\n      return {\n        display_name: name,\n        id: id,\n        fields: fields,\n        type: type,\n        content_type: this.contentType,\n        created_at: created_at,\n      };\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?vue&type=template&id=5ab6c870& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Adder',{attrs:{\"open\":_vm.open,\"title\":_vm.$str('select_evidence', 'totara_evidence'),\"existing-items\":_vm.existingItems,\"loading\":_vm.$apollo.loading,\"show-load-more\":_vm.nextPage,\"show-loading-btn\":_vm.showLoadingBtn},on:{\"added\":function($event){return _vm.closeWithData($event)},\"cancel\":function($event){return _vm.cancelAdder()},\"load-more\":function($event){return _vm.loadMoreItems()},\"selected-tab-active\":function($event){return _vm.updateSelectedItems($event)}},scopedSlots:_vm._u([{key:\"browse-filters\",fn:function(){return [_c('FilterBar',{attrs:{\"has-top-bar\":false,\"title\":_vm.$str('filter_evidence', 'totara_evidence')},scopedSlots:_vm._u([{key:\"filters-left\",fn:function(ref){\nvar stacked = ref.stacked;\nreturn [_c('SelectFilter',{attrs:{\"label\":_vm.$str('evidence_type', 'totara_evidence'),\"show-label\":true,\"options\":_vm.evidenceTypeFilterOptions,\"stacked\":stacked},model:{value:(_vm.selectedEvidenceTypeId),callback:function ($$v) {_vm.selectedEvidenceTypeId=$$v},expression:\"selectedEvidenceTypeId\"}})]}},{key:\"filters-right\",fn:function(ref){\nvar stacked = ref.stacked;\nreturn [_c('SearchFilter',{attrs:{\"label\":_vm.$str('filter_evidence_items_search_label', 'totara_evidence'),\"show-label\":false,\"placeholder\":_vm.$str('search', 'totara_core'),\"stacked\":stacked},model:{value:(_vm.searchDebounce),callback:function ($$v) {_vm.searchDebounce=$$v},expression:\"searchDebounce\"}})]}}])})]},proxy:true},{key:\"browse-list\",fn:function(ref){\nvar disabledItems = ref.disabledItems;\nvar selectedItems = ref.selectedItems;\nvar update = ref.update;\nreturn [_c('SelectTable',{attrs:{\"large-check-box\":true,\"no-label-offset\":true,\"value\":selectedItems,\"data\":_vm.evidence && _vm.evidence.items ? _vm.evidence.items : [],\"disabled-ids\":disabledItems,\"checkbox-v-align\":\"center\",\"select-all-enabled\":true,\"border-bottom-hidden\":true},on:{\"input\":function($event){return update($event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"8\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_evidence', 'totara_evidence'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('evidence_type', 'totara_evidence'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('date_created', 'totara_evidence'))+\"\\n        \")])]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"8\",\"valign\":\"center\",\"column-header\":_vm.$str('header_evidence', 'totara_evidence')}},[_vm._v(\"\\n          \"+_vm._s(row.name)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"4\",\"valign\":\"center\",\"column-header\":_vm.$str('evidence_type', 'totara_evidence')}},[_vm._v(\"\\n          \"+_vm._s(row.type.name)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"column-header\":_vm.$str('date_created', 'totara_evidence'),\"valign\":\"center\",\"size\":\"4\"}},[_vm._v(\"\\n          \"+_vm._s(row.created_at)+\"\\n        \")])]}}],null,true)})]}},{key:\"basket-list\",fn:function(ref){\nvar disabledItems = ref.disabledItems;\nvar selectedItems = ref.selectedItems;\nvar update = ref.update;\nreturn [_c('SelectTable',{attrs:{\"large-check-box\":true,\"no-label-offset\":true,\"value\":selectedItems,\"data\":_vm.evidenceSelectedItems,\"disabled-ids\":disabledItems,\"checkbox-v-align\":\"center\",\"border-bottom-hidden\":true,\"select-all-enabled\":true},on:{\"input\":function($event){return update($event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"8\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('header_evidence', 'totara_evidence'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('evidence_type', 'totara_evidence'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',{attrs:{\"size\":\"4\",\"valign\":\"center\"}},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('date_created', 'totara_evidence'))+\"\\n        \")])]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"8\",\"valign\":\"center\",\"column-header\":_vm.$str('header_evidence', 'totara_evidence')}},[_vm._v(\"\\n          \"+_vm._s(row.name)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"size\":\"4\",\"valign\":\"center\",\"column-header\":_vm.$str('evidence_type', 'totara_evidence')}},[_vm._v(\"\\n          \"+_vm._s(row.type.name)+\"\\n        \")]),_vm._v(\" \"),_c('Cell',{attrs:{\"column-header\":_vm.$str('date_created', 'totara_evidence'),\"valign\":\"center\",\"size\":\"4\"}},[_vm._v(\"\\n          \"+_vm._s(row.created_at)+\"\\n        \")])]}}],null,true)})]}}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/adder/EvidenceAdder.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?vue&type=template&id=5f3e3a96& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-linkedReviewAdminViewEvidence\"},[_c('ParticipantContent',{attrs:{\"title\":_vm.$str('admin_placeholder_title', 'totara_evidence'),\"content\":_vm.data,\"type\":_vm.$str('admin_placeholder_type', 'totara_evidence')}})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/AdminView.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?vue&type=template&id=671b8ff5& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-linkedReviewViewEvidence\"},[_c('h3',{staticClass:\"tui-linkedReviewViewEvidence__heading\"},[_vm._v(\"\\n    \"+_vm._s(_vm.content.display_name)+\"\\n  \")]),_vm._v(\" \"),_c('dl',{staticClass:\"tui-linkedReviewViewEvidence__customFields\"},_vm._l((_vm.fields),function(ref){\nvar label = ref.label;\nvar type = ref.type;\nvar customField = ref.content;\nreturn _c('div',{key:label,staticClass:\"tui-linkedReviewViewEvidence__customFields-field\"},[_c('dt',[_vm._v(_vm._s(label))]),_vm._v(\" \"),(type !== 'file')?_c('dd',{domProps:{\"innerHTML\":_vm._s(customField.html)}}):_c('dd',[(customField.file_name)?_c('FileCard',{attrs:{\"file-size\":customField.file_size,\"filename\":customField.file_name,\"download-url\":customField.url}}):_c('p',[_vm._v(\"\\n          \"+_vm._s(customField.html)+\"\\n        \")])],1)])}),0),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedReviewViewEvidence__type\"},[_vm._v(\"\\n    \"+_vm._s(_vm.content.type.name)+\"\\n  \")])])}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContent.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?vue&type=template&id=2ed943e3& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('SelectContent',{attrs:{\"adder\":_vm.adder,\"add-btn-text\":_vm.$str('add_evidence', 'totara_evidence'),\"can-show-adder\":_vm.canShowAdder,\"cant-add-text\":_vm.cantAddText,\"is-draft\":_vm.isDraft,\"participant-instance-id\":_vm.participantInstanceId,\"remove-text\":_vm.$str('remove_evidence', 'totara_evidence'),\"required\":_vm.required,\"section-element-id\":_vm.sectionElementId,\"user-id\":_vm.userId},on:{\"unsaved-plugin-change\":function($event){return _vm.$emit('unsaved-plugin-change', $event)},\"update\":function($event){return _vm.$emit('update', $event)}},scopedSlots:_vm._u([{key:\"content-preview\",fn:function(ref){\nvar content = ref.content;\nreturn [_c(_vm.previewComponent,{tag:\"component\",attrs:{\"content\":_vm.getItemData(content)}})]}}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_evidence/src/components/performelement_linked_review/ParticipantContentPicker.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "./server/totara/evidence/webapi/ajax/user_evidence_items.graphql":
/*!************************************************************************!*\
  !*** ./server/totara/evidence/webapi/ajax/user_evidence_items.graphql ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_items\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_items_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"evidence\"},\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_items\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"items\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"type\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"created_at\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"DATELONG\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fields\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"label\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"type\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"content\"},\"arguments\":[],\"directives\":[]}]}}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"total\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"next_cursor\"},\"arguments\":[],\"directives\":[]}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/totara/evidence/webapi/ajax/user_evidence_items.graphql?");

/***/ }),

/***/ "./server/totara/evidence/webapi/ajax/user_evidence_types.graphql":
/*!************************************************************************!*\
  !*** ./server/totara/evidence/webapi/ajax/user_evidence_types.graphql ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_types\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_types_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"types\"},\"name\":{\"kind\":\"Name\",\"value\":\"totara_evidence_user_evidence_types\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"items\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/totara/evidence/webapi/ajax/user_evidence_types.graphql?");

/***/ }),

/***/ "performelement_linked_review/components/SelectContent":
/*!*****************************************************************************************!*\
  !*** external "tui.require(\"performelement_linked_review/components/SelectContent\")" ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"performelement_linked_review/components/SelectContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22performelement_linked_review/components/SelectContent\\%22)%22?");

/***/ }),

/***/ "totara_evidence/components/adder/EvidenceAdder":
/*!**********************************************************************************!*\
  !*** external "tui.require(\"totara_evidence/components/adder/EvidenceAdder\")" ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"totara_evidence/components/adder/EvidenceAdder\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22totara_evidence/components/adder/EvidenceAdder\\%22)%22?");

/***/ }),

/***/ "totara_evidence/components/performelement_linked_review/ParticipantContent":
/*!**************************************************************************************************************!*\
  !*** external "tui.require(\"totara_evidence/components/performelement_linked_review/ParticipantContent\")" ***!
  \**************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"totara_evidence/components/performelement_linked_review/ParticipantContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22totara_evidence/components/performelement_linked_review/ParticipantContent\\%22)%22?");

/***/ }),

/***/ "tui/components/adder/Adder":
/*!**************************************************************!*\
  !*** external "tui.require(\"tui/components/adder/Adder\")" ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/adder/Adder\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/adder/Adder\\%22)%22?");

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

/***/ "tui/components/file/FileCard":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/file/FileCard\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/file/FileCard\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/file/FileCard\\%22)%22?");

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

/***/ })

/******/ });