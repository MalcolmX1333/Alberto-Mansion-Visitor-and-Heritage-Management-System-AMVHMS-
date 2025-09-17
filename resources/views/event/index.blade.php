<!-- resources/views/event/index.blade.php -->
@extends('layouts.app')

@section('content')

    <style>
        /* Customize the calendar toolbar buttons */
        .fc .fc-toolbar-chunk .fc-button {
            background-color: #97CC70; /* Apply the green color to buttons */
            border-color: #97CC70; /* Button borders */
            color: #fff; /* Button text color */
        }
        .fc .fc-toolbar-chunk .fc-button:hover {
            background-color: #85b863; /* Darker hover effect */
            border-color: #85b863;
        }

        /* Calendar header styling */
        .fc-toolbar-title {
            color: #333; /* Title color */
            font-size: 24px;
            font-weight: bold;
        }

        /* Highlight current day */
        .fc-day-today {
            background-color: #e9f7e1; /* Light green highlight for current day */
        }

        /* Event colors */
        .fc-event {
            background-color: #97CC70 !important;
            border-color: #85b863 !important;
        }

        /* Calendar container with responsive height */
        #calendar {
            width: 100%;
            height: 600px;
            max-width: 1200px;
            margin: 20px auto; /* Add margin for better spacing */
        }

        /* Button styling to match the color scheme */
        #addEventButton {
            display: block;
            margin: 20px auto;
            background-color: #97CC70;
            border-color: #85b863;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        #addEventButton:hover {
            background-color: #85b863;
            border-color: #6ea557;
        }

        /* SweetAlert custom input field styles */
        .swal2-input {
            font-size: 16px;
            padding: 10px;
            width: calc(100% - 20px); /* Adjust width for padding */
        }

        /* Label styles */
        .swal2-field {
            text-align: left;
            margin-bottom: 10px;
        }

        .swal2-field label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .swal2-field input {
            margin-top: 5px;
        }

        /* Image preview styles */
        .swal2-image-preview {
            margin-top: 10px;
            max-width: 100%;
            height: auto;
        }

        /* Custom Swal styles */
        .custom-swal-responsive {
            width: auto !important; /* Allow for automatic width */
            max-width: 600px; /* Set a maximum width */
        }

        /* Media query for small screens */
        @media screen and (max-width: 768px) {
            .fc-toolbar {
                padding: 5px; /* Reduce padding */
                flex-direction: column; /* Stack toolbar items vertically */
            }
            .fc-toolbar-title {
                font-size: 18px; /* Reduce font size */
            }
            .fc .fc-toolbar-chunk .fc-button {
                font-size: 12px; /* Reduce button font size */
                padding: 5px 10px; /* Reduce button padding */
            }
            #calendar {
                margin-top: 50px; /* Add margin to push the calendar down */
            }
        }
    </style>

    <!-- Button to trigger SweetAlert -->
    <button id="addEventButton" class="btn">Add Event</button>

    <!-- Calendar container -->
    <div id="calendar"></div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            views: {
                dayGridMonth: { buttonText: 'Month' },
                timeGridWeek: { buttonText: 'Week' },
                timeGridDay: { buttonText: 'Day' }
            },
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
                location: event.location,
                // Use the correct image URL without repeating the base URL
                image_url: event.image_url ? `${event.image_url}` : null
            }));
            // Log the image URLs for debugging
            console.log(events.map(event => event.image_url));
            successCallback(events);
        })
        .catch(() => {
            failureCallback();
            alert('There was an error while fetching events!');
        });
},
            eventClick: function(info) {
                const event = info.event;
                Swal.fire({
                    title: 'Edit Event',
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    showDenyButton: true,
                    denyButtonText: 'Delete',
                    html: `
                        <div class="swal2-field">
                            <label for="title">Title</label>
                            <input type="text" id="title" class="swal2-input" value="${event.title}" required>
                        </div>
                        <div class="swal2-field">
                            <label for="description">Description</label>
                            <input type="text" id="description" class="swal2-input" value="${event.extendedProps.description}" required>
                        </div>
                        <div class="swal2-field">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" id="start_date" class="swal2-input" value="${moment(event.start).format('YYYY-MM-DDTHH:mm')}" required>
                        </div>
                        <div class="swal2-field">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" id="end_date" class="swal2-input" value="${event.end ? moment(event.end).format('YYYY-MM-DDTHH:mm') : ''}" required>
                        </div>
                        <div class="swal2-field">
                            <label for="location">Location</label>
                            <input type="text" id="location" class="swal2-input" value="${event.extendedProps.location}" required>
                        </div>
                        <div class="swal2-field">
                            <label for="image_url">Image URL</label>
                            <input type="file" id="image_url" class="swal2-input">
                        </div>
                        <div class="swal2-field">
                            <img id="image_preview" class="swal2-image-preview" src="${event.extendedProps.image_url ? `https://artifactsmalvar.com/storage/images/${event.extendedProps.image_url}` : ''}" alt="Image Preview">
                        </div>
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        const title = Swal.getPopup().querySelector('#title').value;
                        const description = Swal.getPopup().querySelector('#description').value;
                        const start_date = Swal.getPopup().querySelector('#start_date').value;
                        const end_date = Swal.getPopup().querySelector('#end_date').value;
                        const location = Swal.getPopup().querySelector('#location').value;
                        const image_url = Swal.getPopup().querySelector('#image_url').files[0];
                        if (!title || !description || !start_date || !end_date || !location) {
                            Swal.showValidationMessage(`All fields are required`);
                        }
                        return { title, description, start_date, end_date, location, image_url };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('title', result.value.title);
                        formData.append('description', result.value.description);
                        formData.append('start_date', result.value.start_date);
                        formData.append('end_date', result.value.end_date);
                        formData.append('location', result.value.location);
                        if (result.value.image_url) {
                            formData.append('image_url', result.value.image_url);
                        }

                        fetch(`/event/edit/${event.id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Updated!', data.message, 'success');
                                    calendar.refetchEvents();
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error', 'There was an error updating the event', 'error');
                            });
                    } else if (result.isDenied) {
                        fetch(`/event/destroy/${event.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    calendar.refetchEvents();
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error', 'There was an error deleting the event', 'error');
                            });
                    }
                });
            }
        });
        calendar.render();

        document.getElementById('addEventButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Add Event',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'custom-swal-responsive'
                },
                html: `
                    <div class="swal2-field">
                        <label for="title">Title</label>
                        <input type="text" id="title" class="swal2-input" placeholder="Enter title" required>
                    </div>
                    <div class="swal2-field">
                        <label for="description">Description</label>
                        <input type="text" id="description" class="swal2-input" placeholder="Enter description" required>
                    </div>
                    <div class="swal2-field">
                        <label for="start_date">Start Date</label>
                        <input type="datetime-local" id="start_date" class="swal2-input" required>
                    </div>
                    <div class="swal2-field">
                        <label for="end_date">End Date</label>
                        <input type="datetime-local" id="end_date" class="swal2-input" required>
                    </div>
                    <div class="swal2-field">
                        <label for="location">Location</label>
                        <input type="text" id="location" class="swal2-input" placeholder="Enter location" required>
                    </div>
                    <div class="swal2-field">
                        <label for="image_url">Image URL</label>
                        <input type="file" id="image_url" class="swal2-input" accept="image/*">
                    </div>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const title = Swal.getPopup().querySelector('#title').value;
                    const description = Swal.getPopup().querySelector('#description').value;
                    const start_date = Swal.getPopup().querySelector('#start_date').value;
                    const end_date = Swal.getPopup().querySelector('#end_date').value;
                    const location = Swal.getPopup().querySelector('#location').value;
                    const image_url = Swal.getPopup().querySelector('#image_url').files[0];
                    if (!title || !description || !start_date || !end_date || !location) {
                        Swal.showValidationMessage(`All fields are required`);
                    }
                    return { title, description, start_date, end_date, location, image_url };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('title', result.value.title);
                    formData.append('description', result.value.description);
                    formData.append('start_date', result.value.start_date);
                    formData.append('end_date', result.value.end_date);
                    formData.append('location', result.value.location);
                    if (result.value.image_url) {
                        formData.append('image_url', result.value.image_url);
                    }

                    fetch('/event/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Created!', data.message, 'success');
                                calendar.refetchEvents();
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'There was an error creating the event', 'error');
                        });
                }
            });
        });
    });
</script>





@endsection
