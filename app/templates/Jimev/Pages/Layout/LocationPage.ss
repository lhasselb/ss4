        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$Title</h2>
                    </div>
                    <% include BreadCrumbs %>
                </div>
            </div>
            <div class="c-content-box c-size-md c-bg-white">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="typography">$Content</div>
                            <div class="c-content-feature-9 typography">
                                <ul class="c-list schedule">
                                    <li class="wow animate fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                        <div class="c-card">
                                            <i class="icon-clock c-font-blue-1-5 c-font-22 c-bg-white c-float-left"></i>
                                            <div class="c-content c-content-left">
                                                <h3 class="c-theme-font c-font-uppercase c-font-bold">Wann</h3>
                                                <p>$Schedule</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="wow animate fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                        <div class="c-card">
                                            <i class="icon-map c-font-blue-1-5 c-font-27 c-bg-white c-float-left"></i>
                                            <div class="c-content c-content-left">
                                                <h3 class="c-theme-font c-font-uppercase c-font-bold">Wo</h3>
                                                <p>$Location</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="wow animate fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                        <div class="c-card">
                                            <i class="icon-user c-font-blue-1-5 c-font-27 c-bg-white c-float-left"></i>
                                            <div class="c-content c-content-left">
                                                <h3 class="c-theme-font c-font-uppercase c-font-bold">Ansprechpartner</h3>
                                                $Contact
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="c-content-feature-9 typography">
                                $LocationDescription
                                <% if $Map.exists()%><div class="map-container">$Map.RAW()</div><% end_if %>
                            </div>
                        </div>
                        <div class="col-md-3"><% include SideBar %></div>
                    </div>
                </div>
            </div>
        </div>
