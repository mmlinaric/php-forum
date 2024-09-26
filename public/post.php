<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (empty($_GET['id']))
{
    header("Location: index.php");
    die();
}

// Find post
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
    $_SESSION["error"] = "Post not found.";
    header("Location: index.php");
    die();
}

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Add a reply
if (isset($_POST["add-reply"]))
{
    if (!isset($_SESSION["user_id"]))
    {
        $_SESSION["error"] = "You must be logged in to post replies.";
        header("Location: post.php?id=".$_GET["id"]);
        die();
    }

    $text = htmlspecialchars($_POST["text"]);

    if (empty($text))
    {
        $_SESSION["error"] = "Reply cannot be empty.";
        header("Location: post.php?id=".$_GET['id']);
        die();
    }

    if (strlen($text) < 10)
    {
        $_SESSION["error"] = "Reply must be at least 10 characters long.";
        header("Location: post.php?id=".$_GET['id']);
        die();
    }

    if (strlen($text) > 300)
    {
        $_SESSION["error"] = "Reply cannot be longer than 300 characters.";
        header("Location: post.php?id=".$_GET['id']);
        die();
    }

    // Add reply to the database
    try {
        $stmt = $pdo->prepare("INSERT INTO replies (user_id, post_id, text) VALUES (:user_id, :post_id, :text)");
        $stmt->execute(array(
            ":user_id" => $_SESSION["user_id"],
            ":post_id" => $_GET["id"],
            ":text" => $text
        ));
    } catch (PDOException $e) {
        $_SESSION["error"] = "An error occurred while adding a reply.";
        header("Location: post.php?id=".$_GET['id']);
        die();
    }
}

// Load replies
$stmt = $pdo->prepare('
SELECT
    r.id,
    r.text,
    r.created_at,
    u.username
FROM
    replies r
LEFT JOIN
    users u ON u.id = r.user_id
WHERE
    r.post_id = :id
ORDER BY
    r.created_at ASC
');
$stmt->execute(array(':id' => $_GET['id']));

$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2><?php echo htmlspecialchars($post["title"]); ?></h2>
<p>Author: <b><?php echo $post["username"]; ?></b></p>
<fieldset class="post-text">
    <legend>Text:</legend>
    <p><?php echo nl2br(htmlspecialchars($post["text"])); ?></p>
</fieldset>

<?php if (isset($_SESSION["user_id"])) { ?>
<h3>Add a reply</h3>
<form method="POST">
    <label for="text">
    <textarea id="text" name="text" class="no-resize" placeholder="Enter your reply..." rows="5" minlength="10" maxlength="300" required></textarea>

    <input type="submit" name="add-reply" value="Reply">
</form>
<?php } ?>

<h2>Replies:</h2>
<?php if ($stmt->rowCount() > 0) { ?>
    <?php foreach($replies as $reply) { ?>
    <fieldset>
        <legend>By <b><?php echo $reply["username"]; ?></b> on <?php echo $reply["created_at"]; ?>:</legend>
        <p><?php echo nl2br(htmlspecialchars($reply["text"])); ?></p>
    </fieldset>
    <?php } ?>
<?php } else { ?>
    <p>There are no replies.</p>
<?php } ?>

<?php include("../tpl/footer.php"); ?>