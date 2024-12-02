<!-- Login as Admin -->
<?php
	session_start();
	include('connection.php');
	if(isset($_POST['adsub'])){
		$username = $_POST['username1'];
		$password = $_POST['password2'];
		$query = "SELECT * FROM admin WHERE username = ?";
		$stmt = $con->prepare($query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
	
			// Kiểm tra mật khẩu
			if (password_verify($password, $row['password'])) {
				$_SESSION['username'] = $username;
				$_SESSION['admin_id'] = $row['admin_id'];
				$_SESSION['admin_name'] = $row['admin_name'];
				header("Location: admin-panel.php");
				exit();
			} else {
				echo "<script>
					alert('Invalid Username or Password. Try Again!');
					window.location.href = 'index.php';
				</script>";
			}
		} else {
			echo "<script>
				alert('Invalid Username or Password. Try Again!');
				window.location.href = 'index.php';
			</script>";
		}
	}

	// Xử lý đăng ký admin mới
	if (isset($_POST['adsub1'])) {
		$admin_name = $_POST['admin_name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$con_password = $_POST['con_password'];

		// Kiểm tra username đã tồn tại chưa
		$checkQuery = "SELECT * FROM admin WHERE username = ?";
		$stmt = $con->prepare($checkQuery);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$checkResult = $stmt->get_result();

		if ($checkResult->num_rows > 0) {
			echo "<script>
				alert('Username already exists!');
				window.history.back();
			</script>";
			exit();
		}	

		if ($password === $con_password) {
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	
			// Tạo account mới cho admin
			$createAccountQuery = "INSERT INTO account(role_id, status) VALUES ('3', '1')";
			if ($con->query($createAccountQuery) === TRUE) {
				// Lấy account_id mới được tạo
				$account_id = $con->insert_id;
	
				// Chèn dữ liệu vào bảng admin
				$insertAdminQuery = "INSERT INTO admin(account_id, admin_name, username, password) 
									VALUES ('$account_id', '$admin_name', '$username',  '$hashed_password')";
				
				if ($con->query($insertAdminQuery) === TRUE) {
					$_SESSION['account_id'] = $account_id;
					$_SESSION['admin_id'] = $con->insert_id;
					$_SESSION['admin_name'] = $admin_name;
	
					// Hiển thị thông báo và chuyển hướng đến admin-panel
					echo "<script>
						alert('Register a new admin successfully!');
						window.location.href = 'admin-panel.php';
					</script>";
				} else {
					echo "Lỗi: " . $insertAdminQuery . "<br>" . $con->error;
				}
			} else {
				echo "Lỗi: " . $createAccountQuery . "<br>" . $con->error;
			}
		} else {
			echo "<script>
				alert('Password and Confirm Password do not match!');
				window.history.back();
			</script>";
			exit();
		}
	}
?>


