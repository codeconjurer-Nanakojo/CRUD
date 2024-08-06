

<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "CRUD";
$port = 3307;

$conn = new mysqli($server, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$userid = "";
$fullname = "";
$username = "";
$email = "";

// Retrieve data from URL if provided
if (isset($_GET['userid']) && isset($_GET['fullname']) && isset($_GET['username']) && isset($_GET['email'])) {
    $userid = htmlspecialchars($_GET['userid']);
    $fullname = htmlspecialchars($_GET['fullname']);
    $username = htmlspecialchars($_GET['username']);
    $email = htmlspecialchars($_GET['email']);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Use a prepared statement to prevent SQL injection
    $sql = "UPDATE signup 
            SET full_name = ?, user_name = ?, email = ? 
            WHERE user_id = ?";
    
    


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fullname, $username, $email, $userid);

    if ($stmt->execute()) {
        echo "Record updated successfully";
        header("Location: ../view_info.html");
    } else {
        echo "Error updating record: " . $stmt->error;
        header("Location: update.php");
    }


    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    
    <script src="../../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    
    <title>Document</title>
    <style>
      li:active{
        color: darkblue;
      }

      .custom-container{
        width:480px;
        
      }
      
    </style>
</head>
<body>
   
      <nav class="navbar navbar-expand-lg  navbar-light bg-info" aria-label="First navbar example">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="../img/brand-img.png" alt="Bootstrap" width="30" height="24">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
    
          <div class="collapse navbar-collapse" id="navbarsExample01">
              <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item active">
                <a class="nav-link text-light" aria-current="page" href="../index.html">Home</a>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-light" href="../login.html">Login</a>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-light" href="../index.html" >
                  Signup
                </a>
              </li>
              <li class="nav-item active">
                <a class="nav-link text-light" href="../view_info.html">View_info</a>
              </li>
            </ul>
              
            
          </div>
        </div>
      </nav>
      <div class="d-flex align-items-center justify-content-center" style="height:75vh;">
      <div class="card " style="width: 30rem; ">
        <div class="card-header bg-info ">
          <h3 class="text-center text-light">Update Registration form in php</h3>
        </div>
        <ul class="list-group list-group-flush pt-4 pb-2">
          <li class="list-group-item">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="mb-3">
            <input type="text" class="form-control" id="userid" name="userid" required placeholder="user id" value="<?php echo htmlspecialchars($userid); ?>">
          </div> 
          <div class="mb-3">  
            <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="full name" value="<?php echo htmlspecialchars($fullname); ?>">
          </div> 
          <div class="mb-3">  
            <input type="text" class="form-control" id="username" name="username" required placeholder="user name" value="<?php echo htmlspecialchars($username); ?>">
          </div> 
          <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" required placeholder="email" value="<?php echo htmlspecialchars($email); ?>">
          </div>   
           
           
            <input type="submit" class="btn btn-info text-light" value="Update">
        </form>
          </li>
          
        </ul>
      </div>
      </div>

      
</body>
</html>



