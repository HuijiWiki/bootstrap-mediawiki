/*! Copyright (C) 2012 Lunarity
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/*jshint browser:true jquery:true laxbreak:true smarttabs:true multistr:true trailing:true */
/*global mediaWiki */
 
// Standard cite:
// <sup class="reference"><a href="#cite_note_0">[1]</a></sup>
// WARN: Format can be altered by a MediaWiki message
((window.dev = window.dev || {}).ReferencePopups = dev.ReferencePopups || {}).lockdown = true;    
 
// Global namespace
window.dev = window.dev || {};
/*global dev */
dev.ReferencePopups = dev.ReferencePopups || {};
// Debugging function
dev.ReferencePopups.unload = dev.ReferencePopups.unload || function () {
    "use strict";
    delete this.Popup;
    delete this.configure;
    if (this.unloadCore) {
        this.unloadCore();
    }
};
 
 
// i18n messages
(function ( module, lang ) {
    /*jshint forin:false */
    "use strict";
    var i18n = {
        en: {
            coreConfigureText: 'Configure Reference Popups',
            coreConfigureHover: 'Change settings for Reference Popups'
        },
        fr: {
            coreConfigureText: 'Configurer Popups de Référence',
            coreConfigureHover: 'Modifier les préférences pour les Popups de Référence'
        },
        it: {
            coreConfigureText: 'Configura le note a popup',
            coreConfigureHover: 'Cambia le impostazioni per le note a popup'
        },
        pl: {
            coreConfigureText: 'Skonfiguruj wyskakujące przypisy',
            coreConfigureHover: 'Zmień ustawienia dla wyskakujących przypisów'
        },
        'pt-br': {
            coreConfigureText: 'Configurar Popups de Referência',
            coreConfigureHover: 'Mudar configurações para Popups de Referência'
        },
        vi: {
            coreConfigureText: 'Thiết đặt popup tham khảo',
            coreConfigureHover: 'Thay đổi thiết đặt cho popup tham khảo'
        },
        ca: {
            coreConfigureText: 'Configura referències emergents',
            coreConfigureHover: 'Canviar la configuració de referències emergents'
        },
        es: {
            coreConfigureText: 'Configurar popups de referencias',
            coreConfigureHover: 'Modificar la configuración de los popups de referencias'
        },
        ko: {
            coreConfigureText: '주석 말풍선 설정',
            coreConfigureHover: '주석 말풍선 설정하기'
        }
    };
    var msg = module.messages = module.messages || {};
    for (var m in i18n.en) {
        msg[m] = msg[m] || (i18n[lang] && i18n[lang][m]) || i18n.en[m];
    }
})(dev.ReferencePopups, mediaWiki.config.get('wgUserLanguage'));
 
 
 
// The popup itself is separated from the core logic which makes it reusable.
// Load dependencies
(function ( window, $, mw ) {
    "use strict";
 
    // Double runs
    var module = window.dev.ReferencePopups;
    if (module.Popup) {
        return $.noop;
    }
 
    // Deps
    var mwReady = $.Deferred(),
        mwDeps = ['jquery.ui.position', 'jquery.effects.fold', 'jquery.ui.core', 'jquery.ui.widget'];
    mw.loader.load(mwDeps, null, true);
    mw.loader.using(mwDeps, mwReady.resolve, mwReady.reject);
    var colors = window.dev.colors || $.ajax({
        url: mw.config.get('wgLoadScript'),
        data: {
            mode: 'articles',
            only: 'scripts',
            articles: 'w:dev:Colors/code.js'
        },
        dataType: 'script',
        cache: true
    });
 
    // Support CSS
    if (!module.cssLoaded) {
        window.importArticle({type: 'style', article: 'w:dev:ReferencePopups/code.css'});
        module.cssLoaded = true;
    }
 
    var dfd = $.Deferred();
    module.Popup = dfd.promise();
    return function ( callback ) {
        $.when(mwReady, colors).done(function () {
            dfd.resolve(module.Popup = callback(module, window, $, mw, window.dev.colors));
        }).fail(function () {
            delete module.Popup;
            dfd.reject();
        });
    };
}(window, jQuery, mediaWiki))(function ( module, window, $, mw, Colors ) {
    "use strict";
 
// Wikia are using jQuery 1.8 which removed the deprecated $.curCSS...
// but jQuery UI 1.8 needs it and they didn't update THAT to 1.9...
    $.curCSS = $.curCSS || $.css;
 
// Custom CSS. Try to make the popup fit into the skin by adapting to the color scheme
    (function ( color, Colors, mw, $ ) {
        // Colors interfacing. It doesn't handle various common values.
        function ifOk ( val, alt ) {
            // Colors chokes on transparent
            return (val && val !== 'transparent' && val !== 'rgba(0, 0, 0, 0)') ? val : alt;
        }
        function tryParse ( val, alt ) {
            try {
                return Colors.parse(val);
            } catch (e) {
                if (window.console) {
                    window.console.warn('Colors Parse Error (' + val + '): ', e, e.stack);
                }
                return Colors.parse(alt);
            }
        }
        function toRgba ( color, alpha ) {
            return 'rgba(' + color.red() + ',' + color.green() + ',' + color.blue() + ',' + alpha + ')';
        }
 
        if (mw.config.get('skin') === 'oasis') {
            color.page = Colors.parse(Colors.wikia.page);
            color.pageBorder = Colors.parse(Colors.wikia.border);
            color.accent = Colors.parse(Colors.wikia.menu);
            color.popText = Colors.wikia.text;
        } else {
            var $content = $('#content');
            color.page = tryParse(ifOk($('#globalWrapper').css('backgroundColor'), $content.css('backgroundColor')), 'white');
            color.pageBorder = tryParse(ifOk($content.css('borderLeftColor'), $content.css('borderRightColor')), '#AAA');
            color.accent = tryParse(ifOk($('#footer').css('borderTopColor'), $('body').css('backgroundColor')), '#fabd23');
            color.popText = tryParse($('#mw-content-text').css('color'), 'black');
        }
        // Calculate color variants, we want the popup to stand out from the page slightly
        // I do that by calculating a module color like the rail modules.
        var mixCol;
        if (color.page.isBright()) { // Darken, desaturate and blue shift
            color.back = color.page.mix(mixCol = 'black', 95).mix('blue', 97);
        } else { // Lighten, desaturate
            color.back = color.page.mix(mixCol = 'white', 95);
        }
        color.popEdge = color.accent.mix(color.pageBorder, 90);
 
        // Background gradient colors, faint glass-like effect
        color.backA = toRgba(color.back, 0.95);
        color.backB = toRgba(color.back.mix(mixCol, 99), 0.95);
        color.backC = toRgba(color.back.mix(mixCol, 97), 0.95);
 
        // Style sheet for the popup itself
        Colors.css('\
	.refpopups-box {\
		background-color: $back;\
		color: $popText;\
		border-color: $popEdge;\
		background: -webkit-linear-gradient(30deg, $backA, $backB 15%, $backB 25%, $backA 40%, $backA 50%, $backB 60%, $backB 70%, $backC 70%, $backC 90%, $backA 92%);\
		background: linear-gradient(60deg, $backA, $backB 15%, $backB 25%, $backA 40%, $backA 50%, $backB 60%, $backB 70%, $backC 70%, $backC 90%, $backA 92%);\
	}\
	.refpopups-chevron-in {\
		border-top-color: $back;\
		border-top-color: $backA;\
	}\
	.refpopups-flipped > .refpopups-chevron-in {\
		border-bottom-color: $back;\
		border-bottom-color: $backA;\
	}\
	.refpopups-chevron-out {\
		border-top-color: $popEdge;\
	}\
	.refpopups-flipped > .refpopups-chevron-out {\
		border-bottom-color: $popEdge;\
	}', color);
    })(module.colors = module.colors || {}, Colors, mw, $);
 
// The reference popup itself. [Requires UI 1.8, tries to use parts of 1.9]
// It's implemented as jQuery UI widget for structural and convenience reasons, but this
// isn't really intended to be used by 3rd party code (although it could be)
    var widgetId = 0;
    $.widget('Lunarity.referencePopup', {
        options: {// Default option configuration
            activateBy: 'hover',
            hoverDelay: 200,
            content: '', // primary content
            disabled: false, // Disables all events and hides if currently visible
            visible: false,
            animation: 'fold', // Values: false, or string name
            animSpeed: 300,
            context: null, // context node to attach the popup to (#mw-content-text) [Default: body, watch the z-index]
            escapeCloses: true,
            extraClass: '', // Extra CSS classes for repurposing the popups (e.g. width control)
            contentBoxClass: '', // Classes applied to the content box (WikiaArticle)
            stickyHover: false, // Ignore mouseleave from the element we are attached to.
 
            open: null, // open/close event callbacks (optional)
            close: null
        },
        // Constructor, no parameters but the options member is configured.
        _create: function () {
            // Requires jQuery UI 1.9 to set this automatically
            if (!this.document) {
                this.document = $(this.element[0].ownerDocument);
                this.window = $(this.document[0].defaultView || this.document[0].parentWindow);
                this.eventNamespace = '.' + this.widgetBaseClass + widgetId++;
            }
            this._timeouts = {};
            // The outer box is unstyled which is necessary to avoid borders screwing us with
            // offsetParent calculations for the chevrons. (top/left relative to padding-box, offset
            // is border-box)
            this._$popup = $(
                '<div class="refpopups-popup" id="' + this.eventNamespace.substr(1) + '">' +
                '<div class="refpopups-chevron-out"></div>' +
                '<div class="refpopups-chevron-in"></div>' +
                '<div class="refpopups-box">' +
                '</div></div>')
                .addClass(this.options.extraClass)
                .find('> .refpopups-box').addClass(this.options.contentBoxClass).end();
            this.options.context = $(this.document.find(this.options.context)[0]);
            if (!this.options.context.length) {
                this.options.context = $(this.document[0].body);
            }
            // Sanitise and call _installEvents
            this._setOption('activateBy', this.options.activateBy === 'click' ? 'click' : 'hover');
            this._updateContent();
            if (!this.options.disabled) {
                this.options.disabled = true;
                this.enable();
            }
            if (this.options.visible) {
                this.options.visible = false;
                this.show();
            }
        },
        _clearTimeout: function ( name ) {
            /*jshint eqnull:true */
            // !=/== null will match x === null || x === undefined
            if (this._timeouts[name] !== null) {
                window.clearTimeout(this._timeouts[name]);
                this._timeouts[name] = null;
            }
        },
        _setTimeout: function ( name, callback, delay ) {
            this._clearTimeout(name);
            var me = this;
            this._timeouts[name] = window.setTimeout(function () {
                me._timeouts[name] = null;
                callback.call(me);
            }, delay || 0);
        },
        // Installs/updates/replaces events on the various elements
        // This should be safe to call at any time although I suspect there may be
        // uncloseable and random closing glitches if the popup is visible.
        _installEvents: function () {
            var self = this, map, ns = this.eventNamespace;
            this._$popup.off(ns);
            this.element.off(ns);
            this._clearTimeout('open');
            this._clearTimeout('close');
 
            // If we're disabled then just kill all the events and stop
            if (this.options.disabled) {
                return;
            }
 
            // Install auto hide when mouseout
            if (this.options.activateBy === 'hover') {
                // NOTE: This is somewhat fiddly as I need to sync leaving the popup
                //	and entering the base element to avoid glitching.
                map = {};
                map['mouseenter' + ns] = function () {
                    self._clearTimeout('close');
                };
                map['mouseleave' + ns] = function ( ev ) {
                    self._setTimeout('close', function () {
                        this.hide(ev);
                    }, self.options.hoverDelay);
                };
                this._$popup.on(map);
 
                // Install the event handler on the base element
                map = {};
                map['mouseenter' + ns] = function ( ev ) {
                    // Clear the close timeout if a close cycle is happening
                    self._clearTimeout('close');
                    self._setTimeout('open', function () {
                        this.show(ev);
                    }, self.options.hoverDelay);
                };
                map['mouseleave' + ns] = function ( ev ) {
                    self._clearTimeout('open');
                    // If sticky hovering is off then we need to arm the close
                    // timeout. Otherwise, we'll just stick open.
                    if (!self.options.stickyHover) {
                        self._setTimeout('close', function () {
                            this.hide(ev);
                        }, self.options.hoverDelay);
                    }
                };
                this.element.on(map);
            }
            // Click event is always installed in order to deal with touchscreens
            // Touchscreens don't have hover, so when touching happens, we enable click
            // handling even in hover mode
            var lastTouch;
            map = {};
            map['touchEnd' + ns] = function ( ev ) {
                // Fires when touch stops, always fired, even if finger wanders away
                lastTouch = ev.originalEvent;
            };
            map['click' + ns] = function ( ev ) {
                // On touch screens, there is no hover so we always process clicks
                // defaultPrevented is IE9 or newer. I don't think IE8 has touch events anyway.
                if ((self.options.activateBy !== 'click' && (!lastTouch || lastTouch.defaultPrevented)) || self.options.disabled) {
                    return;
                }
                lastTouch = null; // for click event generated by touchEnd
                if (!self.options.visible) {
                    ev.preventDefault(); // Block normal interaction
                    self.show(ev);
                } else {
                    // If it's already visible then we allow the click to pass through
                    self.hide(ev);
                }
            };
            if (this.options.escapeCloses) {
                var onKeyUp = function ( ev ) {
                    if (ev.which === $.ui.keyCode.ESCAPE) {
                        self.hide(ev);
                    }
                };
                map['keyup' + ns] = onKeyUp;
                this._$popup.on('keyup' + ns, onKeyUp);
            }
            this.element.on(map);
            map = null;
        },
        triggerHover: function ( event ) {
            if (this.options.activateBy === 'hover') {
                this._setTimeout('open', function () {
                    this.show(event);
                }, this.options.hoverDelay);
            }
        },
        destroy: function () {
            this.hide();
            this._$popup.remove();
 
            this.element.off(this.eventNamespace); // FIXME: 1.9's destroy does this automatically
            return $.Widget.prototype.destroy.call(this);
        },
        _updateContent: function ( val ) {
            val = val || this.options.content || '';
            // Canonicalise to either a DOM node or a jQuery
            if (!val.jquery && !val.nodeType) {
                // The rewrap $() is to discard the prevObject chain
                this.options.content = val = $($(this.document[0].createElement('div')).html(val).contents().unwrap());
            }
            this._$popup.find('> .refpopups-box').empty().append(val);
        },
        // Adds/removes popup's id from ARIA describedby list for the element
        _describeAria: function ( yes ) {
            var k = 'aria-describedby',
                val = this.element.attr(k),
                s = val ? val.split(/\s+/g) : [],
                id = this._$popup[0].id,
                at = $.inArray(id, s);
            if (at !== -1) {
                if (!yes) {
                    s.splice(at, 1);
                }
            } else if (yes) {
                s.push(id);
            }
            val = s.join(' ');
            this.element[val ? 'attr' : 'removeAttr'](k, val);
        },
        show: function ( event ) {
            if (this.options.visible || this.options.disabled) {
                return;
            }
 
            // Popups are go
            this.options.context.append(this._$popup.stop(true, true));
            this._positionPopup();
            this._describeAria(true);
            if (this.options.animation) {
                this._$popup.hide().show(this.options.animation, this.options.animSpeed);
            }
 
            // Attach the scroll tracking event to the window to keep the popup from leaving
            // the visible area for as long as possible. Event handler is throttled for
            // performance.
            // WARN: This causes noticeable scroll lag when smooth scrolling in Firefox,
            //	throttling more helps but doesn't prevent it, and becomes annoying with the
            //	popup reacting noticeably late to the scroll.
            var triggered = 0, self = this, ns = this.eventNamespace;
            this.window.on('scroll' + ns + ' resize' + ns, function adaptCallback () {
                if (++triggered !== 1) {
                    return;
                }
                self._positionPopup();
                window.setTimeout(function () {
                    var t = triggered;
                    triggered = 0;
                    if (t > 1 && self.options.visible) {
                        adaptCallback();
                    }
                }, 100);
            });
 
            // Install the click-out event to close the popup in click mode
            if (this.options.activateBy === 'click') {
                this.document.on('click' + ns, function ( ev ) {
                    // Since we have references, this is kind of awkward
                    if (!$(ev.target).closest(self._$popup.add(self.element)).length) {
                        self.hide();
                    }
                });
            }
 
            this.options.visible = true;
            this._trigger('open', event/*, arbitrary data object*/); // fires referencepopupopen
        },
        hide: function ( event ) {
            if (!this.options.visible) {
                return;
            }
 
            // Detach the global tracking events
            // FIXME: 1.9 provides _on/_off which would make this much easier
            this.document.off(this.eventNamespace);
            this.window.off(this.eventNamespace);
 
            // Hide the popup itself
            if (this.options.animation) {
                // The clone is a trick to avoid the completion callback from detaching
                // the new popup. The callback fires asynchronously which means it can
                // end up detaching after a new session starts despite the .stop()
                // This also has the benefit that destroy() won't kill the animation.
                var $clone = this._$popup.clone();
                this._$popup.after($clone);
                $clone.hide(this.options.animation, this.options.animSpeed, function () {
                    $clone.remove();
                });
            }
            // NOTE: Detaching is actually faster than hiding (display:none) surprisingly
            this._$popup.detach();
            this._describeAria(false);
 
            this.options.visible = false;
            this._trigger('close', event);
        },
        toggle: function ( yesno ) {
            yesno = (yesno !== void 0 ? yesno : !this.options.visible);
            this[yesno ? 'show' : 'hide']();
        },
        _setOption: function ( key, val ) {
            switch (key) {
                case 'disabled':
                    if (val && this.options.visible) {
                        this.hide();
                    }
                    this.options.disabled = !!val;
                    this._installEvents();
                    return; // Don't add disabled attributes and classes
                case 'visible':
                    this[val ? 'show' : 'hide']();
                    return;
                case 'activateBy':
                    if (({hover: 1, click: 1})[val] === 1) {
                        this.options.activateBy = val;
                        this._installEvents();
                    }
                    return;
                case 'content':
                    this.options.content = val;
                    this._updateContent();
                    return;
                case 'context': // This is construction only.
                    return;
                case 'extraClass':
                    this._$popup[0].className = 'refpopups-popup';
                    this._$popup.addClass(val);
                    break;
                case 'extraBoxClass':
                    this._$popup.find('> .refpopups-box').prop('className', 'refpopups-box').addClass(val);
                    break;
            }
 
            return $.Widget.prototype._setOption.call(this, key, val);
        },
        _positionPopup: function () {
            var $popup = this._$popup.removeClass('refpopups-flipped'),
                $this = this.element,
                $chevOut = $popup.find('> .refpopups-chevron-out'),
                chevOutHeight = 0;
 
            // If custom CSS has hidden the chevrons then the result of this will be bogus
            if ($chevOut.is(':visible')) {
                // This calculation is trying to deal with the fact that the chevron overlaps the
                // popup's border so is not entirely outside of it. [Calculation is only valid when
                // unflipped, we're assuming that the chevron is adjacent and the same size both ways]
                chevOutHeight = $chevOut.position().top + $chevOut.outerHeight() - $popup.outerHeight();
            }
 
            // Position the popup slightly above the reference element, leaving space
            // for the chevrons underneath. This may not fit due to scrollTop.
            $popup.position({
                my: 'bottom',
                at: 'top',
                of: $this,
                collision: 'fit none',
                offset: '0 -' + chevOutHeight // FIXME: DEPRECATED in 1.9, but 1.8 doesn't support offsets
            });
            // Check for not fitting, if it doesn't fit then we need to manually flip it.
            // We need to do this the hard way because flip seems to be broken in 1.8
            // jQuery UI 1.9's using function may allow me to not have to do this manually but
            // that's not likely to be available in MW for a while.
            var spaceAtTop = $this.offset().top,
                spaceAtBottom = spaceAtTop + $this[0].offsetHeight,
                popupHeight = $popup[0].offsetHeight + chevOutHeight,
                scroll = this.window.scrollTop();
            spaceAtTop = spaceAtTop - scroll;
            spaceAtBottom = (scroll + this.window.height()) - spaceAtBottom;
            if (spaceAtTop < popupHeight && spaceAtTop < spaceAtBottom) {
                $popup.addClass('refpopups-flipped').position({// Flip
                    my: 'top',
                    at: 'bottom',
                    of: $this,
                    collision: 'fit none',
                    offset: '0 ' + chevOutHeight
                });
            }
 
            // If the chevrons are invisible then this calculation is pointless
            if (!chevOutHeight) {
                return;
            }
 
            // Now the pointless gimmickery, position the chevron so it points directly at the
            // middle of the host element
            var $chevIn = $popup.find('> .refpopups-chevron-in'),
                myLoc = $this.offset().left - $popup.offset().left + $this.outerWidth() / 2;
            $chevOut.css('left', (myLoc - $chevOut.outerWidth() / 2) + 'px');
            $chevIn.css('left', (myLoc - $chevIn.outerWidth() / 2) + 'px');
        }
    });
 
    return $.Lunarity.referencePopup;
}); // End UI Widget
 
 
/**************************************************************************************/
 
 
// Reference logic that uses the popup to actual show the things
// This is the core. (In MVC terms, this is the Controller. The popup is the View, and
// the citation nodes in the DOM are the Model)
(function ( window, $ ) {
    "use strict";
 
    // Check for loading in noCore mode or double runs and abort.
    var module = window.dev.ReferencePopups;
    if (module.unloadCore) {
        return $.noop;
    }
    module.unloadCore = $.noop;
 
    return function ( callback ) {
        $.when(module.Popup).done(function () {
            callback(module, window, $, window.mediaWiki);
        }).fail(function () {
            delete module.unloadCore;
        });
    };
}(window, jQuery))(function ( module, window, $, mw ) {
    "use strict";
 
// Local Storage wrapper. I don't know when $.storage becomes available as it isn't
// a standard ResourceLoader module. And I have an allergy to jquery.cookie
// NOTE: IE8 supports localStorage and we don't care about anything more crap than that
    var store = {
        get: function ( key ) {
            try {
                return JSON.parse(window.localStorage.getItem(key));
            } catch (e) {
                return null;
            }
        },
        set: function ( key, data ) {
            try {
                window.localStorage.setItem(key, JSON.stringify(data));
            } catch (e) {
            }
        }
    };
 
// Configuration (Get settings from storage)
// TODO: We may want to introduce Preferences for global settings.
    var userConfig = (function ( config, defaults ) {
        if (typeof (config) !== 'object' || config === null) {
            config = {};
        }
        // Must be in range or it becomes inoperable
        if (({hover: 1, click: 1})[config.react] !== 1) {
            config.react = 'hover';
        }
        config.hoverDelay = config.hoverDelay || defaults.hoverDelay || 200;
        config.animate = (config.animate === void 0 ? defaults.animate === void 0 || defaults.animate : !!config.animate);
        config.disabled = !!config.disabled && !module.lockdown;
        config.stick = (config.stick === void 0 ? defaults.stick !== void 0 && defaults.stick : !!config.stick);
        return config;
    })(store.get('RefPopupsJS'), module.defaults || {});
 
 
 
// This code creates and destroys popups as the references are interacted with.
// I do it this way as it minimises memory usage by ensuring only one popup
// will exist at any time.
// Armed is the currently active popup instance, open is the currently OPEN
// popup instance. The difference only matters when hovering, the previously
// open one will be killed and replaced by the Armed instance when armed opens.
    var armedPop = null, openPop = null;
    function cyclePopup ( newTarget ) {
        // Can't arm the open.
        if (openPop && openPop.element.is(newTarget)) {
            return;
        }
        if (armedPop) {
            // Don't do anything if we are arming already armed
            if (armedPop.element.is(newTarget)) {
                return;
            }
            armedPop.destroy();
        }
        return (armedPop = constructPopup($(newTarget)));
    }
    function cleanupPopups () {
        if (openPop) {
            openPop.destroy();
        }
        if (armedPop) {
            armedPop.destroy();
        }
        openPop = armedPop = null;
    }
    $(function ( $ ) {
        // Click processing functions
        var lastTouch;
        $('#mw-content-text').on({
            'touchEnd.RefPopups': function ( ev ) {
                // Fires when touch stops, always fired, even if finger wanders away
                lastTouch = ev.originalEvent;
            },
            'click.RefPopups': function ( ev ) {
                // On touch screens, there is no hover so we always process clicks
                if (((!lastTouch || lastTouch.defaultPrevented) && userConfig.react !== 'click') || userConfig.disabled) {
                    return;
                }
                lastTouch = null;
                var pop = cyclePopup(this);
                if (pop) {
                    // The popup missed the event so signal it manually
                    ev.preventDefault(); // Prevent link nav
                    pop.show(ev);
                }
            },
            // Central state tracking, this is where the always-only-one happens
            // This is important for hover to avoid insta-kills when brushing
            // across references
            'referencepopupopen.RefPopups': function () {
                // Only react to popups created by us, not ones by other scripts
                if (!armedPop || !armedPop.element.is(this)) {
                    return;
                }
                var oldOpen = openPop;
                openPop = armedPop;
                armedPop = null;
                if (oldOpen) { // Kill existing so only one is open
                    // IMPORTANT: The destroy MAY trigger a close event which will
                    //	invoke the handler below, that can cause TWO calls to
                    //	destroy() which is bad. We switch the value of openPop
                    //	BEFORE destroying to avoid that.
                    oldOpen.destroy();
                }
            },
            'referencepopupclose.RefPopups': function () {
                if (!openPop || !openPop.element.is(this)) {
                    return;
                }
                // If there is an armed popup then we just drop dead, otherwise
                // we shift ourself from open to armed.
                if (armedPop) {
                    openPop.destroy();
                } else {
                    armedPop = openPop;
                }
                openPop = null;
            }
        }, '.reference');
        // When the page is hidden (navigated away but held in cache) then we will
        // close any open popups so that when the user hits back, they won't still
        // be open.
        $(window).on('pagehide.RefPopups', cleanupPopups);
        // Install the hover events, if necessary.
        applyHoverEvents();
    });
// Installs hovering events if we're in hover mode, otherwise doesn't do anything
// It's called from the configuration save callback
    function applyHoverEvents () {
        var $article = $('#mw-content-text');
        $article.off('mouseenter.RefPopups');
        if (userConfig.disabled) {
            return;
        }
        if (userConfig.react !== 'hover') {
            if (openPop) {
                openPop.option('activateBy', 'click');
            }
            if (armedPop) {
                armedPop.option('activateBy', 'click');
            }
            return;
        }
        $article.on('mouseenter.RefPopups', '.reference', function () {
            var pop = cyclePopup(this);
            // Popup missed the hover, so cycle manually
            if (pop) {
                pop.triggerHover();
            }
        });
        if (openPop) {
            openPop.option('activateBy', 'hover');
        }
        if (armedPop) {
            armedPop.option('activateBy', 'hover');
        }
    }
 
// Add configuration buttons to the interface.
// Lockdown prevents it at the admin's option (NOT RECOMMENDED)
    if (!module.lockdown) {
        $(function ( $ ) {
            // We insert the configuration link below the categories, above article comments.
            // It displays as a float right, cleared block.
            $('#WikiaArticleCategories, #catlinks').first().after(
                $('<a href="#configure-refpopups" class="refpopups-configure-page" />')
                .html('[' + module.messages.coreConfigureText + ']')
                .click(onClickConfigure)
                .appendTo(this)
                );
        });
    }
 
// Debugging function to shut everything down.
// Also functions as the double run protection
    module.unloadCore = function () {
        // Detach events
        $('#mw-content-text').off('.RefPopups');
        $(window).off('.RefPopups');
        // Remove configuration buttons
        $('a[href="#configure-refpopups"]').remove();
        // Close currently open popups, if any
        cleanupPopups();
 
        // The core is unloaded now, it could be reinitialised
        delete this.unloadCore;
    };
    $(window).trigger('dev-ReferencePopups-config', module.settings = $.extend({}, userConfig));
 
// Interfacing code to load and display the configuration interface.
// The interface is stored in a separate file to reduce the size.
    var configureIsPending = false;
    function onClickConfigure ( ev ) {
        ev.preventDefault();
        var closeFunc = function () {
            configureIsPending = false;
        }, interfaceFunc = function ( confFunc ) {
            confFunc(userConfig, function ( newSettings ) {
                store.set('RefPopupsJS', newSettings);
                // We need to kill any active popups in order to apply the
                // new settings to them. This is especially important for disabled
                // since they'll be left open otherwise.
                cleanupPopups();
                applyHoverEvents();
 
                // Signal the new configuration to anyone interested in it
                $(window).trigger('dev-ReferencePopups-config', module.settings = $.extend({}, newSettings));
            }, closeFunc);
        };
 
        // If a lazy-load is running then don't open a second time, it "works" but
        // not properly due to duplicate #ids
        if (configureIsPending) {
            return;
        }
        configureIsPending = true;
 
        // Already loaded is the best.
        if (module.configure) {
            // It may or may not be a promise.
            $.when(module.configure).done(interfaceFunc).fail(closeFunc);
            return;
        }
 
        // Do lazy load. This would be a hell of a lot easier if we had an explicit
        // dependency system. Then I could just require() and wait for the promise.
        $.ajax({
            url: mw.config.get('wgLoadScript'),
            data: {
                mode: 'articles',
                only: 'scripts',
                articles: 'w:dev:ReferencePopups/code.configure.js'
            },
            dataType: 'script',
            cache: true
        }).then(function () {
            // WARN: This only works with same origin because browsers suck.
            //	Cross-origin fires done immediately before the code runs.
            // Chain promise
            $.when(module.configure).done(interfaceFunc).fail(closeFunc);
        }).fail(closeFunc);
    }
 
    function constructPopup ( $ref ) {
        var frag = $ref.find('a[href^="#cite_note"]').first();
        if (!frag.length) {
            return null; // Failsafe for crap wiki configurations
        }
        // The first step is to determine the Element for the reference node
        // NOTE: When a reference contains special HTML chars like '&', they get encoded
        //	to '.26', however the '.' marks the start of a class which is a problem...
        //   jQuery supports escaping the dot so we can work around this. There are other
        //   special characters but I think only dot will occur in cite's #ids.
        var $cite = $(frag.attr('href').replace(/\./g, '\\.'));
        if (!$cite.length) {
            // This happens when the ref tag is of the "name=X" form, but X doesn't exist
            return null;
        }
 
        // Create the content box. Sometimes people ram giant images in to the reference
        // so we wrap it in a scrollable box to avoid spill.
        var $content = $('<div style="overflow:auto">'),
            // Configuration button
            $conf = $('<a href="#" class="refpopups-configure" />')
            .prop('title', module.messages.coreConfigureHover)
            .click(onClickConfigure);
 
        // We need to get just the reference body itself, without the backreference links
        $content.append($cite.find('.reference-text').clone());
 
        // And away we go
        // NOTE: We're not using the $.fn.referencePopup wrapper as it's safer to stay
        //	in our own namespace.
        return new module.Popup({
            content: $conf.add($content),
            activateBy: userConfig.react,
            hoverDelay: userConfig.hoverDelay,
            animation: userConfig.animate && 'fold',
            stickyHover: userConfig.stick,
            contentBoxClass: 'WikiaArticle'
                // Disabled. Attach to body so on top of everything.
                // NOTE: May cause CSS malfunctions in the popup due to not being descended
                //	from .WikiaPage.V2 or #WikiaMainContent
                //context: '#WikiaMainContent, #mw-content-text'
        }, $ref[0]);
    }
 
}); // End Core
 
//