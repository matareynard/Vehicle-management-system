<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'vehicle_management');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM admins WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['admin'] = $user['username'];
      header("Location: /vehicle_management/index.php");
    } else {
      $error = "Wrong password.";
    }
  } else {
    $error = "Admin not found.";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
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

    input[type="text"],
    input[type="password"] {
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

    .button-link {
      width: 100%;
      padding: 10px;
      background: #16a085;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }


    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <form method="POST">
    <h2>Admin Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">LOG IN</button>
    <div>
      <a href="register.php">Sign Up</a>
    </div>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
  </form>
</body>

</html>