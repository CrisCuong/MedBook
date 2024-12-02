<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../connection.php';
    
    $otp = $_POST['otp'];
    
    // Kiểm tra tính hợp lệ của OTP
    $stmt = $con->prepare("SELECT account_id, expiry_time FROM otp_token WHERE otp = ?");
    $stmt->bind_param('s', $otp);
    $stmt->execute();
    $stmt->bind_result($account_id, $expiry_time);
    $stmt->fetch();
    
    if ($account_id && strtotime($expiry_time) > time()) {
        // OTP hợp lệ, cho phép đặt lại mật khẩu
        header('Location: reset-password.php?account_id=' . $account_id);
        exit;
    } else {
        echo "Invalid or expired OTP.";
    }
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
                        <h3 class="forgot-password-heading">Enter OTP</h3>
                        <form action="" method="POST">
                            <!-- Form nhập OTP -->
                            <div class="forgot-password-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="otp" id="otp" required>
                                </div>                                                                
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btnForgot">Verify OTP</button>
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
