﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		var b = /\[\[[^\]]+\]\]/g;
		CKEDITOR.plugins.add("placeholder", {requires: "dialog", lang: "en,bg,cs,cy,da,de,el,eo,et,fa,fi,fr,he,hr,it,nb,nl,no,pl,tr,ug,uk,vi,zh-cn", icons: "createplaceholder,editplaceholder", onLoad: function () {
			CKEDITOR.addCss(".cke_placeholder{background-color: #ffff00;" + (
				CKEDITOR.env.gecko ? "cursor: default;" : ""
				) + "}")
		}, init: function (a) {
			var b = a.lang.placeholder;
			a.addCommand("createplaceholder", new CKEDITOR.dialogCommand("createplaceholder"));
			a.addCommand("editplaceholder", new CKEDITOR.dialogCommand("editplaceholder"));
			a.ui.addButton && a.ui.addButton("CreatePlaceholder", {label: b.toolbar, command: "createplaceholder", toolbar: "insert,5"});
			a.addMenuItems && (
				a.addMenuGroup("placeholder", 20), a.addMenuItems({editplaceholder: {label: b.edit, command: "editplaceholder", group: "placeholder", order: 1}}), a.contextMenu && a.contextMenu.addListener(function (a) {
					return!a || !a.data("cke-placeholder") ? null : {editplaceholder: CKEDITOR.TRISTATE_OFF}
				})
				);
			a.on("doubleclick", function (b) {
				if (CKEDITOR.plugins.placeholder.getSelectedPlaceHoder(a)) {
					b.data.dialog =
						"editplaceholder"
				}
			});
			a.on("contentDom", function () {
				a.editable().on("resizestart", function (b) {
					a.getSelection().getSelectedElement().data("cke-placeholder") && b.data.preventDefault()
				})
			});
			CKEDITOR.dialog.add("createplaceholder", this.path + "dialogs/placeholder.js");
			CKEDITOR.dialog.add("editplaceholder", this.path + "dialogs/placeholder.js")
		}, afterInit: function (a) {
			var c = a.dataProcessor, e = c && c.dataFilter, c = c && c.htmlFilter;
			e && e.addRules({text: function (d) {
				return d.replace(b, function (b) {
					return CKEDITOR.plugins.placeholder.createPlaceholder(a,
						null, b, 1)
				})
			}});
			c && c.addRules({elements: {span: function (a) {
				a.attributes && a.attributes["data-cke-placeholder"] && delete a.name
			}}})
		}})
	}
	)();
CKEDITOR.plugins.placeholder = {createPlaceholder: function (b, a, c, e) {
	var d = new CKEDITOR.dom.element("span", b.document);
	d.setAttributes({contentEditable: "false", "data-cke-placeholder": 1, "class": "cke_placeholder"});
	c && d.setText(c);
	if (e) {
		return d.getOuterHtml();
	}
	a ? CKEDITOR.env.ie ? (
		d.insertAfter(a), setTimeout(function () {
			a.remove();
			d.focus()
		}, 10)
		) : d.replace(a) : b.insertElement(d);
	return null
}, getSelectedPlaceHoder: function (b) {
	b = b.getSelection().getRanges()[0];
	b.shrink(CKEDITOR.SHRINK_TEXT);
	for (b = b.startContainer; b && !(
		b.type == CKEDITOR.NODE_ELEMENT && b.data("cke-placeholder")
		);) {
		b = b.getParent();
	}
	return b
}};