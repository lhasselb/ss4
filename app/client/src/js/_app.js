"use strict";

import $ from 'jquery';
import Events from './_events';

const JangoApp = (($) => {

    // Constants
    const W = window;
    const D = document;
    const $Body = $('body');

    const NAME = 'JangoApp';

    // IE mode
    let isRTL = false;
    let isIE9 = false;
    let isIE10 = false;
    let isIE = false;

    let resizeHandlers = [];

    // last popep popover
    let lastPopedPopover;

    class JangoApp {

        static init() {
            this.dispose();

            //console.log(`Initializing: ${NAME}`);
            //IMPORTANT!!!: Do not modify the core handlers call order.

            //Core handlers
            this.handleHeight();
            this.addResizeHandler(this.handleHeight); // handle auto calculating height on window resize

            this.handleInit(); // initialize core variables
            this.handleOnResize(); // set and handle responsive

            //UI Component handlers
            //handleAnimate(); // handle animate
            this.handleCheckboxRadios() // handle checkbox & radios
            this.handleAlerts(); //handle closabled alerts
            this.handleDropdowns(); // handle dropdowns
            this.handleTooltips(); // handle bootstrap tooltips
            this.handlePopovers(); // handles bootstrap popovers
            this.handleAccordions(); //handles accordions
            this.handleModals(); // handle modals

            // Hacks
            this.handleFixInputPlaceholderForIE(); //IE9 & IE10 input placeholder issue fix

        }

        static changeLogo(filename) {
            var path = '../assets/jango/img/layout/logos/' + filename + '.png';
            $('.c-brand img.c-desktop-logo').attr('src', path);
        };

        //public function to remember last opened popover that needs to be closed on click
        static setLastPopedPopover() {
            lastPopedPopover = el;
        };

        //public function to add callback a function which will be called on window resize
        static addResizeHandler(func) {
            //console.log(`addResizeHandler added ` + func.name + `()`);
            resizeHandlers.push(func);
        };

        // wrJangoer function to scroll(focus) to an element
        static scrollTo(el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;
            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }
            $('html,body').animate({
                scrollTop: pos
            }, 'slow');
        };

        // function to scroll to the top
        static scrollTop() {
            Jango.scrollTo();
        };
        //public function to initialize the fancybox plugin
        static initFancybox() {
            handleFancybox();
        };

        //public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
        static getActualVal(el) {
            el = $(el);
            if (el.val() === el.attr("placeholder")) {
                return "";
            }
            return el.val();
        };

        //public function to get a parameter by name from URL
        static getURLParameter(paramName) {
            var searchString = window.location.search.substring(1),
                i, val, params = searchString.split("&");

            for (i = 0; i < params.length; i++) {
                val = params[i].split("=");
                if (val[0] == paramName) {
                    return unescape(val[1]);
                }
            }
            return null;
        };
        // check for device touch support
        static isTouchDevice() {
            try {
                    D.createEvent("TouchEvent");
                    return true;
                } catch (e) {
                    return false;
            }
        };

        // To get the correct viewport width based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
        static getViewPort() {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        };

        // generate unique ID
        static getUniqueID(prefix) {
            return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
        };

        // check IE mode
        static isIE() {
            return isIE10;
        };

        // check IE9 mode
        static isIE9() {
            return isIE10;
        };

        // check IE10 mode
        static isIE10() {
            return isIE10;
        };

        // responsive breakpoints
        static getBreakpoint(size) {
            // bootstrap responsive breakpoints
            var sizes = {
                'xs': 480, // extra small
                'sm': 768, // small
                'md': 992, // medium
                'lg': 1200 // large
            };
            return sizes[size] ? sizes[size] : 0;
        };

        // initializes main settings
        static handleInit() {
            isIE9 = !!navigator.userAgent.match(/MSIE 9.0/);
            isIE10 = !!navigator.userAgent.match(/MSIE 10.0/);
            isIE = navigator.userAgent.indexOf("MSIE ") > -1 || navigator.userAgent.indexOf("Trident/") > -1;

            if (isIE10) {
                $('html').addClass('ie10'); // detect IE10 version
            }

            if (isIE9) {
                $('html').addClass('ie9'); // detect IE10 version
            }

            if (isIE) {
                $('html').addClass('ie'); // detect IE10 version
            }
        }

        // runs callback functions set by Jango.addResponsiveHandler().
        static runResizeHandlers() {
            //console.log(`runResizeHandlers called`);
            // reinitialize other subscribed elements
            for (var i = 0; i < resizeHandlers.length; i++) {
                var each = resizeHandlers[i];
                //console.log(`runResizeHandlers hadler = ` + each.name);
                each.call();
            }
        }

        // handle group element heights
        static handleHeight() {
            $('[data-auto-height]').each(function () {
                var parent = $(this);
                var items = $('[data-height]', parent);
                var height = 0;
                var mode = parent.attr('data-mode');
                var offset = parseInt(parent.attr('data-offset') ? parent.attr('data-offset') : 0);

                items.each(function () {
                    if ($(this).attr('data-height') == "height") {
                        $(this).css('height', '');
                    } else {
                        $(this).css('min-height', '');
                    }

                    var height_ = (mode == 'base-height' ? $(this).outerHeight() : $(this).outerHeight(true));
                    if (height_ > height) {
                        height = height_;
                    }
                });

                height = height + offset;

                items.each(function () {
                    if ($(this).attr('data-height') == "height") {
                        $(this).css('height', height);
                    } else {
                        $(this).css('min-height', height);
                    }
                });

                if (parent.attr('data-related')) {
                    $(parent.attr('data-related')).css('height', parent.height());
                }
            });
        }

        // handle the layout reinitialization on window resize
        static handleOnResize() {
            //console.log(`handleOnResize called `);
            var resize;
            $(window).resize(function () {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function () {
                    JangoApp.runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.
            });
        }

        // Handles custom checkboxes & radios using jQuery Uniform plugin
        static handleCheckboxRadios() {
            // Material design ckeckbox and radio effects
            $('body').on('click', '.c-checkbox > label, .c-radio > label', function () {
                var the = $(this);
                // find the first span which is our circle/bubble
                var el = $(this).children('span:first-child');

                // add the bubble class (we do this so it doesnt show on page load)
                el.addClass('inc');

                // clone it
                var newone = el.clone(true);

                // add the cloned version before our original
                el.before(newone);

                // remove the original so that it is ready to run on next click
                $("." + el.attr("class") + ":last", the).remove();
            });
        }

        // Handles Bootstrap Accordions.
        static handleAccordions() {
            $('body').on('shown.bs.collapse', '.accordion.scrollable', function (e) {
                Jango.scrollTo($(e.target));
            });
        }

        // Handles Bootstrap Tabs.
        static handleTabs() {
            //activate tab if tab id provided in the URL
            if (encodeURI(location.hash)) {
                var tabid = encodeURI(location.hash.substr(1));
                $('a[href="#' + tabid + '"]').parents('.tab-pane:hidden').each(function () {
                    var tabid = $(this).attr("id");
                    $('a[href="#' + tabid + '"]').click();
                });
                $('a[href="#' + tabid + '"]').click();
            }
        }

        // Handles Bootstrap Modals.
        static handleModals() {
            /* lh:start */
            // add the animation to the popover
            $('a[rel=popover]').popover().click(function(e) {
                e.preventDefault();
                const open = $(this).attr('data-easein') || 'pulse';
                //console.log(open);
                $(this).velocity(open);
                // close popover automatically
                setTimeout(function () {
                    $('.popover').popover('hide');
                }, 4000);
            });

            // add the animation to the modal
            $(".modal").each(function(index) {
                $(this).on('show.bs.modal', function(e) {
                    const open = $(this).attr('data-easein') || 'pulse';
                    //console.log(open);
                    $('.modal-dialog').velocity(open);
                });
            });
            /* lh:end */
            // fix stackable modal issue: when 2 or more modals opened, closing one of modal will remove .modal-open class.
            $('body').on('hide.bs.modal', function () {
                if ($('.modal:visible').length > 1 && $('html').hasClass('modal-open') === false) {
                    $('html').addClass('modal-open');
                } else if ($('.modal:visible').length <= 1) {
                    $('html').removeClass('modal-open');
                }
            });

            // fix page scrollbars issue
            $('body').on('show.bs.modal', '.modal', function () {
                if ($(this).hasClass("modal-scroll")) {
                    $('body').addClass("modal-open-noscroll");
                }
            });

            // fix page scrollbars issue
            $('body').on('hide.bs.modal', '.modal', function () {
                $('body').removeClass("modal-open-noscroll");
            });

            // remove ajax content and remove cache on modal closed
            $('body').on('hidden.bs.modal', '.modal:not(.modal-cached)', function () {
                $(this).removeData('bs.modal');
            });
            /* lh:start */
            let id = $('.c-alert-toggler').data("target");
            let rightnow = function(){
                let date = new Date();
                return new Date(date.getFullYear(),date.getMonth(), date.getDate() ,date.getHours(), date.getMinutes());
            };
            let startyear = $(id).data("startyear");
            let startmonth = $(id).data("startmonth");
            let startday = $(id).data("startday");
            let starthour = $(id).data("starthour");
            let startminute = $(id).data("startminute");
            let start = new Date(startyear,startmonth-1,startday,starthour,startminute);
            let endyear = $(id).data("endyear");
            let endmonth = $(id).data("endmonth");
            let endday = $(id).data("endday");
            let endhour = $(id).data("endhour");
            let endminute = $(id).data("endminute");
            let end = new Date(endyear,endmonth-1,endday,endhour,endminute);
            const valid =  function() {
                return end >= rightnow() && rightnow() >= start;
            };

            if(!valid()) {
                console.log('Alarm is ' + valid() + ' (date out of range) at ' + rightnow());
                console.log('Range start = ' + start + ' & end = ' + end);
                $('.jimAlarm').hide();
            } else $('.jimAlarm').show();

            if(W.Cookies ) {
                if (Cookies.get('alert') != id && valid()) {
                    $(id).modal('show');
                    Cookies.set('alert', id, { expires: 1 });
                }
            }
            const checkHeader = setInterval(function() {
                if(valid()) {
                    $('.jimAlarm').show();
                }
                if(valid() && Cookies.get('shown') !=1 && Cookies.get('alert') != id) {
                    $(id).modal('show');
                    Cookies.set('shown', 1, { expires: 1 });
                }
            }, 60 * 1000);
            /* lh:end */
        }

        // Handles Bootstrap Tooltips.
        static handleTooltips() {
            // global tooltips
            //$('.tooltips').tooltip();
            //$('.tooltips').tooltip({trigger: 'hover'});
            $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

        };

        // Handles Bootstrap Dropdowns
        static handleDropdowns() {
            /*
              Hold dropdown on click
            */
            $('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
                e.stopPropagation();
            });
        }

        static handleAlerts() {
            $('body').on('click', '[data-close="alert"]', function (e) {
                $(this).parent('.alert').hide();
                $(this).closest('.note').hide();
                e.preventDefault();
            });

            $('body').on('click', '[data-close="note"]', function (e) {
                $(this).closest('.note').hide();
                e.preventDefault();
            });

            $('body').on('click', '[data-remove="note"]', function (e) {
                $(this).closest('.note').remove();
                e.preventDefault();
            });
        }

        // Handle Hower Dropdowns
        static handleDropdownHover() {
            $('[data-hover="dropdown"]').not('.hover-initialized').each(function () {
                $(this).dropdownHover();
                $(this).addClass('hover-initialized');
            });
        }

        // Handles Bootstrap Popovers
        static handlePopovers() {
            $('.popovers').popover();
            // close last displayed popover
            $(document).on('click.bs.popover.data-api', function (e) {
                if (lastPopedPopover) {
                    lastPopedPopover.popover('hide');
                }
            });
        }

        // Fix input placeholder issue for IE9 and IE10
        static handleFixInputPlaceholderForIE() {
            //fix html5 placeholder attribute for ie9 & ie10
            if (isIE9 || isIE10) {
                // this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
                $('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function () {
                    var input = $(this);

                    if (input.val() === '' && input.attr("placeholder") !== '') {
                        input.addClass("placeholder").val(input.attr('placeholder'));
                    }

                    input.focus(function () {
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });

                    input.blur(function () {
                        if (input.val() === '' || input.val() == input.attr('placeholder')) {
                            input.val(input.attr('placeholder'));
                        }
                    });
                });
            }
        }

        static dispose() {
            //console.log(`Destroying: ${NAME}`);
        }
    }

    $(W).on(`${Events.AJAX} ${Events.LOADED}`, () => {
        JangoApp.init();
    });

    W.JangoApp = JangoApp;

    return JangoApp;

})($);

export default JangoApp;
