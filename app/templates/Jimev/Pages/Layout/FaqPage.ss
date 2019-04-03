        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$Title</h2>
                    </div>
                    <% include BreadCrumbs %>
                </div>
            </div>
            <div class="c-content-box c-size-md c-bg-white" role="main">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="typography">$Content</div>
                            <div class="cbp-panel">
                                <div id="filters-container" class="cbp-l-filters-underline">
                                    <div data-filter="*" class="cbp-filter-item-active cbp-filter-item">Alle</div>
                                    <% loop Sections %>
                                    <div data-filter=".{$Title.LowerCase()}" class="cbp-filter-item">$Title</div>
                                    <% end_loop %>
                                </div>
                                <div id="grid-container" class="cbp cbp-l-grid-faq typography">
                                    <% loop FAQS %>
                                    <div class="cbp-item <% loop FAQTags %>$Title.LowerCase() <% end_loop %>">
                                        <div class="cbp-caption">
                                            <div class="cbp-caption-defaultWrap">
                                                <i class="fa fa-question-circle"></i>$Question</div>
                                            <div class="cbp-caption-activeWrap">
                                                <div class="cbp-l-caption-body">$Answer</div>
                                            </div>
                                        </div>
                                    </div>
                                    <% end_loop %>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
