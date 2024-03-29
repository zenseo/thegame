/*
 Highcharts JS v2.2.5 (2012-06-08)
 Prototype adapter

 @author Michael Nelson, Torstein H?nsi.

 Feel free to use and modify this script.
 Highcharts license: www.highcharts.com/license.
 */
var HighchartsAdapter = function () {
	var g = typeof Effect !== "undefined";
	return{init: function (c) {
		if (g) {
			Effect.HighchartsTransition = Class.create(Effect.Base, {initialize: function (a, b, d, e) {
				var f;
				this.element = a;
				this.key = b;
				f = a.attr ? a.attr(b) : $(a).getStyle(b);
				if (b === "d") {
					this.paths = c.init(a, a.d, d), this.toD = d, f = 0, d = 1;
				}
				this.start(Object.extend(e || {}, {from: f, to: d, attribute: b}))
			}, setup: function () {
				HighchartsAdapter._extend(this.element);
				if (!this.element._highchart_animation) {
					this.element._highchart_animation = {};
				}
				this.element._highchart_animation[this.key] =
					this
			}, update: function (a) {
				var b = this.paths, d = this.element;
				b && (
					a = c.step(b[0], b[1], a, this.toD)
					);
				d.attr ? d.attr(this.options.attribute, a) : (
					b = {}, b[this.options.attribute] = a, $(d).setStyle(b)
					)
			}, finish: function () {
				delete this.element._highchart_animation[this.key]
			}})
		}
	}, adapterRun: function (c, a) {
		return parseInt($(c).getStyle(a), 10)
	}, getScript: function (c, a) {
		var b = $$("head")[0];
		b && b.appendChild((
			new Element("script", {type: "text/javascript", src: c})
			).observe("load", a))
	}, addNS: function (c) {
		var a = /^(?:click|mouse(?:down|up|over|move|out))$/;
		return/^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/.test(c) || a.test(c) ? c : "h:" + c
	}, addEvent: function (c, a, b) {
		c.addEventListener || c.attachEvent ? Event.observe($(c), HighchartsAdapter.addNS(a), b) : (
			HighchartsAdapter._extend(c), c._highcharts_observe(a, b)
			)
	}, animate: function (c, a, b) {
		var d, b = b || {};
		b.delay = 0;
		b.duration = (
			b.duration || 500
			) / 1E3;
		b.afterFinish = b.complete;
		if (g) {
			for (d in a) {
				new Effect.HighchartsTransition($(c), d, a[d], b);
			}
		}
		else {
			if (c.attr) {
				for (d in a) {
					c.attr(d, a[d]);
				}
			}
			b.complete &&
			b.complete()
		}
		c.attr || $(c).setStyle(a)
	}, stop: function (c) {
		var a;
		if (c._highcharts_extended && c._highchart_animation) {
			for (a in c._highchart_animation) {
				c._highchart_animation[a].cancel()
			}
		}
	}, each: function (c, a) {
		$A(c).each(a)
	}, offset: function (c) {
		return $(c).cumulativeOffset()
	}, fireEvent: function (c, a, b, d) {
		c.fire ? c.fire(HighchartsAdapter.addNS(a), b) : c._highcharts_extended && (
			b = b || {}, c._highcharts_fire(a, b)
			);
		b && b.defaultPrevented && (
			d = null
			);
		d && d(b)
	}, removeEvent: function (c, a, b) {
		$(c).stopObserving && (
			a && (
				a = HighchartsAdapter.addNS(a)
				),
				$(c).stopObserving(a, b)
			);
		window === c ? Event.stopObserving(c, a, b) : (
			HighchartsAdapter._extend(c), c._highcharts_stop_observing(a, b)
			)
	}, washMouseEvent: function (c) {
		return c
	}, grep: function (c, a) {
		return c.findAll(a)
	}, map: function (c, a) {
		return c.map(a)
	}, merge: function () {
		function c(a, b) {
			var d, e;
			for (e in b) {
				d = b[e], a[e] = d && typeof d === "object" && d.constructor !== Array && typeof d.nodeType !== "number" ? c(a[e] || {}, d) : b[e];
			}
			return a
		}

		return function () {
			var a = arguments, b, d = {};
			for (b = 0; b < a.length; b++) {
				d = c(d, a[b]);
			}
			return d
		}.apply(this,
				arguments)
	}, _extend: function (c) {
		c._highcharts_extended || Object.extend(c, {_highchart_events: {}, _highchart_animation: null, _highcharts_extended: !0, _highcharts_observe: function (a, b) {
			this._highchart_events[a] = [this._highchart_events[a], b].compact().flatten()
		}, _highcharts_stop_observing: function (a, b) {
			a ? b ? this._highchart_events[a] = [this._highchart_events[a]].compact().flatten().without(b) : delete this._highchart_events[a] : this._highchart_events = {}
		}, _highcharts_fire: function (a, b) {
			(
				this._highchart_events[a] ||
					[]
				).each(function (a) {
					if (!b.stopped) {
						b.preventDefault = function () {
							b.defaultPrevented = !0
						}, a.bind(this)(b) === !1 && b.preventDefault()
					}
				}.bind(this))
		}})
	}}
}();
