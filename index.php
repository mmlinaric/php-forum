<?php require_once("config/db.php"); include("tpl/header.php"); ?>

<?php

$stmt = $pdo->prepare('SELECT * FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<ul>
    <?php foreach($categories as $category) { ?>
        <li><?php echo $category["name"]; ?></li>
    <?php } ?>
<ul>

<?php include("tpl/footer.php"); ?>