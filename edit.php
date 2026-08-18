<?php

session_start();

include "dbconn.php";

/* ==================================================
   1. CHECK LOGIN
================================================== */

if (!isset($_SESSION['name'])) {

    header("Location: login.php");
    exit();

}

$loggedInUser = $_SESSION['name'];

$message = "";
$messageType = "";

$row = null;

$id = null;


/* ==================================================
   2. GET USER ID
================================================== */

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

    $sql = $conn->prepare(
        "SELECT id, name, age, email, address
         FROM usersdt
         WHERE id = ?"
    );

    $sql->bind_param("i", $id);

    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

    } else {

        $message = "User record not found.";
        $messageType = "danger";

    }

    $sql->close();

} else {

    $message = "No user was selected.";
    $messageType = "danger";

}


/* ==================================================
   3. UPDATE USER
================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST['id'] ?? "";

    $name = trim($_POST['name'] ?? "");

    $age = trim($_POST['age'] ?? "");

    $email = trim($_POST['email'] ?? "");

    $address = trim($_POST['address'] ?? "");


    /* ----------------------------------------------
       VALIDATE ID
    ---------------------------------------------- */

    if (!is_numeric($id)) {

        $message = "Invalid user ID.";
        $messageType = "danger";

    }


    /* ----------------------------------------------
       VALIDATE EMPTY FIELDS
    ---------------------------------------------- */

    elseif (
        empty($name) ||
        empty($age) ||
        empty($email) ||
        empty($address)
    ) {

        $message = "Please fill in all fields.";
        $messageType = "danger";

    }


    /* ----------------------------------------------
       VALIDATE AGE
    ---------------------------------------------- */

    elseif (
        !is_numeric($age) ||
        $age < 1 ||
        $age > 120
    ) {

        $message = "Please enter a valid age.";
        $messageType = "danger";

    }


    /* ----------------------------------------------
       VALIDATE EMAIL
    ---------------------------------------------- */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    }


    /* ----------------------------------------------
       UPDATE DATABASE
    ---------------------------------------------- */

    else {

        $id = (int) $id;

        $sql = $conn->prepare(
            "UPDATE usersdt
             SET name = ?, age = ?, email = ?, address = ?
             WHERE id = ?"
        );

        $sql->bind_param(
            "sissi",
            $name,
            $age,
            $email,
            $address,
            $id
        );


        if ($sql->execute()) {

            $sql->close();

            header("Location: homepage.php?updated=1");
            exit();

        } else {

            $message = "Unable to update user. Please try again.";
            $messageType = "danger";

        }

        $sql->close();

    }


    /* ----------------------------------------------
       KEEP ENTERED DATA IF ERROR
    ---------------------------------------------- */

    $row = [

        "id" => $id,

        "name" => $name,

        "age" => $age,

        "email" => $email,

        "address" => $address

    ];

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
        Edit User | User Management System
    </title>


    <!-- ==================================================
         BOOTSTRAP
    ================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- ==================================================
         BOOTSTRAP ICONS
    ================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    <!-- ==================================================
         GOOGLE FONT
    ================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        /* ==================================================
           GLOBAL
        ================================================== */

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

            overflow-x: hidden;

            background:

                radial-gradient(
                    circle at 10% 10%,
                    rgba(99, 102, 241, 0.16),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 90% 90%,
                    rgba(168, 85, 247, 0.14),
                    transparent 32%
                ),

                linear-gradient(
                    135deg,
                    #f8faff 0%,
                    #eef2ff 50%,
                    #faf8ff 100%
                );

        }


        /* ==================================================
           ANIMATED BACKGROUND
        ================================================== */

        .background-shape {

            position: fixed;

            border-radius: 50%;

            pointer-events: none;

            z-index: 0;

            filter: blur(1px);

        }


        .shape-one {

            width: 320px;

            height: 320px;

            top: -120px;

            left: -120px;

            background:
                rgba(79, 70, 229, 0.09);

            animation:
                floatOne 8s ease-in-out infinite;

        }


        .shape-two {

            width: 380px;

            height: 380px;

            right: -170px;

            bottom: -170px;

            background:
                rgba(124, 58, 237, 0.09);

            animation:
                floatTwo 10s ease-in-out infinite;

        }


        .shape-three {

            width: 130px;

            height: 130px;

            right: 12%;

            top: 20%;

            background:
                rgba(59, 130, 246, 0.07);

            animation:
                floatThree 7s ease-in-out infinite;

        }


        @keyframes floatOne {

            0%,
            100% {

                transform:
                    translate(0, 0);

            }

            50% {

                transform:
                    translate(30px, 25px);

            }

        }


        @keyframes floatTwo {

            0%,
            100% {

                transform:
                    translate(0, 0);

            }

            50% {

                transform:
                    translate(-30px, -25px);

            }

        }


        @keyframes floatThree {

            0%,
            100% {

                transform:
                    translateY(0);

            }

            50% {

                transform:
                    translateY(-25px);

            }

        }


        /* ==================================================
           MAIN WRAPPER
        ================================================== */

        .page-wrapper {

            position: relative;

            z-index: 1;

            min-height: 100vh;

            padding:
                30px 20px 40px;

        }


        .page-container {

            max-width: 850px;

            margin: auto;

        }


        /* ==================================================
           NAVBAR
        ================================================== */

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 30px;

            padding:
                17px 22px;

            border-radius: 22px;

            background:
                rgba(255, 255, 255, 0.82);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255, 255, 255, 0.75);

            box-shadow:
                0 15px 40px
                rgba(30, 41, 59, 0.08);

            animation:
                slideDown 0.7s ease both;

        }


        @keyframes slideDown {

            from {

                opacity: 0;

                transform:
                    translateY(-25px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        /* ==================================================
           BRAND
        ================================================== */

        .brand {

            display: flex;

            align-items: center;

            gap: 13px;

        }


        .brand-icon {

            width: 48px;

            height: 48px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            color: white;

            font-size: 21px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            box-shadow:
                0 10px 22px
                rgba(79, 70, 229, 0.25);

            animation:
                iconPulse 3s ease-in-out infinite;

        }


        @keyframes iconPulse {

            0%,
            100% {

                transform:
                    translateY(0);

            }

            50% {

                transform:
                    translateY(-3px);

            }

        }


        .brand-text h2 {

            margin: 0;

            color: #111827;

            font-size: 18px;

            font-weight: 800;

        }


        .brand-text p {

            margin: 2px 0 0;

            color: #64748b;

            font-size: 12px;

        }


        /* ==================================================
           USER AREA
        ================================================== */

        .user-area {

            display: flex;

            align-items: center;

            gap: 10px;

        }


        .user-info {

            text-align: right;

        }


        .user-info small {

            display: block;

            color: #94a3b8;

            font-size: 10px;

        }


        .user-info strong {

            display: block;

            color: #334155;

            font-size: 12px;

        }


        .user-avatar {

            width: 42px;

            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            color: white;

            font-size: 13px;

            font-weight: 700;

            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );

        }


        .logout-btn {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding:
                10px 14px;

            border-radius: 11px;

            color: #dc2626;

            background: #fff1f2;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition:
                0.25s ease;

        }


        .logout-btn:hover {

            color: white;

            background: #dc2626;

            transform:
                translateY(-2px);

        }


        /* ==================================================
           PAGE TITLE
        ================================================== */

        .page-heading {

            text-align: center;

            margin:
                10px 0 25px;

            animation:
                fadeUp 0.8s ease 0.15s both;

        }


        .page-heading h1 {

            margin: 0 0 7px;

            color: #111827;

            font-size: 30px;

            font-weight: 800;

            letter-spacing: -1px;

        }


        .page-heading p {

            margin: 0;

            color: #64748b;

            font-size: 13px;

        }


        /* ==================================================
           EDIT CARD
        ================================================== */

        .edit-card {

            position: relative;

            overflow: hidden;

            padding: 34px;

            border-radius: 26px;

            background:
                rgba(255, 255, 255, 0.88);

            backdrop-filter:
                blur(22px);

            -webkit-backdrop-filter:
                blur(22px);

            border:
                1px solid
                rgba(255, 255, 255, 0.8);

            box-shadow:
                0 25px 60px
                rgba(30, 41, 59, 0.10);

            animation:
                cardEntrance 0.8s
                cubic-bezier(.22,.61,.36,1)
                0.25s both;

        }


        .edit-card::before {

            content: "";

            position: absolute;

            width: 220px;

            height: 220px;

            right: -120px;

            top: -120px;

            border-radius: 50%;

            background:
                rgba(99, 102, 241, 0.08);

            pointer-events: none;

        }


        @keyframes cardEntrance {

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


        @keyframes fadeUp {

            from {

                opacity: 0;

                transform:
                    translateY(18px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        /* ==================================================
           EDIT ICON
        ================================================== */

        .page-icon {

            width: 70px;

            height: 70px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin:
                0 auto 18px;

            border-radius: 20px;

            color: #4f46e5;

            background:
                linear-gradient(
                    135deg,
                    #eef2ff,
                    #f3e8ff
                );

            box-shadow:
                0 10px 25px
                rgba(79, 70, 229, 0.12);

            animation:
                iconFloat 3s ease-in-out infinite;

        }


        .page-icon i {

            font-size: 28px;

        }


        @keyframes iconFloat {

            0%,
            100% {

                transform:
                    translateY(0)
                    rotate(0deg);

            }

            50% {

                transform:
                    translateY(-5px)
                    rotate(-2deg);

            }

        }


        /* ==================================================
           FORM
        ================================================== */

        .form-label {

            color: #334155;

            font-size: 12px;

            font-weight: 700;

            margin-bottom: 8px;

        }


        .form-label i {

            color: #6366f1;

        }


        .form-control {

            min-height: 48px;

            border:
                1px solid
                #e2e8f0;

            border-radius: 12px;

            background:
                rgba(255, 255, 255, 0.9);

            color: #1e293b;

            font-size: 13px;

            transition:
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                transform 0.25s ease,
                background 0.25s ease;

        }


        textarea.form-control {

            min-height: 110px;

            resize: vertical;

        }


        .form-control:hover {

            border-color: #c7d2fe;

        }


        .form-control:focus {

            border-color: #6366f1;

            background: white;

            box-shadow:
                0 0 0 4px
                rgba(99, 102, 241, 0.10),
                0 8px 20px
                rgba(79, 70, 229, 0.07);

            transform:
                translateY(-1px);

            outline: none;

        }


        .form-control::placeholder {

            color: #a1a1aa;

        }


        .readonly-field {

            background:
                #f8fafc !important;

            color: #64748b;

            cursor: not-allowed;

        }


        /* ==================================================
           FORM GROUP ANIMATION
        ================================================== */

        .form-group {

            animation:
                fadeUp 0.6s ease both;

        }


        .form-group:nth-child(1) {

            animation-delay: 0.35s;

        }


        .form-group:nth-child(2) {

            animation-delay: 0.42s;

        }


        .form-group:nth-child(3) {

            animation-delay: 0.49s;

        }


        .form-group:nth-child(4) {

            animation-delay: 0.56s;

        }


        .form-group:nth-child(5) {

            animation-delay: 0.63s;

        }


        /* ==================================================
           BUTTONS
        ================================================== */

        .button-row {

            display: flex;

            gap: 10px;

            margin-top: 25px;

            animation:
                fadeUp 0.7s ease 0.7s both;

        }


        .cancel-btn,
        .update-btn {

            min-height: 50px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            border-radius: 12px;

            font-size: 13px;

            font-weight: 700;

            text-decoration: none;

            transition:
                0.25s ease;

        }


        .cancel-btn {

            flex: 1;

            color: #475569;

            background: #f8fafc;

            border:
                1px solid
                #e2e8f0;

        }


        .cancel-btn:hover {

            color: #334155;

            background: #f1f5f9;

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 18px
                rgba(30, 41, 59, 0.08);

        }


        .update-btn {

            flex: 1;

            border: none;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            box-shadow:
                0 10px 22px
                rgba(79, 70, 229, 0.22);

        }


        .update-btn:hover {

            color: white;

            transform:
                translateY(-3px);

            box-shadow:
                0 15px 30px
                rgba(79, 70, 229, 0.32);

        }


        .update-btn:active,
        .cancel-btn:active {

            transform:
                translateY(0)
                scale(0.98);

        }


        /* ==================================================
           ALERT
        ================================================== */

        .custom-alert {

            border: none;

            border-radius: 12px;

            font-size: 13px;

            font-weight: 600;

            animation:
                alertEntrance 0.5s ease both;

        }


        @keyframes alertEntrance {

            from {

                opacity: 0;

                transform:
                    translateY(-10px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        /* ==================================================
           FOOTER
        ================================================== */

        .footer {

            padding:
                22px 0 5px;

            text-align: center;

            color: #94a3b8;

            font-size: 11px;

            animation:
                fadeUp 0.8s ease 0.8s both;

        }


        /* ==================================================
           MOBILE
        ================================================== */

        @media (max-width: 768px) {

            .page-wrapper {

                padding:
                    20px 12px 30px;

            }


            .topbar {

                padding:
                    14px 15px;

            }


            .user-info {

                display: none;

            }


            .page-heading h1 {

                font-size: 26px;

            }


            .edit-card {

                padding:
                    25px 20px;

            }

        }


        @media (max-width: 576px) {

            .brand-text p {

                display: none;

            }


            .logout-btn span {

                display: none;

            }


            .logout-btn {

                width: 38px;

                height: 38px;

                padding: 0;

                justify-content: center;

            }


            .brand-icon {

                width: 43px;

                height: 43px;

            }


            .button-row {

                flex-direction: column;

            }


            .cancel-btn,
            .update-btn {

                width: 100%;

            }


            .edit-card {

                border-radius: 20px;

            }

        }


        /* ==================================================
           REDUCED MOTION
        ================================================== */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {

                animation-duration: 0.01ms !important;

                animation-iteration-count: 1 !important;

                transition-duration: 0.01ms !important;

            }

        }

    </style>

</head>


<body>


<!-- ==================================================
     BACKGROUND SHAPES
================================================== -->

<div class="background-shape shape-one"></div>

<div class="background-shape shape-two"></div>

<div class="background-shape shape-three"></div>


<main class="page-wrapper">

    <div class="page-container">


        <!-- ==================================================
             TOPBAR
        ================================================== -->

        <nav class="topbar">


            <div class="brand">

                <div class="brand-icon">

                    <i class="bi bi-people-fill"></i>

                </div>


                <div class="brand-text">

                    <h2>User Management</h2>

                    <p>
                        Manage your users easily
                    </p>

                </div>

            </div>


            <div class="user-area">


                <div class="user-info">

                    <small>
                        Logged in as
                    </small>

                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $loggedInUser
                        );
                        ?>

                    </strong>

                </div>


                <div class="user-avatar">

                    <?php

                    echo strtoupper(
                        substr(
                            $loggedInUser,
                            0,
                            1
                        )
                    );

                    ?>

                </div>


                <a
                    href="logout.php"
                    class="logout-btn"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    <span>
                        Logout
                    </span>

                </a>


            </div>

        </nav>


        <!-- ==================================================
             PAGE HEADING
        ================================================== -->

        <section class="page-heading">

            <h1>
                Edit User
            </h1>

            <p>
                Update the user's information below.
            </p>

        </section>


        <!-- ==================================================
             EDIT CARD
        ================================================== -->

        <section class="edit-card">


            <!-- ICON -->

            <div class="page-icon">

                <i class="bi bi-pencil-square"></i>

            </div>


            <!-- ALERT -->

            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php echo htmlspecialchars($messageType); ?> custom-alert mb-4"
                    role="alert"
                >

                    <i
                        class="bi bi-exclamation-circle me-2"
                    ></i>

                    <?php

                    echo htmlspecialchars(
                        $message
                    );

                    ?>

                </div>

            <?php endif; ?>


            <?php if ($row !== null): ?>


                <!-- ==================================================
                     EDIT FORM
                ================================================== -->

                <form
                    action="edit.php?id=<?php echo urlencode($row['id']); ?>"
                    method="POST"
                >


                    <!-- HIDDEN ID -->

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo htmlspecialchars($row['id']); ?>"
                    >


                    <!-- USER ID -->

                    <div class="mb-3 form-group">

                        <label
                            class="form-label"
                        >

                            <i class="bi bi-hash me-1"></i>

                            User ID

                        </label>


                        <input
                            type="text"
                            class="form-control readonly-field"
                            value="<?php echo htmlspecialchars($row['id']); ?>"
                            readonly
                        >

                    </div>


                    <!-- NAME -->

                    <div class="mb-3 form-group">

                        <label
                            for="name"
                            class="form-label"
                        >

                            <i class="bi bi-person me-1"></i>

                            Full Name

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="<?php echo htmlspecialchars($row['name']); ?>"
                            placeholder="Enter full name"
                            autocomplete="name"
                            required
                        >

                    </div>


                    <!-- AGE -->

                    <div class="mb-3 form-group">

                        <label
                            for="age"
                            class="form-label"
                        >

                            <i class="bi bi-calendar3 me-1"></i>

                            Age

                        </label>


                        <input
                            type="number"
                            class="form-control"
                            id="age"
                            name="age"
                            value="<?php echo htmlspecialchars($row['age']); ?>"
                            min="1"
                            max="120"
                            placeholder="Enter age"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3 form-group">

                        <label
                            for="email"
                            class="form-label"
                        >

                            <i class="bi bi-envelope me-1"></i>

                            Email Address

                        </label>


                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($row['email']); ?>"
                            placeholder="Enter email address"
                            autocomplete="email"
                            required
                        >

                    </div>


                    <!-- ADDRESS -->

                    <div class="mb-3 form-group">

                        <label
                            for="address"
                            class="form-label"
                        >

                            <i class="bi bi-geo-alt me-1"></i>

                            Address

                        </label>


                        <textarea
                            class="form-control"
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Enter address"
                            required
                        ><?php echo htmlspecialchars($row['address']); ?></textarea>

                    </div>


                    <!-- ==================================================
                         BUTTONS
                    ================================================== -->

                    <div class="button-row">


                        <a
                            href="homepage.php"
                            class="cancel-btn"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="update-btn"
                        >

                            <i class="bi bi-check-circle"></i>

                            Update User

                        </button>


                    </div>


                </form>


            <?php endif; ?>


        </section>


        <!-- ==================================================
             FOOTER
        ================================================== -->

        <footer class="footer">

            User Management System

            &nbsp;•&nbsp;

            Secure Dashboard

            &nbsp;•&nbsp;

            © <?php echo date("Y"); ?>

        </footer>


    </div>

</main>


<!-- ==================================================
     BOOTSTRAP JS
================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>