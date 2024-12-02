<?php
    require_once('../connection.php');
    session_start();

    // Hàm lấy danh sách lịch hẹn hôm nay
    function fetchTodayAppointments($patient_id, $con) {
        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");
        $query = "
            SELECT a.appointment_id, TIME_FORMAT(t.start_time, '%H:%i') AS start_time, d.doctor_name, sp.speciality_name, DATE_FORMAT(s.date, '%d/%m/%Y') AS formatted_date
            FROM appointment AS a
            JOIN timeframe AS t ON a.timeframe_id = t.timeframe_id
            JOIN schedule AS s ON t.schedule_id = s.schedule_id
            JOIN doctor AS d ON s.doctor_id = d.doctor_id
            JOIN speciality AS sp ON d.speciality_id = sp.speciality_id
            WHERE s.date = ? AND TIMEDIFF(t.start_time, ?) > '00:30:00' 
            AND a.patient_id = ?
            ORDER BY t.start_time";

        $stmt = $con->prepare($query);
        $stmt->bind_param('ssi', $currentDate, $currentTime, $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    // Hàm lấy danh sách bác sĩ gần đây
    function fetchRecentDoctors($patient_id, $con) {
        $query = "
            SELECT d.doctor_id, d.doctor_name, sp.speciality_name, d.profile_pic
            FROM doctor AS d
            JOIN speciality AS sp ON d.speciality_id = sp.speciality_id
            JOIN (
                SELECT s.doctor_id, MAX(s.date) AS latest_date
                FROM appointment AS a
                JOIN timeframe AS t ON a.timeframe_id = t.timeframe_id
                JOIN schedule AS s ON t.schedule_id = s.schedule_id
                WHERE a.patient_id = ?
                GROUP BY s.doctor_id
                ORDER BY latest_date DESC
                LIMIT 3
            ) AS recent_doctors ON d.doctor_id = recent_doctors.doctor_id";

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        return $doctors;
    }

    $patient_id = $_SESSION['patient_id'] ?? null;

    if ($patient_id) {
        $todayAppointments = fetchTodayAppointments($patient_id, $con);
        $recentDoctors = fetchRecentDoctors($patient_id, $con);

        echo json_encode([
            'todayAppointments' => $todayAppointments,
            'recentDoctors' => $recentDoctors
        ]);
    } else {
        echo json_encode(['error' => 'Patient not logged in']);
    }
?>
