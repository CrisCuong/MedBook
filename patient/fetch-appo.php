<?php
    session_start();
    include('../connection.php');

    $patient_id = $_SESSION['patient_id'];
    if (!isset($_SESSION['patient_id'])) {
        die("Error: Patient ID not found in session.");
    }

    // Cancel appointment and update booked slot again
    if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['appointment_id'])) {
        $appointment_id = $_GET['appointment_id'];
    
        // Begin a transaction to ensure both queries are executed correctly
        $con->begin_transaction();
    
        try {
            // Step 1: Update the patient_status to 0 (canceled) in the appointment table
            $sql = "UPDATE appointment SET patient_status = 0 WHERE appointment_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $appointment_id);
            if (!$stmt->execute()) {
                throw new Exception("Error canceling appointment: " . $stmt->error);
            }

            // Step 2: Lấy account_id của bệnh nhân để sử dụng cho notification
            $fetchPatientAccountIdQuery = "SELECT account_id FROM patient WHERE patient_id = ?";
            $stmt = $con->prepare($fetchPatientAccountIdQuery);
            if (!$stmt) {
                throw new Exception('Error preparing statement: ' . $con->error);
            }
            $stmt->bind_param('i', $patient_id); // Liên kết tham số an toàn
            $stmt->execute();
            $resultPatientAccountId = $stmt->get_result();

            if ($resultPatientAccountId->num_rows > 0) {
                $rowPatientAccountId = $resultPatientAccountId->fetch_assoc();
                $account_id = $rowPatientAccountId['account_id']; // Dùng account_id này để thêm notification
            } else {
                throw new Exception('Failed to fetch account details.');
            }
            $stmt->close();

            // Step 3: Chèn thông báo vào bảng `notification` với role patient
            $message = "The appointment was canceled by You";
            $sql = "INSERT INTO notification (account_id, appointment_id, message, status, created_at, displayed_role) VALUES (?, ?, ?, 'unread', NOW(), 'patient')";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("iis", $account_id, $appointment_id, $message);
            if (!$stmt->execute()) {
                throw new Exception("Error inserting notification: " . $stmt->error);
            }

            // Step 4: Fetch the timeframe_id associated with this appointment
            $sql = "SELECT timeframe_id FROM appointment WHERE appointment_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $appointment_id);
            if (!$stmt->execute()) {
                throw new Exception("Error fetching timeframe_id: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $timeframe_id = $row['timeframe_id'];
    
            // Step 5: Decrement the booked value in the timeframe table
            $sql = "UPDATE timeframe SET booked = booked - 1 WHERE timeframe_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $timeframe_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating booked value: " . $stmt->error);
            }

            // Step 6: Fetch the doctor_id associated with this appointment
            $sql = "SELECT s.doctor_id FROM appointment a 
                    JOIN timeframe t ON a.timeframe_id = t.timeframe_id 
                    JOIN schedule s ON t.schedule_id = s.schedule_id WHERE a.appointment_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $appointment_id);
            if (!$stmt->execute()) {
                throw new Exception("Error fetching doctor_id: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $doctor_id = $row['doctor_id'];

            // Step 7: Fetch the account_id of the doctor to use for notification
            $fetchDoctorAccountIdQuery = "SELECT account_id FROM doctor WHERE doctor_id = ?";
            $stmt = $con->prepare($fetchDoctorAccountIdQuery);
            if (!$stmt) {
                throw new Exception('Error preparing statement: ' . $con->error);
            }
            $stmt->bind_param('i', $doctor_id);
            $stmt->execute();
            $resultDoctorAccountId = $stmt->get_result();

            if ($resultDoctorAccountId->num_rows > 0) {
                $rowDoctorAccountId = $resultDoctorAccountId->fetch_assoc();
                $doctor_account_id = $rowDoctorAccountId['account_id']; // Dùng account_id này để thêm notification
            } else {
                throw new Exception('Failed to fetch doctor account details.');
            }

            $stmt->close();

            // Step 8: Insert notification for the doctor
            $messageDoctor = "The appointment was canceled by the Patient";
            $sql = "INSERT INTO notification (account_id, appointment_id, message, status, created_at, displayed_role) 
                VALUES (?, ?, ?, 'unread', NOW(), 'doctor')";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("iis", $doctor_account_id, $appointment_id, $messageDoctor);
            if (!$stmt->execute()) {
                throw new Exception("Error inserting doctor notification: " . $stmt->error);
            }

    
            // Commit the transaction if all queries succeed
            $con->commit();
    
            // Fetch the updated appointments list
            fetchUpdatedAppointments($con, $patient_id);
    
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $con->rollback();
            echo $e->getMessage();
        }
        $stmt->close();
    } else {
        // If no action is set, just fetch the appointments normally
        fetchUpdatedAppointments($con, $patient_id);
    }

    // Function to fetch and return the updated appointment list
    function fetchUpdatedAppointments($con, $patient_id) {
        // $sql = "SELECT a.appointment_id, a.patient_id, a.timeframe_id, a.appointment_order, 
        //                a.doctor_status, a.patient_status, d.doctor_name, d.speciality_id, 
        //                d.doctor_id, s.date, t.start_time,
        //                CASE 
        //                    WHEN a.doctor_status = 1 AND a.patient_status = 1 THEN 1
        //                    ELSE 0 
        //                END AS current_status,
        //                CONCAT(s.date, ' ', t.start_time) AS appointment_datetime
        //         FROM appointment a
        //         JOIN timeframe t ON a.timeframe_id = t.timeframe_id
        //         JOIN schedule s ON t.schedule_id = s.schedule_id
        //         JOIN doctor d ON s.doctor_id = d.doctor_id
        //         WHERE a.patient_id = ?
        //             AND s.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        //         ORDER BY s.date DESC, t.start_time DESC";
        $sql = "SELECT a.appointment_id, a.patient_id, a.timeframe_id, a.appointment_order, 
                   a.doctor_status, a.patient_status, d.doctor_name, d.speciality_id, 
                   d.doctor_id, s.date, t.start_time,
                   CASE 
                       WHEN CONCAT(s.date, ' ', t.start_time) < NOW() THEN 1
                       ELSE 0
                   END AS is_overdue,
                   CONCAT(s.date, ' ', t.start_time) AS appointment_datetime
            FROM appointment a
            JOIN timeframe t ON a.timeframe_id = t.timeframe_id
            JOIN schedule s ON t.schedule_id = s.schedule_id
            JOIN doctor d ON s.doctor_id = d.doctor_id
            WHERE a.patient_id = ?
                AND s.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            ORDER BY is_overdue ASC, 
                     CASE WHEN is_overdue = 0 THEN CONCAT(s.date, ' ', t.start_time) END ASC,
                     CASE WHEN is_overdue = 1 THEN CONCAT(s.date, ' ', t.start_time) END DESC";
        
        $stmt = $con->prepare($sql);
    
        if (!$stmt) {
            die("Error: " . $con->error);
        }
        
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = array();
        
        while ($row = $result->fetch_assoc()) {
            // Kiểm tra xem appointment đã qua chưa
            $appointment_datetime = new DateTime($row['appointment_datetime']);
            $current_datetime = new DateTime();
    
            if ($appointment_datetime < $current_datetime) {
                $row['show_detail_button'] = false;  // Đã qua, chỉ hiển thị "Book Again"
            } else {
                $row['show_detail_button'] = true;   // Hiển thị cả "Detail" và "Book Again"
            }
    
            $appointments[] = $row;
        }
    
        header('Content-Type: application/json');
        echo json_encode($appointments);
        $stmt->close();
        $con->close();
    }
    
?>
        