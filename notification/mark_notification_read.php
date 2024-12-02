<?php
    // Kết nối đến cơ sở dữ liệu
    include('../connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $notificationId = $_POST['id']; // Get the notification ID from the POST request
    
        // Update notification status to 'read'
        $sql = "UPDATE notification SET status = 'read' WHERE notification_id = ?";
        $stmt = $con->prepare($sql);
        
        if ($stmt === false) {
            die("Prepare failed: " . $con->error);
        }
    
        $stmt->bind_param("i", $notificationId); // Bind the notification ID
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Notification not found or already read.']);
        }
    
        $stmt->close();
    }
    
    $con->close();
?>
