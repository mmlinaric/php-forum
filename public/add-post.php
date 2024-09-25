<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (!isset($_SESSION["user_id"]))
{
    $_SESSION["error"] = "You are not logged in.";
    header("Location: add-post.php");
    die();
}

if (isset($_POST["add-post"]))
{
    $title = htmlspecialchars($_POST["title"]);
    $category = $_POST["category"];
    $text = htmlspecialchars($_POST["text"]);

    if(empty($title) || empty($category) || empty($text))
    {
        $_SESSION["error"] = "You have to enter all inputs.";
        header("Location: add-post.php");
        die();
    }

    // Check title
    if (strlen($title) < 10)
    {
        $_SESSION["error"] = "Title is too short.";
        header("Location: add-post.php");
        die();
    }

    if (strlen($title) > 70)
    {
        $_SESSION["error"] = "Title is too long.";
        header("Location: add-post.php");
        die();
    }

    // Check text
    if (strlen($text) < 10)
    {
        $_SESSION["error"] = "Text is too short.";
        header("Location: add-post.php");
        die();
    }

    if (strlen($text) > 10000)
    {
        $_SESSION["error"] = "Text is too long.";
        header("Location: add-post.php");
        die();
    }

    // Check if category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id");
    $stmt->execute(array(
        ':id' => $category
    ));

    if ($stmt->rowCount() == 0)
    {
        $_SESSION["error"] = "Category does not exist.";
        header("Location: add-post.php");
        die();
    }

    // Add post to the database
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (title, text, category_id, user_id) VALUES (:title, :text, :category_id, :user_id)");
        $stmt->execute(array(
            ':title' => $title,
            ':text' => $text,
            ':category_id' => $category,
            ':user_id' => $_SESSION["user_id"]
        ));
    } catch (PDOException $e) {
        $_SESSION["error"] = "An error occurred while trying to add post.".$e->getMessage();
        header("Location: add-post.php");
        die();
    }

    $_SESSION["info"] = "Post added successfully.";
    header("Location: posts.php?id=".$pdo->lastInsertId());
    die();
}

$stmt = $pdo->prepare("SELECT id, name FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>New post</h2>

<?php if ($stmt->rowCount() > 0) { // If no categories are found, don't allow posting ?>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Title" minlength="10" maxlength="70" required autofocus>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <?php foreach($categories as $category) { ?>
                <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></option>
            <?php } ?>
        </select>

        <label for="text">Text:</label>
        <textarea id="text" name="text" placeholder="Text" rows="15" minlength="10" maxlength="10000" required></textarea>

        <input type="submit" name="add-post" value="Post">
    </form>
<?php } else { ?>
    <p>Posting is not available because there are no categories to post in.</p>
<?php } ?>

<?php include("../tpl/footer.php"); ?>