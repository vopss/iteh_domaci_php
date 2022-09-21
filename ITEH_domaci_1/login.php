<?php
require_once('models/user.php');
require_once('db_connection.php');
$login_message = NULL;
$register_message = NULL;
if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitizeInputData($_POST['username']);
    $password = sanitizeInputData($_POST['password']);

    $user = new User($username, $password);

    if ($user->login($conn)) {
        //successful login
        session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['email'] = $user->email;
        header('Location: index.php');
        exit();
    } else {
        $login_message = "Pogrešno korisničko ime ili šifra";
    }
} elseif (isset($_POST['register']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitizeInputData($_POST['username']);
    $password = sanitizeInputData($_POST['password']);
    $confirmPassword = sanitizeInputData($_POST['confirm-password']);
    $email = sanitizeInputData($_POST['email']);

    if (empty($username) || empty($password) || empty($email) || empty($confirmPassword)) {
        $register_message = "Morate popuniti sva polja";
    } elseif ($confirmPassword != $password) {
        $register_message = "Šifre se ne poklapaju";
    } else {
        $user = new User($username, $password, $email);
        $result = $user->register($conn);
        if ($result != true) {
            //failed registration
            $register_message = "Greška prilikom registracije";
        } else {
            //successful registration
            session_start();
            $user->login($conn);
            $_SESSION['username'] = $user->username;
            $_SESSION['user_id'] = $user->id;
            $_SESSION['email'] = $user->email;
            header('Location: index.php');
        }
    }
}

function sanitizeInputData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="styles/login.css">
    <title>Login | Automobili.com</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-login">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="#" class="active" id="login-form-link">Login</a>
                            </div>
                            <div class="col-xs-6">
                                <a href="#" id="register-form-link">Register</a>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="login-form" action="" method="post" role="form" style="display: block;">
                                    <div class="form-group">
                                        <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="login" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form id="register-form" action="" method="post" role="form" style="display: none;">
                                    <div class="form-group">
                                        <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="register" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!empty($login_message)) {
            echo '<div class="alert alert-danger error-login">
            ' . $login_message . '
            </div>';
        }
        ?>

        <?php
        if (!empty($register_message)) {
            echo '<div class="alert alert-danger error-login">
            ' . $register_message . '
            </div>';
        }
        ?>

    </div>

    <script src="scripts/login.js"></script>
</body>

</html>