{extends file="base.tpl"}

{block name="content"}

<article>
    <h1>{$article->name|escape}</h1>

    <p class="meta">
        ID статьи: {$article->id}<br>
        Дата создания: {$article->createdAt->format('d.m.Y H:i')}
        ({$article->createdAt->getTimezone()->getName()})<br>
        Просмотров: {$article->viewsCount|number_format:0:',':' '}
    </p>

    <section class="content">
        <h2>Кратко</h2>
        <p>{$article->content|escape|nl2br}</p>
    </section>

    <section class="description">
        <h2>Описание</h2>
        <p>{$article->description|escape|nl2br}</p>
    </section>
</article>

{if !empty($recommendedArticles)}
    <section class="recommended">
        <h2>Рекомендуем почитать</h2>

        <div class="recommended-list">
            {foreach $recommendedArticles as $recommended}
                {include file='components/article_card.tpl' item=$recommended}
            {/foreach}
        </div>
    </section>
{/if}

{/block}
