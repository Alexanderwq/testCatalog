{extends file="base.tpl"}

{block name="title"} Главная — Мой блог {/block}

{block name="description"}
    Последние статьи, рецепты, путешествия и интересные материалы.
{/block}

{block name="content"}

    {foreach $categories as $category}
        <h2>{$category->name}</h2>

        <p>{$category->description}</p>

        <p>
            <a href="/category/{$category->id}">
                Все статьи
            </a>
        </p>

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
                    <p>
                        Дата создания: {$article->createdAt->format('d.m.Y H:i')}
                    </p>
                </div>
            {/foreach}
        {/if}
    {/foreach}

{/block}