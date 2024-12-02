<?php
    include('../connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];

        // Begin transaction to ensure both queries succeed
        $con->begin_transaction();

        try {
            // Step 1: Update doctor_status to 0 (canceled)
            $sql = "UPDATE appointment SET doctor_status = 0 WHERE appointment_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $appointment_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating doctor_status: " . $stmt->error);
            }

            // Step 2: Fetch patient_id and doctor_id for this appointment
            $sql = "SELECT patient_id, t.schedule_id, s.doctor_id 
                    FROM appointment a 
                    JOIN timeframe t ON a.timeframe_id = t.timeframe_id 
                    JOIN schedule s ON t.schedule_id = s.schedule_id 
                    WHERE a.appointment_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $appointment_id);
            if (!$stmt->execute()) {
                throw new Exception("Error fetching patient_id and doctor_id: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $patient_id = $row['patient_id'];
            $doctor_id = $row['doctor_id'];

            // Step 3: Fetch account_id for both patient and doctor
            // Fetch account_id for patient
            $fetchPatientAccountIdQuery = "SELECT account_id FROM patient WHERE patient_id = ?";
            $stmt = $con->prepare($fetchPatientAccountIdQuery);
            $stmt->bind_param('i', $patient_id);
            $stmt->execute();
            $resultPatientAccountId = $stmt->get_result();
            if ($resultPatientAccountId->num_rows > 0) {
                $rowPatientAccountId = $resultPatientAccountId->fetch_assoc();
                $patient_account_id = $rowPatientAccountId['account_id'];
            } else {
                throw new Exception("Failed to fetch patient account details.");
            }

            // Fetch account_id for doctor
            $fetchDoctorAccountIdQuery = "SELECT account_id FROM doctor WHERE doctor_id = ?";
            $stmt = $con->prepare($fetchDoctorAccountIdQuery);
            $stmt->bind_param('i', $doctor_id);
            $stmt->execute();
            $resultDoctorAccountId = $stmt->get_result();
            if ($resultDoctorAccountId->num_rows > 0) {
                $rowDoctorAccountId = $resultDoctorAccountId->fetch_assoc();
                $doctor_account_id = $rowDoctorAccountId['account_id'];
            } else {
                throw new Exception("Failed to fetch doctor account details.");
            }

            // Step 4: Insert notification for the patient
            $messagePatient = "Your appointment was canceled by the Doctor";
            $sql = "INSERT INTO notification (account_id, appointment_id, message, status, created_at, displayed_role) 
                    VALUES (?, ?, ?, 'unread', NOW(), 'patient')";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("iis", $patient_account_id, $appointment_id, $messagePatient);
            if (!$stmt->execute()) {
                throw new Exception("Error inserting patient notification: " . $stmt->error);
            }

            // Step 5: Insert notification for the doctor
            $messageDoctor = "You have canceled the appointment";
            $sql = "INSERT INTO notification (account_id, appointment_id, message, status, created_at, displayed_role) 
                    VALUES (?, ?, ?, 'unread', NOW(), 'doctor')";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("iis", $doctor_account_id, $appointment_id, $messageDoctor);
            if (!$stmt->execute()) {
                throw new Exception("Error inserting doctor notification: " . $stmt->error);
            }

            // Commit transaction
            $con->commit();
            echo "Success";

        } catch (Exception $e) {
            // Rollback transaction in case of error
            $con->rollback();
            echo $e->getMessage();
        }

        $stmt->close();
        $con->close();
    } else {
        echo "Invalid request.";
    }
?>
