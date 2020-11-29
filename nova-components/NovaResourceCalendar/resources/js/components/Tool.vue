<template>
    <div>
        <heading class="mb-6">Calendar</heading>

        <card class="p-6">
            <div class="max-w-5xl">
                <FullCalendar ref="fullCalendar" :options="calendarOptions" />
            </div>
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
    metaInfo() {
        return {
            title: 'Calendar',
        }
    },
    data() {
        return {
            modalOpen: false,
            dayView: false,
            current: false,
            calendarOptions: {
                events: '/nova-vendor/nova-resource-calendar/events',
                plugins: [ timeGridPlugin, dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                editable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,

                // Toolbar
                customButtons: {
                    createEvent: {
                        text: 'Create',
                        icon: 'plus-square',
                        click: this.createEventHandler,
                    }
                },
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'createEvent dayGridMonth,timeGridWeek,timeGridDay today prev,next',
                },

                // Event Handlers
                dateClick: this.dateClickHandler,
                eventClick: this.eventClickHandler,
                eventChange: this.eventChangeHandler,
            }
        }
    },
    methods: {
        createEventHandler() {
            this.$router.push({ name: 'create', params: { resourceName: 'scheduled-notifications' }})
        },
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
            axios
                .put(`/nova-vendor/nova-resource-calendar/events/${payload.event.id}`, {
                    'scheduled_at': payload.event.startStr,
                })
                .then(() => this.$toasted.show('Schedule updated!', { type: 'success' }))
                .catch(() => this.$toasted.show('Error updating Schedule!', { type: 'error' }))
        },
        dateClickHandler(date) {
            let view = ['dayGridMonth','timeGridWeek','timeGridDay'];
            let type = this.$refs.fullCalendar.getApi().view.type;
            let next = view[(view.indexOf(type) + 1) % view.length];

            this.$refs.fullCalendar.getApi().changeView(next, date.dateStr);
        }
    },
}
</script>
