﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function j(a) {
			var c = a.editable();
			if (!c._.shadow) {
				var a = a.getSelection().getRanges()[0].clone(), e = a.createBookmark2(), b = c.clone(1, 1);
				b.setStyles({"z-index": -1, position: "absolute", left: c.$.offsetLeft + "px", top: c.$.offsetTop + "px", width: c.getSize("width", 1) + "px", height: c.getSize("height", 1) + "px"});
				b.is("body") || b.setStyle("margin", 0);
				b.addClass("cke-selection-shadow");
				b.insertBefore(c);
				b.$.scrollTop = c.$.scrollTop;
				a.moveToBookmark(e);
				e = new CKEDITOR.style(CKEDITOR.tools.extend({attributes: {"data-cke-highlight": 1},
					fullMatch: 1, ignoreReadonly: 1, childRule: function () {
						return 0
					}}, {element: "var", styles: {"font-style": "normal", "background-color": "#B0B0B0", color: "#fff"}}, !0));
				a.boundary = b;
				e.applyToRange(a);
				b.insertAfter(c);
				c.setOpacity(0);
				c._.shadow = b
			}
		}

		function h(a) {
			delete g[a.name];
			CKEDITOR.tools.isEmpty(g) && (
				clearTimeout(i), i = 0
				);
			a = a.editable();
			a._.shadow && (
				a.setOpacity(1), a._.shadow.remove(), delete a._.shadow
				)
		}

		function n(a) {
			g[a.name] = "";
			i || function () {
				var a = arguments.callee;
				i = setTimeout(function () {
					for (var e in g) {
						var b =
							CKEDITOR.instances[e], d = g[e], f;
						f = b.editable();
						for (var k = "", l = 0, m = ["width", "height"]; l < m.length; l++) {
							k += "|" + f.getSize(m[l]);
						}
						f = {html: f.getHtml(), size: k};
						d && (
							f.html != d.html ? (
								h(b), j(b)
								) : f.size != d.size && (
								b = b.editable(), d = b._.shadow, d.setStyles({"z-index": -1, position: "absolute", left: b.$.offsetLeft + "px", top: b.$.offsetTop + "px", width: b.getSize("width", 1) + "px", height: b.getSize("height", 1) + "px"}), d.$.scrollTop = b.$.scrollTop
								)
							);
						g[e] = f
					}
					a()
				}, 200)
			}()
		}

		if (CKEDITOR.env.ie && 8 > CKEDITOR.env.version) {
			CKEDITOR.plugins.add("selectionmark",
				function () {
				});
		}
		else {
			var g = {}, i;
			CKEDITOR.plugins.add("selectionmark", {init: function (a) {
				a.on("instanceReady", function () {
					var c = a.editable();
					if (!(
						"absolute" == c.getComputedStyle("position") || /^inline/.exec(c.getComputedStyle("display"))
						)) {
						for (var e = ["top", "bottom"], b = 0, d; d = e[b], 2 > b; b++) {
							var f = parseInt(c.getComputedStyle("border-" + d + "-width"), 10), g = parseInt(c.getComputedStyle("padding-" + d), 10);
							!f && !g && (
								d = "border-" + d, c.setStyle(d, "1px solid transparent")
								)
						}
					}
					c.on("selectionchange", function () {
						c._.shadow && (
							h(a),
								j(a)
							)
					});
					c.on("blur", function () {
						setTimeout(function () {
							if (a.focusManager.hasFocus) {
								var b = a.getSelection();
								if (b && b.isLocked) {
									var b = a.getSelection(), c;
									if (c = b) {
										b = b.getRanges(), c = !(
											1 == b.length && b[0].collapsed
											);
									}
									c && (
										j(a), n(a)
										)
								}
							}
						}, 200)
					});
					c.on("focus", function () {
						h(a)
					})
				});
				a.on("blur", function () {
					h(a)
				});
				a.on("beforeDestroy", function () {
					h(a)
				})
			}})
		}
	}
	)();