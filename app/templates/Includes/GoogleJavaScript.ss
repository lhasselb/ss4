<% if $UseCompressedAssets %>
<% require javascript("mappable/javascript/google/mappablegoogle.min.js") %>
<% else %>
<% require javascript("mappable/javascript/google/FullScreenControl.js") %>
<%-- require javascript("mappable/javascript/google/markerclusterer.js") --%>
<% require javascript("mysite/javascript/google/maputil.js") %>
<% end_if %>
