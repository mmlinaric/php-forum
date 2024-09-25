<?php

require_once("config/init.php");
include("tpl/header.php");

$stmt = $pdo->prepare('SELECT id, name FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Discussion categories</h2>

<?php if ($stmt->rowCount() > 0) { ?>
    <table class="link-table">
        <tr>
            <th>Name</th>
        </tr>

        <?php foreach($categories as $category) { ?>
            <tr>
                <td><a href="posts.php?id=<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></a></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>There are no posts categories in this forum.</p>
<?php } ?>

<?php include("tpl/footer.php"); ?>