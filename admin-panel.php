<!-- Panel của Admin -->

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style3.css">
    <!-- Bootstrap chuyển tab -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>

  <?php
    include('func3.php');
    // Kiểm tra session, nếu không tồn tại, chuyển hướng về trang login
    if (!isset($_SESSION['admin_id'])) {
      header("Location: index.php");
      exit();
    }
    include('admin-func.php');

    $admin_name = $_SESSION['admin_name'];
  ?>

  <body style="background-color: #91d9ff;">
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #fff">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
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
                <a class="dropdown-item" href="admin/change-password.php">Change password</a>
                <a class="dropdown-item" href="#" onclick="clearTabAndLogout()">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif; margin-top: 100px;"> Welcome <?php echo $admin_name ?>! </h3>
      <div class="row">
        <div class="col-md-4" style="max-width:25%; margin-top: 2%">
          <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" href="#list-dash" role="tab" aria-controls="home" data-toggle="list">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="#list-doc" id="list-doc-list" role="tab" data-toggle="list" aria-controls="home">Doctor List</a>
            <a class="list-group-item list-group-item-action" href="#list-pati" id="list-pati-list" role="tab" data-toggle="list" aria-controls="home">Patient List</a>
            <a class="list-group-item list-group-item-action" href="#list-appo" id="list-appo-list" role="tab" data-toggle="list" aria-controls="home">Appointment List</a>
            <a class="list-group-item list-group-item-action" href="#list-spec" id="list-spec-list" role="tab" data-toggle="list" aria-controls="home">Speciality List</a>
          </div><br>
        </div>

        <div class="col-md-8" style="max-width:25%;margin-top: 2%; ">
          <div class="tab-content" id="nav-tabContent" style="width: 1050px;">
            <!-- Dashboard -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
              <div class="container-content bg-white" style="width: 1000px;">
                <h4 id="dashListHeading" style="margin: 0px 0px 30px 50px;">Dashboard</h4> 
                <div id="dashListContainer">

                  <!-- Summary Cards Row -->
                  <div class="row mb-4">
                    <div class="col-md-3">
                      <div class="card text-center bg-light border-0 shadow-sm">
                        <div class="card-body">
                          <h6 class="card-title">Total Doctors</h6>
                          <p class="card-text" id="totalDoctors">0</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card text-center bg-light border-0 shadow-sm">
                        <div class="card-body">
                          <h6 class="card-title">Total Patients</h6>
                          <p class="card-text" id="totalPatients">0</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card text-center bg-light border-0 shadow-sm">
                        <div class="card-body">
                          <h6 class="card-title">Appointments Today</h6>
                          <p class="card-text" id="appointmentsToday">0</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="card text-center bg-light border-0 shadow-sm">
                        <div class="card-body">
                          <h6 class="card-title">Monthly Registrations</h6>
                          <p class="card-text" id="monthlyRegistrations">0</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Reports and Analytics Section -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="card border-0 shadow-sm">
                        <div class="card-body">
                          <h5 class="card-title">Monthly Appointments Trend</h5>
                          <canvas id="appointmentsTrendChart"></canvas> <!-- Chart JS -->
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 shadow-sm">
                        <div class="card-body">
                          <h5 class="card-title">Active Users</h5>
                          <canvas id="activePatientsDoctorsChart"></canvas> <!-- Chart JS -->
                        </div>
                      </div>
                    </div>
                  </div>


                </div>
              </div> 
            </div>
            
            <!-- Doctor List -->
            <div class="tab-pane fade" id="list-doc" role="tabpanel" aria-labelledby="list-doc-list">
              <div class="container-content bg-white">
                <div class="row">
                  <div class="col-md-2">
                    <button class="add-new-btn float-left" id="addNewButton">Add new</button>  
                  </div>
                  <div class="col-md-6">
                    <h4 id="doctorListHeading" style="margin: 0px 100px 30px 0px;">Doctor List</h4> 
                  </div>
                  <div class="col-md-4">
                    <div class="search-container">
                      <input type="email" id="docSearch" placeholder="Search by Email">
                    </div>
                    <script>
                      document.getElementById('docSearch').addEventListener('keyup', function() {
                        var input = this.value.toLowerCase();
                        var rows = document.querySelectorAll('#doctorListTable tbody tr');
                        rows.forEach(function(row) {
                          var email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();   // email column 3
                          if (email.includes(input)) {
                            row.style.display = ''; 
                          } else {
                            row.style.display = 'none'; 
                          }
                        });
                        if (input === '') {
                          rows.forEach(function(row) {
                            row.style.display = ''; 
                          });
                        }
                      });
                    </script>
                  </div>
                </div>

                <!-- Add doctor form -->
                <div id="addNewDoctorForm" style="display: none;">
                  <form id="doctorForm" method="post" action="admin-func.php" enctype="multipart/form-data">
                    <div class="form-grid">
                      <div class="form-item label">Full Name:</div>
                      <div class="form-item field-data">
                        <input type="text" name="doctor_name" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">License:</div>
                      <div class="form-item field-data">
                        <input type="text" name="business_license" required>
                      </div>
                      <div class="form-item label">Speciality:</div>
                      <?php
                      // Sắp xếp mảng $specialities theo tên chuyên môn
                      usort($specialities, function($a, $b) {
                          return strcmp($a['speciality_name'], $b['speciality_name']);
                      });
                      ?>
                      <div class="form-item field-data">
                        <select name="speciality" style="width: 100%;height: 44px;" required>
                          <option value="">Select Speciality</option>
                          <?php foreach ($specialities as $speciality): ?>
                            <option value="<?php echo $speciality['speciality_id']; ?>">
                              <?php echo $speciality['speciality_name']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-item label"></div>
                      <div class="form-item field-data"></div>
                      <div class="form-item label">Address:</div>
                      <div class="form-item field-data">
                        <input type="text" name="address" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Profile Picture:</div>
                      <div class="form-item field-data">
                        <input type="file" name="profile_pic" accept="image/*">
                      </div>
                      <div class="form-item label">Email:</div>
                      <div class="form-item field-data">
                        <input type="email" name="email" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Phone:</div>
                      <div class="form-item field-data">
                        <input type="text" name="phone">
                      </div>
                      <div class="form-item label">Password:</div>
                      <div class="form-item field-data">
                        <input type="password" name="password" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Confirm Password:</div>
                      <div class="form-item field-data">
                        <input type="password" name="confirm_password" required>
                      </div>
                    </div >
                    <div class="form-footer">
                      <input type="hidden" name="action" value="add_doctor">
                      <button type="submit" class="add-new-btn">Add new</button>
                    </div>
                  </form>
                </div>

                <!-- Update doctor -->
                <div id="updateDoctorForm" style="display: none;">
                  <form id="doctorUpdateForm" method="post" action="admin-func.php" enctype="multipart/form-data">
                    <div class="form-grid">
                      <input type="hidden" name="doctor_id" value="">
                      <input type="hidden" id="current_profile_pic_hidden" name="current_profile_pic" value="">
                      <div class="form-item label">Full Name:</div>
                      <div class="form-item field-data">
                        <input type="text" id="update_doctor_name" name="doctor_name" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">License:</div>
                      <div class="form-item field-data">
                        <input type="text" id="update_business_license" name="business_license" required>
                      </div>
                      <div class="form-item label">Speciality:</div>
                      <?php
                      // Sắp xếp mảng $specialities theo tên chuyên môn
                      usort($specialities, function($a, $b) {
                          return strcmp($a['speciality_name'], $b['speciality_name']);
                      });
                      ?>
                      <div class="form-item field-data">
                        <select id="update_speciality" name="speciality" style="width: 100%;height: 44px;" required>
                          <option value="">Select Speciality</option>
                          <?php foreach ($specialities as $speciality): ?>
                            <option value="<?php echo $speciality['speciality_id']; ?>">
                              <?php echo $speciality['speciality_name']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-item label"></div>
                      <div class="form-item field-data"></div>
                      <div class="form-item label">Address:</div>
                      <div class="form-item field-data">
                        <input type="text" id="update_address" name="address" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Profile Picture:</div>
                      <div class="form-item field-data">
                        <input type="file" id="update_profile_pic" name="profile_pic" accept="image/*">
                        <img id="current_profile_pic" src="" style="max-width: 100px; max-height: 100px;">
                      </div>
                      <div class="form-item label">Email:</div>
                      <div class="form-item field-data">
                        <input type="email" id="update_email" name="email" required>
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Phone:</div>
                      <div class="form-item field-data">
                        <input type="text" id="update_phone" name="phone">
                      </div>
                      <div class="form-item label">Password:</div>
                      <div class="form-item field-data">
                        <input type="password" id="update_password" name="password">
                      </div>
                      <div class="form-item label" style="margin-left: 40px">Confirm Password:</div>
                      <div class="form-item field-data">
                        <input type="password" id="update_confirm_password" name="confirm_password">
                      </div>
                      <div class="form-item label"></div>
                    </div>
                    <div class="form-footer">
                      <input type="hidden" id="update_doctor_id" name="doctor_id">
                      <input type="hidden" name="action" value="update_doctor">
                      <button type="submit" class="upd-btn">Update</button>
                    </div>
                  </form>
                </div>

                <!-- Doctor list -->
                <div id="doctorListContainer">
                  <table id="doctorListTable">
                    <thead>
                      <tr class="title">
                        <!-- <th>Doctor ID</th> -->
                        <th>Doctor Name</th>
                        <th>Speciality</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                            $checked = $row['status'] ? 'checked' : '';
                            echo "<tr>";
                            // echo "<td>" . $row["doctor_id"] . "</td>";
                            echo "<td>" . $row["doctor_name"] . "</td>";
                            echo "<td>" . $row["speciality_name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "
                              <td style='text-align: center;'>
                                <div class='custom-control custom-switch toggle'>
                                  <input type='checkbox' class='custom-control-input' id='active-" . $row["doctor_id"] . "' name='doctor[" . $row["doctor_id"] . "][status]' $checked onclick='confirmStatusChange(" . $row["doctor_id"] . ", this)'>
                                  <label class='custom-control-label' for='active-" . $row["doctor_id"] . "'></label>
                                </div>
                              </td>";
                            echo "<td style='text-align: center;'>
                              <button class='update-btn' data-doctor-id='" . $row["doctor_id"] . "'>Update</button>
                              <button class='delete-btn' data-doctor-id='" . $row["doctor_id"] . "'>Delete</button>
                            </td>";
                            echo "</tr>";
                          }
                        } else {
                          echo "<tr><td colspan='6'>No doctors found</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>

              </div>
              <script>
                // Xác nhận trước khi cập nhật trạng thái
                function confirmStatusChange(doctorId, checkbox) {
                  const isChecked = checkbox.checked;
                  const confirmation = confirm("Do you want to change the status?");
                  
                  if (confirmation) {
                    // Make an AJAX request to update the status in the database
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "admin-func.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                      if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Status updated successfully!");
                        localStorage.setItem('statusUpdate', 'updated');
                      }
                    };
                    xhr.send(`action=update_status&doctor_id=${doctorId}&status=${isChecked ? 1 : 0}`);
                  } else {
                    // If not confirmed, revert the checkbox state
                    checkbox.checked = !isChecked;
                  }
                }
              </script>
            </div>

            <!-- Patient List -->
            <div class="tab-pane fade" id="list-pati" role="tabpanel" aria-labelledby="list-pati-list">
              <div class="container-content bg-white">
                <div class="row">
                  <div class="col-md-6">
                    <h4 id="patientListHeading" style="margin: 0px 0px 30px 50px;">Patient List</h4> 
                  </div>
                  <div class="col-md-6">
                    <div class="search-container">
                      <input type="text" id="phoneSearch" placeholder="Search by Phone Number">
                    </div>
                    <script>
                      document.getElementById('phoneSearch').addEventListener('keyup', function() {
                        var input = this.value.toLowerCase();
                        var rows = document.querySelectorAll('#patientListTable tbody tr');

                        rows.forEach(function(row) {
                          var phoneNumber = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                          if (phoneNumber.includes(input)) {
                            row.style.display = ''; // Hiển thị hàng
                          } else {
                            row.style.display = 'none'; // Ẩn hàng
                          }
                        });
                        // Kiểm tra nếu không có dữ liệu trong input thì hiển thị lại tất cả
                        if (input === '') {
                          rows.forEach(function(row) {
                            row.style.display = ''; // Hiển thị lại tất cả hàng
                          });
                        }
                      });
                    </script>
                  </div>
                </div>
                <div id="patientListContainer">
                  <table id="patientListTable">
                    <thead>
                      <tr class="title">
                        <th>Patient Name</th>
                        <th>D.O.B</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php                       
                        // Display patients
                        if (isset($result) && $result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["DOB"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["phone_number"]) . "</td>";
                            echo "<td style='text-align: center;'>
                              <button class='delete-btn-patient' data-patient-id='" . $row["patient_id"] . "'>Delete</button>
                                  </td>";
                            echo "</tr>";
                          }
                        } else {
                            echo "<tr><td colspan='6'>No patients found</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>

              </div>  
            </div>

            <!-- Appointment List -->
            <div class="tab-pane fade" id="list-appo" role="tabpanel" aria-labelledby="list-appo-list">
              <div class="container-content bg-white">
                <h4 id="appoListHeading" style="margin: 0px 0px 30px 50px;">Appointment List</h4> 
                <div id="appoListContainer">
                  <table id="appoListTable">
                    <thead>
                      <tr class="title">
                        <th style="width: 95px">Appoint. ID</th>
                        <th>Patient ID</th>
                        <th>Doctor ID</th>
                        <th>Date</th>
                        <th>Timeframe</th>
                        <th style="width: 220px">Appointment Status</th>
                        <th style="width: 120px">Patient Status</th>
                        <th style="width: 120px">Doctor Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Appointment list will be inserted here -->
                    </tbody>
                  </table>
                </div>
              </div> 
            </div>

            <!-- Speciality List -->
            <div class="tab-pane fade" id="list-spec" role="tabpanel" aria-labelledby="list-spec-list">
              <div class="container-content bg-white">
                <div class="row">
                  <div class="col-md-2">
                    <button class="add-new-btn float-left" id="addNewSpecButton">Add new</button>  
                  </div>
                  <div class="col-md-6">
                    <h4 id="specListHeading" style="margin: 0px 0px 30px 50px;">Speciality List</h4> 
                  </div>
                  <div class="col-md-4">
                    <div class="search-container">
                      <input type="text" id="specSearch" placeholder="Search by Speciality Name">
                    </div>
                    <script>
                      document.getElementById('specSearch').addEventListener('keyup', function() {
                        var input = this.value.toLowerCase();
                        var rows = document.querySelectorAll('#specListTable tbody tr');

                        rows.forEach(function(row) {
                          var specialityName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                          if (specialityName.includes(input)) {
                            row.style.display = ''; // Hiển thị hàng
                          } else {
                            row.style.display = 'none'; // Ẩn hàng
                          }
                        });
                        // Kiểm tra nếu không có dữ liệu trong input thì hiển thị lại tất cả
                        if (input === '') {
                          rows.forEach(function(row) {
                            row.style.display = ''; // Hiển thị lại tất cả hàng
                          });
                        }
                      });
                    </script>
                  </div>
                </div>
                
                <!-- Add spec form -->
                <div id="addNewSpecForm" style="display: none;">
                  <form id="specForm" method="post" action="admin-func.php" enctype="multipart/form-data">
                    <div class="row"  style="width: 800px;margin-left: 150px;margin-top: 50px; ">
                      <div class="col-md-8">
                        <div class="form-item field-data">
                          <input type="text" name="speciality_name" id="specialityInput" placeholder="Enter the new speciality" required>
                        </div>
                      </div>
                      <div class="col-md-4 form-footer-spec">
                        <input type="hidden" name="action" value="add_spec">
                        <button type="submit" class="add-new-btn">Add new</button>
                      </div>
                    </div>
                  </form>
                </div>

                <!-- Speciality List -->
                <div id="specListContainer">
                  <table id="specListTable">
                    <thead>
                      <tr class="title">
                        <th>Speciality</th>
                        <!-- <th>Action</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php                       
                        // Display speciality
                        if (isset($result) && $result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["speciality_name"]) . "</td>";
                            // echo "<td style='text-align: center;'>
                            //   <button class='delete-btn-spec' data-spec-id='" . $row["speciality_id"] . "'>Delete</button>
                            //       </td>";
                            echo "</tr>";
                          }
                        } else {
                            echo "<tr><td colspan='6'>No specialities found</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div> 
            </div>
            

          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan tới tab Doctor List
        let currentView = 'dashList'; // Track the current view có thể đổi lại doctorList để test
        
        const addNewButton = document.getElementById('addNewButton');
        const updateButton = document.getElementById('updateButton'); 
        const doctorListTable = document.getElementById('doctorListTable');
        const addNewDoctorForm = document.getElementById('addNewDoctorForm');
        const updateDoctorForm = document.getElementById('updateDoctorForm');
        const addNewSpecForm = document.getElementById('addNewSpecForm');
        const addNewSpecButton = document.getElementById('addNewSpecButton');

        const dashListHeading = document.getElementById('dashListHeading');
        const dashListContainer = document.getElementById('dashListContainer');
        const doctorListHeading = document.getElementById('doctorListHeading');
        const doctorListContainer = document.getElementById('doctorListContainer');
        const patientListHeading = document.getElementById('patientListHeading');
        const patientListContainer = document.getElementById('patientListContainer');
        const appoListHeading = document.getElementById('appoListHeading');
        const appoListContainer = document.getElementById('appoListContainer');
        const specListHeading = document.getElementById('specListHeading');
        const specListContainer = document.getElementById('specListContainer');
        
        // Handle form submission
        function handleFormSubmit(event) {
          event.preventDefault();
          const form = event.target;
          const formData = new FormData(form);
          const xhr = new XMLHttpRequest();
          xhr.open("POST", 'admin-func.php', true);
          xhr.onload = function () {
            if (xhr.status === 200) {
              console.log(xhr.responseText);
              alert(xhr.responseText);
              if (xhr.responseText.includes('Adding a new doctor successfully.') || xhr.responseText.includes('Doctor updated successfully.')) {
                fetchDoctorList(() => {
                  document.getElementById("docSearch").value = "";
                  switchView('doctorList');
                  form.reset();
                });
              } else if (xhr.responseText.includes('Adding a new speciality successfully.')) {
                fetchSpecList(() => {
                  switchView('specialityList');
                });
              }
            } else {
              alert('An error occurred: ' + xhr.statusText);
            }
          };
          xhr.onerror = function () {
            alert('An error occurred during the request.');
          };
          xhr.send(formData);
        }

        // Add event listener to forms
        if (addNewDoctorForm) {
          addNewDoctorForm.addEventListener('submit', handleFormSubmit);
        }
        if (updateDoctorForm) {
          updateDoctorForm.addEventListener('submit', handleFormSubmit);
        }
        if (addNewSpecForm) {
          addNewSpecForm.addEventListener('submit', handleFormSubmit);
        }

        // Handle view switching
        function switchView(view) {

          if (view === 'dashList') {
            dashListContainer.style.display = 'block';
            doctorListContainer.style.display = 'none';
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            addNewDoctorForm.style.display = 'none';
            updateDoctorForm.style.display = 'none';
            addNewSpecForm.style.display = 'none';
            currentView = 'dashList';
          } else if (view === 'doctorList') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'block';
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            addNewDoctorForm.style.display = 'none';
            updateDoctorForm.style.display = 'none';
            addNewSpecForm.style.display = 'none';
            doctorListHeading.textContent = 'Doctor List';
            addNewButton.textContent = 'Add new';
            docSearch.style.display = "block";
            currentView = 'doctorList';
          } else if (view === 'addNewDoctor') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            addNewDoctorForm.style.display = 'block';
            updateDoctorForm.style.display = 'none';
            addNewSpecForm.style.display = 'none';
            doctorListHeading.textContent = 'Add a new doctor';
            addNewButton.textContent = 'Back';
            docSearch.style.display = "none";
            currentView = 'addNewDoctor';
          } else if (view === 'updateDoctor') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            addNewDoctorForm.style.display = 'none';
            updateDoctorForm.style.display = 'block';
            addNewSpecForm.style.display = 'none';
            doctorListHeading.textContent = 'Update doctor';
            docSearch.style.display = "none";
            addNewButton.textContent = 'Back';
            currentView = 'updateDoctor';
          } else if (view === 'patientList') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';  // Ẩn tab bác sĩ
            patientListContainer.style.display = 'block'; // Hiển thị tab bệnh nhân
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            currentView = 'patientList';
          }  else if (view === 'appointmentList') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';  // Ẩn tab bác sĩ
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'block';
            specListContainer.style.display = 'none';
            currentView = 'appointmentList';
          } else if (view === 'specialityList') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';  // Ẩn tab bác sĩ
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'block';
            addNewDoctorForm.style.display = 'none';
            updateDoctorForm.style.display = 'none';
            addNewSpecForm.style.display = 'none';
            specListHeading.textContent = 'Speciality List';
            addNewSpecButton.textContent = 'Add new';
            currentView = 'specialityList';
          } else if (view === 'addNewSpec') {
            dashListContainer.style.display = 'none';
            doctorListContainer.style.display = 'none';
            patientListContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            specListContainer.style.display = 'none';
            addNewDoctorForm.style.display = 'none';
            updateDoctorForm.style.display = 'none';
            addNewSpecForm.style.display = 'block';
            specListHeading.textContent = 'Add a new speciality';
            addNewSpecButton.textContent = 'Back';
            currentView = 'addNewSpec';
          }

          console.log('Switching to view:', view);
          console.log('dashListContainer display:', dashListContainer.style.display);
          console.log('doctorListContainer display:', doctorListContainer.style.display);
          console.log('patientListContainer display:', patientListContainer.style.display);
          console.log('appoListContainer display:', appoListContainer.style.display);
          console.log('specListContainer display:', specListContainer.style.display);
          console.log('currentView display:', currentView);
          
          // Cập nhật lịch sử với trạng thái mới
          history.replaceState({ view: view }, '', `#${view}`);
          // history.pushState({ view: view }, '', `?view=${view}`);
          // currentView = view;
        }

        // Xử lý sự kiện popstate khi nhấn Back
        window.addEventListener('popstate', function(event) {
          if (event.state && event.state.view) {
            switchView(event.state.view);
          } else {
            switchView('doctorList');
          }
        });

        // Function to handle Update button click
        document.querySelectorAll('.update-btn').forEach(button => {
          button.addEventListener('click', function() {
            const doctorId = this.getAttribute('data-doctor-id');
            fetch(`admin-func.php?id=${doctorId}`)
            .then(response => response.json())
            .then(data => {
              if (data.error) {
                console.error('Error fetching doctor details:', data.error);
                return;
              }

              // Populate the update form with the fetched data
              document.getElementById('update_doctor_name').value = data.doctor_name;
              document.getElementById('update_business_license').value = data.business_license;
              document.getElementById('update_speciality').value = data.speciality_id;
              document.getElementById('update_address').value = data.address;
              document.getElementById('update_email').value = data.email;
              document.getElementById('update_phone').value = data.phone;
              document.getElementById('update_doctor_id').value = data.doctor_id;
              document.getElementById('update_password').value = ''; // Mật khẩu trống khi load form
              document.getElementById('update_confirm_password').value = '';

              const profilePicElement = document.getElementById('current_profile_pic');
              const profilePicHiddenInput = document.getElementById('current_profile_pic_hidden');

              if (data.profile_pic) {
                profilePicElement.src = `${data.profile_pic}`;
                profilePicElement.style.display = 'inline-block';
                profilePicHiddenInput.value = data.profile_pic;
              } else {
                profilePicElement.style.display = 'none';
                profilePicHiddenInput.value = '';
              }
              document.getElementById('update_profile_pic').value = null;
              switchView('updateDoctor');
            })
            .catch(error => console.error('Error fetching doctor details:', error));
          });
        });

        // Add or update doctor successfully, go to doctor list
        addNewButton.addEventListener('click', function() {
          if (currentView === 'addNewDoctor' || currentView === 'updateDoctor') {
            switchView('doctorList');
          } else {
            switchView('addNewDoctor');
          }
        });

        if (updateButton) {
          updateButton.addEventListener('click', function() {
            switchView('updateDoctor');
          });
        }

        // Set default view
        switchView('dashList');

        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề chuyển tab
        const defaultTab = '#list-dash';
        const isLoggedIn = sessionStorage.getItem('isLoggedIn');
        
        function activateTab(tabId) {
          document.querySelectorAll('.tab-pane').forEach(function(tabContent) {
            tabContent.classList.remove('show', 'active');
          });
          document.querySelectorAll('.list-group-item-action').forEach(function(tabLink) {
            tabLink.classList.remove('active');
          });

          const tab = document.querySelector(`.list-group-item-action[href="${tabId}"]`);
          if (tab) {
            tab.classList.add('active');
            document.querySelector(tabId).classList.add('show', 'active');
          }
        }

        document.querySelectorAll('.list-group-item-action').forEach(function(tab) {
          tab.addEventListener('click', function(event) {
            event.preventDefault();
            const tabId = tab.getAttribute('href');
            localStorage.setItem('activeTab', tabId);
            // Kiểm tra tabId và gọi switchView với giá trị chính xác
            if (tabId === '#list-pati') {
              fetchPatientList();
              switchView('patientList');
            } else if (tabId === '#list-appo') {
              switchView('appointmentList');
            } else if (tabId === '#list-spec') {
              fetchSpecList();
              switchView('specialityList');
            } else if (tabId === '#list-doc') {
              fetchDoctorList();
              switchView('doctorList');
            } else {
              switchView('dashList');  // Mặc định là tab dash list
            }
            const currentUrl = window.location.href.split('#')[0] + tabId;
            history.replaceState(null, null, currentUrl);
          });
        });

        let activeTab = localStorage.getItem('activeTab');
        if (!isLoggedIn) {
          activeTab = defaultTab;
          sessionStorage.setItem('isLoggedIn', 'true');
        }

        if (activeTab) {
          if (activeTab === '#list-pati') {
            switchView('patientList');
          } else if (activeTab === '#list-appo') {
            switchView('appointmentList');
          } else if (activeTab === '#list-spec') {
            switchView('specialityList');
          } else if (activeTab === '#list-doctor') {
            switchView('doctorList');
          } else {
            switchView('dashList');
          }
        } else {
          switchView('dashList'); // Đảm bảo default là dashList nếu không có giá trị activeTab
        }

        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề tab Dashboard
        fetchDashboardData();




        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề tab Doctor List

        // Handle Delete button click for doctor list
        // document.querySelectorAll('.delete-btn').forEach(button => {
        //   button.addEventListener('click', function() {
        //     const doctorId = this.closest('tr').querySelector('.update-btn').getAttribute('data-doctor-id');
        //     const confirmation = confirm("Do you really want to delete this doctor?");

        //     if (confirmation) {
        //       // Make an AJAX request to delete the doctor
        //       const xhr = new XMLHttpRequest();
        //       xhr.open("POST", "admin-func.php", true);
        //       xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //       xhr.onload = function() {
        //         if (xhr.status === 200) {
        //           alert(xhr.responseText);
        //           fetchDoctorList();
        //         } else {
        //           alert('Failed to delete doctor: ' + xhr.statusText);
        //         }
        //       };
        //       xhr.send(`action=delete_doctor&doctor_id=${doctorId}`);
        //     }
        //   });
        // }); 

        function fetchDoctorList(callback) {
          const xhr = new XMLHttpRequest();
          xhr.open("GET", 'admin-func.php?fetchDoctors=1', true);
          xhr.onload = function () {
            if (xhr.status === 200) {
              doctorListTable.innerHTML = xhr.responseText;
              // Gán sự kiện sau khi bảng được cập nhật
              attachEventListeners();
              if (callback) callback();
            } else {
              alert('Failed to refresh the doctor list: ' + xhr.statusText);
            }
          };
          xhr.send();
        }

        // Khi reload lại doctor list
        function attachEventListeners() {
          // Gán sự kiện cho nút Update
          document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
              const doctorId = this.getAttribute('data-doctor-id');
              fetch(`admin-func.php?id=${doctorId}`)
              .then(response => response.json())
              .then(data => {
                if (data.error) {
                  console.error('Error fetching doctor details:', data.error);
                  return;
                }

                // Populate the update form with the fetched data
                document.getElementById('update_doctor_name').value = data.doctor_name;
                document.getElementById('update_business_license').value = data.business_license;
                document.getElementById('update_speciality').value = data.speciality_id;
                document.getElementById('update_address').value = data.address;
                document.getElementById('update_email').value = data.email;
                document.getElementById('update_phone').value = data.phone;
                document.getElementById('update_doctor_id').value = data.doctor_id;
                document.getElementById('update_password').value = ''; // Mật khẩu trống khi load form
                document.getElementById('update_confirm_password').value = '';

                const profilePicElement = document.getElementById('current_profile_pic');
                const profilePicHiddenInput = document.getElementById('current_profile_pic_hidden');

                if (data.profile_pic) {
                  profilePicElement.src = `${data.profile_pic}`;
                  profilePicElement.style.display = 'inline-block';
                  profilePicHiddenInput.value = data.profile_pic;
                } else {
                  profilePicElement.style.display = 'none';
                  profilePicHiddenInput.value = '';
                }
                document.getElementById('update_profile_pic').value = null;
                switchView('updateDoctor');
              })
              .catch(error => console.error('Error fetching doctor details:', error));
            });
          });

          // Gán sự kiện cho nút Delete doctor
          document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
              const doctorId = this.closest('tr').querySelector('.update-btn').getAttribute('data-doctor-id');
              const confirmation = confirm("Do you want to delete this doctor and all related data? You will not be able to recover the data.");

              if (confirmation) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "admin-func.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                  if (xhr.status === 200) {
                    alert(xhr.responseText);
                    fetchDoctorList();
                  } else {
                    alert('Failed to delete doctor: ' + xhr.statusText);
                  }
                };
                xhr.send(`action=delete_doctor&doctor_id=${doctorId}`);
              }
            });
          });
        }


        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề tab Patient List

        function fetchPatientList(callback) {
          const xhr = new XMLHttpRequest();
          xhr.open("GET", 'admin-func.php?fetchPatients', true);
          xhr.onload = function () {
            if (xhr.status === 200) {
              document.getElementById('patientListTable').innerHTML = xhr.responseText;
              attachDeletePatientEvents();
              if (typeof callback === 'function') {
                  callback();
              }
            } else {
              alert('An error occurred: ' + xhr.statusText);
            }
          };
          xhr.onerror = function () {
            alert('An error occurred during the request.');
          };
          xhr.send();
        }

        // Hàm format date sang dd/mm/yyyy
        function formatDate(dateString) {
          const date = new Date(dateString);
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
          const year = date.getFullYear();
          return `${day}/${month}/${year}`;
        }

        function attachDeletePatientEvents() {
          document.querySelectorAll('.delete-btn-patient').forEach(button => {
            button.addEventListener('click', function() {
              const patientId = this.getAttribute('data-patient-id');
              if (confirm('Do you want to delete this patient? You will not be able to recover the data.')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'admin-func.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                  if (xhr.status === 200) {
                    alert(xhr.responseText);
                    fetchPatientList();  // Fetch patient list again after deletion
                  } else {
                    alert('Failed to delete patient: ' + xhr.statusText);
                  }
                };
                xhr.send(`action=delete_patient&patient_id=${patientId}`);
              }
            });
          });
        }


        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề tab Appointment List
        document.getElementById('list-appo-list').addEventListener('click', function () {
          fetchAppointments();
        });

        function fetchAppointments() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'admin-func.php?fetchAppointments', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.querySelector('#appoListTable tbody').innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to fetch appointments');
                }
            };
            xhr.send();
        }

        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề tab Speciality List
        function fetchSpecList(callback) {
          const xhr = new XMLHttpRequest();
          xhr.open("GET", 'admin-func.php?fetchSpecs', true);
          xhr.onload = function () {
            if (xhr.status === 200) {
              document.getElementById('specListTable').innerHTML = xhr.responseText;
              // attachDeleteSpecEvents();
              if (typeof callback === 'function') {
                  callback();
              }
            } else {
              alert('An error occurred: ' + xhr.statusText);
            }
          };
          xhr.onerror = function () {
            alert('An error occurred during the request.');
          };
          xhr.send();
        }

        /*// Delete speciality
        function attachDeleteSpecEvents() {
          document.querySelectorAll('.delete-btn-spec').forEach(button => {
            button.addEventListener('click', function() {
              const specId = this.getAttribute('data-spec-id');
              if (confirm('Are you sure you want to delete this speciality?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'admin-func.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                  if (xhr.status === 200) {
                    alert(xhr.responseText);
                    fetchSpecList();  // Fetch speciality list again after deletion
                  } else {
                    alert('Failed to delete speciality: ' + xhr.statusText);
                  }
                };
                xhr.send(`action=delete_spec&spec_id=${specId}`);
              }
            });
          });
        }*/

        // Click add new button ở speciality list
        addNewSpecButton.addEventListener('click', function() {
          if (currentView === 'specialityList') {
            document.getElementById("specSearch").style.display = "none";
            switchView('addNewSpec');
            document.getElementById("specialityInput").value = "";
          } else {
            switchView('specialityList');
          }
        });

      });

      // Script bên dưới liên quan đến các thống kê Dashboard
      function fetchDashboardData() {
        fetch('admin/fetch-dashboard.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error("Error:", data.error);
            return;
          }
          // Update Summary Cards
          document.getElementById('totalDoctors').textContent = data.totalDoctors;
          document.getElementById('totalPatients').textContent = data.totalPatients;
          document.getElementById('appointmentsToday').textContent = data.appointmentsToday;
          document.getElementById('monthlyRegistrations').textContent = data.monthlyRegistrations;
          // Update Appointments Trend Chart
          updateChart('appointmentsTrendChart', data.appointmentsTrend, 'Appointments Trend');
          // Update Active Patients Chart
          updateActivePatientsAndDoctorsChart(data.activePatients, data.activeDoctors);
        })
        .catch(error => console.error("Fetch Error:", error));
      }

      function updateChart(chartId, chartData, label) {
        const ctx = document.getElementById(chartId).getContext('2d');
        const labels = chartData.map(item => item.month);
        const values = chartData.map(item => item.count);
        if (ctx.chart) {
          ctx.chart.destroy();
        }
        ctx.chart = new Chart(ctx, {
          type: 'line', // You can change to 'bar' if needed
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: values,
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      }

      function updateActivePatientsAndDoctorsChart(activePatients, activeDoctors) {
        const ctx = document.getElementById('activePatientsDoctorsChart').getContext('2d');
        const labels = activePatients.map(item => item.month);
        const patientValues = activePatients.map(item => item.count);
        const doctorValues = activeDoctors.map(item => item.count);
        if (ctx.chart) {
          ctx.chart.destroy();
        }
        ctx.chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Patients',
                data: patientValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
              },
              {
                label: 'Doctors',
                data: doctorValues,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 1
              }
            ]
          },
          options: {
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      }


      // Clear activeTab when logging out
      function clearTabAndLogout() {
        localStorage.removeItem('activeTab');
        location.href = 'logout.php';
      }
    </script>
  </body>
</html>