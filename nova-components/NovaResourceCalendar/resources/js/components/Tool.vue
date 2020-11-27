<template>
    <div>
        <heading class="mb-6">Calendar</heading>

        <card class="p-6">
            <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </card>

        <portal v-if="modalOpen" to="modals" transition="fade-transition">
            <EventModal @close="eventClickHandler" title="Scheduled Notification" :url="current.extendedProps.edit">
                <div class="mb-4">
                    <label class="block mb-2 font-bold">
                        Type:
                    </label>

                    <p>{{ current.extendedProps.type }}</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-bold">
                        Scheduled At:
                    </label>

                    <p>{{ current.start }}</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-bold">
                        Message:
                    </label>

                    <p>{{ current.extendedProps.message }}</p>
                </div>

                <div v-if="current.extendedProps.event_title" class="border-t-1 border-30">
                    <div class="mb-4">
                        <label class="block mb-2 font-bold">
                            Event Title:
                        </label>

                        <p>{{ current.extendedProps.event_title }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">
                            Event Start Time:
                        </label>

                        <p>{{ current.extendedProps.event_start_time }}</p>
                    </div>
                </div>
            </EventModal>
        </portal>
    </div>
</template>

<script>
import EventModal from './EventModal'
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction'

export default {
    components: {
        FullCalendar,
        EventModal,
    },
    data() {
        return {
            modalOpen: false,
            weekView: false,
            current: false,
            calendarOptions: {
                events: '/nova-vendor/nova-resource-calendar/events',
                plugins: [ timeGridPlugin, dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,

                // Event Handlers
                eventChange: this.eventChangeHandler,
                dateClick: this.dateClickHandler,
            }
        }
    },
    methods: {
        eventClickHandler(payload) {
            if (this.modalOpen) {
                this.modalOpen = false;
                this.current = false;
                return;
            }

            this.current = payload.event;
            this.modalOpen = true;
        },
        eventChangeHandler(payload) {
            console.log(`${payload.event.id} update time to ${payload.event.startStr}`);
            axios
                .put(`/nova-vendor/nova-resource-calendar/events/${payload.event.id}`, {
                    'scheduled_at': payload.event.startStr,
                })
                .then(console.log)
                .catch(console.log);
        },
        dateClickHandler(date) {
            this.weekView = !this.weekView;
            this.$refs.fullCalendar.getApi().changeView(this.weekView ? 'timeGridWeek' : 'dayGridMonth', date);
        }
    },
}
</script>
