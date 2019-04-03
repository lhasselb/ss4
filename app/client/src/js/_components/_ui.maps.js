"use strict";

import $ from 'jquery';

const MapsUI = (($) => {

    // Constants
    const NAME = 'MapsUI';

    const MAP =  $('.map-container').click(function() {
        $(this).find('iframe').addClass('clicked');
    }).mouseleave(function() {
        $(this).find('iframe').removeClass('clicked');
    });

    class MapsUI {
        // Constructor
        // Static/Public methods
    }


    return MapsUI;

})($);

export default MapsUI;
