﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		CKEDITOR.plugins.add("xml", {});
		CKEDITOR.xml = function (c) {
			var a = null;
			if ("object" == typeof c) {
				a = c;
			}
			else if (c = (
				c || ""
				).replace(/&nbsp;/g, " "), window.DOMParser) {
				a = (
					new DOMParser
					).parseFromString(c, "text/xml");
			}
			else if (window.ActiveXObject) {
				try {
					a = new ActiveXObject("MSXML2.DOMDocument")
				} catch (b) {
					try {
						a = new ActiveXObject("Microsoft.XmlDom")
					} catch (d) {
					}
				}
				a && (
					a.async = !1, a.resolveExternals = !1, a.validateOnParse = !1, a.loadXML(c)
					)
			}
			this.baseXml = a
		};
		CKEDITOR.xml.prototype = {selectSingleNode: function (c, a) {
			var b = this.baseXml;
			if (a || (
				a = b
				)) {
				if (CKEDITOR.env.ie || a.selectSingleNode) {
					return a.selectSingleNode(c);
				}
				if (b.evaluate) {
					return(
						b = b.evaluate(c, a, null, 9, null)
						) && b.singleNodeValue || null
				}
			}
			return null
		}, selectNodes: function (c, a) {
			var b = this.baseXml, d = [];
			if (a || (
				a = b
				)) {
				if (CKEDITOR.env.ie || a.selectNodes) {
					return a.selectNodes(c);
				}
				if (b.evaluate && (
					b = b.evaluate(c, a, null, 5, null)
					)) {
					for (var e; e = b.iterateNext();) {
						d.push(e)
					}
				}
			}
			return d
		}, getInnerXml: function (c, a) {
			var b = this.selectSingleNode(c, a), d = [];
			if (b) {
				for (b = b.firstChild; b;) {
					b.xml ? d.push(b.xml) : window.XMLSerializer &&
						d.push((
							new XMLSerializer
							).serializeToString(b)), b = b.nextSibling;
				}
			}
			return d.length ? d.join("") : null
		}}
	}
	)();