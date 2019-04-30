<!DOCTYPE HTML>
<html>
<head>
<style>
body{
  margin: 0;
  color: #F9FBF2;
  background: #c8c8c8;
  font: 600 18px/18px 'Open Sans';
  text-shadow: 1px 1px 3px #4C4C4C;
  text-align: center;
}

.login-wrap{
  width: 100%;
  margin: auto;
  margin-top: 30px;
  max-width: 770px;
  min-height: 570px;
  position: relative;
  background: url(sunset.jpg) no-repeat center;
  box-shadow: 0 12px 15px 0 rgba(0,0,0,.24), 0 17px 50px 0 rgba(0, 0, 0, .19);
  text-align: left;
}

.login-html .sign-in{
  display:none;
}
.login-html .tab,
.login-form .group .label,
.login-form .group .button{
  text-shadow: 1px 1px 3px #4C4C4C;
  color: #F9FBF2;
  font: 600 15px 'Open Sans';
}
.group {
  padding: 50px 70px 70px 50px;
}
.login-form .group .input,
.login-form .group .button{
  border: none;
  width: 90%;
  padding: 12px 20px;
  border-radius: 25px;
  background: rgba(255,255,255,.15);
} 
.login-form .group .label{
  color:#F9FBF2;
  font-size: 15px;
  text-shadow: 1px 1px 3px #4C4C4C;
}
.error{
  color:#F9FBF2;
  font-size: 12px;
  text-shadow: 1px 1px 3px #4C4C4C;
}
.login-form .group .button{
  background: #85BAA1;
}

</style>
</head>
<body>


<?php
// Tạo biến
$nameErr = $emailErr = $phoneErr = $usernameErr = $dobErr = $imageErr = "";
$name = $email = $phone = $username = $dob = $image_name = "";

// Hàm tăng security
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_POST['name'])) {
    $nameErr = 'Vui lòng nhập tên';
  } else {
    $name = test_input($_POST['name']);
  }
  if (empty($_POST['email'])) {
    $emailErr = 'Vui lòng nhập địa chỉ email';
  } else {
    $email = test_input($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = 'Email này không hợp lệ';
    }
  }
  if (empty($_POST['phone'])) {
    $phoneErr = 'Vui lòng nhập số điện thoại';
  } else {
    $phone = test_input($_POST['phone']);
  }
  if (empty($_POST['username'])) {
    $usernameErr = 'Vui lòng nhập tên đăng nhập';
  } else {
    $username = test_input($_POST['username']);
    if (!preg_match('/^[a-zA-Z0-9 -_]*$/', $username)) {
      $usernameErr = 'Tên đăng nhập này không hợp lệ';
    }
  }
  if (empty($_POST['dob'])) {
    $dobErr = 'Vui lòng nhập mật khẩu';
  } else {
    $dob = test_input($_POST['dob']);
  }
  if (!isset($_FILES['image'])) {
    $imageErr = 'Vui lòng nhập ảnh đại diện';
  } else {
    $image_name = $_FILES['image']['name'];
    $target_dir = 'img/';
    $target_file = $target_dir . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType == 'jpg') {
      if (!file_exists($target_dir . basename($target_file, PATHINFO_EXTENSION))) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir.$image_name);
      }
    }
  }
}
?>

<div class='login-wrap'>
<div class='login-form'>
<form method='post' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' enctype="multipart/form-data" class='sign-in-html'>
  <table align="center" class='group'>
    <tr>
      <td class='sign-in'><h2>Form Đăng Ký</h2></td>
      <td><p><span class='error'>*  Vui lòng không để trống</span></p></td>
    </tr>
    <tr>
      <td class='label'>Họ và tên:</td>
      <td><input type='text' class="input" name='name' value='<?php echo $name;?>'>
      <span class='error'>* <?php echo $nameErr;?></span></td>
    </tr>
    <tr>
      <td class='label'>Email:</td>
      <td><input type='email' class="input" name='email' value='<?php echo $email;?>'>
      <span class='error'>* <?php echo $emailErr;?></span></td>
    </tr>
    <tr>
      <td class='label'>Điện thoại:</td>
      <td><input type='tel' class="input" name='phone' value='<?php echo $phone;?>'>
      <span class='error'>* <?php echo $phoneErr;?></span></td>
    </tr>
    <tr>
      <td class='label'>Tên đăng nhập:</td>
      <td><input type='text' class="input" name='username' value='<?php echo $username;?>'>
      <span class='error'>* <?php echo $usernameErr;?></span></td>
    </tr>
    <tr>
      <td class='label'>Sinh nhật:</td>
      <td><input type='date' class="input" name='dob' value='<?php echo $dob;?>'>
      <span class='error'>* <?php echo $dobErr;?></span></td>
    </tr>
    <tr>
      <td class='label'>Ảnh đại diện:</td>
      <td><input type="file" name="image" value="Upload" class='input'>
      <span class='error'>* <?php echo $imageErr;?></span></td>
    </tr>
    <tr>
      <td><input type='submit' name='submit' value='Submit' class="button" ></td>
    </tr>
  </table>
</form>
</div>
</div>
<?php
// Tạo connection
$servername = 'localhost';
$userName = "root";
$password = '';
$database = 'myDB';
$conn = new mysqli($servername, $userName, $password, $database);
if ($conn->connect_error) {
  die('Connection failed:' . $conn->connect_error);
}

// Tạo bảng 
$sql = 'CREATE TABLE project2 (
id INT(6) AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
email VARCHAR(50) NOT NULL UNIQUE,
phone INT(15) NOT NULL,
username VARCHAR(30) NOT NULL UNIQUE,
dob datetime(6) NOT NULL,
image_name VARCHAR(200) NOT NULL
)';
$conn->query($sql);

// Điền dữ liệu vào bảng
$formInput = '("'.$name.'", "'.$email.'", "'.$phone.'", "'.$username.'", "'.$dob.'", "'.$image_name.'")';
$sql = "INSERT INTO project2 (name, email, phone, username, dob, image_name)
        VALUES $formInput
";
$result = mysqli_query($conn, $sql) or die ('Registration failed: User already existed.');

?>

</body>
</html>