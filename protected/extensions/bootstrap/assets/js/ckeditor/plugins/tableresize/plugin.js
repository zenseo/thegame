﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function t(a) {
			return CKEDITOR.env.ie ? a.$.clientWidth : parseInt(a.getComputedStyle("width"), 10)
		}

		function n(a, h) {
			var b = a.getComputedStyle("border-" + h + "-width"), c = {thin: "0px", medium: "1px", thick: "2px"};
			0 > b.indexOf("px") && (
				b = b in c && "none" != a.getComputedStyle("border-style") ? c[b] : 0
				);
			return parseInt(b, 10)
		}

		function w(a) {
			var h = [], b = -1, c = "rtl" == a.getComputedStyle("direction"), f;
			f = a.$.rows;
			for (var p = 0, e, g, d, j = 0, o = f.length; j < o; j++) {
				d = f[j], e = d.cells.length, e > p && (
					p = e, g = d
					);
			}
			f = g;
			p = new CKEDITOR.dom.element(a.$.tBodies[0]);
			e = p.getDocumentPosition();
			g = 0;
			for (d = f.cells.length; g < d; g++) {
				var j = new CKEDITOR.dom.element(f.cells[g]), o = f.cells[g + 1] && new CKEDITOR.dom.element(f.cells[g + 1]), b = b + (
					j.$.colSpan || 1
					), k, i, l = j.getDocumentPosition().x;
				c ? i = l + n(j, "left") : k = l + j.$.offsetWidth - n(j, "right");
				o ? (
					l = o.getDocumentPosition().x, c ? k = l + o.$.offsetWidth - n(o, "right") : i = l + n(o, "left")
					) : (
					l = a.getDocumentPosition().x, c ? k = l : i = l + a.$.offsetWidth
					);
				j = Math.max(i - k, 3);
				h.push({table: a, index: b, x: k, y: e.y, width: j, height: p.$.offsetHeight, rtl: c})
			}
			return h
		}

		function u(a) {
			(
				a.data || a
				).preventDefault()
		}

		function A(a) {
			function h() {
				j = 0;
				d.setOpacity(0);
				k && b();
				var v = e.table;
				setTimeout(function () {
					v.removeCustomData("_cke_table_pillars")
				}, 0);
				g.removeListener("dragstart", u)
			}

			function b() {
				for (var v = e.rtl, b = v ? l.length : x.length, a = 0; a < b; a++) {
					var d = x[a], c = l[a], f = e.table;
					CKEDITOR.tools.setTimeout(function (a, b, d, c, e, g) {
						a && a.setStyle("width", i(Math.max(b + g, 0)));
						d && d.setStyle("width", i(Math.max(c - g, 0)));
						e && f.setStyle("width", i(e + g * (
							v ? -1 : 1
							)))
					}, 0, this, [d, d && t(d), c, c && t(c),
						(
							!d || !c
							) && t(f) + n(f, "left") + n(f, "right"), k])
				}
			}

			function c(a) {
				u(a);
				for (var a = e.index, b = CKEDITOR.tools.buildTableMap(e.table), c = [], h = [], i = Number.MAX_VALUE, n = i, s = e.rtl, r = 0, w = b.length; r < w; r++) {
					var m = b[r], q = m[a + (
						s ? 1 : 0
						)], m = m[a + (
						s ? 0 : 1
						)], q = q && new CKEDITOR.dom.element(q), m = m && new CKEDITOR.dom.element(m);
					if (!q || !m || !q.equals(m)) {
						q && (
							i = Math.min(i, t(q))
							), m && (
							n = Math.min(n, t(m))
							), c.push(q), h.push(m)
					}
				}
				x = c;
				l = h;
				y = e.x - i;
				z = e.x + n;
				d.setOpacity(0.5);
				o = parseInt(d.getStyle("left"), 10);
				k = 0;
				j = 1;
				d.on("mousemove", p);
				g.on("dragstart",
					u);
				g.on("mouseup", f, this)
			}

			function f(a) {
				a.removeListener();
				h()
			}

			function p(a) {
				r(a.data.$.clientX)
			}

			var e, g, d, j, o, k, x, l, y, z;
			g = a.document;
			d = CKEDITOR.dom.element.createFromHtml('<div data-cke-temp=1 contenteditable=false unselectable=on style="position:absolute;cursor:col-resize;filter:alpha(opacity=0);opacity:0;padding:0;background-color:#004;background-image:none;border:0px none;z-index:10"></div>', g);
			s || g.getDocumentElement().append(d);
			this.attachTo = function (a) {
				j || (
					s && (
						g.getBody().append(d), k = 0
						), e =
						a, d.setStyles({width: i(a.width), height: i(a.height), left: i(a.x), top: i(a.y)}), s && d.setOpacity(0.25), d.on("mousedown", c, this), g.getBody().setStyle("cursor", "col-resize"), d.show()
					)
			};
			var r = this.move = function (a) {
				if (!e) {
					return 0;
				}
				if (!j && (
					a < e.x || a > e.x + e.width
					)) {
					return e = null, j = k = 0, g.removeListener("mouseup", f), d.removeListener("mousedown", c), d.removeListener("mousemove", p), g.getBody().setStyle("cursor", "auto"), s ? d.remove() : d.hide(), 0;
				}
				a -= Math.round(d.$.offsetWidth / 2);
				if (j) {
					if (a == y || a == z) {
						return 1;
					}
					a = Math.max(a,
						y);
					a = Math.min(a, z);
					k = a - o
				}
				d.setStyle("left", i(a));
				return 1
			}
		}

		function r(a) {
			var h = a.data.getTarget();
			if ("mouseout" == a.name) {
				if (!h.is("table")) {
					return;
				}
				for (var b = new CKEDITOR.dom.element(a.data.$.relatedTarget || a.data.$.toElement); b && b.$ && !b.equals(h) && !b.is("body");) {
					b = b.getParent();
				}
				if (!b || b.equals(h)) {
					return
				}
			}
			h.getAscendant("table", 1).removeCustomData("_cke_table_pillars");
			a.removeListener()
		}

		var i = CKEDITOR.tools.cssLength, s = CKEDITOR.env.ie && (
			CKEDITOR.env.ie7Compat || CKEDITOR.env.quirks || 7 > CKEDITOR.env.version
			);
		CKEDITOR.plugins.add("tableresize", {init: function (a) {
			a.on("contentDom", function () {
				var h;
				a.document.getBody().on("mousemove", function (b) {
					b = b.data;
					if (h && h.move(b.$.clientX)) {
						u(b);
					}
					else {
						var c = b.getTarget(), f;
						if (c.is("table") || c.getAscendant("tbody", 1)) {
							f = c.getAscendant("table", 1);
							if (!(
								c = f.getCustomData("_cke_table_pillars")
								)) {
								f.setCustomData("_cke_table_pillars", c = w(f)), f.on("mouseout", r), f.on("mousedown", r);
							}
							a:{
								b = b.$.clientX;
								f = 0;
								for (var i = c.length; f < i; f++) {
									var e = c[f];
									if (b >= e.x && b <= e.x + e.width) {
										c = e;
										break a
									}
								}
								c =
									null
							}
							c && (
								!h && (
									h = new A(a)
									), h.attachTo(c)
								)
						}
					}
				})
			})
		}})
	}
	)();