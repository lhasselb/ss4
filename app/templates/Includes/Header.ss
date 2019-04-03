<header class="c-layout-header c-layout-header-4 c-layout-header-default-mobile" data-minimize-offset="80">
    <div class="c-topbar c-topbar-light">
        <div class="container">
            <nav class="c-top-menu c-pull-left">
                <ul class="c-icons c-theme-ul">
                    <li><a data-original-title="Facebook-Gruppen"  data-animation="false" data-easein="bounceInLeft" href="#" rel="popover" data-placement="bottom" data-content="<% loop FacebookLinks %><% if Last %><a href='$FacebookLink.URL' class='jimevLink' $FacebookLink.TargetAttr>$FacebookLink.Title</a><% else %><a href='$FacebookLink.URL' class='jimevLink' $FacebookLink.TargetAttr>$FacebookLink.Title</a><br/><% end_if %><% end_loop %>" data-html="true" aria-describedby="popoverFacebook"><span><i class="fab fa-facebook-square fa-lg"></i></span></a></li>
                </ul>
            </nav>
            <nav class="c-top-menu c-pull-right">
                <ul class="c-links c-nolang c-theme-ul jimAlarm" style="display:none;"><% if Alert %><% loop Alert %>
                    <li><span class="c-alert-toggler" data-toggle="modal" data-target="#Modal-$ID"><a title="$Title" href="#" class="c-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="$Title"><i class="fas fa-exclamation-circle c-font-red"></i></a></span></li><% end_loop %><% end_if %>
                </ul>
                <ul class="c-links c-nolang c-theme-ul">
                    <li><a href="kontakt/" class="jimevLink" title="Kontakt">Kontakt</a></li>
                    <li class="c-divider">|</li>
                    <li><a href="links/" class="jimevLink" title="Links"><span aria-hidden="true" class="icon-link"></span> Links</a></li>
                    <li class="c-divider">|</li>
                    <li><a href="faq/" class="jimevLink" title="FAQ"><span aria-hidden="true" class="icon-question"></span> FAQ</a></li>
                    <li class="c-divider">|</li>
                    <li><a href="http://eepurl.com/dtRN8r" class="jimevLink" title="E-Mail im Newsletter eintragen"><span aria-hidden="true" class="icon-envelope-open"></span> Newsletter</a></li>
                </ul>
                <ul class="c-ext c-theme-ul"><li class="c-search hide"></li></ul>
            </nav>
        </div>
    </div>
    <div class="c-navbar">
        <div class="container">
            <div class="c-navbar-wrapper clearfix">
                <div class="c-brand c-pull-left">
                    <a href="$BaseHref" class="c-logo"><img src="{$AbsoluteBaseURL}resources/app/client/dist/img/logo/logo.svg" alt="Jim e.V." class="c-desktop-logo" style="width: 170px; height: 70px;"><img src="{$AbsoluteBaseURL}resources/app/client/dist/img/logo/logo.svg" alt="Jim e.V." class="c-desktop-logo-inverse" style="width: 150px; height: 61px;"><img src="{$AbsoluteBaseURL}resources/app/client/dist/img/logo/logo.svg" alt="Jim e.V." class="c-mobile-logo" style="width: 87px; height: 35px;"></a>
                    <button class="c-hor-nav-toggler" type="button" data-target=".c-mega-menu"><span class="c-line"></span><span class="c-line"></span><span class="c-line"></span></button>
                    <button class="c-topbar-toggler" type="button"><i class="fa fa-ellipsis-v"></i></button>
                </div>
                <nav class="c-mega-menu c-pull-right c-mega-menu-dark c-mega-menu-dark-mobile c-fonts-uppercase c-fonts-bold">
                <% include Navigation %>
                </nav>
            </div>
        </div>
    </div>
</header>
