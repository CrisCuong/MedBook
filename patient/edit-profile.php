<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="../css/style1.css">
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
    </head>
  
    <?php 
        include('../func1.php');
        // Kết nối cơ sở dữ liệu và lấy thông tin bệnh nhân
        $patient_id = $_SESSION['patient_id'];
        $patient_name = $_SESSION['patient_name'];

        // Truy vấn dữ liệu bệnh nhân
        $sql = "SELECT patient_name, DOB, email, phone_number, gender, address FROM patient WHERE patient_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();

        $DOB = isset($patient['DOB']) ? date('Y-m-d', strtotime($patient['DOB'])) : '';
        $gender = $patient['gender'] ?? 'Male';
    ?>

    <body style="background-color: #91d9ff; margin-top: 120px">
        <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #fff">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="../patient-panel.php" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-color:#60c2fe"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
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
                        <a class="dropdown-item" href="change-password.php">Change password</a>
                        <a class="dropdown-item" href="#" onclick="clearTabAndLogout()">Logout</a>
                    </div>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif;"> Welcome <?php echo $patient_name ?>!</h3>
            <div class="edit-profile">
                <div class="row justify-content-center">
                    <div class="col-md-8 cell-body">
                        <div class="body-content">        
                            <form id="profile-form" method="POST" action="update-profile.php">
                                <h3 class="heading" style="text-align: center;color: #0062cc;font-size: 30px;margin-bottom: 35px">Edit profile</h3>
                                <div class="form-group">
                                    <label for="patient_name">Full name:</label>
                                    <input type="text" id="patient_name" name="patient_name" value="<?php echo htmlspecialchars($patient['patient_name']); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <div class="maxl">
                                        <label class="radio inline">
                                            <input type="radio" name="gender" value="Male" <?php echo $gender === 'Male' ? 'checked' : ''; ?> disabled>
                                            <span> Male </span> 
                                        </label>
                                        <label class="radio inline">
                                            <input type="radio" name="gender" value="Female" <?php echo $gender === 'Female' ? 'checked' : ''; ?> disabled>
                                            <span> Female </span> 
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="DOB">Date of Birth:</label>
                                    <input type="date" id="DOB" name="DOB" value="<?php echo htmlspecialchars($patient['DOB']); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone number:</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone_number']); ?>" disabled>
                                </div>
                                <div class="button-group">
                                    <a href="../patient-panel.php" class="btn btn-secondary">Back</a>
                                    <button type="button" id="edit-btn" class="btn btn-primary" onclick="toggleEditMode()">Edit</button>
                                    <button type="submit" id="save-btn" class="btn btn-primary" style="display: none;">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <script>
        function toggleEditMode() {
            // Enable input fields
            document.querySelectorAll('#profile-form input').forEach(input => {
                input.disabled = !input.disabled;
            });

            // Toggle buttons
            document.getElementById('edit-btn').style.display = 
                document.getElementById('edit-btn').style.display === 'none' ? 'inline-block' : 'none';
            document.getElementById('save-btn').style.display = 
                document.getElementById('save-btn').style.display === 'none' ? 'inline-block' : 'none';
        }

        function clearTabAndLogout() {
            localStorage.removeItem('activeTab'); // Clear the active tab from localStorage
            location.href = '../logout.php'; // Proceed with the logout
        }
    </script>
    </body>
</html>


