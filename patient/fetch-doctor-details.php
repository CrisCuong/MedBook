<?php
    include('../connection.php'); 

    $doctorId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($doctorId > 0) {
        // Prepare and execute query
        $stmt = $con->prepare("SELECT d.doctor_id, d.doctor_name, s.speciality_id, s.speciality_name, d.address, d.phone, d.profile_pic, d.business_license, d.intro 
                                FROM doctor d
                                JOIN speciality s ON d.speciality_id = s.speciality_id
                                WHERE d.doctor_id = ?");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the data
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Doctor not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Invalid doctor ID']);
    }
    $con->close();
?>
