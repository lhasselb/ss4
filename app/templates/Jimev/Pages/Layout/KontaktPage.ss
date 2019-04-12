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
                        </div>
                        <div class="col-md-3">
                            <div class="facebook">
                                <% loop Links %>
                                <a href="$Me.URL" class="btn btn-block btn-social c-btn-square c-btn-uppercase btn-md btn-facebook"><i class="fab fa-facebook-f"></i> $Me.Title
                                </a>
                                <% end_loop %>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
