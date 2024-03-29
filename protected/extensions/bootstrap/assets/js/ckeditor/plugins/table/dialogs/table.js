﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		function r(a) {
			for (var f = 0, l = 0, k = 0, d, e = a.$.rows.length; k < e; k++) {
				d = a.$.rows[k];
				for (var c = f = 0, b, g = d.cells.length; c < g; c++) {
					b = d.cells[c], f += b.colSpan;
				}
				f > l && (
					l = f
					)
			}
			return l
		}

		function n(a) {
			return function () {
				var f = this.getValue(), f = !!(
					CKEDITOR.dialog.validate.integer()(f) && 0 < f
					);
				f || (
					alert(a), this.select()
					);
				return f
			}
		}

		function o(a, f) {
			var l = function (d) {
				return new CKEDITOR.dom.element(d, a.document)
			}, k = a.plugins.dialogadvtab;
			return{title: a.lang.table.title, minWidth: 310, minHeight: CKEDITOR.env.ie ? 310 : 280,
				onLoad: function () {
					var d = this, a = d.getContentElement("advanced", "advStyles");
					if (a) {
						a.on("change", function () {
							var a = this.getStyle("width", ""), b = d.getContentElement("info", "txtWidth");
							b && b.setValue(a, !0);
							a = this.getStyle("height", "");
							(
								b = d.getContentElement("info", "txtHeight")
								) && b.setValue(a, !0)
						})
					}
				}, onShow: function () {
					var d = a.getSelection(), e = d.getRanges(), c, b = this.getContentElement("info", "txtRows"), g = this.getContentElement("info", "txtCols"), p = this.getContentElement("info", "txtWidth"), h = this.getContentElement("info",
						"txtHeight");
					"tableProperties" == f && (
						(
							d = d.getSelectedElement()
							) && d.is("table") ? c = d : 0 < e.length && (
							CKEDITOR.env.webkit && e[0].shrink(CKEDITOR.NODE_ELEMENT), c = a.elementPath(e[0].getCommonAncestor(!0)).contains("table", 1)
							), this._.selectedElement = c
						);
					c ? (
						this.setupContent(c), b && b.disable(), g && g.disable()
						) : (
						b && b.enable(), g && g.enable()
						);
					p && p.onChange();
					h && h.onChange()
				}, onOk: function () {
					var d = a.getSelection(), e = this._.selectedElement && d.createBookmarks(), c = this._.selectedElement || l("table"), b = {};
					this.commitContent(b,
						c);
					if (b.info) {
						b = b.info;
						if (!this._.selectedElement) {
							for (var g = c.append(l("tbody")), f = parseInt(b.txtRows, 10) || 0, h = parseInt(b.txtCols, 10) || 0, i = 0; i < f; i++) {
								for (var j = g.append(l("tr")), k = 0; k < h; k++) {
									var m = j.append(l("td"));
									CKEDITOR.env.ie || m.append(l("br"))
								}
							}
						}
						f = b.selHeaders;
						if (!c.$.tHead && (
							"row" == f || "both" == f
							)) {
							j = new CKEDITOR.dom.element(c.$.createTHead());
							g = c.getElementsByTag("tbody").getItem(0);
							g = g.getElementsByTag("tr").getItem(0);
							for (i = 0; i < g.getChildCount(); i++) {
								h = g.getChild(i), h.type == CKEDITOR.NODE_ELEMENT && !h.data("cke-bookmark") && (
									h.renameNode("th"), h.setAttribute("scope", "col")
									);
							}
							j.append(g.remove())
						}
						if (null !== c.$.tHead && !(
							"row" == f || "both" == f
							)) {
							j = new CKEDITOR.dom.element(c.$.tHead);
							g = c.getElementsByTag("tbody").getItem(0);
							for (k = g.getFirst(); 0 < j.getChildCount();) {
								g = j.getFirst();
								for (i = 0; i < g.getChildCount(); i++) {
									h = g.getChild(i), h.type == CKEDITOR.NODE_ELEMENT && (
										h.renameNode("td"), h.removeAttribute("scope")
										);
								}
								g.insertBefore(k)
							}
							j.remove()
						}
						if (!this.hasColumnHeaders && (
							"col" == f || "both" == f
							)) {
							for (j = 0; j < c.$.rows.length; j++) {
								h =
									new CKEDITOR.dom.element(c.$.rows[j].cells[0]), h.renameNode("th"), h.setAttribute("scope", "row");
							}
						}
						if (this.hasColumnHeaders && !(
							"col" == f || "both" == f
							)) {
							for (i = 0; i < c.$.rows.length; i++) {
								j = new CKEDITOR.dom.element(c.$.rows[i]), "tbody" == j.getParent().getName() && (
									h = new CKEDITOR.dom.element(j.$.cells[0]), h.renameNode("td"), h.removeAttribute("scope")
									);
							}
						}
						b.txtHeight ? c.setStyle("height", b.txtHeight) : c.removeStyle("height");
						b.txtWidth ? c.setStyle("width", b.txtWidth) : c.removeStyle("width");
						c.getAttribute("style") || c.removeAttribute("style")
					}
					if (this._.selectedElement) {
						try {
							d.selectBookmarks(e)
						} catch (n) {
						}
					}
					else {
						a.insertElement(c),
							setTimeout(function () {
								var d = new CKEDITOR.dom.element(c.$.rows[0].cells[0]), b = a.createRange();
								b.moveToPosition(d, CKEDITOR.POSITION_AFTER_START);
								b.select()
							}, 0)
					}
				}, contents: [
					{id: "info", label: a.lang.table.title, elements: [
						{type: "hbox", widths: [null, null], styles: ["vertical-align:top"], children: [
							{type: "vbox", padding: 0, children: [
								{type: "text", id: "txtRows", "default": 3, label: a.lang.table.rows, required: !0, controlStyle: "width:5em", validate: n(a.lang.table.invalidRows), setup: function (d) {
									this.setValue(d.$.rows.length)
								},
									commit: m},
								{type: "text", id: "txtCols", "default": 2, label: a.lang.table.columns, required: !0, controlStyle: "width:5em", validate: n(a.lang.table.invalidCols), setup: function (d) {
									this.setValue(r(d))
								}, commit: m},
								{type: "html", html: "&nbsp;"},
								{type: "select", id: "selHeaders", "default": "", label: a.lang.table.headers, items: [
									[a.lang.table.headersNone, ""],
									[a.lang.table.headersRow, "row"],
									[a.lang.table.headersColumn, "col"],
									[a.lang.table.headersBoth, "both"]
								], setup: function (d) {
									var a = this.getDialog();
									a.hasColumnHeaders = !0;
									for (var c =
										0; c < d.$.rows.length; c++) {
										var b = d.$.rows[c].cells[0];
										if (b && "th" != b.nodeName.toLowerCase()) {
											a.hasColumnHeaders = !1;
											break
										}
									}
									null !== d.$.tHead ? this.setValue(a.hasColumnHeaders ? "both" : "row") : this.setValue(a.hasColumnHeaders ? "col" : "")
								}, commit: m},
								{type: "text", id: "txtBorder", "default": 1, label: a.lang.table.border, controlStyle: "width:3em", validate: CKEDITOR.dialog.validate.number(a.lang.table.invalidBorder), setup: function (d) {
									this.setValue(d.getAttribute("border") || "")
								}, commit: function (d, a) {
									this.getValue() ? a.setAttribute("border",
										this.getValue()) : a.removeAttribute("border")
								}},
								{id: "cmbAlign", type: "select", "default": "", label: a.lang.common.align, items: [
									[a.lang.common.notSet, ""],
									[a.lang.common.alignLeft, "left"],
									[a.lang.common.alignCenter, "center"],
									[a.lang.common.alignRight, "right"]
								], setup: function (a) {
									this.setValue(a.getAttribute("align") || "")
								}, commit: function (a, e) {
									this.getValue() ? e.setAttribute("align", this.getValue()) : e.removeAttribute("align")
								}}
							]},
							{type: "vbox", padding: 0, children: [
								{type: "hbox", widths: ["5em"], children: [
									{type: "text",
										id: "txtWidth", controlStyle: "width:5em", label: a.lang.common.width, title: a.lang.common.cssLengthTooltip, "default": 500, getValue: q, validate: CKEDITOR.dialog.validate.cssLength(a.lang.common.invalidCssLength.replace("%1", a.lang.common.width)), onChange: function () {
										var a = this.getDialog().getContentElement("advanced", "advStyles");
										a && a.updateStyle("width", this.getValue())
									}, setup: function (a) {
										(
											a = a.getStyle("width")
											) && this.setValue(a)
									}, commit: m}
								]},
								{type: "hbox", widths: ["5em"], children: [
									{type: "text", id: "txtHeight",
										controlStyle: "width:5em", label: a.lang.common.height, title: a.lang.common.cssLengthTooltip, "default": "", getValue: q, validate: CKEDITOR.dialog.validate.cssLength(a.lang.common.invalidCssLength.replace("%1", a.lang.common.height)), onChange: function () {
										var a = this.getDialog().getContentElement("advanced", "advStyles");
										a && a.updateStyle("height", this.getValue())
									}, setup: function (a) {
										(
											a = a.getStyle("height")
											) && this.setValue(a)
									}, commit: m}
								]},
								{type: "html", html: "&nbsp;"},
								{type: "text", id: "txtCellSpace", controlStyle: "width:3em",
									label: a.lang.table.cellSpace, "default": 1, validate: CKEDITOR.dialog.validate.number(a.lang.table.invalidCellSpacing), setup: function (a) {
									this.setValue(a.getAttribute("cellSpacing") || "")
								}, commit: function (a, e) {
									this.getValue() ? e.setAttribute("cellSpacing", this.getValue()) : e.removeAttribute("cellSpacing")
								}},
								{type: "text", id: "txtCellPad", controlStyle: "width:3em", label: a.lang.table.cellPad, "default": 1, validate: CKEDITOR.dialog.validate.number(a.lang.table.invalidCellPadding), setup: function (a) {
									this.setValue(a.getAttribute("cellPadding") ||
										"")
								}, commit: function (a, e) {
									this.getValue() ? e.setAttribute("cellPadding", this.getValue()) : e.removeAttribute("cellPadding")
								}}
							]}
						]},
						{type: "html", align: "right", html: ""},
						{type: "vbox", padding: 0, children: [
							{type: "text", id: "txtCaption", label: a.lang.table.caption, setup: function (a) {
								this.enable();
								a = a.getElementsByTag("caption");
								if (0 < a.count()) {
									var a = a.getItem(0), e = a.getFirst(CKEDITOR.dom.walker.nodeType(CKEDITOR.NODE_ELEMENT));
									e && !e.equals(a.getBogus()) ? (
										this.disable(), this.setValue(a.getText())
										) : (
										a = CKEDITOR.tools.trim(a.getText()),
											this.setValue(a)
										)
								}
							}, commit: function (d, e) {
								if (this.isEnabled()) {
									var c = this.getValue(), b = e.getElementsByTag("caption");
									if (c) {
										0 < b.count() ? (
											b = b.getItem(0), b.setHtml("")
											) : (
											b = new CKEDITOR.dom.element("caption", a.document), e.getChildCount() ? b.insertBefore(e.getFirst()) : b.appendTo(e)
											), b.append(new CKEDITOR.dom.text(c, a.document));
									}
									else if (0 < b.count()) {
										for (c = b.count() - 1; 0 <= c; c--) {
											b.getItem(c).remove()
										}
									}
								}
							}},
							{type: "text", id: "txtSummary", label: a.lang.table.summary, setup: function (a) {
								this.setValue(a.getAttribute("summary") ||
									"")
							}, commit: function (a, e) {
								this.getValue() ? e.setAttribute("summary", this.getValue()) : e.removeAttribute("summary")
							}}
						]}
					]},
					k && k.createAdvancedTab(a)
				]}
		}

		var q = CKEDITOR.tools.cssLength, m = function (a) {
			var f = this.id;
			a.info || (
				a.info = {}
				);
			a.info[f] = this.getValue()
		};
		CKEDITOR.dialog.add("table", function (a) {
			return o(a, "table")
		});
		CKEDITOR.dialog.add("tableProperties", function (a) {
			return o(a, "tableProperties")
		})
	}
	)();