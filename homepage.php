<?php

session_start();

include "dbconn.php";


if (!isset($_SESSION['name'])) {

    header("Location: login.php");
    exit();

}

$loggedInUser = $_SESSION['name'];


$result = $conn->query(
    "SELECT id, name, age, email, address
     FROM usersdt
     ORDER BY id ASC"
);



$countResult = $conn->query(
    "SELECT COUNT(*) AS total
     FROM usersdt"
);

$countRow = $countResult->fetch_assoc();

$totalUsers = $countRow["total"] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Home | User Management System</title>


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

            color: #1e293b;

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

            overflow-x: hidden;

        }

        .background-shape {

            position: fixed;

            border-radius: 50%;

            pointer-events: none;

            z-index: 0;

        }

        .shape-one {

            width: 320px;

            height: 320px;

            top: -120px;

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

            width: 130px;

            height: 130px;

            right: 12%;

            top: 20%;

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

        .dashboard-wrapper {

            position: relative;

            z-index: 1;

            min-height: 100vh;

            padding: 35px 20px;

        }


        .dashboard-container {

            max-width: 1250px;

            margin: auto;

        }

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 30px;

            padding: 18px 22px;

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

            box-shadow:
                0 10px 22px
                rgba(79, 70, 229, 0.25);

        }


        .brand-text h2 {

            margin: 0;

            font-size: 18px;

            font-weight: 800;

            color: #111827;

        }


        .brand-text p {

            margin: 2px 0 0;

            color: #64748b;

            font-size: 12px;

        }


        .user-area {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .user-info {

            text-align: right;

        }


        .user-info small {

            display: block;

            color: #94a3b8;

            font-size: 11px;

        }


        .user-info strong {

            display: block;

            color: #334155;

            font-size: 13px;

        }


        .user-avatar {

            width: 42px;

            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );

            font-weight: 700;

        }


        .logout-btn {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 10px 14px;

            border-radius: 11px;

            color: #dc2626;

            background: #fff1f2;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition: 0.25s ease;

        }


        .logout-btn:hover {

            color: white;

            background: #dc2626;

            transform: translateY(-2px);

        }

        .welcome-section {

            margin-bottom: 25px;

        }


        .welcome-section h1 {

            margin-bottom: 7px;

            color: #111827;

            font-size: 32px;

            font-weight: 800;

            letter-spacing: -1px;

        }


        .welcome-section p {

            margin: 0;

            color: #64748b;

            font-size: 14px;

        }

        .stats-card {

            position: relative;

            overflow: hidden;

            padding: 24px;

            margin-bottom: 28px;

            border-radius: 22px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            color: white;

            box-shadow:
                0 18px 40px
                rgba(79, 70, 229, 0.20);

        }


        .stats-card::after {

            content: "";

            position: absolute;

            width: 160px;

            height: 160px;

            right: -60px;

            top: -60px;

            border-radius: 50%;

            background:
                rgba(255,255,255,0.10);

        }


        .stats-content {

            position: relative;

            z-index: 2;

        }


        .stats-label {

            font-size: 13px;

            opacity: 0.85;

            margin-bottom: 5px;

        }


        .stats-number {

            font-size: 34px;

            font-weight: 800;

            line-height: 1;

        }


        .stats-icon {

            position: absolute;

            right: 25px;

            top: 50%;

            transform: translateY(-50%);

            font-size: 48px;

            opacity: 0.18;

        }

        .table-card {

            background:
                rgba(255, 255, 255, 0.88);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255, 255, 255, 0.75);

            border-radius: 24px;

            box-shadow:
                0 20px 55px
                rgba(30, 41, 59, 0.09);

            overflow: hidden;

        }


        .table-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 23px 25px;

            border-bottom:
                1px solid
                #eef2f7;

        }


        .table-title {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .table-title-icon {

            width: 42px;

            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            color: #4f46e5;

            background: #eef2ff;

        }


        .table-title h3 {

            margin: 0;

            font-size: 17px;

            font-weight: 800;

            color: #111827;

        }


        .table-title p {

            margin: 2px 0 0;

            color: #94a3b8;

            font-size: 11px;

        }

        .add-user-btn {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 11px 17px;

            border: none;

            border-radius: 12px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            font-size: 13px;

            font-weight: 700;

            text-decoration: none;

            box-shadow:
                0 8px 20px
                rgba(79, 70, 229, 0.20);

            transition: 0.25s ease;

        }


        .add-user-btn:hover {

            color: white;

            transform:
                translateY(-2px);

            box-shadow:
                0 12px 25px
                rgba(79, 70, 229, 0.30);

        }

        .table-responsive {

            overflow-x: auto;

        }


        .user-table {

            width: 100%;

            margin: 0;

            border-collapse: separate;

            border-spacing: 0;

        }


        .user-table thead th {

            padding: 16px 20px;

            color: #64748b;

            background: #f8fafc;

            border-bottom:
                1px solid
                #e2e8f0;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            white-space: nowrap;

        }


        .user-table tbody td {

            padding: 17px 20px;

            color: #475569;

            border-bottom:
                1px solid
                #f1f5f9;

            font-size: 13px;

            vertical-align: middle;

        }


        .user-table tbody tr {

            transition:
                background 0.2s ease,
                transform 0.2s ease;

        }


        .user-table tbody tr:hover {

            background:
                rgba(238, 242, 255, 0.55);

        }


        .user-table tbody tr:last-child td {

            border-bottom: none;

        }

    
        .user-cell {

            display: flex;

            align-items: center;

            gap: 11px;

        }


        .small-avatar {

            width: 38px;

            height: 38px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 11px;

            color: #4f46e5;

            background: #eef2ff;

            font-weight: 800;

            font-size: 13px;

        }


        .user-name {

            color: #1e293b;

            font-weight: 700;

        }

        .age-badge {

            display: inline-flex;

            align-items: center;

            padding: 6px 10px;

            border-radius: 8px;

            color: #475569;

            background: #f1f5f9;

            font-size: 11px;

            font-weight: 700;

        }

        .email-cell {

            color: #6366f1;

            font-weight: 500;

        }


        
        .action-buttons {

            display: flex;

            align-items: center;

            gap: 7px;

        }


        .action-btn {

            width: 36px;

            height: 36px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            border: none;

            text-decoration: none;

            transition: 0.22s ease;

        }


        .edit-btn {

            color: #2563eb;

            background: #eff6ff;

        }


        .edit-btn:hover {

            color: white;

            background: #2563eb;

            transform:
                translateY(-2px);

        }


        .delete-btn {

            color: #dc2626;

            background: #fef2f2;

        }


        .delete-btn:hover {

            color: white;

            background: #dc2626;

            transform:
                translateY(-2px);

        }
        .empty-state {

            padding: 65px 20px;

            text-align: center;

        }


        .empty-icon {

            width: 70px;

            height: 70px;

            margin: 0 auto 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 20px;

            color: #6366f1;

            background: #eef2ff;

            font-size: 28px;

        }


        .empty-state h4 {

            margin-bottom: 7px;

            color: #1e293b;

            font-size: 17px;

            font-weight: 800;

        }


        .empty-state p {

            margin-bottom: 20px;

            color: #94a3b8;

            font-size: 13px;

        }

        .footer {

            padding: 25px 0 5px;

            text-align: center;

            color: #94a3b8;

            font-size: 11px;

        }

        @media (max-width: 768px) {

            .dashboard-wrapper {

                padding:
                    20px 12px;

            }


            .topbar {

                padding:
                    15px;

            }


            .user-info {

                display: none;

            }


            .welcome-section h1 {

                font-size: 26px;

            }


            .table-header {

                align-items: flex-start;

                flex-direction: column;

            }


            .add-user-btn {

                width: 100%;

                justify-content: center;

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


            .stats-card {

                padding: 20px;

            }


            .stats-number {

                font-size: 30px;

            }

        }

    </style>

</head>


<body>
    <div class="background-shape shape-one"></div>

    <div class="background-shape shape-two"></div>

    <div class="background-shape shape-three"></div>

    <main class="dashboard-wrapper">

        <div class="dashboard-container">

            <nav class="topbar">

                <div class="brand">

                    <div class="brand-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>


                    <div class="brand-text">

                        <h2>User Management</h2>

                        <p>Manage your users easily</p>

                    </div>

                </div>


                <div class="user-area">

                    <div class="user-info">

                        <small>Logged in as</small>

                        <strong>
                            <?php echo htmlspecialchars($loggedInUser); ?>
                        </strong>

                    </div>


                    <div class="user-avatar">

                        <?php

                        echo strtoupper(
                            substr($loggedInUser, 0, 1)
                        );

                        ?>

                    </div>


                    <a
                        href="logout.php"
                        class="logout-btn"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        <span>Logout</span>

                    </a>

                </div>

            </nav>

            <section class="welcome-section">

                <h1>

                    Welcome back,
                    <?php echo htmlspecialchars($loggedInUser); ?> 👋

                </h1>

                <p>

                    Manage registered users and their information
                    from your dashboard.

                </p>

            </section>

            <section class="stats-card">

                <div class="stats-content">

                    <div class="stats-label">

                        Total Registered Users

                    </div>


                    <div class="stats-number">

                        <?php echo $totalUsers; ?>

                    </div>

                </div>


                <i class="bi bi-people-fill stats-icon"></i>

            </section>

            <section class="table-card">


              

                <div class="table-header">

                    <div class="table-title">

                        <div class="table-title-icon">

                            <i class="bi bi-person-lines-fill"></i>

                        </div>


                        <div>

                            <h3>User Records</h3>

                            <p>
                                All information stored in usersdt
                            </p>

                        </div>

                    </div>


                    
                </div>

                <?php if ($result && $result->num_rows > 0): ?>

                    <div class="table-responsive">

                        <table class="user-table">

                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>User</th>

                                    <th>Age</th>

                                    <th>Email</th>

                                    <th>Address</th>

                                    <th>Actions</th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php while ($row = $result->fetch_assoc()): ?>

                                    <tr>

                                
                                        <td>

                                            <strong>
                                                #<?php
                                                echo htmlspecialchars(
                                                    $row["id"]
                                                );
                                                ?>
                                            </strong>

                                        </td>


                

                                        <td>

                                            <div class="user-cell">

                                                <div class="small-avatar">

                                                    <?php

                                                    echo strtoupper(
                                                        substr(
                                                            $row["name"],
                                                            0,
                                                            1
                                                        )
                                                    );

                                                    ?>

                                                </div>


                                                <div class="user-name">

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row["name"]
                                                    );

                                                    ?>

                                                </div>

                                            </div>

                                        </td>


                                       

                                        <td>

                                            <span class="age-badge">

                                                <?php

                                                echo htmlspecialchars(
                                                    $row["age"]
                                                );

                                                ?>

                                                years

                                            </span>

                                        </td>


                                                                          <td>

                                            <span class="email-cell">

                                                <i
                                                    class="bi bi-envelope me-1"
                                                ></i>

                                                <?php

                                                echo htmlspecialchars(
                                                    $row["email"]
                                                );

                                                ?>

                                            </span>

                                        </td>


                                       
                                        <td>

                                            <i
                                                class="bi bi-geo-alt me-1 text-secondary"
                                            ></i>

                                            <?php

                                            echo htmlspecialchars(
                                                $row["address"]
                                            );

                                            ?>

                                        </td>


                                        

                                        <td>

                                            <div class="action-buttons">


                                
                                                <a
                                                    href="edit.php?id=<?php echo urlencode($row["id"]); ?>"
                                                    class="action-btn edit-btn"
                                                    title="Edit User"
                                                >

                                                    <i
                                                        class="bi bi-pencil-fill"
                                                    ></i>

                                                </a>


                                                

                                                <a
                                                    href="delete.php?id=<?php echo urlencode($row["id"]); ?>"
                                                    class="action-btn delete-btn"
                                                    title="Delete User"
                                                    onclick="return confirm('Are you sure you want to delete this user?');"
                                                >

                                                    <i
                                                        class="bi bi-trash-fill"
                                                    ></i>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>


                <?php else: ?>


                                       <div class="empty-state">

                        <div class="empty-icon">

                            <i class="bi bi-people"></i>

                        </div>


                        <h4>

                            No users found

                        </h4>


                        <p>

                            There are currently no users
                            stored in the system.

                        </p>


                        <a
                            href="add.php"
                            class="add-user-btn"
                        >

                            <i class="bi bi-person-plus-fill"></i>

                            Add First User

                        </a>

                    </div>


                <?php endif; ?>


            </section>


               <footer class="footer">

                User Management System

                &nbsp;•&nbsp;

                Secure Dashboard

                &nbsp;•&nbsp;

                © <?php echo date("Y"); ?>

            </footer>


        </div>

    </main>


 

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>