<div id="$HolderID" class="form-group field<% if $extraClass %> $extraClass<% end_if %>">
    <% if Title %><label class="col-md-4 control-label" for="$ID">$Title</label><% else %><label class="control-label" for="$ID"></label><% end_if %>
    <div class="col-md-6 controls">$Field</div>
	<% if $RightTitle %><label class="right" for="$ID">$RightTitle</label><% end_if %>
	<% if $Message %><span class="message help-block has-$MessageType">$Message</span><% end_if %>
	<% if $Description %><span class="description">$Description</span><% end_if %>
</div>
