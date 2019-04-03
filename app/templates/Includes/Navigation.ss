            <!-- 1st level start -->
            <ul class="nav navbar-nav c-theme-nav"><% loop $Menu(1) %><% if $URLSegment == 'home' %><% if $LinkOrSection == section %>
                <li class=<% if LinkOrSection = section %>"c-active"<% end_if %>><a href="#" title="$MenuTitle" class="c-link"><i class="fas fa-home fa-fw" aria-hidden="true"></i></a></li><% else %>
                <li><a href="$BaseHref" title="Startseite und News" class="c-link" data-toggle="tooltip" data-placement="left"><i class="fas fa-home fa-fw" aria-hidden="true"></i></a></li><% end_if %><% else %><% if $Children %>
                <li class="<% if LinkOrSection = section %>c-active <% end_if %>c-menu-type-classic"><a href="$Link" title="$Title.XML" class="c-link dropdown-toggle">$MenuTitle.XML<span class="c-arrow c-toggler"></span></a>
                    <!-- 2nd level start -->
                    <ul class="dropdown-menu c-menu-type-classic c-pull-left"><% loop $Children %><% if $Children %>
                    <li class="dropdown-submenu"><a href="$Link" title="$Title.XML">$MenuTitle.XML<span class="c-arrow c-toggler"></span></a></li><% else %>
                        <li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li><% end_if %><% end_loop %>
                    </ul>
                    <!-- 2nd level end -->
                </li><% else %>
                <li <% if LinkOrSection = section %>class="c-active"<% end_if %>><a href="$Link" class="c-link" title="$Title.XML">$MenuTitle.XML</a></li><% end_if %><% end_if %><% end_loop %>
            </ul>
            <!-- 1st level end -->
