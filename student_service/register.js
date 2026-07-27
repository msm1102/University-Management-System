const app = document.getElementById("app");

app.innerHTML = `
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">
            Student Registration
        </h2>
        <a href="dashboard.html" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-1 px-3 rounded border transition">
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
            class="w-full border p-2 rounded"
        >
        <p id="emailError" class="text-red-500 text-sm hidden">
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
                class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-2 rounded font-medium">
                ⚡ Generate Strong Password
            </button>
        </div>

        <input
            type="text"
            id="password"
            placeholder="Enter Password"
            class="w-full border p-2 rounded"
        >

        <div class="w-full bg-gray-300 h-2 rounded mt-2">
            <div
                id="strengthBar"
                class="h-2 bg-red-500 rounded"
                style="width:0%">
            </div>
        </div>
        <p id="strengthText" class="mt-2 text-sm">
            Strength : Empty
        </p>
    </div>

    <button
        id="submitBtn"
        class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
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
        strengthBar.className = "h-2 bg-red-500 rounded";
        strengthText.innerHTML = "Strength : Empty";
    } else if (score <= 1) {
        strengthBar.style.width = "25%";
        strengthBar.className = "h-2 bg-red-500 rounded";
        strengthText.innerHTML = "Strength : Weak";
    } else if (score <= 3) {
        strengthBar.style.width = "60%";
        strengthBar.className = "h-2 bg-yellow-500 rounded";
        strengthText.innerHTML = "Strength : Moderate";
    } else {
        strengthBar.style.width = "100%";
        strengthBar.className = "h-2 bg-green-500 rounded";
        strengthText.innerHTML = "Strength : Strong";
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

submitBtn.addEventListener("click", function (event) {
    event.preventDefault();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmailValid = emailRegex.test(email.value);
    const isPasswordStrong = password.value.length >= 8;

    if (isEmailValid && isPasswordStrong) {
        app.innerHTML = `
        <div class="max-w-md mx-auto mt-20 bg-green-100 border border-green-500 p-8 rounded-lg text-center shadow-lg">
            <h2 class="text-3xl font-bold text-green-700">
                ✅ Account Created Successfully!
            </h2>
            <p class="mt-4">
                Welcome to the University Management System.
            </p>

            <div class="mt-6 flex justify-center gap-4">
                <button
                    onclick="location.reload()"
                    class="bg-gray-600 text-white px-5 py-2 rounded hover:bg-gray-700">
                    Register Another
                </button>
                <a
                    href="dashboard.html"
                    class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 inline-block">
                    Go to Dashboard
                </a>
            </div>
        </div>
        `;
    } else {
        alert("Please enter a valid email and a password with at least 8 characters.");
    }
});