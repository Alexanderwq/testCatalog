{extends file="base.tpl"}

{block name="title"} Главная — Мой блог {/block}

{block name="description"}
    Последние статьи, рецепты, путешествия и интересные материалы.
{/block}

{block name="styles"}
    <link rel="stylesheet" href="/css/home.css">
{/block}

{block name="content"}

    {foreach $categories as $category}
        <section class="category">
            <div class="category__head">
                <h2 class="category__title">{$category->name|escape}</h2>
                <a class="category__link" href="/category/{$category->id}">Все статьи</a>
            </div>

            <p class="category__description">{$category->description|escape}</p>

            {if !empty($articlesByCategory[$category->id])}
                <div class="article-list">
                    {foreach $articlesByCategory[$category->id] as $article}
                        {include file='components/article_card.tpl' item=$article}
                    {/foreach}
                </div>
            {/if}
        </section>
    {/foreach}

{/block}