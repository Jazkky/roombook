<?php
include("mysql/config.php");

function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, trim($input));
}

$bkin = isset($_GET['bkin']) ? sanitizeInput($conn, $_GET['bkin']) : ''; 
$bktel = isset($_GET['bktel']) ? sanitizeInput($conn, $_GET['bktel']) : '';
$bkcust = isset($_GET['bkcust']) ? sanitizeInput($conn, $_GET['bkcust']) : '';
$bkout = isset($_GET['bkout']) ? sanitizeInput($conn, $_GET['bkout']) : '';

if (isset($_GET['rmid'])) {
    $rmid = sanitizeInput($conn, $_GET['rmid']);
    $cancel_sql = "UPDATE books SET bkstatus = '0' WHERE rmid = '$rmid' AND bkin = '$bkin' AND bktel = '$bktel'";
    if ($conn->query($cancel_sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Cancelled',
                    text: 'Your booking has been cancelled successfully.'
                }).then((result) => {
                    window.location.href = 'rooms_list.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error cancelling booking: ".$conn->error."'
                });
              </script>";
    }
}

$sql = "SELECT * FROM books 
        LEFT JOIN rooms ON books.rmid = rooms.rmid 
        LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype
        WHERE books.bkin = '$bkin' AND books.bktel = '$bktel' AND books.bkstatus = '1'";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Hotel System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
</head>
<body>
<div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <h2 class="mb-0">Booking Details</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cancel</th>
                        <th>RoomNO</th>
                        <th>Type</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <a href="?bkin=<?php echo urlencode($bkin); ?>&bkout=<?php echo urlencode($bkout); ?>&bkcust=<?php echo urlencode($bkcust); ?>&bktel=<?php echo urlencode($bktel); ?>&rmid=<?php echo urlencode($row['rmid']); ?>" class="btn btn-danger">Cancel</a>
                        </td>
                        <td><?php echo htmlspecialchars($row['rmid']); ?></td>
                        <td><?php echo htmlspecialchars($row['tpname']); ?></td>
                        <td><?php echo date_format(date_create($row['bkin']), "d/m/Y"); ?></td>
                        <td><?php echo date_format(date_create($row['bkout']), "d/m/Y"); ?></td>
                        <td align="right"><?php echo number_format($row['rmprice']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function(){
        $('.btn-danger').click(function(e){
            e.preventDefault();
            var cancelUrl = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Once cancelled, you will not be able to recover this booking!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = cancelUrl;
                }
            });
        });
    });
</script>
</body>
</html>
