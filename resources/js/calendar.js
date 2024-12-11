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
    nowIndicator: true,
    plugins: isBarber ? [listPlugin] : [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    initialView: isBarber ? 'listWeek' : 'timeGridThreeDay' ,
    firstDay: 2,
    aspectRatio: 2,
    eventStartEditable: true,
    eventResizableFromStart: true,
    allDaySlot: false,
    displayEventTime: false,
    timeZone: 'Europe/Rome',
    eventClick: function(info) {

        let currentView = calendar.view.type;

        if (currentView.includes('list')) {
            info.jsEvent.preventDefault();
            return;
        }

        console.log(info.event)

        Swal.fire({
            title: info.event.title,
            html: info.event.extendedProps.description + "<br/> Tel: " + info.event.extendedProps.contact,
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Elimina",
            cancelButtonText: "Annulla"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Livewire.dispatch('delete-appointment', { id: info.event.id });
            }
        });


    },
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




