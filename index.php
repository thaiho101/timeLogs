<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Time Logs</title>
        <link rel='stylesheet' href="style.css">
        <link rel="icon" type="image/x-icon" href="/logo/clockLogo.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    </head>
    <body>
        <div id='menu-nav'>
            <a class='menu-sites' href="./createCode/"><i class='fa fa-pencil-square'></i> Create Code</a>
            <span style='width: 15px;'></span>
            <a class='menu-sites' href='./timeTracking/' style=''><i class='far fa-clock'></i> Time Tracking</a>
        </div>
        <div id='notification'></div>
        <div id='menu-Nav'>
            <div id='header-title'>
                <h1>Time Logs</h1>
            </div>
        </div>

        <div id='container'>
            <div id='content'>
                <div class='content-child'>
                    <div id='header-table'>
                        <h2 style="filter: drop-shadow(0px 0px 10px white);">Enter 4-Digit Code</h2>
                    </div>
                    <div>
                        <!-- <input id='display' type='' maxlength='4' disabled> -->
                    </div>

                    <div id='display'>
                        <span class='digital-slot' id='slot0'></span>
                        <span class='digital-slot' id='slot1'></span>
                        <span class='digital-slot' id='slot2'></span>
                        <span class='digital-slot' id='slot3'></span>
                    </div>

                    <div class='num-pad'>
                        <button class='btn' onclick="press(1)">1</button>
                        <button class='btn' onclick="press(2)">2</button>
                        <button class='btn' onclick="press(3)">3</button>
                        <button class='btn' onclick="press(4)">4</button>
                        <button class='btn' onclick="press(5)">5</button>
                        <button class='btn' onclick="press(6)">6</button>
                        <button class='btn' onclick="press(7)">7</button>
                        <button class='btn' onclick="press(8)">8</button>
                        <button class='btn' onclick="press(9)">9</button>
                        <button class='btn' onclick="clearCode()" style='font-weight: bold;'>C</button>
                        <button class='btn' onclick="press(0)">0</button>
                        <button class='btn' onclick="deleteLast()" style='font-weight: bold;'>←</button>
                    </div>
                </div>
            </div>
        </div>

        <div id='footer'>
            <p>&copy; <?php echo date('Y');?> Developed by Nam Ho</p>
        </div>
        <script src="script.js"></script>
    </body>
</html>
