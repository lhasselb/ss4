        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$Course.CourseTitle</h2>
                    </div>
                    <% include BreadCrumbs %>
                </div>
            </div>
            <div class="c-content-box c-size-md">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="c-content-blog-post-1-view">
                                <% with $Course %>
                                <div class="c-content-blog-post-1">
                                    <% if CourseImage %>
                                    <div class="c-media">
                                        <div class="c-content-media-2-slider">
                                            <div class="c-theme">
                                                <div class="item">
                                                    <div class="c-content-media-2" style="background-image: url($CourseImage.FocusFill(813,460).URL); min-height: 460px;"> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <% end_if %>
                                    <div class="c-title c-font-bold c-font-uppercase">$CourseTitle</div>
                                    <div class="typography c-desc">$CourseContent</div>
                                    <% if $Sections.count() > "1" %>
                                    <div class="c-panel">
                                        <ul class="c-tags typography">
                                            <% loop Sections %><li><a href="$Link">$Title</a></li><% end_loop %>
                                        </ul>
                                    </div>
                                    <% end_if %>
                                </div>
                                <% end_with %>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="c-content-ver-nav">
                                <div class="c-content-title-1 c-theme c-title-md c-margin-t-40">
                                    <h3 class="c-font-bold c-font-uppercase"><% if Parent %>$Parent.MenuTitle<% else %>$MenuTitle<% end_if %></h3>
                                    <div class="c-line-left c-theme-bg"></div>
                                </div>
                                <ul class="c-menu c-arrow-dot1 c-theme">
                                <% loop $Menu(2) %><% if LinkOrCurrent != current %><li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li><% else %><li><a class="active" href="$Link" title="$Title.XML">$MenuTitle.XML</a></li><% end_if %><% end_loop %>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
