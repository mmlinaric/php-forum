<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

// Check if category exists
$stmt = $pdo->prepare("SELECT id, name FROM categories WHERE id = :id");
$stmt->execute(array(':id' => $_GET['id']));

if ($stmt->rowCount() == 0)
{
    header("Location: index.php");
    die();
}

$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Find all posts in the specified category
$stmt = $pdo->prepare('
SELECT
    p.id,
    p.title,
    p.created_at,
    u.username
FROM
    posts p
LEFT JOIN
    users u ON u.id = p.user_id
WHERE
    category_id = :id
');
$stmt->execute(array(
    ':id' => $_GET['id']
));
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2><?php echo $category["name"]; ?></h2>

<?php if ($stmt->rowCount() > 0) { ?>

    <div style="overflow-x: auto;"><table class="link-table">
        <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Date posted</th>
        </tr>

        <?php foreach($posts as $post) { ?>
            <tr>
                <td><a href="post.php?id=<?php echo $post["id"]; ?>"><?php echo $post["title"]; ?></a></td>
                <td><?php echo $post["username"]; ?></td>
                <td><?php echo $post["created_at"]; ?></td>
            </tr>
        <?php } ?>
    </table></div>
<?php } else { ?>
    <p>There are no posts found in this category.</p>
<?php } ?>

<?php include("../tpl/footer.php"); ?>