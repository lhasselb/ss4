/* global window */

window.jQuery.entwine('ss', ($) => {

    $(document).ajaxComplete(function( event, xhr, settings ) {
        //console.log(event);
        //console.log(xhr.responseText);
        //console.log(settings);
        //console.log('Ready');
        // The related NewsID: Should be empty or int
        const select = $('#Form_ItemEditForm_NewsID');
        // Title
        const newsTitle = $('input[name=News-_1_-NewsTitle]');
        const courseTitel = $('#Form_ItemEditForm_CourseTitle');
        //console.log(newsTitle);
        //console.log(courseTitel);

        if($(select).val() === '') {
            console.log('Empty NewsID: Re-use CourseTitle for News');
            // Copy CourseTitel to NewsTitel
            $(courseTitel).entwine({
                onkeyup() {
                    $(newsTitle).val($(this).val()); // 'News für ' +
                    //console.log( $(newsTitle).val() );
                }
            });
        }
    });

});
