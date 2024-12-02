<!-- Test log in nha -->

<html>
<head>
	<title>MedBook</title>
	<link rel="shortcut icon" type="image/x-icon" href="img/MedBook_icon.jpg" />
    <link rel="stylesheet" type="text/css" href="css/style1.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <style >
        .form-control {
        border-radius: 0.75rem;
    }
    </style>

    <script>
        var checkPass = function() {
            if (document.getElementById('password').value == document.getElementById('cpassword').value) {
                document.getElementById('message').style.color = '#5dd05d';
                document.getElementById('message').innerHTML = 'Matched';
            } else {
                document.getElementById('message').style.color = '#f55252';
                document.getElementById('message').innerHTML = 'Not Matching';
            }
        }

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
    </script>
</head>

<!------ Include the above in your HEAD tag ---------->
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" >
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="#" style="margin-top: 10px;margin-left:-65px;font-family: 'IBM Plex Sans', sans-serif;"><h4><i class="fa fa-user-plus" aria-hidden="true"></i>MedBook</h4></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link js-scroll-trigger" href="index.php" style="color: white;font-family: 'IBM Plex Sans', sans-serif;"><h6>Home</h6></a>
                </li>
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link js-scroll-trigger" href="aboutus.html" style="color: white;font-family: 'IBM Plex Sans', sans-serif;"><h6>About us</h6></a>
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container register" style="font-family: 'IBM Plex Sans', sans-serif;">
        <div class="row">
            <div class="col-md-3 register-left" style="margin-top: 10%;right: 5%">
                <img src="img/MedBook_icon.jpg" alt="logo icon"/>
                <h3>Welcome</h3> 
            </div>
            <div class="col-md-9 register-right" style="margin-top: 40px;left: 80px;">
                <!-- Role bar -->
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist" style="width: 40%;">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#doctor" role="tab" aria-controls="doctor" aria-selected="false">Doctor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Login as Patient</h3>
                        <form method="post" action="func1.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Phone number*" name="phonenumber" onkeydown="return alphaOnly(event);" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password*" name="password1" required/>
                                    </div>        
                                    <input type="submit" class="btnRegister" name="patsub" value="Login"/>
                                </div>
                                <a style="color: #000000" href="forgot.php">Forgot password</a>
                                <a href="register.php">Create a new account</a>        
                            </div>
                            
                        </form>
                    </div>

                    <div class="tab-pane fade show" id="doctor" role="tabpanel" aria-labelledby="profile-tab">
                        <h3  class="register-heading">Login as Doctor</h3>
                        <form method="post" action="func2.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email*" name="email" onkeydown="return alphaOnly(event);" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password*" name="password2" required/>
                                    </div>        
                                    <input type="submit" class="btnRegister" name="docsub" value="Login"/>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade show" id="admin" role="tabpanel" aria-labelledby="profile-tab">
                        <h3  class="register-heading">Login as Admin</h3>
                        <form method="post" action="func3.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Username*" name="username" onkeydown="return alphaOnly(event);" required/>
                                    </div>        
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password*" name="password3" required/>
                                    </div>    
                                    <input type="submit" class="btnRegister" name="adsub" value="Login"/>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    
</html>

  