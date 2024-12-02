<!-- Register as Patient -->

<html lang="en">
<head>
    <title>MedBook</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/image/MedBook_icon.jpeg" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="css/index.css">
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


    <div class="register" style="background-color: #91d9ff;font-family: 'IBM Plex Sans', sans-serif;height: 90%;">
        <div class="row">
            <div class="col-md-4 register-left animated-logo" style="margin-top: 10%;left: 10%">
                <img src="img/image/MedBook_icon.jpeg" alt="logo" style="width: 200px; height: auto"/>
                <h3 style="margin: 20px 0 0 40px;">Welcome</h3>
            </div>

            <div class="col-md-7 register-right" style="margin-top: 50px;left:5px">
                <div class="card-body">
                    <h3 class="register-heading">Register as Patient</h3>
                    <form method="post" action="func1.php">
                        <div class="row register-form">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="Full name" name="patient_name"  onkeydown="return numericOnly(event);" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="Address" name="address" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email" name="email"  />
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top: 5%;">
                                    <div class="form-group">
                                        <div class="maxl">
                                            <label class="radio inline"> 
                                                <input type="radio" name="gender" value="Male" checked>
                                                <span> Male </span> 
                                            </label>
                                            <label class="radio inline"> 
                                                <input type="radio" name="gender" value="Female">
                                                <span> Female </span> 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="DOB" class="form-control" placeholder="D.O.B" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" minlength="10" maxlength="11" name="phone_number" class="form-control" onkeydown="return phoneNumberOnly(event);" placeholder="Phone number" pattern="[0-9]{10,11}" required />
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:-10">
                                    <div class="form-group">
                                        <input type="password" class="form-control"  id="password" placeholder="Password" name="password"  onkeyup='checkPass();' required/>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:-10">
                                    <div class="form-group">
                                        <input type="password" class="form-control"  id="con_password" placeholder="Confirm password" name="con_password"  onkeyup='checkPass();' required/><span id='message'></span>
                                    </div>
                                </div>
                                <input type="submit" class="btnRegister" name="patsub1" onclick="return checklen();" value="Register"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var checkPass = function() {
            if (document.getElementById('password').value == document.getElementById('con_password').value) {
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
        function phoneNumberOnly(event) {
            var key = event.keyCode;
            if (key === 8 || key === 9 || (key >= 48 && key <= 57)) { // Allow Backspace, Tab, and 0-9
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
        function numericOnly(event) {
            var key = event.keyCode;
            if (key === 8 || key === 9 || key === 37 || key === 39 || key === 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
                return true;
            }
            return false;
        }
    </script>





    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>
</html>