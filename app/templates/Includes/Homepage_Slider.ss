            <section class="c-layout-revo-slider c-layout-revo-slider-4">
                <div class="tp-banner-container c-theme">
                    <div class="tp-banner rev_slider" data-version="5.0">
                        <ul><% if Sliders %><% loop Sliders %><% if $First %>
                            <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-delay="6000">
                                <img alt="$Title" src="$SliderImage.URL" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                <div class="tp-caption customin customout" data-x="center" data-y="center" data-hoffset="" data-voffset="-50" data-speed="500" data-start="1000" data-transform_idle="o:1;" data-transform_in="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-transform_out="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="600">
                                    <h3 style="$Color;" class="c-main-title-circle c-font-48 c-font-bold c-font-center c-font-uppercase c-font-white c-block">$Headline</h3>
                                </div>
                                <div class="tp-caption lft" data-x="center" data-y="center" data-voffset="110" data-speed="900" data-start="2000" data-transform_idle="o:1;" data-transform_in="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-transform_out="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;">{$Link.setClass('c-action-btn btn btn-lg c-btn-square c-theme-btn c-btn-bold c-btn-uppercase')}</div>
                            </li><% else %>
                            <li data-transition="fade" data-slotamount="1" data-masterspeed="1000">
                                <img alt="$Title" src="resources/app/client/dist/img/blank.png" data-lazyload="$SliderImage.URL" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                <div class="tp-caption customin customout" data-x="center" data-y="center" data-hoffset="" data-voffset="-50" data-speed="500" data-start="1000" data-transform_idle="o:1;" data-transform_in="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-transform_out="rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="600">
                                    <h3 style="$Color;" class="c-main-title-circle c-font-48 c-font-bold c-font-center c-font-uppercase c-font-white c-block">$Headline</h3>
                                </div>
                                <div class="tp-caption lft" data-x="center" data-y="center" data-voffset="110" data-speed="900" data-start="2000" data-transform_idle="o:1;" data-transform_in="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;" data-transform_out="x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;">{$Link.setClass('c-action-btn btn btn-lg c-btn-square c-theme-btn c-btn-bold c-btn-uppercase')}</div>
                            </li><% end_if %><% end_loop %><% end_if %>
                        </ul>
                    </div>
                </div>
            </section>
