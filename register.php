    <?php
    require 'koneksi.php';
    session_start();

    if (isset($_SESSION["login"])) {
      header("Location: index.php");
      exit;
    }

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $username = $_POST["username"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $hashed = password_hash($password, PASSWORD_DEFAULT);

      // Tentukan role berdasarkan domain email
      if (str_ends_with($email, '@example.com')) {
        $role = 'admin';
      } else {
        $role = 'user';
      }

      mysqli_query($conn, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed', '$role')");

      $user_id = mysqli_insert_id($conn);

      $_SESSION["login"] = true;
      $_SESSION["user"] = $username;
      $_SESSION["email"] = $email;
      $_SESSION["id"] = $user_id;
      $_SESSION["role"] = $role;

      // Redirect berdasarkan role
      if ($role === 'admin') {
        header("Location: admin/index.php");
      } else {
        header("Location: user/dashboard_user.php");
      }
      exit;
    }
    ?>






    <!doctype html>
    <html lang="en">

    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login Page</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://unpkg.com/feather-icons"></script>
      <style>
        body {
          background-color: rgba(174, 174, 174, 1);
          /* Ganti dengan gambar kamu */
          background-size: cover;
          background-position: center;
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .login-card {
          background-color: rgba(255, 255, 255, 0.15);
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.3);
          border-radius: 15px;
          padding: 30px;
          width: 100%;
          max-width: 400px;
          color: #fff;
        }

        .login-card input {
          background-color: rgba(255, 255, 255, 0.3);
          border: none;
          color: #fff;
        }

        .login-card input::placeholder {
          color: rgba(255, 255, 255, 0.8);
        }

        .login-card a {
          color: #ddd;
        }

        .login-card a:hover {
          color: #fff;
          text-decoration: underline;
        }
      </style>
    </head>

    <body>
      <div class="login-card text-center shadow">
        <div class="mb-2">
          <i data-feather="user" width="48" height="48" color="#333"></i>
        </div>
        <h2 class="mb-3" style="color: #333;">Register</h2>

        <form action="" method="POST">
          <div class="mb-3 text-start">
            <input type="text" class="form-control <?php if (isset($errors['username'])) echo 'is-invalid'; ?>" name="username" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <?php if (isset($errors['username'])): ?>
              <div class="invalid-feedback"><?= $errors['username'] ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3 text-start">
            <input type="text" class="form-control <?php if (isset($errors['email'])) echo 'is-invalid'; ?>" name="email" placeholder="Masukkan email">
            <?php if (isset($errors['email'])): ?>
              <div class="invalid-feedback"><?= $errors['email'] ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3 text-start">
            <input type="password" class="form-control <?php if (isset($errors['password'])) echo 'is-invalid'; ?>" name="password" placeholder="Masukkan password">
            <?php if (isset($errors['password'])): ?>
              <div class="invalid-feedback"><?= $errors['password'] ?></div>
            <?php endif; ?>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="login.php">Sudah Punya Akun?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        feather.replace();
      </script>
    </body>

    </html>