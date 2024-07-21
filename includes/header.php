<?php
session_start();
define("APPURL", "http://localhost:8888/forum");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome To Tola</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?php echo APPURL; ?>/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.21.0/css/uikit.almost-flat.min.css">

    <style>
        body {
            padding-top: 0px;
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            margin: 10px;
        }

        .boutonne:hover {
            background-color: #dee2e6;
        }

        .category-label {
            display: inline-block;
            width: 150px;
            /* Adjust the size as needed */
            height: 150px;
            /* Adjust the size as needed */
            background-size: cover;
            background-position: center;
            color: white;
            font-weight: bold;
            text-align: center;
            line-height: 150px;
            /* Same as height to center the text */
            margin: 10px;
            border: 1px solid transparent;
            /* to keep the outline */
        }

        .category-label:hover,
        .category-label:focus {
            border-color: #007bff;

        }
        .image {
            width: 100%;
            height: 100%;

        }

        .modal-content {
            width: 125%;
        }

        .category-label {
            width: 185px;
        }

    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-info" ">
<div class="container-fluid">
    <a class="navbar-brand" href="<?php echo APPURL; ?>">Tola</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo APPURL; ?>">Home</a>
            </li>
            <?php if(isset($_SESSION['username'])) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APPURL; ?>/topics/create.php">Create Topic</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="margin-right: 80px" >
                        <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $_SESSION['username']?>">Public Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/users/edit-user.php?id=<?php echo $_SESSION['user_id']?>">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APPURL; ?>/auth/register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APPURL; ?>/auth/login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
        </ul>
    </div>
</div>
</nav>
