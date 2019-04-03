                            <div class="c-content-ver-nav">
                                <div class="c-content-title-1 c-theme c-title-md">
                                    <h3 class="c-font-bold c-font-uppercase">
                                    <% if Parent %>$Parent.MenuTitle<% else %>$MenuTitle<% end_if %>
                                    </h3>
                                    <div class="c-line-left c-theme-bg"></div>
                                </div>
                                <ul class="c-menu c-arrow-dot1 c-theme">
                                <% loop $Menu(2) %>
                                <% if LinkOrCurrent != current %>
                                    <li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
                                <% else %>
                                    <li class="active">$MenuTitle.XML</li>
                                <% end_if %>
                                <% end_loop %>
                                </ul>
                            </div>
