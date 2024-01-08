<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

$host = "localhost";
$dbname = "todolist";
$user = "root";
$password = "";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$req = $bdd->query('SELECT * FROM todo ORDER BY created_at DESC');
$taches = $req->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    switch ($action) {
        case 'new':
            
            $title = $_POST['title'];
            $stmt = $bdd->prepare('INSERT INTO todo (title, created_at) VALUES (?, NOW())');
            $stmt->execute([$title]);
            break;

        case 'delete':
         
            $stmt = $bdd->prepare('DELETE FROM todo WHERE id = ?');
            $stmt->execute([$id]);
            break;

        case 'toggle':
         
            $stmt = $bdd->prepare('UPDATE todo SET done = 1 - done WHERE id = ?');
            $stmt->execute([$id]);
            break;
    }
    header('Location:todo.php');
   
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Todo App</a>
</nav>


<div class="container mt-4">
    <form method="post">
        <div class="form-group">
            <label for="title">Nouvelle tâche</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <button type="submit" class="btn btn-primary" name="action" value="new">Ajouter</button>
    </form>
</div>


<div class="container mt-4">
    <ul class="list-group">
        <?php foreach ($taches as $tache): ?>
            <li class="list-group-item <?php echo ($tache['done'] == 1) ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
                <?php echo $tache['title']; ?>
                <form class="float-right" method="post">
                    <input type="hidden" name="id" value="<?php echo $tache['id']; ?>">
                    <button type="submit" class="btn btn-success" name="action" value="toggle">Toggle</button>
                    <button type="submit" class="btn btn-danger" name="action" value="delete">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.css"></script>
    
</body>
</html>