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
                    <div id="filters-container" class="cbp-l-filters-text">
                        <div class="cbp-l-filters-text-sort">Tags:</div>
                        <div data-filter="*" class="cbp-filter-item-active cbp-filter-item">Alle<div class="cbp-filter-counter"></div></div>/
                        <% loop FotosPageTags %><div data-filter=".$TagTitle" class="cbp-filter-item">$Title<div class="cbp-filter-counter"></div></div><% if $Last %><% else %> / <% end_if %><% end_loop %>
                        <div style="clear: both;"></div>
                        <div class="cbp-l-filters-text-sort">Jahre:</div>
                        <% loop FotosPageYears %><div data-filter=".$AlbumYear" class="cbp-filter-item">$AlbumYear<div class="cbp-filter-counter"></div></div><% if $Last %><% else %> / <% end_if %><% end_loop %>
                    </div>
                    <div id="grid-container" class="cbp cbp-l-grid-masonry-projects"><!-- cbp-l-grid-agency-->
                    <% loop Galleries %>
                        <div class="cbp-item <% loop GalleryTags %>$TagTitle <% end_loop %>$AlbumDate.Year">
                            <div class="cbp-caption">
                                <div class="cbp-caption-defaultWrap"><img src="$AlbumOrFirstImage.fill(600,600).URL" alt="$AlbumTitle"></div>
                                <div class="cbp-caption-activeWrap">
                                    <div class="c-masonry-border"></div>
                                    <div class="cbp-l-caption-alignCenter">
                                        <div class="cbp-l-caption-body">
                                            <!-- class="cbp-singlePage "-->
                                            <a href="$Link" class="cbp-l-caption-buttonLeft btn btn-lg c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase">Öffnen</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cbp-l-grid-agency-title">$AlbumName</div>
                            <div class="cbp-l-grid-agency-desc">$ImageNumber Bilder</div>
                            <div class="cbp-l-grid-agency-desc"><% loop GalleryTags %>$Title<% if Last %><% else %> / <% end_if %><% end_loop %></div>
                        </div>
                    <% end_loop %>
                    </div>
                </div>
            </div>
        </div>
