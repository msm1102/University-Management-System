<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['email']) && isset($data['password'])) {
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 8) {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO students (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'এই ইমেইলটি আগে থেকেই নিবন্ধিত অথবা ডাটাবেস এরর।']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'ভুল ইনপুট দেওয়া হয়েছে।']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">

    <div id="app"></div>

    <script>
        const app = document.getElementById("app");

        app.innerHTML = `
        <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">
                    Student Registration
                </h2>
                <a href="dashboard.php" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-1 px-3 rounded border transition">
                    ← Dashboard
                </a>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    placeholder="Enter your email"
                    class="w-full border p-2 rounded focus:outline-none"
                >
                <p id="emailError" class="text-red-500 text-sm hidden mt-1">
                    Invalid Email Address
                </p>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="font-semibold">
                        Password
                    </label>
                    <button 
                        type="button" 
                        id="generateBtn" 
                        class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-2 rounded font-medium transition">
                        ⚡ Generate Strong Password
                    </button>
                </div>

                <input
                    type="text"
                    id="password"
                    placeholder="Enter Password"
                    class="w-full border p-2 rounded focus:outline-none"
                >

                <div class="w-full bg-gray-300 h-2 rounded mt-3">
                    <div
                        id="strengthBar"
                        class="h-2 bg-red-500 rounded transition-all duration-300"
                        style="width:0%">
                    </div>
                </div>
                <p id="strengthText" class="mt-2 text-sm font-medium text-gray-600">
                    Strength : Empty
                </p>
            </div>

            <button
                id="submitBtn"
                class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition font-semibold mt-4">
                Register
            </button>
        </div>
        `;

        const email = document.getElementById("email");
        const emailError = document.getElementById("emailError");

        email.addEventListener("input", function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailRegex.test(email.value)) {
                email.classList.remove("border-red-500");
                email.classList.add("border-green-500");
                emailError.classList.add("hidden");
            } else {
                email.classList.remove("border-green-500");
                email.classList.add("border-red-500");
                emailError.classList.remove("hidden");
            }
        });

        const password = document.getElementById("password");
        const strengthBar = document.getElementById("strengthBar");
        const strengthText = document.getElementById("strengthText");

        function checkPasswordStrength() {
            const value = password.value;
            let score = 0;

            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            if (value.length === 0) {
                strengthBar.style.width = "0%";
                strengthBar.className = "h-2 bg-red-500 rounded transition-all duration-300";
                strengthText.innerHTML = "Strength : Empty";
                strengthText.style.color = "#4b5563"; 
            } else if (score <= 1) {
                strengthBar.style.width = "25%";
                strengthBar.className = "h-2 bg-red-500 rounded transition-all duration-300";
                strengthText.innerHTML = "Strength : Weak";
                strengthText.style.color = "#ef4444"; 
            } else if (score <= 3) {
                strengthBar.style.width = "60%";
                strengthBar.className = "h-2 bg-yellow-500 rounded transition-all duration-300";
                strengthText.innerHTML = "Strength : Moderate";
                strengthText.style.color = "#eab308"; 
            } else {
                strengthBar.style.width = "100%";
                strengthBar.className = "h-2 bg-green-500 rounded transition-all duration-300";
                strengthText.innerHTML = "Strength : Strong";
                strengthText.style.color = "#22c55e"; 
            }
        }

        password.addEventListener("input", checkPasswordStrength);

        const generateBtn = document.getElementById("generateBtn");

        generateBtn.addEventListener("click", function () {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=";
            let newPassword = "";
            
            for (let i = 0; i < 12; i++) {
                const randomIndex = Math.floor(Math.random() * chars.length);
                newPassword += chars[randomIndex];
            }

            password.value = newPassword;
            checkPasswordStrength();
        });

        const submitBtn = document.getElementById("submitBtn");

        submitBtn.addEventListener("click", async function (event) {
            event.preventDefault();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isEmailValid = emailRegex.test(email.value);
            const isPasswordStrong = password.value.length >= 8;

            if (isEmailValid && isPasswordStrong) {
                
                submitBtn.innerHTML = "Processing...";
                submitBtn.disabled = true;

                try {
                    const response = await fetch('register.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email: email.value,
                            password: password.value
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        app.innerHTML = `
                        <div class="max-w-md mx-auto mt-20 bg-green-100 border border-green-500 p-8 rounded-lg text-center shadow-lg">
                            <h2 class="text-3xl font-bold text-green-700">
                                ✅ Account Created Successfully!
                            </h2>
                            <p class="mt-4 text-green-800">
                                Welcome to the University Management System.
                            </p>

                            <div class="mt-6 flex justify-center gap-4">
                                <button
                                    onclick="location.reload()"
                                    class="bg-gray-600 text-white px-5 py-2 rounded hover:bg-gray-700 transition">
                                    Register Another
                                </button>
                                <a
                                    href="login.php"
                                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 inline-block transition">
                                    Go to Login
                                </a>
                            </div>
                        </div>
                        `;
                    } else {
                        alert("Registration Failed: " + result.message);
                        submitBtn.innerHTML = "Register";
                        submitBtn.disabled = false;
                    }
                } catch (error) {
                    alert("সার্ভারের সাথে কানেক্ট করতে সমস্যা হচ্ছে।");
                    submitBtn.innerHTML = "Register";
                    submitBtn.disabled = false;
                }

            } else {
                alert("Please enter a valid email and a password with at least 8 characters.");
            }
        });
    </script>
</body>
</html>