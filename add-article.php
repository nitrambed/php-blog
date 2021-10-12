<?php

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit etre une url valide';
$filename = __DIR__ . '/data/articles.json';
$errors = [
    'title' => '',
    'image' => '',
    'category' => '',
    'content' => ''
];

$articles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
    }
    $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_STRING,
        'image' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_STRING,
        'content' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $title = $_POST['title'] ?? '';
    $image = $_POST['imaage'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!title) {
        $errors['title'] = ERROR_REQUIRED;
    } else if (mb_strlen($title) < 5) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    if (!category) {
        $errors['category'] = ERROR_REQUIRED;
    }

    if (!content) {
        $errors['content'] = ERROR_REQUIRED;
    } else if (mb_strlen($content) < 5) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $articles = [...$articles, [
            'title' => $title,
            'image' => $image,
            'category' => $category,
            'content' => $content
        ]];
        file_put_contents($filename, json_encode($articles));
        header('Location : /');
    }
}


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/add-article.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script async src="public/js/index.js"></script>
    <title>Créer un article</title>
</head>

<!-- title -->
<!-- img -->
<!-- category -->
<!-- content -->

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1>Écrire un article</h1>
                <form action="/add-article.php" method='POST'>
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title">
                        <!-- <p class="text-error"></p> -->
                    </div>
                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" name="image" id="image">
                        <!-- <p class="text-error"></p> -->
                    </div>
                    <div class="form-control">
                        <label for="category">Catégorie</label>
                        <select name="category" id="category">
                            <option value="technology">Technologie</option>
                            <option value="nature">Nature</option>
                            <option value="politics">Politique</option>
                        </select>
                        <!-- <p class="text-error"></p> -->
                    </div>
                    <div class="form-control">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content"></textarea>
                        <!-- <p class="text-error"></p> -->
                    </div>
                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">Sauvegarder</button>

                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>

</html>