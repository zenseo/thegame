﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function d(a) {
			for (var a = a.getElementsByTag("*"), c = a.count(), b, e = 0; e < c; e++) {
				b = a.getItem(e), function (a) {
					for (var b = 0; b < j.length; b++) {
						(
							function (b) {
								var c = a.getAttribute("on" + b);
								a.hasAttribute("on" + b) && (
									a.removeAttribute("on" + b), a.on(b, function (b) {
										var e = /(return\s*)?CKEDITOR\.tools\.callFunction\(([^)]+)\)/.exec(c), k = e && e[1], f = e && e[2].split(","), e = /return false;/.test(c);
										if (f) {
											for (var h = f.length, i, g = 0; g < h; g++) {
												f[g] = i = CKEDITOR.tools.trim(f[g]);
												var d = i.match(/^(["'])([^"']*?)\1$/);
												if (d) {
													f[g] = d[2];
												}
												else if (i.match(/\d+/)) {
													f[g] = parseInt(i, 10);
												}
												else {
													switch (i) {
														case "this":
															f[g] = a.$;
															break;
														case "event":
															f[g] = b.data.$;
															break;
														case "null":
															f[g] = null
													}
												}
											}
											f = CKEDITOR.tools.callFunction.apply(window, f);
											k && !1 === f && (
												e = 1
												)
										}
										e && b.data.preventDefault()
									})
									)
							}
							)(j[b])
					}
				}(b)
			}
		}

		var j = "click keydown mousedown keypress mouseover mouseout".split(" ");
		CKEDITOR.plugins.add("adobeair", {onLoad: function () {
			CKEDITOR.env.air && (
				CKEDITOR.dom.document.prototype.write = CKEDITOR.tools.override(CKEDITOR.dom.document.prototype.write, function (a) {
					function c(b, a, c, h) {
						a = b.append(a);
						(
							c = CKEDITOR.htmlParser.fragment.fromHtml(c).children[0].attributes
							) && a.setAttributes(c);
						h && a.append(b.getDocument().createText(h))
					}

					return function (b, e) {
						if (this.getBody()) {
							var d = this, h = this.getHead(), b = b.replace(/(<style[^>]*>)([\s\S]*?)<\/style>/gi, function (a, b, d) {
								c(h, "style", b, d);
								return""
							}), b = b.replace(/<base\b[^>]*\/>/i, function (b) {
								c(h, "base", b);
								return""
							}), b = b.replace(/<title>([\s\S]*)<\/title>/i, function (b, a) {
								d.$.title = a;
								return""
							}), b = b.replace(/<head>([\s\S]*)<\/head>/i, function (b) {
								var a =
									new CKEDITOR.dom.element("div", d);
								a.setHtml(b);
								a.moveChildren(h);
								return""
							});
							b.replace(/(<body[^>]*>)([\s\S]*)(?=$|<\/body>)/i, function (b, a, c) {
								d.getBody().setHtml(c);
								(
									b = CKEDITOR.htmlParser.fragment.fromHtml(a).children[0].attributes
									) && d.getBody().setAttributes(b)
							})
						}
						else {
							a.apply(this, arguments)
						}
					}
				}), CKEDITOR.addCss("body.cke_editable { padding: 8px }"), CKEDITOR.ui.on("ready", function (a) {
					a = a.data;
					if (a._.panel) {
						var c = a._.panel._.panel, b;
						(
							function () {
								c.isLoaded ? (
									b = c._.holder, d(b)
									) : setTimeout(arguments.callee,
									30)
							}
							)()
					}
					else {
						a instanceof CKEDITOR.dialog && d(a._.element)
					}
				})
				)
		}, init: function (a) {
			CKEDITOR.env.air && (
				a.on("uiReady", function () {
					d(a.container);
					a.on("elementsPathUpdate", function (a) {
						d(a.data.space)
					})
				}), a.on("contentDom", function () {
					a.document.on("click", function (a) {
						a.data.preventDefault(!0)
					})
				})
				)
		}})
	}
	)();