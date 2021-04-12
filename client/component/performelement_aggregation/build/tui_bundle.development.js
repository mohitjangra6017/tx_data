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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/performelement_aggregation/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/performelement_aggregation/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!*******************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \*******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./AggregationAdminEdit\": \"./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue\",\n\t\"./AggregationAdminEdit.vue\": \"./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue\",\n\t\"./AggregationAdminSummary\": \"./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue\",\n\t\"./AggregationAdminSummary.vue\": \"./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue\",\n\t\"./AggregationAdminView\": \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue\",\n\t\"./AggregationAdminView.vue\": \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue\",\n\t\"./AggregationParticipantForm\": \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue\",\n\t\"./AggregationParticipantForm.vue\": \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/performelement_aggregation/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/performelement_aggregation/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue":
/*!*********************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8&\");\n/* harmony import */ var _AggregationAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AggregationAdminEdit.vue?vue&type=script&lang=js& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AggregationAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _AggregationAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AggregationAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_AggregationAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AggregationAdminEdit.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8& ***!
  \****************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminEdit_vue_vue_type_template_id_7a60b5e8___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue":
/*!************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue ***!
  \************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AggregationAdminSummary.vue?vue&type=template&id=8761e4d8& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8&\");\n/* harmony import */ var _AggregationAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AggregationAdminSummary.vue?vue&type=script&lang=js& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AggregationAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _AggregationAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AggregationAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_AggregationAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AggregationAdminSummary.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8& ***!
  \*******************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminSummary.vue?vue&type=template&id=8761e4d8& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminSummary_vue_vue_type_template_id_8761e4d8___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminView.vue":
/*!*********************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminView.vue ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AggregationAdminView.vue?vue&type=template&id=aacca7b2& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2&\");\n/* harmony import */ var _AggregationAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AggregationAdminView.vue?vue&type=script&lang=js& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AggregationAdminView.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AggregationAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _AggregationAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AggregationAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_AggregationAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_aggregation/src/components/AggregationAdminView.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AggregationAdminView.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminView.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2& ***!
  \****************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationAdminView.vue?vue&type=template&id=aacca7b2& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationAdminView_vue_vue_type_template_id_aacca7b2___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AggregationParticipantForm.vue?vue&type=template&id=47a6366a& */ \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a&\");\n/* harmony import */ var _AggregationParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AggregationParticipantForm.vue?vue&type=script&lang=js& */ \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _AggregationParticipantForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _AggregationParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _AggregationParticipantForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_AggregationParticipantForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./AggregationParticipantForm.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_AggregationParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AggregationParticipantForm.vue?vue&type=template&id=47a6366a& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AggregationParticipantForm_vue_vue_type_template_id_47a6366a___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_aggregation/src/tui.json":
/*!******************************************************************!*\
  !*** ./client/component/performelement_aggregation/src/tui.json ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"performelement_aggregation\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"performelement_aggregation\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"performelement_aggregation\")\ntui._bundle.addModulesFromContext(\"performelement_aggregation/components\", __webpack_require__(\"./client/component/performelement_aggregation/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_aggregation\": [\n    \"add_excluded_value\",\n    \"add_question\",\n    \"aggregate_question_n\",\n    \"calculations_to_display\",\n    \"duplicate_questions_validation_error\",\n    \"excluded_value_n\",\n    \"excluded_values\",\n    \"excluded_values\",\n    \"excluded_values_help_text\",\n    \"no_available_questions\",\n    \"questions_for_response_aggregation\",\n    \"questions_to_aggregate\",\n    \"select_question_element\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_aggregation\": [\n    \"calculations_to_display\",\n    \"excluded_values\",\n    \"excluded_values_help_text\",\n    \"questions_for_response_aggregation\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_aggregation\": [\n    \"aggregated_response_display\",\n    \"header_blurb\",\n    \"header_blurb_with_exclusions\",\n    \"calculated_value_preview_placeholder\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_aggregation\": [\n    \"header_blurb\",\n    \"header_blurb_with_exclusions\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/uniform */ \"tui/components/uniform\");\n/* harmony import */ var tui_components_uniform__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_form_Checkbox__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/form/Checkbox */ \"tui/components/form/Checkbox\");\n/* harmony import */ var tui_components_form_Checkbox__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_Checkbox__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/loading/Loader */ \"tui/components/loading/Loader\");\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_form_Repeater__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/form/Repeater */ \"tui/components/form/Repeater\");\n/* harmony import */ var tui_components_form_Repeater__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_Repeater__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/icons/Add */ \"tui/components/icons/Add\");\n/* harmony import */ var tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/buttons/ButtonIcon */ \"tui/components/buttons/ButtonIcon\");\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! mod_perform/components/element/PerformAdminCustomElementEdit */ \"mod_perform/components/element/PerformAdminCustomElementEdit\");\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var performelement_aggregation_graphql_aggregatable_question_elements__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! performelement_aggregation/graphql/aggregatable_question_elements */ \"./server/mod/perform/element/aggregation/webapi/ajax/aggregatable_question_elements.graphql\");\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Checkbox: (tui_components_form_Checkbox__WEBPACK_IMPORTED_MODULE_1___default()),\n    FormCheckboxGroup: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormCheckboxGroup\"],\n    FormRow: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormRow\"],\n    FormSelect: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormSelect\"],\n    FormNumber: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormNumber\"],\n    Repeater: (tui_components_form_Repeater__WEBPACK_IMPORTED_MODULE_3___default()),\n    FieldArray: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FieldArray\"],\n    AddIcon: (tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_4___default()),\n    ButtonIcon: (tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_5___default()),\n    Loader: (tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default()),\n    PerformAdminCustomElementEdit: (mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_6___default()),\n  },\n  inheritAttrs: false,\n  props: {\n    identifier: String,\n    rawData: Object,\n    rawTitle: String,\n    settings: Object,\n    data: Object,\n    currentActivityId: Number,\n    extraPluginConfigData: Object,\n  },\n  data() {\n    const initialValues = {\n      sourceSectionElementIds: this.createValuesForRepeater(\n        this.rawData.sourceSectionElementIds,\n        false\n      ),\n      calculations: this.rawData.calculations,\n      excludedValues: this.createValuesForRepeater(\n        this.rawData.excludedValues,\n        true\n      ),\n      identifier: this.identifier,\n      rawTitle: this.rawTitle,\n    };\n\n    return {\n      loading: false,\n      initialValues,\n      questionCounter: initialValues.sourceSectionElementIds.length,\n      excludedValuesCounter: initialValues.excludedValues.length,\n    };\n  },\n  computed: {\n    activityAggregatableElementOptions() {\n      const sections = this.rawData.aggregatableSections;\n\n      let elementOptions;\n\n      if (!sections || sections.length === 0) {\n        return [\n          {\n            id: null,\n            label: this.$str(\n              'no_available_questions',\n              'performelement_aggregation'\n            ),\n          },\n        ];\n      }\n\n      // If there are multiple groups\n      if (sections.length > 1) {\n        elementOptions = sections.map(group => {\n          return {\n            label: group.title,\n            options: group.aggregatable_section_elements.map(sectionElement => {\n              return {\n                id: sectionElement.id,\n                label: sectionElement.element.title,\n              };\n            }),\n          };\n        });\n      } else {\n        elementOptions = sections[0].aggregatable_section_elements.map(\n          sectionElement => {\n            return {\n              id: sectionElement.id,\n              label: sectionElement.element.title,\n            };\n          }\n        );\n      }\n\n      let defaultOption = {\n        id: null,\n        label: this.$str(\n          'select_question_element',\n          'performelement_aggregation'\n        ),\n      };\n\n      elementOptions.unshift(defaultOption);\n      return elementOptions;\n    },\n    calculationOptions() {\n      return this.extraPluginConfigData.calculations;\n    },\n  },\n  created() {\n    if (!this.rawData.aggregatableSections) {\n      this.fetchAggregatableSectionElements();\n    }\n  },\n  methods: {\n    /**\n     * Fetch available source section elements,\n     * should only be called on new element creation because this\n     * information is available on the element.raw_data.\n     */\n    async fetchAggregatableSectionElements() {\n      this.loading = true;\n\n      const result = await this.$apollo.query({\n        query: performelement_aggregation_graphql_aggregatable_question_elements__WEBPACK_IMPORTED_MODULE_7__[\"default\"],\n        variables: { input: { activity_id: this.currentActivityId } },\n        fetchPolicy: 'network-only',\n      });\n\n      this.$set(\n        this.rawData,\n        'aggregatableSections',\n        result.data.performelement_aggregation_aggregatable_question_elements\n          .sections\n      );\n\n      this.loading = false;\n    },\n\n    /**\n     * Convert the element data to a format for persisting.\n     */\n    getElementForUpdate(element) {\n      element.data.sourceSectionElementIds = element.data.sourceSectionElementIds.map(\n        option => option.value\n      );\n\n      element.data.excludedValues = element.data.excludedValues.map(\n        option => option.value\n      );\n\n      return element;\n    },\n\n    /**\n     * Convert source section element ids in to a format for the repeater component.\n     * @param values {number[]|undefined}\n     * @param singleEmpty {boolean}\n     * @return {{name: number, value: (null|string|number)}[]}\n     */\n    createValuesForRepeater(values, singleEmpty) {\n      if (!values) {\n        values = [];\n      }\n\n      const forRepeater = values.map((id, index) => {\n        return {\n          name: index,\n          value: id,\n        };\n      });\n\n      if (forRepeater.length === 0) {\n        forRepeater.push(this.createEmptyRepeaterValue(forRepeater.length));\n      }\n\n      if (forRepeater.length === 1 && !singleEmpty) {\n        forRepeater.push(this.createEmptyRepeaterValue(forRepeater.length));\n      }\n\n      return forRepeater;\n    },\n\n    /**\n     * Create an empty object in to a format for the repeater component.\n     * @param name {number}\n     * @returns {{name: number, value: null}}\n     */\n    createEmptyRepeaterValue(name) {\n      return {\n        name,\n        value: null,\n      };\n    },\n\n    /**\n     * Validation rule to ensure the same question is not selected more than once.\n     *\n     * @param items {{name: number, value: number|string}[]}\n     * @return {{value: string|null}[]}\n     */\n    sourceSectionElementsValidation(items) {\n      const message = this.$str(\n        'duplicate_questions_validation_error',\n        'performelement_aggregation'\n      );\n\n      const isDuplicate = itemToCheck => {\n        // Exclude empty values, these are to be handled by the individual required validation.\n        if (!itemToCheck.value) {\n          return;\n        }\n\n        return items.some(\n          item =>\n            item.name !== itemToCheck.name &&\n            Number(item.value) === Number(itemToCheck.value)\n        );\n      };\n\n      return items.map(item => {\n        return {\n          value: isDuplicate(item) ? message : null,\n        };\n      });\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! mod_perform/components/element/PerformAdminCustomElementSummary */ \"mod_perform/components/element/PerformAdminCustomElementSummary\");\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    PerformAdminCustomElementSummary: (mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0___default()),\n  },\n  props: {\n    data: Object,\n    identifier: String,\n    isRequired: Boolean,\n    settings: Object,\n    title: String,\n    type: Object,\n    extraPluginConfigData: Object,\n  },\n  computed: {\n    extraFields() {\n      const extraFields = [\n        {\n          title: this.$str(\n            'questions_for_response_aggregation',\n            'performelement_aggregation'\n          ),\n          options: this.sectionElementsForAggregation.map(sectionElement => {\n            return {\n              value: `${sectionElement.element.title} (${sectionElement.element.element_plugin.name})`,\n            };\n          }),\n        },\n        {\n          title: this.$str(\n            'calculations_to_display',\n            'performelement_aggregation'\n          ),\n          options: this.calculationsToDisplay.map(calculation => {\n            return { value: calculation };\n          }),\n        },\n      ];\n\n      if (this.hasExcludedValues) {\n        extraFields.push({\n          title: this.$str('excluded_values', 'performelement_aggregation'),\n          helpmsg: this.$str(\n            'excluded_values_help_text',\n            'performelement_aggregation'\n          ),\n          options: this.excludedValues.map(calculation => {\n            return { value: calculation };\n          }),\n        });\n      }\n\n      return extraFields;\n    },\n    sectionElementsForAggregation() {\n      return this.data.sourceSectionElementIds.map(id =>\n        this.aggregatableSectionElements.find(\n          sectionElement => Number(sectionElement.id) === Number(id)\n        )\n      );\n    },\n    aggregatableSectionElements() {\n      return this.data.aggregatableSections.reduce(\n        (aggregatable_elements, aggregatableSection) => [\n          ...aggregatable_elements,\n          ...aggregatableSection.aggregatable_section_elements,\n        ],\n        []\n      );\n    },\n    calculationsToDisplay() {\n      const options = this.extraPluginConfigData.calculations;\n\n      return this.data.calculations.map(\n        chosenCalculation =>\n          options.find(option => option.name === chosenCalculation).label\n      );\n    },\n    hasExcludedValues() {\n      return this.excludedValues.length > 0;\n    },\n    excludedValues() {\n      if (!this.data.excludedValues) {\n        return [];\n      }\n\n      // Remove any empty entries.\n      return this.data.excludedValues.filter(\n        value => value !== null && value.trim() !== ''\n      );\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  inheritAttrs: false,\n  props: {\n    data: Object,\n    extraPluginConfigData: Object,\n  },\n  computed: {\n    hasExcludedValues() {\n      return this.excludedValues.length > 0;\n    },\n    excludedValuesCsv() {\n      return this.excludedValues.join(', ');\n    },\n    excludedValues() {\n      if (!this.data.excludedValues) {\n        return [];\n      }\n\n      // Remove any empty entries.\n      return this.data.excludedValues.filter(\n        value => value !== null && value.trim() !== ''\n      );\n    },\n    aggregationTypesPreview() {\n      const value = this.$str(\n        'calculated_value_preview_placeholder',\n        'performelement_aggregation'\n      );\n\n      const options = this.extraPluginConfigData.calculations;\n\n      return this.data.calculations.map(chosenCalculation => {\n        const label = options.find(option => option.name === chosenCalculation)\n          .label;\n\n        return this.$str(\n          'aggregated_response_display',\n          'performelement_aggregation',\n          { label, value }\n        );\n      });\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var mod_perform_components_element_ElementParticipantResponse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! mod_perform/components/element/ElementParticipantResponse */ \"mod_perform/components/element/ElementParticipantResponse\");\n/* harmony import */ var mod_perform_components_element_ElementParticipantResponse__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_ElementParticipantResponse__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var mod_perform_components_element_participant_form_ResponseDisplay__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! mod_perform/components/element/participant_form/ResponseDisplay */ \"mod_perform/components/element/participant_form/ResponseDisplay\");\n/* harmony import */ var mod_perform_components_element_participant_form_ResponseDisplay__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_participant_form_ResponseDisplay__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! mod_perform/components/element/ElementParticipantFormContent */ \"mod_perform/components/element/ElementParticipantFormContent\");\n/* harmony import */ var mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ElementParticipantFormContent: (mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_2___default()),\n    ElementParticipantResponse: (mod_perform_components_element_ElementParticipantResponse__WEBPACK_IMPORTED_MODULE_0___default()),\n    ResponseDisplay: (mod_perform_components_element_participant_form_ResponseDisplay__WEBPACK_IMPORTED_MODULE_1___default()),\n  },\n  props: {\n    path: [String, Array],\n    error: String,\n    isDraft: Boolean,\n    element: {\n      type: Object,\n      required: true,\n    },\n    participantInstanceId: {\n      type: Number,\n      required: false,\n    },\n    sectionElement: {\n      type: Object,\n      required: true,\n    },\n  },\n  computed: {\n    hasExcludedValues() {\n      return this.excludedValues.length > 0;\n    },\n    excludedValuesCsv() {\n      return this.excludedValues.join(', ');\n    },\n    excludedValues() {\n      if (!this.element.data.excludedValues) {\n        return [];\n      }\n\n      // Remove any empty entries.\n      return this.element.data.excludedValues.filter(\n        value => value !== null && String(value).trim() !== ''\n      );\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?vue&type=template&id=7a60b5e8& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-aggregationAdminEdit\"},[_c('PerformAdminCustomElementEdit',{attrs:{\"initial-values\":_vm.initialValues,\"settings\":_vm.settings,\"validation-mode\":\"auto\"},on:{\"cancel\":function($event){return _vm.$emit('display')},\"update\":function($event){_vm.$emit('update', _vm.getElementForUpdate($event))}}},[_c('FormRow',{attrs:{\"label\":_vm.$str('questions_to_aggregate', 'performelement_aggregation'),\"required\":\"\"},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar labelId = ref.labelId;\nreturn [_c('Loader',{attrs:{\"loading\":_vm.loading}},[_c('FieldArray',{attrs:{\"path\":\"sourceSectionElementIds\",\"validate\":_vm.sourceSectionElementsValidation},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar items = ref.items;\nvar push = ref.push;\nvar remove = ref.remove;\nreturn [_c('Repeater',{attrs:{\"rows\":items,\"min-rows\":2,\"delete-icon\":true,\"aria-labelledby\":labelId},on:{\"add\":function($event){return push()},\"remove\":function (item, i) { return remove(i); }},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar index = ref.index;\nreturn [_c('FormSelect',{attrs:{\"aria-label\":_vm.$str(\n                    'aggregate_question_n',\n                    'performelement_aggregation',\n                    index + 1\n                  ),\"options\":_vm.activityAggregatableElementOptions,\"name\":[index, 'value'],\"validations\":function (v) { return [v.required()]; }}})]}},{key:\"add\",fn:function(){return [_c('ButtonIcon',{attrs:{\"aria-label\":_vm.$str('add_question', 'performelement_aggregation'),\"styleclass\":{ small: true },\"text\":_vm.$str('add_question', 'performelement_aggregation')},on:{\"click\":function($event){push(_vm.createEmptyRepeaterValue(_vm.questionCounter++))}}},[_c('AddIcon')],1)]},proxy:true}],null,true)})]}}],null,true)})],1)]}}])}),_vm._v(\" \"),_c('FormRow',{attrs:{\"label\":_vm.$str('calculations_to_display', 'performelement_aggregation'),\"required\":\"\"},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\n                  var labelId = ref.labelId;\nreturn [_c('FormCheckboxGroup',{attrs:{\"validations\":function (v) { return [v.required()]; },\"name\":\"calculations\"}},_vm._l((_vm.calculationOptions),function(calculationOption){return _c('Checkbox',{key:calculationOption.name,attrs:{\"value\":calculationOption.name}},[_vm._v(\"\\n          \"+_vm._s(calculationOption.label)+\"\\n        \")])}),1)]}}])}),_vm._v(\" \"),_c('FormRow',{attrs:{\"label\":_vm.$str('excluded_values', 'performelement_aggregation'),\"helpmsg\":_vm.$str('excluded_values_help_text', 'performelement_aggregation')},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\n                  var labelId = ref.labelId;\nreturn [_c('FieldArray',{attrs:{\"path\":\"excludedValues\"},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\n                  var items = ref.items;\n                  var push = ref.push;\n                  var remove = ref.remove;\nreturn [_c('Repeater',{attrs:{\"rows\":items,\"min-rows\":1,\"delete-icon\":true,\"aria-labelledby\":labelId},on:{\"add\":function($event){return push()},\"remove\":function (item, i) { return remove(i); }},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\n                  var index = ref.index;\nreturn [_c('FormNumber',{attrs:{\"aria-label\":_vm.$str(\n                  'excluded_value_n',\n                  'performelement_aggregation',\n                  index + 1\n                ),\"name\":[index, 'value'],\"char-length\":\"4\"}})]}},{key:\"add\",fn:function(){return [_c('ButtonIcon',{attrs:{\"aria-label\":_vm.$str('add_excluded_value', 'performelement_aggregation'),\"styleclass\":{ small: true }},on:{\"click\":function($event){push(_vm.createEmptyRepeaterValue(_vm.excludedValuesCounter++))}}},[_c('AddIcon')],1)]},proxy:true}],null,true)})]}}],null,true)})]}}])})],1)],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminEdit.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?vue&type=template&id=8761e4d8& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-redisplayAdminSummary\"},[_c('PerformAdminCustomElementSummary',{attrs:{\"extra-fields\":_vm.extraFields,\"identifier\":_vm.identifier,\"is-required\":_vm.isRequired,\"settings\":_vm.settings,\"title\":_vm.title},on:{\"display\":function($event){return _vm.$emit('display')}}})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminSummary.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?vue&type=template&id=aacca7b2& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-aggregationAdminView\"},[(_vm.hasExcludedValues)?_c('div',[_vm._v(\"\\n    \"+_vm._s(_vm.$str(\n        'header_blurb_with_exclusions',\n        'performelement_aggregation',\n        _vm.excludedValuesCsv\n      ))+\"\\n  \")]):_c('div',{staticClass:\"tui-aggregationAdminView__blurb\"},[_vm._v(\"\\n    \"+_vm._s(_vm.$str('header_blurb', 'performelement_aggregation'))+\"\\n  \")]),_vm._v(\" \"),_c('div',_vm._l((_vm.aggregationTypesPreview),function(preview,i){return _c('div',{key:i},[_vm._v(\"\\n      \"+_vm._s(preview)+\"\\n    \")])}),0)])}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationAdminView.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?vue&type=template&id=47a6366a& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-aggregationParticipantForm\"},[(_vm.hasExcludedValues)?_c('div',[_vm._v(\"\\n    \"+_vm._s(_vm.$str(\n        'header_blurb_with_exclusions',\n        'performelement_aggregation',\n        _vm.excludedValuesCsv\n      ))+\"\\n  \")]):_c('div',[_vm._v(\"\\n    \"+_vm._s(_vm.$str('header_blurb', 'performelement_aggregation'))+\"\\n  \")]),_vm._v(\" \"),_c('ElementParticipantFormContent',_vm._b({attrs:{\"participant-can-answer\":_vm.sectionElement.can_respond,\"element\":_vm.element,\"section-element\":_vm.sectionElement,\"from-print\":false,\"is-draft\":_vm.isDraft},scopedSlots:_vm._u([{key:\"content\",fn:function(ref){\n      var labelId = ref.labelId;\nreturn [_c('ElementParticipantResponse',{scopedSlots:_vm._u([{key:\"content\",fn:function(){return [_c('ResponseDisplay',{attrs:{\"element\":_vm.element,\"data\":_vm.sectionElement.response_data_formatted_lines}})]},proxy:true}],null,true)})]}}])},'ElementParticipantFormContent',_vm.$attrs,false))],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_aggregation/src/components/AggregationParticipantForm.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "./server/mod/perform/element/aggregation/webapi/ajax/aggregatable_question_elements.graphql":
/*!***************************************************************************************************!*\
  !*** ./server/mod/perform/element/aggregation/webapi/ajax/aggregatable_question_elements.graphql ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_aggregation_aggregatable_question_elements\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"aggregatable_question_elements_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_aggregation_aggregatable_question_elements\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"sections\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"title\"},\"name\":{\"kind\":\"Name\",\"value\":\"display_title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"aggregatable_section_elements\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"element\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"element_plugin\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/perform/element/aggregation/webapi/ajax/aggregatable_question_elements.graphql?");

/***/ }),

/***/ "mod_perform/components/element/ElementParticipantFormContent":
/*!************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/ElementParticipantFormContent\")" ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/ElementParticipantFormContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/ElementParticipantFormContent\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/ElementParticipantResponse":
/*!*********************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/ElementParticipantResponse\")" ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/ElementParticipantResponse\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/ElementParticipantResponse\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/PerformAdminCustomElementEdit":
/*!************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/PerformAdminCustomElementEdit\")" ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/PerformAdminCustomElementEdit\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/PerformAdminCustomElementEdit\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/PerformAdminCustomElementSummary":
/*!***************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/PerformAdminCustomElementSummary\")" ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/PerformAdminCustomElementSummary\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/PerformAdminCustomElementSummary\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/participant_form/ResponseDisplay":
/*!***************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/participant_form/ResponseDisplay\")" ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/participant_form/ResponseDisplay\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/participant_form/ResponseDisplay\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/ButtonIcon":
/*!*********************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/ButtonIcon\")" ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/ButtonIcon\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/ButtonIcon\\%22)%22?");

/***/ }),

/***/ "tui/components/form/Checkbox":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/form/Checkbox\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/form/Checkbox\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/form/Checkbox\\%22)%22?");

/***/ }),

/***/ "tui/components/form/Repeater":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/form/Repeater\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/form/Repeater\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/form/Repeater\\%22)%22?");

/***/ }),

/***/ "tui/components/icons/Add":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/Add\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/Add\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/Add\\%22)%22?");

/***/ }),

/***/ "tui/components/loading/Loader":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/loading/Loader\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/loading/Loader\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/loading/Loader\\%22)%22?");

/***/ }),

/***/ "tui/components/uniform":
/*!**********************************************************!*\
  !*** external "tui.require(\"tui/components/uniform\")" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/uniform\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/uniform\\%22)%22?");

/***/ })

/******/ });