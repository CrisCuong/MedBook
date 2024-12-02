<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../connection.php';
    
    $identifier = $_POST['identifier'];
    
    // Kiểm tra xem số điện thoại có tồn tại trong bảng bệnh nhân không
    $stmt = $con->prepare("SELECT account_id, phone_number FROM patient WHERE phone_number = ?");
    $stmt->bind_param('s', $identifier);
    $stmt->execute();
    $stmt->bind_result($account_id, $phone_number);
    $stmt->fetch();
    
    if ($account_id) {
        // Tạo mã OTP ngẫu nhiên
        $otp = rand(100000, 999999);
        $expiry_time = date("Y-m-d H:i:s", strtotime('+5 minutes'));  // OTP có hiệu lực trong 5 phút
        
        // Lưu OTP vào database
        $stmt = $con->prepare("INSERT INTO otp_token (account_id, otp, expiry_time) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $account_id, $otp, $expiry_time);
        $stmt->execute();
        
        // Gửi OTP qua SMS (Giả sử có API SMS)
        sendSMS($phone_number, "Your OTP code is: $otp");
        echo "An OTP has been sent to your phone number.";
    } 
    // else {
    //     // Nếu không tìm thấy bệnh nhân, kiểm tra trong bảng bác sĩ (doctor)
    //     $stmt = $con->prepare("SELECT account_id, email FROM doctor WHERE email = ?");
    //     $stmt->bind_param('s', $identifier);
    //     $stmt->execute();
    //     $stmt->bind_result($account_id, $email);
    //     $stmt->fetch();

    //     if ($account_id) {
    //         $otp = rand(100000, 999999);
    //         $expiry_time = date("Y-m-d H:i:s", strtotime('+5 minutes'));

    //         // Lưu OTP vào bảng otp_tokens
    //         $stmt = $con->prepare("INSERT INTO otp_token (account_id, otp, expiry_time) VALUES (?, ?, ?)");
    //         $stmt->bind_param('iss', $account_id, $otp, $expiry_time);
    //         $stmt->execute();

    //         // Gửi OTP qua email
    //         mail($email, "Reset Password OTP", "Your OTP code is: $otp");
    //         echo "An OTP has been sent to your email address.";
    //     } else {
    //         echo "No account found with that phone number or email.";
    //     }
    // }
}

// Function giả lập gửi SMS
function sendSMS($phone_number, $message) {
    // Đây là nơi bạn sẽ tích hợp API của nhà cung cấp dịch vụ SMS
    // Ví dụ: Twilio, Nexmo, MessageBird...
    // Ở đây chỉ là ví dụ đơn giản về giả lập
    require("../API/SpeedSMS/SpeedSMSAPI.php");
    $smsAPI = new SpeedSMSAPI("GnPZHJ2kRxuemPZ8m574ds4RGpYN_KAC");
    /**
     * Để lấy thông tin về tài khoản như: email, số dư tài khoản bạn sử dụng hàm getUserInfo()
     */

    $userInfo = $smsAPI->getUserInfo();
    /* * Hàm getUserInfo() sẽ trả về một mảng như sau:
    * /
    ["email"=>"test@speedsms.vn", "balance" =>100000.0, "currency" => "VND"]

    /** Để gửi SMS bạn sử dụng hàm sendSMS như sau:
    */
    $phones = ["8491xxxxx", "8498xxxxxx"]; 
    /* tối đa 100 số cho 1 lần gọi API */
    $content = "test sms";
    $type = sms_type
    /**
    sms_type có các giá trị như sau:
    2: tin nhắn gửi bằng đầu số ngẫu nhiên
    3: tin nhắn gửi bằng brandname
    4: tin nhắn gửi bằng brandname mặc định (Verify hoặc Notify)
    5: tin nhắn gửi bằng app android
    */
    $sender = "brandname";
    /**
    brandname là tên thương hiệu hoặc số điện thoại đã đăng ký với SpeedSMS
    */

    $response = $smsAPI->sendSMS($phones, $content, $type, $sender);

    /**hàm sendSMS sẽ trả về một mảng như sau:*/
    [
    "status"=>"success", "code"=> "00", 
    "data"=>[
        "tranId"=>123456, "totalSMS"=>2,     
        "totalPrice"=>500, "invalidPhone"=>[] 
        ]
    ]
    */
    // Trong trường hợp gửi sms bị lỗi, hàm sendSMS sẽ trả về mảng như sau:
    [
    "status"=>"error", "code"=>"error code", "message" => "error description",
    "invalidPhone"=>["danh sách sdt lỗi"]
    ]

    /** Để gửi VOICE OTP bạn sử dụng hàm sendVoice như sau:
    */
    $phone = "8491xxxxx"; 
    $content = "xxxx";
    /* nội dung chỉ chứa mã code, ví dụ: 1234 */
    $response = $smsAPI->sendVoice($phone, $content);

    /**hàm sendVoice sẽ trả về một mảng như sau:*/
    [
    "status"=>"success", "code"=> "00", 
    "data"=>[
        "tranId"=>123456,     
        "totalPrice"=>400, "invalidPhone"=>[] 
        ]
    ]
    */
    // Trong trường hợp gửi voice otp bị lỗi, hàm sẽ trả về mảng như sau:
    [
    "status"=>"error", "code"=>"error code", "message" => "error description",
    "invalidPhone"=>["danh sách sdt lỗi"]
    ]
    // echo "Sending SMS to $phone_number: $message";
}
?>
