<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'connection.php'; // Ensure this file is included and the connection is open

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

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
                // die('Password and Confirm password are not matched.');
                echo 'Password and Confirm password are not matched.';
            }

            // Check if email already exists
            $check_email_sql = "SELECT COUNT(*) FROM doctor WHERE email = ?";
            $stmt = $con->prepare($check_email_sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            if ($count > 0) {
                // echo json_encode(['error' => 'Email already exists.']);
                echo 'Email already exists.';
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
                // echo json_encode(['error' => 'Business License already exists.']);
                echo 'Business License already exists.';
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
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

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
                $stmt->bind_param('issssssssisi', $account_id, $doctor_name, $business_license, $address, $phone, $email, $password_hash, $password_hash,  $profile_pic, $speciality, $intro, $status);

                if ($stmt->execute()) {
                    echo 'Adding a new doctor successfully.';
                    // echo json_encode(['success' => 'Adding a new doctor successfully.']);

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
                        $mail->Subject = "Account Registration Successfully";
                        $mail->Body    = "
                        <html>
                        <head>
                            <title>Account Registration Successful</title>
                        </head>
                        <body>
                            <p>Dear Dr. $doctor_name,</p>
                            <p>Your account has been successfully registered!</p>
                            <p>Thank you for joining us.</p>
                            <p>Best regards,<br>MedBook Team</p>
                        </body>
                        </html>
                        ";

                        $mail->send(); // Gửi email

                        echo '<br>A confirmation email has been sent.';
                    } catch (Exception $e) {
                        echo 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}';
                    }


                } else {
                    echo 'Error: ' . $stmt->error;
                    // echo json_encode(['error' => 'Error: ' . $stmt->error]);
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
                echo 'Password and Confirm password do not match.';
                // echo json_encode(['error' => 'Password and Confirm password do not match.']);
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
                echo 'Email already exists.';
                // echo json_encode(['error' => 'Email already exists.']);
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
                echo 'Business License already exists.';
                // echo json_encode(['error' => 'Business License already exists.']);
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
                    echo 'Failed to upload image.';
                    // echo json_encode(['error' => 'Failed to upload image.']);
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
                    // echo json_encode(['suceess' => 'Doctor updated successfully.']);
                    echo "Doctor updated successfully.";
                } else {
                    echo json_encode(['error' => 'Error: ' . $stmt->error]);
                }
        
                $stmt->close();
            } else {
                // Nếu người dùng nhập mật khẩu mới, kiểm tra confirm password và cập nhật
                if ($password === $confirm_password) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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
                        $hashedPassword, 
                        $hashedPassword, 
                        $doctor_id
                    );
                    if ($stmt->execute()) {
                        //echo json_encode(['suceess' => 'Doctor updated successfully.']);
                        echo "Doctor updated successfully.";

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

                // Lấy tất cả appointment_id liên quan đến bác sĩ để xóa thông báo
                $query = "SELECT appointment_id FROM appointment WHERE timeframe_id IN (SELECT timeframe_id FROM timeframe WHERE schedule_id IN (SELECT schedule_id FROM schedule WHERE doctor_id = ?))";
                if ($stmt = $con->prepare($query)) {
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Lưu tất cả appointment_id liên quan
                    $appointment_ids = [];
                    while ($row = $result->fetch_assoc()) {
                        $appointment_ids[] = $row['appointment_id'];
                    }
                    $stmt->close();

                    // Xoá các thông báo liên quan đến các cuộc hẹn của bác sĩ
                    if (!empty($appointment_ids)) {
                        $appointment_id_list = implode(',', $appointment_ids);
                        $query = "DELETE FROM notification WHERE appointment_id IN ($appointment_id_list)";
                        if ($stmt = $con->prepare($query)) {
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }

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
                    $query = "SELECT timeframe_id, appointment_id FROM appointment WHERE patient_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $patient_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Lưu tất cả các `appointment_id` và `timeframe_id` liên quan đến các cuộc hẹn của bệnh nhân
                        $appointment_ids = [];
                        $timeframe_ids = [];
                        while ($row = $result->fetch_assoc()) {
                            $appointment_ids[] = $row['appointment_id'];
                            $timeframe_ids[] = $row['timeframe_id'];
                        }
                        $stmt->close();

                        // Xóa các thông báo liên quan đến các cuộc hẹn của bệnh nhân
                        if (!empty($appointment_ids)) {
                            $appointment_id_list = implode(',', $appointment_ids);
                            $query = "DELETE FROM notification WHERE appointment_id IN ($appointment_id_list)";
                            if ($stmt = $con->prepare($query)) {
                                $stmt->execute();
                                $stmt->close();
                            }
                        }

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

                    // Xóa các thông báo liên quan đến bệnh nhân dựa trên account_id
                    $query = "DELETE FROM notification WHERE account_id = ?";
                    if ($stmt = $con->prepare($query)) {
                        $stmt->bind_param("i", $account_id);
                        $stmt->execute();
                        $stmt->close();
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
                                    echo "Delete patient and related records successfully!";
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
                echo 'Speciality already exists.';
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