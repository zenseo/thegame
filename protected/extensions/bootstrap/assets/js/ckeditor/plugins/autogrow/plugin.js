﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		var f = function (a) {
			if (a.window) {
				var c = a.document;
				new CKEDITOR.dom.element(c.getWindow().$.frameElement);
				var b = c.getBody(), d = c.getDocumentElement(), g = a.window.getViewPaneSize().height, c = "BackCompat" == c.$.compatMode ? b : d, b = c.getStyle("overflow-y"), e = c.getDocument(), d = CKEDITOR.dom.element.createFromHtml('<span style="margin:0;padding:0;border:0;clear:both;width:1px;height:1px;display:block;">' + (
					CKEDITOR.env.webkit ? "&nbsp;" : ""
					) + "</span>", e);
				e[CKEDITOR.env.ie ? "getBody" : "getDocumentElement"]().append(d);
				e = d.getDocumentPosition(e).y + d.$.offsetHeight;
				d.remove();
				c.setStyle("overflow-y", b);
				b = e + (
					a.config.autoGrow_bottomSpace || 0
					);
				d = a.config.autoGrow_maxHeight || Infinity;
				b = Math.max(b, void 0 != a.config.autoGrow_minHeight ? a.config.autoGrow_minHeight : 200);
				b = Math.min(b, d);
				b != g && (
					b = a.fire("autoGrow", {currentHeight: g, newHeight: b}).newHeight, a.resize(a.container.getStyle("width"), b, !0)
					);
				c.$.scrollHeight > c.$.clientHeight && b < d ? c.setStyle("overflow-y", "hidden") : c.removeStyle("overflow-y")
			}
		};
		CKEDITOR.plugins.add("autogrow",
			{init: function (a) {
				if (a.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
					a.on("instanceReady", function () {
						if (a.editable().isInline()) {
							a.ui.space("contents").setStyle("height", "auto");
						}
						else {
							a.addCommand("autogrow", {exec: f, modes: {wysiwyg: 1}, readOnly: 1, canUndo: !1, editorFocus: !1});
							var c = {contentDom: 1, key: 1, selectionChange: 1, insertElement: 1, mode: 1}, b;
							for (b in c) {
								a.on(b, function (b) {
									var c = a.getCommand("maximize");
									"wysiwyg" == b.editor.mode && (
										!c || c.state != CKEDITOR.TRISTATE_ON
										) && setTimeout(function () {
											f(b.editor);
											f(b.editor)
										},
										100)
								});
							}
							a.config.autoGrow_onStartup && a.execCommand("autogrow")
						}
					})
				}
			}})
	}
	)();