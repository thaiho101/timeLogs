<?php
require_once("../config.php");
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Time Tracking</title>
        <link rel='stylesheet' href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    </head>
    <body>
        <div id='homepage'>
            <a href="../index.php" style='text-decoration: none; color: black;'><i class='fas fa-home' style='color: green;'></i> Home Page</a>
        </div>
        <span style='padding-top: 5px;'></span>
        <div class='time-range-form-cover'>
            <form id='time-range-form' method='get'>
                <div class='info-verify'>
                    <label for="from">From</label>
                    <div class='iconInput'>
                        <i class='far fa-calendar-alt iconStyle'></i> 
                        <input type="date" id='from' name='from' value="<?php echo $_GET['from'] ? $_GET['from'] : '';?>" required>
                    </div>
                </div>

                <br>
                <div class='info-verify'>
                    <label for="to">To</label>
                    <div class='iconInput'>
                        <i class='far fa-calendar-alt iconStyle'></i> 
                        <input type="date" id='to' name='to' value="<?php echo $_GET['to'] ? $_GET['to'] : '';?>" required>
                    </div>
                </div>

                <br>
                <div class='info-verify'>
                    <label for="userCode">4-Digit Code</label>
                    <div class='iconInput'>
                        <i class='fas fa-shield-alt iconStyle'></i> 
                        <input type="password" id='userCode' name='userCode' required>
                    </div>
                </div>

                <br>
                <button type='submit' id='submitButton'>Submit</button>
            </form>
        </div>
        <br>
        <div class='totalTime-cover'>
            <div id='totalTime-header'>Total Time :</div>
            <div id='totalTime'></div>
        </div>
        <br>
        <div id='content'>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['from']) && isset($_GET['to'])) {
                    $dateFrom = $_GET['from'] . ' 00:00:00';
                    $dateTo = $_GET['to'] . ' 23:59:59';
                    $user_code = $_GET['userCode'];

                    $stmt = $conn->prepare("SELECT *
                                    FROM users u
                                    LEFT JOIN attendance a ON u.id = a.user_id
                                    WHERE u.code = ? AND a.login_time BETWEEN ? AND ?
                                    ORDER BY a.id DESC;");
                    $stmt->bind_param('sss', $user_code, $dateFrom, $dateTo);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo "<table border='0'>";
                        echo "
                                <tr><td class='headerStyle'>Name</td>
                                    <td class='headerStyle'>Code</td>
                                    <td class='headerStyle'>Weekday</td>
                                    <td class='headerStyle'>Login Time</td>
                                    <td class='headerStyle'>Logout Time</td>
                                    <td class='headerStyle'>Total Time</td>
                                </tr>
                            ";

                        $amountOfTime = 0;
                        $numOrder = 1;

                        while ($dataRow = $result->fetch_assoc()) {
                            $login_time = $dataRow['login_time'];
                            $logout_time = $dataRow['logout_time'];

                            $login_timeStamp = strtotime($login_time);
                            $logout_timeStamp = strtotime($logout_time);

                            if ($logout_timeStamp) {
                                $diff = $logout_timeStamp - $login_timeStamp;

                                $hours = floor($diff / 3600);
                                $seconds = floor(($diff % 3600) / 60);
                                $totalTime = "{$hours} hrs {$seconds} minutes";

                                $logoutTimeData = date('m/d/Y H:i:s', $logout_timeStamp);
                            } else {
                                $logoutTimeData = "Still logged in";
                                $totalTime = "0";
                            }



                            $evenOddClass = ($numOrder % 2 === 0) ? 'evenClass' : 'oddClass';
                            echo "<tr class='{$evenOddClass}'>
                                    <td>" . $dataRow['first_name'] . "</td>
                                    <td>" . $dataRow['code'] . "</td>
                                    <td>" . date('l', strtotime($dataRow['login_time'])) . "</td>
                                    <td>" . date('m/d/Y H:i:s', $login_timeStamp) . "</td>
                                    <td>" . $logoutTimeData . "</td>
                                    <td>" . $totalTime . "</td>
                                </tr>";
                            $amountOfTime += $diff;
                            $numOrder++;
                        }
                        echo "</table>";
                    } else {
                        echo "<p style='color:white;'>No records found for the selected range.</p>";
                    }
                }

                $hours = floor($amountOfTime / 3600);
                $seconds = floor(($amountOfTime % 3600) / 60);
                $totalTime = "{$hours} hours {$seconds} minutes";
                // echo $totalTime;
            ?>
        </div>

        <script>
            let totalTime = "<?php echo $totalTime; ?>";
            const total_Time = document.getElementById("totalTime");

            total_Time.innerHTML = totalTime;
        </script>
    </body>



</html>