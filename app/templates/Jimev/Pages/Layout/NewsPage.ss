        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$Title<% if SelectedYear %> ($SelectedYear)<% end_if %></h2>
                    </div>
                    <% include BreadCrumbs %>
                </div>
            </div>
            <div class="c-content-box c-size-md">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="c-content-blog-post-1-list">
                                <% loop PaginatedLatestNews %><div class="c-content-blog-post-1">
                                    <% if NewsImage %><div class="c-media">
                                        <div class="c-content-media-2-slider" data-slider="owl" data-single-item="true" data-auto-play="4000">
                                            <div class="owl-carousel owl-theme c-theme owl-single">
                                                <div class="item">
                                                    <div class="c-content-media-2" style="background-image: url($NewsImage.FocusFill(758,230).URL); min-height: 230px;"> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><% end_if %>
                                    <div class="c-title c-font-bold c-font-uppercase">
                                    <% if Link %><a href="$Link">$NewsTitle</a><% else %>$NewsTitle<% end_if %>
                                    </div>
                                    <div class="typography c-desc">
                                        $NewsContent
                                        <% if Link %><a href="$Link" title="$Title">...weiter zum Kurs <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a><% end_if %>
                                    </div>
                                    <div class="typography c-panel">
                                        <div class="c-date"><span class="c-font-uppercase">$NiceNewsDate</span></div>
                                        <ul class="c-tags c-theme-ul-bg">
                                        <% if Sections %>
                                            <% loop Sections %>
                                            <li>$Title</li>
                                            <% end_loop %>
                                        <% else %>
                                            <li>$NewsSection</li>
                                        <% end_if %>
                                        </ul>
                                    </div>
                                </div><% end_loop %>
                                <% include NewsPagination %>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c-content-ver-nav">
                                <div class="c-content-title-1 c-theme c-title-md">
                                    <h3 class="c-font-bold c-font-uppercase">Archiv</h3>
                                    <div class="c-line-left c-theme-bg"></div>
                                </div>
                                <ul class="c-menu c-arrow-dot1 c-theme">
                                <% loop ArchiveDates %>
                                <% if LinkOrCurrent != current %>
                                    <li><a href="$Link" title="$Year">$Year ($NewsCount) </a></li>
                                <% else %>
                                    <li class="active">$MenuTitle.XML</li>
                                <% end_if %>
                                <% end_loop %>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
