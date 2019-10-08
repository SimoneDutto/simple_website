<?php
    session_start();
    require_once 'functions.php';
    destroySession();
    header("Location: ./index.php");
?>