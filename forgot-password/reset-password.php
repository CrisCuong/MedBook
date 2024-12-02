<?php
    if (isset($_GET['account_id'])) {
        $account_id = $_GET['account_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            include '../connection.php';
            
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password === $confirm_password) {
                // Hash mật khẩu mới và cập nhật vào cơ sở dữ liệu
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                $stmt = $con->prepare("
                    UPDATE patient SET password = ? WHERE account_id = ?
                    UNION
                    UPDATE doctor SET password = ? WHERE account_id = ?
                ");
                $stmt->bind_param('sisi', $hashed_password, $account_id, $hashed_password, $account_id);
                $stmt->execute();
                
                echo "Password has been reset successfully.";
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid account.";
    }
?>

<html>
    <head>
        <title>MedBook</title>
        <link rel="shortcut icon" type="image/x-icon" href="../img/image/MedBook_icon.jpg" />
        <link rel="stylesheet" type="text/css" href="../css/forgot-password.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <style >
            .form-control {
                border-radius: 0.75rem;
            }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="color: #fff">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="index.php" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="background-color:#60c2fe"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <!-- <select id="languageSwitcher">
                            <option value="en">English</option>
                            <option value="vi">Vietnamese</option>
                        </select> -->
                        <li class="nav-item" style="margin-right: 40px;">
                            <button class="btn btn-secondary ml-2" onclick="location.href='../index.php'" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif;height: 40px;"><h5>Home</h5></button>
                        </li>
                        <li class="nav-item" style="margin-right: 40px;">
                            <button class="btn btn-secondary ml-2" onclick="location.href='../about_us.php'" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif; height: 40px;"><h5>About us</h5></button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        

        <div class="forgot-password" style="background-color: #91d9ff;font-family: 'IBM Plex Sans', sans-serif;height: 90%;">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 cell-body" style="margin-top: 50px">
                    <div class="body-content">
                        <h3 class="forgot-password-heading">Reset a new password</h3>
                        <form action="" method="POST">
                            <!-- Form đặt lại mật khẩu -->
                            <div class="forgot-password-form">
                                <label for="new_password">New Password:</label>
                                <div class="form-group">
                                    <input type="password" name="new_password" id="new_password" required>
                                </div>             
                                <label for="confirm_password">Confirm Password:</label>                                                   
                                <div class="form-group">
                                    <input type="password" name="confirm_password" id="confirm_password" required>
                                </div>    
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btnForgot">Reset Password</button>
                            </div>    
                        </form>  
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</html>

