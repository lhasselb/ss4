"use strict";

import $ from 'jquery';
// Add this to Gallery
import '../../scss/_plugins/_galleria.scss';

// Galleria has been addded using CDN see requirements.yml
//import Galleria from 'galleria'
//import '../../thirdparty/galleria/themes/twelve/galleria.twelve.css';
//import '../../thirdparty/galleria/themes/twelve/galleria.twelve.min.js';

const GalleriaUI = (($) => {
    // Constants
    const G = window;
    const NAME = 'GalleriaUI';

    /*
     * Galleria - https://galleria.io/ configuration
     * Setting a relative height (16/9 ratio = 0.5625)
     * Setting a relative height (4/3 ratio = 0.75)
     * imageCrop: true,
     * thumbCrop: 'height',
     * transition: 'fade',
     * easing: 'galleriaOut',
     * initialTransition: 'fadeslide',
     * show: 0,
     * _hideDock: Galleria.TOUCH ? false : true,
     * //autoplay: 5000
     */
    const galleria = Galleria.configure({
        //debug: true,
        variation: 'light',
        lightbox: true,
        swipe: 'disabled', //true
        // if you don't want Galleria to upscale any images, set this to 1.
        maxScaleRatio: 1.25,
        responsive: true,
        thumbnails: 'lazy',
        show: 0,
        showCounter: false,
        initialTransition: 'fade',
        transition: 'fadeslide',//default 'fade'
        autoplay: 1000,
        //play: 1000, //default 5000
        //width: 400,
        //height: 300,
        height: 0.5625,
        // Theme specific
        // Toggles the fullscreen button
        _showFullscreen: true,
        // Toggles the lightbox button
        _showPopout: true,
        // Toggles the progress bar when playing a slideshow
        _showProgress: true,
        // Toggles tooltip
        _showTooltip: true,
        // Localized strings, modify these if you want tooltips in your language
        _locale: {
            show_thumbnails: "Zeige Miniaturbild ",
            hide_thumbnails: "Verberge Miniaturbild ",
            play: "Diashow abspielen ",
            pause: "Diashow anhalten",
            enter_fullscreen: "Öffne Vollbild",
            exit_fullscreen: "Beende Vollbild",
            popout_image: "Bild in eigenem Fenster",
            showing_image: "Anzeige von Bild %s von %s"
        }
    });

    /**
     * Start galleria if all required JS params are det from within FotosPage_album.ss
     */
    if (typeof GalleryId !== 'undefined' || typeof GalleryData !== 'undefined' || typeof ImageIds !== 'undefined')
    {
        Galleria.run('.galleria', { dataSource: GalleryData});
    }

    function loadNextImage(imageId) {
        console.log(imageId);
        $('.galleria-image-nav-right').hide();
        $.ajax({
            method: 'POST',
            url: '/fotos/album/' + GalleryId + '/',
            /* ImageID is the post parameter name ! */
            data: {ImageID: imageId},
            dataType: 'json',
            success: function (data) {
                /* Push takes an object {"image": "...",} */
                Galleria.get(0).push(data.Image);
            },
            error: function(xhr, status, error) {
                console.log(status);
                console.log(error);
                console.log(xhr.responseText);
            }
        });
        $('.galleria-image-nav-right').show();
    }

    Galleria.ready(function(options) {

        var gallery = this;
        /**
         * Bind loadstart event fired when an image gets loaded
         */
        this.bind("loadstart", function(e) {
            this.lazyLoad(e.index);
            const next = parseInt(e.index) + 1;
            if ( !e.cached ) {
                /* Load the next image */
                if (next < ImageIds.length ) {
                    loadNextImage(ImageIds[next]);
                }
            } else {}
            // Add the index to the page
            $('span.index').html(next);
        });

        /**
         * Use loadfinish event to stop autoplay after one round
         */
        this.bind("loadfinish", function(e) {
            if ( e.index == ImageIds.length -1 ) {
                gallery.pause();
            }
        });

        /**
         * Use pause event
         */
        this.bind("pause", function(e) {
            console.log("PAUSE" , e.index);
        });
    });

    return GalleriaUI;
})($);

export default GalleriaUI;
