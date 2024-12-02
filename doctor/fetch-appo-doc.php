<?php
    include('connection.php');
    $doctor_id = $_SESSION['doctor_id'];

    $current_date = date('Y-m-d');
    $six_months_ago = date('Y-m-d', strtotime('-6 months'));

    // Query to fetch appointments for this doctor
    $sql = "SELECT a.appointment_id, a.appointment_order, p.patient_name, s.date AS appointment_date, t.start_time, a.patient_status, a.doctor_status 
             FROM appointment a
             JOIN patient p ON a.patient_id = p.patient_id
             JOIN timeframe t ON a.timeframe_id = t.timeframe_id
             JOIN schedule s ON t.schedule_id = s.schedule_id
             WHERE s.doctor_id = ? AND s.date BETWEEN ? AND ?
             ORDER BY s.date ASC, t.start_time ASC, a.appointment_order ASC";
             
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iss", $doctor_id, $six_months_ago, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<thead><tr><th>Appointment Order</th><th>Patient Name</th><th>Date</th><th>Appointment Time</th><th>Current Status</th><th>Actions</th></tr></thead><tbody>";
        
        while ($row = $result->fetch_assoc()) {
            // Xác định trạng thái hiện tại
            if ($row['doctor_status'] == 1 && $row['patient_status'] == 1) {
                $status = "<span class='status-active'>Active</span>";
            } elseif ($row['doctor_status'] == 1 && $row['patient_status'] == 0) {
                $status = "<span class='status-canceled'>Canceled by Patient</span>";
            } elseif ($row['doctor_status'] == 0 && $row['patient_status'] == 1) {
                $status = "<span class='status-canceled'>Canceled by You</span>";
            }
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['appointment_order']) . "</td>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . date('d-m-Y', strtotime($row['appointment_date'])) . "</td>";
            echo "<td>" . date('H:i', strtotime($row['start_time'])) . " - " . date('H:i', strtotime($row['start_time'] . ' + 30 minutes')) . "</td>";
            echo "<td>" . $status . "</td>";
            // Chỉ hiển thị nút Cancel nếu trạng thái là "Active"
            echo "<td>";
            if ($row['doctor_status'] == 1 && $row['patient_status'] == 1) {
                echo "<button class='cancel-btn' data-appointment-id='" . $row['appointment_id'] . "'>Cancel</button>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
    } else {
        echo "<div class='alert alert-warning'>No appointments found.</div>";
    }

    $stmt->close();

    $con->close();
?>