<?php

session_start();

include "dbconn.php";

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$loggedInUser = $_SESSION['name'];

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $age = trim($_POST["age"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if ($name === "" || $age === "" || $email === "" || $address === "") {

        $message = "Please fill in all fields.";
        $messageType = "danger";

    } elseif (!is_numeric($age) || $age < 1 || $age > 120) {

        $message = "Please enter a valid age.";
        $messageType = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO usersdt (name, age, email, address)
             VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            $stmt->bind_param(
                "siss",
                $name,
                $age,
                $email,
                $address
            );

            if ($stmt->execute()) {

                header("Location: homepage.php");
                exit();

            } else {

                $message = "Failed to add user.";
                $messageType = "danger";
            }

            $stmt->close();

        } else {

            $message = "Database error.";
            $messageType = "danger";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add New User | User Management</title>

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

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", sans-serif;

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
                    #f8faff,
                    #eef2ff,
                    #faf8ff
                );
        }

        .page {
            min-height: 100vh;
            padding: 35px 20px;
        }

        .container-box {
            max-width: 750px;
            margin: auto;
        }

        /* TOP BAR */

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 18px 22px;
            margin-bottom: 25px;

            border-radius: 22px;

            background: rgba(255,255,255,0.85);

            backdrop-filter: blur(20px);

            box-shadow:
                0 15px 40px rgba(30,41,59,0.08);
        }

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
            font-size: 22px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );
        }

        .brand h2 {
            margin: 0;

            font-size: 18px;
            font-weight: 800;

            color: #111827;
        }

        .brand p {
            margin: 2px 0 0;

            color: #64748b;
            font-size: 12px;
        }

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

        .avatar {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            color: white;
            font-weight: 700;

            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );
        }

        .logout {
            display: inline-flex;
            align-items: center;

            gap: 6px;

            padding: 9px 12px;

            border-radius: 10px;

            color: #dc2626;
            background: #fff1f2;

            text-decoration: none;

            font-size: 12px;
            font-weight: 700;
        }

    

        .form-card {
            background: rgba(255,255,255,0.90);

            border-radius: 24px;

            box-shadow:
                0 20px 55px rgba(30,41,59,0.10);

            overflow: hidden;
        }

        .form-header {
            padding: 25px;

            border-bottom: 1px solid #eef2f7;
        }

        .form-heading {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .form-icon {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            color: #4f46e5;
            background: #eef2ff;

            font-size: 21px;
        }

        .form-header h1 {
            margin: 0;

            font-size: 22px;
            font-weight: 800;

            color: #111827;
        }

        .form-header p {
            margin: 3px 0 0;

            color: #94a3b8;
            font-size: 12px;
        }


        .form-body {
            padding: 28px 25px;
        }

        .form-label {
            margin-bottom: 7px;

            font-size: 13px;
            font-weight: 700;

            color: #334155;
        }

        .form-control {
            min-height: 48px;

            border-radius: 12px;

            border: 1px solid #e2e8f0;

            background: #f8fafc;

            font-size: 13px;
        }

        .form-control:focus {
            border-color: #6366f1;

            background: white;

            box-shadow:
                0 0 0 4px
                rgba(99,102,241,0.10);
        }

        textarea.form-control {
            min-height: 95px;
            resize: vertical;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .required {
            color: #dc2626;
        }

        /* BUTTONS */

        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;

            margin-top: 25px;
        }

        .back-btn {
            padding: 12px 18px;

            border-radius: 11px;

            background: #f1f5f9;
            color: #475569;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;
        }

        .add-btn {
            padding: 12px 20px;

            border: none;

            border-radius: 11px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            font-size: 13px;
            font-weight: 700;

            box-shadow:
                0 8px 20px
                rgba(79,70,229,0.20);
        }

        .alert {
            border-radius: 12px;
            font-size: 13px;
        }

        .footer {
            padding: 25px;

            text-align: center;

            color: #94a3b8;

            font-size: 11px;
        }

        @media(max-width:600px) {

            .page {
                padding: 20px 12px;
            }

            .user-info {
                display: none;
            }

            .brand p {
                display: none;
            }

            .buttons {
                flex-direction: column-reverse;
            }

            .back-btn,
            .add-btn {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>

<body>

<div class="page">

    <div class="container-box">

    

        <nav class="topbar">

            <div class="brand">

                <div class="brand-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>

                    <h2>User Management</h2>

                    <p>Add and manage users</p>

                </div>

            </div>


            <div class="user-area">

                <div class="user-info">

                    <small>Logged in as</small>

                    <strong>
                        <?php
                        echo htmlspecialchars($loggedInUser);
                        ?>
                    </strong>

                </div>

                <div class="avatar">

                    <?php
                    echo strtoupper(
                        substr($loggedInUser, 0, 1)
                    );
                    ?>

                </div>

                <a
                    href="logout.php"
                    class="logout"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>

            </div>

        </nav>


        <section class="form-card">


            <div class="form-header">

                <div class="form-heading">

                    <div class="form-icon">

                        <i class="bi bi-person-plus-fill"></i>

                    </div>

                    <div>

                        <h1>Add New User</h1>

                        <p>
                            Enter the user's information
                        </p>

                    </div>

                </div>

            </div>


            <div class="form-body">


                <?php if ($message !== ""): ?>

                    <div class="alert alert-<?php echo $messageType; ?>">

                        <?php
                        echo htmlspecialchars($message);
                        ?>

                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    action="add.php"
                >


                    <div class="form-group">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            placeholder="Enter user's name"
                            value="<?php
                            echo htmlspecialchars(
                                $_POST["name"] ?? ""
                            );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label
                            for="age"
                            class="form-label"
                        >
                            Age
                            <span class="required">*</span>
                        </label>

                        <input
                            type="number"
                            id="age"
                            name="age"
                            class="form-control"
                            placeholder="Enter age"
                            min="1"
                            max="120"
                            value="<?php
                            echo htmlspecialchars(
                                $_POST["age"] ?? ""
                            );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                            <span class="required">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email address"
                            value="<?php
                            echo htmlspecialchars(
                                $_POST["email"] ?? ""
                            );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label
                            for="address"
                            class="form-label"
                        >
                            Address
                            <span class="required">*</span>
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            class="form-control"
                            placeholder="Enter address"
                            required
                        ><?php
                        echo htmlspecialchars(
                            $_POST["address"] ?? ""
                        );
                        ?></textarea>

                    </div>


        

                    <div class="buttons">

                


                        <button
                            type="submit"
                            class="add-btn"
                        >
                            <i class="bi bi-person-plus-fill"></i>
                            Add User
                        </button>

                    </div>

                </form>

            </div>

        </section>


        <footer class="footer">

            User Management System
            &nbsp;•&nbsp;
            Secure Dashboard
            &nbsp;•&nbsp;
            © <?php echo date("Y"); ?>

        </footer>

    </div>

</div>

</body>

</html>