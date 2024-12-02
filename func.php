<!-- Register as Patient dự bị -->

<?php
  // session_start();
  include('connection.php');
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
      $que = "insert into account(role_id,status) values ('1','1');";
      if ($con->query($que) === TRUE) {
        // Lấy account_id mới được tạo
        $account_id = $con->insert_id;

        $query = "insert into patient(account_id,patient_name,gender,address,DOB,email,phone_number,password,con_password) values ('$account_id','$patient_name','$gender','$address','$DOB','$email','$phone_number','$password','$con_password');";
        if ($con->query($query) === TRUE) {
          // $_SESSION['username'] = $_POST['patient_name']." ".$_POST['lname'];
          $_SESSION['account_id'] = $account_id;
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





