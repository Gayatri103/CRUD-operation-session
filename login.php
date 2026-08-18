<?php

session_start();

include "dbconn.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $pass = $_POST["pass"] ?? "";

    if (empty($email) || empty($pass)) {

        $message = "Please enter your email and password.";
        $messageType = "danger";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    }

    else {

        $sql = $conn->prepare(
            "SELECT name, email, pass, address
             FROM Gayatri
             WHERE email = ?"
        );

        $sql->bind_param(
            "s",
            $email
        );

        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($pass, $user["pass"])) {


                session_regenerate_id(true);


                $_SESSION["name"] = $user["name"];

                $_SESSION["email"] = $user["email"];

            

                header("Location: homepage.php");

                exit();

            }

            else {

                $message = "Incorrect email or password.";
                $messageType = "danger";

            }

        }

        else {

            $message = "Incorrect email or password.";
            $messageType = "danger";

        }


        $sql->close();

    }

}

?>

<!doctype html>

<html lang="en" data-bs-theme="light">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Login | User Management System</title>
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


        html,
        body {
            margin: 0;
            min-height: 100%;
        }


        body {

            min-height: 100vh;

            font-family: "Inter", sans-serif;

            color: #1e293b;

            background:

                radial-gradient(
                    circle at 10% 20%,
                    rgba(99, 102, 241, 0.18),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 90% 80%,
                    rgba(168, 85, 247, 0.18),
                    transparent 30%
                ),

                linear-gradient(
                    135deg,
                    #f8faff 0%,
                    #eef2ff 45%,
                    #f8f7ff 100%
                );

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

            background:
                rgba(99, 102, 241, 0.12);

            top: -90px;

            left: -90px;

            animation:
                floatOne 8s ease-in-out infinite;

        }


        .shape-two {

            width: 350px;

            height: 350px;

            background:
                rgba(168, 85, 247, 0.10);

            bottom: -150px;

            right: -120px;

            animation:
                floatTwo 10s ease-in-out infinite;

        }


        .shape-three {

            width: 130px;

            height: 130px;

            background:
                rgba(59, 130, 246, 0.10);

            top: 30%;

            right: 10%;

            animation:
                floatThree 7s ease-in-out infinite;

        }


        @keyframes floatOne {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(30px, 25px);
            }

        }


        @keyframes floatTwo {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-30px, -25px);
            }

        }


        @keyframes floatThree {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-25px);
            }

        }
        .login-wrapper {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 40px 15px;

            position: relative;

            z-index: 1;

        }

        .login-card {

            width: 100%;

            max-width: 500px;

            background:
                rgba(255, 255, 255, 0.82);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255, 255, 255, 0.75);

            border-radius: 28px;

            padding: 42px;

            box-shadow:

                0 30px 80px
                rgba(30, 41, 59, 0.12),

                0 8px 30px
                rgba(99, 102, 241, 0.08);

            animation:
                cardAppear 0.8s ease forwards;

        }


        @keyframes cardAppear {

            from {

                opacity: 0;

                transform:
                    translateY(35px)
                    scale(0.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }

        .login-icon {

            width: 78px;

            height: 78px;

            margin:
                0 auto 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 22px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            color: white;

            font-size: 34px;

            box-shadow:
                0 15px 35px
                rgba(79, 70, 229, 0.30);

            animation:
                iconFloat 4s ease-in-out infinite;

        }


        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }

        .login-title {

            font-size: 30px;

            font-weight: 800;

            letter-spacing: -0.8px;

            color: #111827;

            margin-bottom: 8px;

        }


        .login-subtitle {

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

            transform:
                translateY(-50%);

            color: #94a3b8;

            font-size: 17px;

            z-index: 3;

            pointer-events: none;

            transition:
                0.3s ease;

        }
    .custom-input {

            width: 100%;

            height: 54px;

            border:
                1.5px solid
                #e2e8f0;

            border-radius: 14px;

            background:
                rgba(248, 250, 252, 0.9);

            padding:
                0 16px 0 46px;

            color: #1e293b;

            font-size: 14px;

            outline: none;

            transition:

                border-color 0.25s ease,

                box-shadow 0.25s ease,

                background 0.25s ease;

        }


        .custom-input::placeholder {

            color: #a1a9b8;

        }


        .custom-input:hover {

            border-color:
                #c7d2fe;

        }


        .custom-input:focus {

            border-color:
                #6366f1;

            background:
                #ffffff;

            box-shadow:
                0 0 0 4px
                rgba(99, 102, 241, 0.10);

        }


        .custom-input:focus + .input-icon {

            color:
                #6366f1;

        }

        .password-input {

            padding-right:
                55px;

        }


        .password-toggle {

            position: absolute;

            right: 12px;

            top: 50%;

            transform:
                translateY(-50%);

            width: 38px;

            height: 38px;

            border: none;

            background: transparent;

            color: #64748b;

            border-radius: 10px;

            transition:
                0.25s ease;

        }


        .password-toggle:hover {

            background:
                #eef2ff;

            color:
                #4f46e5;

        }

        .helper-text {

            display: block;

            margin-top: 7px;

            color: #94a3b8;

            font-size: 11px;

        }

        .custom-alert {

            border: none;

            border-radius: 14px;

            font-size: 13px;

            font-weight: 500;

            padding:
                14px 16px;

            box-shadow:
                0 8px 20px
                rgba(15, 23, 42, 0.06);

            animation:
                alertAppear 0.4s ease;

        }


        @keyframes alertAppear {

            from {

                opacity: 0;

                transform:
                    translateY(-8px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }

        .login-btn {

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
                0 12px 25px
                rgba(79, 70, 229, 0.25);

            transition:

                transform 0.25s ease,

                box-shadow 0.25s ease;

        }


        .login-btn::before {

            content: "";

            position: absolute;

            top: 0;

            left: -100%;

            width: 70%;

            height: 100%;

            background:

                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,0.25),
                    transparent
                );

            transform:
                skewX(-20deg);

            transition:
                0.6s ease;

        }


        .login-btn:hover::before {

            left: 130%;

        }


        .login-btn:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 18px 35px
                rgba(79, 70, 229, 0.35);

        }


        .login-btn:active {

            transform:
                translateY(0);

        }


        .login-btn:disabled {

            opacity:
                0.85;

            cursor:
                wait;

        }


        .register-area {

            margin-top: 26px;

            padding-top: 22px;

            border-top:
                1px solid
                #e2e8f0;

            text-align: center;

        }


        .register-area p {

            margin: 0;

            color: #64748b;

            font-size: 13px;

        }


        .register-link {

            color:
                #4f46e5;

            font-weight:
                700;

            text-decoration:
                none;

            transition:
                0.25s ease;

        }


        .register-link:hover {

            color:
                #7c3aed;

            text-decoration:
                underline;

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

            color:
                #10b981;

            font-size:
                14px;

        }

        @media (max-width: 576px) {

            .login-wrapper {

                padding:
                    20px 12px;

            }


            .login-card {

                padding:
                    30px 22px;

                border-radius:
                    23px;

            }


            .login-icon {

                width:
                    68px;

                height:
                    68px;

                font-size:
                    29px;

                border-radius:
                    19px;

            }


            .login-title {

                font-size:
                    25px;

            }


            .login-subtitle {

                margin-bottom:
                    25px;

            }


            .custom-input {

                height:
                    52px;

            }


            .login-btn {

                height:
                    54px;

            }

        }

    </style>

</head>


<body>


    

    <div class="background-shape shape-one"></div>

    <div class="background-shape shape-two"></div>

    <div class="background-shape shape-three"></div>


    
    <main class="login-wrapper">

        <div class="login-card">

  
            <div class="text-center">

                <div class="login-icon">

                    <i class="bi bi-person-fill-lock"></i>

                </div>


                <h1 class="login-title">

                    Welcome Back

                </h1>


                <p class="login-subtitle">

                    Login to continue to your account

                </p>

            </div>


            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show custom-alert"
                    role="alert"
                >

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?php

                    echo htmlspecialchars($message);

                    ?>


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
                id="loginForm"
            >


                

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
                            placeholder="Enter your registered email"
                            autocomplete="email"
                            value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                            required
                        >


                        <i
                            class="bi bi-envelope input-icon"
                        ></i>

                    </div>


                    <span class="helper-text">

                        Use the email address you entered during registration.

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
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >


                        <i
                            class="bi bi-lock input-icon"
                        ></i>


                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                            aria-label="Show password"
                        >

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>


             

                <div>

                    <button
                        type="submit"
                        class="login-btn"
                        id="loginButton"
                    >

                        <span id="buttonContent">

                            <i
                                class="bi bi-box-arrow-in-right me-2"
                            ></i>

                            Login

                        </span>

                    </button>

                </div>


            

                <div class="register-area">

                    <p>

                        Don't have an account?

                        <a
                            href="register.php"
                            class="register-link"
                        >

                            Create an account

                            <i
                                class="bi bi-arrow-right ms-1"
                            ></i>

                        </a>

                    </p>

                </div>

                <div class="security-badge">

                    <i class="bi bi-shield-check"></i>

                    Secure login · Your password is protected

                </div>


            </form>

        </div>

    </main>
    <script>

        const togglePassword =
            document.getElementById("togglePassword");

        const password =
            document.getElementById("password");


        togglePassword.addEventListener(
            "click",
            function () {

                if (password.type === "password") {

                    password.type = "text";

                    this.innerHTML =
                        '<i class="bi bi-eye-slash"></i>';

                    this.setAttribute(
                        "aria-label",
                        "Hide password"
                    );

                }

                else {

                    password.type = "password";

                    this.innerHTML =
                        '<i class="bi bi-eye"></i>';

                    this.setAttribute(
                        "aria-label",
                        "Show password"
                    );

                }

            }
        );

        const loginForm =
            document.getElementById("loginForm");

        const loginButton =
            document.getElementById("loginButton");

        const buttonContent =
            document.getElementById("buttonContent");


        loginForm.addEventListener(
            "submit",
            function () {

                loginButton.disabled = true;

                buttonContent.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Signing in...';

            }
        );
  document
            .querySelectorAll(".custom-input")
            .forEach(function (input) {

                input.addEventListener(
                    "focus",
                    function () {

                        const icon =
                            this.parentElement
                                .querySelector(".input-icon");

                        if (icon) {

                            icon.style.color =
                                "#6366f1";

                        }

                    }
                );


                input.addEventListener(
                    "blur",
                    function () {

                        const icon =
                            this.parentElement
                                .querySelector(".input-icon");

                        if (icon) {

                            icon.style.color =
                                "#94a3b8";

                        }

                    }
                );

            });

    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>