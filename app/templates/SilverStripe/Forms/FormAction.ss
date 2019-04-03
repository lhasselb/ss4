<% if UseButtonTag %>
<div class="form-group c-margin-t-40">
    <div class="col-sm-offset-4 col-md-8">
    	<button class="btn btn-default c-theme-btn<% if extraClass %> $extraClass<% end_if %>" $AttributesHTML('class')><i class="far fa-paper-plane"></i><% if ButtonContent %>$ButtonContent<% else %>$Title<% end_if %></button>
    </div>
</div>
<% else %>
<div class="form-group c-margin-t-40">
    <div class="col-sm-offset-4 col-md-8">
	    <input class="btn btn-default c-theme-btn <% if extraClass %> $extraClass<% end_if %>" $AttributesHTML('class') />
    </div>
</div>
<% end_if %>
