<?php

session_start();
if (isset($_GET)) {
    session_unset();
    session_destroy();
    header('Location: login.php');
}
