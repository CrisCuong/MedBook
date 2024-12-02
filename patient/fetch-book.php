<?php
    include('../connection.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header('Content-Type: application/json');

    // Handle Speciality Fetch
    if (isset($_GET['action']) && $_GET['action'] == 'selectSpecialities') {
        $sql = "SELECT speciality_id, speciality_name FROM speciality ORDER BY speciality_name ASC";
        $result = $con->query($sql);

        $specialities = [];
        while ($row = $result->fetch_assoc()) {
            $specialities[] = $row;
        }

        echo json_encode($specialities);
        exit();
    }

    // Handle Doctor Fetch by Speciality
    if (isset($_GET['action']) && $_GET['action'] == 'selectDoctors' && isset($_GET['speciality_id'])) {
        $specialityId = $_GET['speciality_id'];
        $sql = "SELECT doctor_id, doctor_name FROM doctor WHERE speciality_id = $specialityId AND status = 1 ORDER BY doctor_name ASC";
        $result = $con->query($sql);

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        echo json_encode($doctors);
        exit();
    }

    // Handle Available Dates by Doctor
    if (isset($_GET['action']) && $_GET['action'] == 'selectDates' && isset($_GET['doctor_id'])) {
        $doctorId = $_GET['doctor_id'];
        $sql = "SELECT DISTINCT date, schedule_id FROM schedule WHERE doctor_id = $doctorId AND status = 1 ORDER BY date ASC";
        $result = $con->query($sql);

        $dates = [];
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row;
        }

        echo json_encode($dates);
        exit();
    }

    // Handle Available Time Slots by Doctor and Date
    if (isset($_GET['action']) && $_GET['action'] == 'selectTimes' && isset($_GET['schedule_id'])) {
        $scheduleID = $_GET['schedule_id'];
        $sql = "SELECT timeframe_id, start_time, available, booked FROM timeframe WHERE schedule_id = '$scheduleID' ORDER BY start_time ASC /*AND available = 1*/";
        $result = $con->query($sql);

        $timeframes = [];
        while ($row = $result->fetch_assoc()) {
            $timeframes[] = $row;
        }

        echo json_encode($timeframes);
        exit();
    }

    // Handle Appointment Booking
    if (isset($_GET['action']) && $_GET['action'] == 'createAppointment') {
        $rawData = file_get_contents('php://input');

        if ($rawData === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to read request data.']);
            exit();
        }

        // Ensure the data is not empty and decode the JSON
        if (empty($rawData)) {
            echo json_encode(['success' => false, 'message' => 'No data received.']);
            exit();
        }

        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
            exit();
        }

        // Validate data
        if (!isset($data['speciality']) || !isset($data['doctor']) || !isset($data['date']) || !isset($data['time'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required data fields.']);
            exit();
        }

        // Check if expected data fields are present
        $specialityId = $data['speciality'];
        $doctorId = $data['doctor'];
        $scheduleId = $data['date'];
        $timeframeId = $data['time'];

        // Assume $patient_id is already available (e.g., from session data after login)
        session_start();
        if (!isset($_SESSION['patient_id'])) {
            echo json_encode(['success' => false, 'message' => 'Patient not logged in.']);
            exit();
        }
        $patientId = $_SESSION['patient_id'];

        // Logic 1: Kiểm tra timeframe bệnh nhân đã đặt chưa?
        $checkDuplicateQuery = "SELECT COUNT(*) AS count 
                            FROM appointment 
                            WHERE patient_id = $patientId 
                            AND timeframe_id = $timeframeId";
        $duplicateResult = $con->query($checkDuplicateQuery);
        $duplicateRow = $duplicateResult->fetch_assoc();

        if ($duplicateRow['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'You have already booked this timeframe.']);
            exit();
        }

        // Logic 2: Kiểm tra số lượng timeframes bệnh nhân đã đặt trong ngày với 1 bác sĩ cụ thể
        $checkDateQuery = "SELECT date FROM schedule WHERE schedule_id = $scheduleId";
        $dateResult = $con->query($checkDateQuery);
        $dateRow = $dateResult->fetch_assoc();
        $date = $dateRow['date'];

        $checkTimeframeLimitQuery = "SELECT COUNT(*) AS count 
                                    FROM appointment 
                                    JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
                                    JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
                                    WHERE appointment.patient_id = $patientId 
                                    AND schedule.doctor_id = $doctorId
                                    AND DATE(schedule.date) = DATE('$date')";
        $timeframeLimitResult = $con->query($checkTimeframeLimitQuery);
        $timeframeLimitRow = $timeframeLimitResult->fetch_assoc();

        if ($timeframeLimitRow['count'] >= 2) {
        echo json_encode(['success' => false, 'message' => 'You have already booked 2 timeframes for this doctor on this date.']);
        exit();
        }

        // Logic 3: Kiểm tra số lượng bác sĩ đã đặt trong ngày?
        $checkDoctorLimitQuery = "SELECT COUNT(DISTINCT schedule.doctor_id) AS count 
                                FROM appointment 
                                JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
                                JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
                                WHERE appointment.patient_id = $patientId 
                                AND DATE(schedule.date) = DATE('$date')";
        $doctorLimitResult = $con->query($checkDoctorLimitQuery);
        $doctorLimitRow = $doctorLimitResult->fetch_assoc();

        if ($doctorLimitRow['count'] >= 3) {
            echo json_encode(['success' => false, 'message' => 'You have already booked 3 doctors for this date.']);
            exit();
        }

        // Logic 4: Kiểm tra timeframe còn slot trống không?
        $sqlCheck = "SELECT booked, available FROM timeframe WHERE timeframe_id = $timeframeId";
        $resultCheck = $con->query($sqlCheck);
        $rowCheck = $resultCheck->fetch_assoc();

        if ($rowCheck['booked'] < $rowCheck['available']) {
            // Get the max appointment_order in this timeframe
            $sqlGetMaxOrder = "SELECT MAX(appointment_order) AS max_order FROM appointment WHERE timeframe_id = $timeframeId";
            $resultMaxOrder = $con->query($sqlGetMaxOrder);
            $rowMaxOrder = $resultMaxOrder->fetch_assoc();
            $currentMaxOrder = isset($rowMaxOrder['max_order']) ? $rowMaxOrder['max_order'] : 0;

            // Calculate the new appointment order
            $newAppointmentOrder = $currentMaxOrder + 1;

            // Proceed to book the appointment with the new appointment order
            $sqlBook = "INSERT INTO appointment (patient_id, timeframe_id, appointment_order, patient_status, doctor_status) 
                        VALUES ($patientId, $timeframeId, $newAppointmentOrder, '1', '1')";
            if ($con->query($sqlBook) === TRUE) {
                // Lấy appointment_id mới vừa được tạo
                $appointment_id = $con->insert_id;
            
                // Cập nhật số lượng đã đặt
                $sqlUpdateBooked = "UPDATE timeframe SET booked = booked + 1 WHERE timeframe_id = $timeframeId";
                $con->query($sqlUpdateBooked);
            
                // Truy vấn account_id từ patient_id
                $fetchPatientAccountIdQuery = "SELECT account_id FROM patient WHERE patient_id = $patientId";
                $resultPatientAccountId = $con->query($fetchPatientAccountIdQuery);
                if ($resultPatientAccountId->num_rows > 0) {
                    $rowPatientAccountId = $resultPatientAccountId->fetch_assoc();
                    $patientAccountId = $rowPatientAccountId['account_id']; // Use this for notification
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to fetch patient account details.']);
                    exit();
                }

                // Truy vấn account_id từ doctor_id
                $fetchDoctorAccountIdQuery = "SELECT account_id FROM doctor WHERE doctor_id = $doctorId";
                $resultDoctorAccountId = $con->query($fetchDoctorAccountIdQuery);
                if ($resultDoctorAccountId->num_rows > 0) {
                    $rowDoctorAccountId = $resultDoctorAccountId->fetch_assoc();
                    $doctorAccountId = $rowDoctorAccountId['account_id']; // Use this for notification
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to fetch doctor account details.']);
                    exit();
                }

                $messageForPatient = "You booked a new appointment successfully!";
                $messageForDoctor = "You have a new appointment!";
                
                // Chèn thông báo vào bảng notification cho displayed_role patient
                $sqlInsertPatientNotification = "INSERT INTO notification (message, account_id, appointment_id, status, created_at, displayed_role) 
                                          VALUES (?, ?, ?, 'unread', NOW(), 'patient')";
                $stmt = $con->prepare($sqlInsertPatientNotification);
                $stmt->bind_param('sii', $messageForPatient, $patientAccountId, $appointment_id);
                $stmt->execute();

                // Chèn thông báo vào bảng notification cho displayed_role doctor
                $sqlInsertDoctorNotification = "INSERT INTO notification (message, account_id, appointment_id, status, created_at, displayed_role) 
                                          VALUES (?, ?, ?, 'unread', NOW(), 'doctor')";
                $stmt = $con->prepare($sqlInsertDoctorNotification);
                $stmt->bind_param('sii', $messageForDoctor, $doctorAccountId, $appointment_id);
                $stmt->execute();

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to book the appointment.']);
            }
        } else {
            // Timeframe is full
            echo json_encode(['success' => false, 'message' => 'This timeframe is fully booked.']);
        }
        exit();
    }


?>