<!-- Login as Doctor -->
<?php
	session_start();
	include('connection.php');
	if(isset($_POST['docsub'])){
		$email = $_POST['email1'];
		$password = $_POST['password3'];
		$query = "SELECT * FROM doctor WHERE email = ?";
		$stmt = $con->prepare($query);
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();

		// Kiểm tra nếu tìm thấy email
		if ($row = $result->fetch_assoc()) {
			// So sánh mật khẩu đã hash với mật khẩu người dùng nhập
			if (password_verify($password, $row['password'])) {
				// Lưu thông tin bác sĩ vào session
				$_SESSION['email'] = $row['email'];
				$_SESSION['doctor_name'] = $row['doctor_name'];
				$_SESSION['doctor_id'] = $row['doctor_id'];

				header("Location: doctor-panel.php");
				exit(); // Dừng script sau khi chuyển hướng
			} else {
				echo "<script>alert('Invalid Email or Password. Try Again!'); 
					window.location.href = 'index.php';</script>";
			}
		} else {
			echo "<script>alert('Invalid Email or Password. Try Again!'); 
				window.location.href = 'index.php';</script>";
		}
		$stmt->close();
	}
?>
