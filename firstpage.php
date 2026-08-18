<?php

session_start();

include "dbconn.php";

$message = "";
$messageType = "";

if (isset($_SESSION['name'])) {

    header("Location: homepage.php");
    exit();

}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = trim($_POST["name"] ?? "");
    $pass  = $_POST["pass"] ?? "";

    if (empty($login) || empty($pass)) {

        $message = "Please enter username/email and password.";
        $messageType = "danger";

    } else {

        $sql = $conn->prepare(
            "SELECT id, name, email, password
             FROM usersdt
             WHERE name = ? OR email = ?
             LIMIT 1"
        );

        $sql->bind_param(
            "ss",
            $login,
            $login
        );

        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();


            if (password_verify($pass, $user["password"])) {

                $_SESSION["name"] = $user["name"];
                $_SESSION["user_id"] = $user["id"];

                header("Location: homepage.php");
                exit();

            } else {

                $message = "Incorrect password.";
                $messageType = "danger";

            }

        } else {

            $message = "Username or email not found.";
            $messageType = "danger";

        }

        $sql->close();
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Login | User Management System
    </title>


  

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
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

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 30px 15px;

            color: #1e293b;

            overflow-x: hidden;

            background:

                radial-gradient(
                    circle at 10% 10%,
                    rgba(99, 102, 241, 0.18),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 90% 90%,
                    rgba(168, 85, 247, 0.16),
                    transparent 32%
                ),

                linear-gradient(
                    135deg,
                    #f8faff 0%,
                    #eef2ff 50%,
                    #faf8ff 100%
                );

        }

        .background-shape {

            position: fixed;

            border-radius: 50%;

            pointer-events: none;

            z-index: 0;

        }


        .shape-one {

            width: 330px;

            height: 330px;

            top: -130px;

            left: -120px;

            background:
                rgba(79, 70, 229, 0.08);

            animation:
                floatOne 8s ease-in-out infinite;

        }


        .shape-two {

            width: 380px;

            height: 380px;

            right: -170px;

            bottom: -170px;

            background:
                rgba(124, 58, 237, 0.08);

            animation:
                floatTwo 10s ease-in-out infinite;

        }


        .shape-three {

            width: 120px;

            height: 120px;

            right: 15%;

            top: 15%;

            background:
                rgba(59, 130, 246, 0.06);

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

            position: relative;

            z-index: 2;

            width: 100%;

            max-width: 460px;

            animation:
                pageEnter 0.7s ease forwards;

        }


        @keyframes pageEnter {

            from {

                opacity: 0;

                transform:
                    translateY(25px)
                    scale(0.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }

        .login-card {

            position: relative;

            overflow: hidden;

            padding: 38px;

            border-radius: 28px;

            background:
                rgba(255, 255, 255, 0.86);

            backdrop-filter:
                blur(22px);

            -webkit-backdrop-filter:
                blur(22px);

            border:
                1px solid
                rgba(255, 255, 255, 0.8);

            box-shadow:
                0 25px 70px
                rgba(30, 41, 59, 0.12);

        }


        .login-card::before {

            content: "";

            position: absolute;

            width: 180px;

            height: 180px;

            right: -90px;

            top: -90px;

            border-radius: 50%;

            background:
                rgba(99, 102, 241, 0.08);

        }

        .brand-icon {

            width: 70px;

            height: 70px;

            margin: 0 auto 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 21px;

            color: white;

            font-size: 30px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            box-shadow:
                0 15px 30px
                rgba(79, 70, 229, 0.25);

            animation:
                iconFloat 4s ease-in-out infinite;

        }


        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }

        }


        .login-title {

            text-align: center;

            margin-bottom: 7px;

            color: #111827;

            font-size: 28px;

            font-weight: 800;

            letter-spacing: -0.7px;

        }


        .login-subtitle {

            text-align: center;

            margin-bottom: 28px;

            color: #64748b;

            font-size: 13px;

        }


        .form-label {

            color: #334155;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 8px;

        }


        .input-group-custom {

            position: relative;

            margin-bottom: 20px;

        }


        .input-icon {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #6366f1;

            z-index: 5;

        }


        .form-control {

            height: 52px;

            padding-left: 45px;

            padding-right: 45px;

            border-radius: 13px;

            border: 1px solid #e2e8f0;

            background: #f8fafc;

            color: #1e293b;

            font-size: 13px;

            transition:
                0.25s ease;

        }


        .form-control:focus {

            background: white;

            border-color: #6366f1;

            box-shadow:
                0 0 0 4px
                rgba(99, 102, 241, 0.10);

        }


        .password-toggle {

            position: absolute;

            right: 14px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            color: #94a3b8;

            cursor: pointer;

            z-index: 5;

        }


        .password-toggle:hover {

            color: #4f46e5;

        }

        .login-btn {

            width: 100%;

            height: 52px;

            border: none;

            border-radius: 13px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            font-size: 14px;

            font-weight: 700;

            box-shadow:
                0 10px 25px
                rgba(79, 70, 229, 0.22);

            transition:
                0.25s ease;

        }


        .login-btn:hover {

            color: white;

            transform:
                translateY(-2px);

            box-shadow:
                0 15px 30px
                rgba(79, 70, 229, 0.30);

        }


        .login-btn:active {

            transform:
                translateY(0);

        }

        .register-section {

            text-align: center;

            margin-top: 25px;

            padding-top: 22px;

            border-top:
                1px solid #eef2f7;

        }


        .register-section p {

            margin-bottom: 10px;

            color: #94a3b8;

            font-size: 12px;

        }


        .register-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 10px 18px;

            border-radius: 11px;

            color: #4f46e5;

            background: #eef2ff;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition:
                0.25s ease;

        }


        .register-btn:hover {

            color: white;

            background: #4f46e5;

            transform:
                translateY(-2px);

        }

        .custom-alert {

            border: none;

            border-radius: 12px;

            font-size: 12px;

            font-weight: 600;

            margin-bottom: 20px;

        }

        .login-footer {

            text-align: center;

            margin-top: 20px;

            color: #94a3b8;

            font-size: 10px;

        }

        @media (max-width: 576px) {

            body {

                padding: 20px 12px;

            }


            .login-card {

                padding: 28px 22px;

                border-radius: 22px;

            }


            .login-title {

                font-size: 25px;

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


          

            <div class="brand-icon">

                <i class="bi bi-people-fill"></i>

            </div>


            <h1 class="login-title">

                Welcome Back

            </h1>


            <p class="login-subtitle">

                Login to your User Management System

            </p>


           

            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php echo htmlspecialchars($messageType); ?> custom-alert"
                >

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            

            <form
                method="POST"
                action="login.php"
            >


               

                <label
                    for="name"
                    class="form-label"
                >

                    Username or Email

                </label>


                <div class="input-group-custom">

                    <i
                        class="bi bi-person input-icon"
                    ></i>


                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        placeholder="Enter username or email"
                        autocomplete="username"
                        required
                    >

                </div>


               

                <label
                    for="pass"
                    class="form-label"
                >

                    Password

                </label>


                <div class="input-group-custom">

                    <i
                        class="bi bi-lock input-icon"
                    ></i>


                    <input
                        type="password"
                        id="pass"
                        name="pass"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >


                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword()"
                    >

                        <i
                            id="passwordIcon"
                            class="bi bi-eye"
                        ></i>

                    </button>

                </div>


               

                <button
                    type="submit"
                    class="login-btn"
                >

                    <i class="bi bi-box-arrow-in-right me-2"></i>

                    Login

                </button>


            </form>


           

            <div class="register-section">

                <p>
                    Don't have an account?
                </p>


              

                <a
                    href="register.php"
                    class="register-btn"
                >

                    <i class="bi bi-person-plus-fill"></i>

                    Create New Account

                </a>

            </div>


        </div>


        <div class="login-footer">

            User Management System
            &nbsp;•&nbsp;
            Secure Login
            &nbsp;•&nbsp;
            © <?php echo date("Y"); ?>

        </div>


    </main>


    <script>

        function togglePassword() {

            const password =
                document.getElementById("pass");

            const icon =
                document.getElementById("passwordIcon");


            if (password.type === "password") {

                password.type = "text";

                icon.classList.remove("bi-eye");

                icon.classList.add("bi-eye-slash");

            } else {

                password.type = "password";

                icon.classList.remove("bi-eye-slash");

                icon.classList.add("bi-eye");

            }

        }

    </script>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>