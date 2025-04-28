<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Microblog' ?></title>
    <link rel="icon" href="/public/icons/img_1.png">
    <link rel="stylesheet" href="/public/css/commonStyles.css?v=1.0">
    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="/public/css/<?= $extraCss ?>.css?v=1.0">
    <?php endif; ?>
    <?php if (isset($extraJs) && isset($type)): ?>
        <script src="/public/js/<?= $extraJs ?>.js?v=1.0" type=<?= $type ?> defer></script>
    <?php endif; ?>
</head>
<body>
<?php
if (isset($contentTemplate)) {
    include $contentTemplate;
}
?>
</body>
</html>