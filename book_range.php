<?php 
include("mysql/config.php");
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
</head>
<body>
    <?php $nowdate = date("Y-m-d"); ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Room Booking Form</h2>
            </div>
            <div class="card-body">
                <form action="books_form.php" method="GET" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="bkin">Check-In</label>
                        <input type="date" class="form-control" id="bkin" name="bkin" value="<?php echo $nowdate; ?>" required>
                        <div class="invalid-feedback">
                            Please select a valid check-in date.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bkout">Check-Out</label>
                        <input type="date" class="form-control" id="bkout" name="bkout" value="<?php echo $nowdate; ?>" required>
                        <div class="invalid-feedback">
                            Please select a valid check-out date.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">OK</button>
                    <a href="rooms_list.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        // JavaScript for validating dates with SweetAlert
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        const bkin = document.getElementById('bkin');
                        const bkout = document.getElementById('bkout');
                        if (bkout.value <= bkin.value) {
                            event.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Date',
                                text: 'Check-Out date must be later than Check-In date.',
                            });
                            return;
                        }
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
