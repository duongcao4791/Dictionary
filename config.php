<?php 	// config.php
require_once 'functions.php';
$hn = 'localhost';
$un = 'duong';				//username
$pw = 'duong123';     		//password
$db = 'final';      		//database name
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(mysql_fatal_error($conn->connect_error));



function mysql_fatal_error($msg, $conn)
{
    $msg2 = mysqli_error($conn);
    echo <<< _END
We are sorry, but it was not possible to complete
the requested task. The error message we got was:

	<p>$msg: $msg2</p>

Please click the back button on your browser
and try again. If you are still having problems,
please <a href="mailto:admin@server.com">email
our administrator</a>. Thank you.
_END;
}

?>