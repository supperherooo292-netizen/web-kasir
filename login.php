<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="login-container">
    <div class="login-left">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Login Illustration">
    </div>

    <div class="login-right">
      <h2>Member Login</h2>
      <form action="proses-login.php?v=<?php echo time();?>" method="POST">
        <div class="input-group">
          <i class="fa-solid fa-user"></i>
          <input type="text" name="username" placeholder="username" required>
        </div>
        <div class="input-group">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="login-btn">LOGIN</button>
      </form>
    </div>
  </div>
  </div>
</body>
</html>