<?php

require_once("config/db.php");
include("tpl/header.php");

if (!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

$stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id");
$stmt->execute(array(':id' => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($row))
{
    header("Location: index.php");
    die();
}

$stmt = $pdo->prepare('SELECT id, title FROM posts WHERE category_id = :id');
$stmt->execute(array(
    ':id' => $_GET['id']
));
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<ul>
    <?php if (count($posts) > 0) { ?>
        <?php foreach($posts as $post) { ?>
            <li><a href="post.php?id=<?php echo $post["id"]; ?>"><?php echo $post["title"]; ?></a></li>
        <?php } ?>
    <?php } else { ?>
        <p>There are no posts found in this category.</p>
    <?php } ?> 
</ul>

<?php include("tpl/footer.php"); ?>