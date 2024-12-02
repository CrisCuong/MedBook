<?php
    session_start();
    include('../connection.php');

    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }

    // Fetch data from the database
    $data = [];

    // Total Doctors
    $query = "SELECT COUNT(*) AS total_doctors FROM doctor";
    $result = $con->query($query);
    $data['totalDoctors'] = $result->fetch_assoc()['total_doctors'] ?? 0;

    // Total Patients
    $query = "SELECT COUNT(*) AS total_patients FROM patient";
    $result = $con->query($query);
    $data['totalPatients'] = $result->fetch_assoc()['total_patients'] ?? 0;

    // Appointments Today
    $query = "SELECT COUNT(*) AS appointments_today
            FROM appointment
            JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
            JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
            WHERE DATE(schedule.date) = CURDATE()";
    $result = $con->query($query);
    $data['appointmentsToday'] = $result->fetch_assoc()['appointments_today'] ?? 0;

    // Monthly Registrations (New Patients and Doctors This Month)
    $query = "SELECT 
                (SELECT COUNT(*) FROM doctor d 
                JOIN account a ON d.account_id = a.account_id 
                WHERE MONTH(a.created_at) = MONTH(CURDATE()) 
                AND YEAR(a.created_at) = YEAR(CURDATE())) AS new_doctors,
                (SELECT COUNT(*) FROM patient p 
                JOIN account a ON p.account_id = a.account_id 
                WHERE MONTH(a.created_at) = MONTH(CURDATE()) 
                AND YEAR(a.created_at) = YEAR(CURDATE())) AS new_patients";
    $result = $con->query($query);
    $row = $result->fetch_assoc();
    $data['monthlyRegistrations'] = $row['new_doctors'] + $row['new_patients'];

    // Monthly Appointments Trend (Last 6 Months)
    $query = "SELECT DATE_FORMAT(schedule.date, '%m-%Y') AS month, COUNT(*) AS count
                FROM appointment
                JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
                JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
                WHERE schedule.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month";
    $result = $con->query($query);
    $appointmentsTrend = [];
    while ($row = $result->fetch_assoc()) {
        $appointmentsTrend[] = $row;
    }
    $data['appointmentsTrend'] = $appointmentsTrend;

    // // Patient Growth (New Patients Last 6 Months)
    // $query = "SELECT DATE_FORMAT(a.created_at, '%m-%Y') AS month, COUNT(*) AS count
    //             FROM patient p
    //             JOIN account a ON p.account_id = a.account_id
    //             WHERE a.created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    //             GROUP BY month
    //             ORDER BY month";
    // $result = $con->query($query);
    // $patientGrowth = [];
    // while ($row = $result->fetch_assoc()) {
    //     $patientGrowth[] = $row;
    // }
    // $data['patientGrowth'] = $patientGrowth;
    

    // Active Patients (Patients with Appointments in the Last 6 Months)
    $query = "SELECT 
                DATE_FORMAT(schedule.date, '%m-%Y') AS month, 
                COUNT(DISTINCT appointment.patient_id) AS active_patients
            FROM appointment
            JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
            JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
            WHERE schedule.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month";

    $result = $con->query($query);
    $activePatients = [];

    while ($row = $result->fetch_assoc()) {
        $activePatients[] = [
            'month' => $row['month'],
            'count' => $row['active_patients']
        ];
    }

    $data['activePatients'] = $activePatients; // Prepare data for the JS chart



    // Active Doctors (Doctors with Schedules in the Last 6 Months)
$queryDoctors = "
SELECT 
    DATE_FORMAT(schedule.date, '%m-%Y') AS month, 
    COUNT(DISTINCT schedule.doctor_id) AS active_doctors
FROM schedule
WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY month
ORDER BY month";

$resultDoctors = $con->query($queryDoctors);
$activeDoctors = [];

while ($row = $resultDoctors->fetch_assoc()) {
$activeDoctors[] = [
    'month' => $row['month'],
    'count' => $row['active_doctors']
];
}

$data['activeDoctors'] = $activeDoctors; // Prepare data for the JS chart




    // Return data as JSON
    echo json_encode($data);
?>
