﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(
	function () {
		CKEDITOR.plugins.add("ajax", {requires: "xml"});
		CKEDITOR.ajax = function () {
			var h = function () {
				if (!CKEDITOR.env.ie || "file:" != location.protocol) {
					try {
						return new XMLHttpRequest
					} catch (a) {
					}
				}
				try {
					return new ActiveXObject("Msxml2.XMLHTTP")
				} catch (b) {
				}
				try {
					return new ActiveXObject("Microsoft.XMLHTTP")
				} catch (d) {
				}
				return null
			}, f = function (a) {
				return 4 == a.readyState && (
					200 <= a.status && 300 > a.status || 304 == a.status || 0 === a.status || 1223 == a.status
					)
			}, i = function (a) {
				return f(a) ? a.responseText : null
			}, j = function (a) {
				if (f(a)) {
					var b =
						a.responseXML;
					return new CKEDITOR.xml(b && b.firstChild ? b : a.responseText)
				}
				return null
			}, g = function (a, b, d) {
				var e = !!b, c = h();
				if (!c) {
					return null;
				}
				c.open("GET", a, e);
				e && (
					c.onreadystatechange = function () {
						4 == c.readyState && (
							b(d(c)), c = null
							)
					}
					);
				c.send(null);
				return e ? "" : d(c)
			};
			return{load: function (a, b) {
				return g(a, b, i)
			}, loadXml: function (a, b) {
				return g(a, b, j)
			}}
		}()
	}
	)();