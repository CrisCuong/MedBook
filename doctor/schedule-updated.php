<?php
    session_start(); // Đảm bảo session đã được khởi tạo
    include('../connection.php');
    if (!isset($_SESSION['doctor_id'])) {
        die('Doctor ID is not set in the session.');
    }
    $doctor_id = $_SESSION['doctor_id']; // Ensure you have the doctor_id
    $response = ['status' => 'error', 'message' => 'An error occurred.'];

    // Kiểm tra trạng thái của bác sĩ trước khi thực hiện cập nhật
    $doctor_query = "SELECT status FROM doctor WHERE doctor_id = '$doctor_id'";
    $doctor_result = mysqli_query($con, $doctor_query);

    if (mysqli_num_rows($doctor_result) > 0) {
        $doctor_row = mysqli_fetch_assoc($doctor_result);
        $doctor_status = $doctor_row['status'];

        // Nếu status của bác sĩ không phải là 1, thông báo và không cập nhật
        if ($doctor_status != 1) {
            $response = ['status' => 'error', 'message' => 'You are inactive. Cannot update the schedule.'];
            echo json_encode($response);
            exit; // Dừng thực hiện nếu bác sĩ không hoạt động
        }
    } else {
        // Nếu không tìm thấy bác sĩ trong hệ thống
        $response = ['status' => 'error', 'message' => 'Doctor not found.'];
        echo json_encode($response);
        exit; // Dừng thực hiện nếu không tìm thấy bác sĩ
    }


    // Tiếp tục thực hiện cập nhật khi status của bác sĩ là 1
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $schedule_data = $_POST['schedule'];

        foreach ($schedule_data as $date => $data) {
            $start_time = $data['start_time'];
            $end_time = $data['end_time'];
            $nums = $data['nums'];
            $status = isset($data['status']) ? 1 : 0;

            if (strtotime($start_time) >= strtotime($end_time)) {
                $response = ['status' => 'error', 'message' => 'Start time cannot be greater than or equal to end time. Schedule update failed.'];
                echo json_encode($response);
                exit; // Dừng thực hiện nếu start_time không hợp lệ
            }

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

            // Xử lý khi status của schedule == 1
            // if ($status == 1) {
            //     // Lấy danh sách tất cả các timeframe hiện tại cho schedule_id
            //     $existing_timeframes = [];
            //     $timeframes_query = "SELECT timeframe_id FROM timeframe WHERE schedule_id = '$schedule_id'";
            //     $timeframes_result = mysqli_query($con, $timeframes_query);
                
            //     while ($tf_row = mysqli_fetch_assoc($timeframes_result)) {
            //         $tf_id = $tf_row['timeframe_id'];
            //         $existing_timeframes[] = $tf_id;
            
            //         // Kích hoạt lại các cuộc hẹn (doctor_status = 1) trong timeframe này
            //         $activate_appointments_query = "UPDATE appointment 
            //                                         SET doctor_status = 1 
            //                                         WHERE timeframe_id = '$tf_id' AND doctor_status = 0";
            //         mysqli_query($con, $activate_appointments_query);
            //     }
            
            //     // Nếu không có timeframe, tạo mới
            //     if (empty($existing_timeframes)) {
            //         // Tạo mới timeframe
            //         $slots = generateTimeSlots($schedule_id, $start_time, $end_time, $nums);
            
            //         foreach ($slots as $slot) {
            //             // Thêm mới timeframe
            //             $insert_timeframe_query = "INSERT INTO timeframe (schedule_id, start_time, available, booked) 
            //                                        VALUES (?, ?, ?, 0)";
            //             $insert_stmt = $con->prepare($insert_timeframe_query);
            //             $insert_stmt->bind_param("isi", $slot['schedule_id'], $slot['start_time'], $slot['available']);
            //             $insert_stmt->execute();
            //             $insert_stmt->close();
            //         }
            //     }
            // }
            

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
