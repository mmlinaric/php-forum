<?php

require_once("config/init.php");
include("tpl/header.php");

if (!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

$stmt = $pdo->prepare("SELECT id, title, text FROM posts WHERE id = :id");
$stmt->execute(array(':id' => $_GET['id']));
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($post))
{
    header("Location: index.php");
    die();
}

?>

<ul>
    <h2><?php echo $post["title"]; ?></h2>
    <p><?php echo $post["text"]; ?></p>
</ul>

<?php include("tpl/footer.php"); ?>