<?php
    session_start(); // Bắt đầu session để lấy doctor_id
    include ('../connection.php');

    $doctor_id = $_SESSION['doctor_id']; // Lấy doctor_id từ session

    // Kiểm tra phương thức yêu cầu
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Truy vấn đoạn intro của bác sĩ
        $query = "SELECT intro FROM doctor WHERE doctor_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } else {
            echo json_encode(['error' => 'No intro found']);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lấy dữ liệu JSON từ client
        $data = json_decode(file_get_contents("php://input"), true);
        $intro = $data['intro'];

        $intro = htmlspecialchars($intro, ENT_QUOTES, 'UTF-8');
        // Loại bỏ bullet trước khi lưu vào cơ sở dữ liệu
        $intro = preg_replace('/^•\s*/m', '', $intro); // Xóa bullet '• ' ở đầu mỗi dòng
        
        // Update đoạn intro trong cơ sở dữ liệu
        $query = "UPDATE doctor SET intro = ? WHERE doctor_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $intro, $doctor_id);

        if ($stmt->execute()) {
            echo "Doctor introduction saved successfully.";
        } else {
            echo "Error saving introduction.";
        }
    } else {
        echo "Invalid request method.";
    }
?>