<!--Admin page
----direct to that page if users already loggedin
----User can upload their own database
----Or use the default data.
-->

<?php
require_once 'config.php';
require_once 'functions.php';

// Initialize the session
session_start();
$username = $_SESSION["username"];
// Check if the user is already logged in
// If not, go to index page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

//Prevent session hijacking OR user click logout
elseif(($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))||isset($_POST["logout"]))
{
    logout();
    header("Location: index.php");
    exit;
}
else{

/*INSERT DATA*/
function insert($conn)
{
    $username = $_SESSION["username"];

    if (isset($_POST['upload'])) {

        //  Create table to store data
        create_table($conn, $username);

        //load file upload
        $f = fopen($_FILES['content']['tmp_name'], "r");
        insert_data($conn,$username,$f);    //insert data
        echo "Upload completed!!!";
        $f->close();
    }
}

/*Translate function */
function translate($conn)
{
    //Check if the users uploaded their own database
    $username = $_SESSION["username"];
    if(table_exists($conn,$username)){
        $name = $username;
    }
    else 
        //if not use default
        $name = 'data';
    
    if (isset($_GET['search'])) {
        //echo $name;
        $word = mysqli_real_escape_string($conn, $_GET['search']);
        //echo "$word";
        //fetch data from the database
        $query = "SELECT * FROM $name WHERE word = '$word'";
        $result = $conn->query($query);
        if (!$result) die($conn->error);

        // find the total number of rows in the table
        $rows = $result->num_rows;
        // if rows count is 0 then
        if ($rows == 0){
            echo "Word not Found!!!";
        } else {
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
}

?>
<html>
<body>
    <div>
<!--------UPLOAD FORM --------->
<div>
<header><h2>Hello, <?php echo $username ?>. Welcome to Lame Translate</h2></header>
<form method="post" action="" enctype="multipart/form-data">
    <table style="width: 30%;">
        <tr>
            <td width="10%"><button type="submit" name="logout">Logout</button><br/></td>
            <td width="10%"><a href="index.php">Home</a></td>
            <td width="10%"><p><a href="output.php">Show Data</a></p></td>
        </tr>
    </table>
</form>
</div>
<form method="post" action="" enctype="multipart/form-data">
    <h4>Please upload a database file (.txt format)</h4>
    <h5>File format: Word|Meaning</h5>
    <table style="width: 100%;">
        <tr>
            <td width="10%"><label>Select file </label></td>
            <td width="10%"><input type ="file" accept=".txt" name="content" id="content"required><br/></td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><button type="submit" name="upload">Upload</button><br/>
                <?php insert($conn); ?>
            </td>
        </tr>
    </table>
</form>
</div>
</body>
</html>

<html>
<body>

<!--------INPUT FORM --------->
<div>
    <h5>If you haven't uploaded you databses, the default database is English - Vietnamese</h5>
    <form method="GET" action="" enctype="multipart/form-data">
    <textarea type = "text" style = "font-size: 14px" required placeholder = "Enter text" name = "search" rows = "8"cols="40"><?php if(isset($_GET['search'])) { 
         echo htmlentities ($_GET['search']); }?></textarea>
    <button type="submit" value = "translate"> Translate!</button>
    <textarea type = "text" style = "font-size: 14px" placeholder = "Translation" name = "output" rows = "8"cols="40"><?php translate($conn); ?></textarea>
    </form>

</div>
</body>
</html>
