<?php
    // Start the session to access session variables
session_start();

// Include database connection file
include('../connection.php'); // Adjust the path as needed

// Check if the session variable 'patient_id' is set
if (!isset($_SESSION['patient_id'])) {
    die('Error: Patient ID is not set in the session.');
}

// Get patient ID from the session
$patient_id = $_SESSION['patient_id'];

// Check if the $con variable is defined (ensure db_connection.php sets it)
if (!isset($con)) {
    die('Error: Database connection not established.');
}

// Fetch current timestamp
$current_time = date('H:i:s');

// Fetch upcoming appointments within the current timeframe
$appointments_query = "
    SELECT a.appointment_id, a.appointment_order, s.date, t.start_time, a.doctor_status
    FROM appointment a
    JOIN timeframe t ON a.timeframe_id = t.timeframe_id
    JOIN schedule s ON t.schedule_id = s.schedule_id
    WHERE a.patient_id = ? 
      AND s.date = CURDATE() 
      AND t.start_time <= ? 
      AND (t.start_time + INTERVAL 24 HOUR) > ? -- Adjust the timeframe duration as needed
      AND a.patient_status = 1
    ORDER BY s.date, t.start_time ASC
";
$stmt = $con->prepare($appointments_query);
$stmt->bind_param("iss", $patient_id, $current_time, $current_time);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

// Fetch recent doctor recommendations (example data, adjust as needed)
$doctors_query = "SELECT doctor_name, speciality_id, profile_pic FROM doctor ORDER BY RAND() LIMIT 3";
$result = $con->query($doctors_query);

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

// Return data as JSON
echo json_encode(['appointments' => $appointments, 'doctors' => $doctors]);

$stmt->close();
$con->close();
?>