@extends('layouts.guest-app')

@section('content')
    <style>
        /* Responsive Calendar Styles */
        #calendar {
            width: 100%;
            max-width: 100%;
        }

        /* Custom Toolbar Styling */
        .fc-toolbar-chunk button.fc-button {
            background-color: #88d200 !important;
            border-color: #88d200 !important;
        }

        .fc-toolbar-chunk button.fc-button:hover {
            background-color: #7ac400 !important;
            border-color: #7ac400 !important;
        }

        .fc-toolbar-chunk button.fc-button-active {
            background-color: #6eab00 !important;
            border-color: #6eab00 !important;
        }

        /* Mobile-first responsive adjustments */
        @media screen and (max-width: 768px) {
            .fc-toolbar.fc-header-toolbar {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .fc-toolbar-chunk {
                margin-bottom: 10px;
            }

            .fc-button-group {
                margin-bottom: 10px;
            }

            .fc-day-grid-event .fc-content {
                white-space: normal;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .fc-event {
                font-size: 0.8em;
            }
        }

        /* Ensure calendar is fully responsive */
        .fc-view-container {
            width: 100%;
            overflow-x: auto;
        }
    </style>

    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                        Upcoming Events
                    </h1>
                    <p class="text-white link-nav"><a href="{{route('landing.page')}}">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="event.html"> Events</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="upcoming-event-area section-gap" id="events">
        <div class="container">
            <div id="calendar"></div>
        </div>
    </section>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Responsive view configuration
                initialView: 'dayGridMonth',
                height: 'auto', // Automatically adjust height
                contentHeight: 'auto', // Adjust content height dynamically
                aspectRatio: 1.35, // Responsive aspect ratio

                // Responsive toolbar
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                // Responsive button text
                views: {
                    dayGridMonth: {
                        buttonText: 'Month',
                        // Limit number of events shown on mobile
                        eventLimit: true,
                        eventLimitText: function(n) {
                            return '+' + n + ' more';
                        }
                    },
                    timeGridWeek: {
                        buttonText: 'Week',
                        // Adjust week view for smaller screens
                        type: 'timeGrid',
                        duration: { weeks: 1 }
                    },
                    timeGridDay: {
                        buttonText: 'Day',
                        type: 'timeGrid',
                        duration: { days: 1 }
                    }
                },

                // Responsive breakpoints
                breakpoints: {
                    // Adjust view for different screen sizes
                    // These are example values - adjust as needed
                    mobile: {
                        breakpoint: { max: 767 },
                        settings: {
                            initialView: 'dayGridMonth',
                            eventLimit: 3
                        }
                    },
                    tablet: {
                        breakpoint: { min: 768, max: 1024 },
                        settings: {
                            initialView: 'dayGridMonth',
                            eventLimit: 5
                        }
                    }
                },

                // Event fetching
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('/event/data')
                        .then(response => response.json())
                        .then(data => {
                            const events = data.map(event => ({
                                id: event.id,
                                title: event.title,
                                start: event.start_date,
                                end: event.end_date,
                                description: event.description,
                                location: event.location
                            }));
                            successCallback(events);
                        })
                        .catch(() => {
                            failureCallback();
                            alert('There was an error while fetching events!');
                        });
                },

                // Event click handler
                eventClick: function(info) {
                    const event = info.event;
                    Swal.fire({
                        title: event.title,
                        html: `<p><strong>Description:</strong> ${event.extendedProps.description}</p>
                               <p><strong>Location:</strong> ${event.extendedProps.location}</p>`,
                        imageUrl: event.extendedProps.image_url,
                        imageAlt: 'Event Image'
                    });
                }
            });
            calendar.render();
        });
    </script>
@endsection
