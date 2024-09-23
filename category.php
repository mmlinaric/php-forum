<?php

require_once("config/db.php");
include("tpl/header.php");

if (!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

?>

<?php

$stmt = $pdo->prepare('SELECT name FROM categories WHERE id = :id');
$stmt->execute(array(
    ':id' => $_GET['id']
));
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($category))
{
    header("Location: index.php");
    die();
}

?>

<h2><?php echo $category['name']; ?></h2>

<?php include("tpl/footer.php"); ?>