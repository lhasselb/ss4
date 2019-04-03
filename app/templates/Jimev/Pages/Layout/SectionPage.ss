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
                    <div class="row">
                        <div class="col-md-9">
                            <div class="c-content-blog-post-1-list">
                                <% loop PaginatedCourses %>
                                <div class="c-content-blog-post-1">
                                    <% if CourseImage %><div class="c-media">
                                        <div class="c-content-media-2-slider" data-single-item="true">
                                            <div class="c-theme">
                                                <div class="item">
                                                    <div class="c-content-media-2" style="background-image: url($CourseImage.FocusFill(758,230).URL); min-height: 230px;"> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><% end_if %>
                                    <div class="c-title c-font-bold c-font-uppercase">
                                        <a href="$Link">$CourseTitle</a>
                                    </div>
                                    <div class="typography c-desc">$CourseShort</div>
                                    <div class="typography c-panel">
                                        <!--<div class="c-date">$NiceNewsDate</div>--><a href="$Link" title="$Title">...weiter zum Kurs <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a>
                                        <ul class="c-tags c-theme-ul-bg">
                                            <% loop Sections %><!--<li><a href="$Link">$Title</a></li>--><li>$Title</li><% end_loop %>
                                        </ul>
                                    </div>
                                </div>
                                <% end_loop %>
                                <% include CoursePagination %>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <% include SideBar %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
