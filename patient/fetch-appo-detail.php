<?php
// Kết nối đến cơ sở dữ liệu
include ('../connection.php');

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Chuẩn bị truy vấn SQL
    $sql = "SELECT a.appointment_order, d.doctor_name, d.address, s.date, t.start_time, sp.speciality_name, a.doctor_status, a.patient_status
            FROM appointment a
            JOIN timeframe t ON a.timeframe_id = t.timeframe_id
            JOIN schedule s ON t.schedule_id = s.schedule_id
            JOIN doctor d ON s.doctor_id = d.doctor_id
            JOIN speciality sp ON d.speciality_id = sp.speciality_id
            WHERE a.appointment_id = ?";

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Chuyển kết quả thành mảng
    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
        echo json_encode($appointment); // Trả về kết quả dạng JSON
    } else {
        echo json_encode(['error' => 'Appointment not found.']);
    }
} else {
    echo json_encode(['error' => 'Appointment ID is missing.']);
}

?>
