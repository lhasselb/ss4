<!DOCTYPE html>
<html lang="$ContentLocale.ATT" dir="$i18nScriptDirection.ATT">
<%--  manifest="/cache.appcache" --%>
<head>
    <% include MetaHead %>
</head>
<body oncontextmenu="return true;" class="c-layout-header-fixed c-layout-header-mobile-fixed c-layout-header-topbar c-layout-header-topbar-collapse">
	<%-- Upgrade your Browser notice --%>
	<!--[if lt IE 10]><div class="main-bn"><a href="https://www.google.com/chrome/browser/desktop/" title="<%t Page.UPGRADEBROWSER 'Upgrade your browser' %>"><%t Page.OUTDATEDBROWSER 'You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today.' %></a></div><![endif]-->
	<%-- No JS enabled notice --%>
	<noscript><div class="main-bn"><%t Page.JAVASCRIPTREQUIRED 'Please, enable javascript.' %></div></noscript>
    <% include Header %>
    <%-- Site Wide Alert Message hidden Bootstrap Modal box--%><% include ModalAlarm %>
    $Layout
    <% include Footer %>
    <div class="c-layout-go2top"><i class="icon-arrow-up"></i></div>
    <%-- Require CSS+JS from /public/resourses/[js,css]/[ClassName].[js,css] --%>$AutoRequirements($ClassName).RAW
</body>
</html>
<%-- $RenderDebugBar --%>
