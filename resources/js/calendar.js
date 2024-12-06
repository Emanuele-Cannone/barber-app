import './app.js';
import {Calendar} from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import itLocale from '@fullcalendar/core/locales/it';


let calendarEl = document.getElementById('calendar');
let calendar = new Calendar(calendarEl, {
    locales: [itLocale],
    locale: 'it',
    plugins: isBarber ? [listPlugin] : [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    initialView: isBarber ? 'listWeek' : 'timeGridThreeDay' ,
    firstDay: 2,
    aspectRatio: 2,
    eventStartEditable: true,
    eventResizableFromStart: true,
    allDaySlot: false,
    displayEventTime: false,
    timeZone: 'UTC',
    eventClick: function(info) {

        let currentView = calendar.view.type;

        if (currentView.includes('list')) {
            info.jsEvent.preventDefault();
            return;
        }

        Livewire.dispatch('openEventModal', { event: info });

    },
    // Questo metodo viene chiamato quando ogni evento viene disegnato
    eventDidMount: function(info) {
        // Colora in base allo status
        /*
        switch(info.event.extendedProps.barberColor) {
            case 'completed':
                info.el.style.backgroundColor = 'green';
                break;
            case 'pending':
                info.el.style.backgroundColor = 'orange';
                break;
            case 'in-progress':
                info.el.style.backgroundColor = 'blue';
                break;
            default:
                info.el.style.backgroundColor = 'gray'; // Default color
        }
        */
    },
    /* events: [
        {
            title: 'BCH237',
            start: '2024-09-29T10:30:00',
            end: '2024-09-29T11:30:00',
            extendedProps: {
                department: 'BioChemistry'
            },
        },
        {
            title: 'BCH237',
            start: '2024-09-20T10:30:00',
            end: '2024-09-20T11:30:00',
            extendedProps: {
                department: 'BioChemistry'
            },
        }
    ], */
    events: appointments,
    customButtons: {
        addEvent: {
            text: 'Aggiungi Appuntamento',
            click: function () {
                document.dispatchEvent(new CustomEvent('open-modal'));
            }
        }
    },
    slotMinTime: '09:00:00',
    slotMaxTime: '20:00:00',
    headerToolbar: {
        left: appointmentPermission ? 'addEvent prev,next' : 'prev,next',
        center: 'title',
        right: isBarber ? '' : 'dayGridMonth,timeGridWeek,listWeek,timeGridThreeDay'
    },
    views: {
        timeGridThreeDay: {
            type: 'timeGrid',
            duration: { days: 3 },
            buttonText: '3 day'
        }
    }
});

calendar.render();




