<?php
    session_start();
    session_unset();        // Xóa tất cả các biến session
    session_destroy();      // Hủy session

    // Xóa cookie PHPSESSID
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MedBook</title>
        <!-- Bootstrap CSS -->
        <!-- <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="img/image/MedBook_icon.jpeg" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
        <!-- Bootstrap chuyển tab -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <style >
            .btn-outline-light:hover {
                color: #0076d4;
                background-color: #f8f9fa;
                border-color: #f8f9fa;
            }
        </style>
    </head>
    <body style="background-color: #60c2fe ;color:white;padding-top:300px;text-align:center;">
        <h3>You have logged out.</h3><br><br>
        <a href="index.php" class="btn btn-outline-light">Back to Home Page</a>

        <script type="text/javascript">
            // Xóa lịch sử điều hướng sau khi đăng xuất
            if (window.history.replaceState) {
                // Thay thế trạng thái lịch sử hiện tại để ngăn forward trở lại trang bảo mật
                window.history.replaceState(null, null, window.location.href);
            }

            // Ngăn người dùng quay lại bằng cách vô hiệu hóa nút back
            window.onload = function() {
                window.history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function(event) {
                    window.history.pushState(null, null, window.location.href);
                });
            };
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    </body>
</html>