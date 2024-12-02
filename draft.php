<!-- appointment list của doctor-panel -->
 
<table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Appointment ID</th>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Patient ID</th>
                    <th scope="col">Appointment Date</th>
                    <th scope="col">Appointment Time</th>
                    <th scope="col">Current Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                    include('connection.php');
                    global $con;
                    $patient_id = $_SESSION['patient_id'];
                    // $doctor_id = $_SESSION['doctor_id'];
                    // $doctor_name = $_SESSION['doctor_name'];
                    $query = "select appointment_id,patient_id,timeframe_id,patient_status,doctor_status from appointment where patient_id='$patient_id';";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
                  ?>
                    <tr>
                      <td><?php echo $row['pid'];?></td>
                      <td><?php echo $row['ID'];?></td>
                      <td><?php echo $row['fname'];?></td>
                      <td><?php echo $row['lname'];?></td>
                      <td><?php echo $row['gender'];?></td>
                      <td><?php echo $row['email'];?></td>
                      <td><?php echo $row['contact'];?></td>
                      <td><?php echo $row['appdate'];?></td>
                      <td><?php echo $row['apptime'];?></td>
                      <td>
                        <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                        {
                          echo "Active";
                        }
                        if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
                        {
                          echo "Canceled by Patient";
                        }
                        if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
                        {
                          echo "Canceled by You";
                        }
                        ?>
                      </td>
                      <td>
                        <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                        { ?>
                          <a href="doctor-panel.php?ID=<?php echo $row['ID']?>&cancel=update" 
                            onClick="return confirm('Are you sure you want to cancel this appointment ?')"
                            title="Cancel Appointment" tooltip-placement="top" tooltip="Remove"><button class="btn btn-danger">Cancel</button></a>
                          <?php } else {
                            echo "Cancelled";
                          } ?> 
                        </td>
                        <td>
                          <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                          { ?>
                          <a href="procribe.php?pid=<?php echo $row['pid']?>&ID=<?php echo $row['ID']?>&fname=<?php echo $row['fname']?>&lname=<?php echo $row['lname']?>&appdate=<?php echo $row['appdate']?>&apptime=<?php echo $row['apptime']?>"
                          tooltip-placement="top" tooltip="Remove" title="procribe">
                          <button class="btn btn-success">procibe</button></a>
                          <?php } else {
                            echo "-";
                          } ?>  
                        </td>
                      </td>
                    </tr></a>
                  <?php } ?>
                </tbody>
              </table>
              <br>


<!-- Book appointment của patient-panel -->
<div class="container-content bg-white" >
                <div class="row">
                  <div class="col-md-6">
                    <h3>Patient Profile</h3>
                  </div>
                  <div class="col-md-6">
                    <form method="get" action="" id="patient-search">
                      <div class="input-group">
                        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter the phone number" required>
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-outline-secondary">
                            <i class="fa fa-search"></i>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <?php
                  if (isset($_GET['phone_number'])){
                    $phone_number = $_GET['phone_number'];
                    include('connection.php');
                    $sql = "SELECT patient_name, gender, address, email, patient_id, DOB, phone_number FROM patient WHERE phone_number = ?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("s", $phone_number);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0){
                      echo "<div class='content-dash'>";
                      while($row = $result->fetch_assoc()){
                        // Calculate age
                        $DOB = new DateTime($row['DOB']);
                        $now = new DateTime();
                        $age = $now->diff($DOB)->y;
                        echo "<div class='row'>";
                        echo "<div class='col-md-6 text-left'>";
                        echo "<p><strong>Fullname:</strong> " . $row['patient_name'] . "</p>";
                        echo "<p><strong>Age:</strong> " . $age . "</p>";
                        echo "<p><strong>Address:</strong> " . $row['address'] . "</p>";
                        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                        echo "<p><strong>Phone number:</strong> " . $row['phone_number'] . "</p>";
                        echo "</div>";
                        echo "<div class='col-md-6 text-left'>";
                        echo "<p><strong>Gender:</strong> " . $row['gender'] . "</p>";
                        echo "<p><strong>Patient ID:</strong> " . $row['patient_id'] . "</p>";
                        echo "</div>";
                        echo "</div>";
                      }
                      echo "</div>";
                      echo "</div>";
                    } else {
                      echo "<div class='alert alert-warning mt-3'>Cannot find the phone number!</div>";
                    }
                    $stmt->close();
                    $con->close();
                  }
                ?>
              </div>

<!-- Doctor detail của patient-panel -->
<?php
                include('connection.php');
                // Giả sử doctor_id được lấy từ query string
                $doctor_id = $_GET['doctor_id'];
                $sql = "SELECT d.doctor_name, d.address, d.profile_pic, d.phone, d.business_license, d.intro, sp.speciality_name 
                        FROM doctor d
                        JOIN speciality sp ON d.speciality_id = sp.speciality_id
                        WHERE d.doctor_id = ?";
                        
                $stmt = $con->prepare($sql);
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $stmt->bind_result($doctor_name, $address, $profile_pic, $phone, $intro, $speciality_name);
                $stmt->fetch();
                $stmt->close();
              ?>
              <section class="doctor-info">
                <div class="doctor-card">
                  <img src="<?php echo $profile_pic; ?>" alt="Doctor Image" class="doctor-image">
                  <div class="doctor-details">
                    <h2><?php echo $doctor_name; ?></h2>
                    <p><strong><?php echo $speciality_name; ?></strong></p>
                    <p class="location">&#128205; <?php echo $address; ?></p>
                    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
                    <p><strong>Introduction:</strong> <?php echo $intro; ?></p>
                  </div>
                </div>
              </section>


<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                Appointment List của Patient-panel                                            ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->

<!-- Appointment List của Patient-panel -->
<table class="table table-hover">
                <thead>
                  <tr> 
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Consultancy Fees</th>
                    <th scope="col">Appointment Date</th>
                    <th scope="col">Appointment Time</th>
                    <th scope="col">Current Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $con=mysqli_connect("localhost","root","root","myhmsdb");
                    global $con;
                    $query = "select ID,doctor,docFees,appdate,apptime,userStatus,doctorStatus from appointmenttb where fname ='$fname' and lname='$lname';";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){                  
                      #$fname = $row['fname'];
                      #$lname = $row['lname'];
                      #$email = $row['email'];
                      #$contact = $row['contact'];
                  ?>
                  <tr>
                    <td><?php echo $row['doctor'];?></td>
                    <td><?php echo $row['docFees'];?></td>
                    <td><?php echo $row['appdate'];?></td>
                    <td><?php echo $row['apptime'];?></td>                              
                    <td>
                      <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                        {
                          echo "Active";
                        }
                        if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
                        {
                          echo "Cancelled by You";
                        }
                        if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
                        {
                          echo "Cancelled by Doctor";
                        }
                      ?>
                    </td>

                    <td>
                      <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                        { 
                      ?>                              
                      <a href="admin-panel.php?ID=<?php echo $row['ID']?>&cancel=update" onClick="return confirm('Are you sure you want to cancel this appointment ?')" title="Cancel Appointment" tooltip-placement="top" tooltip="Remove"><button class="btn btn-danger">Cancel</button></a>
                      <?php 
                        } else {
                          echo "Cancelled";
                        } 
                      ?>      
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
              <br>



<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                            Bản sao admin-func.php 23h30 ngày 7/9                             ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->


<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'connection.php'; // Ensure this file is included and the connection is open

    if (isset($_GET['fetchDoctors'])) {
        $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="doctorListTable">
                <thead>
                    <tr class="title">
                        <th>Doctor Name</th>
                        <th>Speciality</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $checked = $row['status'] ? 'checked' : '';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "
                    <td style='text-align: center;'>
                        <div class='custom-control custom-switch toggle'>
                            <input type='checkbox' class='custom-control-input' id='active-" . htmlspecialchars($row["doctor_id"]) . "' name='doctor[" . htmlspecialchars($row["doctor_id"]) . "][status]' $checked onclick='confirmStatusChange(" . htmlspecialchars($row["doctor_id"]) . ", this)'>
                            <label class='custom-control-label' for='active-" . htmlspecialchars($row["doctor_id"]) . "'></label>
                        </div>
                    </td>";
                echo "<td style='text-align: center;'>
                    <button class='update-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Update</button>
                    <button class='delete-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No doctors found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchPatients'])) {
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number
            FROM patient
            ORDER BY patient_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="patientListTable">
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
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['DOB'] = date("d/m/Y", strtotime($row['DOB']));
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "<td style='text-align: center;'>
                    <button class='delete-btn-patient' data-patient-id='" . htmlspecialchars($row["patient_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No patients found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchSpecs'])) {
        $sql = "SELECT speciality_id, speciality_name
            FROM speciality
            ORDER BY speciality_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="specListTable">
                <thead>
                    <tr class="title">
                        <th>Speciality Name</th>
                        
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                // echo "<td style='text-align: center;'>
                //     <button class='delete-btn-spec' data-spec-id='" . htmlspecialchars($row["speciality_id"]) . "'>Delete</button>
                // </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No specialities found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['id'])) {
        $doctor_id = $con->real_escape_string($_GET['id']);
        $sql = "SELECT doctor_id, doctor_name, business_license, speciality_id, address, email, phone, profile_pic, password, confirm_password FROM doctor WHERE doctor_id = '$doctor_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } else {
            echo json_encode(['error' => 'Doctor not found']);
        }
    } elseif (isset($_GET['patient_id'])) {
        $patient_id = $con->real_escape_string($_GET['patient_id']);
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number FROM patient WHERE patient_id = '$patient_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
            echo json_encode($patient);
        } else {
            echo json_encode(['error' => 'Patient not found']);
        }
    } elseif (isset($_GET['spec_id'])) {
        $spec_id = $con->real_escape_string($_GET['spec_id']);
        $sql = "SELECT speciality_id, speciality_name FROM speciality WHERE speciality_id = '$spec_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $spec = $result->fetch_assoc();
            echo json_encode($spec);
        } else {
            echo json_encode(['error' => 'Speciality not found']);
        }
    }

    else if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'update_status') {
            // Cập nhật status bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $status = intval($_POST['status']);

            $sql = "UPDATE doctor SET status = ? WHERE doctor_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $status, $doctor_id);

            if ($stmt->execute()) {
                header("Location: admin-panel.php?view=doctorList");
                exit();
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        
        } elseif ($action === 'add_doctor') {
            // Add new doctor
            $doctor_name = $_POST['doctor_name'];
            $business_license = $_POST['business_license'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $speciality = $_POST['speciality'];

            // Check if passwords match
            if ($password !== $confirm_password) {
                die('Password and Confirm password are not matched.');
            }

            // Check if email already exists
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('s', $business_license);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();

            // Handle file upload
            if (isset($_FILES['profile_pic'])) {
                $image = $_FILES['profile_pic'];
                $image_name = $image['name'];
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);

                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    die('Failed to upload image.');
                }
            } else {
                $profile_pic = null; // Nếu không có hình ảnh, đặt giá trị null
            }

            // Hash the password for security
            // $password_hash = password_hash($password, PASSWORD_BCRYPT);                 * sau này sửa hash sau *

            // Insert vào bảng account để lấy account_id
            $role_id = 2; // role_id cho bác sĩ
            $status = 1; // Trạng thái mặc định là active
            $account_sql = "INSERT INTO account (role_id, status) VALUES (?, ?)";
            $stmt = $con->prepare($account_sql);
            $stmt->bind_param('ii', $role_id, $status);

            if ($stmt->execute()) {
                // Lấy account_id vừa tạo
                $account_id = $stmt->insert_id;

                // Insert vào bảng doctor với account_id vừa tạo
                $intro = ''; // role_id cho bác sĩ
                $status = 1; // Trạng thái mặc định là active
                $doctor_sql = "INSERT INTO doctor (account_id, doctor_name, business_license, address, phone, email, password, confirm_password, profile_pic, speciality_id, intro, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($doctor_sql);
                $stmt->bind_param('issssssssisi', $account_id, $doctor_name, $business_license, $address, $phone, $email, $password, $confirm_password,  $profile_pic, $speciality, $intro, $status);

                if ($stmt->execute()) {
                    echo 'Adding a new doctor successfully.';
                } else {
                    echo 'Error: ' . $stmt->error;
                }
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'update_doctor') {
            // Cập nhật thông tin bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $doctor_name = htmlspecialchars(trim($_POST['doctor_name']));
            $business_license = htmlspecialchars(trim($_POST['business_license']));
            $address = htmlspecialchars(trim($_POST['address']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $speciality = intval($_POST['speciality']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // $status = intval($_POST['status'] ?? '');
            $current_profile_pic = htmlspecialchars(trim($_POST['current_profile_pic'] ?? ''));

            // Kiểm tra nếu password và confirm_password không giống nhau
            if ($password !== $confirm_password) {
                echo json_encode(['error' => 'Password and Confirm password do not match.']);
                exit; // Dừng lại nếu không khớp
            }

            // Check if email already exists for another doctor
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('si', $email, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists for other doctors
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('si', $business_license, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();
    
            // Handle file upload
            $profile_pic = null;
            
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
                $image = $_FILES['profile_pic'];
                $image_name = htmlspecialchars(basename($image['name']));
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);
    
                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    echo json_encode(['error' => 'Failed to upload image.']);
                    exit;
                }
            } else {
                // Preserve existing image if not uploading a new one
                $profile_pic = $current_profile_pic ?? $profile_pic;
            }
    
            // $stmt->close();
            // Kiểm tra nếu người dùng không nhập mật khẩu mới
            if (($password == '') && ($confirm_password == '')) {
                // Không cập nhật password, chỉ cập nhật các trường khác
                $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ? WHERE doctor_id = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('sssssssi', 
                    $doctor_name, 
                    $business_license, 
                    $address, 
                    $phone, 
                    $email,
                    $profile_pic,
                    $speciality, 
                    $doctor_id
                );
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Doctor updated successfully.']);
                } else {
                    echo json_encode(['error' => 'Error: ' . $stmt->error]);
                }
        
                $stmt->close();
            } else {
                // Nếu người dùng nhập mật khẩu mới, kiểm tra confirm password và cập nhật
                if ($password === $confirm_password) {
                    // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);   sau này sử dụng hash thì đổi lại
                    $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ?, password = ?, confirm_password = ? WHERE doctor_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('sssssssssi', 
                        $doctor_name, 
                        $business_license, 
                        $address, 
                        $phone, 
                        $email, 
                        $profile_pic,
                        $speciality, 
                        $password, 
                        $confirm_password, 
                        $doctor_id
                    );
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Doctor updated successfully.']);
                    } else {
                        echo json_encode(['error' => 'Error: ' . $stmt->error]);
                    }
            
                    $stmt->close();
                } else {
                    // Thông báo lỗi nếu mật khẩu không khớp
                    echo json_encode(['error' => 'Passwords do not match']);
                }
            }
        } elseif ($action === 'delete_doctor') {
            // Xoá bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
        
            // Query to get profile picture filename
            $query = "SELECT profile_pic FROM doctor WHERE doctor_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $stmt->bind_result($profile_pic);
                $stmt->fetch();
                $stmt->close();

                // Xoá bản ghi trong bảng 'timeframe' liên quan 'schedule_id'
                $query = "DELETE FROM timeframe WHERE schedule_id IN (SELECT schedule_id FROM schedule WHERE doctor_id = ?)";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete timeframes.";
                }
                // Xoá bản ghi trong bảng 'schedule' liên quan 'doctor_id'
                $query = "DELETE FROM schedule WHERE doctor_id = ?";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete schedules.";
                }
                // Xoá bản ghi trong bảng 'account' liên quan 'doctor_id'  
                $query = "SELECT account_id FROM doctor WHERE doctor_id = ?";     // Lấy account_id từ doctor
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                    $stmt->bind_result($account_id);
                    $stmt->fetch();
                    $stmt->close();               

                    // Xóa bản ghi bác sĩ
                    $query = "DELETE FROM doctor WHERE doctor_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $doctor_id);
                        if ($stmt->execute()) {
                            // Xóa file hình ảnh của bác sĩ nếu tồn tại
                            $profile_pic_path = "img/doctor_pic/" . basename($profile_pic);

                            if ($profile_pic && file_exists($profile_pic_path)) {
                                if (unlink($profile_pic_path)) {
                                    echo "Profile picture deleted successfully.";
                                } else {
                                    echo "Failed to delete profile picture.";
                                }
                            } else {
                                echo "Profile picture not found or path is incorrect.";
                            }
                            // Xóa bản ghi account
                            $query = "DELETE FROM account WHERE account_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $account_id);
                                if ($stmt->execute()) {
                                    echo " Doctor and related data deleted successfully!";
                                } else {
                                    echo "Failed to delete account.";
                                }
                            } else {
                                echo "Failed to prepare statement for deleting account.";
                            }
                        } else {
                            echo "Failed to delete doctor.";
                        }
                    } else {
                        echo "Failed to prepare statement for deleting doctor.";
                    }
                } else {
                    echo "Failed to prepare statement for fetching account_id.";
                }
            } else {
                echo "Failed to prepare statement for fetching profile picture.";
            }
        } elseif ($action === 'delete_patient') {
            // Xóa bệnh nhân
            $patient_id = intval($_POST['patient_id']);

            // Lấy `account_id` từ bảng `patient`
            $query = "SELECT account_id FROM patient WHERE patient_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $patient_id);
                $stmt->execute();
                $stmt->bind_result($account_id);
                $stmt->fetch();
                $stmt->close();

                // Xóa bản ghi bệnh nhân
                $query = "DELETE FROM patient WHERE patient_id = ?";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $patient_id);
                    if ($stmt->execute()) {
                        // Xóa bản ghi account liên quan
                        $query = "DELETE FROM account WHERE account_id = ?";
                        if ($stmt = $con->prepare($query)) {
                            $stmt->bind_param("i", $account_id);
                            if ($stmt->execute()) {
                                echo "Patient and related account deleted successfully!";
                            } else {
                                echo "Failed to delete account.";
                            }
                        } else {
                            echo "Failed to prepare statement for deleting account.";
                        }
                    } else {
                        echo "Failed to delete patient.";
                    }
                } else {
                    echo "Failed to prepare statement for deleting patient.";
                }
            } else {
                echo "Failed to prepare statement for fetching account_id.";
            }
        } elseif ($action === 'add_spec') {
            // Add new spec
            $speciality_name = $_POST['speciality_name'];

            // Check if speciality already exists
            $check_speciality_sql = "SELECT COUNT(*) FROM speciality WHERE speciality_name = ?";
            $stmt = $con->prepare($check_speciality_sql);
            $stmt->bind_param('s', $speciality_name);
            $stmt->execute();
            $stmt->bind_result($speciality_count);
            $stmt->fetch();
            if ($speciality_count > 0) {
                echo json_encode(['error' => 'Speciality already exists.']);
                exit;
            }
            $stmt->close();

            // Insert into speciality table
            $speciality_sql = "INSERT INTO speciality (speciality_id, speciality_name) 
                               VALUES (?, ?)";
            $stmt = $con->prepare($speciality_sql);
            $stmt->bind_param('is', $speciality_id, $speciality_name);

            if ($stmt->execute()) {
                echo 'Adding a new speciality successfully.';
            } else {
                echo 'Error: ' . $stmt->error;
            }
        /*} elseif ($action === 'delete_spec') {
            // Xóa spec
            $spec_id = intval($_POST['spec_id']);

            // Cập nhật speciality_id của các bác sĩ thành NULL
            $updateDoctorQuery = "UPDATE doctor SET speciality_id = NULL WHERE speciality_id = ?";
            if ($stmt = $con->prepare($updateDoctorQuery)) {
                $stmt->bind_param("i", $spec_id);
                if ($stmt->execute()) {
                    echo " Doctors who have this speciality is records updated.";
                } else {
                    echo "Failed to update doctor records.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for updating doctor records.";
            }

            // Xoá bản ghi speciality
            $query = "DELETE FROM speciality WHERE speciality_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $spec_id); 
                if ($stmt->execute()) {
                    echo "Speciality is deleted successfully!";
                } else {
                    echo "Failed to delete speciality.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for deleting speciality.";
            }

            // Thông báo đến các bác sĩ bị ảnh hưởng (ví dụ qua email hoặc thông báo hệ thống)
            // Fetch email addresses of affected doctors
            // Truy xuất danh sách các bác sĩ bị ảnh hưởng
            $fetchDoctorsQuery = "SELECT email FROM doctor WHERE speciality_id IS NULL";
            $result = $con->query($fetchDoctorsQuery);

            // Gửi email thông báo cho từng bác sĩ
            while ($row = $result->fetch_assoc()) {
                $doctorName = $row['doctor_name'];
                $email = $row['email'];
                $subject = "Speciality Deleted";
                $message = "Dear Doctor $doctorName! Your speciality has been deleted, and your speciality ID has been reset to NULL. Please contact to us to update your records.";
                $headers = "From: no-reply@medbook.com";

                // Gửi email
                if (mail($email, $subject, $message, $headers)) {
                    echo "Email sent to $email.";
                } else {
                    echo "Failed to send email to $email.";
                }
            } */

        } else {
            echo "Invalid request - unknown action";
        }

        // Other actions can be handled here

    } else {
        var_dump($_GET);
        echo json_encode(['error' => 'Invalid request.']);
    }

    // Truy vấn lấy các chuyên môn cho dropdown
    $speciality_sql = "SELECT speciality_id, speciality_name FROM speciality";
    $speciality_result = $con->query($speciality_sql);

    $specialities = [];
    if ($speciality_result->num_rows > 0) {
        while ($row = $speciality_result->fetch_assoc()) {
            $specialities[] = $row;
        }
    }

    // Fetch doctor data with speciality for display
    $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
    $result = $con->query($sql);

    // Ensure you have completed all operations before closing the connection
    $con->close();
?>


<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****              File gốc của admin-panel.php ngày 7/9 23h30 còn appo                            ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->


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
    <link rel="shortcut icon" type="image/x-icon" href="img/MedBook_icon.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style3.css">
    <!-- Bootstrap chuyển tab -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>

  <?php
    include('func3.php');
    include('admin-func.php');

    $username = $_SESSION['username'];

    // if(isset($_POST['docsub']))
    // {
    //   $doctor=$_POST['doctor'];
    //   $dpassword=$_POST['dpassword'];
    //   $demail=$_POST['demail'];
    //   $spec=$_POST['special'];
    //   $docFees=$_POST['docFees'];
    //   $query="insert into doctb(username,password,email,spec,docFees)values('$doctor','$dpassword','$demail','$spec','$docFees')";
    //   $result=mysqli_query($con,$query);
    //   if($result)
    //     {
    //       echo "<script>alert('Doctor added successfully!');</script>";
    //   }
    // }


    // if(isset($_POST['docsub1']))
    // {
    //   $demail=$_POST['demail'];
    //   $query="delete from doctb where email='$demail';";
    //   $result=mysqli_query($con,$query);
    //   if($result)
    //     {
    //       echo "<script>alert('Doctor removed successfully!');</script>";
    //   }
    //   else{
    //     echo "<script>alert('Unable to delete!');</script>";
    //   }
    // }
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
            <li class="nav-item" style="margin-right: 40px;">
              <button class="btn btn-secondary ml-2" onclick="clearTabAndLogout()" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif;">
                <h5>Logout</h5>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif; margin-top: 100px;"> Welcome <?php echo $username ?>! </h3>
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
              <div class="container-content bg-white">
                <h4 id="dashListHeading" style="margin: 0px 0px 30px 50px;">Dashboard</h4> 
                <div id="dashListContainer">23</div>
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
                <div id="appoListContainer"></div>
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
        let currentView = 'doctorList'; // Track the current view
        
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

        // Handle Delete button click for doctor list
        document.querySelectorAll('.delete-btn').forEach(button => {
          button.addEventListener('click', function() {
            const doctorId = this.closest('tr').querySelector('.update-btn').getAttribute('data-doctor-id');
            const confirmation = confirm("Do you really want to delete this doctor?");

            if (confirmation) {
              // Make an AJAX request to delete the doctor
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
              const confirmation = confirm("Do you really want to delete this doctor?");

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
              if (confirm('Are you sure you want to delete this patient?')) {
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

      // Clear activeTab when logging out
      function clearTabAndLogout() {
        localStorage.removeItem('activeTab');
        location.href = 'logout.php';
      }
    </script>
  </body>
</html>







<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****              File gốc của patient-panel.php ngày 20/9 21h00                                  ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->

<!-- Patient panel -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <title>MedBook</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/MedBook_icon.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <!-- Bootstrap chuyển tab -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>

  <?php
    include('func1.php');
    // include('newfunc.php');

    $patient_name = $_SESSION['patient_name'];
    $currentPatientId = $_SESSION['patient_id'];
  ?>

  <body style="background-color: #91d9ff; margin-top: 120px">
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #fff">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon" style="background-color:#60c2fe"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <!-- Notification Bell Icon -->
            <li class="nav-item dropdown" style="position: relative;">
            <a class="nav-link" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right:20px">
                <i class="fas fa-bell" style="font-size: 24px; color: #60c2fe;"></i>
                <!-- Notification Badge -->
                <span id="notificationCount" class="badge badge-danger" style="position: absolute; top: -5px; right: 10px; display: none;">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                <h6 class="dropdown-header">Notifications</h6>
                <div id="notificationList" class="dropdown-list"></div>
              </div>
            </li>

            <li class="nav-item" style="margin-right: 40px;">
              <button class="btn btn-secondary ml-2" onclick="clearTabAndLogout()" style="background-color:#60c2fe;border: none; border-radius:15px;color: white;font-family: 'IBM Plex Sans', sans-serif;">
                <h5>Logout</h5>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif;"> Welcome <?php echo $patient_name ?>! </h3>
      <div class="row">
        <div class="col-md-4" style="max-width:25%; margin-top: 2%">
          <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" href="#list-dash" role="tab" aria-controls="home" data-toggle="list">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="#list-doc" id="list-doc-list" role="tab" data-toggle="list" aria-controls="home">Doctor Detail</a>
            <a class="list-group-item list-group-item-action" href="#list-book" id="list-book-list" role="tab" data-toggle="list" aria-controls="home">Book Appointment</a>
            <a class="list-group-item list-group-item-action" href="#list-appo" id="list-appo-list" role="tab" data-toggle="list" aria-controls="home">Appointment List</a>
          </div><br>
        </div>

        <div class="col-md-8" style="max-width:25%;margin-top: 2%; ">
          <div class="tab-content" id="nav-tabContent" style="width: 950px;">
            <!-- Dashboard -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
              <div class="container-content bg-white" >              
                <h4 id="dashListHeading" style="margin: 0px 0px 30px 50px;">Dashboard</h4> 
                <div id="dashListContainer">
                  
                </div>
              </div>
            </div>
            
            <!-- Doctor Detail -->
            <div class="tab-pane fade" id="list-doc" role="tabpanel" aria-labelledby="list-doc-list">
              <div class="container-content bg-white">
                <div class="row">
                  <div class="col-md-2">
                    <button class="back-btn float-left" id="backButton" style="display: none">Back</button>  
                  </div>
                  <div class="col-md-10">
                    <h4 id="docDetailHeading" style="margin: 0px 80px 10px 0px;">Doctor Detail</h4> 
                  </div>
                </div>
                <!-- Doctor Detail -->
                <div id="docDetailForm" style="display: none;">
                </div>
                <!-- Doctor List -->
                <div id="docListContainer">
                  <div class="search-doctor">
                    <input class="form-control" type="text" id="search-bar" placeholder="Search for doctors or speciality">
                    <button class="btn btn-primary" id="search-button">Search</button>
                  </div>
                  <div id="search-results" class="list-group mt-3"> 
                    <!-- Search results will be dynamically added here -->
                  </div>
                </div> 
              </div> 
            </div>

            <!-- Book Appointment -->
            <script>
              window.onload = function() {
                selectSpecialities(); // Gọi hàm khi trang được tải
              };
              // Fetch and populate the Speciality dropdown
              function selectSpecialities() {
                fetch('patient/fetch-book.php?action=selectSpecialities')
                .then(response => response.json())
                .then(data => {
                  let specialitySelect = document.getElementById('specialitySelect');
                  specialitySelect.innerHTML = '<option>Select Speciality</option>'; // Clear previous options
                  data.forEach(speciality => {
                    let option = document.createElement('option');
                    option.value = speciality.speciality_id;
                    option.textContent = speciality.speciality_name;
                    specialitySelect.appendChild(option);
                  });
                })
                .catch(error => {
                  console.error('Error fetching specialities:', error);
                });
              }

              // Fetch and populate the Doctor dropdown based on selected Speciality
              function selectDoctors() {
                let specialityId = document.getElementById('specialitySelect').value;
                if (!specialityId) return; // Return if no speciality is selected

                // Reset other select fields to default
                let doctorSelect = document.getElementById('doctorSelect');
                let dateSelect = document.getElementById('dateSelect');
                let timeSelect = document.getElementById('timeSelect');

                doctorSelect.innerHTML = '<option>Select Doctor</option>'; // Clear previous options
                dateSelect.innerHTML = '<option>Select Date</option>'; // Clear previous options
                timeSelect.innerHTML = '<option>Select Time</option>'; // Clear previous options

                console.log("Fetching doctors for speciality ID:", specialityId); // Debugging log
                fetch(`patient/fetch-book.php?action=selectDoctors&speciality_id=${specialityId}`)
                .then(response => response.json())
                .then(data => {
                  let doctorSelect = document.getElementById('doctorSelect');
                  doctorSelect.innerHTML = '<option>Select Doctor</option>'; // Clear previous options
                  data.forEach(doctor => {
                    let option = document.createElement('option');
                    option.value = doctor.doctor_id;
                    option.textContent = doctor.doctor_name;
                    doctorSelect.appendChild(option);
                  });
                })
                .catch(error => {
                  console.error('Error fetching doctors:', error);
                });
              }

              // Hàm để chuyển định dạng từ yyyy-mm-dd thành dd-mm-yyyy
              function formatDateToDDMMYYYY(dateString) {
                const dateParts = dateString.split('-');
                return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // Trả về dd-mm-yyyy
              }

              // Hàm để kiểm tra ngày có lớn hơn hoặc bằng ngày hiện tại
              function isDateInFutureOrToday(dateString) {
                const today = new Date();
                const dateToCheck = new Date(dateString);

                // Kiểm tra nếu dateToCheck lớn hơn hoặc bằng ngày hôm nay
                return dateToCheck >= new Date(today.getFullYear(), today.getMonth(), today.getDate());
              }

              // Fetch and populate the Date dropdown based on selected Doctor
              function selectAvailableDates() {
                let doctorId = document.getElementById('doctorSelect').value;
                if (!doctorId) return;

                // Reset other select fields to default
                let dateSelect = document.getElementById('dateSelect');
                let timeSelect = document.getElementById('timeSelect');

                dateSelect.innerHTML = '<option>Select Date</option>'; // Clear previous options
                timeSelect.innerHTML = '<option>Select Time</option>'; // Clear previous options

                console.log("Fetching available dates for doctor ID:", doctorId); // Debugging log
                fetch(`patient/fetch-book.php?action=selectDates&doctor_id=${doctorId}`)
                .then(response => response.json())
                .then(data => {
                  let dateSelect = document.getElementById('dateSelect');
                  dateSelect.innerHTML = '<option>Select Date</option>'; // Clear previous options

                  data.forEach(date => {
                    if (isDateInFutureOrToday(date.date)) { // Chỉ thêm ngày nếu nó là hôm nay hoặc sau hôm nay
                      let option = document.createElement('option');
                      option.value = date.schedule_id; // Lưu schedule_id vào value của option
                      option.textContent = formatDateToDDMMYYYY(date.date); // Hiển thị ngày đã được định dạng
                      dateSelect.appendChild(option);
                    }
                  });
                })
                .catch(error => {
                  console.error('Error fetching dates:', error);
                });
              }

              function selectAvailableTimes() {
                let scheduleId = document.getElementById('dateSelect').value;
                if (!scheduleId) return; // Return if no date is selected

                // Reset other select fields to default
                let timeSelect = document.getElementById('timeSelect');
                timeSelect.innerHTML = '<option>Select Time</option>'; // Clear previous options

                // Get the selected date from the date dropdown
                let selectedDate = document.querySelector(`#dateSelect option[value="${scheduleId}"]`).textContent;
                // Convert selected date back to yyyy-mm-dd format for comparison
                let [day, month, year] = selectedDate.split('-');
                let scheduleDate = new Date(`${year}-${month}-${day}`);
                
                // Get the current time
                let currentTime = new Date(); 
                
                console.log(`Fetching available times for schedule ID: ${scheduleId}`); // Debugging log
                fetch(`patient/fetch-book.php?action=selectTimes&schedule_id=${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                  let timeSelect = document.getElementById('timeSelect');
                  timeSelect.innerHTML = '<option>Select Time</option>'; // Clear previous options

                  console.log("Current time:", currentTime);

                  data.forEach(time => {
                    let booked = time.booked; // Number of bookings
                    let available = time.available; // Maximum slots

                    // Split hours and minutes from start_time
                    let [hours, minutes] = time.start_time.split(':').slice(0, 2);
                    let startDateTime = new Date(scheduleDate); // Create a date object for the start time on the selected date
                    startDateTime.setHours(hours);
                    startDateTime.setMinutes(minutes);

                    // Calculate end_time by adding 30 minutes to start_time
                    let endDateTime = new Date(startDateTime.getTime() + 30 * 60000); // 30 minutes = 30 * 60 * 1000 ms
                    console.log(`Start time: ${startDateTime}, End time: ${endDateTime}`);

                    let isToday = (
                      currentTime.getFullYear() === startDateTime.getFullYear() &&
                      currentTime.getMonth() === startDateTime.getMonth() &&
                      currentTime.getDate() === startDateTime.getDate()
                    );
                    console.log("Is today:", isToday);

                    // Only show timeframe if the current time has not passed end_time and slots are not fully booked
                    if ((isToday && currentTime <= endDateTime) || !isToday) {
                      let formattedStartTime = `${startDateTime.getHours().toString().padStart(2, '0')}:${startDateTime.getMinutes().toString().padStart(2, '0')}`;
                      let formattedEndTime = `${endDateTime.getHours().toString().padStart(2, '0')}:${endDateTime.getMinutes().toString().padStart(2, '0')}`;

                      let option = document.createElement('option');
                      option.value = time.timeframe_id;
                      option.textContent = `${formattedStartTime} - ${formattedEndTime} (${available - booked} slots left)`; // Display remaining slots
                      timeSelect.appendChild(option);
                    }
                  });
                })
                .catch(error => {
                  console.error('Error fetching time slots:', error);
                });
              }



              // Xử lý khi đặt lịch hẹn
              function createAppointment() {
                let speciality = document.getElementById('specialitySelect').value;
                let doctor = document.getElementById('doctorSelect').value;
                let date = document.getElementById('dateSelect').value;
                let time = document.getElementById('timeSelect').value;

                if (!speciality || !doctor || !date || !time) {
                  alert("Please complete all fields before booking.");
                  return;
                }

                fetch('patient/fetch-book.php?action=createAppointment', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                    speciality: speciality,
                    doctor: doctor,
                    date: date,
                    time: time
                  })
                })
                .then(response => response.text()) // Lấy phản hồi dưới dạng text
                .then(responseText => {
                  console.log('Raw server response:', responseText); // In phản hồi thô ra console

                  if (responseText.trim() !== '') {
                    try {
                      // Kiểm tra xem phản hồi có phải JSON hợp lệ không
                      const result = JSON.parse(responseText);
                      console.log('Parsed JSON response:', result); 

                      if (result.success) {
                        alert("Appointment booked successfully!");
                        setTimeout(() => {
                          // Chuyển sang tab Appointment List
                          switchView('appoList'); // Chuyển sang tab 'Appointment List'
                        }, 0); // Đặt delay là 0 để chuyển ngay lập tức sau alert
                        updateBookedCount(time);
                        
                        // Reset các trường select về mặc định
                        selectSpecialities();
                        selectDoctors();
                        selectAvailableDates();
                        selectAvailableTimes();       
         
                      } else {
                        alert(`Failed to book appointment: ${result.message}`);
                      }
                    } catch (error) {
                      console.error('Error parsing JSON:', error);
                    }
                  } else {
                    console.error('Empty response from server.');
                    alert("Failed to book appointment. Please try again.");
                  }
                })
                .catch(error => {
                  console.error('Error booking appointment:', error); // Xử lý lỗi khi fetch thất bại
                  alert("Failed to book appointment. Please try again.");
                });
              }

              // Hàm cập nhật số lượng booked sau khi đặt thành công
              function updateBookedCount(timeframeId) {
                let timeSelect = document.getElementById('timeSelect');
                for (let option of timeSelect.options) {
                  if (option.value == timeframeId) {
                    let slotsLeftText = option.textContent.match(/\((\d+) slots left\)/);
                    if (slotsLeftText) {
                      let slotsLeft = parseInt(slotsLeftText[1]) - 1;
                      if (slotsLeft > 0) {
                        option.textContent = option.textContent.replace(/\(\d+ slots left\)/, `(${slotsLeft} slots left)`);
                      } else {
                        // Nếu hết slots, xóa option đó
                        option.remove();
                      }
                    }
                  }
                }
              }


            </script>

            <div class="tab-pane fade" id="list-book" role="tabpanel" aria-labelledby="list-book-list">
              <div class="container-content bg-white">
                <h4 id="bookAppoHeading" style="margin: 0px 0px 30px 50px;">Book an appointment</h4>
                
                <!-- Book an appointment with doctor detail -->              
                
                <!-- Book an appointment without doctor detail -->
                <div id="bookAppoContainer">
                  <form id="appointmentForm">
                    <label for="speciality">Speciality:</label>
                    <select id="specialitySelect" onchange="selectDoctors()" required>
                      <option>Select Speciality</option>
                      <!-- Options will be dynamically populated here -->
                    </select>

                    <label for="doctor">Doctor:</label>
                    <select id="doctorSelect" onchange="selectAvailableDates()" required>
                      <option>Select Doctor</option>
                      <!-- Options will be dynamically populated here -->
                    </select>

                    <label for="appointmentDate">Appointment Date:</label>
                    <select id="dateSelect" onchange="selectAvailableTimes()" required>
                      <option>Select Date</option>
                      <!-- Options will be dynamically populated here -->
                    </select>

                    <label for="appointmentTime">Appointment Time:</label>
                    <select id="timeSelect" required>
                      <option>Select Time</option>
                      <!-- Options will be dynamically populated here -->
                    </select>
                  </form>
                  <!-- Submit Button -->
                  <button type="button" onclick="createAppointment()">Book</button>
                </div>
              </div>  
            </div>

            <!-- Appointment List -->
            <div class="tab-pane fade" id="list-appo" role="tabpanel" aria-labelledby="list-appo-list">
              <div class="container-content bg-white">
                <div class="row">
                  <div class="col-md-2">
                    <button class="back-btn float-left" id="appoBackButton" style="display: none">Back</button>  
                  </div>
                  <div class="col-md-10">
                    <h4 id="appoListHeading" style="margin: 0px 80px 10px 0px;">Appointment List</h4> 
                  </div>
                </div>
                <!-- Appointment Detail -->
                <div id="appoDetailForm" style="display: none;">
                </div>
                <!-- Appointment List -->
                <div id="appoListContainer">
                  <table id="appoListTable">
                    <thead>
                      <tr class="title">
                        <th>Doctor Name</th>
                        <th>Date</th>
                        <th>Timeframe</th>
                        <th>Current Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($appointments)): ?>
                      <?php foreach ($appointments as $appointment): ?>
                      
                      <?php endforeach; ?>
                      <?php else: ?>

                      <?php endif; ?>
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
      // function bookAppointment() {
      //   // Chuyển đến tab Book Appointment
      //   document.getElementById('list-book-list').click();
      // }

      document.addEventListener('DOMContentLoaded', function() {
        // ------------------------------------------------------------------------------------------
        // Script của nút chuông thông báo
        // Function to display notification after a successful booking
// function displayNotification(message) {
//   // Get the notification list and count elements
//   const notificationList = document.getElementById('notificationList');
//   const notificationCount = document.getElementById('notificationCount');

//   // Create a new notification item
//   const notificationItem = document.createElement('a');
//   notificationItem.classList.add('dropdown-item');
//   notificationItem.textContent = message;

//   // Add the new notification to the list
//   notificationList.appendChild(notificationItem);

//   // Update the notification count
//   let count = parseInt(notificationCount.textContent) || 0;
//   count += 1;
//   notificationCount.textContent = count;
//   notificationCount.style.display = 'inline'; // Show the notification count badge
// }

// Call this function after a successful booking
// function handleBookingSuccess() {
//   displayNotification('Your appointment has been successfully booked!');
// }

        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề switchView
        let currentView = 'dashList';
        
        const detailButton = document.querySelectorAll('.detail-button');
        const backButton = document.getElementById('backButton');
        const appoBackButton = document.getElementById('appoBackButton');
        const docDetailForm = document.getElementById('docDetailForm');
        const appoDetailForm = document.getElementById('appoDetailForm');

        const dashListHeading = document.getElementById('dashListHeading');
        const dashListContainer = document.getElementById('dashListContainer');
        const docDetailHeading = document.getElementById('docDetailHeading');
        const docListContainer = document.getElementById('docListContainer');
        const bookAppoHeading = document.getElementById('bookAppoHeading');
        const bookAppoContainer = document.getElementById('bookAppoContainer');
        const appoListHeading = document.getElementById('appoListHeading');
        const appoListContainer = document.getElementById('appoListContainer');

        // Handle view switching
        function switchView(view) {
          if (view === 'dashList') {
            dashListContainer.style.display = 'block';
            docListContainer.style.display = 'none';
            bookAppoContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            currentView = 'dashList';
          } else if (view === 'docDetail') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'block';
            docDetailForm.style.display = 'none';
            bookAppoContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            backButton.style.display = 'none';
            currentView = 'docDetail';
          } else if (view === 'docDetailView') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'none';
            docDetailForm.style.display = 'block';
            bookAppoContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            backButton.style.display = 'block';
            currentView = 'docDetailView';
          } else if (view === 'bookAppo') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'none';
            bookAppoContainer.style.display = 'block';
            appoListContainer.style.display = 'none';
            currentView = 'bookAppo';
          } else if (view === 'appoList') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'none';  
            bookAppoContainer.style.display = 'none'; 
            appoListHeading.textContent = 'Appointment List';
            appoListContainer.style.display = 'block';
            appoDetailForm.style.display = 'none';
            appoBackButton.style.display = 'none';
            currentView = 'appoList';
            fetchAppointments();
          } else if (view === 'appoDetail') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'none';  
            bookAppoContainer.style.display = 'none'; 
            appoListContainer.style.display = 'none';
            appoListHeading.textContent = 'Appointment Detail';
            appoDetailForm.style.display = 'block';
            appoBackButton.style.display = 'block';
            currentView = 'appoDetail';
          }

          console.log('Switching to view:', view);
          console.log('dashListContainer display:', dashListContainer.style.display);
          console.log('docListContainer display:', docListContainer.style.display);
          console.log('bookAppoContainer display:', bookAppoContainer.style.display);
          console.log('appoListContainer display:', appoListContainer.style.display);
          console.log('currentView display:', currentView);

          // Cập nhật lịch sử với trạng thái mới
          currentView = view;
          history.replaceState({ view: view }, '', `#${view}`);
          // history.pushState({ view: view }, '', `?view=${view}`);
          
        }

        window.addEventListener('popstate', function(event) {
          if (event.state && event.state.view) {
            switchView(event.state.view);
          } else {
            switchView('dashList');
          }
        });

        // Set default view
        switchView('dashList');

        // ------------------------------------------------------------------------------------------
        // Script bên dưới liên quan đến vấn đề chuyển tab
        const defaultTab = '#list-dash'; 
        const isLoggedIn = sessionStorage.getItem('isLoggedIn'); // Cờ kiểm tra đăng nhập

        function activateTab(tabId) {
          // Xóa tất cả lớp active/show
          document.querySelectorAll('.tab-pane').forEach(function(tabContent) {
            tabContent.classList.remove('show', 'active');
          });
          document.querySelectorAll('.list-group-item-action').forEach(function(tabLink) {
            tabLink.classList.remove('active');
          });
          // Thêm lớp active/show vào tab được chọn
          const tab = document.querySelector(`.list-group-item-action[href="${tabId}"]`);
          if (tab) {
            tab.classList.add('active');
            document.querySelector(tabId).classList.add('show', 'active');
          }
        }

        // Quản lý chuyển đổi tab
        document.querySelectorAll('.list-group-item-action').forEach(function(tab) {
          tab.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn chặn việc thay đổi URL
            const tabId = tab.getAttribute('href');
            localStorage.setItem('activeTab', tabId);
            // activateTab(tabId);
            if (tabId === '#list-doc') {
              switchView('docDetail');
            } else if (tabId === '#list-book') {
              switchView('bookAppo');
            } else if (tabId === '#list-appo') {
              // fetchAppoList();
              switchView('appoList');
            } else {
              switchView('dashList');  // Mặc định là tab dash list
            }
            // Cập nhật URL mà không làm mới trang
            const currentUrl = window.location.href.split('#')[0] + tabId;
            history.replaceState(null, null, currentUrl);
          });
        });

        let activeTab = localStorage.getItem('activeTab');
        if (!isLoggedIn) {
          activeTab = defaultTab;
          sessionStorage.setItem('isLoggedIn', 'true'); // Đánh dấu là đã đăng nhập
        }
        // Giữ tab Patient Profile đang hoạt động khi nhấn Enter để tìm kiếm
        if (activeTab) {
          if (activeTab === '#list-doc') {
            switchView('docDetail');
          } else if (activeTab === '#list-book') {
            switchView('bookAppo');
          } else if (activeTab === '#list-appo') {
            switchView('appoList');
          } else {
            switchView('dashList');
          }
        } else {
          switchView('dashList'); // Đảm bảo default là dashList nếu không có giá trị activeTab
        }

        // ------------------------------------------------------------------------------------------
        // Script dưới liên quan đến tab doctor detail
        document.getElementById('list-doc-list').addEventListener('click', function() {
          fetchDoctors();
          setUpDetailButtons();
        });

        // Ensure 'Detail' button clicks switch to the detailed view
        function setUpDetailButtons() {
          document.querySelectorAll('.detail-button').forEach(button => {
            button.addEventListener('click', function() {
              const doctorId = this.getAttribute('data-doctor-id'); // Lấy doctor_id từ thuộc tính
              console.log('Doctor ID from button:', doctorId); // Kiểm tra xem có lấy được doctor_id không
              if (doctorId) {
                fetchDoctorDetails(doctorId); // Nếu lấy được, gọi hàm fetchDoctorDetails
              } else {
                console.error('Doctor ID is missing.');
              }
            });
          });
        }

        function fetchDoctors() {
          $.ajax({
            url: 'patient/fetch-doctor.php',
            type: 'GET',
            success: function(response) {
              let doctors = JSON.parse(response);
              let searchResults = document.getElementById('search-results');
              searchResults.innerHTML = '';           

              doctors.forEach(doctor => {
                let doctorItem = `
                  <div class="doctor-card">
                    <img src="${doctor.profile_pic}" alt="${doctor.doctor_name}">
                    <div class="doctor-info">
                      <h5>${doctor.doctor_name}</h5>
                      <p>${doctor.speciality_name}</p>
                      <p><i class="fas fa-map-marker-alt"></i> ${doctor.address}</p>
                    </div>
                    <div class="doctor-buttons">
                      <button class="btn detail-button" data-doctor-id="${doctor.doctor_id}">Detail</button>
                      <button class="btn book-button" onclick="bookAppointment()">Booking</button>
                    </div>
                  </div>
                `;
                console.log(`Doctor ID for ${doctor.doctor_name}: ${doctor.doctor_id}`);
                searchResults.innerHTML += doctorItem;
              });
              // Re-setup detail buttons after content update
              setUpDetailButtons();
            }
          });
        }

        // Fetch Detail Doctor View
        // Click detail, go to doctor detail view
        document.addEventListener('click', function(event) {
          if (event.target && event.target.classList.contains('detail-button')) {
            const doctorId = event.target.getAttribute('data-doctor-id');
            fetchDoctorDetails(doctorId);
          }
        });

        // Fetch doctor details and display them
        function fetchDoctorDetails(doctorId) {
          // console.log('Fetching doctor details for ID:', doctorId); // Kiểm tra giá trị ID
          fetch(`patient/fetch-doctor-details.php?id=${doctorId}`)
          .then(response => response.json())
          .then(data => {
            console.log('Fetched doctor details:', data); // Kiểm tra dữ liệu trả về
            if (data.error) {
              docDetailForm.innerHTML = `<p>${data.error}</p>`;
            } else {
              docDetailForm.innerHTML = `
                <div class="detail-container">
                  <div class="row">
                    <div class="col-3">
                      <img src="${data.profile_pic}" alt="Doctor's Profile Picture" class="img-fluid" />
                    </div>
                    <div class="col-9 info-detail">
                      <div class="doctor-info-detail">
                        <h3 style="display: inline-block; margin-right: 30px; margin-bottom: 20px">Dr. ${data.doctor_name}</h3>
                        <p style="display: inline-block;margin-left: 120px;">${data.speciality_name}</p>
                      </div>
                      <p><strong><i class="fas fa-map-marker-alt"></i></strong> ${data.address}</p>
                      
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-12">
                      <p><strong>Introduction:</strong> ${data.intro}</p>
                    </div>
                  </div>
                  <button class="book-button" onclick="bookAppointment()">Booking</button>
                </div>
                <!-- Add more details as needed -->
              `;
              switchView('docDetailView');
            }
          })
          .catch(error => {
            console.error('Error fetching doctor details:', error);
            docDetailForm.innerHTML = '<p>Error fetching doctor details. Please try again later.</p>';
          });
        } 

        // Click back, go to doctor list
        backButton.addEventListener('click', function() {
          switchView('docDetail');
        });
        
        // Search functionality in doctor detail
        const searchInput = document.getElementById('search-bar');
        const searchButton = document.getElementById('search-button');
        const resultContainer = document.getElementById('search-results');

        function performSearch() {
          const query = searchInput.value.trim().toLowerCase();
          console.log('Search query:', query); // Kiểm tra truy vấn
          if (query == ""){
            fetchDoctors();
          }

          if (query.length > 0) {
            fetch('patient/search.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: 'searchTerm=' + encodeURIComponent(query),
            })
            .then(response => response.json())
            .then(data => {
              resultContainer.innerHTML = ''; // Xóa kết quả cũ

              if (data.length > 0) {
                data.forEach((doctor, index) => {
                  const doctorElement = document.createElement('div');
                  doctorElement.className = 'doctor-card';
                  doctorElement.innerHTML = `
                    <img src="${doctor.profile_pic}" alt="${doctor.doctor_name}">
                    <div class="doctor-info">
                      <h5>${doctor.doctor_name}</h5>
                      <p>${doctor.speciality_name}</p>
                      <p><i class="fas fa-map-marker-alt"></i> ${doctor.address}</p>
                    </div>
                    <div class="doctor-buttons">
                      <button class="btn detail-button" data-doctor-id="${doctor.doctor_id}">Detail</button>
                      <button class="btn book-button" onclick="bookAppointment()">Booking</button>
                    </div>
                  `;
                  resultContainer.appendChild(doctorElement);
                });
                // Ensure container scrolls if more than 3 results
                resultContainer.style.overflowY = data.length > 3 ? 'auto' : 'hidden';
              } else {
                resultContainer.innerHTML = '<div class="doctor-result">No results found</div>';
              }
            })
            .catch(error => {
              console.error('Error fetching search results:', error);
            });
          } else {
            resultContainer.innerHTML = ''; // Xóa kết quả nếu truy vấn rỗng
          }
        }
        searchInput.addEventListener('keydown', function(event) {
          if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
            performSearch();
          }
        });
        searchButton.addEventListener('click', function() {
          performSearch();
        });

        // ------------------------------------------------------------------------------------------
        // Script dưới liên quan tới book appointment
      





        // ------------------------------------------------------------------------------------------
        // Script dưới liên quan tới appointment list

        // Hàm hiển thị chi tiết cuộc hẹn
        function viewDetail(appointmentId) {
        fetch(`patient/fetch-appo-detail.php?appointment_id=${appointmentId}`)
        .then(response => response.json())
        .then(appointment => {
          console.log('Fetched appointment details:', appointment);
            if (appointment.error) {
              appoDetailForm.innerHTML = `<p>${appointment.error}</p>`;
            } else {
              appoDetailForm.innerHTML = `
                <div class="appointment-details">
                  <div class="left-section"
                    <p><strong>Appointment Order</strong></p>
                    <p class="appo-order">${appointment.appointment_order}</p>
                  </div>
                  <div class="right-section" style="text-align: left"
                    <p><strong>Doctor Name:</strong> ${appointment.doctor_name}</p>
                    <p><strong>Speciality:</strong> ${appointment.speciality_name}</p>
                    <p><strong>Address:</strong> ${appointment.address}</p>
                    <p><strong>Date:</strong> ${formatDate(appointment.date)}</p>
                    <p><strong>Timeframe:</strong> ${formatTimeframe(appointment.start_time)}</p>
                    <p><strong>Status:</strong> ${getCurrentStatus(appointment.doctor_status, appointment.patient_status)}</p>
                  </div>
                </div>
              `;
              switchView('appoDetail');
            }
          })
          .catch(error => console.error('Error fetching appointment details:', error));
        }

        // Click back, go to appointment list
        appoBackButton.addEventListener('click', function() {
          switchView('appoList');
        });

        // Fetch the appointment list and display them
        function fetchAppointments() {
          fetch('patient/fetch-appo.php')
          .then(response => response.json())
          .then(data => {
            const tableBody = document.querySelector('#appoListTable tbody');
            tableBody.innerHTML = ''; // Xóa nội dung hiện tại của bảng

            if (data.length > 0) {
              data.forEach(appointment => {
                const row = document.createElement('tr');
                const isActive = getCurrentStatus(appointment.doctor_status, appointment.patient_status) === '<span style="color: #0eb50e;">Active</span>';
                row.innerHTML = `
                  <td style="text-align: left">Dr. ${appointment.doctor_name}</td>
                  <td>${formatDate(appointment.date)}</td>
                  <td>${formatTimeframe(appointment.start_time)}</td>
                  <td>${getCurrentStatus(appointment.doctor_status, appointment.patient_status)}</td>
                  <td>
                    <button class="detail-btn" data-appointment-id="${appointment.appointment_id}">Detail</button>
                    ${isActive ? `<button class="cancel-btn" data-appointment-id="${appointment.appointment_id}" onclick="confirmCancel(${appointment.appointment_id})">Cancel</button>` : ''}
                `;
                tableBody.appendChild(row);
              });

              // Add event listener for Cancel buttons
              document.querySelectorAll('.cancel-btn').forEach(button => {
                button.addEventListener('click', function () {
                  const appointmentId = this.getAttribute('data-appointment-id');
                  confirmCancel(appointmentId);
                });
              });
            } else {
              const row = document.createElement('tr');
              row.innerHTML = '<td colspan="5">No appointments found.</td>';
              tableBody.appendChild(row);
            }
          })
          .catch(error => console.error('Error fetching appointments:', error));
        }

        // Function to handle cancel confirmation
        function confirmCancel(appointmentId) {
          if (confirm('Are you sure you want to cancel this appointment?')) {
            // After confirmation, send a request to cancel and refresh the list
            fetch(`patient/fetch-appo.php?action=cancel&appointment_id=${appointmentId}`)
            .then(response => response.json())
            .then(() => {
              // Refresh the appointment list
              fetchAppointments();
            })
            .catch(error => console.error('Error cancelling appointment:', error));
          }
        }

        // Fetch the appointment list on page load
        fetchAppointments();

        // Thêm sự kiện click cho tất cả các nút trong bảng
        document.querySelector('#appoListTable tbody').addEventListener('click', function(event) {
          if (event.target.classList.contains('detail-btn')) {
            const appointmentId = event.target.getAttribute('data-appointment-id');
            viewDetail(appointmentId);
          }
        });

        // Hàm định dạng ngày và thời gian
        function formatDate(dateString) {
          const date = new Date(dateString);
          return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
        }

        function formatTimeframe(startTime) {
          const start = new Date(`1970-01-01T${startTime}`);
          const end = new Date(start.getTime() + 30 * 60000);
          return `${String(start.getHours()).padStart(2, '0')}:${String(start.getMinutes()).padStart(2, '0')} - ${String(end.getHours()).padStart(2, '0')}:${String(end.getMinutes()).padStart(2, '0')}`;
        }

        function getCurrentStatus(doctorStatus, patientStatus) {
          if (doctorStatus === 0) return '<span style="color: red;">Canceled by Doctor</span>';
          if (patientStatus === 0) return '<span style="color: red;">Canceled by You</span>';
          return '<span style="color: #0eb50e;">Active</span>';
        }

        
      });
      // Xóa activeTab khi người dùng đăng xuất
      function clearTabAndLogout() {
        localStorage.removeItem('activeTab'); // Clear the active tab from localStorage
        location.href = 'logout.php'; // Proceed with the logout
      }
    </script>
  </body>
</html>







<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                File gốc của fetch-book.php ngày 20/9 21h00                                   ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->




<?php
    include('../connection.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header('Content-Type: application/json');
    


    // Handle Speciality Fetch
    if (isset($_GET['action']) && $_GET['action'] == 'selectSpecialities') {
        $sql = "SELECT speciality_id, speciality_name FROM speciality";
        $result = $con->query($sql);

        $specialities = [];
        while ($row = $result->fetch_assoc()) {
            $specialities[] = $row;
        }

        echo json_encode($specialities);
        exit();
    }

    // Handle Doctor Fetch by Speciality
    if (isset($_GET['action']) && $_GET['action'] == 'selectDoctors' && isset($_GET['speciality_id'])) {
        $specialityId = $_GET['speciality_id'];
        $sql = "SELECT doctor_id, doctor_name FROM doctor WHERE speciality_id = $specialityId";
        $result = $con->query($sql);

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        echo json_encode($doctors);
        exit();
    }

    // Handle Available Dates by Doctor
    if (isset($_GET['action']) && $_GET['action'] == 'selectDates' && isset($_GET['doctor_id'])) {
        $doctorId = $_GET['doctor_id'];
        $sql = "SELECT DISTINCT date, schedule_id FROM schedule WHERE doctor_id = $doctorId AND status = 1";
        $result = $con->query($sql);

        $dates = [];
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row;
        }

        echo json_encode($dates);
        exit();
    }

    // Handle Available Time Slots by Doctor and Date
    if (isset($_GET['action']) && $_GET['action'] == 'selectTimes' && isset($_GET['schedule_id'])) {
        $scheduleID = $_GET['schedule_id'];
        $sql = "SELECT timeframe_id, start_time, available, booked FROM timeframe WHERE schedule_id = '$scheduleID' /*AND available = 1*/";
        $result = $con->query($sql);

        $timeframes = [];
        while ($row = $result->fetch_assoc()) {
            $timeframes[] = $row;
        }

        echo json_encode($timeframes);
        exit();
    }

    // Handle Appointment Booking
    if (isset($_GET['action']) && $_GET['action'] == 'createAppointment') {
        $rawData = file_get_contents('php://input');

        if ($rawData === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to read request data.']);
            exit();
        }

        // Ensure the data is not empty and decode the JSON
        if (empty($rawData)) {
            echo json_encode(['success' => false, 'message' => 'No data received.']);
            exit();
        }

        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
            exit();
        }

        // Validate data
        if (!isset($data['speciality']) || !isset($data['doctor']) || !isset($data['date']) || !isset($data['time'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required data fields.']);
            exit();
        }

        // Check if expected data fields are present
        $specialityId = $data['speciality'];
        $doctorId = $data['doctor'];
        $scheduleId = $data['date'];
        $timeframeId = $data['time'];

        // Assume $patient_id is already available (e.g., from session data after login)
        session_start();
        if (!isset($_SESSION['patient_id'])) {
            echo json_encode(['success' => false, 'message' => 'Patient not logged in.']);
            exit();
        }
        $patientId = $_SESSION['patient_id'];

        // Logic 1: Kiểm tra timeframe bệnh nhân đã đặt chưa?
        $checkDuplicateQuery = "SELECT COUNT(*) AS count 
                            FROM appointment 
                            WHERE patient_id = $patientId 
                            AND timeframe_id = $timeframeId";
        $duplicateResult = $con->query($checkDuplicateQuery);
        $duplicateRow = $duplicateResult->fetch_assoc();

        if ($duplicateRow['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'You have already booked this timeframe.']);
            exit();
        }

        // Logic 2: Kiểm tra số lượng timeframes bệnh nhân đã đặt trong ngày với 1 bác sĩ cụ thể
        $checkDateQuery = "SELECT date FROM schedule WHERE schedule_id = $scheduleId";
        $dateResult = $con->query($checkDateQuery);
        $dateRow = $dateResult->fetch_assoc();
        $date = $dateRow['date'];

        $checkTimeframeLimitQuery = "SELECT COUNT(*) AS count 
                                    FROM appointment 
                                    JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
                                    JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
                                    WHERE appointment.patient_id = $patientId 
                                    AND schedule.doctor_id = $doctorId
                                    AND DATE(schedule.date) = DATE('$date')";
        $timeframeLimitResult = $con->query($checkTimeframeLimitQuery);
        $timeframeLimitRow = $timeframeLimitResult->fetch_assoc();

        if ($timeframeLimitRow['count'] >= 2) {
        echo json_encode(['success' => false, 'message' => 'You have already booked 2 timeframes for this doctor on this date.']);
        exit();
        }

        // Logic 3: Kiểm tra số lượng bác sĩ đã đặt trong ngày?
        $checkDoctorLimitQuery = "SELECT COUNT(DISTINCT schedule.doctor_id) AS count 
                                FROM appointment 
                                JOIN timeframe ON appointment.timeframe_id = timeframe.timeframe_id
                                JOIN schedule ON timeframe.schedule_id = schedule.schedule_id
                                WHERE appointment.patient_id = $patientId 
                                AND DATE(schedule.date) = DATE('$date')";
        $doctorLimitResult = $con->query($checkDoctorLimitQuery);
        $doctorLimitRow = $doctorLimitResult->fetch_assoc();

        if ($doctorLimitRow['count'] >= 3) {
            echo json_encode(['success' => false, 'message' => 'You have already booked 3 doctors for this date.']);
            exit();
        }

        // Logic 4: Kiểm tra timeframe còn slot trống không?
        $sqlCheck = "SELECT booked, available FROM timeframe WHERE timeframe_id = $timeframeId";
        $resultCheck = $con->query($sqlCheck);
        $rowCheck = $resultCheck->fetch_assoc();

        if ($rowCheck['booked'] < $rowCheck['available']) {
            // Get the max appointment_order in this timeframe
            $sqlGetMaxOrder = "SELECT MAX(appointment_order) AS max_order FROM appointment WHERE timeframe_id = $timeframeId";
            $resultMaxOrder = $con->query($sqlGetMaxOrder);
            $rowMaxOrder = $resultMaxOrder->fetch_assoc();
            $currentMaxOrder = isset($rowMaxOrder['max_order']) ? $rowMaxOrder['max_order'] : 0;

            // Calculate the new appointment order
            $newAppointmentOrder = $currentMaxOrder + 1;

            // Proceed to book the appointment with the new appointment order
            $sqlBook = "INSERT INTO appointment (patient_id, timeframe_id, appointment_order, patient_status, doctor_status) 
                        VALUES ($patientId, $timeframeId, $newAppointmentOrder, '1', '1')";
            if ($con->query($sqlBook) === TRUE) {
                // Update the booked count
                $sqlUpdateBooked = "UPDATE timeframe SET booked = booked + 1 WHERE timeframe_id = $timeframeId";
                $con->query($sqlUpdateBooked);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to book the appointment.']);
            }
        } else {
            // Timeframe is full
            echo json_encode(['success' => false, 'message' => 'This timeframe is fully booked.']);
        }
        exit();
    }


?>








<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                File gốc của schedule-update.php ngày 28/9 23h50                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****             Đã hoàn thành logic điều chỉnh start_time, end_time, nums                        ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->


<?php
    session_start(); // Đảm bảo session đã được khởi tạo
    include('../connection.php');
    if (!isset($_SESSION['doctor_id'])) {
        die('Doctor ID is not set in the session.');
    }
    $doctor_id = $_SESSION['doctor_id']; // Ensure you have the doctor_id
    $response = ['status' => 'error', 'message' => 'An error occurred.'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $schedule_data = $_POST['schedule'];

        foreach ($schedule_data as $date => $data) {
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];
            $nums = $data['nums'];
            $status = isset($data['status']) ? 1 : 0;

            // Check if an entry already exists for the given date
            $query = "SELECT * FROM schedule WHERE doctor_id = '$doctor_id' AND date = '$date'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                // Update existing record
                $row = mysqli_fetch_assoc($result);
                $schedule_id = $row['schedule_id'];
                $old_nums = $row['nums']; // Save old nums value
                
                // Kiểm tra giá trị của nums
                if ($nums < 0) {
                    $response = ['status' => 'error', 'message' => 'The patients per 30-mins frame must be non-negative.'];
                    echo json_encode($response);
                    exit; // Ngừng thực hiện nếu nums < 0
                }

                // Check for existing appointments and confirm cancellation if necessary
                if ($status == 1 && $nums < $old_nums) {
                    // Truy vấn tất cả các timeframe và số lượng booked
                    $timeframes_query = "SELECT timeframe_id, booked FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                        $booked = $tf_row['booked'];
                        
                        // Hủy các cuộc hẹn có appointment_order > nums
                        $appointments_to_cancel = $booked - $nums; // Số lượng cần hủy
                
                        // Chỉ hủy nếu booked > nums
                        if ($appointments_to_cancel > 0) {
                            // Truy vấn các cuộc hẹn trong timeframe này
                            $appointments_query = "SELECT appointment_id, appointment_order FROM appointment 
                                                WHERE timeframe_id = $tf_id AND doctor_status = 1 
                                                ORDER BY appointment_order ASC"; // Lấy theo thứ tự tăng dần
                            
                            $appointments_result = mysqli_query($con, $appointments_query);
                
                            // Hủy các cuộc hẹn từ thứ tự thấp lên cao
                            while ($appt_row = mysqli_fetch_assoc($appointments_result)) {
                                $appointment_id = $appt_row['appointment_id'];
                                $appointment_order = $appt_row['appointment_order'];
                
                                // Hủy cuộc hẹn nếu cần thiết
                                if ($appointment_order > $nums) {
                                    $cancel_query = "UPDATE appointment SET doctor_status = 0 WHERE appointment_id = $appointment_id";
                                    mysqli_query($con, $cancel_query);
                                }
                            }
                        }
                    }
                } elseif ($status == 1 && $nums > $old_nums) {
                    // Tăng nums, kích hoạt lại các cuộc hẹn bị hủy
                    $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                
                        // Kích hoạt lại các cuộc hẹn có doctor_status = 0 và appointment_order <= nums
                        $activate_query = "UPDATE appointment 
                                        SET doctor_status = 1 
                                        WHERE timeframe_id = $tf_id AND doctor_status = 0 
                                        AND appointment_order <= $nums"; // Kích hoạt các cuộc hẹn <= nums
                        mysqli_query($con, $activate_query);
                    }
                }
                
                // Update schedule record
                $update_query = "UPDATE schedule SET start_time='$start_time', end_time='$end_time', nums='$nums', status='$status' 
                                WHERE doctor_id = '$doctor_id' AND date = '$date'";
                mysqli_query($con, $update_query);

                // Remove existing timeframes for this schedule if status is 0
                if ($status == 0) {
                    $delete_timeframe_query = "DELETE FROM timeframe WHERE schedule_id = '$schedule_id'";
                    mysqli_query($con, $delete_timeframe_query);
                }
            } else {
                // Insert new record
                $insert_query = "INSERT INTO schedule (doctor_id, date, start_time, end_time, nums, status) 
                                VALUES ('$doctor_id', '$date', '$start_time', '$end_time', '$nums', '$status')";
                mysqli_query($con, $insert_query);
                $schedule_id = mysqli_insert_id($con); // Get the last inserted schedule ID
            }

            // Only generate and save timeframes if the status is 1
            if ($status == 1) {
                updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums);
            }
        }
        // Set success message
        $response = ['status' => 'success', 'message' => 'Schedule updated successfully!'];
    }

    // Return JSON response
    echo json_encode($response);

    // Tạo các timeframe
    function generateTimeSlots($schedule_id, $start_time, $end_time, $nums) {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $slots = [];
            
        while ($start < $end) {
            $slot_start = $start->format('H:i:s');
            $start->modify('+30 minutes');
            $slots[] = [
                'schedule_id' => $schedule_id,
                'start_time' => $slot_start,
                'available' => $nums
            ];
        }
        return $slots;
    }

    // Cập nhật database
    function updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums) {
        // Lấy danh sách tất cả các timeframe hiện tại cho schedule_id
        $existing_timeframes = [];
        $select_query = "SELECT timeframe_id, start_time FROM timeframe WHERE schedule_id = ?";
        $stmt = $con->prepare($select_query);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $existing_timeframes[$row['start_time']] = $row['timeframe_id'];
        }
        $stmt->close();

        $slots = generateTimeSlots($schedule_id, $start_time, $end_time, $nums);

        foreach ($slots as $slot) {
            if (isset($existing_timeframes[$slot['start_time']])) {
                // Nếu timeframe đã tồn tại, cập nhật nó
                $timeframe_id = $existing_timeframes[$slot['start_time']];
                $update_query = "UPDATE timeframe SET available = ? WHERE timeframe_id = ?";
                $update_stmt = $con->prepare($update_query);
                $update_stmt->bind_param("ii", $slot['available'], $timeframe_id);
                $update_stmt->execute();
                $update_stmt->close();
            
                // Cập nhật doctor_status của các appointment đã hủy về 1 nếu có
                $activate_query = "UPDATE appointment 
                SET doctor_status = 1 
                WHERE timeframe_id = ? 
                AND doctor_status = 0 
                AND appointment_order <= ?"; // Ensure only valid appointments are reactivated
                $activate_stmt = $con->prepare($activate_query);
                $activate_stmt->bind_param("ii", $timeframe_id, $nums); // Bind both timeframe_id and nums
                $activate_stmt->execute();
                $activate_stmt->close();
            
                // Xóa timeframe này khỏi danh sách để không bị xóa sau này
                unset($existing_timeframes[$slot['start_time']]);
            } else {
                // Nếu timeframe chưa tồn tại, thêm mới
                $insert_query = "INSERT INTO timeframe (schedule_id, start_time, available, booked) VALUES (?, ?, ?, 0)";
                $insert_stmt = $con->prepare($insert_query);
                $insert_stmt->bind_param("isi", $slot['schedule_id'], $slot['start_time'], $slot['available']);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }

        // Xóa các timeframe còn lại trong existing_timeframes vì chúng không còn phù hợp
        if (!empty($existing_timeframes)) {
            foreach ($existing_timeframes as $start_time => $timeframe_id) {
                // Kiểm tra xem timeframe có appointment hay không
                $appointment_check_query = "SELECT COUNT(*) as count FROM appointment WHERE timeframe_id = ?";
                $check_stmt = $con->prepare($appointment_check_query);
                $check_stmt->bind_param("i", $timeframe_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $count_row = $check_result->fetch_assoc();
                $appointment_count = $count_row['count'];
                $check_stmt->close();

                if ($appointment_count > 0) {
                    // Nếu có appointment, cập nhật doctor_status của tất cả các appointment trong timeframe đó
                    $update_status_query = "UPDATE appointment SET doctor_status = 0 WHERE timeframe_id = ?";
                    $update_status_stmt = $con->prepare($update_status_query);
                    $update_status_stmt->bind_param("i", $timeframe_id);
                    $update_status_stmt->execute();
                    $update_status_stmt->close();
                } else {
                    // Nếu không có appointment, xóa timeframe
                    $delete_query = "DELETE FROM timeframe WHERE timeframe_id = ?";
                    $delete_stmt = $con->prepare($delete_query);
                    $delete_stmt->bind_param("i", $timeframe_id);
                    $delete_stmt->execute();
                    $delete_stmt->close();
                }
            }
        }
    }
?>





<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                File gốc của schedule-update.php ngày 2/10 12h30                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****             Đã hoàn thành logic điều chỉnh start_time, end_time, nums, status                ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->









<?php
    session_start(); // Đảm bảo session đã được khởi tạo
    include('../connection.php');
    if (!isset($_SESSION['doctor_id'])) {
        die('Doctor ID is not set in the session.');
    }
    $doctor_id = $_SESSION['doctor_id']; // Ensure you have the doctor_id
    $response = ['status' => 'error', 'message' => 'An error occurred.'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $schedule_data = $_POST['schedule'];

        foreach ($schedule_data as $date => $data) {
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];
            $nums = $data['nums'];
            $status = isset($data['status']) ? 1 : 0;

            // Check if an entry already exists for the given date
            $query = "SELECT * FROM schedule WHERE doctor_id = '$doctor_id' AND date = '$date'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                // Update existing record
                $row = mysqli_fetch_assoc($result);
                $schedule_id = $row['schedule_id'];
                $old_nums = $row['nums']; // Save old nums value
                
                // Kiểm tra giá trị của nums
                if ($nums < 0) {
                    $response = ['status' => 'error', 'message' => 'The patients per 30-mins frame must be non-negative.'];
                    echo json_encode($response);
                    exit; // Ngừng thực hiện nếu nums < 0
                }

                // Check for existing appointments and confirm cancellation if necessary
                if ($status == 1 && $nums < $old_nums) {
                    // Truy vấn tất cả các timeframe và số lượng booked
                    $timeframes_query = "SELECT timeframe_id, booked FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                        $booked = $tf_row['booked'];
                        
                        // Hủy các cuộc hẹn có appointment_order > nums
                        $appointments_to_cancel = $booked - $nums; // Số lượng cần hủy
                
                        // Chỉ hủy nếu booked > nums
                        if ($appointments_to_cancel > 0) {
                            // Truy vấn các cuộc hẹn trong timeframe này
                            $appointments_query = "SELECT appointment_id, appointment_order FROM appointment 
                                                WHERE timeframe_id = $tf_id AND doctor_status = 1 
                                                ORDER BY appointment_order ASC"; // Lấy theo thứ tự tăng dần
                            
                            $appointments_result = mysqli_query($con, $appointments_query);
                
                            // Hủy các cuộc hẹn từ thứ tự thấp lên cao
                            while ($appt_row = mysqli_fetch_assoc($appointments_result)) {
                                $appointment_id = $appt_row['appointment_id'];
                                $appointment_order = $appt_row['appointment_order'];
                
                                // Hủy cuộc hẹn nếu cần thiết
                                if ($appointment_order > $nums) {
                                    $cancel_query = "UPDATE appointment SET doctor_status = 0 WHERE appointment_id = $appointment_id";
                                    mysqli_query($con, $cancel_query);
                                }
                            }
                        }
                    }
                } elseif ($status == 1 && $nums > $old_nums) {
                    // Tăng nums, kích hoạt lại các cuộc hẹn bị hủy
                    $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                
                        // Kích hoạt lại các cuộc hẹn có doctor_status = 0 và appointment_order <= nums
                        $activate_query = "UPDATE appointment 
                                        SET doctor_status = 1 
                                        WHERE timeframe_id = $tf_id AND doctor_status = 0 
                                        AND appointment_order <= $nums"; // Kích hoạt các cuộc hẹn <= nums
                        mysqli_query($con, $activate_query);
                    }
                }
                
                // Update schedule record
                $update_query = "UPDATE schedule SET start_time='$start_time', end_time='$end_time', nums='$nums', status='$status' 
                                WHERE doctor_id = '$doctor_id' AND date = '$date'";
                mysqli_query($con, $update_query);

                // Remove existing timeframes for this schedule if status is 0
                if ($status == 0) {
                    // Kiểm tra xem có bất kỳ timeframe nào chứa appointment không
                    $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                
                        // Kiểm tra xem timeframe này có appointment nào không
                        $appointment_check_query = "SELECT COUNT(*) as appointment_count FROM appointment WHERE timeframe_id = '$tf_id'";
                        $appointment_check_result = mysqli_query($con, $appointment_check_query);
                        $count_row = mysqli_fetch_assoc($appointment_check_result);
                
                        if ($count_row['appointment_count'] > 0) {
                            // Nếu có appointment, cập nhật doctor_status của tất cả các appointment trong timeframe đó thành 0
                            $update_appointments_query = "UPDATE appointment SET doctor_status = 0 WHERE timeframe_id = '$tf_id'";
                            mysqli_query($con, $update_appointments_query);
                        } else {
                            // Nếu không có appointment, xóa timeframe này
                            $delete_timeframe_query = "DELETE FROM timeframe WHERE timeframe_id = '$tf_id'";
                            mysqli_query($con, $delete_timeframe_query);
                        }
                    }
                }
                
            } else {
                // Insert new record
                $insert_query = "INSERT INTO schedule (doctor_id, date, start_time, end_time, nums, status) 
                                VALUES ('$doctor_id', '$date', '$start_time', '$end_time', '$nums', '$status')";
                mysqli_query($con, $insert_query);
                $schedule_id = mysqli_insert_id($con); // Get the last inserted schedule ID
            }

            if ($status == 1) {
                // Lấy danh sách tất cả các timeframe hiện tại cho schedule_id
                $existing_timeframes = [];
                $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                $timeframes_result = mysqli_query($con, $timeframes_query);
                
                while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                    $tf_id = $tf_row['timeframe_id'];
                    $existing_timeframes[] = $tf_id;
            
                    // Kích hoạt lại các cuộc hẹn (doctor_status = 1) trong timeframe này
                    $activate_appointments_query = "UPDATE appointment 
                                                    SET doctor_status = 1 
                                                    WHERE timeframe_id = '$tf_id' AND doctor_status = 0";
                    mysqli_query($con, $activate_appointments_query);
                }
            
                // Nếu không có timeframe, tạo mới
                if (empty($existing_timeframes)) {
                    // Tạo mới timeframe
                    $slots = generateTimeSlots($schedule_id, $start_time, $end_time, $nums);
            
                    foreach ($slots as $slot) {
                        // Thêm mới timeframe
                        $insert_timeframe_query = "INSERT INTO timeframe (schedule_id, start_time, available, booked) 
                                                   VALUES (?, ?, ?, 0)";
                        $insert_stmt = $con->prepare($insert_timeframe_query);
                        $insert_stmt->bind_param("isi", $slot['schedule_id'], $slot['start_time'], $slot['available']);
                        $insert_stmt->execute();
                        $insert_stmt->close();
                    }
                }
            }
            

            // Only generate and save timeframes if the status is 1
            if ($status == 1) {
                updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums);
            }
        }
        // Set success message
        $response = ['status' => 'success', 'message' => 'Schedule updated successfully!'];
    }

    // Return JSON response
    echo json_encode($response);

    // Tạo các timeframe
    function generateTimeSlots($schedule_id, $start_time, $end_time, $nums) {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $slots = [];
            
        while ($start < $end) {
            $slot_start = $start->format('H:i:s');
            $start->modify('+30 minutes');
            $slots[] = [
                'schedule_id' => $schedule_id,
                'start_time' => $slot_start,
                'available' => $nums
            ];
        }
        return $slots;
    }

    // Cập nhật database
    function updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums) {
        // Lấy danh sách tất cả các timeframe hiện tại cho schedule_id
        $existing_timeframes = [];
        $select_query = "SELECT timeframe_id, start_time FROM timeframe WHERE schedule_id = ?";
        $stmt = $con->prepare($select_query);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $existing_timeframes[$row['start_time']] = $row['timeframe_id'];
        }
        $stmt->close();

        $slots = generateTimeSlots($schedule_id, $start_time, $end_time, $nums);

        foreach ($slots as $slot) {
            if (isset($existing_timeframes[$slot['start_time']])) {
                // Nếu timeframe đã tồn tại, cập nhật nó
                $timeframe_id = $existing_timeframes[$slot['start_time']];
                $update_query = "UPDATE timeframe SET available = ? WHERE timeframe_id = ?";
                $update_stmt = $con->prepare($update_query);
                $update_stmt->bind_param("ii", $slot['available'], $timeframe_id);
                $update_stmt->execute();
                $update_stmt->close();
            
                // Cập nhật doctor_status của các appointment đã hủy về 1 nếu có
                $activate_query = "UPDATE appointment 
                SET doctor_status = 1 
                WHERE timeframe_id = ? 
                AND doctor_status = 0 
                AND appointment_order <= ?"; // Ensure only valid appointments are reactivated
                $activate_stmt = $con->prepare($activate_query);
                $activate_stmt->bind_param("ii", $timeframe_id, $nums); // Bind both timeframe_id and nums
                $activate_stmt->execute();
                $activate_stmt->close();
            
                // Xóa timeframe này khỏi danh sách để không bị xóa sau này
                unset($existing_timeframes[$slot['start_time']]);
            } else {
                // Nếu timeframe chưa tồn tại, thêm mới
                $insert_query = "INSERT INTO timeframe (schedule_id, start_time, available, booked) VALUES (?, ?, ?, 0)";
                $insert_stmt = $con->prepare($insert_query);
                $insert_stmt->bind_param("isi", $slot['schedule_id'], $slot['start_time'], $slot['available']);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }

        // Xóa các timeframe còn lại trong existing_timeframes vì chúng không còn phù hợp
        if (!empty($existing_timeframes)) {
            foreach ($existing_timeframes as $start_time => $timeframe_id) {
                // Kiểm tra xem timeframe có appointment hay không
                $appointment_check_query = "SELECT COUNT(*) as count FROM appointment WHERE timeframe_id = ?";
                $check_stmt = $con->prepare($appointment_check_query);
                $check_stmt->bind_param("i", $timeframe_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $count_row = $check_result->fetch_assoc();
                $appointment_count = $count_row['count'];
                $check_stmt->close();

                if ($appointment_count > 0) {
                    // Nếu có appointment, cập nhật doctor_status của tất cả các appointment trong timeframe đó
                    $update_status_query = "UPDATE appointment SET doctor_status = 0 WHERE timeframe_id = ?";
                    $update_status_stmt = $con->prepare($update_status_query);
                    $update_status_stmt->bind_param("i", $timeframe_id);
                    $update_status_stmt->execute();
                    $update_status_stmt->close();
                } else {
                    // Nếu không có appointment, xóa timeframe
                    $delete_query = "DELETE FROM timeframe WHERE timeframe_id = ?";
                    $delete_stmt = $con->prepare($delete_query);
                    $delete_stmt->bind_param("i", $timeframe_id);
                    $delete_stmt->execute();
                    $delete_stmt->close();
                }
            }
        }
    }
?>





<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                     File gốc của admin-func.php ngày 2/10 22h30                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****             Đã hoàn thành logic điều chỉnh status doctor, delete patient                     ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->




<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'connection.php'; // Ensure this file is included and the connection is open

    if (isset($_GET['fetchDoctors'])) {
        $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="doctorListTable">
                <thead>
                    <tr class="title">
                        <th>Doctor Name</th>
                        <th>Speciality</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $checked = $row['status'] ? 'checked' : '';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "
                    <td style='text-align: center;'>
                        <div class='custom-control custom-switch toggle'>
                            <input type='checkbox' class='custom-control-input' id='active-" . htmlspecialchars($row["doctor_id"]) . "' name='doctor[" . htmlspecialchars($row["doctor_id"]) . "][status]' $checked onclick='confirmStatusChange(" . htmlspecialchars($row["doctor_id"]) . ", this)'>
                            <label class='custom-control-label' for='active-" . htmlspecialchars($row["doctor_id"]) . "'></label>
                        </div>
                    </td>";
                echo "<td style='text-align: center;'>
                    <button class='update-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Update</button>
                    <button class='delete-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No doctors found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchPatients'])) {
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number
            FROM patient
            ORDER BY patient_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="patientListTable">
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
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['DOB'] = date("d/m/Y", strtotime($row['DOB']));
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "<td style='text-align: center;'>
                    <button class='delete-btn-patient' data-patient-id='" . htmlspecialchars($row["patient_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No patients found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchAppointments'])) {
        // Fetch appointment details with related information
        $sql = "SELECT a.appointment_id, a.patient_id, a.doctor_status, a.patient_status, tf.timeframe_id, tf.start_time, s.date AS schedule_date, d.doctor_id, d.doctor_name, p.patient_id, p.patient_name
                FROM appointment AS a
                JOIN timeframe AS tf ON a.timeframe_id = tf.timeframe_id
                JOIN schedule AS s ON tf.schedule_id = s.schedule_id
                JOIN doctor AS d ON s.doctor_id = d.doctor_id
                JOIN patient AS p ON a.patient_id = p.patient_id
                ORDER BY a.appointment_id ASC";
        $result = $con->query($sql);
        // Start table output
        echo '<table id="appoListTable">
             <tbody>';
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Format date as d/m/Y
                $formattedDate = date("d/m/Y", strtotime($row['schedule_date']));

                // Format timeframe: start_time (hours and minutes only) and end_time (start_time + 30 minutes)
                $startTime = date("H:i", strtotime($row['start_time']));
                $endTime = date("H:i", strtotime($row['start_time'] . ' +30 minutes'));
                $timeframe = $startTime . " - " . $endTime;

                // Determine appointment status
                if ($row['doctor_status'] == 1 && $row['patient_status'] == 1) {
                    $appointmentStatus = "Active";
                } elseif ($row['doctor_status'] == 0 && $row['patient_status'] == 1) {
                    $appointmentStatus = "Canceled by Doctor";
                } elseif ($row['doctor_status'] == 1 && $row['patient_status'] == 0) {
                    $appointmentStatus = "Canceled by Patient";
                } else {
                    $appointmentStatus = "Unknown";
                }

                // Format the data into table rows
                echo "<tr>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['appointment_id']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['patient_id']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['doctor_id']) . "</td>";
                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                echo "<td>" . htmlspecialchars($timeframe) . "</td>";
                echo "<td>" . htmlspecialchars($appointmentStatus) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['patient_status']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['doctor_status']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No appointments found</td></tr>";
        }        
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchSpecs'])) {
        $sql = "SELECT speciality_id, speciality_name
            FROM speciality
            ORDER BY speciality_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="specListTable">
                <thead>
                    <tr class="title">
                        <th>Speciality Name</th>
                        
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                // echo "<td style='text-align: center;'>
                //     <button class='delete-btn-spec' data-spec-id='" . htmlspecialchars($row["speciality_id"]) . "'>Delete</button>
                // </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No specialities found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['id'])) {
        $doctor_id = $con->real_escape_string($_GET['id']);
        $sql = "SELECT doctor_id, doctor_name, business_license, speciality_id, address, email, phone, profile_pic, password, confirm_password FROM doctor WHERE doctor_id = '$doctor_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } else {
            echo json_encode(['error' => 'Doctor not found']);
        }
    } elseif (isset($_GET['patient_id'])) {
        $patient_id = $con->real_escape_string($_GET['patient_id']);
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number FROM patient WHERE patient_id = '$patient_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
            echo json_encode($patient);
        } else {
            echo json_encode(['error' => 'Patient not found']);
        }
    } elseif (isset($_GET['spec_id'])) {
        $spec_id = $con->real_escape_string($_GET['spec_id']);
        $sql = "SELECT speciality_id, speciality_name FROM speciality WHERE speciality_id = '$spec_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $spec = $result->fetch_assoc();
            echo json_encode($spec);
        } else {
            echo json_encode(['error' => 'Speciality not found']);
        }
    }

    else if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'update_status') {
            // Cập nhật status bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $status = intval($_POST['status']);

            $sql = "UPDATE doctor SET status = ? WHERE doctor_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $status, $doctor_id);

            if ($stmt->execute()) {
                header("Location: admin-panel.php?view=doctorList");
                exit();
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        
        } elseif ($action === 'add_doctor') {
            // Add new doctor
            $doctor_name = $_POST['doctor_name'];
            $business_license = $_POST['business_license'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $speciality = $_POST['speciality'];

            // Check if passwords match
            if ($password !== $confirm_password) {
                die('Password and Confirm password are not matched.');
            }

            // Check if email already exists
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('s', $business_license);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();

            // Handle file upload
            if (isset($_FILES['profile_pic'])) {
                $image = $_FILES['profile_pic'];
                $image_name = $image['name'];
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);

                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    die('Failed to upload image.');
                }
            } else {
                $profile_pic = null; // Nếu không có hình ảnh, đặt giá trị null
            }

            // Hash the password for security
            // $password_hash = password_hash($password, PASSWORD_BCRYPT);                 * sau này sửa hash sau *

            // Insert vào bảng account để lấy account_id
            $role_id = 2; // role_id cho bác sĩ
            $status = 1; // Trạng thái mặc định là active
            $account_sql = "INSERT INTO account (role_id, status) VALUES (?, ?)";
            $stmt = $con->prepare($account_sql);
            $stmt->bind_param('ii', $role_id, $status);

            if ($stmt->execute()) {
                // Lấy account_id vừa tạo
                $account_id = $stmt->insert_id;

                // Insert vào bảng doctor với account_id vừa tạo
                $intro = ''; // role_id cho bác sĩ
                $status = 1; // Trạng thái mặc định là active
                $doctor_sql = "INSERT INTO doctor (account_id, doctor_name, business_license, address, phone, email, password, confirm_password, profile_pic, speciality_id, intro, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($doctor_sql);
                $stmt->bind_param('issssssssisi', $account_id, $doctor_name, $business_license, $address, $phone, $email, $password, $confirm_password,  $profile_pic, $speciality, $intro, $status);

                if ($stmt->execute()) {
                    echo 'Adding a new doctor successfully.';
                } else {
                    echo 'Error: ' . $stmt->error;
                }
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'update_doctor') {
            // Cập nhật thông tin bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $doctor_name = htmlspecialchars(trim($_POST['doctor_name']));
            $business_license = htmlspecialchars(trim($_POST['business_license']));
            $address = htmlspecialchars(trim($_POST['address']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $speciality = intval($_POST['speciality']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // $status = intval($_POST['status'] ?? '');
            $current_profile_pic = htmlspecialchars(trim($_POST['current_profile_pic'] ?? ''));

            // Kiểm tra nếu password và confirm_password không giống nhau
            if ($password !== $confirm_password) {
                echo json_encode(['error' => 'Password and Confirm password do not match.']);
                exit; // Dừng lại nếu không khớp
            }

            // Check if email already exists for another doctor
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('si', $email, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists for other doctors
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('si', $business_license, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();
    
            // Handle file upload
            $profile_pic = null;
            
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
                $image = $_FILES['profile_pic'];
                $image_name = htmlspecialchars(basename($image['name']));
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);
    
                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    echo json_encode(['error' => 'Failed to upload image.']);
                    exit;
                }
            } else {
                // Preserve existing image if not uploading a new one
                $profile_pic = $current_profile_pic ?? $profile_pic;
            }
    
            // $stmt->close();
            // Kiểm tra nếu người dùng không nhập mật khẩu mới
            if (($password == '') && ($confirm_password == '')) {
                // Không cập nhật password, chỉ cập nhật các trường khác
                $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ? WHERE doctor_id = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('sssssssi', 
                    $doctor_name, 
                    $business_license, 
                    $address, 
                    $phone, 
                    $email,
                    $profile_pic,
                    $speciality, 
                    $doctor_id
                );
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Doctor updated successfully.']);
                } else {
                    echo json_encode(['error' => 'Error: ' . $stmt->error]);
                }
        
                $stmt->close();
            } else {
                // Nếu người dùng nhập mật khẩu mới, kiểm tra confirm password và cập nhật
                if ($password === $confirm_password) {
                    // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);   sau này sử dụng hash thì đổi lại
                    $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ?, password = ?, confirm_password = ? WHERE doctor_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('sssssssssi', 
                        $doctor_name, 
                        $business_license, 
                        $address, 
                        $phone, 
                        $email, 
                        $profile_pic,
                        $speciality, 
                        $password, 
                        $confirm_password, 
                        $doctor_id
                    );
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Doctor updated successfully.']);
                    } else {
                        echo json_encode(['error' => 'Error: ' . $stmt->error]);
                    }
            
                    $stmt->close();
                } else {
                    // Thông báo lỗi nếu mật khẩu không khớp
                    echo json_encode(['error' => 'Passwords do not match']);
                }
            }
        } elseif ($action === 'delete_doctor') {
            // Xoá bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
        
            // Query to get profile picture filename
            $query = "SELECT profile_pic FROM doctor WHERE doctor_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $stmt->bind_result($profile_pic);
                $stmt->fetch();
                $stmt->close();

                // Xoá bản ghi trong bảng 'timeframe' liên quan 'schedule_id'
                $query = "DELETE FROM timeframe WHERE schedule_id IN (SELECT schedule_id FROM schedule WHERE doctor_id = ?)";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete timeframes.";
                }
                // Xoá bản ghi trong bảng 'schedule' liên quan 'doctor_id'
                $query = "DELETE FROM schedule WHERE doctor_id = ?";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete schedules.";
                }
                // Xoá bản ghi trong bảng 'account' liên quan 'doctor_id'  
                $query = "SELECT account_id FROM doctor WHERE doctor_id = ?";     // Lấy account_id từ doctor
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                    $stmt->bind_result($account_id);
                    $stmt->fetch();
                    $stmt->close();               

                    // Xóa bản ghi bác sĩ
                    $query = "DELETE FROM doctor WHERE doctor_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $doctor_id);
                        if ($stmt->execute()) {
                            // Xóa file hình ảnh của bác sĩ nếu tồn tại
                            $profile_pic_path = "img/doctor_pic/" . basename($profile_pic);

                            if ($profile_pic && file_exists($profile_pic_path)) {
                                if (unlink($profile_pic_path)) {
                                    echo "Profile picture deleted successfully.";
                                } else {
                                    echo "Failed to delete profile picture.";
                                }
                            } else {
                                echo "Profile picture not found or path is incorrect.";
                            }
                            // Xóa bản ghi account
                            $query = "DELETE FROM account WHERE account_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $account_id);
                                if ($stmt->execute()) {
                                    echo " Doctor and related data deleted successfully!";
                                } else {
                                    echo "Failed to delete account.";
                                }
                            } else {
                                echo "Failed to prepare statement for deleting account.";
                            }
                        } else {
                            echo "Failed to delete doctor.";
                        }
                    } else {
                        echo "Failed to prepare statement for deleting doctor.";
                    }
                } else {
                    echo "Failed to prepare statement for fetching account_id.";
                }
            } else {
                echo "Failed to prepare statement for fetching profile picture.";
            }
        } elseif ($action === 'delete_patient') {
            // Xóa bệnh nhân
            $patient_id = intval($_POST['patient_id']);

            // Lấy `account_id` từ bảng `patient`
            $query = "SELECT account_id FROM patient WHERE patient_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $patient_id);
                $stmt->execute();
                $stmt->bind_result($account_id);
                $stmt->fetch();
                $stmt->close();

                // Bắt đầu giao dịch để đảm bảo tính nhất quán
                $con->begin_transaction();

                try {
                    // Lấy tất cả các `timeframe_id` từ bảng `appointment` liên quan đến bệnh nhân
                    $query = "SELECT timeframe_id FROM appointment WHERE patient_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $patient_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Lưu tất cả các `timeframe_id` liên quan đến các cuộc hẹn của bệnh nhân
                        $timeframe_ids = [];
                        while ($row = $result->fetch_assoc()) {
                            $timeframe_ids[] = $row['timeframe_id'];
                        }
                        $stmt->close();

                        // Xóa tất cả các cuộc hẹn của bệnh nhân
                        $query = "DELETE FROM appointment WHERE patient_id = ?";
                        if ($stmt = $con->prepare($query)) {
                            $stmt->bind_param("i", $patient_id);
                            $stmt->execute();
                            $stmt->close();
                        }

                        // Cập nhật lại `booked` trong bảng `timeframe` (giảm đi 1 cho mỗi `timeframe_id`)
                        foreach ($timeframe_ids as $timeframe_id) {
                            $query = "UPDATE timeframe SET booked = booked - 1 WHERE timeframe_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $timeframe_id);
                                $stmt->execute();
                                $stmt->close();
                            }
                        }
                    }

                    // Xóa bản ghi bệnh nhân
                    $query = "DELETE FROM patient WHERE patient_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $patient_id);
                        if ($stmt->execute()) {
                            // Xóa bản ghi account liên quan
                            $query = "DELETE FROM account WHERE account_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $account_id);
                                if ($stmt->execute()) {
                                    // Commit the transaction
                                    $con->commit();
                                    echo "Delete patient successfully!";
                                    // echo "Patient, related account, appointments, and timeframe updated successfully!";
                                } else {
                                    throw new Exception("Failed to delete account.");
                                }
                            } else {
                                throw new Exception("Failed to prepare statement for deleting account.");
                            }
                        } else {
                            throw new Exception("Failed to delete patient.");
                        }
                    } else {
                        throw new Exception("Failed to prepare statement for deleting patient.");
                    }
                } catch (Exception $e) {
                    // Rollback transaction on failure
                    $con->rollback();
                    echo "Failed to delete patient and related records: " . $e->getMessage();
                }
            } else {
                echo "Failed to prepare statement for fetching account_id.";
            }
        } elseif ($action === 'add_spec') {
            // Add new spec
            $speciality_name = $_POST['speciality_name'];

            // Check if speciality already exists
            $check_speciality_sql = "SELECT COUNT(*) FROM speciality WHERE speciality_name = ?";
            $stmt = $con->prepare($check_speciality_sql);
            $stmt->bind_param('s', $speciality_name);
            $stmt->execute();
            $stmt->bind_result($speciality_count);
            $stmt->fetch();
            if ($speciality_count > 0) {
                echo json_encode(['error' => 'Speciality already exists.']);
                exit;
            }
            $stmt->close();

            // Insert into speciality table
            $speciality_sql = "INSERT INTO speciality (speciality_id, speciality_name) 
                               VALUES (?, ?)";
            $stmt = $con->prepare($speciality_sql);
            $stmt->bind_param('is', $speciality_id, $speciality_name);

            if ($stmt->execute()) {
                echo 'Adding a new speciality successfully.';
            } else {
                echo 'Error: ' . $stmt->error;
            }
        /*} elseif ($action === 'delete_spec') {
            // Xóa spec
            $spec_id = intval($_POST['spec_id']);

            // Cập nhật speciality_id của các bác sĩ thành NULL
            $updateDoctorQuery = "UPDATE doctor SET speciality_id = NULL WHERE speciality_id = ?";
            if ($stmt = $con->prepare($updateDoctorQuery)) {
                $stmt->bind_param("i", $spec_id);
                if ($stmt->execute()) {
                    echo " Doctors who have this speciality is records updated.";
                } else {
                    echo "Failed to update doctor records.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for updating doctor records.";
            }

            // Xoá bản ghi speciality
            $query = "DELETE FROM speciality WHERE speciality_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $spec_id); 
                if ($stmt->execute()) {
                    echo "Speciality is deleted successfully!";
                } else {
                    echo "Failed to delete speciality.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for deleting speciality.";
            }

            // Thông báo đến các bác sĩ bị ảnh hưởng (ví dụ qua email hoặc thông báo hệ thống)
            // Fetch email addresses of affected doctors
            // Truy xuất danh sách các bác sĩ bị ảnh hưởng
            $fetchDoctorsQuery = "SELECT email FROM doctor WHERE speciality_id IS NULL";
            $result = $con->query($fetchDoctorsQuery);

            // Gửi email thông báo cho từng bác sĩ
            while ($row = $result->fetch_assoc()) {
                $doctorName = $row['doctor_name'];
                $email = $row['email'];
                $subject = "Speciality Deleted";
                $message = "Dear Doctor $doctorName! Your speciality has been deleted, and your speciality ID has been reset to NULL. Please contact to us to update your records.";
                $headers = "From: no-reply@medbook.com";

                // Gửi email
                if (mail($email, $subject, $message, $headers)) {
                    echo "Email sent to $email.";
                } else {
                    echo "Failed to send email to $email.";
                }
            } */

        } else {
            echo "Invalid request - unknown action";
        }

        // Other actions can be handled here

    } else {
        var_dump($_GET);
        echo json_encode(['error' => 'Invalid request.']);
    }

    // Truy vấn lấy các chuyên môn cho dropdown
    $speciality_sql = "SELECT speciality_id, speciality_name FROM speciality";
    $speciality_result = $con->query($speciality_sql);

    $specialities = [];
    if ($speciality_result->num_rows > 0) {
        while ($row = $speciality_result->fetch_assoc()) {
            $specialities[] = $row;
        }
    }

    // Fetch doctor data with speciality for display
    $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
    $result = $con->query($sql);

    // Ensure you have completed all operations before closing the connection
    $con->close();
?>







<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                     File gốc của admin-func.php ngày 3/10 12h30                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****         Đã hoàn thành logic điều chỉnh status doctor, delete patient, delete doctor          ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->





<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'connection.php'; // Ensure this file is included and the connection is open

    if (isset($_GET['fetchDoctors'])) {
        $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="doctorListTable">
                <thead>
                    <tr class="title">
                        <th>Doctor Name</th>
                        <th>Speciality</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $checked = $row['status'] ? 'checked' : '';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "
                    <td style='text-align: center;'>
                        <div class='custom-control custom-switch toggle'>
                            <input type='checkbox' class='custom-control-input' id='active-" . htmlspecialchars($row["doctor_id"]) . "' name='doctor[" . htmlspecialchars($row["doctor_id"]) . "][status]' $checked onclick='confirmStatusChange(" . htmlspecialchars($row["doctor_id"]) . ", this)'>
                            <label class='custom-control-label' for='active-" . htmlspecialchars($row["doctor_id"]) . "'></label>
                        </div>
                    </td>";
                echo "<td style='text-align: center;'>
                    <button class='update-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Update</button>
                    <button class='delete-btn' data-doctor-id='" . htmlspecialchars($row["doctor_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No doctors found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchPatients'])) {
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number
            FROM patient
            ORDER BY patient_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="patientListTable">
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
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['DOB'] = date("d/m/Y", strtotime($row['DOB']));
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "<td style='text-align: center;'>
                    <button class='delete-btn-patient' data-patient-id='" . htmlspecialchars($row["patient_id"]) . "'>Delete</button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No patients found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchAppointments'])) {
        // Fetch appointment details with related information
        $sql = "SELECT a.appointment_id, a.patient_id, a.doctor_status, a.patient_status, tf.timeframe_id, tf.start_time, s.date AS schedule_date, d.doctor_id, d.doctor_name, p.patient_id, p.patient_name
                FROM appointment AS a
                JOIN timeframe AS tf ON a.timeframe_id = tf.timeframe_id
                JOIN schedule AS s ON tf.schedule_id = s.schedule_id
                JOIN doctor AS d ON s.doctor_id = d.doctor_id
                JOIN patient AS p ON a.patient_id = p.patient_id
                ORDER BY a.appointment_id ASC";
        $result = $con->query($sql);
        // Start table output
        echo '<table id="appoListTable">
             <tbody>';
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Format date as d/m/Y
                $formattedDate = date("d/m/Y", strtotime($row['schedule_date']));

                // Format timeframe: start_time (hours and minutes only) and end_time (start_time + 30 minutes)
                $startTime = date("H:i", strtotime($row['start_time']));
                $endTime = date("H:i", strtotime($row['start_time'] . ' +30 minutes'));
                $timeframe = $startTime . " - " . $endTime;

                // Determine appointment status
                if ($row['doctor_status'] == 1 && $row['patient_status'] == 1) {
                    $appointmentStatus = "Active";
                } elseif ($row['doctor_status'] == 0 && $row['patient_status'] == 1) {
                    $appointmentStatus = "Canceled by Doctor";
                } elseif ($row['doctor_status'] == 1 && $row['patient_status'] == 0) {
                    $appointmentStatus = "Canceled by Patient";
                } else {
                    $appointmentStatus = "Unknown";
                }

                // Format the data into table rows
                echo "<tr>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['appointment_id']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['patient_id']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['doctor_id']) . "</td>";
                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                echo "<td>" . htmlspecialchars($timeframe) . "</td>";
                echo "<td>" . htmlspecialchars($appointmentStatus) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['patient_status']) . "</td>";
                echo "<td style='text-align:center'>" . htmlspecialchars($row['doctor_status']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No appointments found</td></tr>";
        }        
        echo '</tbody></table>';
    } elseif (isset($_GET['fetchSpecs'])) {
        $sql = "SELECT speciality_id, speciality_name
            FROM speciality
            ORDER BY speciality_name ASC";
        $result = $con->query($sql);
        // Start the table structure
        echo '<table id="specListTable">
                <thead>
                    <tr class="title">
                        <th>Speciality Name</th>
                        
                    </tr>
                </thead>
                <tbody>';
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['speciality_name']) . "</td>";
                // echo "<td style='text-align: center;'>
                //     <button class='delete-btn-spec' data-spec-id='" . htmlspecialchars($row["speciality_id"]) . "'>Delete</button>
                // </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No specialities found</td></tr>";
        }
        echo '</tbody></table>';
    } elseif (isset($_GET['id'])) {
        $doctor_id = $con->real_escape_string($_GET['id']);
        $sql = "SELECT doctor_id, doctor_name, business_license, speciality_id, address, email, phone, profile_pic, password, confirm_password FROM doctor WHERE doctor_id = '$doctor_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } else {
            echo json_encode(['error' => 'Doctor not found']);
        }
    } elseif (isset($_GET['patient_id'])) {
        $patient_id = $con->real_escape_string($_GET['patient_id']);
        $sql = "SELECT patient_id, patient_name, DOB, gender, address, phone_number FROM patient WHERE patient_id = '$patient_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
            echo json_encode($patient);
        } else {
            echo json_encode(['error' => 'Patient not found']);
        }
    } elseif (isset($_GET['spec_id'])) {
        $spec_id = $con->real_escape_string($_GET['spec_id']);
        $sql = "SELECT speciality_id, speciality_name FROM speciality WHERE speciality_id = '$spec_id'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $spec = $result->fetch_assoc();
            echo json_encode($spec);
        } else {
            echo json_encode(['error' => 'Speciality not found']);
        }
    }

    else if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'update_status') {
            // Cập nhật status bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $status = intval($_POST['status']);

            $sql = "UPDATE doctor SET status = ? WHERE doctor_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $status, $doctor_id);

            if ($stmt->execute()) {
                header("Location: admin-panel.php?view=doctorList");
                exit();
            } else {
                echo json_encode(['error' => 'Error: ' . $stmt->error]);
            }
            $stmt->close();
        
        } elseif ($action === 'add_doctor') {
            // Add new doctor
            $doctor_name = $_POST['doctor_name'];
            $business_license = $_POST['business_license'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $speciality = $_POST['speciality'];

            // Check if passwords match
            if ($password !== $confirm_password) {
                die('Password and Confirm password are not matched.');
            }

            // Check if email already exists
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('s', $business_license);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();

            // Handle file upload
            if (isset($_FILES['profile_pic'])) {
                $image = $_FILES['profile_pic'];
                $image_name = $image['name'];
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);

                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    die('Failed to upload image.');
                }
            } else {
                $profile_pic = null; // Nếu không có hình ảnh, đặt giá trị null
            }

            // Hash the password for security
            // $password_hash = password_hash($password, PASSWORD_BCRYPT);                 * sau này sửa hash sau *

            // Insert vào bảng account để lấy account_id
            $role_id = 2; // role_id cho bác sĩ
            $status = 1; // Trạng thái mặc định là active
            $account_sql = "INSERT INTO account (role_id, status) VALUES (?, ?)";
            $stmt = $con->prepare($account_sql);
            $stmt->bind_param('ii', $role_id, $status);

            if ($stmt->execute()) {
                // Lấy account_id vừa tạo
                $account_id = $stmt->insert_id;

                // Insert vào bảng doctor với account_id vừa tạo
                $intro = ''; // role_id cho bác sĩ
                $status = 1; // Trạng thái mặc định là active
                $doctor_sql = "INSERT INTO doctor (account_id, doctor_name, business_license, address, phone, email, password, confirm_password, profile_pic, speciality_id, intro, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($doctor_sql);
                $stmt->bind_param('issssssssisi', $account_id, $doctor_name, $business_license, $address, $phone, $email, $password, $confirm_password,  $profile_pic, $speciality, $intro, $status);

                if ($stmt->execute()) {
                    echo 'Adding a new doctor successfully.';
                } else {
                    echo 'Error: ' . $stmt->error;
                }
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'update_doctor') {
            // Cập nhật thông tin bác sĩ
            $doctor_id = intval($_POST['doctor_id']);
            $doctor_name = htmlspecialchars(trim($_POST['doctor_name']));
            $business_license = htmlspecialchars(trim($_POST['business_license']));
            $address = htmlspecialchars(trim($_POST['address']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $speciality = intval($_POST['speciality']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // $status = intval($_POST['status'] ?? '');
            $current_profile_pic = htmlspecialchars(trim($_POST['current_profile_pic'] ?? ''));

            // Kiểm tra nếu password và confirm_password không giống nhau
            if ($password !== $confirm_password) {
                echo json_encode(['error' => 'Password and Confirm password do not match.']);
                exit; // Dừng lại nếu không khớp
            }

            // Check if email already exists for another doctor
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('si', $email, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                echo json_encode(['error' => 'Email already exists.']);
                exit;
            }
            $stmt->close();

            // Check if business license already exists for other doctors
            $check_license_sql = "SELECT COUNT(*) FROM doctor WHERE business_license = ? AND doctor_id != ?";
            $stmt = $con->prepare($check_license_sql);
            $stmt->bind_param('si', $business_license, $doctor_id);
            $stmt->execute();
            $stmt->bind_result($license_count);
            $stmt->fetch();
            if ($license_count > 0) {
                echo json_encode(['error' => 'Business License already exists.']);
                exit;
            }
            $stmt->close();
    
            // Handle file upload
            $profile_pic = null;
            
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
                $image = $_FILES['profile_pic'];
                $image_name = htmlspecialchars(basename($image['name']));
                $image_tmp = $image['tmp_name'];
                $image_path = 'img/doctor_pic/' . basename($image_name);
    
                if (move_uploaded_file($image_tmp, $image_path)) {
                    $profile_pic = $image_path;
                } else {
                    echo json_encode(['error' => 'Failed to upload image.']);
                    exit;
                }
            } else {
                // Preserve existing image if not uploading a new one
                $profile_pic = $current_profile_pic ?? $profile_pic;
            }
    
            // $stmt->close();
            // Kiểm tra nếu người dùng không nhập mật khẩu mới
            if (($password == '') && ($confirm_password == '')) {
                // Không cập nhật password, chỉ cập nhật các trường khác
                $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ? WHERE doctor_id = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('sssssssi', 
                    $doctor_name, 
                    $business_license, 
                    $address, 
                    $phone, 
                    $email,
                    $profile_pic,
                    $speciality, 
                    $doctor_id
                );
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Doctor updated successfully.']);
                } else {
                    echo json_encode(['error' => 'Error: ' . $stmt->error]);
                }
        
                $stmt->close();
            } else {
                // Nếu người dùng nhập mật khẩu mới, kiểm tra confirm password và cập nhật
                if ($password === $confirm_password) {
                    // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);   sau này sử dụng hash thì đổi lại
                    $query = "UPDATE doctor SET doctor_name = ?, business_license = ?, address = ?, phone = ?, email = ?, profile_pic = ?, speciality_id = ?, password = ?, confirm_password = ? WHERE doctor_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('sssssssssi', 
                        $doctor_name, 
                        $business_license, 
                        $address, 
                        $phone, 
                        $email, 
                        $profile_pic,
                        $speciality, 
                        $password, 
                        $confirm_password, 
                        $doctor_id
                    );
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Doctor updated successfully.']);
                    } else {
                        echo json_encode(['error' => 'Error: ' . $stmt->error]);
                    }
            
                    $stmt->close();
                } else {
                    // Thông báo lỗi nếu mật khẩu không khớp
                    echo json_encode(['error' => 'Passwords do not match']);
                }
            }
        } elseif ($action === 'delete_doctor') {
            // Xoá bác sĩ
            $doctor_id = intval($_POST['doctor_id']);

            // Query to check if the doctor has any future appointments
            $query = "
                SELECT a.appointment_id, t.start_time, s.date
                FROM appointment a
                INNER JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                INNER JOIN schedule s ON t.schedule_id = s.schedule_id
                WHERE s.doctor_id = ? AND CONCAT(s.date, ' ', t.start_time) >= NOW()";
            
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $stmt->store_result();

                // Nếu có appointment chưa diễn ra thì không cho xoá
                if ($stmt->num_rows > 0) {
                    echo "Cannot delete doctor because they have future appointments.";
                    $stmt->close();
                    return;  // Dừng xoá
                }
                $stmt->close();
            } else {
                echo "Failed to check appointments.";
                return;  // Dừng xoá nếu không thể kiểm tra
            }

            // Proceed to delete doctor if no future appointments exist
            // Query to get profile picture filename
            $query = "SELECT profile_pic FROM doctor WHERE doctor_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $doctor_id);
                $stmt->execute();
                $stmt->bind_result($profile_pic);
                $stmt->fetch();
                $stmt->close();

                // Kiểm tra và xoá những appointment trong quá khứ
                $query = "
                    DELETE a FROM appointment a
                    INNER JOIN timeframe t ON a.timeframe_id = t.timeframe_id
                    INNER JOIN schedule s ON t.schedule_id = s.schedule_id
                    WHERE s.doctor_id = ? AND CONCAT(s.date, ' ', t.start_time) < NOW()";

                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete past appointments.";
                    return;
                }

                // Xoá bản ghi trong bảng 'timeframe' liên quan 'schedule_id'
                $query = "DELETE FROM timeframe WHERE schedule_id IN (SELECT schedule_id FROM schedule WHERE doctor_id = ?)";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete timeframes.";
                }
                // Xoá bản ghi trong bảng 'schedule' liên quan 'doctor_id'
                $query = "DELETE FROM schedule WHERE doctor_id = ?";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                } else {
                    echo "Failed to delete schedules.";
                }
                // Xoá bản ghi trong bảng 'account' liên quan 'doctor_id'  
                $query = "SELECT account_id FROM doctor WHERE doctor_id = ?";     // Lấy account_id từ doctor
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                    $stmt->bind_result($account_id);
                    $stmt->fetch();
                    $stmt->close();               

                    // Xóa bản ghi bác sĩ
                    $query = "DELETE FROM doctor WHERE doctor_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $doctor_id);
                        if ($stmt->execute()) {
                            // Xóa file hình ảnh của bác sĩ nếu tồn tại
                            $profile_pic_path = "img/doctor_pic/" . basename($profile_pic);

                            if ($profile_pic && file_exists($profile_pic_path)) {
                                if (unlink($profile_pic_path)) {
                                    echo "Profile picture deleted successfully. ";
                                } else {
                                    echo "Failed to delete profile picture.";
                                }
                            } else {
                                echo "Profile picture not found or path is incorrect.";
                            }
                            // Xóa bản ghi account
                            $query = "DELETE FROM account WHERE account_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $account_id);
                                if ($stmt->execute()) {
                                    echo " Doctor and related data deleted successfully!";
                                } else {
                                    echo "Failed to delete account.";
                                }
                            } else {
                                echo "Failed to prepare statement for deleting account.";
                            }
                        } else {
                            echo "Failed to delete doctor.";
                        }
                    } else {
                        echo "Failed to prepare statement for deleting doctor.";
                    }
                } else {
                    echo "Failed to prepare statement for fetching account_id.";
                }
            } else {
                echo "Failed to prepare statement for fetching profile picture.";
            }
        } elseif ($action === 'delete_patient') {
            // Xóa bệnh nhân
            $patient_id = intval($_POST['patient_id']);

            // Lấy `account_id` từ bảng `patient`
            $query = "SELECT account_id FROM patient WHERE patient_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $patient_id);
                $stmt->execute();
                $stmt->bind_result($account_id);
                $stmt->fetch();
                $stmt->close();

                // Bắt đầu giao dịch để đảm bảo tính nhất quán
                $con->begin_transaction();

                try {
                    // Lấy tất cả các `timeframe_id` từ bảng `appointment` liên quan đến bệnh nhân
                    $query = "SELECT timeframe_id FROM appointment WHERE patient_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $patient_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Lưu tất cả các `timeframe_id` liên quan đến các cuộc hẹn của bệnh nhân
                        $timeframe_ids = [];
                        while ($row = $result->fetch_assoc()) {
                            $timeframe_ids[] = $row['timeframe_id'];
                        }
                        $stmt->close();

                        // Xóa tất cả các cuộc hẹn của bệnh nhân
                        $query = "DELETE FROM appointment WHERE patient_id = ?";
                        if ($stmt = $con->prepare($query)) {
                            $stmt->bind_param("i", $patient_id);
                            $stmt->execute();
                            $stmt->close();
                        }

                        // Cập nhật lại `booked` trong bảng `timeframe` (giảm đi 1 cho mỗi `timeframe_id`)
                        foreach ($timeframe_ids as $timeframe_id) {
                            $query = "UPDATE timeframe SET booked = booked - 1 WHERE timeframe_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $timeframe_id);
                                $stmt->execute();
                                $stmt->close();
                            }
                        }
                    }

                    // Xóa bản ghi bệnh nhân
                    $query = "DELETE FROM patient WHERE patient_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $patient_id);
                        if ($stmt->execute()) {
                            // Xóa bản ghi account liên quan
                            $query = "DELETE FROM account WHERE account_id = ?";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->bind_param("i", $account_id);
                                if ($stmt->execute()) {
                                    // Commit the transaction
                                    $con->commit();
                                    echo "Delete patient successfully!";
                                    // echo "Patient, related account, appointments, and timeframe updated successfully!";
                                } else {
                                    throw new Exception("Failed to delete account.");
                                }
                            } else {
                                throw new Exception("Failed to prepare statement for deleting account.");
                            }
                        } else {
                            throw new Exception("Failed to delete patient.");
                        }
                    } else {
                        throw new Exception("Failed to prepare statement for deleting patient.");
                    }
                } catch (Exception $e) {
                    // Rollback transaction on failure
                    $con->rollback();
                    echo "Failed to delete patient and related records: " . $e->getMessage();
                }
            } else {
                echo "Failed to prepare statement for fetching account_id.";
            }
        } elseif ($action === 'add_spec') {
            // Add new spec
            $speciality_name = $_POST['speciality_name'];

            // Check if speciality already exists
            $check_speciality_sql = "SELECT COUNT(*) FROM speciality WHERE speciality_name = ?";
            $stmt = $con->prepare($check_speciality_sql);
            $stmt->bind_param('s', $speciality_name);
            $stmt->execute();
            $stmt->bind_result($speciality_count);
            $stmt->fetch();
            if ($speciality_count > 0) {
                echo json_encode(['error' => 'Speciality already exists.']);
                exit;
            }
            $stmt->close();

            // Insert into speciality table
            $speciality_sql = "INSERT INTO speciality (speciality_id, speciality_name) 
                               VALUES (?, ?)";
            $stmt = $con->prepare($speciality_sql);
            $stmt->bind_param('is', $speciality_id, $speciality_name);

            if ($stmt->execute()) {
                echo 'Adding a new speciality successfully.';
            } else {
                echo 'Error: ' . $stmt->error;
            }
        /*} elseif ($action === 'delete_spec') {
            // Xóa spec
            $spec_id = intval($_POST['spec_id']);

            // Cập nhật speciality_id của các bác sĩ thành NULL
            $updateDoctorQuery = "UPDATE doctor SET speciality_id = NULL WHERE speciality_id = ?";
            if ($stmt = $con->prepare($updateDoctorQuery)) {
                $stmt->bind_param("i", $spec_id);
                if ($stmt->execute()) {
                    echo " Doctors who have this speciality is records updated.";
                } else {
                    echo "Failed to update doctor records.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for updating doctor records.";
            }

            // Xoá bản ghi speciality
            $query = "DELETE FROM speciality WHERE speciality_id = ?";
            if ($stmt = $con->prepare($query)) {
                $stmt->bind_param("i", $spec_id); 
                if ($stmt->execute()) {
                    echo "Speciality is deleted successfully!";
                } else {
                    echo "Failed to delete speciality.";
                }
                $stmt->close(); 
            } else {
                echo "Failed to prepare statement for deleting speciality.";
            }

            // Thông báo đến các bác sĩ bị ảnh hưởng (ví dụ qua email hoặc thông báo hệ thống)
            // Fetch email addresses of affected doctors
            // Truy xuất danh sách các bác sĩ bị ảnh hưởng
            $fetchDoctorsQuery = "SELECT email FROM doctor WHERE speciality_id IS NULL";
            $result = $con->query($fetchDoctorsQuery);

            // Gửi email thông báo cho từng bác sĩ
            while ($row = $result->fetch_assoc()) {
                $doctorName = $row['doctor_name'];
                $email = $row['email'];
                $subject = "Speciality Deleted";
                $message = "Dear Doctor $doctorName! Your speciality has been deleted, and your speciality ID has been reset to NULL. Please contact to us to update your records.";
                $headers = "From: no-reply@medbook.com";

                // Gửi email
                if (mail($email, $subject, $message, $headers)) {
                    echo "Email sent to $email.";
                } else {
                    echo "Failed to send email to $email.";
                }
            } */

        } else {
            echo "Invalid request - unknown action";
        }

        // Other actions can be handled here

    } else {
        var_dump($_GET);
        echo json_encode(['error' => 'Invalid request.']);
    }

    // Truy vấn lấy các chuyên môn cho dropdown
    $speciality_sql = "SELECT speciality_id, speciality_name FROM speciality";
    $speciality_result = $con->query($speciality_sql);

    $specialities = [];
    if ($speciality_result->num_rows > 0) {
        while ($row = $speciality_result->fetch_assoc()) {
            $specialities[] = $row;
        }
    }

    // Fetch doctor data with speciality for display
    $sql = "SELECT doctor.doctor_id, doctor.doctor_name, speciality.speciality_name, doctor.email, doctor.status 
            FROM doctor
            JOIN speciality ON doctor.speciality_id = speciality.speciality_id
            ORDER BY doctor.doctor_name ASC";
    $result = $con->query($sql);

    // Ensure you have completed all operations before closing the connection
    $con->close();
?>







<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                File gốc của schedule-update.php ngày 10/10 23h50                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****                                                                                              ****** -->
<!-- *****             Đã hoàn thành logic điều chỉnh start_time, end_time, nums, status                ****** -->
<!-- *****                                                                                              ****** -->
<!-- ********************************************************************************************************* -->
<!-- ********************************************************************************************************* -->




<?php
    session_start(); // Đảm bảo session đã được khởi tạo
    include('../connection.php');
    if (!isset($_SESSION['doctor_id'])) {
        die('Doctor ID is not set in the session.');
    }
    $doctor_id = $_SESSION['doctor_id']; // Ensure you have the doctor_id
    $response = ['status' => 'error', 'message' => 'An error occurred.'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $schedule_data = $_POST['schedule'];

        foreach ($schedule_data as $date => $data) {
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];
            $nums = $data['nums'];
            $status = isset($data['status']) ? 1 : 0;

            // Check if an entry already exists for the given date
            $query = "SELECT * FROM schedule WHERE doctor_id = '$doctor_id' AND date = '$date'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                // Update existing record
                $row = mysqli_fetch_assoc($result);
                $schedule_id = $row['schedule_id'];
                $old_nums = $row['nums']; // Save old nums value
                
                // Kiểm tra giá trị của nums
                if ($nums < 0) {
                    $response = ['status' => 'error', 'message' => 'The patients per 30-mins frame must be non-negative.'];
                    echo json_encode($response);
                    exit; // Ngừng thực hiện nếu nums < 0
                }

                // Check for existing appointments and confirm cancellation if necessary
                if ($status == 1 && $nums < $old_nums) {
                    // Truy vấn tất cả các timeframe và số lượng booked
                    $timeframes_query = "SELECT timeframe_id, booked FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                        $booked = $tf_row['booked'];
                        
                        // Hủy các cuộc hẹn có appointment_order > nums
                        $appointments_to_cancel = $booked - $nums; // Số lượng cần hủy
                
                        // Chỉ hủy nếu booked > nums
                        if ($appointments_to_cancel > 0) {
                            // Truy vấn các cuộc hẹn trong timeframe này
                            $appointments_query = "SELECT appointment_id, appointment_order FROM appointment 
                                                WHERE timeframe_id = $tf_id AND doctor_status = 1 
                                                ORDER BY appointment_order ASC"; // Lấy theo thứ tự tăng dần
                            
                            $appointments_result = mysqli_query($con, $appointments_query);
                
                            // Hủy các cuộc hẹn từ thứ tự thấp lên cao
                            while ($appt_row = mysqli_fetch_assoc($appointments_result)) {
                                $appointment_id = $appt_row['appointment_id'];
                                $appointment_order = $appt_row['appointment_order'];
                
                                // Hủy cuộc hẹn nếu cần thiết
                                if ($appointment_order > $nums) {
                                    $cancel_query = "UPDATE appointment SET doctor_status = 0 WHERE appointment_id = $appointment_id";
                                    mysqli_query($con, $cancel_query);
                                }
                            }
                        }
                    }
                } elseif ($status == 1 && $nums > $old_nums) {
                    // Tăng nums, kích hoạt lại các cuộc hẹn bị hủy
                    $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                    
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                
                        // Kích hoạt lại các cuộc hẹn có doctor_status = 0 và appointment_order <= nums
                        $activate_query = "UPDATE appointment 
                                        SET doctor_status = 1 
                                        WHERE timeframe_id = $tf_id AND doctor_status = 0 
                                        AND appointment_order <= $nums"; // Kích hoạt các cuộc hẹn <= nums
                        mysqli_query($con, $activate_query);
                    }
                }
                
                // Update schedule record
                $update_query = "UPDATE schedule SET start_time='$start_time', end_time='$end_time', nums='$nums', status='$status' 
                                WHERE doctor_id = '$doctor_id' AND date = '$date'";
                mysqli_query($con, $update_query);

                // Remove existing timeframes for this schedule if status is 0
                if ($status == 0) {
                    // Kiểm tra xem có bất kỳ timeframe nào chứa appointment không
                    $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
                    $timeframes_result = mysqli_query($con, $timeframes_query);
                
                    while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
                        $tf_id = $tf_row['timeframe_id'];
                
                        // Kiểm tra xem timeframe này có appointment nào không
                        $appointment_check_query = "SELECT COUNT(*) as appointment_count FROM appointment WHERE timeframe_id = '$tf_id'";
                        $appointment_check_result = mysqli_query($con, $appointment_check_query);
                        $count_row = mysqli_fetch_assoc($appointment_check_result);
                
                        if ($count_row['appointment_count'] > 0) {
                            // Nếu có appointment, cập nhật doctor_status của tất cả các appointment trong timeframe đó thành 0
                            $update_appointments_query = "UPDATE appointment SET doctor_status = 0 WHERE timeframe_id = '$tf_id'";
                            mysqli_query($con, $update_appointments_query);
                        } else {
                            // Nếu không có appointment, xóa timeframe này
                            $delete_timeframe_query = "DELETE FROM timeframe WHERE timeframe_id = '$tf_id'";
                            mysqli_query($con, $delete_timeframe_query);
                        }
                    }
                }
            } else {
                // Insert new record
                $insert_query = "INSERT INTO schedule (doctor_id, date, start_time, end_time, nums, status) 
                                VALUES ('$doctor_id', '$date', '$start_time', '$end_time', '$nums', '$status')";
                mysqli_query($con, $insert_query);
                $schedule_id = mysqli_insert_id($con); // Get the last inserted schedule ID
            }

            // Only generate and save timeframes if the status is 1
            if ($status == 1) {
                updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums);
            }
        }
        // Set success message
        $response = ['status' => 'success', 'message' => 'Schedule updated successfully!'];
    }

    // Return JSON response
    echo json_encode($response);

    // Tạo các timeframe
    function generateTimeSlots($schedule_id, $start_time, $end_time, $nums) {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $slots = [];
            
        while ($start < $end) {
            $slot_start = $start->format('H:i:s');
            $start->modify('+30 minutes');
            $slots[] = [
                'schedule_id' => $schedule_id,
                'start_time' => $slot_start,
                'available' => $nums
            ];
        }
        return $slots;
    }

    // Cập nhật database
    function updateOrInsertTimeSlots($con, $schedule_id, $start_time, $end_time, $nums) {
        // Lấy danh sách tất cả các timeframe hiện tại cho schedule_id
        $existing_timeframes = [];
        $select_query = "SELECT timeframe_id, start_time FROM timeframe WHERE schedule_id = ?";
        $stmt = $con->prepare($select_query);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $existing_timeframes[$row['start_time']] = $row['timeframe_id'];
        }
        $stmt->close();

        $slots = generateTimeSlots($schedule_id, $start_time, $end_time, $nums);

        foreach ($slots as $slot) {
            if (isset($existing_timeframes[$slot['start_time']])) {
                // Nếu timeframe đã tồn tại, cập nhật nó
                $timeframe_id = $existing_timeframes[$slot['start_time']];
                $update_query = "UPDATE timeframe SET available = ? WHERE timeframe_id = ?";
                $update_stmt = $con->prepare($update_query);
                $update_stmt->bind_param("ii", $slot['available'], $timeframe_id);
                $update_stmt->execute();
                $update_stmt->close();
            
                // Cập nhật doctor_status của các appointment đã hủy về 1 nếu có
                $activate_query = "UPDATE appointment 
                SET doctor_status = 1 
                WHERE timeframe_id = ? 
                AND doctor_status = 0 
                AND appointment_order <= ?"; // Ensure only valid appointments are reactivated
                $activate_stmt = $con->prepare($activate_query);
                $activate_stmt->bind_param("ii", $timeframe_id, $nums); // Bind both timeframe_id and nums
                $activate_stmt->execute();
                $activate_stmt->close();
            
                // Xóa timeframe này khỏi danh sách để không bị xóa sau này
                unset($existing_timeframes[$slot['start_time']]);
            } else {
                // Nếu timeframe chưa tồn tại, thêm mới
                $insert_query = "INSERT INTO timeframe (schedule_id, start_time, available, booked) VALUES (?, ?, ?, 0)";
                $insert_stmt = $con->prepare($insert_query);
                $insert_stmt->bind_param("isi", $slot['schedule_id'], $slot['start_time'], $slot['available']);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }

        // Xóa các timeframe còn lại trong existing_timeframes vì chúng không còn phù hợp
        if (!empty($existing_timeframes)) {
            foreach ($existing_timeframes as $start_time => $timeframe_id) {
                // Kiểm tra xem timeframe có appointment hay không
                $appointment_check_query = "SELECT COUNT(*) as count FROM appointment WHERE timeframe_id = ?";
                $check_stmt = $con->prepare($appointment_check_query);
                $check_stmt->bind_param("i", $timeframe_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $count_row = $check_result->fetch_assoc();
                $appointment_count = $count_row['count'];
                $check_stmt->close();

                if ($appointment_count > 0) {
                    // Nếu có appointment, cập nhật doctor_status của tất cả các appointment trong timeframe đó
                    $update_status_query = "UPDATE appointment SET doctor_status = 0 WHERE timeframe_id = ?";
                    $update_status_stmt = $con->prepare($update_status_query);
                    $update_status_stmt->bind_param("i", $timeframe_id);
                    $update_status_stmt->execute();
                    $update_status_stmt->close();
                } else {
                    // Nếu không có appointment, xóa timeframe
                    $delete_query = "DELETE FROM timeframe WHERE timeframe_id = ?";
                    $delete_stmt = $con->prepare($delete_query);
                    $delete_stmt->bind_param("i", $timeframe_id);
                    $delete_stmt->execute();
                    $delete_stmt->close();
                }
            }
        }
    }
?>










func1.php





<!-- Login or Register as Patient  -->

<?php
  session_start();

  include('connection.php');
  if(isset($_POST['patsub'])){
    $phone_number=$_POST['phone_number'];
    $password=$_POST['password1'];
    $query="SELECT * FROM patient WHERE phone_number='$phone_number';";
    $result=mysqli_query($con,$query);
    if(mysqli_num_rows($result)==1) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $hashed_password = $row['password'];  // The stored hashed password

        // Verify the entered password against the hashed password
        if(password_verify($password, $hashed_password)) {
            $_SESSION['patient_id'] = $row['patient_id'];
            $_SESSION['phone_number'] = $row['phone_number'];
            $_SESSION['patient_name'] = $row['patient_name'];

            header("Location: patient-panel.php");
            exit();
        } else {
            echo "<script>alert('Invalid Phone number or Password. Try Again!');
            window.location.href = 'index.php';</script>";
        }
    }
    else {
      echo("<script>alert('Invalid Phone number or Password. Try Again!');
        window.location.href = 'index.php';</script>");
    }
  }


  // Xử lý đăng ký mới
  if(isset($_POST['patsub1'])) {
    $patient_name=$_POST['patient_name'];
    $gender=$_POST['gender'];
    $address=$_POST['address'];
    $DOB=$_POST['DOB'];
    $email=$_POST['email'];
    $phone_number=$_POST['phone_number'];
    $password=$_POST['password'];
    $con_password=$_POST['con_password'];

    // Kiểm tra ngày sinh không vượt quá ngày hiện tại
    if(strtotime($DOB) > time()){
      echo "<script>
        alert('The date of birth is invalid!');
        window.history.back();
      </script>";
      exit();
    }

    // Kiểm tra số điện thoại đã tồn tại chưa
    $checkPhoneQuery = "SELECT * FROM patient WHERE phone_number = '$phone_number'";
    $checkPhoneResult = mysqli_query($con, $checkPhoneQuery);

    if(mysqli_num_rows($checkPhoneResult) > 0){
      echo "<script>
        alert('The phone number is registered!');
        window.history.back();
      </script>";
      exit();
    }

    if($password==$con_password){
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $que = "INSERT INTO account(role_id,status) values ('1','1');";
      if ($con->query($que) === TRUE) {
        // Lấy account_id mới được tạo
        $account_id = $con->insert_id;

        $query = "INSERT into patient(account_id,patient_name,gender,address,DOB,email,phone_number,password,con_password) values ('$account_id','$patient_name','$gender','$address','$DOB','$email','$phone_number','$hashed_password','$hashed_password');";
        if ($con->query($query) === TRUE) {
          $_SESSION['account_id'] = $account_id;
          $_SESSION['patient_id'] = $con->insert_id;
          $_SESSION['patient_name'] = $_POST['patient_name'];
          $_SESSION['gender'] = $_POST['gender'];
          $_SESSION['address'] = $_POST['address'];
          $_SESSION['DOB'] = $_POST['DOB'];
          $_SESSION['email'] = $_POST['email'];
          $_SESSION['phone_number'] = $_POST['phone_number'];
          
          // Hiển thị thông báo thành công và chuyển hướng đến patient-panel
          echo "<script>
            alert('Register a new patient successfully!');
            window.location.href = 'patient-panel.php';
          </script>";
        } else {
          echo "Lỗi: " . $query . "<br>" . $con->error;
        }
      } else {
        echo "Lỗi: " . $que . "<br>" . $con->error;
      }
    } else {
      echo "<script>
        alert('Password and Confirm Password are not matched!');
        window.history.back();
      </script>";
      exit();
    }
  }
?>



