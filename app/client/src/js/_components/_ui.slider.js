"use strict";

import $ from 'jquery';

// This will trigger to copy the required slider fonts (dist/fonts/revicon.*)
import "../../scss/_components/_ui.slider.scss";

/* Import of revolution slider and tools failed
 *
 * import '../../thirdparty/revolution/css/settings.css';
 * import '../../thirdparty/revolution/css/layers.css';
 * import '../../thirdparty/revolution/css/navigation.css';
 * import '../../thirdparty/revolution/js/source/jquery.themepunch.tools.min.js';
 * import '../../thirdparty/revolution/js/source/jquery.themepunch.revolution.js';
 *
 * IMPORTANT: The assets need to be copied to dist/js and dist/css (webpack config).
 *            And referenced within DeferedRequirements, see _config/requirements.yml
 *            The css classes have been moved to app.scss to be included before the revolution slider custom styles
 *              - '/thirdparty/revolution/settings.css'
 *              - '/thirdparty/revolution/layers.css'
 *              - '/thirdparty/revolution/navigation.css'
 */
// NOT required any more: import TweenLite from 'gsap/TweenLite';

const SliderUI = (($) => {
    // Constants
    const SLIDER = $('.c-layout-revo-slider .tp-banner');
    const cont = $('.c-layout-revo-slider .tp-banner-container');
    const height = (JangoApp.getViewPort().width < JangoApp.getBreakpoint('md') ? 400 : 620);
    //console.log(`Jango getViewPort width = ${JangoApp.getViewPort().width}`);

    const api = SLIDER.show().revolution({
        sliderType:'standard',
        sliderLayout:'fullwidth',
        delay: 15000,
        autoHeight: 'off',
        gridheight:500,
        navigation: {
            keyboardNavigation:'off',
            keyboard_direction: 'horizontal',
            mouseScrollNavigation:'off',
            onHoverStop:'on',
            arrows: {
                style:'circle',
                enable:true,
                hide_onmobile:false,
                hide_onleave:false,
                tmp:'',
                left: {
                    h_align:'left',
                    v_align:'center',
                    h_offset:30,
                    v_offset:0
                },
                right: {
                    h_align:'right',
                    v_align:'center',
                    h_offset:30,
                    v_offset:0
                }
            },
            touch:{
                touchenabled:'on',
                swipe_threshold: 75,
                swipe_min_touches: 1,
                swipe_direction: 'horizontal',
                drag_block_vertical: false
            },
        },
        viewPort: {
            enable:true,
            outof:'pause',
            visible_area:'80%'
        },
        shadow: 0,
        spinner: 'spinner2',
        disableProgressBar:'on',
        fullScreenOffsetContainer: '.tp-banner-container',
        hideThumbsOnMobile: 'on',
        hideNavDelayOnMobile: 1500,
        hideBulletsOnMobile: 'on',
        hideArrowsOnMobile: 'on',
        hideThumbsUnderResolution: 0,
    });

    class SilderUI {
        // Constructor
        // Public methods
    }

    return SliderUI;
})($);

export default SliderUI;
