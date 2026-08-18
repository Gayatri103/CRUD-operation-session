<?php

session_start();

include "dbconn.php";


if (!isset($_SESSION['name'])) {

    header("Location: login.php");
    exit();

}


$message = "";
$messageType = "";

$user = null;

$id = null;

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

        $user = $result->fetch_assoc();

    } else {

        $message = "User record not found.";

        $messageType = "danger";

    }


    $sql->close();

} else {

    $message = "No user was selected.";

    $messageType = "danger";

}


if ($_SERVER["REQUEST_METHOD"] === "POST") {



    $id = $_POST['id'] ?? "";


    
    if (!is_numeric($id)) {

        $message = "Invalid user ID.";

        $messageType = "danger";

    } else {

        $id = (int) $id;

        $sql = $conn->prepare(
            "DELETE FROM usersdt
             WHERE id = ?"
        );

        $sql->bind_param("i", $id);


        if ($sql->execute()) {

            $sql->close();

            header("Location: homepage.php?deleted=1");

            exit();

        } else {

            $message = "Unable to delete user. Please try again.";

            $messageType = "danger";

        }


        $sql->close();

    }

}

?>


<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Delete User | User Management System
    </title>




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


    <style>

        body {

            background-color: #f8f9fa;

            min-height: 100vh;

        }


        .delete-card {

            max-width: 650px;

            margin: auto;

            border: none;

            border-radius: 18px;

        }


        .warning-icon {

            width: 75px;

            height: 75px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: auto;

            border-radius: 50%;

            background-color: #fff3cd;

        }


        .user-details {

            background-color: #f8f9fa;

            border-radius: 12px;

            padding: 20px;

        }


        .detail-label {

            font-weight: 600;

            color: #495057;

        }

    </style>

</head>


<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">


        <a
            class="navbar-brand text-primary fw-bold"
            href="homepage.php"
        >

            <i class="bi bi-speedometer2 me-2"></i>

            User Management System

        </a>


        <div class="ms-auto">


            <a
                href="homepage.php"
                class="btn btn-outline-secondary btn-sm me-2"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Dashboard

            </a>


            <a
                href="logout.php"
                class="btn btn-outline-danger btn-sm"
            >

                <i class="bi bi-box-arrow-right me-1"></i>

                Logout

            </a>


        </div>

    </div>

</nav>

<main class="container py-5">


    <div class="card shadow-sm delete-card">

        <div class="card-body p-4 p-md-5">


          
            <div class="warning-icon mb-4">

                <i
                    class="bi bi-exclamation-triangle-fill text-warning fs-1"
                ></i>

            </div>


           
            <div class="text-center mb-4">

                <h2 class="fw-bold text-danger">

                    Delete User

                </h2>


                <p class="text-muted">

                    Are you sure you want to permanently
                    delete this user?

                </p>

            </div>
            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php echo htmlspecialchars($messageType); ?>"
                    role="alert"
                >

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <?php if ($user !== null): ?>
<div class="user-details mb-4">


                    

                    <div class="row mb-3">

                        <div class="col-4 detail-label">

                            User ID

                        </div>

                        <div class="col-8">

                            <span class="badge text-bg-secondary">

                                <?php
                                echo htmlspecialchars($user['id']);
                                ?>

                            </span>

                        </div>

                    </div>


                   

                    <div class="row mb-3">

                        <div class="col-4 detail-label">

                            Name

                        </div>

                        <div class="col-8">

                            <?php
                            echo htmlspecialchars($user['name']);
                            ?>

                        </div>

                    </div>



                    <div class="row mb-3">

                        <div class="col-4 detail-label">

                            Age

                        </div>

                        <div class="col-8">

                            <?php
                            echo htmlspecialchars($user['age']);
                            ?>

                        </div>

                    </div>


                   

                    <div class="row mb-3">

                        <div class="col-4 detail-label">

                            Email

                        </div>

                        <div class="col-8 text-break">

                            <?php
                            echo htmlspecialchars($user['email']);
                            ?>

                        </div>

                    </div>


                    

                    <div class="row">

                        <div class="col-4 detail-label">

                            Address

                        </div>

                        <div class="col-8 text-break">

                            <?php
                            echo htmlspecialchars($user['address']);
                            ?>

                        </div>

                    </div>


                </div>

                <form
                    action="delete.php?id=<?php echo urlencode($user['id']); ?>"
                    method="POST"
                >


                    <!-- Hidden ID -->

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo htmlspecialchars($user['id']); ?>"
                    >


                   

                    <div class="d-flex gap-2">


                     

                        <a
                            href="homepage.php"
                            class="btn btn-outline-secondary btn-lg flex-fill"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel

                        </a>



                        <button
                            type="submit"
                            class="btn btn-danger btn-lg flex-fill"
                            onclick="return confirm('Are you sure you want to delete this user?');"
                        >

                            <i class="bi bi-trash3 me-1"></i>

                            Delete User

                        </button>


                    </div>


                </form>


            <?php endif; ?>


        </div>

    </div>


</main>




<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwxH9JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
></script>


</body>

</html>