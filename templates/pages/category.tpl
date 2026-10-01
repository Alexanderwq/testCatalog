{extends file="base.tpl"}

{block name="styles"}
    <link rel="stylesheet" href="/css/category.css">
{/block}

{block name="content"}
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

    <div class="article-list">
        {foreach $articles as $article}
            {include file='components/article_card.tpl' item=$article}
            {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

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
{/block}