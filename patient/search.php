<?php
    header('Content-Type: application/json');
    include('../connection.php');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $searchTerm = strtolower($_POST['searchTerm']);
        
        $stmt = $con->prepare("
            SELECT d.doctor_id, d.doctor_name, s.speciality_id, s.speciality_name, d.address, d.profile_pic
            FROM doctor d
            INNER JOIN speciality s ON d.speciality_id = s.speciality_id
            WHERE (LOWER (d.doctor_name) LIKE ? OR LOWER(s.speciality_name) LIKE ?) AND d.status = 1
            ORDER BY d.doctor_name ASC
        ");
        $likeSearchTerm = "%$searchTerm%";
        $stmt->bind_param('ss', $likeSearchTerm, $likeSearchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    
        echo json_encode($doctors);
    }
?>
