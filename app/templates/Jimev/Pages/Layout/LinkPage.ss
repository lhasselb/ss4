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
                        <div class="col-md-12">
                            <div class="typography">$Content
                            <% loop Linkset %><div class="c-content-panel">
                                    <div class="c-label">$Title</div>
                                    <div class="c-body">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-4">Link</th><th class="col-md-8">Beschreibung</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <% loop Links %><tr><td class="col-md-4">$Me</td><td clas="col-md-8">$Description</td></tr><% end_loop %>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><% end_loop %>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

