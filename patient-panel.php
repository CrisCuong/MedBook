<!-- Patient panel -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
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

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Thư viện biểu đồ -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  </head>

  <?php
    include('func1.php');
    // include('newfunc.php');
    // Kiểm tra session, nếu không tồn tại, chuyển hướng về trang login
    if (!isset($_SESSION['patient_id'])) {
      header("Location: index.php");
      exit();
    }
    
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
            <!-- Notification Bell Icon for Patient -->
            <div class="dropdown" style="position: relative;margin: 5px 20px 0 0">
              <div id="notification-icon" class="icon" style="cursor:pointer;">
                <i class="fas fa-bell" style="font-size: 30px; color: #60c2fe;"></i>
                <span id="patientNotificationCount"></span>
              </div>
              <ul id="patientNotificationList" class="dropdown-menu notification-list" style="display:none"> 
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
                <a class="dropdown-item" href="patient/edit-profile.php">Edit profile</a>
                <a class="dropdown-item" href="patient/change-password.php">Change password</a>
                <a class="dropdown-item" href="#" onclick="clearTabAndLogout()">Logout</a>
              </div>
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
            <a class="list-group-item list-group-item-action" href="#list-doc" id="list-doc-list" role="tab" data-toggle="list" aria-controls="home">Search Doctor</a>
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
                  <div class="dashboard-row">
                    <!-- Upcoming appointment -->
                    <section class="overview">
                      <div class="overview-card">
                        <i class="fas fa-calendar-alt card-icon"></i>
                        <h4>Upcoming appointment</h4>
                        <p>You have <span id="upcoming-count"></span> upcomming appointments</p>
                        <ul id="upcoming-appointments-list"></ul>
                      </div>
                    </section>

                    <div class="dashboard-row doctor-recommendations">
                      <h4>Recent Medical Doctor Offices</h4>
                      <div class="doctor-list">
                        <div class="doctor-card-dash">
                          <img src="" alt="" class="doctor-avatar">
                          <div class="doctor-info">
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  
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
                    <h4 id="docDetailHeading" style="margin: 0px 80px 10px 0px;">Search Doctor</h4> 
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

                    // Skip this timeframe if booked equals available
                    if (booked >= available) {
                      return; // Do not show this timeframe
                    }

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
                        // Reset các trường select về mặc định
                        resetSelectFields();
                        // switchView('appoList');
                        updateBookedCount(time);
                        loadNotificationCount(); // Load notification count after booking
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

              // Hàm để reset tất cả các trường select về giá trị mặc định
              function resetSelectFields() {
                  document.getElementById('specialitySelect').selectedIndex = 0;
                  document.getElementById('doctorSelect').innerHTML = '<option>Select Doctor</option>';
                  document.getElementById('dateSelect').innerHTML = '<option>Select Date</option>';
                  document.getElementById('timeSelect').innerHTML = '<option>Select Time</option>';
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
      function bookAppointment(specialityId, doctorId) {
        // Chuyển đến tab Book Appointment với doctor chọn sẵn
        console.log('Speciality ID:', specialityId);
        console.log('Doctor ID:', doctorId);
        document.getElementById('list-book-list').click();
        setTimeout(() => {
          preSelectSpecialityAndDoctor(specialityId, doctorId);
        }, 300);
      }

      function preSelectSpecialityAndDoctor(specialityId, doctorId) {
        // Fetch and populate the Speciality dropdown
        fetch('patient/fetch-book.php?action=selectSpecialities')
        .then(response => response.json())
        .then(data => {
          let specialitySelect = document.getElementById('specialitySelect');
          specialitySelect.innerHTML = '<option>Select Speciality</option>'; // Clear previous options
            
          // Populate speciality options
          data.forEach(speciality => {
            let option = document.createElement('option');
            option.value = speciality.speciality_id;
            option.textContent = speciality.speciality_name;
            specialitySelect.appendChild(option);
          });

          // Pre-select the speciality
          if (specialityId) {
            specialitySelect.value = specialityId;
          }

          // Fetch and populate the Doctor dropdown based on selected Speciality
          selectDoctorsForPreSelection(specialityId, doctorId);
        })
        .catch(error => {
          console.error('Error fetching specialities:', error);
        });
      }

      function selectDoctorsForPreSelection(specialityId, doctorId) {
        fetch(`patient/fetch-book.php?action=selectDoctors&speciality_id=${specialityId}`)
        .then(response => response.json())
        .then(data => {
          let doctorSelect = document.getElementById('doctorSelect');
          doctorSelect.innerHTML = '<option>Select Doctor</option>'; // Clear previous options
          
          // Populate doctor options
          data.forEach(doctor => {
            let option = document.createElement('option');
            option.value = doctor.doctor_id;
            option.textContent = doctor.doctor_name;
            doctorSelect.appendChild(option);
          });

          // Pre-select the doctor
          if (doctorId) {
            doctorSelect.value = doctorId;
            selectAvailableDates();
          }
        })
        .catch(error => {
          console.error('Error fetching doctors:', error);
        });
      }


      
      document.addEventListener('DOMContentLoaded', function() {
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
            document.getElementById('search-bar').value = '';
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'block';
            docDetailForm.style.display = 'none';
            docDetailHeading.textContent = 'Search Doctor';
            bookAppoContainer.style.display = 'none';
            appoListContainer.style.display = 'none';
            backButton.style.display = 'none';
            currentView = 'docDetail';
          } else if (view === 'docDetailView') {
            dashListContainer.style.display = 'none';
            docListContainer.style.display = 'none';
            docDetailForm.style.display = 'block';
            docDetailHeading.textContent = 'Doctor Detail';
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
            // switchView('dashList');
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
        // Script của nút chuông thông báo
        // Function to load notifications count
        window.loadNotificationCount = function() {
          $.ajax({
            url: 'notification/load_patient_notifications.php', // The PHP file that fetches notifications count
            method: 'GET',
            dataType: 'json',
            success: function (data) {
              if (data.success) {
                // Update the badge with unread notification count
                if (data.unreadCount > 0) {
                  $('#patientNotificationCount').text(data.unreadCount > 99 ? '99+' : data.unreadCount).show();
                } else {
                  $('#patientNotificationCount').hide();
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
          loadPatientNotifications();
        });

        loadNotificationCount();

        // Function to load notifications
        function loadPatientNotifications() {
          $.ajax({
            url: 'notification/load_patient_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
              if (data.success) {
                // Update the notification list
                let notificationList = $('#patientNotificationList');
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
                $('#markAllAsReadBtn').on('click', function(event) {
                  event.stopPropagation(); // Prevent redirect action
                  markAllNotificationsAsRead();
                });

                // Show the notification list
                notificationList.slideToggle();

                // Attach click event listener to notification items
                notificationList.off('click').on('click', '.dropdown-item', function() {
                  const notificationId = $(this).data('id');
                  const appointmentId = $(this).data('appo-id');                  
                  if (notificationId) {
                    event.preventDefault(); // Prevent default action if marking as read
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
            url: 'notification/mark_all_notifications_read_patient.php',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                console.log('All notifications marked as read');
                loadNotificationCount(); // Cập nhật lại số lượng thông báo chưa đọc
                loadPatientNotifications(); // Tải lại danh sách thông báo để hiển thị trạng thái mới
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
                loadPatientNotifications();
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
            $('#patientNotificationList').hide(); // Hide if clicked outside
          }
        });

        function viewAppointmentStatus(notificationId, appointmentId) {
          // Chuyển đến tab Appointment Detail
          console.log('Notifcation ID:', notificationId);
          console.log('Appointment ID:', appointmentId);
          document.getElementById('list-appo-list').click();
          switchView('appoDetail');
          viewDetail(appointmentId);
        }


        // ------------------------------------------------------------------------------------------
        // Script dưới liên quan đến tab dashboard
        fetch('patient/dashPatient.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error(data.error);
            alert('Error: ' + data.error);
            return;
          }

          // Cập nhật danh sách lịch hẹn
          const upcomingList = document.getElementById('upcoming-appointments-list');
          const upcomingCount = document.getElementById('upcoming-count');
          upcomingList.innerHTML = '';
          data.todayAppointments.forEach(appointment => {
            const li = document.createElement('li');
            li.textContent = `Dr. ${appointment.doctor_name} (${appointment.speciality_name}) on ${appointment.formatted_date} at ${appointment.start_time}`;
            upcomingList.appendChild(li);
          });
          upcomingCount.textContent = data.todayAppointments.length;

          // Cập nhật danh sách bác sĩ gần đây
          const doctorList = document.querySelector('.doctor-list');
          doctorList.innerHTML = '';
          data.recentDoctors.forEach(doctor => {
            const doctorCard = document.createElement('div');
            doctorCard.className = 'doctor-card-dash';
            doctorCard.innerHTML = `
              <img src="${doctor.profile_pic}" alt="${doctor.doctor_name}" class="doctor-avatar">
              <div class="doctor-info">
                <h4>${doctor.doctor_name}</h4>
                <p>${doctor.speciality_name}</p>
              </div>
            `;
            doctorList.appendChild(doctorCard);
          });
        })
        .catch(error => console.error('Error fetching data:', error));


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

              doctors.forEach((doctor, index) => {
                console.log(doctor); // Log the doctor object to check if speciality_id is available
                
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
                    <button class="btn book-button" onclick="bookAppointment(${doctor.speciality_id}, ${doctor.doctor_id})">Booking</button>
                  </div>
                `;
                resultContainer.appendChild(doctorElement);
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
          fetch(`patient/fetch-doctor-details.php?id=${doctorId}`)
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              docDetailForm.innerHTML = `<p>${data.error}</p>`;
            } else {
              // Ensure the first line also starts with a bullet
              const formattedIntro = data.intro ? '• ' + data.intro.replace(/\n/g, '\n• ') : '';

              docDetailForm.innerHTML = `
                <div class="detail-container">
                  <div class="row">
                    <div class="col-3">
                      <img src="${data.profile_pic}" alt="Doctor's Profile Picture" class="img-fluid" />
                    </div>
                    <div class="col-9 info-detail">
                      <div class="doctor-info-detail">
                        <h3 style="display: inline-block; margin-right: 30px; margin-bottom: 20px">Dr. ${data.doctor_name}</h3>
                        <p style="display: inline-block; margin-left: 120px;">${data.speciality_name}</p>
                      </div>
                      <p style="margin: 0 0 0 40px"><strong>
                        <i class="fas fa-map-marker-alt"></i></strong>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(data.address + ', Bien Hoa, Dong Nai')}" target="_blank">
                          ${data.address}, Bien Hoa, Dong Nai
                        </a>
                      </p>  
                      <p style="margin: 0 0 0 40px">Business License: ${data.business_license}</p>
                      <p style="margin: 0 0 0 40px">Schedule: ${data.business_license}</p>                                                  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <p style="margin: 16px 0 10px 0"><strong>Introduction</strong></p>
                      <pre class="doctor-intro-text">${formattedIntro}</pre> 
                    </div>
                  </div>
                  <button class="book-button" onclick="bookAppointment(${data.speciality_id}, ${doctorId})">Booking Now</button>
                </div>
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
                      <button class="btn book-button" onclick="bookAppointment(${doctor.speciality_id}, ${doctor.doctor_id})">Booking</button>
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
                  <div class="appointment-details" style="font-size: 17px">
                    <div class="left-section">
                      <p><strong>Appointment Order:</strong></p>
                      <p class="appo-order">${appointment.appointment_order}</p>
                    </div>
                    <div class="right-section" style="text-align: left">
                      <p><strong>Doctor Name:</strong> ${appointment.doctor_name}</p>
                      <p><strong>Speciality:</strong> ${appointment.speciality_name}</p>
                      <p>
                        <strong>Address:</strong>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(appointment.address + ', Bien Hoa, Dong Nai')}" target="_blank">
                          ${appointment.address}, Bien Hoa, Dong Nai
                        </a>
                      </p>
                      <p><strong>Date:</strong> ${formatDate(appointment.date)}</p>
                      <p><strong>Timeframe:</strong> ${formatTimeframe(appointment.start_time)}</p>
                      <p><strong>Status:</strong> ${getCurrentStatus(appointment.doctor_status, appointment.patient_status)}</p>
                      ${appointment.doctor_status === 0 ? `
                      <button id="other-doctors-btn" class="btn">Other Doctors</button>
                    ` : ''}
                    </div>
                    
                  </div>
                `;

                if (appointment.doctor_status === 0) {
                  document.getElementById('other-doctors-btn').addEventListener('click', () => {
                    switchToDoctorSearch(appointment.speciality_name);
                  });
                }
                switchView('appoDetail');
              }
            })
            .catch(error => console.error('Error fetching appointment details:', error));
        }

        // Hàm chuyển sang tab tìm kiếm bác sĩ và thực hiện tìm kiếm
        function switchToDoctorSearch(specialityName) {
          //switchView('docDetail'); // Chuyển sang tab tìm kiếm bác sĩ
          document.getElementById('list-doc-list').click();
          searchInput.value = specialityName; // Đặt giá trị thanh tìm kiếm
          performSearch(); // Thực hiện tìm kiếm
          
        }

        // Click back, go to appointment list
        appoBackButton.addEventListener('click', function() {
          switchView('appoList');
        });

        function getCurrentStatus(doctorStatus, patientStatus, isPast) {
          if (isPast) return '<span style="color: #ff8000;">Overdue</span>';
          if (doctorStatus === 1 && patientStatus === 1) {
            return '<span style="color: #0eb50e;">Active</span>';
          } else if (doctorStatus === 0 && patientStatus === 1) {
            return '<span style="color: red;">Canceled by Doctor</span>';
          } else if (doctorStatus === 1 && patientStatus === 0) {
            return '<span style="color: red;">Canceled by Patient</span>';
          }
          return '<span style="color: #808080;">Unknown</span>';
        }

        // Fetch the appointment list and display them
        function fetchAppointments() {
          fetch('patient/fetch-appo.php')
          .then(response => response.json())
          .then(data => {
            const tableBody = document.querySelector('#appoListTable tbody');
            tableBody.innerHTML = ''; // Xóa nội dung hiện tại của bảng

            if (data.length > 0) {
              data.forEach(appointment => {
                const appointmentDateTime = new Date(`${appointment.date} ${appointment.start_time}`);
                const now = new Date();
                const isPast = appointmentDateTime < now; // Kiểm tra xem cuộc hẹn đã qua chưa
                const currentStatus = getCurrentStatus(appointment.doctor_status, appointment.patient_status, isPast);
                const isActive = currentStatus === '<span style="color: #0eb50e;">Active</span>';

                const row = document.createElement('tr');
                row.innerHTML = `
                  <td style="text-align: left">Dr. ${appointment.doctor_name}</td>
                  <td>${formatDate(appointment.date)}</td>
                  <td>${formatTimeframe(appointment.start_time)}</td>
                  <td>${currentStatus}</td>
                  <td>
                    ${renderActionButtons(currentStatus, appointment, isPast)}
                  </td>
                `;
                tableBody.appendChild(row);
              });

              // Add event listener for Book Again buttons
              document.querySelectorAll('.book-again-btn').forEach(button => {
                button.addEventListener('click', function () {
                  const specialityId = this.getAttribute('data-speciality-id');
                  const doctorId = this.getAttribute('data-doctor-id');
                  bookAppointment(specialityId, doctorId);
                });
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
              row.innerHTML = '<td class="alert-warning" colspan="5">No appointments found.</td>';
              tableBody.appendChild(row);
            }
          })
          .catch(error => console.error('Error fetching appointments:', error));
        }

        // Function to render action buttons based on status
        function renderActionButtons(currentStatus, appointment, isPast) {
          if (currentStatus.includes('Overdue')) {
            return `<button class="book-again-btn" 
              data-speciality-id="${appointment.speciality_id}" 
              data-doctor-id="${appointment.doctor_id}">
              Book Again
            </button>`;
          } else if (currentStatus.includes('Active')) {
            return `<button class="detail-btn" data-appointment-id="${appointment.appointment_id}">Detail</button>
            <button class="cancel-btn" data-appointment-id="${appointment.appointment_id}" 
            onclick="confirmCancel(${appointment.appointment_id})">Cancel</button>`;
          } else if (currentStatus.includes('Canceled')) {
            return `<button class="detail-btn" data-appointment-id="${appointment.appointment_id}">Detail</button>`;
          }
          return ''; // No buttons for unknown status
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
              loadNotificationCount();
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
