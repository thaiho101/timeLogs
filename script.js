let code = "";

function press(num) {
    if (code.length < 4) {
        code += num;
        updateDisplay();

        if (code.length === 4) {
            checkCode(code);
        }
    }
}

function deleteLast() {
    code = code.slice(0, -1);
    updateDisplay();
}

function clearCode() {
    code = '';
    updateDisplay();
}

function updateDisplay() {
    for (let i = 0; i < 4; i++) {
        document.getElementById("slot" + i).textContent = code[i] ? '*' : '';
    }
}

function checkCode(pin) {
    fetch("checkCode.php" ,{
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "code=" + encodeURIComponent(pin)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "login") {
            showNotification(`✅<br>
                <strong style='color: blue;'>${data.name}</strong> <strong>Logged in</strong> at <br>
                Date: ${data.date} <br>
                Time: ${data.time}`, "#2ecc71"); // green
        } else if (data.status === "logout") {
            showNotification(`👋<br> 
                <strong style='color: blue;'>${data.name}</strong> <strong style='color: orange;'>Logged out</strong> at <br>
                Date: ${data.date} <br>
                Time: ${data.time}.<br><br>
                Total Working time:<br> 
                <strong style="color: yellow;">${data.totalTime}</strong>`, "#3498db"); // blue
        } else if (data.status === "not_found") {
            showNotification("❌ Invalid code.", "#f39c12"); // red
        } else {
            showNotification("⚠️ Something went wrong.", "#e74c3c"); // orange
        }
        clearCode();
    })
    .catch(err => {
        alert("❗ Server error.");
        clearCode();
    });
}




//Notification
function showNotification(message, bgColor = "#333") {
    const notif = document.getElementById("notification");
    // notif.innerText = message;
    notif.innerHTML = message;
    notif.style.backgroundColor = bgColor;
    notif.style.display = "block";

    setTimeout (() => {
        notif.style.display = "none";
    }, 4000);
}











document.addEventListener("DOMContentLoaded", function () {
  // Detect if the device supports hover
  const canHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

  // If it can hover (like a desktop), allow hover styles
  if (canHover) {
    document.querySelectorAll('.btn').forEach(btn => {
      btn.classList.add('allow-hover');
    });
  }
});







