<?php
    $con= new mysqli("localhost","root","root","MedBook");
    if ($con->connect_error){
        die("Connection failed:  ".$con->connect_error);
    }
?>