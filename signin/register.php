<?php
$conn = new mysqli('localhost', 'root', '', 'vehicle_management');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fullname = trim($_POST['fullname']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $username = trim($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check if username or email already exists
  $check = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
  $check->bind_param("ss", $username, $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    $message = "Username or email already exists. Please use different credentials.";
  } else {
    try {
      $stmt = $conn->prepare("INSERT INTO admins (fullname, email, phone, username, password) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $fullname, $email, $phone, $username, $password);
      $stmt->execute();

      // Redirect to login after successful signup
      header("Location: ../signin/login.php");
      exit;
    } catch (mysqli_sql_exception $e) {
      $message = "Signup failed: " . $e->getMessage();  // Debugging message
    }
  }

  $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Signup</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    form {
      background: white;
      padding: 20px 25px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #16a085;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .message {
      margin-top: 10px;
      text-align: center;
    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <form method="POST">
    <h2>Admin Signup</h2>
    <input type="text" name="fullname" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="text" name="phone" placeholder="Phone Number" required />
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Sign Up</button>
    <a href="../signin/login.php" class="button-link">LOG IN</a>
    <?php if ($message): ?>
      <div class="message <?php echo strpos($message, 'failed') !== false || strpos($message, 'exists') !== false ? 'error' : ''; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
  </form>
</body>

</html>