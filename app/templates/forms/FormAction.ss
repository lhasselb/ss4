<% if UseButtonTag %>
<div class="form-group c-margin-t-40">
    <div class="col-sm-offset-4 col-md-8">
    	<button class="btn btn-default c-theme-btn" $AttributesHTML>
            <i class="fa fa-send-o"></i>
    		<% if ButtonContent %>$ButtonContent<% else %>$Title<% end_if %>
    	</button>
    </div>
</div>
<% else %>
<div class="form-group c-margin-t-40">
    <div class="col-sm-offset-4 col-md-8">
	<input class="btn btn-default c-theme-btn" $AttributesHTML />
    </div>
</div>
<% end_if %>
