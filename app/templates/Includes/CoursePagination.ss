                                <% if PaginatedCourses.MoreThanOnePage %>
                                <div class="c-pagination">
                                    <ul class="c-content-pagination c-theme">
                                    <% if PaginatedCourses.NotFirstPage %>
                                        <li class="c-prev">
                                            <a href="$PaginatedCourses.PrevLink" title="Zurück">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <% end_if %>
                                    <% loop PaginatedCourses.PaginationSummary(4) %>
                                        <% if CurrentBool %>
                                        <li class="c-active">
                                            <a href="$Link" title="Zeige Seite $PageNum">$PageNum</a>
                                        </li>
                                        <% else %>
                                        <% if Link %>
                                        <li>
                                            <a href="$Link" title="Zeige Seite $PageNum">$PageNum</a>
                                        </li>
                                        <% else %>
                                        &hellip;
                                        <% end_if %>
                                        <% end_if %>
                                    <% end_loop %>
                                    <% if PaginatedCourses.NotLastPage %>
                                        <li class="c-next">
                                            <a href="$PaginatedCourses.NextLink" title="Weiter">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </li>
                                    <% end_if %>
                                    </ul>
                                </div>
                                <% end_if %>

