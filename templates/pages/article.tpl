{extends file="base.tpl"}

{block name="styles"}
    <link rel="stylesheet" href="/css/article.css">
{/block}

{block name="content"}
    <article class="article-page">

        <header class="article-page__header">
            <h1 class="article-page__title">
                {$article->name|escape}
            </h1>

            <div class="article-page__meta">
                <span class="article-page__meta-item">
                    ID статьи: {$article->id}
                </span>

                <span class="article-page__meta-item">
                    Дата создания:
                    {$article->createdAt->format('d.m.Y H:i')}
                    ({$article->createdAt->getTimezone()->getName()})
                </span>

                <span class="article-page__meta-item">
                    Просмотров:
                    {$article->viewsCount|number_format:0:',':' '}
                </span>
            </div>
        </header>

        <section class="article-page__section">
            <h2>Кратко</h2>

            <p>
                {$article->content|escape|nl2br}
            </p>
        </section>

        <section class="article-page__section">
            <h2>Описание</h2>

            <p>
                {$article->description|escape|nl2br}
            </p>
        </section>

    </article>
    {if !empty($recommendedArticles)}
        <section class="article-page__recommended">
            <h2>Рекомендуем почитать</h2>

            <div class="article-page__recommended-list">
                {foreach $recommendedArticles as $recommended}
                    {include file='components/article_card.tpl' item=$recommended}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
