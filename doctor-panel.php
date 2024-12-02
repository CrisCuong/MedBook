<!-- Panel của Doctor -->

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
    <link rel="shortcut icon" type="image/x-icon" href="img/image/MedBook_icon.jpeg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <!-- Bootstrap chuyển tab -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <!-- Thông báo khi update schedule -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#schedule-form').submit(function(event) {
          event.preventDefault(); // Ngăn chặn hành vi gửi form mặc định
          $.ajax({
            url: 'doctor/schedule-updated.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                alert(response.message); // Hiển thị thông báo thành công
                  window.location.href = 'doctor-panel.php'; // Chuyển hướng về trang schedule
                } else {
                  alert(response.message); // Hiển thị thông báo lỗi
                }
              },
              error: function() {
                alert('An error occurred.'); // Hiển thị thông báo lỗi nếu có lỗi trong quá trình gửi request
              }
            });
          });
        });
    </script>
  </head>
  
  <?php 
    include('func2.php');

    // Kiểm tra session, nếu không tồn tại, chuyển hướng về trang login
    if (!isset($_SESSION['doctor_id'])) {
      header("Location: index.php");
      exit();
    }
    
    $doctor_id = $_SESSION['doctor_id'];
    $doctor_name = $_SESSION['doctor_name'];
  ?>

  <body style="background-color: #91d9ff; margin-top: 120px">
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #fff">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="" style="color: #60c2fe; margin-top: 10px;margin-left:-25px;font-family: 'IBM Plex Sans', sans-serif;"><h1>MedBook</h1></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon" style="background-color:#60c2fe"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <!-- Notification Bell Icon for Doctor -->
            <div class="dropdown" style="position: relative;margin: 5px 20px 0 0">
              <div id="notification-icon" class="icon" style="cursor:pointer;">
                <i class="fas fa-bell" style="font-size: 30px; color: #60c2fe;"></i>
                <span id="doctorNotificationCount"></span>
              </div>
              <ul id="doctorNotificationList" class="dropdown-menu notification-list" style="display:none"> 
                <!-- Notifications will be populated here -->
                <li><button id="markAllAsReadBtn" class="dropdown-item">Mark All as Read</button></li>
              </ul>
            </div>

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
                <a class="dropdown-item" href="doctor/change-password.php">Change password</a>
                <a class="dropdown-item" href="#" onclick="clearTabAndLogout()">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <h3 style = "text-align: center;font-family:'IBM Plex Sans', sans-serif;"> Welcome Dr. <?php echo $doctor_name ?>!</h3>
      <div class="row">
        <div class="col-md-4" style="max-width:25%;margin-top: 2%">
          <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" href="#list-dash" role="tab" aria-controls="home" data-toggle="list">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="#list-app" id="list-app-list" role="tab" data-toggle="list" aria-controls="home">Appointment List</a>
            <a class="list-group-item list-group-item-action" href="#list-pro" id="list-pro-list" role="tab" data-toggle="list" aria-controls="home">Patient Profile</a>
            <a class="list-group-item list-group-item-action" href="#list-sche" id="list-sche-list" role="tab" data-toggle="list" aria-controls="home">Schedule</a>
            <a class="list-group-item list-group-item-action" href="#list-intro" id="list-intro-list" role="tab" data-toggle="list" aria-controls="home">Doctor Introduction</a>
          </div><br>
        </div>
    
        <div class="col-md-8" style="max-width:25%;margin-top: 2%; ">
          <div class="tab-content" id="nav-tabContent" style="width: 950px;">
            <!-- Dashboard -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
              <div class="container-content bg-white" >
                <h4 id="dashListHeading" style="margin: 0px 0px 30px 50px;">Dashboard</h4> 
                <div id="dashListContainer">

                  <!-- Section: Thống kê lịch hẹn -->
                  <div class="dash-section">
                    <div class="statistic-container">
                      <div class="statistic-cards">
                        <!-- Card 1: Tổng số lịch hẹn -->
                        <div class="stat-card">
                          <h6>Total appointments in this month</h6>
                          <p id="totalAppointments"></p>
                        </div>
                        <!-- Card 2: Số lịch hẹn hôm nay -->
                        <div class="stat-card">
                          <h6>Count appointments today</h6>
                          <p id="appointmentsToday"></p>
                        </div>
                      </div>
                      <!-- Biểu đồ lịch hẹn hàng tháng -->
                      <canvas id="monthlyAppointmentsChart" width="400px" height="200px"></canvas>
                    </div>
                  </div>

                  <!-- Section: Thống kê bệnh nhân -->
                  <div class="dash-section">
                    <h5>Patient Statistics</h5>
                    <div class="patient-statistic-cards">
                      <div class="stat-card">
                        <h6>Total patients</h6>
                        <p id="totalPatients">1200</p>
                      </div>
                      <div class="stat-card">
                        <h6>New patients in this month</h6>
                        <p id="newPatientsThisMonth">8</p>
                      </div>
                    </div>
                  </div>
                  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                  <script src="doctor/dashDoctor.js"></script>
                </div>
              </div>
            </div>
            
            <!-- Appointment List -->
            <div class="tab-pane fade" id="list-app" role="tabpanel" aria-labelledby="list-app-list">
              <div class="container-content bg-white">
              <h4 id="appoListHeading" style="margin: 0px 0px 30px 50px;">Appointment List</h4> 
                <div id="appoListContainer">
                  <table id="appoListTable">
                    <?php include('doctor/fetch-appo-doc.php'); ?>
                  </table>
                </div>
              </div>  
            </div>

            <!-- Patient Profile -->
            <div class="tab-pane fade" id="list-pro" role="tabpanel" aria-labelledby="list-pro-list">
              <div class="container-content bg-white" >
                <div class="row">
                  <div class="col-md-6">
                    <h4 id="patiProHeading" style="margin: 0px 80px 10px 0px;">Patient Profile</h4> 
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
                <div id="patiProContainer" style="height:260px">
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
              </div> 
            </div>

            <!-- Schedule -->
            <div class="tab-pane fade" id="list-sche" role="tabpanel" aria-labelledby="list-sche-list">
              <div class="container-content bg-white" >
                <form id="schedule-form" method="POST" action="doctor/schedule-updated.php">
                  <div class="row">
                    <div class="col-md-3 d-flex align-items-center">
                      <i class="fas fa-calendar-alt mr-2"></i>
                      <span id="current-day" class="ml-2"></span>
                      <input type="date" class="form-control" id="schedule-date" value="<?php echo date('Y-m-d'); ?>" readonly></input>
                    </div>
                    <!-- Hiển thị thứ hiện tại -->
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        // Lấy ngày hiện tại từ input
                        var scheduleDateInput = document.getElementById('schedule-date');
                        var currentDate = new Date(scheduleDateInput.value);                    
                        // Mảng các tên thứ trong tuần
                        var weekdays = ['Sunday, ', 'Monday, ', 'Tuesday, ', 'Wednesday, ', 'Thursday, ', 'Friday, ', 'Saturday, '];                    
                        // Lấy thứ của ngày hiện tại
                        var dayName = weekdays[currentDate.getDay()];                   
                        // Hiển thị thứ trong thẻ span
                        var daySpan = document.getElementById('current-day');
                        daySpan.textContent = dayName;
                      });
                    </script>

                    <div class="col-md-7">
                      <h4 id="scheHeading" style="margin: 0px 80px 10px 0px;">Schedule</h4> 
                    </div>
                    <div class="col-md-2">
                      <button class="float-right" id="update-button">Update</button>
                    </div>
                  </div>
                  <table class="table table-bordered table-hover mt-3">
                    <thead>
                      <tr>
                        <th>Weekdays</th>
                        <th>Start time</th>
                        <th>End time</th>
                        <th>Patients per 30-mins frame</th>
                        <th>Active</th>
                      </tr>
                    </thead>
                    <tbody id="schedule-body">
                      <?php
                        include('connection.php');
                        $current_date = date('Y-m-d');
                        // Mảng các ngày trong tuần
                        $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        // Mảng chứa dữ liệu lịch trình
                        $schedule_data = [];
                        // Truy vấn dữ liệu lịch trình cho 7 ngày tiếp theo
                        $query = "SELECT * FROM schedule WHERE doctor_id = '$doctor_id' AND date >= '$current_date' AND date < DATE_ADD('$current_date', INTERVAL 7 DAY)";
                        $result = mysqli_query($con, $query);
                        // Lưu dữ liệu lịch vào mảng
                        while ($row = mysqli_fetch_assoc($result)) {
                            $schedule_data[$row['date']] = $row;
                        }
                        // Hiển thị lịch trình cho 7 ngày tiếp theo
                        for ($i = 0; $i < 7; $i++) {
                          $date = date('Y-m-d', strtotime("+$i day", strtotime($current_date)));
                          $day_name = $weekdays[date('w', strtotime($date))];
                          $formatted_date = date('d/m', strtotime($date));
                          // Kiểm tra nếu có dữ liệu lịch trình cho ngày này
                          if (isset($schedule_data[$date])) {
                            $schedule = $schedule_data[$date];
                            $start_time = $schedule['start_time'];
                            $end_time = $schedule['end_time'];
                            $nums = $schedule['nums'];
                            $checked = $schedule['status'] ? 'checked' : '';
                          } else {
                            // Mặc định nếu không có dữ liệu
                            $start_time = '17:00';
                            $end_time = '20:00';
                            $nums = 6;
                            $checked = '';
                          }
                          echo "
                          <tr>
                            <td>$day_name<br><small>$formatted_date</small></td>
                            <td><input type='time' class='form-control' name='schedule[$date][start_time]' value='$start_time'></td>
                            <td><input type='time' class='form-control' name='schedule[$date][end_time]' value='$end_time'></td>
                            <td><input type='number' class='form-control' name='schedule[$date][nums]' value='$nums'></td>
                            <td>
                              <div class='custom-control custom-switch toggle'>
                                <input type='checkbox' class='custom-control-input' id='active-$date' name='schedule[$date][status]' $checked>
                                <label class='custom-control-label' for='active-$date'></label>
                              </div>
                            </td>
                          </tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </form>
              </div>
              
            </div>
          
            <!-- Doctor Introduction -->
            <div class="tab-pane fade" id="list-intro" role="tabpanel" aria-labelledby="list-intro-list">
              <div class="container-content bg-white" >
                <h4 id="introListHeading" style="margin: 0px 0px 30px 50px;">Doctor Introduction</h4> 
                <div id="introListContainer">
                  <div class="row content-dash">
                    <div class="col-md-8">
                      <textarea id="intro-text" rows="10" cols="60" maxlength="1000" oninput="countCharacters()"></textarea>
                      <div><span id="charCount">0</span>/1000 characters</div> <!-- Bộ đếm ký tự -->
                    </div>
                    <div class="col-md-4">
                      <p>• Just enter text, with each 'newline' the system will automatically add bullets to that paragraph.</p>
                      <p>• You cannot enter more than the specified number of characters.</p>
                    </div>
                  </div>
                  <br>
                  <div class="save-button">
                    <button id="save-btn" onclick="saveIntro()">Save</button>
                  </div>
                </div>
              </div>
              <script>
                // Lấy doctorID từ session PHP
                const doctorId = <?php echo $_SESSION['doctor_id']; ?>;

                // Đếm ký tự nhập vào
                function countCharacters() {
                  const textarea = document.getElementById("intro-text");
                  const textWithoutBulletsAndNewlines = textarea.value
                  .replace(/^•\s*/gm, '') // Loại bỏ bullet ở đầu mỗi dòng
                  .replace(/\n/g, ''); // Loại bỏ newline
                  const charCount = textWithoutBulletsAndNewlines.length;
                  document.getElementById("charCount").innerText = charCount;
                }

                // Tải sẵn đoạn giới thiệu nếu đã có
                function loadIntro() {
                  console.log("loadIntro() called");
                  fetch('doctor/doctor-intro.php')
                  .then(response => response.json())
                  .then(data => {
                    if (data.intro) {
                      const formattedIntro = data.intro.replace(/\n/g, '\n• '); // Thay newline bằng bullet
                      document.getElementById("intro-text").value = "• " + formattedIntro; // Hiện intro đã lưu
                      countCharacters(); // Cập nhật bộ đếm ký tự
                    }
                  })
                  .catch(error => console.error('Error fetching intro:', error)); // Thêm thông báo lỗi
                }

                // Lưu đoạn giới thiệu
                function saveIntro() {
                  let intro = document.getElementById("intro-text").value;
                  // Loại bỏ bullet trước khi lưu (nếu có bullet)
                  intro = intro.replace(/^•\s*/gm, ''); // Loại bỏ bullet '• ' ở đầu mỗi dòng
                  // Tạo lại bullet cho mỗi dòng văn bản khi có newline, nếu không có bullet
                  const formattedIntro = intro.split('\n').map(line => '• ' + line.trim()).join('\n');

                  // Lưu nội dung đã bỏ bullet (chỉ văn bản)
                  fetch('doctor/doctor-intro.php', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                      doctor_id: doctorId,
                      intro: formattedIntro // Chỉ lưu văn bản không bullet
                    })
                  })
                  .then(response => response.text())
                  .then(result => {
                    alert(result); // Thông báo thành công hoặc thất bại
                    loadIntro();
                  });
                }

                // Gọi loadIntro() khi chuyển qua tab Doctor Introduction
                document.getElementById('list-intro-list').onclick = function() {
                  console.log("Doctor Introduction tab is clicked");
                  loadIntro();
                };
              </script>
            </div>
              

          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        /* ----------------------------------------------------------------------------- */
        /* Liên quan tới quản lý tab*/
        const defaultTab = '#list-dash'; // ID của tab Dashboard
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
            activateTab(tabId);
            
            // Xóa tham số phone_number khỏi URL khi chuyển tab khác tab "Patient Profile"
            if (tabId !== '#list-pro') {
              const currentUrl = window.location.href.split('?')[0]; // Lấy URL không có tham số
              history.replaceState(null, null, currentUrl); // Thay đổi URL mà không tải lại trang
              document.getElementById("phone_number").value = ''; // Reset input phone number
            }
          });
        });

        let activeTab = localStorage.getItem('activeTab');
        if (!isLoggedIn) {
          activeTab = defaultTab;
          sessionStorage.setItem('isLoggedIn', 'true'); // Đánh dấu là đã đăng nhập
        }

        // Giữ tab Patient Profile đang hoạt động khi nhấn Enter để tìm kiếm
        if (activeTab) {
          activateTab(activeTab);
        }

        // ------------------------------------------------------------------------------------------
        // Script của nút chuông thông báo
        loadNotificationCount();

        // Function to load notifications count
        function loadNotificationCount() {
          $.ajax({
            url: 'notification/load_doctor_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
              if (data.success) {
                // Update the badge with unread notification count
                if (data.unreadCount > 0) {
                  $('#doctorNotificationCount').text(data.unreadCount > 99 ? '99+' : data.unreadCount).show();
                } else {
                  $('#doctorNotificationCount').hide();
                }
              } else {
                console.log(data.message);
              }
            },
            error: function () {
              console.log('Error loading notification count');
            }
          });
        }
        // Add click event listener to the notification icon
        document.getElementById('notification-icon').addEventListener('click', function() {
          loadDoctorNotifications();
        });

        // Function to load notifications
        function loadDoctorNotifications() {
          $.ajax({
            url: 'notification/load_doctor_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
              if (data.success) {
                // Update the notification list
                let notificationList = $('#doctorNotificationList');
                notificationList.empty(); // Clear the old list

                data.notifications.forEach(function(notification) {
                  let notificationClass = notification.status === 'unread' ? 'unread' : 'read';
                  let notificationItem = `
                    <a class="dropdown-item ${notificationClass}" data-id="${notification.notification_id}" data-appo-id="${notification.appointment_id}" href="#">
                      <div class="notification-content">
                        <span class="notification-message">${notification.message}</span>
                        <br>
                        <span class="notification-time">${notification.created_at}</span>
                      </div>
                    </a>
                  `;
                  notificationList.append(notificationItem);
                });

                // Hiển thị nút "Mark All as Read" ở cuối danh sách
                notificationList.prepend('<li><button id="markAllAsReadBtn" class="dropdown-item" style="text-align: center; color: #007bff; text-decoration: underline; font-size: 0.9em;">Mark All as Read</button></li>');
                
                // Thêm sự kiện click cho nút "Mark All as Read" sau khi thêm vào danh sách
                $('#markAllAsReadBtn').on('click', markAllNotificationsAsRead);

                // Show the notification list
                notificationList.slideToggle();

                // Attach click event listener to notification items
                notificationList.off('click').on('click', '.dropdown-item', function() {
                  const notificationId = $(this).data('id');
                  const appointmentId = $(this).data('appo-id');                  
                  if (notificationId) {
                    markNotificationAsRead(notificationId);
                    viewAppointmentStatus(notificationId, appointmentId);
                  }
                });
              } else {
                console.log(data.message);
              }
            },
            error: function(xhr, status, error) {
              console.error('Error loading notifications:', error);
              console.log('Response:', xhr.responseText);
            }
          });
        }

        // Event listener for "Mark All as Read" button
        document.getElementById('markAllAsReadBtn').addEventListener('click', function() {
          markAllNotificationsAsRead(); // Gọi hàm để đánh dấu tất cả là đã đọc
        });

        let isMarkingAllRead = false;
        // Function to mark all notifications as read
        function markAllNotificationsAsRead() {
          if (isMarkingAllRead) return; // Nếu cờ đang bật, không thực hiện yêu cầu khác
          isMarkingAllRead = true;

          $.ajax({
            url: 'notification/mark_all_notifications_read.php',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                console.log('All notifications marked as read');
                loadNotificationCount(); // Cập nhật lại số lượng thông báo chưa đọc
                loadDoctorNotifications(); // Tải lại danh sách thông báo để hiển thị trạng thái mới
              } else {
                console.log('Error marking all notifications as read:', response.message);
              }
            },
            error: function(xhr, status, error) {
              console.error('Error marking all notifications as read:', error);
              console.log('Response:', xhr.responseText);
            },
            complete: function() {
              isMarkingAllRead = false; // Bỏ cờ sau khi hoàn tất
            }
          });
        }

        // Function to mark notification read
        function markNotificationAsRead(notificationId) {
          if (isMarkingAllRead) return;
          console.log('Marking notification as read:', notificationId); // Check if this logs the correct ID

          $.ajax({
            url: 'notification/mark_notification_read.php',
            method: 'POST',
            data: { id: notificationId },
            dataType: 'json', // Ensure to expect a JSON response
            success: function(response) {
              if (response.success) {
                console.log('Notification marked as read');
                loadNotificationCount();
                loadDoctorNotifications();
              } else {
                console.log('Error marking notification as read:', response.message);
              }
            },
            error: function(xhr, status, error) {
              console.error('Error updating notification status:', error);
              console.log('Response:', xhr.responseText); // Check the response for debugging
            }
          });
        }

        // Hide the notification list when clicking outside
        $(document).on('click', function(event) {
          if (!$(event.target).closest('#notification-icon').length) {
            $('#doctorNotificationList').hide(); // Hide if clicked outside
          }
        });

        // Set interval to reload notification count every 30 seconds (30000 milliseconds)
        // setInterval(loadNotificationCount, 30000);


        function viewAppointmentStatus(notificationId, appointmentId) {
          // Chuyển đến tab Appointment Detail
          console.log('Notifcation ID:', notificationId);
          console.log('Appointment ID:', appointmentId);
          document.getElementById('list-app-list').click();
        }




        /* --------------------------------------------------------------------------------- */
        /* Liên quan tới schedule*/
        // Confirm khi chuyển status từ 1 về 0
        const checkboxes = document.querySelectorAll('.custom-control-input');
        checkboxes.forEach(function(checkbox) {
          checkbox.addEventListener('change', function(event) {
            if (!this.checked) {  // Nếu chuyển từ 1 sang 0
              const confirmOffStatus = confirm('All appointments in this will be canceled completely. Do you want to turn off the status?');
              if (!confirmOffStatus) {
                event.preventDefault();  // Ngăn không cho checkbox thay đổi trạng thái
                this.checked = true;  // Đặt lại trạng thái checkbox về 1
              }
            }
          });
        });

        // Confirm khi thay đổi start_time, end_time, nums
        // function confirmInputChange(inputElement, event) {
        //   const confirmDeletion = confirm('Changing this value may affect all related timeframes and appointments. Do you want to proceed?');
        //   if (!confirmDeletion) {
        //     event.preventDefault();  // Ngăn không cho thay đổi giá trị
        //     inputElement.value = inputElement.defaultValue;  // Đặt lại giá trị ban đầu của input
        //   }
        // }

        // // Gán sự kiện confirm cho các input loại 'time' và 'number'
        // document.querySelectorAll("input[type='time'], input[type='number']").forEach(function(input) {
        //   input.addEventListener('change', function(event) {
        //     confirmInputChange(this, event);
        //   });
        // });





        /* --------------------------------------------------------------------------------- */
        /* Liên quan tới appointment list*/

        const cancelButtons = document.querySelectorAll('.cancel-btn');

        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const appointmentId = this.getAttribute('data-appointment-id');

                if (confirm('Do you want to cancel this appointment?')) {
                    // Gửi yêu cầu AJAX đến fetch-appo.php
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'doctor/cancel-appo.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            if (xhr.responseText.trim() === 'Success') {
                            // Cập nhật giao diện mà không cần reload
                            alert('Appointment canceled successfully.');
                            location.reload(); // Reload trang để cập nhật thông tin
                        } else {
                            // Hiển thị lỗi từ phía máy chủ
                            alert('Error: ' + xhr.responseText);
                        }
                        } else {
                            alert('An error occurred while canceling the appointment.');
                        }
                    };
                    xhr.send('appointment_id=' + appointmentId);
                }
            });
        });

        /* --------------------------------------------------------------------------------- */
        /* Liên quan tới doctor introduction */
        if (document.getElementById('list-intro').classList.contains('active')) {
          console.log("Page loaded and Doctor Introduction tab is active");
          loadIntro(); // Tự động gọi loadIntro khi trang load và tab Doctor Introduction đang được mở
        }

      });
      window.onpageshow = function(event) {
        if (event.persisted) {
            // Buộc reload trang khi back
            window.location.reload();
        }
      };

      // Xóa activeTab khi người dùng đăng xuất
      function clearTabAndLogout() {
        localStorage.removeItem('activeTab'); // Clear the active tab from localStorage
        location.href = 'logout.php'; // Proceed with the logout
      }
    </script>
  </body>
</html>



