<!DOCTYPE html>
<html lang="$ContentLocale.ATT" dir="$i18nScriptDirection.ATT">
<head>
    <% include MetaHead %>
</head>
<body class="c-layout-header-fixed c-layout-header-mobile-fixed c-layout-header-topbar c-layout-header-topbar-collapse">
    <% include Header %>
    $Layout
    <% include Footer %>
    <div class="c-layout-go2top"><i class="icon-arrow-up"></i></div>
    $AutoRequirements($ClassName).RAW
</body>
</html>
<%-- $RenderDebugBar --%>
