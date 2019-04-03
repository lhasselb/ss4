"use strict";

import $ from 'jquery';


const FaqUI = (($) => {

    // Constants
    const NAME = 'FaqUI';
    const FAQ =     $('#grid-container').cubeportfolio({
        filters: '#filters-container',
        defaultFilter: '*',
        animationType: 'sequentially',
        gridAdjustment: 'responsive',
        displayType: 'default',
        caption: 'expand',
        mediaQueries: [{
            width: 1,
            cols: 1
        }],
        gapHorizontal: 0,
        gapVertical: 0
    });
    class FaqUI {
        // Constructor
        // Static/Public methods
    }
return FaqUI;

})($);

export default FaqUI;
