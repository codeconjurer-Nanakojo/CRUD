<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "CRUD";
$port = 3307;

$conn = new mysqli($server, $username, $password, $database, $port);


$sql = "SELECT user_id, full_name, user_name, email FROM signup";
$result = $conn->query($sql);

$record = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userid = htmlspecialchars($row["user_id"]);
        $fullname = htmlspecialchars($row["full_name"]);
        $username=  htmlspecialchars($row["user_name"]);
        $email= htmlspecialchars($row["email"]);
       $record .= "<tr>
                <td>" .  $userid. "</td>
                <td>" .  $fullname  . "</td>
                <td>" . $username . "</td>
                <td>" . $email . "</td>
                <td>
                <a class='btn btn-info' href='php/update.php?action=update&userid=" . urlencode($userid) . "&fullname=" . urlencode($fullname) . "&username=" . urlencode($username) . "&email=" . urlencode($email) . "' role='button'>Edit</a>
                 | 
                <a class='btn btn-danger' href='php/reg_log.php?action=delete&userid=" . urlencode($userid) .  "' role='button'>Delete</a>
                </td>
            </tr>";

        ;
        

    }
} else {
    $record = "<tr><td colspan='4'>No data found</td></tr>";
}

$conn->close();
echo $record;
