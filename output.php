<!--Show Database-->

<!DOCTYPE html>
<html>
<body>
<form>
    <td width="10%"><p><a href="index.php">Home</a></p></td>
</form>
</body>
</html>
<?php
require_once 'config.php';
require_once 'functions.php';
showdata($conn);
?>

<html>
<body>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<?php

function showdata($conn){
session_start();
$username = $_SESSION["username"];
// fetch data from the database
$query = "SELECT * FROM $username";
$result = $conn->query($query);
if (!$result) die($conn->error);

// find the total number of rows in the table
$rows = $result->num_rows;
?>
<h2><?php echo $username ?> Database</h2>

<table style="width:30%" ;>
    <tr>
        <th>Word</th>
        <th>Meaning</th>
    </tr>
    <?php

    // if rows count is 0 then
    if ($rows == 0) {
        ?>
        <tr>
            <td colspan="2">No data</td>
        </tr>
        <?php
    } else {
        for ($j = 1; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            ?>
            <tr>
                <td><?php echo $row['word']; ?></td>
                <td><?php echo $row['meaning']; ?></td>
            </tr>
            <?php
        }
        $result->close();
        $conn->close();
    }
}
?>
</table>
<br>

