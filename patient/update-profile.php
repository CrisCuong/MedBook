<?php
    include('../func1.php');

    // Check if patient_id is available in the session
    if (!isset($_SESSION['patient_id'])) {
        die("User not logged in.");
    }
    $patient_id = $_SESSION['patient_id'];

    // Retrieve current patient data to compare with new data
    $sql = "SELECT patient_name, DOB, gender, address, email, phone_number FROM patient WHERE patient_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $current_patient_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Initialize an array to hold new data
    $updated_fields = [];

    // Check for posted data and only keep the ones that are changed
    $patient_name = isset($_POST['patient_name']) ? $_POST['patient_name'] : $current_patient_data['patient_name'];
    $DOB = isset($_POST['DOB']) ? $_POST['DOB'] : $current_patient_data['DOB'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : $current_patient_data['gender'];
    $address = isset($_POST['address']) ? $_POST['address'] : $current_patient_data['address'];
    $email = isset($_POST['email']) ? $_POST['email'] : $current_patient_data['email'];
    $phone_number = isset($_POST['phone']) ? $_POST['phone'] : $current_patient_data['phone_number'];

    // Validate DOB to ensure it's not a future date
    if ($DOB !== $current_patient_data['DOB']) {
        $currentDate = new DateTime();
        $dobDate = new DateTime($DOB);
        if ($dobDate > $currentDate) {
            echo "<script>
                alert('Date of birth cannot be in the future.');
                window.location.href = 'edit-profile.php';
            </script>";
            exit();
        }
    }

    // Check for duplicate phone number if it has changed
    if ($phone_number !== $current_patient_data['phone_number']) {
        $sql = "SELECT patient_id FROM patient WHERE phone_number = ? AND patient_id != ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $phone_number, $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<script>
                alert('This phone number is already in use.');
                window.location.href = 'edit-profile.php';
            </script>";
            exit();
        }
        $stmt->close();
    }

    // Prepare the SQL statement dynamically based on which fields are changed
    $sql = "UPDATE patient SET ";
    $params = [];
    $types = '';

    if ($patient_name !== $current_patient_data['patient_name']) {
        $sql .= "patient_name=?, ";
        $params[] = $patient_name;
        $types .= "s";
    }
    if ($DOB !== $current_patient_data['DOB']) {
        $sql .= "DOB=?, ";
        $params[] = $DOB;
        $types .= "s";
    }
    if ($gender !== $current_patient_data['gender']) {
        $sql .= "gender=?, ";
        $params[] = $gender;
        $types .= "s";
    }
    if ($address !== $current_patient_data['address']) {
        $sql .= "address=?, ";
        $params[] = $address;
        $types .= "s";
    }
    if ($email !== $current_patient_data['email']) {
        $sql .= "email=?, ";
        $params[] = $email;
        $types .= "s";
    }
    if ($phone_number !== $current_patient_data['phone_number']) {
        $sql .= "phone_number=?, ";
        $params[] = $phone_number;
        $types .= "s";
    }

    

    // Prepare and execute the update query if there are fields to update
    if (!empty($params)) {
        // Remove the trailing comma and space, then add the WHERE clause
        $sql = rtrim($sql, ', ') . " WHERE patient_id=?"; // Remove last comma
        $params[] = $patient_id;
        $types .= "i"; // patient_id is an integer
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params); // Use the spread operator to bind parameters dynamically

        if ($stmt->execute()) {
            $_SESSION['patient_name'] = $patient_name;
            echo "<script>
                alert('Profile updated successfully!');
                window.location.href = '../patient-panel.php';
            </script>";
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>
            alert('No changes detected.');
            window.location.href = 'edit-profile.php';
        </script>";
    }
?>
