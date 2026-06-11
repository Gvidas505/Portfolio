<?php
require_once 'includes/DatabaseConnection.php';

// Admin details
$name = 'Admin';
$email = 'admin@gmail.com';
$password = 'admin12345';

// Hash the password properly
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin already exists
    $check = $pdo->prepare("SELECT * FROM user WHERE Email = :email");
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        echo "⚠️ Admin already exists.";
    } else {
        // Insert admin
        $stmt = $pdo->prepare("
            INSERT INTO user (Name, Email, PasswordHash)
            VALUES (:name, :email, :password)
        ");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hash
        ]);

        echo "✅ Admin created successfully!";
    }

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}