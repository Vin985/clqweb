//Responsive Menu
! function(a, b, c) {
  "use strict";
  var d = function(d, e) {
    var f = !!b.getComputedStyle;
    f || (b.getComputedStyle = function(a) {
      return this.el = a, this.getPropertyValue = function(b) {
        var c = /(\-([a-z]){1})/g;
        return "float" === b && (b = "styleFloat"), c.test(b) && (b = b.replace(c, function() {
          return arguments[2].toUpperCase()
        })), a.currentStyle[b] ? a.currentStyle[b] : null
      }, this
    });
    var g, h, i, j, k, l, m = function(a, b, c, d) {
        if ("addEventListener" in a) try {
          a.addEventListener(b, c, d)
        } catch (e) {
          if ("object" != typeof c || !c.handleEvent) throw e;
          a.addEventListener(b, function(a) {
            c.handleEvent.call(c, a)
          }, d)
        } else "attachEvent" in a && ("object" == typeof c && c.handleEvent ? a.attachEvent("on" + b, function() {
          c.handleEvent.call(c)
        }) : a.attachEvent("on" + b, c))
      },
      n = function(a, b, c, d) {
        if ("removeEventListener" in a) try {
          a.removeEventListener(b, c, d)
        } catch (e) {
          if ("object" != typeof c || !c.handleEvent) throw e;
          a.removeEventListener(b, function(a) {
            c.handleEvent.call(c, a)
          }, d)
        } else "detachEvent" in a && ("object" == typeof c && c.handleEvent ? a.detachEvent("on" + b, function() {
          c.handleEvent.call(c)
        }) : a.detachEvent("on" + b, c))
      },
      o = function(a) {
        if (a.children.length < 1) throw new Error("The Nav container has no containing elements");
        for (var b = [], c = 0; c < a.children.length; c++) 1 === a.children[c].nodeType && b.push(a.children[c]);
        return b
      },
      p = function(a, b) {
        for (var c in b) a.setAttribute(c, b[c])
      },
      q = function(a, b) {
        0 !== a.className.indexOf(b) && (a.className += " " + b, a.className = a.className.replace(/(^\s*)|(\s*$)/g, ""))
      },
      r = function(a, b) {
        var c = new RegExp("(\\s|^)" + b + "(\\s|$)");
        a.className = a.className.replace(c, " ").replace(/(^\s*)|(\s*$)/g, "")
      },
      s = function(a, b, c) {
        for (var d = 0; d < a.length; d++) b.call(c, d, a[d])
      },
      t = a.createElement("style"),
      u = a.documentElement,
      v = function(b, c) {
        var d;
        this.options = {
          animate: !0,
          transition: 284,
          label: "Menu",
          insert: "before",
          customToggle: "",
          closeOnNavClick: !1,
          openPos: "relative",
          navClass: "nav-collapse",
          navActiveClass: "js-nav-active",
          jsClass: "js",
          init: function() {},
          open: function() {},
          close: function() {}
        };
        for (d in c) this.options[d] = c[d];
        if (q(u, this.options.jsClass), this.wrapperEl = b.replace("#", ""), a.getElementById(this.wrapperEl)) this.wrapper = a.getElementById(this.wrapperEl);
        else {
          if (!a.querySelector(this.wrapperEl)) throw new Error("The nav element you are trying to select doesn't exist");
          this.wrapper = a.querySelector(this.wrapperEl)
        }
        this.wrapper.inner = o(this.wrapper), h = this.options, g = this.wrapper, this._init(this)
      };
    return v.prototype = {
      destroy: function() {
        this._removeStyles(), r(g, "closed"), r(g, "opened"), r(g, h.navClass), r(g, h.navClass + "-" + this.index), r(u, h.navActiveClass), g.removeAttribute("style"), g.removeAttribute("aria-hidden"), n(b, "resize", this, !1), n(b, "focus", this, !1), n(a.body, "touchmove", this, !1), n(i, "touchstart", this, !1), n(i, "touchend", this, !1), n(i, "mouseup", this, !1), n(i, "keyup", this, !1), n(i, "click", this, !1), h.customToggle ? i.removeAttribute("aria-hidden") : i.parentNode.removeChild(i)
      },
      toggle: function() {
        j === !0 && (l ? this.close() : this.open())
      },
      open: function() {
        l || (r(g, "closed"), q(g, "opened"), q(u, h.navActiveClass), q(i, "active"), g.style.position = h.openPos, p(g, {
          "aria-hidden": "false"
        }), l = !0, h.open())
      },
      close: function() {
        l && (q(g, "closed"), r(g, "opened"), r(u, h.navActiveClass), r(i, "active"), p(g, {
          "aria-hidden": "true"
        }), h.animate ? (j = !1, setTimeout(function() {
          g.style.position = "absolute", j = !0
        }, h.transition + 10)) : g.style.position = "absolute", l = !1, h.close())
      },
      resize: function() {
        "none" !== b.getComputedStyle(i, null).getPropertyValue("display") ? (k = !0, p(i, {
          "aria-hidden": "false"
        }), g.className.match(/(^|\s)closed(\s|$)/) && (p(g, {
          "aria-hidden": "true"
        }), g.style.position = "absolute"), this._createStyles(), this._calcHeight()) : (k = !1, p(i, {
          "aria-hidden": "true"
        }), p(g, {
          "aria-hidden": "false"
        }), g.style.position = h.openPos, this._removeStyles())
      },
      handleEvent: function(a) {
        var c = a || b.event;
        switch (c.type) {
          case "touchstart":
            this._onTouchStart(c);
            break;
          case "touchmove":
            this._onTouchMove(c);
            break;
          case "touchend":
          case "mouseup":
            this._onTouchEnd(c);
            break;
          case "click":
            this._preventDefault(c);
            break;
          case "keyup":
            this._onKeyUp(c);
            break;
          case "focus":
          case "resize":
            this.resize(c)
        }
      },
      _init: function() {
        this.index = c++, q(g, h.navClass), q(g, h.navClass + "-" + this.index), q(g, "closed"), j = !0, l = !1, this._closeOnNavClick(), this._createToggle(), this._transitions(), this.resize();
        var d = this;
        setTimeout(function() {
          d.resize()
        }, 20), m(b, "resize", this, !1), m(b, "focus", this, !1), m(a.body, "touchmove", this, !1), m(i, "touchstart", this, !1), m(i, "touchend", this, !1), m(i, "mouseup", this, !1), m(i, "keyup", this, !1), m(i, "click", this, !1), h.init()
      },
      _createStyles: function() {
        t.parentNode || (t.type = "text/css", a.getElementsByTagName("head")[0].appendChild(t))
      },
      _removeStyles: function() {
        t.parentNode && t.parentNode.removeChild(t)
      },
      _createToggle: function() {
        if (h.customToggle) {
          var b = h.customToggle.replace("#", "");
          if (a.getElementById(b)) i = a.getElementById(b);
          else {
            if (!a.querySelector(b)) throw new Error("The custom nav toggle you are trying to select doesn't exist");
            i = a.querySelector(b)
          }
        } else {
          var c = a.createElement("a");
          c.innerHTML = h.label, p(c, {
            href: "#",
            "class": "nav-toggle"
          }), "after" === h.insert ? g.parentNode.insertBefore(c, g.nextSibling) : g.parentNode.insertBefore(c, g), i = c
        }
      },
      _closeOnNavClick: function() {
        if (h.closeOnNavClick) {
          var a = g.getElementsByTagName("a"),
            b = this;
          s(a, function(c) {
            m(a[c], "click", function() {
              k && b.toggle()
            }, !1)
          })
        }
      },
      _preventDefault: function(a) {
        return a.preventDefault ? (a.stopImmediatePropagation && a.stopImmediatePropagation(), a.preventDefault(), a.stopPropagation(), !1) : void(a.returnValue = !1)
      },
      _onTouchStart: function(a) {
        Event.prototype.stopImmediatePropagation || this._preventDefault(a), this.startX = a.touches[0].clientX, this.startY = a.touches[0].clientY, this.touchHasMoved = !1, n(i, "mouseup", this, !1)
      },
      _onTouchMove: function(a) {
        (Math.abs(a.touches[0].clientX - this.startX) > 10 || Math.abs(a.touches[0].clientY - this.startY) > 10) && (this.touchHasMoved = !0)
      },
      _onTouchEnd: function(a) {
        if (this._preventDefault(a), k && !this.touchHasMoved) {
          if ("touchend" === a.type) return void this.toggle();
          var c = a || b.event;
          3 !== c.which && 2 !== c.button && this.toggle()
        }
      },
      _onKeyUp: function(a) {
        var c = a || b.event;
        13 === c.keyCode && this.toggle()
      },
      _transitions: function() {
        if (h.animate) {
          var a = g.style,
            b = "max-height " + h.transition + "ms";
          a.WebkitTransition = a.MozTransition = a.OTransition = a.transition = b
        }
      },
      _calcHeight: function() {
        for (var a = 0, b = 0; b < g.inner.length; b++) a += g.inner[b].offsetHeight;
        var c = "." + h.jsClass + " ." + h.navClass + "-" + this.index + ".opened{max-height:" + a + "px !important} ." + h.jsClass + " ." + h.navClass + "-" + this.index + ".opened.dropdown-active {max-height:9999px !important}";
        t.styleSheet ? t.styleSheet.cssText = c : t.innerHTML = c, c = ""
      }
    }, new v(d, e)
  };
  "undefined" != typeof module && module.exports ? module.exports = d : b.responsiveNav = d
}(document, window, 0);
