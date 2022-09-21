<?php
require_once('db_connection.php');
require_once('models/user.php');


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    session_start();
    $id = $_SESSION['user_id'];
    $result = User::deleteUser($conn, $id);
    if ($result) {
        echo "Success";
    } else {
        echo "Greška: " . $result;
    };
}

if (isset($_POST['username']) || isset($_POST['email'])) {
    session_start();
    $username = sanitizeInputData($_POST['username']);
    $email = sanitizeInputData($_POST['email']);
    $user = new User();
    $user->id = $_SESSION['user_id'];
    $result = $user->updateUserData($conn, $username, $email);
    if (empty($result)) {
        if (!empty($username)) $_SESSION['username'] = $username;
        if (!empty($email)) $_SESSION['email'] = $email;
        header("location: index.php?message='Uspešno promenjeni podaci'");
    } else {
        header("location: index.php?message='$result'");
    };
    exit();
}

if (isset($_POST['old-password']) && isset($_POST['new-password'])) {
    $oldPassword = sanitizeInputData($_POST['old-password']);
    $newPassword = sanitizeInputData($_POST['new-password']);


    session_start();
    $userId = $_SESSION['user_id'];
    $result = User::changePassword($conn, $userId, $oldPassword, $newPassword);
    if (empty($result)) {
        header("location: index.php?message='Uspešno promenjena šifra'");
    } else {
        header("location: index.php?message='$result'");
    };
    exit();
}

function sanitizeInputData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
