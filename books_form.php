<?php 
include("mysql/config.php"); 
$bkin = isset($_GET['bkin']) ? $_GET['bkin'] : date('Y-m-d');
$bkout = isset($_GET['bkout']) ? $_GET['bkout'] : date('Y-m-d'); 
$bkcus = isset($_GET['bkcus']) ? $_GET['bkcus'] : ""; 
$bktel = isset($_GET['bktel']) ? $_GET['bktel'] : ""; 
$q = isset($_GET['q']) ? (int)$_GET['q'] : 0; 

$day = (int)date_diff(date_create($bkin), date_create($bkout))->format('%R%a');
if ($day < 1) {
    echo "<script>
        window.location.replace('books_range.php');
    </script>";
    exit();
}

$rmid = 0; // Set initial value for $rmid
if (isset($_GET["rmid"])) {
    $rmid = $_GET['rmid'];
    $bkstatus = 0; // Set initial value for $bkstatus
    require('books_status.php');
}

if ($q > 0) {
    $kw = "AND roomtype.rmtype='$q'";
} else {
    $kw = "";
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
    <style>
        /* Custom Styles */
        .card {
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Booking Hotel System</h2>
        </div>
        <div class="card-body">
            <form action="books_form.php" method="GET">
                <div class="form-group">
                    <label>Check-In</label>
                    <input type="date" name="bkin" value="<?php echo $bkin; ?>" readonly class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Check-out</label>
                    <input type="date" name="bkout" value="<?php echo $bkout; ?>" readonly class="form-control"/>
                </div>
                <input type="hidden" name="bkcus" value="<?php echo $bkcus; ?>" required/>
                <input type="hidden" name="bktel" value="<?php echo $bktel; ?>" required/>
                <select name="q" id="q" class="form-control">
                    <option value="0">All</option>
                    <?php
                    $sql = "SELECT * FROM roomtype ORDER BY rmtype ASC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    ?>
                        <option value="<?php echo $row['rmtype']; ?>"><?php echo $row['tpname']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="btn btn-primary mt-3">Search</button>
            </form>
            <br />
            <form action="books_insert.php" method="POST">
                <div class="form-group">
                    <input type="date" name="bkin" value="<?php echo $bkin; ?>" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="date" name="bkout" value="<?php echo $bkout; ?>" class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="bkcus" value="<?php echo $bkcus; ?>" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Tel:</label>
                    <input type="text" name="bktel" value="<?php echo $bktel; ?>" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Choose Room</label>
                    <select name="rmid" size="1" required class="form-control">
                        <?php
                        $sql = "SELECT * FROM rooms LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype
                        WHERE rmid NOT IN (SELECT rmid FROM books WHERE bkstatus = '1' AND 
                        ((bkin >= '$bkin' AND bkin < '$bkout') OR ((bkin < '$bkin' AND bkout > '$bkin'))))".$kw;
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        ?>
                            <option value="<?php echo $row['rmid']; ?>">
                            <?php echo $row['rmid']; ?>&nbsp;
                            <?php echo $row['tpname']; ?>&nbsp;
                            <?php echo number_format($row['rmprice'], 0); ?>
                        </option>
                    <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<?php include("books_room.php"); ?><br />
<a href="rooms_list.php" class="btn btn-secondary">Back</a>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
<script>
    document.getElementById('q').value = "<?php echo $q; ?>";

    // SweetAlert for invalid date
    <?php if ($day < 1) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Check-Out date must be later than Check-In date.'
        });
    <?php } ?>
</script>
</body>
</html>
