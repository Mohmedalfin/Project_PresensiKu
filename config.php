<?php
//database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "presensiku";

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$connection) {
    echo "Connection failed: " . mysqli_connect_error();
}

function base_url($url = null)
{
    $base_url = 'http://localhost/RPLPresensi';
    if ($url != null) {
        return $base_url . '/' . $url;
    } else {
        return $base_url;
    }
}
?>