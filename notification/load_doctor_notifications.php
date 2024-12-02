<?php
    include('../connection.php');
    session_start();

    // Check if the patient is logged in
    if (!isset($_SESSION['doctor_id'])) {
        echo json_encode(['success' => false, 'message' => 'Doctor not logged in.']);
        exit();
    }

    // Fetch account_id based on doctor_id
    $doctorId = $_SESSION['doctor_id'];
    $fetchAccountIdQuery = "SELECT account_id FROM doctor WHERE doctor_id = $doctorId";
    $resultAccountId = $con->query($fetchAccountIdQuery);
    
    if ($resultAccountId->num_rows > 0) {
        $rowAccountId = $resultAccountId->fetch_assoc();
        $accountId = $rowAccountId['account_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch account details.']);
        exit();
    }

    // Fetch notifications related to the account_id
    $notificationQuery = "SELECT notification_id, message, status, created_at, appointment_id, displayed_role FROM notification WHERE account_id = $accountId ORDER BY created_at DESC";
    $resultNotification = $con->query($notificationQuery);

    $notifications = [];
    $unreadCount = 0;
    
    while ($row = $resultNotification->fetch_assoc()) {
        $notifications[] = $row;
        if ($row['status'] == 'unread') {
            $unreadCount++;
        }
    }

    echo json_encode(['success' => true, 'notifications' => $notifications, 'unreadCount' => $unreadCount]);
?>