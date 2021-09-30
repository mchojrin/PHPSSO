<?php

session_start();
if (!array_key_exists('uid', $_SESSION)) {
    $auth0 = require_once '../auth0.php';

    $userInfo = $auth0->getUser();

    if (!$userInfo) {

        header('Location: login.php');
        die;
    }

    $db = require_once '../connect.php';
    $sql = 'SELECT id FROM employees WHERE email = :email';

    if ( ($st = $db->prepare($sql)) === false ) {
        die($db->errorInfo());
    }

    if (($result = $st->execute(['email' => $userInfo['email']])) === false) {
        die($db->errorInfo());
    }

    if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['uid'] = $row['id'];

        header('Location: index.php');
    } else {

        header('Location: login.php');
    }
}