<!-- Index page 
---- Run at the first time or when users don't want to login
---- If user loggedin, redirect to the admin.php page
-->


<?php
require_once 'config.php';
require_once 'functions.php';

session_start();
// Redirect to Admin page if user already log in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: admin.php");
    exit;
}

//Check the default database for the first time
$username = 'data';
if(!(table_exists($conn,$username))){      
	create_table($conn,$username); 
	$f = fopen("default_EV.txt", "r");
	insert_data($conn,$username,$f);
}

//Translate function
function translate($conn)
{
    $username = 'data'; 
    //echo "$username";
    if (isset($_GET['search'])) {
        //echo $username;
        $word = mysqli_real_escape_string($conn, $_GET['search']);
        //echo "$word";
        // fetch data from the database
        $query = "SELECT * FROM $username WHERE word = '$word'";
        $result = $conn->query($query);
        if (!$result) die($conn->error);

        // find the total number of rows in the table
        $rows = $result->num_rows;
        // if rows count is 0 then
        if ($rows == 0){
            echo "Word not Found!!!";
        } else {
            //print out the result
            for ($j = 0; $j < $rows; ++$j) {
                $result->data_seek($j);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                echo $row['meaning'];
            }
        }
        $result->close();
    }
    $conn->close();
}
?>

<html>
<body>

<!--------INPUT FORM --------->
<div>
    <header><h1>Lame Translate</h1></header>
    <br>
    <header><h4>Default Dictionary: English - Vietnamese</h4></header>
    <form method="GET" action="" enctype="multipart/form-data">
    <textarea type = "text" style = "font-size: 14px" placeholder = "Enter text" name = "search" rows = "8"cols="40"><?php if(isset($_GET['search'])) { 
         echo htmlentities ($_GET['search']); }?></textarea>
    </textarea>
    <button type="submit" value = "translate"> Translate!</button>
    <textarea type = "text" style = "font-size: 14px" placeholder = "Translation" name = "output" rows = "8"cols="40"><?php translate($conn); ?></textarea>
    </form>

</div>
</body>
</html>
<html>
<body>
<!-------Link to Login page----------->
<p>Already registered?<a href="login.php">Login</a></p>
<p>Don't have an account?<a href="signup.php">Sign up</a></p></td>
</body>
</html>


<!--SHOW DATA BUTTON-->
<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<body>-->
<!--<form>-->
<!--    <input type="button" value="Show Data" onclick="window.location.href='output.php'" />-->
<!--</form>-->
<!--</body>-->
<!--</html>-->
