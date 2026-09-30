{*{extends file="layouts/base.tpl"}*}

{*{block name="content"}*}
    <header class="category-header">
        <h1>{$category->name}</h1>
        {if $category->description}
            <p class="category-description">{$category->description}</p>
        {/if}
    </header>

    <nav class="sort" aria-label="Сортировка">
        <span>Сортировать:</span>

        <a href="/category/{$category->id}{if $sort != 'date'}?sort=date{/if}"
           {if $sort == 'date'}class="active" aria-current="true"{/if}
        >
            По дате
        </a>

        <a href="/category/{$category->id}{if $sort != 'views'}?sort=views{/if}"
           {if $sort == 'views'}class="active" aria-current="true"{/if}
        >
            По просмотрам
        </a>
    </nav>

    {foreach $articles as $article}
        <article class="article-card">
            <h2>
                <a href="/article/{$article->id}">{$article->name}</a>
            </h2>
            <p>{$article->description}</p>
            <footer>
                <time datetime="{$article->createdAt->format('c')}">
                    {$article->createdAt->format('d.m.Y')}
                </time>
                <span>Просмотров: {$article->viewsCount}</span>
            </footer>
        </article>
        {foreachelse}
        <p>В этой категории пока нет статей.</p>
    {/foreach}

    {if $pages > 1}
        <nav class="pagination" aria-label="Страницы">
            {if $page > 1}
                <a href="/category/{$category->id}?{if $sort}sort={$sort}&amp;{/if}page={$page - 1}" rel="prev">&laquo;</a>
            {/if}

            {for $i=$from to $to}
                {if $i == $page}
                    <span class="current" aria-current="page">{$i}</span>
                {else}
                    <a href="/category/{$category->id}?{if $sort}sort={$sort}&amp;{/if}page={$i}">{$i}</a>
                {/if}
            {/for}

            {if $page < $pages}
                <a href="/category/{$category->id}?{if $sort}sort={$sort}&amp;{/if}page={$page + 1}" rel="next">&raquo;</a>
            {/if}
        </nav>
    {/if}
{*{/block}*}