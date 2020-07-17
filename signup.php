<!-- Signup page -->

<?php
require_once 'config.php';
require_once 'functions.php';

$email ="";
$username ="";
$password = "";
$error = "";


if(isset($_POST["email"]))		//Get the input email
{
    $email = mysql_entities_fix_string($conn, $_POST['email']);
}
if(isset($_POST["username"]))	//Get the input username
{
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    if(!(is_username($username)))
    	$error = "Username must be between 5-32 characters.<br>
    			And contains: Letters, Numbers, and Characters .-_ only.<br>";
}

if(isset($_POST["password"]))	//Get the input password
{
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    if(!(is_password($password)))	//Check valid password
        $error = "Password  MUST contain at least 6 characters.<br> 
                            MUST contain at least one uppercase letter.<br> 
                            MUST contain at least one lowercase letter.<br> 
                            MUST contain at least one number.<br>";
    //echo $password;
}

if($error == ""){
    if(isset($_POST["create"])){
    	//Check the users table exists 
        if(!(table_exists($conn,'users'))){
        create_users($conn); 	//create a users table
        }

        $query1 = "SELECT * FROM users WHERE email='$email'";
        $result1 = $conn->query($query1);
        $query2 = "SELECT * FROM users WHERE username='$username'";
        $result2 = $conn->query($query2);

        if($result1 -> num_rows > 0) 		//Username already exists
        {
            $error = "Email already exists";
        }
        else if($result2 -> num_rows > 0) 	//Email already exists
        {
            $error = "Username already exists";
        }
        else
        {
            add_user($conn, $email, $username, $password);
            (header("Location: login.php"));
        }
    }
}

function is_password($pw)
{
    if(strlen($pw) < 6 ||
        !preg_match("/[a-z]/", $pw)||
        !preg_match("/[A-Z]/", $pw)|| 
        !preg_match("/[0-9]/", $pw))
        return FALSE;
    return TRUE;
}
function is_username($un)
{
	if(strlen($un) < 5 || preg_match("/[^a-zA-Z0-9._-]/",$un))
		return FALSE;
	return TRUE;
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <form  method = "post" action = ""  enctype= "multipart/form-data" class="register-form">
        <h2>Create new account</h2>
        <table style="width: 100%;">
         <tr>
            <td width="10%">Email</td>
            <td><input type="email" required name="email" placeholder="Email address"></td>

        </tr>    
        <tr>
            <td width="10%">Username</td>
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
            <td><button name = "create" type="submit" class="btn btn-primary" >Create</button></td>
        </tr>
            <tr>
                
            </tr>
        </table>
        <p class="message">Already registered?<a href="login.php">Login</a></p>
    </form>
</body>
</html>
