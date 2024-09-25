<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

$stmt = $pdo->prepare('
SELECT
    p.id,
    p.title,
    p.text,
    p.created_at,
    u.username
FROM
    posts p
LEFT JOIN
    users u ON u.id = p.user_id
WHERE
    p.id = :id
');
$stmt->execute(array(':id' => $_GET['id']));

if ($stmt->rowCount() == 0)
{
    header("Location: index.php");
    die();
}

$post = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<h2><?php echo htmlspecialchars($post["title"]); ?></h2>
<p>Author: <b><?php echo $post["username"]; ?></b></p>
<fieldset>
    <legend>Text:</legend>
    <?php echo htmlspecialchars($post["text"]); ?>
</fieldset>

<?php include("../tpl/footer.php"); ?>