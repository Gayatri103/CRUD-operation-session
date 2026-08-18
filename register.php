<?php

include "dbconn.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $pass = $_POST["pass"];
    $confirm_pass = $_POST["confirm_pass"];
    $address = trim($_POST["address"]);

    if (
        empty($name) ||
        empty($email) ||
        empty($pass) ||
        empty($confirm_pass) ||
        empty($address)
    ) {

        $message = "Please fill in all fields.";
        $messageType = "danger";

    }


    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    }

elseif (strlen($pass) < 8) {

        $message = "Password must contain at least 8 characters.";
        $messageType = "danger";

    }
    elseif ($pass !== $confirm_pass) {

        $message = "Passwords do not match.";
        $messageType = "danger";

    }


    else {
        $check = $conn->prepare(
            "SELECT email FROM Gayatri WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message = "This email is already registered.";
            $messageType = "warning";

        }

        else {
            $hashedPassword = password_hash(
                $pass,
                PASSWORD_DEFAULT
            );
            $sql = $conn->prepare(
                "INSERT INTO Gayatri (name, email, pass, address)
                 VALUES (?, ?, ?, ?)"
            );

            $sql->bind_param(
                "ssss",
                $name,
                $email,
                $hashedPassword,
                $address
            );

            if ($sql->execute()) {

                $message = "Registration successful! You can now login.";
                $messageType = "success";
                

            }

            else {

                $message = "Registration failed. Please try again.";
                $messageType = "danger";

            }

            $sql->close();

        }

        $check->close();

    }

}

?>

<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Account | User Management System</title>

    
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.18), transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(168, 85, 247, 0.18), transparent 30%),
                linear-gradient(135deg, #f8faff 0%, #eef2ff 45%, #f8f7ff 100%);
            color: #1e293b;
            overflow-x: hidden;
        }


        .background-shape {
            position: fixed;
            border-radius: 50%;
            filter: blur(2px);
            pointer-events: none;
            z-index: 0;
        }

        .shape-one {
            width: 280px;
            height: 280px;
            background: rgba(99, 102, 241, 0.12);
            top: -90px;
            left: -90px;
            animation: floatOne 8s ease-in-out infinite;
        }

        .shape-two {
            width: 350px;
            height: 350px;
            background: rgba(168, 85, 247, 0.10);
            bottom: -150px;
            right: -120px;
            animation: floatTwo 10s ease-in-out infinite;
        }

        .shape-three {
            width: 130px;
            height: 130px;
            background: rgba(59, 130, 246, 0.10);
            top: 30%;
            right: 10%;
            animation: floatThree 7s ease-in-out infinite;
        }

        @keyframes floatOne {
            0%, 100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(30px, 25px);
            }
        }

        @keyframes floatTwo {
            0%, 100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-30px, -25px);
            }
        }

        @keyframes floatThree {
            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-25px);
            }
        }


        .register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 15px;
            position: relative;
            z-index: 1;
        }

        .register-card {
            width: 100%;
            max-width: 500px;

            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);

            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 28px;

            padding: 42px;

            box-shadow:
                0 30px 80px rgba(30, 41, 59, 0.12),
                0 8px 30px rgba(99, 102, 241, 0.08);

            animation: cardAppear 0.8s ease forwards;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(35px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .register-icon {
            width: 78px;
            height: 78px;

            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 22px;

            background: linear-gradient(
                135deg,
                #4f46e5,
                #7c3aed
            );

            color: white;

            font-size: 34px;

            box-shadow:
                0 15px 35px rgba(79, 70, 229, 0.30);

            animation: iconFloat 4s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .register-title {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #111827;
            margin-bottom: 8px;
        }

        .register-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group-custom {
            margin-bottom: 21px;
        }

        .form-label {
            color: #334155;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .form-label i {
            color: #6366f1;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 17px;
            z-index: 3;
            transition: 0.3s ease;
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            height: 54px;

            border: 1.5px solid #e2e8f0;
            border-radius: 14px;

            background: rgba(248, 250, 252, 0.9);

            padding: 0 16px 0 46px;

            color: #1e293b;
            font-size: 14px;

            outline: none;

            transition:
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                background 0.25s ease,
                transform 0.25s ease;
        }

        .custom-input::placeholder {
            color: #a1a9b8;
        }

        .custom-input:hover {
            border-color: #c7d2fe;
        }

        .custom-input:focus {
            border-color: #6366f1;
            background: #ffffff;

            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.10);
        }

        .custom-input:focus + .input-icon {
            color: #6366f1;
        }

        .password-input {
            padding-right: 55px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);

            width: 38px;
            height: 38px;

            border: none;
            background: transparent;

            color: #64748b;

            border-radius: 10px;

            transition: 0.25s ease;
        }

        .password-toggle:hover {
            background: #eef2ff;
            color: #4f46e5;
        }

        .custom-textarea {
            width: 100%;

            min-height: 105px;

            border: 1.5px solid #e2e8f0;
            border-radius: 14px;

            background: rgba(248, 250, 252, 0.9);

            padding: 15px 16px 15px 46px;

            resize: vertical;

            color: #1e293b;
            font-size: 14px;

            outline: none;

            transition: 0.25s ease;
        }

        .custom-textarea::placeholder {
            color: #a1a9b8;
        }

        .custom-textarea:focus {
            border-color: #6366f1;
            background: white;

            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.10);
        }

        .textarea-wrapper .input-icon {
            top: 22px;
        }

        .password-strength {
            margin-top: 9px;
            display: none;
        }

        .strength-bars {
            display: flex;
            gap: 5px;
            margin-bottom: 5px;
        }

        .strength-bar {
            height: 4px;
            flex: 1;
            border-radius: 10px;
            background: #e2e8f0;
            transition: 0.3s ease;
        }

        .strength-text {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
        }

        .helper-text {
            display: block;
            margin-top: 7px;
            color: #94a3b8;
            font-size: 11px;
        }

        .register-btn {
            position: relative;
            width: 100%;
            height: 56px;

            border: none;
            border-radius: 15px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            color: white;

            font-size: 15px;
            font-weight: 700;

            overflow: hidden;

            box-shadow:
                0 12px 25px rgba(79, 70, 229, 0.25);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .register-btn::before {
            content: "";
            position: absolute;

            top: 0;
            left: -100%;

            width: 70%;
            height: 100%;

            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,0.25),
                transparent
            );

            transform: skewX(-20deg);

            transition: 0.6s ease;
        }

        .register-btn:hover::before {
            left: 130%;
        }

        .register-btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 18px 35px rgba(79, 70, 229, 0.35);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .login-area {
            margin-top: 26px;

            padding-top: 22px;

            border-top: 1px solid #e2e8f0;

            text-align: center;
        }

        .login-area p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .login-link {
            color: #4f46e5;
            font-weight: 700;

            text-decoration: none;

            transition: 0.25s ease;
        }

        .login-link:hover {
            color: #7c3aed;
            text-decoration: underline;
        }


        .custom-alert {
            border: none;
            border-radius: 14px;

            font-size: 13px;
            font-weight: 500;

            padding: 14px 16px;

            box-shadow:
                0 8px 20px rgba(15, 23, 42, 0.06);

            animation: alertAppear 0.4s ease;
        }

        @keyframes alertAppear {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            margin-top: 20px;

            color: #64748b;

            font-size: 11px;
        }

        .security-badge i {
            color: #10b981;
            font-size: 14px;
        }


        @media (max-width: 576px) {

            .register-wrapper {
                padding: 20px 12px;
            }

            .register-card {
                padding: 30px 22px;
                border-radius: 23px;
            }

            .register-icon {
                width: 68px;
                height: 68px;
                font-size: 29px;
                border-radius: 19px;
            }

            .register-title {
                font-size: 25px;
            }

            .register-subtitle {
                margin-bottom: 25px;
            }

            .custom-input {
                height: 52px;
            }

            .register-btn {
                height: 54px;
            }
        }

    </style>
</head>


<body>


    <div class="background-shape shape-one"></div>
    <div class="background-shape shape-two"></div>
    <div class="background-shape shape-three"></div>


  
    <main class="register-wrapper">

        <div class="register-card">

            <div class="text-center">

                <div class="register-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>

                <h1 class="register-title">
                    Create Account
                </h1>

                <p class="register-subtitle">
                    Register now and start managing your account
                </p>

            </div>


            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show custom-alert"
                    role="alert"
                >

                    <?php echo htmlspecialchars($message); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


         
            <form
                action=""
                method="POST"
                id="registerForm"
            >


                <div class="form-group-custom">

                    <label
                        for="name"
                        class="form-label"
                    >
                        <i class="bi bi-person me-1"></i>
                        Full Name
                    </label>

                    <div class="input-wrapper">

                        <input
                            type="text"
                            class="custom-input"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                            autocomplete="name"
                            required
                        >

                        <i class="bi bi-person input-icon"></i>

                    </div>

                </div>


              
                <div class="form-group-custom">

                    <label
                        for="email"
                        class="form-label"
                    >
                        <i class="bi bi-envelope me-1"></i>
                        Email Address
                    </label>

                    <div class="input-wrapper">

                        <input
                            type="email"
                            class="custom-input"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            autocomplete="email"
                            required
                        >

                        <i class="bi bi-envelope input-icon"></i>

                    </div>

                    <span class="helper-text">
                        Your email will be kept private and secure.
                    </span>

                </div>


                
                <div class="form-group-custom">

                    <label
                        for="password"
                        class="form-label"
                    >
                        <i class="bi bi-lock me-1"></i>
                        Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            type="password"
                            class="custom-input password-input"
                            id="password"
                            name="pass"
                            placeholder="Create a strong password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <i class="bi bi-lock input-icon"></i>

                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                            aria-label="Show password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>


                    <div
                        class="password-strength"
                        id="passwordStrength"
                    >

                        <div class="strength-bars">

                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>

                        </div>

                        <span
                            class="strength-text"
                            id="strengthText"
                        >
                            Password strength
                        </span>

                    </div>

                    <span class="helper-text">
                        Use at least 8 characters for better security.
                    </span>

                </div>

                <div class="form-group-custom">

                    <label
                        for="confirmPassword"
                        class="form-label"
                    >
                        <i class="bi bi-shield-lock me-1"></i>
                        Confirm Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            type="password"
                            class="custom-input"
                            id="confirmPassword"
                            name="confirm_pass"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            required
                        >

                        <i class="bi bi-shield-lock input-icon"></i>

                    </div>

                </div>

                <div class="form-group-custom">

                    <label
                        for="address"
                        class="form-label"
                    >
                        <i class="bi bi-geo-alt me-1"></i>
                        Address
                    </label>

                    <div class="input-wrapper textarea-wrapper">

                        <textarea
                            class="custom-textarea"
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Enter your address"
                            autocomplete="street-address"
                            required
                        ></textarea>

                        <i class="bi bi-geo-alt input-icon"></i>

                    </div>

                </div>

                <div>

                    <button
                        type="submit"
                        class="register-btn"
                        id="registerButton"
                    >

                        <span id="buttonContent">

                            <i class="bi bi-person-plus-fill me-2"></i>

                            Create Account

                        </span>

                    </button>

                </div>
                <div class="login-area">

                    <p>

                        Already have an account?

                        <a
                            href="login.php"
                            class="login-link"
                        >
                            Login here
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                    </p>

                </div>


            
                <div class="security-badge">

                    <i class="bi bi-shield-check"></i>

                    Your information is protected and secure

                </div>

            </form>

        </div>

    </main>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    ></script>


    <script>

        const togglePassword =
            document.getElementById("togglePassword");

        const password =
            document.getElementById("password");

        togglePassword.addEventListener("click", function () {

            if (password.type === "password") {

                password.type = "text";

                this.innerHTML =
                    '<i class="bi bi-eye-slash"></i>';

                this.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                password.type = "password";

                this.innerHTML =
                    '<i class="bi bi-eye"></i>';

                this.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        });


        const passwordStrength =
            document.getElementById("passwordStrength");

        const strengthText =
            document.getElementById("strengthText");

        const strengthBars =
            document.querySelectorAll(".strength-bar");


        password.addEventListener("input", function () {

            const value = this.value;

            if (value.length === 0) {

                passwordStrength.style.display = "none";

                return;

            }

            passwordStrength.style.display = "block";

            let score = 0;

            if (value.length >= 8) {
                score++;
            }

            if (/[A-Z]/.test(value)) {
                score++;
            }

            if (/[0-9]/.test(value)) {
                score++;
            }

            if (/[^A-Za-z0-9]/.test(value)) {
                score++;
            }


            strengthBars.forEach(function (bar) {

                bar.style.background = "#e2e8f0";

            });


            if (score === 1) {

                strengthBars[0].style.background = "#ef4444";

                strengthText.textContent =
                    "Weak password";

                strengthText.style.color =
                    "#ef4444";

            }

            else if (score === 2) {

                strengthBars[0].style.background = "#f59e0b";
                strengthBars[1].style.background = "#f59e0b";

                strengthText.textContent =
                    "Fair password";

                strengthText.style.color =
                    "#f59e0b";

            }

            else if (score === 3) {

                strengthBars[0].style.background = "#eab308";
                strengthBars[1].style.background = "#eab308";
                strengthBars[2].style.background = "#eab308";

                strengthText.textContent =
                    "Good password";

                strengthText.style.color =
                    "#ca8a04";

            }

            else if (score === 4) {

                strengthBars[0].style.background = "#22c55e";
                strengthBars[1].style.background = "#22c55e";
                strengthBars[2].style.background = "#22c55e";
                strengthBars[3].style.background = "#22c55e";

                strengthText.textContent =
                    "Strong password";

                strengthText.style.color =
                    "#16a34a";

            }

        });

        const confirmPassword =
            document.getElementById("confirmPassword");

        confirmPassword.addEventListener("input", function () {

            if (this.value === "") {

                this.style.borderColor =
                    "#e2e8f0";

                return;

            }

            if (this.value === password.value) {

                this.style.borderColor =
                    "#22c55e";

            } else {

                this.style.borderColor =
                    "#ef4444";

            }

        });

        const registerForm =
            document.getElementById("registerForm");

        const registerButton =
            document.getElementById("registerButton");

        const buttonContent =
            document.getElementById("buttonContent");


        registerForm.addEventListener("submit", function () {

            registerButton.disabled = true;

            buttonContent.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Creating Account...';

        });

        document
            .querySelectorAll(".custom-input, .custom-textarea")
            .forEach(function (input) {

                input.addEventListener("focus", function () {

                    this.parentElement
                        .querySelector(".input-icon")
                        ?.classList.add("active");

                });

                input.addEventListener("blur", function () {

                    this.parentElement
                        .querySelector(".input-icon")
                        ?.classList.remove("active");

                });

            });

    </script>

</body>

</html>












