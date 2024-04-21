(self["webpackChunk"] = self["webpackChunk"] || []).push([["public_src_plugins_datatables_js_responsive_bootstrap4_min_js"],{

/***/ "./public/src/plugins/datatables/js/responsive.bootstrap4.min.js":
/*!***********************************************************************!*\
  !*** ./public/src/plugins/datatables/js/responsive.bootstrap4.min.js ***!
  \***********************************************************************/
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

/*!
 Bootstrap 4 integration for DataTables' Responsive
 ©2016 SpryMedia Ltd - datatables.net/license
*/
(function (c) {
   true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), Object(function webpackMissingModule() { var e = new Error("Cannot find module 'datatables.net-bs4'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()), Object(function webpackMissingModule() { var e = new Error("Cannot find module 'datatables.net-responsive'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())], __WEBPACK_AMD_DEFINE_RESULT__ = (function (a) {
    return c(a, window, document);
  }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : 0;
})(function (c) {
  var a = c.fn.dataTable,
      b = a.Responsive.display,
      g = b.modal,
      e = c('<div class="modal fade dtr-bs-modal" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"/></div></div></div>');

  b.modal = function (a) {
    return function (b, d, f) {
      if (c.fn.modal) {
        if (!d) {
          if (a && a.header) {
            var d = e.find("div.modal-header"),
                h = d.find("button").detach();
            d.empty().append('<h4 class="modal-title">' + a.header(b) + "</h4>").append(h);
          }

          e.find("div.modal-body").empty().append(f());
          e.appendTo("body").modal();
        }
      } else g(b, d, f);
    };
  };

  return a.Responsive;
});

/***/ })

}]);