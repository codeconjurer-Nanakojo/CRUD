<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "CRUD";
$port = 3307;

$conn = new mysqli($server, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Couldn't connect to database: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function register() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST["fname"], $_POST['uname'], $_POST['email'], $_POST['password'])) {
            $firstname = $_POST['fname'];
            $username = $_POST['uname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Escape user inputs for safety
            $firstname = $conn->real_escape_string($firstname);
            $username = $conn->real_escape_string($username);
            $email = $conn->real_escape_string($email);
            $hashedPassword = $conn->real_escape_string($hashedPassword);

            // Create SQL query
            $sql = "INSERT INTO signup (full_name, user_name, email, password) 
                    VALUES ('$firstname', '$username', '$email', '$hashedPassword')";

            // Execute query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                header("Location: ../login.html");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }
}

function login() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['uname'], $_POST['password'])) {
            $username = $_POST['uname'];
            $password = $_POST['password'];

            // Secure the input against SQL injection
            $username = $conn->real_escape_string($username);

            // SQL query to select the hashed password
            $sql = "SELECT password FROM signup WHERE user_name = '$username'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hashedPassword = $row['password'];

                // Verify the password
                if (password_verify($password, $hashedPassword)) {
                    echo "Login successful";
                    header("Location: ../view_info.html");
                    exit(); // Ensure no further code execution
                } else {
                    echo "Incorrect password";
                    header("Location: ../login.html");
                    exit(); // Ensure no further code execution
                }
            } else {
                echo "User not found";
                header("Location: ../register.html");
                exit(); // Ensure no further code execution
            }
        } else {
            echo "All fields are required.";
        }
    }
}
function deleteuser($userid){
    global $conn;
    // Get the user ID from the URL
    $userid = $_GET['userid'];

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM signup WHERE user_id = ?");
    $stmt->bind_param("i", $userid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to another page after 5 seconds
    header("refresh:2; url=../view_info.html");
    }

// Main code
if (isset($_POST["CRUD"])) {
    if ($_POST["CRUD"] === "Register") {
        register();
        exit();
    } else if ($_POST["CRUD"] === "Login") {
        login();
        exit();
    } 
}

if (isset($_GET["action"])) {
    
    if (isset($_GET['userid']) && $_GET["action"] === "delete") {
        $userid = htmlspecialchars($_GET['userid']);
        deleteuser($userid);
        exit();
    }
}
// Close connection
$conn->close();

