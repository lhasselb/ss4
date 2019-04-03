<% if Title %>
<div id="$Name" class="form-group<% if Message %> has-error<% end_if %><% if extraClass %> $extraClass<% end_if %>"">
	<% if Title %><label class="col-md-4 control-label" for="$ID">$Title</label><% else %><label class="control-label" for="$ID"></label><% end_if %>
	<div class="col-md-6 controls">
		$Field
	<% if Message %><span class="help-block has-{$MessageType}">$Message</span><% end_if %>
    </div>
</div>
<% else %>
        $Field
<% end_if %>
