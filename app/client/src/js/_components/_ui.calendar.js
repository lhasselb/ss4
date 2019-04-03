"use strict";

import $ from 'jquery';

import moment from 'moment'
import fullCalendar from 'fullcalendar';
import 'fullcalendar/dist/locale/de.js';
import 'fullcalendar/dist/gcal.js';
import 'fullcalendar/dist/fullcalendar.min.css';

const CalendarUI = (($) => {
    // Constants
    const NAME = 'CalendarUI';
    const $CALENDAR = $('#calendar');

    $CALENDAR.fullCalendar({
        /*header: {
            left: 'today prev,next',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },*/
        lang: 'de',
        //themeSystem: 'bootstrap3',
        googleCalendarApiKey: 'AIzaSyB6cztHJTF4Xn6LJUiNNhCGyItBzO6xyhs',
        eventSources: [
            {
                googleCalendarId: 'jimev.de_5rs1u41usnfck91lojqr3s6lds@group.calendar.google.com',
                textColor: '#E0C240',
                color: '#fff',
            },
            {
                googleCalendarId: 'jimev.de_m3bmcm2sr1r02s3d8nn7bfcg9o@group.calendar.google.com',
                textColor: '#0D7813',
                color: '#fff',
            },
            {
                googleCalendarId: 'jimev.de_q3c4a9i2u0anqtcbsa7lmroohg@group.calendar.google.com',
                textColor: '#A32929',
                color: '#fff',
            },
            {
                googleCalendarId: 'jimev.de_vdb4ukbe2d4d1125677ea3b3o8@group.calendar.google.com',
                textColor: '#060D5E',
                color: '#fff',
            }
        ],
        timeFormat: 'H:mm',

        eventClick:  function(event, jsEvent, view) {
            $('#modalTitle').html(event.title);
            if(event.start.format('MM.DD.YYYY') != event.end.format('MM.DD.YYYY')){//Multiple days
                $('#modalDate').html('Wann: ' + event.start.format('LL') + ' ' + event.start.format('HH:mm') +
                    ' - ' + event.end.format('LL')  + ' ' + event.end.format('HH:mm'));
            }
            else if(event.start.format('MM.DD.YYYY') == event.end.format('MM.DD.YYYY')) {
                $('#modalDate').html('Wann: ' + event.start.format('LL') + ' ' + event.start.format('HH:mm') +
                    ' - ' + event.end.format('HH:mm'));
            }
            $('#modalLocation').html('Wo: ' + event.location);
            $('#modalBody').html(event.description);
            $('#eventUrl').attr('href',event.url);
            $('#fullCalModal').modal();
            return false;
        },

        eventRender: function(event, element, view) {
            //console.log(event.source);
            if ( event.start.format('MM.DD.YYYY') !== event.end.format('MM.DD.YYYY')) {
                element.css('background-color', event.source.textColor);
                element.css('color', event.source.color);
                //console.log(event);
            }
        }

    });

    /*const sources = $CALENDAR.fullCalendar('getEventSources');
    //console.log(sources.length);
    for (var i=0,  tot=sources.length; i < tot; i++) {
        console.log(sources[i]); //"aa", "bb"
    }*/

    class CalendarUI {
        // Constructor
        // Static/Public methods
    }

    return CalendarUI;
})($);

export default CalendarUI;
