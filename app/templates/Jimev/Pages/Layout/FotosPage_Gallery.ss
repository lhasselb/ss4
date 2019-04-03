<div class="cbp-l-project-title">$AlbumTitle</div>
<div class="cbp-l-project-subtitle">$AlbumDescription</div>
<div class="galleria"></div>

<style type="text/css">
div.galleria .galleria-stage {
    background: #fff;
}
body .galleria-thumbnails {
    width:80%; margin: auto;
}
</style>
<script>
var data = $ImageJson.RAW;
// console.log(data);

Galleria.configure({
    variation: 'light',
    lightbox: false,
    swipe: true,
    maxScaleRatio: 1,
    thumbnails: 'lazy',
    responsive:true,
    show: 0,
    //width: 800,
    //height: 100%,
    height: 0.5625,
    _showPopout: false,
    _locale: {
        show_thumbnails: "Zeige Miniaturbild ",
        hide_thumbnails: "Verberge Miniaturbild ",
        play: "Diashow abspielen ",
        pause: "Diashow anhalten",
        enter_fullscreen: "Öffne Vollbild",
        exit_fullscreen: "Beende Vollbild",
        popout_image: "Bild in eigenem Fenster",
        showing_image: "Anzeige von Bild %s von %s"
    },
    dataSource: data,
});


Galleria.ready(function(options) {
    var gallery = this;
    // Show thumbs as default view
    // gallery.$('thumblink').click();
    // gallery.load(data);

    this.bind("loadfinish", function(e) {
        // the gallery scope
        // Galleria.log(this);
        // the event object
        // Galleria.log(e)
        gallery.lazyLoadChunks(10);
        gallery.detachKeyboard();
    });

    this.bind("fullscreen_enter", function(e) {
        //console.log('enter');
        /*gallery.attachKeyboard({
            left: gallery.prev,
            right: gallery.next,
        });*/
    });

    this.bind("fullscreen_exit", function(e) {
        //console.log('exit');
        //gallery.detachKeyboard();
    });

    $('.galleria-image-nav-right,.galleria-image-nav-left').click(function() {
        //Galleria.log(this);
    });

    $('.cbp-popup-next,.cbp-popup-prev').click(function() {
        gallery.destroy(data);
        gallery.load(data);
        gallery.setOptions({show:0})
        window.setTimeout(function(){
            gallery.lazyLoadChunks(5);
        },10);
    });
});

if (Galleria) { console.log('Galleria works') }

Galleria.run('.galleria');

</script>


