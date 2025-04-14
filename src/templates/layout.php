global$content_template; <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Microblog' ?></title>
    <link rel="stylesheet" href="/public/CSS/common.css?v=1.0">
    <?php if (isset($extra_css)): ?>
        <link rel="stylesheet" href="/public/CSS/<?= $extra_css ?>.css?v=1.0">
    <?php endif; ?>
    <?php if (isset($extra_js)): ?>
        <script src="/public/JS/<?= $extra_js ?>.js?v=1.0" defer></script>
    <?php endif; ?>
</head>
<body>
<?php
if (isset($content_template)) {
    include $content_template;
}
?>
</body>
</html>