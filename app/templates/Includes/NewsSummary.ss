                            <div class="c-content-media-1" data-height="height">
                                <div class="c-content-label c-font-uppercase c-font-bold c-theme-bg">$NewsSection</div>
                                <div class="newsdate c-right ">$NewsDate.Formati18N('%d.%m.%Y')</div>
                                <% if Link %>
                                <a href="$Link" class="c-title c-font-uppercase c-theme-on-hover c-font-bold">$NewsTitle
                                    <% if NewsImage %>
                                    <div class="c-media">
                                        <img class="img-responsive" src="$NewsImage.CroppedFocusedImage(300,100).URL" width="300" height="100" alt="$Title">
                                    </div>
                                    <% end_if %>
                                </a>
                                <% else %>
                                    <span class="c-title c-font-uppercase c-font-bold">$NewsTitle</span>
                                    <% if NewsImage %>
                                    <div class="c-media">
                                        <img class="img-responsive" src="$NewsImage.CroppedFocusedImage(300,100).URL" width="300" height="100" alt="$Title">
                                    </div>
                                    <% end_if %>
                                <% end_if %>
                                <div class="typography dont-break-out">$NewsContent</div>
                            </div>
