<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="../css/style2.css">
        <title>MedBook</title>

        <!-- Bootstrap CSS -->
        <!-- <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="../img/image/MedBook_icon.jpeg" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
        <!-- Bootstrap chuyển tab -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
        <!-- Thông báo khi update schedule -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#schedule-form').submit(function(event) {
            event.preventDefault(); // Ngăn chặn hành vi gửi form mặc định
            $.ajax({
                url: 'doctor/schedule-updated.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                if (response.status === 'success') {
                    alert(response.message); // Hiển thị thông báo thành công
                    window.location.href = 'doctor-panel.php'; // Chuyển hướng về trang schedule
                    } else {
                    alert(response.message); // Hiển thị thông báo lỗi
                    }
                },
                error: function() {
                    alert('An error occurred.'); // Hiển thị thông báo lỗi nếu có lỗi trong quá trình gửi request
                }
                });
            });
            });
        </script>
    </head>
  
    <?php 
        include('../func2.php');
        $doctor_id = $_SESSION['doctor_id'];
        $doctor_name = $_SESSION['doctor_name'];
    ?>

    <body style="background-color: #91d9ff; margin-top: 120px">
        <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #fff">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="../doctor-panel.php" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-color:#60c2fe"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <!-- Notification Bell Icon for Doctor -->
                    <!-- <div class="dropdown" style="position: relative;margin: 5px 20px 0 0">
                        <div id="notification-icon" class="icon" style="cursor:pointer;">
                            <i class="fas fa-bell" style="font-size: 30px; color: #60c2fe;"></i>
                            <span id="doctorNotificationCount"></span>
                        </div>
                        <ul id="doctorNotificationList" class="dropdown-menu notification-list" style="display:none"> 
                        </ul>
                    </div> -->

                    <li class="nav-item dropdown" style="margin-right: 40px;">
                    <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" 
                            id="accountDropdown" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"
                            style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif;">
                        <h5 style="margin: 5px 0 5px 0">Account</h5>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="accountDropdown">
                        <!-- <a class="dropdown-item" href="doctor/change-password.php">Change password</a> -->
                        <a class="dropdown-item" href="#" onclick="clearTabAndLogout()">Logout</a>
                    </div>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif;"> Welcome Dr. <?php echo $doctor_name ?>!</h3>
            
            <div class="change-password">
                <div class="row justify-content-center">
                    <div class="col-md-8 cell-body">
                        <div class="body-content">
                            <form action="change-password.php" method="POST">
                                <h3 class="heading" style="text-align: center;color: #0062cc;font-size: 30px;margin-bottom: 35px">Change password</h3>
                                <div class="form-group">
                                    <label for="current_password">Current Password:</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password:</label>
                                    <input type="password" id="new_password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password:</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                </div>
                                <div class="button-group">
                                    <a href="../doctor-panel.php" class="btn btn-primary">Back</a>
                                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <script>
            function clearTabAndLogout() {
                localStorage.removeItem('activeTab'); // Clear the active tab from localStorage
                location.href = '../logout.php'; // Proceed with the logout
            }
        </script>
    </body>
</html>




<?php
    include('../connection.php'); // Giả sử bạn đã có file config để kết nối DB
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';

    if (isset($_POST['change_password'])) {
        $doctor_id = $_SESSION['doctor_id']; // Đảm bảo session của bác sĩ đã đăng nhập
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Input validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
            return;
        }

        // Kiểm tra mật khẩu hiện tại
        $query = "SELECT password, email FROM doctor WHERE doctor_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password, $email);
        $stmt->fetch();
        $stmt->close();

        // Xác nhận mật khẩu hiện tại và mật khẩu mới khớp
        if (password_verify($current_password, $hashed_password)) {
            // Kiểm tra độ dài mật khẩu mới
            if (strlen($new_password) < 6) {
                echo "<script>alert('New password must be at least 6 characters long.');</script>";
                return;
            }

            if ($new_password === $confirm_password) {
                // Kiểm tra xem mật khẩu mới có trùng với mật khẩu hiện tại không
                if (password_verify($new_password, $hashed_password)) {
                    echo "<script>alert('New password cannot be the same as the current password.');</script>";
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Cập nhật mật khẩu mới
                    $update_query = "UPDATE doctor SET password = ? WHERE doctor_id = ?";
                    $update_stmt = $con->prepare($update_query);
                    $update_stmt->bind_param("si", $new_hashed_password, $doctor_id);
                    if ($update_stmt->execute()) {
                        // Gửi email xác nhận bằng PHPMailer
                        $mail = new PHPMailer(true); // Tạo đối tượng PHPMailer
                        try {
                            // Cấu hình server SMTP
                            $mail->isSMTP();
                            $mail->Host       = 'smtp.gmail.com'; // Máy chủ SMTP
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'medbookv1@gmail.com'; // Thay bằng email của bạn
                            $mail->Password   = 'ixoaujycsmgyheqr'; // Thay bằng mật khẩu ứng dụng
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;

                            // Cấu hình email người gửi và người nhận
                            $mail->setFrom('admin@medbook.com', 'MedBook Admin'); // Email người gửi
                            $mail->addAddress($email); // Email bác sĩ

                            // Nội dung email
                            $mail->isHTML(true);
                            $mail->Subject = "Password Change Confirmation";
                            $mail->Body    = "Your password has been successfully changed.<br><br>If you have not done this action, please contact us immediately at the email below:<br><a href='mailto:medbookv1@gmail.com'>medbookv1@gmail.com</a>";

                            $mail->send(); // Gửi email

                            echo "<script>
                                alert('Password updated successfully. A confirmation email has been sent.');
                                window.location.href = '../doctor-panel.php';
                            </script>";
                        } catch (Exception $e) {
                            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                        }
                    } else {
                        echo "<script>alert('Error updating password.');</script>";
                    }
                    $update_stmt->close();
                }
            } else {
                echo "<script>alert('New passwords do not match.');</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect.');</script>";
        }
    }
?>
