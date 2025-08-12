<?php
require_once("../config.php");
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <title>Create Code</title>
        <link rel='stylesheet' href='style.css'>
                <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        <div id='homepage'>
            <a href="../index.php" style='text-decoration: none; color: black;'><i class='fas fa-home' style='color: green;'></i> Home Page</a>
        </div>
        <span style='padding-top: 5px;'></span>
        <div class='time-range-form-cover'>
            <form id='time-range-form' method="post">
                <div class='info-verify'>
                    <label for="firstName">First Name</label>
                    <input type="text" name='firstName' required>
                </div>
                <br>

                <div class='info-verify'>
                    <label for="lastName">Last Name</label>
                    <input type="text" name='lastName' required>
                </div>
                <br>

                <div class='info-verify'>
                    <label for="codeNum">4-Digit Code</label>
                    <input type="password"  maxlength='4' name='codeNum' required>
                </div>
                <br>

                <button type='submit' id='createCodeButton'>Create Code</button>
            </form>
        </div>
        <?php
            if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['codeNum'])) {
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $codeNum = $_POST['codeNum'];

                $check = $conn->prepare("SELECT * FROM users WHERE code = ?");
                $check->bind_param("i", $codeNum);
                $check->execute();
                $check->store_result();
                if ($check->num_rows > 0) {
                    echo "<br><div style='color: red;'>That code is already in use. Please choose another.</div>";
                    $check->close();
                    return;
                }

                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, code) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $firstName, $lastName, $codeNum);
                if ($stmt->execute()) {
                    echo "<br><div style='color: green;'>You have created your code successfully!</div>";
                } else {
                echo "<div style='color:red;'>Could not create your code. Please try again.</div>";
                }
                $stmt->close();
            }
        ?>
    </body>
</html>