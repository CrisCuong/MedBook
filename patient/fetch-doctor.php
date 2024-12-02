<?php
    include('../connection.php');

    $query = "SELECT doctor_id, doctor_name, speciality.speciality_id, speciality_name, profile_pic, address 
              FROM doctor 
              INNER JOIN speciality ON doctor.speciality_id = speciality.speciality_id
              WHERE doctor.status = 1
              ORDER BY doctor.doctor_name ASC";
    $result = mysqli_query($con, $query);

    $doctors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    echo json_encode($doctors); // Trả về kết quả dưới dạng JSON
?>