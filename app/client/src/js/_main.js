"use strict";

import $ from 'jquery';

import Events from './_events';

import FormBasics from './_components/_ui.form.basics';
import FormDatetime from './_components/_ui.form.datetime';
import FormValidate from './_components/_ui.form.validate';
import FormStorage from './_components/_ui.form.storage';


import AjaxUI from './_components/_ui.ajax';

const MainUI = (($) => {
    // Constants
    const W = window;
    const D = document;
    const $Body = $('body');

    const NAME = 'MainUI';

    // get browser locale
    //const Locale = $('html').attr('lang').substring(0, 2);

    // get browser window visibility preferences
    // Opera 12.10, Firefox >=18, Chrome >=31, IE11
    const HiddenName = 'hidden';
    const VisibilityChangeEvent = 'visibilitychange';

    // update visibility state
    D.addEventListener(VisibilityChangeEvent, () => {
        if (D.visibilityState === HiddenName) {
            //console.log('Tab: hidden');
            $Body.addClass('is-hidden');
            $Body.trigger('tabHidden');
        } else {
            //console.log('Tab: focused');
            $Body.removeClass('is-hidden');
            $Body.trigger('tabFocused');
        }
    });

    // session ping
    setInterval(() => {
        if ($Body.hasClass('is-offline')) {
            return;
        }

        $.ajax({
            sync: false,
            async: true,
            cache: false,
            url: '/Security/ping',
            global: false,
            type: 'POST',
            complete(data, datastatus) {
                if (datastatus !== 'success') {
                    W.location.reload(false);
                }
            },
        });
    }, 300000); // 5 min in ms

    W.URLDetails = {
        'base': $('base').attr('href'),
        'relative': '/',
        'hash': '',
    };

    class MainUI {
        // Static methods

        static init() {
            this.dispose();

            //console.log(`Initializing: ${NAME}`);

            // update location details
            this.updateLocation();

            // mark available offline areas
            if ('caches' in W) {
                $('a.offline').addClass('offline-available');
            }

            this.loadImages();

            // fire page printing
            if (W.URLDetails['hash'].indexOf('printpage') > -1) {
                W.print();
            }
        }

        static updateLocation(url) {
            let location = url || W.location.href;
            location = location.replace(W.URLDetails['base'], '/');
            const hash = location.indexOf('#');

            W.URLDetails.relative = location.split('#')[0];
            W.URLDetails.hash = (hash >= 0) ? location.substr(location.indexOf('#')) : '';
        }

        // load all images
        static loadImages() {
            const $imgs = $Body.find('img');
            const $imgUrls = [];
            const $imgLazyUrls = [];

            // collect image details
            $imgs.each((i, el) => {
                const $el = $(el);
                const src = $el.attr('src');
                const lazySrc = $el.data('lazy-src');

                if (src && src.length) {
                    $imgUrls.push(src);
                }
                if (lazySrc && lazySrc.length) {
                    $imgLazyUrls.push(lazySrc);
                    $el.addClass('loading');

                    AjaxUI.preload([lazySrc]).then(() => {
                        $el.attr('src', lazySrc);

                        $el.addClass('loaded');
                        $el.removeClass('loading');

                        $el.trigger('image-lazy-loaded');
                    });
                }
            });

            // load defined images
            AjaxUI.preload($imgUrls).then(() => {
                $(W).trigger('images-loaded');

                // load lazy images
                AjaxUI.preload($imgLazyUrls).then(() => {
                    //console.log('All images are loaded!');

                    $(W).trigger('images-lazy-loaded');
                });
            });
        }

        static dispose() {
            //console.log(`Destroying: ${NAME}`);
        }
    }

    $(W).on(`${Events.AJAX} ${Events.LOADED}`, () => {
        MainUI.init();
    });

    W.MainUI = MainUI;

    return MainUI;
})($);

export default MainUI;
