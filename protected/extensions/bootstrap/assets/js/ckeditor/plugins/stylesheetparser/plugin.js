﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function i(b, h, d) {
			var e = [], g = [], a;
			for (a = 0; a < b.styleSheets.length; a++) {
				var c = b.styleSheets[a];
				if (!(
					c.ownerNode || c.owningElement
					).getAttribute("data-cke-temp") && !(
					c.href && "chrome://" == c.href.substr(0, 9)
					)) {
					try {
						for (var f = c.cssRules || c.rules, c = 0; c < f.length; c++) {
							g.push(f[c].selectorText)
						}
					} catch (i) {
					}
				}
			}
			a = g.join(" ");
			a = a.replace(/(,|>|\+|~)/g, " ");
			a = a.replace(/\[[^\]]*/g, "");
			a = a.replace(/#[^\s]*/g, "");
			a = a.replace(/\:{1,2}[^\s]*/g, "");
			a = a.replace(/\s+/g, " ");
			a = a.split(" ");
			b = [];
			for (g = 0; g < a.length; g++) {
				f =
					a[g], d.test(f) && !h.test(f) && -1 == CKEDITOR.tools.indexOf(b, f) && b.push(f);
			}
			for (a = 0; a < b.length; a++) {
				d = b[a].split("."), h = d[0].toLowerCase(), d = d[1], e.push({name: h + "." + d, element: h, attributes: {"class": d}});
			}
			return e
		}

		CKEDITOR.plugins.add("stylesheetparser", {onLoad: function () {
			var b = CKEDITOR.editor.prototype;
			b.getStylesSet = CKEDITOR.tools.override(b.getStylesSet, function (b) {
				return function (d) {
					var e = this;
					b.call(this, function (b) {
						d(e._.stylesDefinitions = b.concat(i(e.document.$, e.config.stylesheetParser_skipSelectors ||
							/(^body\.|^\.)/i, e.config.stylesheetParser_validSelectors || /\w+\.\w+/)))
					})
				}
			})
		}})
	}
	)();