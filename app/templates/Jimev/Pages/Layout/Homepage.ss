        <div class="c-layout-page">
            <% include Homepage_Slider %>
            <div class="c-content-box c-size-md c-bg-white" role="main">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 wow animate fadeInLeft">
                            <div class="c-content-title-1">
                                <h2 class="c-font-uppercase c-font-bold">$Title</h2>
                                <div class="c-line-left c-theme-bg"></div>
                            </div>
                            <div class="typography">$Content</div>
                        </div>
                    </div>
                </div>
            </div>
            <% if PaginatedLatestNews %>
            <div class="news c-content-box c-size-md c-bg-grey-1">
                <div class="container">
                    <div class="c-content-title-1">
                        <h3 class="c-font-uppercase c-center c-font-bold">News</h3>
                        <div class="c-line-center"></div>
                    </div>
                    <div class="row" data-auto-height="true">
                            <% loop PaginatedLatestNews %>
                                <% include Homepage_NewsSummary %>
                            <% end_loop %>
                    </div>
                    <a href="news" class="btn c-theme-btn c-btn-border-1x c-btn-square">Mehr News</a>
                </div>
            </div>
            <% end_if %>
        </div>
