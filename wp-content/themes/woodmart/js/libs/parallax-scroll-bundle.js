/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */

 (function( $ ){
    $(function() {
        if ( $(window).width() <= 1024 || ( ! $('body').hasClass('elementor-editor-active') && 0 === $('.wd-parallax-on-scroll').length && 0 === $('[data-parallax]').length ) ) return;
        ParallaxScroll.init();
    });

    var ParallaxScroll = {
        /* PUBLIC VARIABLES */
        showLogs: false,
        round: 1000,

        /* PUBLIC FUNCTIONS */
        init: function() {
            this._log("init");
            if (this._inited) {
                this._log("Already Inited");
                this._inited = true;
                return;
            }
            this._requestAnimationFrame = (function(){
              return  window.requestAnimationFrame       || 
                      window.webkitRequestAnimationFrame || 
                      window.mozRequestAnimationFrame    || 
                      window.oRequestAnimationFrame      || 
                      window.msRequestAnimationFrame     || 
                      function(/* function */ callback, /* DOMElement */ element){
                          window.setTimeout(callback, 1000 / 60);
                      };
            })();
            this._onScroll(true);
        },

        /* PRIVATE VARIABLES */
        _inited: false,
        _properties: ['x', 'y', 'z', 'rotateX', 'rotateY', 'rotateZ', 'scaleX', 'scaleY', 'scaleZ', 'scale'],
        _requestAnimationFrame:null,

        /* PRIVATE FUNCTIONS */
        _log: function(message) {
            if (this.showLogs) console.log("Parallax Scroll / " + message);
        },
        _onScroll: function(noSmooth) {
            var scroll = $(document).scrollTop();
            var windowHeight = $(window).height();
            this._log("onScroll " + scroll);
            $("[data-parallax], .wd-parallax-on-scroll").each($.proxy(function(index, el) {
                var $el = $(el);
                var properties = [];
                var applyProperties = false;
                var style = $el.data("style");
                if (style == undefined) {
                    style = $el.attr("style") || "";
                    $el.data("style", style);
                }
                var datas;
                if (!$el.hasClass('wd-parallax-on-scroll')) {
                    datas = [$el.data("parallax")];
                } else {
                    var classes = $el.attr('class').split(' ');
                    datas = [[]];
                    for (var index = 0; index < classes.length; index++) {
                        if (classes[index].indexOf('wd_scroll') >= 0) {
                            var data = classes[index].split('_');
                            datas[0][data[2]] = data[3]
                        }
                    }
                }
                var iData;
                for(iData = 2; ; iData++) {
                    if($el.data("parallax"+iData)) {
                        datas.push($el.data("parallax-"+iData));
                    }
                    else {
                        break;
                    }
                }
                var datasLength = datas.length;
                for(iData = 0; iData < datasLength; iData ++) {
                    var data = datas[iData];
                    var scrollFrom = data["from-scroll"];
                    if (scrollFrom == undefined) scrollFrom = Math.max(0, $(el).offset().top - windowHeight);
                    scrollFrom = scrollFrom | 0;
                    var scrollDistance = data["distance"];
                    var scrollTo = data["to-scroll"];
                    if (scrollDistance == undefined && scrollTo == undefined) scrollDistance = windowHeight;
                    scrollDistance = Math.max(scrollDistance | 0, 1);
                    var easing = data["easing"];
                    var easingReturn = data["easing-return"];
                    if (easing == undefined || !$.easing|| !$.easing[easing]) easing = null;
                    if (easingReturn == undefined || !$.easing|| !$.easing[easingReturn]) easingReturn = easing;
                    if (easing) {
                        var totalTime = data["duration"];
                        if (totalTime == undefined) totalTime = scrollDistance;
                        totalTime = Math.max(totalTime | 0, 1);
                        var totalTimeReturn = data["duration-return"];
                        if (totalTimeReturn == undefined) totalTimeReturn = totalTime;
                        scrollDistance = 1;
                        var currentTime = $el.data("current-time");
                        if(currentTime == undefined) currentTime = 0;
                    }
                    if (scrollTo == undefined) scrollTo = scrollFrom + scrollDistance;
                    scrollTo = scrollTo | 0;
                    var smoothness = data["smoothness"];
                    if (smoothness == undefined) smoothness = 30;
                    smoothness = smoothness | 0;
                    if (noSmooth || smoothness == 0) smoothness = 1;
                    smoothness = smoothness | 0;
                    var scrollCurrent = scroll;
                    scrollCurrent = Math.max(scrollCurrent, scrollFrom);
                    scrollCurrent = Math.min(scrollCurrent, scrollTo);
                    if(easing) {
                        if($el.data("sens") == undefined) $el.data("sens", "back");
                        if(scrollCurrent>scrollFrom) {
                            if($el.data("sens") == "back") {
                                currentTime = 1;
                                $el.data("sens", "go");
                            }
                            else {
                                currentTime++;
                            }
                        }
                        if(scrollCurrent<scrollTo) {
                            if($el.data("sens") == "go") {
                                currentTime = 1;
                                $el.data("sens", "back");
                            }
                            else {
                                currentTime++;
                            }
                        }
                        if(noSmooth) currentTime = totalTime;
                        $el.data("current-time", currentTime);
                    }
                    this._properties.map($.proxy(function(prop) {
                        var defaultProp = 0;
                        var to = data[prop];
                        if (to == undefined) return;
                        if(prop=="scale" || prop=="scaleX" || prop=="scaleY" || prop=="scaleZ" ) {
                            defaultProp = 1;
                        }
                        else {
                            to = to | 0;
                        }
                        var prev = $el.data("_" + prop);
                        if (prev == undefined) prev = defaultProp;
                        var next = ((to-defaultProp) * ((scrollCurrent - scrollFrom) / (scrollTo - scrollFrom))) + defaultProp;
                        var val = prev + (next - prev) / smoothness;
                        if(easing && currentTime>0 && currentTime<=totalTime) {
                            var from = defaultProp;
                            if($el.data("sens") == "back") {
                                from = to;
                                to = -to;
                                easing = easingReturn;
                                totalTime = totalTimeReturn;
                            }
                            val = $.easing[easing](null, currentTime, from, to, totalTime);
                        }
                        val = Math.ceil(val * this.round) / this.round;
                        if(val==prev&&next==to) val = to;
                        if(!properties[prop]) properties[prop] = 0;
                        properties[prop] += val;
                        if (prev != properties[prop]) {
                            $el.data("_" + prop, properties[prop]);
                            applyProperties = true;
                        }
                    }, this));
                }
                if (applyProperties) {
                    if (properties["z"] != undefined) {
                        var perspective = data["perspective"];
                        if (perspective == undefined) perspective = 800;
                        var $parent = $el.parent();
                        if(!$parent.data("style")) $parent.data("style", $parent.attr("style") || "");
                        $parent.attr("style", "perspective:" + perspective + "px; -webkit-perspective:" + perspective + "px; "+ $parent.data("style"));
                    }
                    if(properties["scaleX"] == undefined) properties["scaleX"] = 1;
                    if(properties["scaleY"] == undefined) properties["scaleY"] = 1;
                    if(properties["scaleZ"] == undefined) properties["scaleZ"] = 1;
                    if (properties["scale"] != undefined) {
                        properties["scaleX"] *= properties["scale"];
                        properties["scaleY"] *= properties["scale"];
                        properties["scaleZ"] *= properties["scale"];
                    }
                    var translate3d = "translate3d(" + (properties["x"] ? properties["x"] : 0) + "px, " + (properties["y"] ? properties["y"] : 0) + "px, " + (properties["z"] ? properties["z"] : 0) + "px)";
                    var rotate3d = "rotateX(" + (properties["rotateX"] ? properties["rotateX"] : 0) + "deg) rotateY(" + (properties["rotateY"] ? properties["rotateY"] : 0) + "deg) rotateZ(" + (properties["rotateZ"] ? properties["rotateZ"] : 0) + "deg)";
                    var scale3d = "scaleX(" + properties["scaleX"] + ") scaleY(" + properties["scaleY"] + ") scaleZ(" + properties["scaleZ"] + ")";
                    var cssTransform = translate3d + " " + rotate3d + " " + scale3d + ";";
                    this._log(cssTransform);
                    $el.attr("style", "transform:" + cssTransform + " -webkit-transform:" + cssTransform + " " + style);
                }
            }, this));
            if(window.requestAnimationFrame) {
                window.requestAnimationFrame($.proxy(this._onScroll, this, false));
            }
            else {
                this._requestAnimationFrame($.proxy(this._onScroll, this, false));
            }
        }
    };
})(jQuery);
/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend(jQuery.easing,
    {
        def: 'easeOutQuad',
        swing: function (x, t, b, c, d) {
            //alert(jQuery.easing.default);
            return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
        },
        easeInQuad: function (x, t, b, c, d) {
            return c * (t /= d) * t + b;
        },
        easeOutQuad: function (x, t, b, c, d) {
            return -c * (t /= d) * (t - 2) + b;
        },
        easeInOutQuad: function (x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t + b;
            return -c / 2 * ((--t) * (t - 2) - 1) + b;
        },
        easeInCubic: function (x, t, b, c, d) {
            return c * (t /= d) * t * t + b;
        },
        easeOutCubic: function (x, t, b, c, d) {
            return c * ((t = t / d - 1) * t * t + 1) + b;
        },
        easeInOutCubic: function (x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        },
        easeInQuart: function (x, t, b, c, d) {
            return c * (t /= d) * t * t * t + b;
        },
        easeOutQuart: function (x, t, b, c, d) {
            return -c * ((t = t / d - 1) * t * t * t - 1) + b;
        },
        easeInOutQuart: function (x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
            return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
        },
        easeInQuint: function (x, t, b, c, d) {
            return c * (t /= d) * t * t * t * t + b;
        },
        easeOutQuint: function (x, t, b, c, d) {
            return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
        },
        easeInOutQuint: function (x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
        },
        easeInSine: function (x, t, b, c, d) {
            return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
        },
        easeOutSine: function (x, t, b, c, d) {
            return c * Math.sin(t / d * (Math.PI / 2)) + b;
        },
        easeInOutSine: function (x, t, b, c, d) {
            return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
        },
        easeInExpo: function (x, t, b, c, d) {
            return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
        },
        easeOutExpo: function (x, t, b, c, d) {
            return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
        },
        easeInOutExpo: function (x, t, b, c, d) {
            if (t == 0) return b;
            if (t == d) return b + c;
            if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
            return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
        },
        easeInCirc: function (x, t, b, c, d) {
            return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
        },
        easeOutCirc: function (x, t, b, c, d) {
            return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
        },
        easeInOutCirc: function (x, t, b, c, d) {
            if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
            return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
        },
        easeInElastic: function (x, t, b, c, d) {
            var s = 1.70158; var p = 0; var a = c;
            if (t == 0) return b; if ((t /= d) == 1) return b + c; if (!p) p = d * .3;
            if (a < Math.abs(c)) { a = c; var s = p / 4; }
            else var s = p / (2 * Math.PI) * Math.asin(c / a);
            return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
        },
        easeOutElastic: function (x, t, b, c, d) {
            var s = 1.70158; var p = 0; var a = c;
            if (t == 0) return b; if ((t /= d) == 1) return b + c; if (!p) p = d * .3;
            if (a < Math.abs(c)) { a = c; var s = p / 4; }
            else var s = p / (2 * Math.PI) * Math.asin(c / a);
            return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
        },
        easeInOutElastic: function (x, t, b, c, d) {
            var s = 1.70158; var p = 0; var a = c;
            if (t == 0) return b; if ((t /= d / 2) == 2) return b + c; if (!p) p = d * (.3 * 1.5);
            if (a < Math.abs(c)) { a = c; var s = p / 4; }
            else var s = p / (2 * Math.PI) * Math.asin(c / a);
            if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
            return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
        },
        easeInBack: function (x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            return c * (t /= d) * t * ((s + 1) * t - s) + b;
        },
        easeOutBack: function (x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
        },
        easeInOutBack: function (x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            if ((t /= d / 2) < 1) return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
            return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
        },
        easeInBounce: function (x, t, b, c, d) {
            return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
        },
        easeOutBounce: function (x, t, b, c, d) {
            if ((t /= d) < (1 / 2.75)) {
                return c * (7.5625 * t * t) + b;
            } else if (t < (2 / 2.75)) {
                return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
            } else if (t < (2.5 / 2.75)) {
                return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
            } else {
                return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
            }
        },
        easeInOutBounce: function (x, t, b, c, d) {
            if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
            return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
        }
    });