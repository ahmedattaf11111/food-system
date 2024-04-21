export default function test() {
	'use strict';
	// datatable init

	$(document).ready(function () {
	
		$("#accordion-menu").vmenuModule({
			Speed: 400,
			autostart: false,
			autohide: true
		});
		// sidebar menu icon
		$('.menu-icon, [data-toggle="left-sidebar-close"]').on('click', function () {
			//$(this).toggleClass('open');
			$('.left-side-bar').toggleClass('open');
			$('.mobile-menu-overlay').toggleClass('show');
		});
		$('[data-toggle="header_search"]').on('click', function () {
			jQuery('.header-search').slideToggle();
		});

		// sidebar menu Active Class
		$('#accordion-menu').each(function () {
			var vars = window.location.href.split("/").pop();
			$(this).find('a[href="' + vars + '"]').addClass('active');
		});
		// sidebar menu accordion
		(function ($) {
			$.fn.vmenuModule = function (option) {
				var obj,
					item;
				var options = $.extend({
					Speed: 220,
					autostart: true,
					autohide: 1
				},
					option);
				obj = $(this);

				item = obj.find("ul").parent("li").children("a");
				item.attr("data-option", "off");

				item.unbind('click').on("click", function () {
					var a = $(this);
					if (options.autohide) {
						a.parent().parent().find("a[data-option='on']").parent("li").children("ul").slideUp(options.Speed / 1.2,
							function () {
								$(this).parent("li").children("a").attr("data-option", "off");
								$(this).parent("li").removeClass("show");
							})
					}
					if (a.attr("data-option") == "off") {
						a.parent("li").children("ul").slideDown(options.Speed,
							function () {
								a.attr("data-option", "on");
								a.parent('li').addClass("show");
							});
					}
					if (a.attr("data-option") == "on") {
						a.attr("data-option", "off");
						a.parent("li").children("ul").slideUp(options.Speed)
						a.parent('li').removeClass("show");
					}
				});
				if (options.autostart) {
					obj.find("a").each(function () {

						$(this).parent("li").parent("ul").slideDown(options.Speed,
							function () {
								$(this).parent("li").children("a").attr("data-option", "on");
							})
					})
				}
				else {
					obj.find("a.active").each(function () {

						$(this).parent("li").parent("ul").slideDown(options.Speed,
							function () {
								$(this).parent("li").children("a").attr("data-option", "on");
								$(this).parent('li').addClass("show");
							})
					})
				}

			}
		})(window.jQuery || window.Zepto);
		var w = $(window).width();
		$(document).on('touchstart click', function (e) {
			if ($(e.target).parents('.left-side-bar').length == 0 && !$(e.target).is('.menu-icon, .menu-icon img')) {
				$('.left-side-bar').removeClass('open');
				$('.menu-icon').removeClass('open');
				$('.mobile-menu-overlay').removeClass('show');
			};
		});
		$(window).on('resize', function () {
			var w = $(window).width();
			if ($(window).width() > 1200) {
				$('.left-side-bar').removeClass('open');
				$('.menu-icon').removeClass('open');
				$('.mobile-menu-overlay').removeClass('show');
			}
		});
		!function ($, wysi) {
			"use strict";

			var tpl = {
				"font-styles": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li class='dropdown'>" +
						"<a class='btn dropdown-toggle" + size + "' data-toggle='dropdown' href='#'>" +
						"<i class='fa fa-font'></i>&nbsp;<span class='current-font'>" + locale.font_styles.normal + "</span>&nbsp;<b class='caret'></b>" +
						"</a>" +
						"<ul class='dropdown-menu'>" +
						"<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='div'>" + locale.font_styles.normal + "</a></li>" +
						"<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h1'>" + locale.font_styles.h1 + "</a></li>" +
						"<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h2'>" + locale.font_styles.h2 + "</a></li>" +
						"<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h3'>" + locale.font_styles.h3 + "</a></li>" +
						"</ul>" +
						"</li>";
				},

				"emphasis": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li>" +
						"<div class='btn-group'>" +
						"<a class='btn" + size + "' data-wysihtml5-command='bold' title='CTRL+B'>" + locale.emphasis.bold + "</a>" +
						"<a class='btn" + size + "' data-wysihtml5-command='italic' title='CTRL+I'>" + locale.emphasis.italic + "</a>" +
						"<a class='btn" + size + "' data-wysihtml5-command='underline' title='CTRL+U'>" + locale.emphasis.underline + "</a>" +
						"</div>" +
						"</li>";
				},

				"lists": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li>" +
						"<div class='btn-group'>" +
						"<a class='btn" + size + "' data-wysihtml5-command='insertUnorderedList' title='" + locale.lists.unordered + "'><i class='fa fa-list'></i></a>" +
						"<a class='btn" + size + "' data-wysihtml5-command='insertOrderedList' title='" + locale.lists.ordered + "'><i class='fa fa-th-list'></i></a>" +
						"<a class='btn" + size + "' data-wysihtml5-command='Outdent' title='" + locale.lists.outdent + "'><i class='fa fa-outdent'></i></a>" +
						"<a class='btn" + size + "' data-wysihtml5-command='Indent' title='" + locale.lists.indent + "'><i class='fa fa-indent'></i></a>" +
						"</div>" +
						"</li>";
				},

				"link": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li>" +
						"<div class='bootstrap-wysihtml5-insert-link-modal modal fade bs-example-modal-lg'>" +
						"<div class='modal-dialog modal-lg'>" +
						"<div class='modal-content'>" +
						"<div class='modal-header'>" +
						"<a class='close' data-dismiss='modal'></a>" +
						"<h3>" + locale.link.insert + "</h3>" +
						"</div>" +
						"<div class='modal-body'>" +
						"<div class='form-group'>" +
						"<input value='http://' class='bootstrap-wysihtml5-insert-link-url form-control' type='text'>" +
						"</div>" +
						"</div>" +
						"<div class='modal-footer'>" +
						"<a href='#' class='btn btn-inverse' data-dismiss='modal'>" + locale.link.cancel + "</a>" +
						"<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.link.insert + "</a>" +
						"</div>" +
						"</div>" +
						"</div>" +
						"</div>" +
						"<a class='btn" + size + "' data-wysihtml5-command='createLink' title='" + locale.link.insert + "'><i class='fa fa-link'></i></a>" +
						"</li>";
				},

				"image": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li>" +
						"<div class='bootstrap-wysihtml5-insert-image-modal modal fade bs-example-modal-lg'>" +
						"<div class='modal-dialog modal-lg'>" +
						"<div class='modal-content'>" +
						"<div class='modal-header'>" +
						"<a class='close' data-dismiss='modal'></a>" +
						"<h3>" + locale.image.insert + "</h3>" +
						"</div>" +
						"<div class='modal-body'>" +
						"<div class='form-group'>" +
						"<input value='http://' class='bootstrap-wysihtml5-insert-image-url  m-wrap large form-control' type='text'>" +
						"</div>" +
						"</div>" +
						"<div class='modal-footer'>" +
						"<a href='#' class='btn' data-dismiss='modal'>" + locale.image.cancel + "</a>" +
						"<a href='#' class='btn  green btn-primary' data-dismiss='modal'>" + locale.image.insert + "</a>" +
						"</div>" +
						"</div>" +
						"</div>" +
						"</div>" +
						"<a class='btn" + size + "' data-wysihtml5-command='insertImage' title='" + locale.image.insert + "'><i class='fa fa-image '></i></a>" +
						"</li>";
				},

				"html": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li>" +
						"<div class='btn-group'>" +
						"<a class='btn" + size + "' data-wysihtml5-action='change_view' title='" + locale.html.edit + "'><i class='fa fa-pencil'></i></a>" +
						"</div>" +
						"</li>";
				},

				"color": function (locale, options) {
					var size = (options && options.size) ? ' btn-' + options.size : '';
					return "<li class='dropdown'>" +
						"<a class='btn dropdown-toggle" + size + "' data-toggle='dropdown' href='#'>" +
						"<span class='current-color'>" + locale.colours.black + "</span>&nbsp;<b class='caret'></b>" +
						"</a>" +
						"<ul class='dropdown-menu'>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='black'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='black'>" + locale.colours.black + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='silver'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='silver'>" + locale.colours.silver + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='gray'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='gray'>" + locale.colours.gray + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='maroon'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='maroon'>" + locale.colours.maroon + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='red'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='red'>" + locale.colours.red + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='purple'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='purple'>" + locale.colours.purple + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='green'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='green'>" + locale.colours.green + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='olive'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='olive'>" + locale.colours.olive + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='navy'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='navy'>" + locale.colours.navy + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='blue'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='blue'>" + locale.colours.blue + "</a></li>" +
						"<li><div class='wysihtml5-colors' data-wysihtml5-command-value='orange'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='orange'>" + locale.colours.orange + "</a></li>" +
						"</ul>" +
						"</li>";
				}
			};

			var templates = function (key, locale, options) {
				return tpl[key](locale, options);
			};


			var Wysihtml5 = function (el, options) {
				this.el = el;
				var toolbarOpts = options || defaultOptions;
				for (var t in toolbarOpts.customTemplates) {
					tpl[t] = toolbarOpts.customTemplates[t];
				}
				this.toolbar = this.createToolbar(el, toolbarOpts);
				this.editor = this.createEditor(options);

				window.editor = this.editor;

				$('iframe.wysihtml5-sandbox').each(function (i, el) {
					$(el.contentWindow).off('focus.wysihtml5').on({
						'focus.wysihtml5': function () {
							$('li.dropdown').removeClass('open');
						}
					});
				});
			};

			Wysihtml5.prototype = {

				constructor: Wysihtml5,

				createEditor: function (options) {
					options = options || {};
					options.toolbar = this.toolbar[0];

					var editor = new wysi.Editor(this.el[0], options);

					if (options && options.events) {
						for (var eventName in options.events) {
							editor.on(eventName, options.events[eventName]);
						}
					}
					return editor;
				},

				createToolbar: function (el, options) {
					var self = this;
					var toolbar = $("<ul/>", {
						'class': "wysihtml5-toolbar",
						'style': "display:none"
					});
					var culture = options.locale || defaultOptions.locale || "en";
					for (var key in defaultOptions) {
						var value = false;

						if (options[key] !== undefined) {
							if (options[key] === true) {
								value = true;
							}
						} else {
							value = defaultOptions[key];
						}

						if (value === true) {
							toolbar.append(templates(key, locale[culture], options));

							if (key === "html") {
								this.initHtml(toolbar);
							}

							if (key === "link") {
								this.initInsertLink(toolbar);
							}

							if (key === "image") {
								this.initInsertImage(toolbar);
							}
						}
					}

					if (options.toolbar) {
						for (key in options.toolbar) {
							toolbar.append(options.toolbar[key]);
						}
					}

					toolbar.find("a[data-wysihtml5-command='formatBlock']").click(function (e) {
						var target = e.target || e.srcElement;
						var el = $(target);
						self.toolbar.find('.current-font').text(el.html());
					});

					toolbar.find("a[data-wysihtml5-command='foreColor']").click(function (e) {
						var target = e.target || e.srcElement;
						var el = $(target);
						self.toolbar.find('.current-color').text(el.html());
					});

					this.el.before(toolbar);

					return toolbar;
				},

				initHtml: function (toolbar) {
					var changeViewSelector = "a[data-wysihtml5-action='change_view']";
					toolbar.find(changeViewSelector).click(function (e) {
						toolbar.find('a.btn').not(changeViewSelector).toggleClass('disabled');
					});
				},

				initInsertImage: function (toolbar) {
					var self = this;
					var insertImageModal = toolbar.find('.bootstrap-wysihtml5-insert-image-modal');
					var urlInput = insertImageModal.find('.bootstrap-wysihtml5-insert-image-url');
					var insertButton = insertImageModal.find('a.btn-primary');
					var initialValue = urlInput.val();

					var insertImage = function () {
						var url = urlInput.val();
						urlInput.val(initialValue);
						self.editor.currentView.element.focus();
						self.editor.composer.commands.exec("insertImage", url);
					};

					urlInput.keypress(function (e) {
						if (e.which == 13) {
							insertImage();
							insertImageModal.modal('hide');
						}
					});

					insertButton.click(insertImage);

					insertImageModal.on('shown', function () {
						urlInput.focus();
					});

					insertImageModal.on('hide', function () {
						self.editor.currentView.element.focus();
					});

					toolbar.find('a[data-wysihtml5-command=insertImage]').click(function () {
						var activeButton = $(this).hasClass("wysihtml5-command-active");

						if (!activeButton) {
							insertImageModal.modal('show');
							insertImageModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function (e) {
								e.stopPropagation();
							});
							return false;
						}
						else {
							return true;
						}
					});
				},

				initInsertLink: function (toolbar) {
					var self = this;
					var insertLinkModal = toolbar.find('.bootstrap-wysihtml5-insert-link-modal');
					var urlInput = insertLinkModal.find('.bootstrap-wysihtml5-insert-link-url');
					var insertButton = insertLinkModal.find('a.btn-primary');
					var initialValue = urlInput.val();

					var insertLink = function () {
						var url = urlInput.val();
						urlInput.val(initialValue);
						self.editor.currentView.element.focus();
						self.editor.composer.commands.exec("createLink", {
							href: url,
							target: "_blank",
							rel: "nofollow"
						});
					};
					var pressedEnter = false;

					urlInput.keypress(function (e) {
						if (e.which == 13) {
							insertLink();
							insertLinkModal.modal('hide');
						}
					});

					insertButton.click(insertLink);

					insertLinkModal.on('shown', function () {
						urlInput.focus();
					});

					insertLinkModal.on('hide', function () {
						self.editor.currentView.element.focus();
					});

					toolbar.find('a[data-wysihtml5-command=createLink]').click(function () {
						var activeButton = $(this).hasClass("wysihtml5-command-active");

						if (!activeButton) {
							insertLinkModal.appendTo('body').modal('show');
							insertLinkModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function (e) {
								e.stopPropagation();
							});
							return false;
						}
						else {
							return true;
						}
					});
				}
			};

			// these define our public api
			var methods = {
				resetDefaults: function () {
					$.fn.wysihtml5.defaultOptions = $.extend(true, {}, $.fn.wysihtml5.defaultOptionsCache);
				},
				bypassDefaults: function (options) {
					return this.each(function () {
						var $this = $(this);
						$this.data('wysihtml5', new Wysihtml5($this, options));
					});
				},
				shallowExtend: function (options) {
					var settings = $.extend({}, $.fn.wysihtml5.defaultOptions, options || {});
					var that = this;
					return methods.bypassDefaults.apply(that, [settings]);
				},
				deepExtend: function (options) {
					var settings = $.extend(true, {}, $.fn.wysihtml5.defaultOptions, options || {});
					var that = this;
					return methods.bypassDefaults.apply(that, [settings]);
				},
				init: function (options) {
					var that = this;
					return methods.shallowExtend.apply(that, [options]);
				}
			};

			$.fn.wysihtml5 = function (method) {
				if (methods[method]) {
					return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
				} else if (typeof method === 'object' || !method) {
					return methods.init.apply(this, arguments);
				} else {
					$.error('Method ' + method + ' does not exist on jQuery.wysihtml5');
				}
			};

			$.fn.wysihtml5.Constructor = Wysihtml5;

			var defaultOptions = $.fn.wysihtml5.defaultOptions = {
				"font-styles": true,
				"color": false,
				"emphasis": true,
				"lists": true,
				"html": false,
				"link": true,
				"image": true,
				events: {},
				parserRules: {
					classes: {
						// (path_to_project/lib/css/wysiwyg-color.css)
						"wysiwyg-color-silver": 1,
						"wysiwyg-color-gray": 1,
						"wysiwyg-color-white": 1,
						"wysiwyg-color-maroon": 1,
						"wysiwyg-color-red": 1,
						"wysiwyg-color-purple": 1,
						"wysiwyg-color-fuchsia": 1,
						"wysiwyg-color-green": 1,
						"wysiwyg-color-lime": 1,
						"wysiwyg-color-olive": 1,
						"wysiwyg-color-yellow": 1,
						"wysiwyg-color-navy": 1,
						"wysiwyg-color-blue": 1,
						"wysiwyg-color-teal": 1,
						"wysiwyg-color-aqua": 1,
						"wysiwyg-color-orange": 1
					},
					tags: {
						"b": {},
						"i": {},
						"br": {},
						"ol": {},
						"ul": {},
						"li": {},
						"h1": {},
						"h2": {},
						"h3": {},
						"blockquote": {},
						"u": 1,
						"img": {
							"check_attributes": {
								"width": "numbers",
								"alt": "alt",
								"src": "url",
								"height": "numbers"
							}
						},
						"a": {
							set_attributes: {
								target: "_blank",
								rel: "nofollow"
							},
							check_attributes: {
								href: "url" // important to avoid XSS
							}
						},
						"span": 1,
						"div": 1
					}
				},
				//stylesheets: ["./lib/css/wysiwyg-color.css"], // (path_to_project/lib/css/wysiwyg-color.css)
				locale: "en"
			};

			if (typeof $.fn.wysihtml5.defaultOptionsCache === 'undefined') {
				$.fn.wysihtml5.defaultOptionsCache = $.extend(true, {}, $.fn.wysihtml5.defaultOptions);
			}

			var locale = $.fn.wysihtml5.locale = {
				en: {
					font_styles: {
						normal: "Normal text",
						h1: "Heading 1",
						h2: "Heading 2",
						h3: "Heading 3"
					},
					emphasis: {
						bold: "Bold",
						italic: "Italic",
						underline: "Underline"
					},
					lists: {
						unordered: "Unordered list",
						ordered: "Ordered list",
						outdent: "Outdent",
						indent: "Indent"
					},
					link: {
						insert: "Insert link",
						cancel: "Cancel"
					},
					image: {
						insert: "Insert image",
						cancel: "Cancel"
					},
					html: {
						edit: "Edit HTML"
					},
					colours: {
						black: "Black",
						silver: "Silver",
						gray: "Grey",
						maroon: "Maroon",
						red: "Red",
						purple: "Purple",
						green: "Green",
						olive: "Olive",
						navy: "Navy",
						blue: "Blue",
						orange: "Orange"
					}
				}
			};

		}(window.jQuery, window.wysihtml5);
		// Store object for local storage data
		var currentOptions = {
			headerBackground: "header-white",
			navigationBackground: "sidebar-dark",
			menuDropdownIcon: 'icon-style-1',
			menuListIcon: 'icon-list-style-1',
		}

		/**
		 * Get local storage value
		 */
		function getOptions() {
			return JSON.parse(localStorage.getItem("optionsObject"))
		}

		/**
		 * Set local storage property value
		 */
		function setOptions(propertyName, propertyValue) {

			//Store in local storage
			var optionsCopy = Object.assign({}, currentOptions);
			optionsCopy[propertyName] = propertyValue

			//Store in local storage
			localStorage.setItem("optionsObject", JSON.stringify(optionsCopy));
		}

		if (getOptions() != null) {
			currentOptions = getOptions()
		} else {
			localStorage.setItem("optionsObject", JSON.stringify(currentOptions));
		}

		/**
		 * Clear local storage
		 */
		function clearOptions() {
			localStorage.removeItem("optionsObject");
		}

		// Set localstorage value to variable
		if (getOptions() != null) {
			currentOptions = getOptions()
		} else {
			localStorage.setItem("optionsObject", JSON.stringify(currentOptions));
		}

		//Layout settings visible
		$('[data-toggle="right-sidebar"]').on('click', function () {
			jQuery('.right-sidebar').addClass('right-sidebar-visible');
		});

		//THEME OPTION CLOSE BUTTON
		$('[data-toggle="right-sidebar-close"]').on('click', function () {
			jQuery('.right-sidebar').removeClass('right-sidebar-visible');
		})

		//VARIABLE
		var body = jQuery('body');
		var left_sidebar = jQuery('.left-side-bar');


		// Header Background
		var header_dark = jQuery('.header-dark');
		var header_light = jQuery('.header-white');

		header_dark.click(function () {
			'use strict';
			jQuery(this).addClass('active');
			header_light.removeClass('active');
			body.removeClass('header-white').addClass('header-dark');

			//Store in local storage
			setOptions("headerBackground", "header-dark")
		});

		//Click for current options
		if (currentOptions.headerBackground === "header-dark") {
			header_dark.trigger("click");
		}

		header_light.click(function () {
			'use strict';
			jQuery(this).addClass('active');
			header_dark.removeClass('active');
			body.removeClass('header-dark').addClass('header-white');

			//Store in local storage
			setOptions("headerBackground", "header-white")
		});

		//Click for current options
		if (currentOptions.headerBackground === "header-white") {
			header_light.trigger("click")
		}

		// Sidebar Background
		var sidebar_dark = jQuery('.sidebar-dark');
		var sidebar_light = jQuery('.sidebar-light');

		sidebar_dark.click(function () {
			'use strict';
			jQuery(this).addClass('active');
			sidebar_light.removeClass('active');
			body.removeClass('sidebar-light').addClass('sidebar-dark');

			//Store in local storage
			setOptions("navigationBackground", "sidebar-dark")
		});

		//Click for current options
		if (currentOptions.navigationBackground === "sidebar-dark") {
			sidebar_dark.trigger("click")
		}

		sidebar_light.click(function () {
			'use strict';
			jQuery(this).addClass('active');
			sidebar_dark.removeClass('active');
			body.removeClass('sidebar-dark').addClass('sidebar-light');

			//Store in local storage
			setOptions("navigationBackground", "sidebar-light")
		});

		//Click for current options
		if (currentOptions.navigationBackground === "sidebar-light") {
			sidebar_light.trigger("click")
		}

		// Menu Dropdown Icon
		$('input:radio[name=menu-dropdown-icon]').change(function () {
			// var className = $('input:radio[name=menu-dropdown-icon]:checked').val().toLowerCase().replace(/\s+/, "-");
			// $(".sidebar-menu").attr('class', 'sidebar-menu ' + className);
			// setOptions("menuDropdownIcon", className);
			var newClass1 = ['sidebar-menu'];
			newClass1.push($('input:radio[name=menu-dropdown-icon]:checked').val().toLowerCase().replace(/\s+/, "-"));
			newClass1.push($('input:radio[name=menu-list-icon]:checked').val().toLowerCase().replace(/\s+/, "-"));
			$(".sidebar-menu").attr('class', newClass1.join(' '));
			setOptions("menuDropdownIcon", newClass1.slice(-2)[0]);
		});
		// if (currentOptions.menuDropdownIcon === "icon-style-1") {
		// 	$('input:radio[value=icon-style-1]').trigger("click")
		// }
		// if (currentOptions.menuDropdownIcon === "icon-style-2") {
		// 	$('input:radio[value=icon-style-2]').trigger("click")
		// }
		// if (currentOptions.menuDropdownIcon === "icon-style-3") {
		$('input:radio[value=icon-style-1]').trigger("click")
		// }

		// Menu List Icon
		$('input:radio[name=menu-list-icon]').change(function () {
			var newClass = ['sidebar-menu'];
			newClass.push($('input:radio[name=menu-dropdown-icon]:checked').val().toLowerCase().replace(/\s+/, "-"));
			newClass.push($('input:radio[name=menu-list-icon]:checked').val().toLowerCase().replace(/\s+/, "-"));
			$(".sidebar-menu").attr('class', newClass.join(' '));
			setOptions("menuListIcon", newClass.slice(-1)[0]);
		});
		if (currentOptions.menuListIcon === "icon-list-style-1") {
			$('input:radio[value=icon-list-style-1]').trigger("click")
		}
		if (currentOptions.menuListIcon === "icon-list-style-2") {
			$('input:radio[value=icon-list-style-2]').trigger("click")
		}
		if (currentOptions.menuListIcon === "icon-list-style-3") {
			$('input:radio[value=icon-list-style-3]').trigger("click")
		}
		if (currentOptions.menuListIcon === "icon-list-style-4") {
			$('input:radio[value=icon-list-style-4]').trigger("click")
		}
		if (currentOptions.menuListIcon === "icon-list-style-5") {
			$('input:radio[value=icon-list-style-5]').trigger("click")
		}
		if (currentOptions.menuListIcon === "icon-list-style-6") {
			$('input:radio[value=icon-list-style-6]').trigger("click")
		}

		// custom select 2 init
		$('.custom-select2').select2();


		$('#reset-settings').click(function () {
			clearOptions();
			location.reload();
		});
		// custom scrollbar
		$(".customscroll").mCustomScrollbar({
			theme: "dark-2",
			scrollInertia: 300,
			direction: "left",
			autoExpandScrollbar: true,
			advanced: { autoExpandHorizontalScroll: true }
		});

	});
}