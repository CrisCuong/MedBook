<?php
    include('../connection.php');
    session_start();
    $doctor_id = $_SESSION['doctor_id'];

    $current_date = date('Y-m-d');
    $currentMonth = date('n'); // Tháng hiện tại (1-12)
    $currentYear = date('Y'); // Năm hiện tại

    $months = array();
    $monthlyCounts = array();

    // Khởi tạo mảng cho các tháng và số lượng
    for ($i = -4; $i <= 1; $i++) {
        $month = ($currentMonth + $i);
        if ($month < 1) {
            $month += 12; // Điều chỉnh nếu tháng âm
            $currentYear--; // Giảm năm
        } elseif ($month > 12) {
            $month -= 12; // Điều chỉnh nếu tháng lớn hơn 12
            $currentYear++; // Tăng năm
        }

        $months[] = date('F', mktime(0, 0, 0, $month, 1, $currentYear)); // Lấy tên tháng
        $monthlyCounts[] = 0; // Khởi tạo với giá trị 0
    }

    // Truy vấn để lấy số lượng lịch hẹn theo tháng
    $sql = "SELECT YEAR(s.date) AS year, MONTH(s.date) AS month, COUNT(*) AS count
        FROM appointment a
        JOIN timeframe t ON a.timeframe_id = t.timeframe_id
        JOIN schedule s ON t.schedule_id = s.schedule_id
        WHERE s.doctor_id = ? AND s.date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
        GROUP BY year, month
        ORDER BY year, month ASC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $monthIndex = (int)$row['month']; // Chỉ số tháng
        if ($monthIndex >= 1 && $monthIndex <= 12) { // Kiểm tra tháng hợp lệ
            // Cập nhật cho vị trí tương ứng
            // Nếu tháng hiện tại là 11 (tháng 11), thì tháng 10 là tháng 10, ... và tháng 6 là tháng 6
            if ($monthIndex >= ($currentMonth - 4) && $monthIndex <= ($currentMonth + 1)) {
                // Điều chỉnh nếu tháng nhỏ hơn 1 hoặc lớn hơn 12
                if ($monthIndex < 1) {
                    $monthIndex += 12; // Điều chỉnh tháng âm
                } elseif ($monthIndex > 12) {
                    $monthIndex -= 12; // Điều chỉnh tháng lớn hơn 12
                }
                // Lưu số lượng vào vị trí tương ứng
                $monthlyCounts[$monthIndex - ($currentMonth - 4)] = (int)$row['count'];
            }
        }
    }

    // Tổng số lịch hẹn trong tháng hiện tại
    $sqlMonthly =   "SELECT COUNT(*) AS totalMonthly FROM appointment a
                    JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                    JOIN schedule s ON t.schedule_id = s.schedule_id
                    WHERE s.doctor_id = ? AND MONTH(s.date) = ? AND YEAR(s.date) = ?";
    $stmtMonthly = $con->prepare($sqlMonthly);
    $stmtMonthly->bind_param("iii", $doctor_id, $currentMonth, $currentYear);
    $stmtMonthly->execute();
    $resultMonthly = $stmtMonthly->get_result();
    $totalMonthlyAppointments = $resultMonthly->fetch_assoc()['totalMonthly'];

    // Tổng số lịch hẹn trong ngày hiện tại
    $sqlToday = "SELECT COUNT(*) AS totalToday FROM appointment a
                JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                JOIN schedule s ON t.schedule_id = s.schedule_id
                WHERE s.doctor_id = ? AND s.date = ?";
    $stmtToday = $con->prepare($sqlToday);
    $stmtToday->bind_param("is", $doctor_id, $current_date);
    $stmtToday->execute();
    $resultToday = $stmtToday->get_result();    
    $totalTodayAppointments = $resultToday->fetch_assoc()['totalToday'];

    /// Truy vấn để lấy tổng số bệnh nhân đã khám
    $sqlTotalPatients = "SELECT COUNT(DISTINCT a.patient_id) AS total_patients
                        FROM appointment a
                        JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                        JOIN schedule s ON t.schedule_id = s.schedule_id
                        WHERE s.doctor_id = ?";

    $stmtTotalPatients = $con->prepare($sqlTotalPatients);
    $stmtTotalPatients->bind_param("i", $doctor_id);
    $stmtTotalPatients->execute();
    $resultTotalPatients = $stmtTotalPatients->get_result();
    $totalPatients = 0;

    if ($row = $resultTotalPatients->fetch_assoc()) {
    $totalPatients = $row['total_patients'];
    }

    // Lấy số bệnh nhân mới trong tháng hiện tại (bệnh nhân có lịch hẹn đầu tiên với bác sĩ trong tháng này)
    $currentMonth = date('m');
    $currentYear = date('Y');

    $sqlNewPatients = "SELECT COUNT(DISTINCT a.patient_id) AS newPatientsThisMonth 
                        FROM appointment a
                        JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                        JOIN schedule s ON t.schedule_id = s.schedule_id
                        WHERE s.doctor_id = ? 
                        AND MONTH(s.date) = ? 
                        AND YEAR(s.date) = ? 
                        AND a.appointment_id IN (
                            SELECT MIN(appointment_id) 
                            FROM appointment 
                            WHERE timeframe_id IN (
                                SELECT timeframe_id 
                                FROM timeframe t2 
                                JOIN schedule s2 ON t2.schedule_id = s2.schedule_id 
                                WHERE s2.doctor_id = ?
                            )
                            GROUP BY patient_id
                        )";
$stmtNewPatients = $con->prepare($sqlNewPatients);
$stmtNewPatients->bind_param("iiii", $doctor_id, $currentMonth, $currentYear, $doctor_id);
$stmtNewPatients->execute();
$resultNewPatients = $stmtNewPatients->get_result();
$newPatientsThisMonth = $resultNewPatients->fetch_assoc()['newPatientsThisMonth'];

    // Đóng kết nối
    $stmt->close();
    $stmtMonthly->close();
    $stmtToday->close();
    $stmtNewPatients->close();
    $con->close();

    // Trả về dữ liệu
    echo json_encode([
        'months' => $months, // Mảng tháng theo chiều tăng dần
        'counts' => $monthlyCounts, // Số lượng tương ứng
        'totalMonthly' => $totalMonthlyAppointments,
        'totalToday' => $totalTodayAppointments,
        'totalPatients' => $totalPatients,
    'newPatientsThisMonth' => $newPatientsThisMonth
    ]);
?>
