﻿(
	function () {
		function p(a, k, o) {
			if (!k.is || !k.getCustomData("block_processed")) {
				k.is && CKEDITOR.dom.element.setMarker(o, k, "block_processed", !0), a.push(k)
			}
		}

		function n(a, k) {
			function o() {
				this.foreach(function (d) {
					if (/^(?!vbox|hbox)/.test(d.type) && (
						d.setup || (
							d.setup = function (b) {
								d.setValue(b.getAttribute(d.id) || "")
							}
							), !d.commit
						)) {
						d.commit = function (b) {
							var a = this.getValue();
							"dir" == d.id && b.getComputedStyle("direction") == a || (
								a ? b.setAttribute(d.id, a) : b.removeAttribute(d.id)
								)
						}
					}
				})
			}

			var n = function () {
				var d = CKEDITOR.tools.extend({},
					CKEDITOR.dtd.$blockLimit);
				a.config.div_wrapTable && (
					delete d.td, delete d.th
					);
				return d
			}(), q = CKEDITOR.dtd.div, l = {}, m = [];
			return{title: a.lang.div.title, minWidth: 400, minHeight: 165, contents: [
				{id: "info", label: a.lang.common.generalTab, title: a.lang.common.generalTab, elements: [
					{type: "hbox", widths: ["50%", "50%"], children: [
						{id: "elementStyle", type: "select", style: "width: 100%;", label: a.lang.div.styleSelectLabel, "default": "", items: [
							[a.lang.common.notSet, ""]
						], onChange: function () {
							var d = ["info:class", "advanced:dir",
								"advanced:style"], b = this.getDialog(), h = b._element && b._element.clone() || new CKEDITOR.dom.element("div", a.document);
							this.commit(h, !0);
							for (var d = [].concat(d), c = d.length, i, e = 0; e < c; e++) {
								(
									i = b.getContentElement.apply(b, d[e].split(":"))
									) && i.setup && i.setup(h, !0)
							}
						}, setup: function (a) {
							for (var b in l) {
								l[b].checkElementRemovable(a, !0) && this.setValue(b)
							}
						}, commit: function (a) {
							var b;
							if (b = this.getValue()) {
								b = l[b];
								var h = a.getCustomData("elementStyle") || "";
								b.applyToObject(a);
								a.setCustomData("elementStyle", h + b._.definition.attributes.style)
							}
						}},
						{id: "class", type: "text", label: a.lang.common.cssClass, "default": ""}
					]}
				]},
				{id: "advanced", label: a.lang.common.advancedTab, title: a.lang.common.advancedTab, elements: [
					{type: "vbox", padding: 1, children: [
						{type: "hbox", widths: ["50%", "50%"], children: [
							{type: "text", id: "id", label: a.lang.common.id, "default": ""},
							{type: "text", id: "lang", label: a.lang.common.langCode, "default": ""}
						]},
						{type: "hbox", children: [
							{type: "text", id: "style", style: "width: 100%;", label: a.lang.common.cssStyle, "default": "", commit: function (a) {
								var b = this.getValue() +
									(
										a.getCustomData("elementStyle") || ""
										);
								a.setAttribute("style", b)
							}}
						]},
						{type: "hbox", children: [
							{type: "text", id: "title", style: "width: 100%;", label: a.lang.common.advisoryTitle, "default": ""}
						]},
						{type: "select", id: "dir", style: "width: 100%;", label: a.lang.common.langDir, "default": "", items: [
							[a.lang.common.notSet, ""],
							[a.lang.common.langDirLtr, "ltr"],
							[a.lang.common.langDirRtl, "rtl"]
						]}
					]}
				]}
			], onLoad: function () {
				o.call(this);
				var d = this, b = this.getContentElement("info", "elementStyle");
				a.getStylesSet(function (a) {
					var c;
					if (a) {
						for (var i = 0; i < a.length; i++) {
							var e = a[i];
							e.element && "div" == e.element && (
								c = e.name, l[c] = new CKEDITOR.style(e), b.items.push([c, c]), b.add(c, c)
								)
						}
					}
					b[1 < b.items.length ? "enable" : "disable"]();
					setTimeout(function () {
						b.setup(d._element)
					}, 0)
				})
			}, onShow: function () {
				"editdiv" == k && this.setupContent(this._element = CKEDITOR.plugins.div.getSurroundDiv(a))
			}, onOk: function () {
				if ("editdiv" == k) {
					m = [this._element];
				}
				else {
					var d = [], b = {}, h = [], c, i = a.getSelection(), e = i.getRanges(), l = i.createBookmarks(), g, j;
					for (g = 0; g < e.length; g++) {
						for (j =
							e[g].createIterator(); c = j.getNextParagraph();) {
							if (c.getName()in n) {
								var f = c.getChildren();
								for (c = 0; c < f.count(); c++) {
									p(h, f.getItem(c), b)
								}
							}
							else {
								for (; !q[c.getName()] && !c.equals(e[g].root);) {
									c = c.getParent();
								}
								p(h, c, b)
							}
						}
					}
					CKEDITOR.dom.element.clearAllMarkers(b);
					e = [];
					g = null;
					for (j = 0; j < h.length; j++) {
						c = h[j], f = a.elementPath(c).blockLimit, a.config.div_wrapTable && f.is(["td", "th"]) && (
							f = a.elementPath(f.getParent()).blockLimit
							), f.equals(g) || (
							g = f, e.push([])
							), e[e.length - 1].push(c);
					}
					for (g = 0; g < e.length; g++) {
						f = e[g][0];
						h = f.getParent();
						for (c = 1; c < e[g].length; c++) {
							h = h.getCommonAncestor(e[g][c]);
						}
						j = new CKEDITOR.dom.element("div", a.document);
						for (c = 0; c < e[g].length; c++) {
							for (f = e[g][c]; !f.getParent().equals(h);) {
								f = f.getParent();
							}
							e[g][c] = f
						}
						for (c = 0; c < e[g].length; c++) {
							if (f = e[g][c], !f.getCustomData || !f.getCustomData("block_processed")) {
								f.is && CKEDITOR.dom.element.setMarker(b, f, "block_processed", !0), c || j.insertBefore(f), j.append(f);
							}
						}
						CKEDITOR.dom.element.clearAllMarkers(b);
						d.push(j)
					}
					i.selectBookmarks(l);
					m = d
				}
				d = m.length;
				for (b = 0; b < d; b++) {
					this.commitContent(m[b]),
						!m[b].getAttribute("style") && m[b].removeAttribute("style");
				}
				this.hide()
			}, onHide: function () {
				"editdiv" == k && this._element.removeCustomData("elementStyle");
				delete this._element
			}}
		}

		CKEDITOR.dialog.add("creatediv", function (a) {
			return n(a, "creatediv")
		});
		CKEDITOR.dialog.add("editdiv", function (a) {
			return n(a, "editdiv")
		})
	}
	)();