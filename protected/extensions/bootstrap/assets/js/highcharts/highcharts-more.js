/*
 Highcharts JS v2.2.5 (2012-06-08)

 (c) 2009-2011 Torstein H?nsi

 License: www.highcharts.com/license
 */
(
	function (g, v) {
		var o = g.each, s = g.extend, p = g.merge, w = g.map, j = g.pick, q = g.pInt, i = g.getOptions().plotOptions, k = g.seriesTypes, t = g.Series, m = function () {
		}, r = g.Axis.prototype, l = g.Tick.prototype, x = {redraw: function () {
			this.isDirty = !1
		}, render: function () {
			this.isDirty = !1
		}, setScale: m, setCategories: m, setTitle: m}, u = {isRadial: !0, defaultRadialGaugeOptions: {center: ["50%", "50%"], labels: {align: "center", x: 0, y: null}, minorGridLineWidth: 0, minorTickInterval: "auto", minorTickLength: 10, minorTickPosition: "inside", minorTickWidth: 1,
			plotBands: [], size: ["90%"], tickLength: 10, tickPosition: "inside", tickWidth: 2, title: {rotation: 0}, zIndex: 2}, defaultRadialXOptions: {center: ["50%", "50%"], labels: {align: "center", distance: 15, x: 0, y: null}, maxPadding: 0, minPadding: 0, size: ["90%"]}, defaultRadialYOptions: {center: ["50%", "50%"], labels: {align: "left", x: 3, y: -2}, size: ["90%"], title: {x: -4, text: null}}, defaultBackgroundOptions: {shape: "circle", borderWidth: 1, borderColor: "silver", backgroundColor: {linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1}, stops: [
			[0, "#FFF"],
			[1, "#DDD"]
		]},
			from: Number.MIN_VALUE, innerRadius: 0, to: Number.MAX_VALUE, outerRadius: "105%"}, setOptions: function (a) {
			var b = this, c;
			b.options = c = p(b.defaultOptions, b.isXAxis ? {} : b.defaultYAxisOptions, b.defaultRadialOptions, a);
			o([].concat(g.splat(a.background || {})).reverse(), function (a) {
				a = p(b.defaultBackgroundOptions, a);
				a.color = a.backgroundColor;
				c.plotBands.unshift(a)
			})
		}, getOffset: function () {
			r.getOffset.call(this);
			this.chart.axisOffset[this.side] = 0;
			this.center = k.pie.prototype.getCenter.call(this)
		}, getLinePath: function (a) {
			var b =
				this.center, a = j(a, b[2] / 2 - this.offset);
			return this.chart.renderer.symbols.arc(this.left + b[0], this.top + b[1], a, a, {start: this.startAngleRad, end: this.endAngleRad, open: !0, innerR: 0})
		}, setAxisTranslation: function () {
			r.setAxisTranslation.call(this);
			if (this.center) {
				this.transA = this.isCircular ? (
					this.endAngleRad - this.startAngleRad
					) / (
					this.max - this.min + (
						this.closestPointRange || 0
						) || 1
					) : this.center[2] / 2 / (
					this.max - this.min || 1
					), this.minPixelPadding = this.transA * (
					(
						this.pointRange || 0
						) / 2
					)
			}
		}, setAxisSize: function () {
			r.setAxisSize.call(this);
			if (this.center) {
				this.len = this.width = this.height = this.isCircular ? this.center[2] * (
					this.endAngleRad - this.startAngleRad
					) / 2 : this.center[2] / 2
			}
		}, getPosition: function (a, b) {
			if (!this.isCircular) {
				b = this.translate(a), a = this.min;
			}
			return this.postTranslate(this.translate(a), j(b, this.center[2] / 2) - this.offset)
		}, postTranslate: function (a, b) {
			var c = this.chart, d = this.center, a = this.startAngleRad + a;
			return{x: c.plotLeft + d[0] + Math.cos(a) * b, y: c.plotTop + d[1] + Math.sin(a) * b}
		}, getPlotBandPath: function (a, b, c) {
			var d = this.center, e = this.startAngleRad,
				h = d[2] / 2, f = [j(c.outerRadius, "100%"), c.innerRadius, j(c.thickness, 10)], g = /%$/, n, f = w(f, function (a) {
					g.test(a) && (
						a = q(a, 10) * h / 100
						);
					return a
				});
			c.shape === "circle" ? (
				a = -Math.PI / 2, b = Math.PI * 1.5, n = !0
				) : (
				a = e + this.translate(a), b = e + this.translate(b)
				);
			return this.chart.renderer.symbols.arc(this.left + d[0], this.top + d[1], f[0], f[0], {start: a, end: b, innerR: j(f[1], f[0] - f[2]), open: n})
		}, getPlotLinePath: function (a) {
			var b = this.center, c = this.chart, d = this.getPosition(a);
			return this.isCircular ? ["M", b[0] + c.plotLeft, b[1] + c.plotTop,
				"L", d.x, d.y] : u.getLinePath.call(this, this.translate(a))
		}, getTitlePosition: function () {
			var a = this.center, b = this.chart, c = this.options.title;
			return{x: b.plotLeft + a[0] + (
				c.x || 0
				), y: b.plotTop + a[1] - {high: 0.5, middle: 0.25, low: 0}[this.options.title.align] * a[2] + (
				c.y || 0
				)}
		}};
		r.init = function (a) {
			return function (b, c) {
				var d = b.angular, e = b.polar, h = c.isX, f;
				if (d) {
					if (s(this, h ? x : u), f = !h) {
						this.defaultRadialOptions = this.defaultGaugeOptions
					}
				}
				else if (e) {
					s(this, u), this.defaultRadialOptions = (
						f = h
						) ? this.defaultRadialXOptions : this.defaultRadialYOptions;
				}
				a.apply(this, arguments);
				if (d || e) {
					d = this.options, this.startAngleRad = (
						d.startAngle - 90
						) * Math.PI / 180, this.endAngleRad = (
						d.endAngle - 90
						) * Math.PI / 180, this.offset = d.offset || 0, this.isCircular = f
				}
			}
		}(r.init);
		l.getPosition = function (a) {
			return function () {
				var b = this.axis, c = arguments;
				return b.getPosition ? b.getPosition(c[1]) : a.apply(this, c)
			}
		}(l.getPosition);
		l.getLabelPosition = function (a) {
			return function () {
				var b = this.axis, c = b.options.labels, d = this.label, e = c.y, h;
				b.isRadial ? (
					h = b.getPosition(this.pos, b.center[2] / 2 + j(c.distance,
						-25)), c.rotation === "auto" ? d.attr({rotation: (
						b.translate(this.pos) + b.startAngleRad + Math.PI / 2
						) / Math.PI * 180}) : e === null && (
						e = q(d.styles.lineHeight) * 0.9 - d.getBBox().height / 2
						), h.x += c.x, h.y += e
					) : h = a.apply(this, arguments);
				return h
			}
		}(l.getLabelPosition);
		l.getMarkPath = function (a) {
			return function (b, c, d) {
				var e = this.axis;
				e.isRadial ? (
					e = e.getPosition(this.pos, e.center[2] / 2 + d), e = ["M", b, c, "L", e.x, e.y]
					) : e = a.apply(this, arguments);
				return e
			}
		}(l.getMarkPath);
		i.arearange = p(i.area, {lineWidth: 0, threshold: null, tooltip: {pointFormat: '<span style="color:{series.color}">{series.name}</span>: {point.low} - {point.high}'},
			trackByArea: !0, dataLabels: {yHigh: -6, yLow: 16}});
		l = g.extendClass(g.Point, {applyOptions: function (a) {
			var b = this.series, c = 0;
			if (typeof a === "object" && typeof a.length !== "number") {
				s(this, a), this.options = a;
			}
			else if (a.length) {
				if (a.length === 3) {
					if (typeof a[0] === "string") {
						this.name = a[0];
					}
					else if (typeof a[0] === "number") {
						this.x = a[0];
					}
					c++
				}
				this.low = a[c++];
				this.high = a[c++]
			}
			if (this.high === null) {
				this.low = null;
			}
			this.y = this.low;
			if (this.x === v && b) {
				this.x = b.autoIncrement();
			}
			return this
		}, toYData: function () {
			return[this.low, this.high]
		}});
		k.arearange = g.extendClass(k.area, {type: "arearange", valueCount: 2, pointClass: l, translate: function () {
			var a = this.yAxis;
			k.area.prototype.translate.apply(this);
			o(this.points, function (b) {
				if (b.y !== null) {
					b.plotLow = b.plotY, b.plotHigh = a.translate(b.high, 0, 1, 0, 1)
				}
			})
		}, getSegmentPath: function (a) {
			for (var b = [], c = a.length, d = t.prototype.getSegmentPath, e; c--;) {
				e = a[c], b.push({plotX: e.plotX, plotY: e.plotHigh});
			}
			a = d.call(this, a);
			d = d.call(this, b);
			b = [].concat(a, d);
			d[0] = "L";
			this.areaPath = this.areaPath.concat(a, d);
			return b
		}, drawDataLabels: function () {
			var a =
				this.points, b = a.length, c, d = [], e = t.prototype.drawDataLabels, h = this.options.dataLabels, f;
			for (c = b; c--;) {
				f = a[c], f.y = f.high, f.plotY = f.plotHigh, d[c] = f.dataLabel, f.dataLabel = f.dataLabelUpper, h.y = h.yHigh;
			}
			e.apply(this, arguments);
			for (c = b; c--;) {
				f = a[c], f.dataLabelUpper = f.dataLabel, f.dataLabel = d[c], f.y = f.low, f.plotY = f.plotLow, h.y = h.yLow;
			}
			e.apply(this, arguments)
		}, drawPoints: m});
		i.gauge = p(i.line, {dataLabels: {enabled: !0, y: 30, borderWidth: 1, borderColor: "silver", borderRadius: 3, style: {fontWeight: "bold"}}, dial: {}, pivot: {},
			tooltip: {headerFormat: ""}, showInLegend: !1});
		i = {type: "gauge", pointClass: g.extendClass(g.Point, {setState: function (a) {
			this.state = a
		}}), angular: !0, translate: function () {
			var a = this, b = a.yAxis, c = b.center;
			a.generatePoints();
			o(a.points, function (d) {
				var e = p(a.options.dial, d.dial), h = q(j(e.radius, 80)) * c[2] / 200, f = q(j(e.baseLength, 70)) * h / 100, g = q(j(e.rearLength, 10)) * h / 100, n = e.baseWidth || 3, i = e.topWidth || 1;
				d.shapeType = "path";
				d.shapeArgs = {d: e.path || ["M", -g, -n / 2, "L", f, -n / 2, h, -i / 2, h, i / 2, f, n / 2, -g, n / 2], translateX: c[0], translateY: c[1],
					rotation: (
						b.startAngleRad + b.translate(d.y)
						) * 180 / Math.PI};
				d.plotX = c[0];
				d.plotY = c[1]
			})
		}, drawPoints: function () {
			var a = this, b = a.yAxis.center, c = a.pivot, d = a.options, e = d.pivot, h = d.dial;
			o(a.points, function (b) {
				var c = b.graphic, d = b.shapeArgs, e = d.d;
				c ? (
					c.animate(d), d.d = e
					) : b.graphic = a.chart.renderer[b.shapeType](d).attr({stroke: h.borderColor || "none", "stroke-width": h.borderWidth || 0, fill: h.backgroundColor || "black"}).add(a.group)
			});
			c ? c.animate({cx: b[0], cy: b[1]}) : a.pivot = a.chart.renderer.circle(b[0], b[1], j(e.radius,
				5)).attr({"stroke-width": e.borderWidth || 0, stroke: e.borderColor || "silver", fill: e.backgroundColor || "black"}).add(a.group)
		}, animate: function () {
			var a = this;
			o(a.points, function (b) {
				var c = b.graphic;
				c && (
					c.attr({rotation: a.yAxis.startAngleRad * 180 / Math.PI}), c.animate({rotation: b.shapeArgs.rotation}, a.options.animation)
					)
			});
			a.animate = null
		}, render: function () {
			this.createGroup();
			k.pie.prototype.render.call(this)
		}, setData: k.pie.prototype.setData, drawTracker: k.column.prototype.drawTracker};
		k.gauge = g.extendClass(k.line,
			i);
		i = t.prototype;
		m = k.column.prototype;
		i.toXY = function (a) {
			var b, c = this.chart;
			a.rectPlotX = a.plotX;
			a.rectPlotY = a.plotY;
			b = this.xAxis.postTranslate(a.plotX, this.yAxis.len - a.plotY);
			a.plotX = a.polarPlotX = b.x - c.plotLeft;
			a.plotY = a.polarPlotY = b.y - c.plotTop
		};
		i.translate = function (a) {
			return function () {
				a.apply(this, arguments);
				if (this.xAxis.getPosition && this.type !== "column") {
					for (var b = this.points, c = b.length; c--;) {
						this.toXY(b[c])
					}
				}
			}
		}(i.translate);
		m.translate = function (a) {
			return function () {
				var b = this.xAxis, c = this.yAxis.len,
					d = b.center, e = b.startAngleRad, h = this.chart.renderer, f, g;
				a.apply(this, arguments);
				if (b.isRadial) {
					b = this.points;
					for (g = b.length; g--;) {
						f = b[g], f.shapeType = "path", f.shapeArgs = {d: h.symbols.arc(d[0], d[1], c - f.plotY, null, {start: e + f.barX, end: e + f.barX + f.pointWidth, innerR: c - f.yBottom})}, this.toXY(f)
					}
				}
			}
		}(m.translate)
	}
	)(Highcharts);
