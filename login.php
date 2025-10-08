<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(135deg, #0f4c3a 0%, #1a5c47 25%, #2d8f47 50%, #50c878 75%, #6be585 100%);
    }
  </style>
</head>
 <?php
    // --- Connect to DB ---
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = ''; // default password for XAMPP
    $db_name = 'login_db';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        // Stop silently if DB connection fails (no DOM output)
        exit();
    }

    // --- Handle POST login ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // ✅ Valid login: redirect or start session
                // session_start(); $_SESSION['user'] = $username;
                header("Location: dashboard.php");
                exit();
            }
        }

        // ❌ Invalid login: stay on page (no DOM change)
    }

    $conn->close();
  ?>

  
<body class="flex items-center justify-center min-h-screen">
  <div class="w-full max-w-sm bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Login</h2>

    <form method="POST" class="space-y-4" action="">
      <div>
        <label class="block text-gray-700 text-sm">Username</label>
        <input
          type="text"
          name="username"
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
          required
        />
      </div>

      <div>
        <label class="block text-gray-700 text-sm">Password</label>
        <input
          type="password"
          name="password"
          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
          required
        />
      </div>

      <div>
        <button
          type="submit"
          class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition"
        >
          Log In
        </button>
      </div>
    </form>
  </div>

</body>
</html>
