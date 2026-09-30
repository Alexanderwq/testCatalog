<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{block name="title"}Мой блог{/block}</title>

    <meta name="description"
          content="{block name="description"}Блог со статьями, рецептами, путешествиями и полезными материалами.{/block}">
</head>

<body>

{include file='components/header.tpl'}

<main class="site-main">
    <div class="container">

        {block name="content"}{/block}

    </div>
</main>

{include file='components/footer.tpl'}

</body>
</html>
