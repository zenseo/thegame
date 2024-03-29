﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function y(a) {
			for (var a = a.toUpperCase(), b = z.length, d = 0, c = 0; c < b; ++c) {
				for (var e = z[c], f = e[1].length; a.substr(0, f) == e[1]; a = a.substr(f)) {
					d += e[0];
				}
			}
			return d
		}

		function A(a) {
			for (var a = a.toUpperCase(), b = B.length, d = 1, c = 1; 0 < a.length; c *= b) {
				d += B.indexOf(a.charAt(a.length - 1)) * c, a = a.substr(0, a.length - 1);
			}
			return d
		}

		var C = CKEDITOR.htmlParser.fragment.prototype, w = CKEDITOR.htmlParser.element.prototype;
		C.onlyChild = w.onlyChild = function () {
			var a = this.children;
			return 1 == a.length && a[0] || null
		};
		w.removeAnyChildWithName =
			function (a) {
				for (var b = this.children, d = [], c, e = 0; e < b.length; e++) {
					c = b[e], c.name && (
						c.name == a && (
							d.push(c), b.splice(e--, 1)
							), d = d.concat(c.removeAnyChildWithName(a))
						);
				}
				return d
			};
		w.getAncestor = function (a) {
			for (var b = this.parent; b && (
				!b.name || !b.name.match(a)
				);) {
				b = b.parent;
			}
			return b
		};
		C.firstChild = w.firstChild = function (a) {
			for (var b, d = 0; d < this.children.length; d++) {
				if (b = this.children[d], a(b) || b.name && (
					b = b.firstChild(a)
					)) {
					return b;
				}
			}
			return null
		};
		w.addStyle = function (a, b, d) {
			var c = "";
			if ("string" == typeof b) {
				c += a + ":" + b + ";";
			}
			else {
				if ("object" == typeof a) {
					for (var e in a) {
						a.hasOwnProperty(e) && (
							c += e + ":" + a[e] + ";"
							);
					}
				}
				else {
					c += a;
				}
				d = b
			}
			this.attributes || (
				this.attributes = {}
				);
			a = this.attributes.style || "";
			a = (
				d ? [c, a] : [a, c]
				).join(";");
			this.attributes.style = a.replace(/^;|;(?=;)/, "")
		};
		CKEDITOR.dtd.parentOf = function (a) {
			var b = {}, d;
			for (d in this) {
				-1 == d.indexOf("$") && this[d][a] && (
					b[d] = 1
					);
			}
			return b
		};
		var G = /^([.\d]*)+(em|ex|px|gd|rem|vw|vh|vm|ch|mm|cm|in|pt|pc|deg|rad|ms|s|hz|khz){1}?/i, D = /^(?:\b0[^\s]*\s*){1,4}$/, x = {ol: {decimal: /\d+/, "lower-roman": /^m{0,4}(cm|cd|d?c{0,3})(xc|xl|l?x{0,3})(ix|iv|v?i{0,3})$/,
			"upper-roman": /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/, "lower-alpha": /^[a-z]+$/, "upper-alpha": /^[A-Z]+$/}, ul: {disc: /[l\u00B7\u2002]/, circle: /[\u006F\u00D8]/, square: /[\u006E\u25C6]/}}, z = [
			[1E3, "M"],
			[900, "CM"],
			[500, "D"],
			[400, "CD"],
			[100, "C"],
			[90, "XC"],
			[50, "L"],
			[40, "XL"],
			[10, "X"],
			[9, "IX"],
			[5, "V"],
			[4, "IV"],
			[1, "I"]
		], B = "ABCDEFGHIJKLMNOPQRSTUVWXYZ", u = 0, p = null, v, E = CKEDITOR.plugins.pastefromword = {utils: {createListBulletMarker: function (a, b) {
			var d = new CKEDITOR.htmlParser.element("cke:listbullet");
			d.attributes = {"cke:listsymbol": a[0]};
			d.add(new CKEDITOR.htmlParser.text(b));
			return d
		}, isListBulletIndicator: function (a) {
			if (/mso-list\s*:\s*Ignore/i.test(a.attributes && a.attributes.style)) {
				return!0
			}
		}, isContainingOnlySpaces: function (a) {
			var b;
			return(
				b = a.onlyChild()
				) && /^(:?\s|&nbsp;)+$/.test(b.value)
		}, resolveList: function (a) {
			var b = a.attributes, d;
			if ((
				d = a.removeAnyChildWithName("cke:listbullet")
				) && d.length && (
				d = d[0]
				)) {
				return a.name = "cke:li", b.style && (
					b.style = E.filters.stylesFilter([
						["text-indent"],
						["line-height"],
						[/^margin(:?-left)?$/, null, function (a) {
							a = a.split(" ");
							a = CKEDITOR.tools.convertToPx(a[3] || a[1] || a[0]);
							!u && (
								null !== p && a > p
								) && (
								u = a - p
								);
							p = a;
							b["cke:indent"] = u && Math.ceil(a / u) + 1 || 1
						}],
						[/^mso-list$/, null, function (a) {
							var a = a.split(" "), d = Number(a[0].match(/\d+/)), a = Number(a[1].match(/\d+/));
							1 == a && (
								d !== v && (
									b["cke:reset"] = 1
									), v = d
								);
							b["cke:indent"] = a
						}]
					])(b.style, a) || ""
					), b["cke:indent"] || (
					p = 0, b["cke:indent"] = 1
					), CKEDITOR.tools.extend(b, d.attributes), !0;
			}
			v = p = u = null;
			return!1
		}, getStyleComponents: function () {
			var a = CKEDITOR.dom.element.createFromHtml('<div style="position:absolute;left:-9999px;top:-9999px;"></div>',
				CKEDITOR.document);
			CKEDITOR.document.getBody().append(a);
			return function (b, d, c) {
				a.setStyle(b, d);
				for (var b = {}, d = c.length, e = 0; e < d; e++) {
					b[c[e]] = a.getStyle(c[e]);
				}
				return b
			}
		}(), listDtdParents: CKEDITOR.dtd.parentOf("ol")}, filters: {flattenList: function (a, b) {
			var b = "number" == typeof b ? b : 1, d = a.attributes, c;
			switch (d.type) {
				case "a":
					c = "lower-alpha";
					break;
				case "1":
					c = "decimal"
			}
			for (var e = a.children, f, h = 0; h < e.length; h++) {
				if (f = e[h], f.name in CKEDITOR.dtd.$listItem) {
					var g = f.attributes, i = f.children, n = i[i.length - 1];
					n.name in
						CKEDITOR.dtd.$list && (
						a.add(n, h + 1), --i.length || e.splice(h--, 1)
						);
					f.name = "cke:li";
					d.start && !h && (
						g.value = d.start
						);
					E.filters.stylesFilter([
						["tab-stops", null, function (a) {
							(
								a = a.split(" ")[1].match(G)
								) && (
								p = CKEDITOR.tools.convertToPx(a[0])
								)
						}],
						1 == b ? ["mso-list", null, function (a) {
							a = a.split(" ");
							a = Number(a[0].match(/\d+/));
							a !== v && (
								g["cke:reset"] = 1
								);
							v = a
						}] : null
					])(g.style);
					g["cke:indent"] = b;
					g["cke:listtype"] = a.name;
					g["cke:list-style-type"] = c
				}
				else if (f.name in CKEDITOR.dtd.$list) {
					arguments.callee.apply(this, [f, b + 1]);
					e = e.slice(0, h).concat(f.children).concat(e.slice(h + 1));
					a.children = [];
					f = 0;
					for (i = e.length; f < i; f++) {
						a.add(e[f])
					}
				}
			}
			delete a.name;
			d["cke:list"] = 1
		}, assembleList: function (a) {
			for (var b = a.children, d, c, e, f, h, g, a = [], i, n, j, m, k, r, q = 0; q < b.length; q++) {
				if (d = b[q], "cke:li" == d.name) {
					if (d.name = "li", c = d.attributes, j = (
						j = c["cke:listsymbol"]
						) && j.match(/^(?:[(]?)([^\s]+?)([.)]?)$/), m = k = r = null, c["cke:ignored"]) {
						b.splice(q--, 1);
					}
					else {
						c["cke:reset"] && (
							g = f = h = null
							);
						e = Number(c["cke:indent"]);
						e != f && (
							n = i = null
							);
						if (j) {
							if (n && x[n][i].test(j[1])) {
								m =
									n, k = i;
							}
							else {
								for (var s in x) {
									for (var t in x[s]) {
										if (x[s][t].test(j[1])) {
											if ("ol" == s && /alpha|roman/.test(t)) {
												if (i = /roman/.test(t) ? y(j[1]) : A(j[1]), !r || i < r) {
													r = i, m = s, k = t
												}
											}
											else {
												m = s;
												k = t;
												break
											}
										}
									}
								}
							}
							!m && (
								m = j[2] ? "ol" : "ul"
								)
						}
						else {
							m = c["cke:listtype"] || "ol", k = c["cke:list-style-type"];
						}
						n = m;
						i = k || (
							"ol" == m ? "decimal" : "disc"
							);
						k && k != (
							"ol" == m ? "decimal" : "disc"
							) && d.addStyle("list-style-type", k);
						if ("ol" == m && j) {
							switch (k) {
								case "decimal":
									r = Number(j[1]);
									break;
								case "lower-roman":
								case "upper-roman":
									r = y(j[1]);
									break;
								case "lower-alpha":
								case "upper-alpha":
									r =
										A(j[1])
							}
							d.attributes.value = r
						}
						if (g) {
							if (e > f) {
								a.push(g = new CKEDITOR.htmlParser.element(m)), g.add(d), h.add(g);
							}
							else {
								if (e < f) {
									f -= e;
									for (var o; f-- && (
										o = g.parent
										);) {
										g = o.parent
									}
								}
								g.add(d)
							}
							b.splice(q--, 1)
						}
						else {
							a.push(g = new CKEDITOR.htmlParser.element(m)), g.add(d), b[q] = g;
						}
						h = d;
						f = e
					}
				}
				else {
					g && (
						g = f = h = null
						);
				}
			}
			for (q = 0; q < a.length; q++)if (g = a[q], s = g.children, i = i = void 0, t = g.children.length, o = i = void 0, b = /list-style-type:(.*?)(?:;|$)/, f = CKEDITOR.plugins.pastefromword.filters.stylesFilter, i = g.attributes, !b.exec(i.style)) {
				for (h = 0; h <
					t; h++)if (i = s[h], i.attributes.value && Number(i.attributes.value) == h + 1 && delete i.attributes.value, i = b.exec(i.attributes.style))if (i[1] == o || !o)o = i[1];
				else {
					o = null;
					break
				}
				if (o) {
					for (h = 0; h < t; h++)i = s[h].attributes, i.style && (
						i.style = f([
							["list-style-type"]
						])(i.style) || ""
						);
					g.addStyle("list-style-type", o)
				}
			}
			v = p = u = null
		}, falsyFilter: function () {
			return!1
		}, stylesFilter: function (a, b) {
			return function (d, c) {
				var e = [];
				(
					d || ""
					).replace(/&quot;/g, '"').replace(/\s*([^ :;]+)\s*:\s*([^;]+)\s*(?=;|$)/g, function (d, g, f) {
						g = g.toLowerCase();
						"font-family" == g && (
							f = f.replace(/["']/g, "")
							);
						for (var n, j, m, k = 0; k < a.length; k++)if (a[k] && (
							d = a[k][0], n = a[k][1], j = a[k][2], m = a[k][3], g.match(d) && (
								!n || f.match(n)
								)
							)) {
							g = m || g;
							b && (
								j = j || f
								);
							"function" == typeof j && (
								j = j(f, c, g)
								);
							j && j.push && (
								g = j[0], j = j[1]
								);
							"string" == typeof j && e.push([g, j]);
							return
						}
						!b && e.push([g, f])
					});
				for (var f = 0; f < e.length; f++)e[f] = e[f].join(":");
				return e.length ? e.join(";") + ";" : !1
			}
		}, elementMigrateFilter: function (a, b) {
			return function (d) {
				var c = b ? (
					new CKEDITOR.style(a, b)
					)._.definition : a;
				d.name = c.element;
				CKEDITOR.tools.extend(d.attributes, CKEDITOR.tools.clone(c.attributes));
				d.addStyle(CKEDITOR.style.getStyleText(c))
			}
		}, styleMigrateFilter: function (a, b) {
			var d = this.elementMigrateFilter;
			return function (c, e) {
				var f = new CKEDITOR.htmlParser.element(null), h = {};
				h[b] = c;
				d(a, h)(f);
				f.children = e.children;
				e.children = [f]
			}
		}, bogusAttrFilter: function (a, b) {
			if (-1 == b.name.indexOf("cke:"))return!1
		}, applyStyleFilter: null}, getRules: function (a) {
			var b = CKEDITOR.dtd, d = CKEDITOR.tools.extend({}, b.$block, b.$listItem, b.$tableContent),
				c = a.config, e = this.filters, a = e.falsyFilter, f = e.stylesFilter, h = e.elementMigrateFilter, g = CKEDITOR.tools.bind(this.filters.styleMigrateFilter, this.filters), i = this.utils.createListBulletMarker, n = e.flattenList, j = e.assembleList, m = this.utils.isListBulletIndicator, k = this.utils.isContainingOnlySpaces, r = this.utils.resolveList, q = function (a) {
					a = CKEDITOR.tools.convertToPx(a);
					return isNaN(a) ? a : a + "px"
				}, s = this.utils.getStyleComponents, t = this.utils.listDtdParents, o = !1 !== c.pasteFromWordRemoveFontStyles, p = !1 !== c.pasteFromWordRemoveStyles;
			return{elementNames: [
				[/meta|link|script/, ""]
			], root: function (a) {
				a.filterChildren();
				j(a)
			}, elements: {"^": function (a) {
				var l;
				CKEDITOR.env.gecko && (
					l = e.applyStyleFilter
					) && l(a)
			}, $: function (a) {
				var l = a.name || "", e = a.attributes;
				l in d && e.style && (
					e.style = f([
						[/^(:?width|height)$/, null, q]
					])(e.style) || ""
					);
				if (l.match(/h\d/)) {
					a.filterChildren();
					if (r(a))return;
					h(c["format_" + l])(a)
				}
				else if (l in b.$inline)a.filterChildren(), k(a) && delete a.name;
				else if (-1 != l.indexOf(":") && -1 == l.indexOf("cke")) {
					a.filterChildren();
					if ("v:imagedata" ==
						l) {
						if (l = a.attributes["o:href"])a.attributes.src = l;
						a.name = "img";
						return
					}
					delete a.name
				}
				l in t && (
					a.filterChildren(), j(a)
					)
			}, style: function (a) {
				if (CKEDITOR.env.gecko) {
					var a = (
						a = a.onlyChild().value.match(/\/\* Style Definitions \*\/([\s\S]*?)\/\*/)
						) && a[1], l = {};
					a && (
						a.replace(/[\n\r]/g, "").replace(/(.+?)\{(.+?)\}/g, function (a, b, d) {
							for (var b = b.split(","), a = b.length, c = 0; c < a; c++)CKEDITOR.tools.trim(b[c]).replace(/^(\w+)(\.[\w-]+)?$/g, function (a, b, c) {
								b = b || "*";
								c = c.substring(1, c.length);
								c.match(/MsoNormal/) || (
									l[b] ||
										(
											l[b] = {}
											), c ? l[b][c] = d : l[b] = d
									)
							})
						}), e.applyStyleFilter = function (a) {
							var b = l["*"] ? "*" : a.name, c = a.attributes && a.attributes["class"];
							b in l && (
								b = l[b], "object" == typeof b && (
									b = b[c]
									), b && a.addStyle(b, !0)
								)
						}
						)
				}
				return!1
			}, p: function (a) {
				if (/MsoListParagraph/.exec(a.attributes["class"])) {
					var b = a.firstChild(function (a) {
						return a.type == CKEDITOR.NODE_TEXT && !k(a.parent)
					});
					(
						b = (
							b = b && b.parent
							) && b.attributes
						) && !b.style && (
						b.style = "mso-list: Ignore;"
						)
				}
				a.filterChildren();
				r(a) || (
					c.enterMode == CKEDITOR.ENTER_BR ? (
						delete a.name, a.add(new CKEDITOR.htmlParser.element("br"))
						) :
						h(c["format_" + (
							c.enterMode == CKEDITOR.ENTER_P ? "p" : "div"
							)])(a)
					)
			}, div: function (a) {
				var b = a.onlyChild();
				if (b && "table" == b.name) {
					var c = a.attributes;
					b.attributes = CKEDITOR.tools.extend(b.attributes, c);
					c.style && b.addStyle(c.style);
					b = new CKEDITOR.htmlParser.element("div");
					b.addStyle("clear", "both");
					a.add(b);
					delete a.name
				}
			}, td: function (a) {
				a.getAncestor("thead") && (
					a.name = "th"
					)
			}, ol: n, ul: n, dl: n, font: function (a) {
				if (m(a.parent))delete a.name;
				else {
					a.filterChildren();
					var b = a.attributes, c = b.style, d = a.parent;
					"font" ==
						d.name ? (
						CKEDITOR.tools.extend(d.attributes, a.attributes), c && d.addStyle(c), delete a.name
						) : (
						c = c || "", b.color && (
							"#000000" != b.color && (
								c += "color:" + b.color + ";"
								), delete b.color
							), b.face && (
							c += "font-family:" + b.face + ";", delete b.face
							), b.size && (
							c += "font-size:" + (
								3 < b.size ? "large" : 3 > b.size ? "small" : "medium"
								) + ";", delete b.size
							), a.name = "span", a.addStyle(c)
						)
				}
			}, span: function (a) {
				if (m(a.parent))return!1;
				a.filterChildren();
				if (k(a))return delete a.name, null;
				if (m(a)) {
					var b = a.firstChild(function (a) {
							return a.value || "img" == a.name
						}),
						d = (
							b = b && (
								b.value || "l."
								)
							) && b.match(/^(?:[(]?)([^\s]+?)([.)]?)$/);
					if (d)return b = i(d, b), (
						a = a.getAncestor("span")
						) && / mso-hide:\s*all|display:\s*none /.test(a.attributes.style) && (
						b.attributes["cke:ignored"] = 1
						), b
				}
				if (d = (
					b = a.attributes
					) && b.style)b.style = f([
					["line-height"],
					[/^font-family$/, null, !o ? g(c.font_style, "family") : null],
					[/^font-size$/, null, !o ? g(c.fontSize_style, "size") : null],
					[/^color$/, null, !o ? g(c.colorButton_foreStyle, "color") : null],
					[/^background-color$/, null, !o ? g(c.colorButton_backStyle, "color") :
						null]
				])(d, a) || "";
				return null
			}, b: h(c.coreStyles_bold), i: h(c.coreStyles_italic), u: h(c.coreStyles_underline), s: h(c.coreStyles_strike), sup: h(c.coreStyles_superscript), sub: h(c.coreStyles_subscript), a: function (a) {
				var b = a.attributes;
				b && !b.href && b.name ? delete a.name : CKEDITOR.env.webkit && (
					b.href && b.href.match(/file:\/\/\/[\S]+#/i)
					) && (
					b.href = b.href.replace(/file:\/\/\/[^#]+/i, "")
					)
			}, "cke:listbullet": function (a) {
				a.getAncestor(/h\d/) && !c.pasteFromWordNumberedHeadingToList && delete a.name
			}}, attributeNames: [
				[/^onmouse(:?out|over)/,
					""],
				[/^onload$/, ""],
				[/(?:v|o):\w+/, ""],
				[/^lang/, ""]
			], attributes: {style: f(p ? [
				[/^list-style-type$/, null],
				[/^margin$|^margin-(?!bottom|top)/, null, function (a, b, d) {
					if (b.name in{p: 1, div: 1}) {
						b = "ltr" == c.contentsLangDirection ? "margin-left" : "margin-right";
						if ("margin" == d)a = s(d, a, [b])[b];
						else if (d != b)return null;
						if (a && !D.test(a))return[b, a]
					}
					return null
				}],
				[/^clear$/],
				[/^border.*|margin.*|vertical-align|float$/, null, function (a, b) {
					if ("img" == b.name)return a
				}],
				[/^width|height$/, null, function (a, b) {
					if (b.name in{table: 1,
						td: 1, th: 1, img: 1})return a
				}]
			] : [
				[/^mso-/],
				[/-color$/, null, function (a) {
					if ("transparent" == a)return!1;
					if (CKEDITOR.env.gecko)return a.replace(/-moz-use-text-color/g, "transparent")
				}],
				[/^margin$/, D],
				["text-indent", "0cm"],
				["page-break-before"],
				["tab-stops"],
				["display", "none"],
				o ? [/font-?/] : null
			], p), width: function (a, c) {
				if (c.name in b.$tableContent)return!1
			}, border: function (a, c) {
				if (c.name in b.$tableContent)return!1
			}, "class": a, bgcolor: a, valign: p ? a : function (a, b) {
				b.addStyle("vertical-align", a);
				return!1
			}}, comment: !CKEDITOR.env.ie ?
				function (a, b) {
					var c = a.match(/<img.*?>/), d = a.match(/^\[if !supportLists\]([\s\S]*?)\[endif\]$/);
					return d ? (
						d = (
							c = d[1] || c && "l."
							) && c.match(/>(?:[(]?)([^\s]+?)([.)]?)</), i(d, c)
						) : CKEDITOR.env.gecko && c ? (
						c = CKEDITOR.htmlParser.fragment.fromHtml(c[0]).children[0], (
							d = (
								d = (
									d = b.previous
									) && d.value.match(/<v:imagedata[^>]*o:href=['"](.*?)['"]/)
								) && d[1]
							) && (
							c.attributes.src = d
							), c
						) : !1
				} : a}
		}}, F = function () {
			this.dataFilter = new CKEDITOR.htmlParser.filter
		};
		F.prototype = {toHtml: function (a) {
			var a = CKEDITOR.htmlParser.fragment.fromHtml(a),
				b = new CKEDITOR.htmlParser.basicWriter;
			a.writeHtml(b, this.dataFilter);
			return b.getHtml(!0)
		}};
		CKEDITOR.cleanWord = function (a, b) {
			CKEDITOR.env.gecko && (
				a = a.replace(/(<\!--\[if[^<]*?\])--\>([\S\s]*?)<\!--(\[endif\]--\>)/gi, "$1$2$3")
				);
			var d = new F, c = d.dataFilter;
			c.addRules(CKEDITOR.plugins.pastefromword.getRules(b));
			b.fire("beforeCleanWord", {filter: c});
			try {
				a = d.toHtml(a)
			} catch (e) {
				alert(b.lang.pastefromword.error)
			}
			a = a.replace(/cke:.*?".*?"/g, "");
			a = a.replace(/style=""/g, "");
			return a = a.replace(/<span>/g, "")
		}
	}
	)();