<% if Alert %><% loop Alert %><div id="Modal-$ID" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="ModalLabel$ID" aria-hidden="true"
                data-startyear="$StartYear" data-startmonth="$StartMonth" data-startday="$StartDay" data-starthour="$StartHour" data-startminute="$StartMinute"
                data-endyear="$EndYear" data-endmonth="$EndMonth" data-endday="$EndDay" data-endhour="$EndHour" data-endminute="$EndMinute"  data-easein="fadeInDown">
                    <div class="modal-dialog">
                        <div class="modal-content c-square">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">$Title</h4>
                            </div>
                            <div class="modal-body typography alarm">$Meldung</div>
                            <div class="modal-footer">
                                <button type="button" class="btn c-theme-btn c-btn-border-2x c-btn-square c-btn-bold c-btn-uppercase" data-dismiss="modal">Schließen</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div><% end_loop %><% end_if %>

