"use strict";

import $ from 'jquery';
import WOW from 'wowjs'


const WowUI = (($) => {

    // Constants
    const NAME = 'WowUI';
    const G = document;
    const wow =  new WOW.WOW({
        animateClass: 'animated',
        offset:100,
        live: false, //true
        mobile: false,
    });

    wow.init();

    setTimeout(
        function() {
            $('.wow').css('opacity', '1');
            }, 100
    );


    class WowUI {
        // Constructor
        // Static/Public methods
    }


    return WowUI;

})($);

export default WowUI;
