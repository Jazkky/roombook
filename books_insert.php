<?php
include("mysql/config.php");
?>
<!DOCTYPE html>
<?php
$v1 = 0;
if (isset($_POST['rmid'])) {
    $rmid = $_POST['rmid'];
    $bkin = $_POST['bkin'];
    $bkout = $_POST['bkout'];
    $bkcus = $_POST['bkcus'];
    $bktel = $_POST['bktel'];

    $sql = "SELECT COUNT(rmid) AS countid FROM books WHERE rmid = '$rmid' 
            AND bkstatus = '1' AND ((bkin >= '$bkin' AND bkin < '$bkout') 
            OR (bkin < '$bkin' AND bkout > '$bkin'))";
    $result = $conn->query($sql);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $countid = (int)$row['countid'];
    if ($countid < 1) {
        $sql = "INSERT INTO books(bkdate, rmid, bkin, bkout, bkcus, bktel, bkstatus) VALUES 
                    (NOW(), '$rmid', '$bkin', '$bkout', '$bkcus', '$bktel', '1')";
        $result = $conn->query($sql);
        $v1 = ($result == 1) ? 1 : 0;
    } else {
        $v1 = 0;
    }
} else {
    $v1 = 0;
}
?>
<html lang="en">

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
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <?php
                if ($v1 == 1) {
                    echo '<h4 class="text-success">Success!</h4>';
                } else {
                    echo '<h4 class="text-danger">Failed!</h4>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        var v1 = <?php echo $v1; ?>;
        var vurl = "books_form.php?bkin=<?php echo $bkin; ?>&bkout=<?php echo $bkout; ?>&bkcus=<?php echo $bkcus; ?>&bktel=<?php echo $bktel; ?>";
        if (v1 == 1) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Booking has been successfully made.',
                timer: 2000,
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = vurl;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Booking failed. Please try again.',
                timer: 2000,
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = vurl;
                }
            });
        }
    </script>
</body>

</html>
