﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function e(c) {
			var b = this.att, c = c && c.hasAttribute(b) && c.getAttribute(b) || "";
			void 0 !== c && this.setValue(c)
		}

		function f() {
			for (var c, b = 0; b < arguments.length; b++) {
				if (arguments[b]instanceof CKEDITOR.dom.element) {
					c = arguments[b];
					break
				}
			}
			if (c) {
				var b = this.att, a = this.getValue();
				a ? c.setAttribute(b, a) : c.removeAttribute(b, a)
			}
		}

		CKEDITOR.plugins.add("dialogadvtab", {createAdvancedTab: function (c, b) {
			b || (
				b = {id: 1, dir: 1, classes: 1, styles: 1}
				);
			var a = c.lang.common, g = {id: "advanced", label: a.advancedTab, title: a.advancedTab,
				elements: [
					{type: "vbox", padding: 1, children: []}
				]}, d = [];
			if (b.id || b.dir) {
				b.id && d.push({id: "advId", att: "id", type: "text", label: a.id, setup: e, commit: f}), b.dir && d.push({id: "advLangDir", att: "dir", type: "select", label: a.langDir, "default": "", style: "width:100%", items: [
					[a.notSet, ""],
					[a.langDirLTR, "ltr"],
					[a.langDirRTL, "rtl"]
				], setup: e, commit: f}), g.elements[0].children.push({type: "hbox", widths: ["50%", "50%"], children: [].concat(d)});
			}
			if (b.styles || b.classes) {
				d = [], b.styles && d.push({id: "advStyles", att: "style", type: "text",
					label: a.styles, "default": "", validate: CKEDITOR.dialog.validate.inlineStyle(a.invalidInlineStyle), onChange: function () {
					}, getStyle: function (b, a) {
						var c = this.getValue().match(RegExp(b + "\\s*:\\s*([^;]*)", "i"));
						return c ? c[1] : a
					}, updateStyle: function (b, c) {
						var a = this.getValue();
						a && (
							a = a.replace(RegExp("\\s*" + b + "s*:[^;]*(?:$|;s*)", "i"), "").replace(/^[;\s]+/, "").replace(/\s+$/, "")
							);
						c && (
							a && !/;\s*$/.test(a) && (
								a += "; "
								), a += b + ": " + c
							);
						this.setValue(a, 1)
					}, setup: e, commit: f}), b.classes && d.push({type: "hbox", widths: ["45%",
					"55%"], children: [
					{id: "advCSSClasses", att: "class", type: "text", label: a.cssClasses, "default": "", setup: e, commit: f}
				]}), g.elements[0].children.push({type: "hbox", widths: ["50%", "50%"], children: [].concat(d)});
			}
			return g
		}})
	}
	)();