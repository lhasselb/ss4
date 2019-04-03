        <script>
        /* Int gallery id */
        const GalleryId = $GalleryId;
        /* Array as a JSON encoded string ["value","value",...] */
        const ImageIds = $GalleryImageIDs.RAW;
        /* Array of JSON objects [{"key": "value",...}] */
        const GalleryData = [$GalleryData.RAW];
        </script>
        <div class="c-layout-page">
            <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
                <div class="container">
                    <div class="c-page-title c-pull-left">
                        <h2 class="c-font-uppercase c-font-sbold">$AlbumName (<span class="index">1</span><span class="total">$Total</span>)</h2>
                        <p>$AlbumDescription</p>
                    </div>
                    <%-- include BreadCrumbs --%>
                    <ul class="c-page-breadcrumbs c-theme-nav c-pull-right c-fonts-regular">
                    <li><a href="$Link" title="$MenuTitle.XML">$MenuTitle.XML</a></li>
                    <li>/</li>
                    <li>$AlbumName
                    </ul>
                </div>
            </div>
            <div class="c-content-box c-size-md">
                <div class="container">
                    <div class="galleria"></div>
                </div>
            </div>
        </div>
