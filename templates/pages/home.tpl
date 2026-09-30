{extends file="base.tpl"}

{block name="title"} Главная — Мой блог {/block}

{block name="description"}
    Последние статьи, рецепты, путешествия и интересные материалы.
{/block}

{block name="content"}

    {foreach $categories as $category}
        <h2>{$category->name}</h2>
        <p>{$category->description}</p>
        {if isset($articlesByCategory[$category->id])}
            {foreach $articlesByCategory[$category->id] as $article}
                <div>
                    <h3>
                        <a href="/article/{$article->id}">
                            {$article->name}
                        </a>
                    </h3>
                    <p>
                        {$article->description}
                    </p>
                </div>
            {/foreach}
        {/if}
    {/foreach}

{/block}