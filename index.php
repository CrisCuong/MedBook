<!-- Trang login -->
<?php
session_start();

// Hủy toàn bộ dữ liệu session
session_unset();
session_destroy();

// Xóa cookie PHPSESSID nếu tồn tại
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


?>

<html>
    <head>
        <title>MedBook</title>
        <link rel="shortcut icon" type="image/x-icon" href="img/image/MedBook_icon.jpeg" />
        <link rel="stylesheet" type="text/css" href="css/index.css">
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
                            <button class="btn btn-secondary ml-2" onclick="location.href='index.php'" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif;height: 40px;"><h5>Home</h5></button>
                        </li>
                        <li class="nav-item" style="margin-right: 40px;">
                            <button class="btn btn-secondary ml-2" onclick="location.href='about_us.php'" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif; height: 40px;"><h5>About us</h5></button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="login" style="background-color: #91d9ff;font-family: 'IBM Plex Sans', sans-serif;height: 90%;">
            <div class="row">
                <div class="col-md-5 login-left" style="margin-top: 2%;right: 5">
                    <img id="roleImage" src="img/image/patient.jpg" alt="Role Image" style="width: 250px;height: auto;margin-left: 50px;">
                    <h3 style="margin-left: 40px;">Welcome</h3>
                </div>
                <div class="col-md-6 login-right" style="margin-top: 50px;left:5px">
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist" style="width: 100%;">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-role="patient" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Patient</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-role="doctor" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Doctor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-role="admin" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3  class="login-heading">Login as Patient</h3>
                            <form method="post" action="func1.php">
                                <div class="login-form">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Phone number" name="phone_number" onkeydown="return phoneNumberOnly(event);" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password1" required autocomplete="off"/>
                                    </div>
                                    <input type="submit" class="btnlogin" name="patsub" value="Login"/>
                                </div>
                                <div class="option" style="text-align:center">
                                    <a href="index1.php">Create a new account</a>
                                    <br>
                                    <a href="forgot-password/forgot-password.php">Forgot password</a>
                                </div>
                            </form>
                        </div>
            
                        <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <h3  class="login-heading">Login as Doctor</h3>
                            <form method="post" action="func2.php">
                                <div class="login-form">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email" name="email1" onkeydown="return emailOnly(event);" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password3" required/>
                                    </div>
                                    <input type="submit" class="btnlogin" name="docsub" value="Login"/>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade show" id="admin" role="tabpanel" aria-labelledby="profile-tab">
                            <h3  class="login-heading">Login as Admin</h3>
                            <form method="post" action="func3.php">
                                <div class="login-form">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Username" name="username1" onkeydown="return alphaOnly(event);" required/>
                                    </div>                           
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password2" required/>
                                    </div>                    
                                    <input type="submit" class="btnlogin" name="adsub" value="Login"/>
                                </div>
                            </form>
                            <!-- <script>
                                window.onload = function() {
                                    // Clear the username and password fields when the page is loaded
                                    document.getElementById('username1').value = '';
                                    document.getElementById('password2').value = '';
                                };
                            </script> -->
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const roleImage = document.getElementById('roleImage');
                const tabs = document.querySelectorAll('.nav-link');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const role = this.getAttribute('data-role');

                        // Di chuyển hình ảnh ra ngoài màn hình (sang trái hoặc sang phải)
                        roleImage.classList.add('move-left');

                        setTimeout(() => {
                            switch (role) {
                                case 'patient':
                                    roleImage.src = 'img/image/patient.jpg';
                                    break;
                                case 'doctor':
                                    roleImage.src = 'img/image/doctor.jpg';
                                    break;
                                case 'admin':
                                    roleImage.src = 'img/image/admin.jpg';
                                    break;
                                default:
                                    roleImage.src = 'img/image/patient.jpg';
                            }
                            roleImage.classList.remove('move-left');
                            roleImage.classList.add('move-right');
                        }, 500); 

                        setTimeout(() => {
                            roleImage.classList.remove('move-right');
                        }, 1000); // Thời gian trễ để hiệu ứng di chuyển vào hoàn tất
                    });
                });
            </script>
        </div>
    </body>

    <script>
        function alphaOnly(event) {
            var key = event.keyCode;
            return ((key >= 65 && key <= 90) || key == 8 || key == 32);
        };

        function checklen() {
            var pass1 = document.getElementById("password");  
            if(pass1.value.length<6){  
                alert("The password must be at least 6 characters. Please try again!");  
                return false;  
            }     
        }
        function phoneNumberOnly(event) {
            var key = event.keyCode;
            if (key === 8 || key === 9 || (key >= 48 && key <= 57)) { // Allow Backspace, Tab, and 0-9
                return true;
            }
            return false;
        }
        function numericOnly(event) {
            var key = event.keyCode;
            if (key === 8 || key === 9 || key === 37 || key === 39 || key === 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
                return true;
            }
            return false;
        }
        function emailOnly(event) {
            var key = event.keyCode;
            if ((key >= 65 && key <= 90) || // A-Z
                (key >= 97 && key <= 122) || // a-z
                (key >= 48 && key <= 57) || // 0-9
                key === 64 || // @
                key === 46 || // .
                key === 45 || // -
                key === 95 || // _
                key === 8 || // Backspace
                key === 9 || // Tab
                key === 37 || // Arrow Left
                key === 39 || // Arrow Right
                key === 46) { // Delete
                return true;
            }
            return false;
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</html>

  