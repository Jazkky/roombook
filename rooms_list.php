<!DOCTYPE html>
<?php
include("mysql/config.php");
$stdate = (isset($_GET['stdate'])) ? $_GET['stdate'] : date('Y-m-d');
$endate = (isset($_GET['endate'])) ? $_GET['endate'] : date('Y-m-d');
if (isset($_GET['rmid'])) {
    $rmid = $_GET['rmid'];
    $bkin = $_GET['bkin'];
    $bkstatus = $_GET['bkstatus'];
    // Update booking status
    $sql = "UPDATE books SET bkstatus='$bkstatus' WHERE rmid='$rmid' AND bkin='$bkin'";
    $conn->query($sql);
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <form action="rooms_list.php" method="GET">
            <div class="form-group row">
                <label for="stdate" class="col-sm-2 col-form-label">Check-In</label>
                <div class="col-sm-4">
                    <input type="date" id="stdate" name="stdate" class="form-control" value="<?php echo $stdate; ?>" required>
                </div>
                <label for="endate" class="col-sm-2 col-form-label">Check-Out</label>
                <div class="col-sm-4">
                    <input type="date" id="endate" name="endate" class="form-control" value="<?php echo $endate; ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="rooms_list.php" class="btn btn-secondary">Today</a>
                    <a href="book_range.php" class="btn btn-success">Booking Now!</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Manage</th>
                        <th>RoomNO</th>
                        <th>Type</th>
                        <th>Check_in</th>
                        <th>Check_out</th>
                        <th>Price/Night</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM books 
                            LEFT JOIN rooms ON books.rmid = rooms.rmid 
                            LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype
                            WHERE books.bkin BETWEEN '$stdate' AND '$endate' AND books.bkstatus = '1'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $day = (int)date_diff(date_create($row['bkin']), date_create($row['bkout']))->format('%a');
                    ?>
                        <tr>
                            <td>
                                <a href="#" onclick="bookstatus('<?php echo $row['rmid']; ?>','<?php echo $row['bkin']; ?>','0')" class="btn btn-danger">Cancel</a>
                                <a href="#" onclick="bookstatus('<?php echo $row['rmid']; ?>','<?php echo $row['bkin']; ?>','2')" class="btn btn-primary">Check-in</a>
                            </td>
                            <td><?php echo $row['rmid']; ?></td>
                            <td><?php echo $row['tpname']; ?></td>
                            <td><?php echo date_format(date_create($row['bkin']), "d/m/Y"); ?></td>
                            <td><?php echo date_format(date_create($row['bkout']), "d/m/Y"); ?></td>
                            <td align="right"><?php echo number_format($row['rmprice'], 0); ?></td>
                            <td align="right"><?php echo number_format($row['rmprice'] * $day, 0); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var vurl = "rooms_list.php?stdate=<?php echo $stdate; ?>&endate=<?php echo $endate; ?>";

        function bookstatus(rmid, bkin, bkstatus) {
            var url = vurl + "&rmid=" + rmid + "&bkin=" + bkin + "&bkstatus=" + bkstatus;
            window.location.replace(url);
        }
    </script>
</body>

</html>
