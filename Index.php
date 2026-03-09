
<?php
/* =====================================================
   BASE DE DONNÉES MYSQL (À EXÉCUTER DANS PHPMYADMIN)

   CREATE DATABASE crud_db;
   USE crud_db;

   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(100) NOT NULL,
       email VARCHAR(100) NOT NULL
   );
   ===================================================== */

// ================= CONNEXION MYSQL =================
$host = "localhost";
$dbname = "crud_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// ================= CREATE =================
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email']]);
    header("Location: index.php");
}

// ================= DELETE =================
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
}

// ================= UPDATE =================
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['id']]);
    header("Location: index.php");
}

// ================= READ =================
$users = $pdo->query("SELECT * FROM users")->fetchAll();

// ================= EDIT =================
$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editUser = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRUD PHP MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2 class="mb-4 text-center">Application CRUD PHP & MySQL</h2>

<!-- ================= FORMULAIRE ================= -->
<form method="post" class="mb-4">
    <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

    <input type="text" name="name" class="form-control mb-2"
           placeholder="Nom"
           value="<?= $editUser['name'] ?? '' ?>" required>

    <input type="email" name="email" class="form-control mb-2"
           placeholder="Email"
           value="<?= $editUser['email'] ?? '' ?>" required>

    <?php if ($editUser): ?>
        <button name="update" class="btn btn-warning w-100">Modifier</button>
    <?php else: ?>
        <button name="add" class="btn btn-primary w-100">Ajouter</button>
    <?php endif; ?>
</form>

<!-- ================= TABLE ================= -->
<table class="table table-bordered text-center">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= $u['name'] ?></td>
        <td><?= $u['email'] ?></td>
        <td>
            <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
            <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- ================= LIEN GITHUB (HTML) ================= -->
<hr>
<p class="text-center">
    <a href="https://github.com/VOTRE_USERNAME/crud-php-mysql" target="_blank">
        🔗 Lien du projet GitHub
    </a>
</p>

</body>
</html>
