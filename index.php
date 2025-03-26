<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include 'db.php'; // Include database connection

// Handle Form Submission
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registration Logic
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure Hashing
        $role = 'user'; // Default role

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);
        
        if ($result->num_rows == 0) {
            // Insert new user
            $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
            if ($conn->query($sql) === TRUE) {
                $message = "✅ Registration Successful! Please Login.";
            } else {
                $message = "❌ Registration Failed: " . $conn->error;
            }
        } else {
            $message = "❌ Email already registered. Try logging in!";
        }
    } elseif (isset($_POST['login'])) {
        // Login Logic
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Fetch user
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "❌ Incorrect Password!";
            }
        } else {
            $message = "❌ No user found with this email!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login & Register</title>
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
        }

        .container {
            width: 40%;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        h2, h3 {
            color: #FFD700;
        }

        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        button {
            width: 85%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .toggle-link {
            color: #FFD700;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleForm(showRegister) {
            document.getElementById("register-form").style.display = showRegister ? "block" : "none";
            document.getElementById("login-form").style.display = showRegister ? "none" : "block";
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Participatory Budgeting System</h2>
        
        <p><?php echo $message; ?></p>

        <!-- Login Form -->
        <div id="login-form">
            <h3>🔹 Login</h3>
            <form method="POST">
                <input type="email" name="email" required placeholder="Email"><br>
                <input type="password" name="password" required placeholder="Password"><br>
                <button type="submit" name="login">Login</button>
            </form>
            <p>New user? <span class="toggle-link" onclick="toggleForm(true)">Register Here</span></p>
        </div>

        <!-- Registration Form -->
        <div id="register-form" style="display:none;">
            <h3>🔹 Register</h3>
            <form method="POST">
                <input type="text" name="name" required placeholder="Full Name"><br>
                <input type="email" name="email" required placeholder="Email"><br>
                <input type="password" name="password" required placeholder="Password"><br>
                <button type="submit" name="register">Register</button>
            </form>
            <p>Already registered? <span class="toggle-link" onclick="toggleForm(false)">Login Here</span></p>
        </div>
    </div>

</body>
</html>