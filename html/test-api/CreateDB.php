<?php
$servername = "143.110.224.7";
$username = "root";
$password = "Val21212@S1n2o3w4w";

// Create connection
$conn = mysql_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_error());
}

// Create database
$sql = "CREATE DATABASE myDB";
if (mysql_query($sql, $conn)) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . mysql_error();
}

// Close connection
mysql_close($conn);
?>
