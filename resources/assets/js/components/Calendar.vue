<template>
    <div class="container" id="">
        <div id="calendar"></div>
        <!-- $('#EventModal').modal('show') -->
        <div class="modal fade" id="EventModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{{event.title}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Titel</label>
                            <input type="text" class="form-control" @keyup.enter="updateEvent" v-model="event.title">
                        </div>
                        <div class="form-group">
                            <label for="">Start</label>
                            <input type="text" class="form-control" @keyup.enter="updateEvent" v-model="event.start">
                        </div>
                        <div class="form-group">
                            <label for="">Endzeit</label>
                            <input type="text" class="form-control" @keyup.enter="updateEvent" v-model="event.end">
                        </div>
                        <div class="form-group">
                            <label for="">All-Day</label>
                            <input type="checkbox" @keyup.enter="updateEvent" v-model="event.allDay">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteEvent">
                            Delete
                        </button>
                        <button type="button" data-dismiss="modal" @click="updateEvent" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>

<script>
    require('fullcalendar');
    require('fullcalendar/dist/locale/de.js');

    var calendar;

    export default {
        data() {
            return {
                event: {
                    start: null,
                    end: null,
                    title: null,
                    id: null,
                    allDay: false
                }
            }
        },
        methods: {
            deleteEvent() {
                axios.delete('/event/delete/' + this.event.id)
                    .then(res => {
                        console.log(res);
                        calendar.fullCalendar('refetchEvents');
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            updateEvent() {
                axios.put('/event/' + this.event.id, this.event)
                    .then(res => {
                        console.log(res);
                        $('#EventModal').modal('hide');
                        calendar.fullCalendar('refetchEvents');
                    })
                    .catch(err => {
                        console.log(err);
                    })
            }
        },
        mounted() {
            console.log('component ready..');

            // CONFIG
            var height = 700;

            // Needed to access Vue properties in JavaScript / jQuery context
            var self = this;

            $(document).ready(function () {
                calendar = $('#calendar');
                calendar.fullCalendar({
                    'header': {
                        left: 'title',
                        center: 'prev,next',
                        right: 'agendaDay agendaWeek basicDay month today'
                    },
                    'minTime': '09:00:00',
                    'maxTime': '21:00:00',
                    'defaultView': 'agendaWeek',
                    'height': height,
                    'events': {
                        'url': 'http://localhost:8000/events',
                        'success': function () {
                            console.log('Alles geladen');
                        }
                    },
                    'dragScroll': true,
                    'themeSystem': 'bootstrap3',
                    'editable': true,
                    'eventDrop': function (calEvent, jsEvent, ui, view) {
                        console.log(calEvent);

                        var event = {
                            id: calEvent.id,
                            start_time: calEvent.start.format('Y-M-D H:mm:ss'),
                            end_time: calEvent.end.format('Y-M-D H:mm:ss'),
                        };

                        axios.put('/event', event)
                            .then(res => {
                                console.log(res);
                                calendar.fullCalendar('refetchEvents');
                            })
                            .catch(err => {
                                console.log(err);
                            });


                    },
                    'eventResize': function (calEvent, jsEvent, ui, view) {
                        console.log(calEvent);

                        var event = {
                            id: calEvent.id,
                            start_time: calEvent.start.format('Y-M-D H:mm:ss'),
                            end_time: calEvent.end.format('Y-M-D H:mm:ss'),
                        };

                        console.log(calEvent.start.format('Y-M-D H:mm:ss'));
                        console.log(calEvent.end.format('Y-M-D H:mm:ss'));

                        axios.put('/event', event)
                            .then(res => {
                                console.log(res);
                                calendar.fullCalendar('refetchEvents');
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    },
                    'selectable': true,
                    'select': function (start, end, jsEvent, view) {
                        var title = window.prompt('Event-Title');

                        var event = {
                            start: start.format('Y-M-D H:mm:ss'),
                            end: end.format('Y-M-D H:mm:ss'),
                            title: title,
                            allDay: false
                        };

                        if (view.name === "month") {
                            event.allDay = true;
                            event.start = start.format('Y-M-D');
                            event.end = end.format('Y-M-D');
                        }

                        console.log(event);


                        axios.post('/event', event)
                            .then(res => {
                                console.log(res);
                                calendar.fullCalendar('refetchEvents');
                            })
                            .catch(err => {
                                console.log(err);
                            });

                    },
                    'eventClick': function (calEvent, jsEvent, view) {
                        console.log(calEvent);

                        self.event.start = (calEvent.start != "null") ? calEvent.start._i : null;
                        self.event.end = (calEvent.end != null) ? calEvent.end._i : null;
                        self.event.title = calEvent.title;
                        self.event.id = calEvent.id;
                        self.event.allDay = calEvent.allDay;

                        $('#EventModal').modal('show');

                    }

                });
            });

        },


    }
</script>

<style lang="sass">
    .container
        margin-top: 40px
</style>