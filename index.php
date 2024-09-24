<?php

require_once("config/init.php");
include("tpl/header.php");

$stmt = $pdo->prepare('SELECT id, name FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<ul>
    <?php foreach($categories as $category) { ?>
        <li><a href="posts.php?id=<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></a></li>
    <?php } ?>
</ul>

<?php include("tpl/footer.php"); ?>