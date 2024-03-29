﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.dialog.add("a11yHelp", function (f) {
	function n(j, i) {
		var a = f.keystrokeHandler.keystrokes, b;
		for (b in a) {
			if (a[b] == i) {
				break;
			}
		}
		for (var a = b, c, g = [], d = 0; d < h.length; d++) {
			c = h[d], b = a / h[d], 1 < b && 2 >= b && (
				a -= c, g.push(e[c])
				);
		}
		g.push(e[a] || String.fromCharCode(a));
		return g.join("+")
	}

	var j = f.lang.a11yhelp, m = CKEDITOR.tools.getNextId(), e = {8: "BACKSPACE", 9: "TAB", 13: "ENTER", 16: "SHIFT", 17: "CTRL", 18: "ALT", 19: "PAUSE", 20: "CAPSLOCK", 27: "ESCAPE", 33: "PAGE UP", 34: "PAGE DOWN", 35: "END", 36: "HOME", 37: "LEFT ARROW", 38: "UP ARROW", 39: "RIGHT ARROW",
		40: "DOWN ARROW", 45: "INSERT", 46: "DELETE", 91: "LEFT WINDOW KEY", 92: "RIGHT WINDOW KEY", 93: "SELECT KEY", 96: "NUMPAD  0", 97: "NUMPAD  1", 98: "NUMPAD  2", 99: "NUMPAD  3", 100: "NUMPAD  4", 101: "NUMPAD  5", 102: "NUMPAD  6", 103: "NUMPAD  7", 104: "NUMPAD  8", 105: "NUMPAD  9", 106: "MULTIPLY", 107: "ADD", 109: "SUBTRACT", 110: "DECIMAL POINT", 111: "DIVIDE", 112: "F1", 113: "F2", 114: "F3", 115: "F4", 116: "F5", 117: "F6", 118: "F7", 119: "F8", 120: "F9", 121: "F10", 122: "F11", 123: "F12", 144: "NUM LOCK", 145: "SCROLL LOCK", 186: "SEMI-COLON", 187: "EQUAL SIGN",
		188: "COMMA", 189: "DASH", 190: "PERIOD", 191: "FORWARD SLASH", 192: "GRAVE ACCENT", 219: "OPEN BRACKET", 220: "BACK SLASH", 221: "CLOSE BRAKET", 222: "SINGLE QUOTE"};
	e[CKEDITOR.ALT] = "ALT";
	e[CKEDITOR.SHIFT] = "SHIFT";
	e[CKEDITOR.CTRL] = "CTRL";
	var h = [CKEDITOR.ALT, CKEDITOR.SHIFT, CKEDITOR.CTRL], o = /\$\{(.*?)\}/g;
	return{title: j.title, minWidth: 600, minHeight: 400, contents: [
		{id: "info", label: f.lang.common.generalTab, expand: !0, elements: [
			{type: "html", id: "legends", style: "white-space:normal;", focus: function () {
			}, html: function () {
				for (var e =
					'<div class="cke_accessibility_legend" role="document" aria-labelledby="' + m + '_arialbl" tabIndex="-1">%1</div><span id="' + m + '_arialbl" class="cke_voice_label">' + j.contents + " </span>", i = [], a = j.legend, b = a.length, c = 0; c < b; c++) {
					for (var g = a[c], d = [], f = g.items, h = f.length, l = 0; l < h; l++) {
						var k = f[l], k = "<dt>%1</dt><dd>%2</dd>".replace("%1", k.name).replace("%2", k.legend.replace(o, n));
						d.push(k)
					}
					i.push("<h1>%1</h1><dl>%2</dl>".replace("%1", g.name).replace("%2", d.join("")))
				}
				return e.replace("%1", i.join(""))
			}() + '<style type="text/css">.cke_accessibility_legend{width:600px;height:400px;padding-right:5px;overflow-y:auto;overflow-x:hidden;}.cke_browser_quirks .cke_accessibility_legend,.cke_browser_ie6 .cke_accessibility_legend{height:390px}.cke_accessibility_legend *{white-space:normal;}.cke_accessibility_legend h1{font-size: 20px;border-bottom: 1px solid #AAA;margin: 5px 0px 15px;}.cke_accessibility_legend dl{margin-left: 5px;}.cke_accessibility_legend dt{font-size: 13px;font-weight: bold;}.cke_accessibility_legend dd{margin:10px}</style>'}
		]}
	],
		buttons: [CKEDITOR.dialog.cancelButton]}
});