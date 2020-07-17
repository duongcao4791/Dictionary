<!-- Login page -->
<?php
// Initialize the session
session_start();

require_once "config.php";
require_once 'functions.php';

// Redirect to Admin page if user already log in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: admin.php");
    exit;
}

// Define variables
$username = $password = "";
$error = "";

//When the form summited
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($error)){

    //Sanitization
    $username = trim(mysql_entities_fix_string($conn, $_POST["username"]));
    $password = trim(mysql_entities_fix_string($conn, $_POST["password"]));
    

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);
    if(!$result)
    {
        $error = "Invalid username or password";
    }
    elseif ($result->num_rows)
    {   
        //hash the password - followed slides
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();
        $salt1 = "qm&h*";
        $salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$password$salt2");
        if ($token === $row[2]) 
        {
            //Start the sessions
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;  
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']); 
              
            header("Location: admin.php");      
        }
        else
            $error = "Invalid username or password";
    }
    else
        $error ="Invalid username or password";
}
$conn ->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Lame Translate Login</h2>
        <table style="width: 100%;">
        <tr>
            <td width="10%">User name</td>
            <td><input type="text" required name="username" placeholder="Username"></td>

        </tr>
        <tr>
            <td width="10%">Password</td>
            <td><input type="password" required name="password" placeholder="Password"></td>

        </tr>
        <td>
            <td><?php echo $error; ?></td>
        </tr>
        <tr ></tr>
            <td>&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" value="Login"></td>
        </tr>
        </table>
        <tr>
                <td width="10%"><p>Don't have an account?<a href="signup.php">Sign up</a></p></td>
                <a href="index.php">Home</a>
        </tr>
    </form>
</body>
</html>