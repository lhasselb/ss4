        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$Title</h2>
                    </div>
                    <% include BreadCrumbs %>
                </div>
            </div>
            <div class="c-content-box c-size-md">
                <div class="container">
                    <div class="typography">$Content</div>

                    <div id="filters-container" class="cbp-l-filters-button">
                        <div data-filter="*" class="cbp-filter-item cbp-filter-item-active">Alle<div class="cbp-filter-counter"></div></div>
                        <% loop ProjectPageTags %>
                        <div data-filter=".$TagTitle" class="cbp-filter-item">$Title<div class="cbp-filter-counter"></div></div><% end_loop %>
                        <div style="clear: both;"></div>
                        <% loop ProjectPageYears %>
                        <div data-filter=".$ProjectYear" class="cbp-filter-item">$ProjectYear<div class="cbp-filter-counter"></div>
                        </div><% end_loop %>
                    </div>

                    <div id="grid-container" class="cbp">
                    <% loop Projects %>
                        <div class="cbp-item <% loop ProjectTags %>$TagTitle <% end_loop %>$ProjectDate.Year">
                            <div class="cbp-caption">
                                <div class="cbp-caption-defaultWrap">
                                    <img src="$ProjectImage.Fill(600,600).URL" alt="$ProjectTitle"> </div>
                                <div class="cbp-caption-activeWrap">
                                    <div class="cbp-l-caption-alignCenter">
                                        <div class="cbp-l-caption-body">
                                            <a href="$Link" class="cbp-l-caption-buttonLeft btn btn-sm c-btn-square c-btn-border-1x c-btn-white  c-btn-uppercase">Mehr</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cbp-l-grid-projects-title">$ProjectTitle</div>
                            <div class="cbp-l-grid-projects-desc">$ProjectDescription</div>
                        </div>
                    <% end_loop %>
                    </div>
                </div>
            </div>
        </div>
