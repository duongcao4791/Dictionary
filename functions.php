<?php   //store all functions needed

function add_user($conn, $em, $un, $pw)     //Register new user
{
    $param_email = $em;
    $param_username = $un;
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    $param_password = hash('ripemd128', "$salt1$pw$salt2");// Creates a password hash
    $query = "INSERT INTO users VALUES('$param_email', '$param_username', '$param_password')";
        $result = $conn->query($query);
        if (!$result) die($conn->error);
}

//Create Users table
function create_users($conn)
{

    $query = "CREATE TABLE users (
        email VARCHAR (100) NOT NULL UNIQUE,
        username VARCHAR(32) NOT NULL UNIQUE PRIMARY KEY,
        password VARCHAR(100) NOT NULL)";

    $conn->query($query);
}

//Create table function
function create_table($conn, $name)
{
    $table = "CREATE TABLE $name( 
            word VARCHAR(64) NOT NULL UNIQUE,
            meaning VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL)";
    $result = $conn->query($table);

}

//Insert data
function insert_data($conn,$table,$f){
    while(!feof($f)) { 
            $data = explode("|", fgets($f));
            $data[1] = str_replace("\n"," ",$data[1]);
            $word = mysqli_real_escape_string($conn,$data[0]);
            $meaning = mysqli_real_escape_string($conn,$data[1]);
            //echo "$word <br>";
            //echo "$meaning <br>";
            //echo "$data[0] <br>";
            //echo "$data[1] <br>";
            $query = "INSERT INTO $table values ('$word', '$meaning')";
            $result = $conn->query($query);
            if (!$result) die($conn->error);
        }
}

//Check table exists
function table_exists($conn, $table)
{
    $query = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($query);
    if($result->num_rows == 1)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function mysql_entities_fix_string($connection, $string) //function from the slides
{
    return htmlentities(mysql_fix_string($connection, $string));
}
    
function mysql_fix_string($connection, $string)     //function from the slides
{
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return $connection->real_escape_string($string);
}

//log out function
function logout(){
    //destroy all session
    session_start();
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}


?>
