<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table with Actions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Add custom styles -->
    <style>
        .table th,
        .table td {
            text-align: center;
        }
        .success-message {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            width: 300px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
                .error-message {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            width: 300px;
            padding: 10px;
            background-color: #bc0606;
            color: white;
            border-radius: 5px;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div id="flash-message" class="alert alert-success success-message" style="position: fixed; top: 10px; right: 10px; z-index: 9999; width: 300px;">
        <span id="flash-message-text"></span>
    </div>
    <div class="container mt-5">
        <!-- Page Title -->
        <h2 class="mb-4">Data Table</h2>

        <!-- Add New Button -->
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New</button>
        </div>

        <!-- Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($students as $student)
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $student->name ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-id="{{ $student->id }}" id="editButton">Update</a>
                            <a href="#" class="btn btn-danger btn-sm" data-id="{{ $student->id }}" id="deleteButton">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include Create Blade Modal Dynamically -->
    <div id="modal-container">
        
    </div>
    <div id="update-container">
        
    </div>

    <!-- Bootstrap JS & Dependencies (Popper.js, Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        // create model load
        $('[data-bs-toggle="modal"]').click(function() {
            $('#modal-container').load("{{ route('students.create') }}", function() {
                var modalElement = document.getElementById('exampleModal');
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (!modal) {
                    modal = new bootstrap.Modal(modalElement);
                }
                modal.show();
            });
        });

        // update model load
        $(document).on('click', '#editButton', function() {
            var studentId = $(this).data('id');  // Get the student ID from the button

            // Load the edit form dynamically for the student
            $('#modal-container').load("/students/" + studentId + "/edit", function() {
                // After the form is loaded, show the modal
                var modalElement = document.getElementById('exampleModal');
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (!modal) {
                    modal = new bootstrap.Modal(modalElement);
                }
                modal.show();
            });
        });

        $(document).on('submit', '#nameForm', function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data
            var studentId = $('#studentId').val();  // Get student ID from hidden field (only for updates)
            // console.log(studentId);

            var actionUrl;
            var methodType;
            
            if (studentId) {
                // alert(123);
                actionUrl = "{{ route('students.update', ':id') }}".replace(':id', studentId);
                methodType = 'PUT';
            } else {
                actionUrl = "{{ route('students.store') }}";
                methodType = 'POST';
            }

            $.ajax({
                url: actionUrl,
                type: methodType, 
                data: formData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),  // Include CSRF token
                success: function(response) {
                    if (response.success) {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                        if (modal) {
                            modal.hide();
                        }
                        window.location.reload();
                    } else {
                        alert('Error saving data');
                    }
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors; 

                    $('.invalid-feedback').remove(); 

                    $.each(errors, function(field, messages) {
                        var input = $('[name="' + field + '"]');
                        var errorMessage = '<div class="invalid-feedback">' + messages[0] + '</div>';
                        input.addClass('is-invalid');
                        input.after(errorMessage);
                    });
                }
            });
        });


        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault(); // Prevent default behavior (link click)

            var studentId = $(this).data('id');  // Get the student ID from data-id attribute

            if (confirm('Are you sure you want to delete this student?')) {
                $.ajax({
                    url: "/students/" + studentId,  // URL to delete the student
                    type: 'DELETE',  // HTTP method DELETE
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),  // CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(123);
                            localStorage.setItem('flashMessage', response.message);
                            $('#student-' + studentId).remove(); 
                            // $('#flash-message-text').text('Test message - Success!');
                            // $('#flash-message').fadeIn().delay(3000).fadeOut();
                            // setTimeout(function() {
                                window.location.reload();  // Reload the page after 3 seconds
                            // }, 3000); 
                        } else {
                            alert(556656);
                            localStorage.setItem('flashError', 'Error deleting student');
                            window.location.reload();
                            // alert('Error deleting student');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(248485398);
                        alert('Something went wrong. Try again!');
                    }
                });
            }
        });

        $(document).ready(function() {
            var flashMessage = localStorage.getItem('flashMessage');

            if (flashMessage) {

                $('#flash-message-text').text(flashMessage);
                $('#flash-message').removeClass('error-message').addClass('success-message');
                $('#flash-message').fadeIn().delay(3000).fadeOut();
                localStorage.removeItem('flashMessage');
            }

            var flashError = localStorage.getItem('flashError');
            if (flashError) {
                $('#flash-message-text').text(flashError);
                $('#flash-message').removeClass('success-message').addClass('error-message');
                $('#flash-message').fadeIn().delay(3000).fadeOut();
                localStorage.removeItem('flashError');
            }
        });
    </script>
</body>

</html>

