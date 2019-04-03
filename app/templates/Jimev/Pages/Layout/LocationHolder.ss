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
                            <div class="c-content-panel c-margin-t-15">
                                <div class="c-label">$Title</div>
                                <div class="c-body">
                                    <div class="row typography">
                                        <div class="col-md-12">
                                            <table class="table table-striped">
                                                <thead><tr><th>Zeit</th><th>Ort</th><th>Bemerkung</th></tr></thead>
                                                <tbody>
                                                <% loop $Children %>
                                                    <tr><td>$Schedule</td><td>$Location</td><td>$Remark</td></tr>
                                                <% end_loop %>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"><% include SideBar %></div>
                    </div>
                </div>
            </div>
        </div>
