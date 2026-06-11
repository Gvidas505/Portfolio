<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=sources1;charset=utf8mb4',
        'root',
        ''
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbMessage = 'Database connection is working';
    $dbError = false;

} catch (PDOException $e) {

    $dbMessage = 'Database connection failed';
    $dbError = true;

}