<?php
    session_start();
    include('../connection.php');
    $doctor_id = $_SESSION['doctor_id'];

    // Lấy account_id từ doctor_id
    $account_sql = "SELECT account_id FROM doctor WHERE doctor_id = ?";
    $stmt = $con->prepare($account_sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->bind_result($account_id);
    $stmt->fetch();
    $stmt->close();

    $response = array();
    if ($account_id) {
        // Đánh dấu tất cả thông báo của account_id là đã đọc
        $update_sql = "UPDATE notification SET status = 'read' WHERE account_id = ? AND status = 'unread'";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("i", $account_id);

        if ($update_stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to mark all notifications as read.';
        }
        $update_stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'Account ID not found for the doctor.';
    }

    echo json_encode($response);
    $con->close();
?>
