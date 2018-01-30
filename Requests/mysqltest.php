<html>
<body>
<?php
    $host="mysql.uits.iu.edu";
    $port=3306;
    $socket="";
    $user="schedule_root";
    $password="";
    $dbname="";

    $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());


    //$con->close();
        print_r("We are running");
?>
</body>
</html>
