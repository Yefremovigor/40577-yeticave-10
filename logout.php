<?php
session_start();

// Разлогинем пользователя.
$_SESSION = [];

// Вернем его на главную.
header("Location: /index.php");
exit();
