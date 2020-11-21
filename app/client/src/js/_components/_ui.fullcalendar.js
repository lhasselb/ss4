"use strict";

import $ from 'jquery';
import moment from 'moment';
/* NEW v4 version */
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
//import timeGridPlugin from '@fullcalendar/timegrid';
//import listPlugin from '@fullcalendar/list';
import googleCalendar from '@fullcalendar/google-calendar';
import deLocale from '@fullcalendar/core/locales/de';
import '@fullcalendar/core/main.css';
import '@fullcalendar/daygrid/main.css';
//import '@fullcalendar/timegrid/main.css';
//import '@fullcalendar/list/main.css';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new Calendar(calendarEl, {
        header: {
            left: 'today, prev,next',
            center: 'title',
            right: 'dayGridMonth, dayGridWeek, dayGridDay'
        },
        height: 900,
        plugins: [ dayGridPlugin, googleCalendar ], //timeGridPlugin, listPlugin,
        locale: deLocale,
        timezone: 'local',
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

        /**
         * See https://fullcalendar.io/docs/eventClick
         * @param info properties:
         * event - The associated Event Object.
         * el - The HTML element for this event.
         * jsEvent - The native JavaScript event with low-level information such as click coordinates.
         * view - The current View Object.
         */
        eventClick: function(info) {
            const event = info.event;
            info.jsEvent.preventDefault(); // don't let the browser navigate
            if (event.url) {
                $('#modalTitle').html(event.title);
                if(moment(event.start).format('MM.DD.YYYY') != moment(event.end).format('MM.DD.YYYY')){//Multiple days
                    $('#modalDate').html('Wann: ' + moment(event.start).format('LL') + ' ' + moment(event.start).format('HH:mm') +
                        ' - ' + moment(event.end).format('LL')  + ' ' + moment(event.end).format('HH:mm'));
                }
                else if(moment(event.start).format('MM.DD.YYYY') == moment(event.end).format('MM.DD.YYYY')) {
                    $('#modalDate').html('Wann: ' + moment(event.start).format('LL') + ' ' + moment(event.start).format('HH:mm') +
                        ' - ' + moment(event.end).format('HH:mm'));
                }
                $('#modalLocation').html('Wo: ' + event.extendedProps.location);
                $('#modalBody').html(event.extendedProps.description);
                $('#eventUrl').attr('href',event.url);
                $('#fullCalModal').modal();
            }
          }
    });
    calendar.render();
});

