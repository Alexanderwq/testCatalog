<article class="article-card">
    <img class="article-card__image"
         src="/images/{$item->image|escape}"
         alt="image"
         width="300" height="150"
         loading="lazy" decoding="async">

    <div class="article-card__body">
        <h3 class="article-card__title">
            <a href="/article/{$item->id}">{$item->name|escape}</a>
        </h3>

        <p class="article-card__text">{$item->description|escape}</p>
        <p class="article-card__text">Просмотров: {$item->viewsCount|escape}</p>

        <time class="article-card__date" datetime="{$item->createdAt->format('c')}">
            {$item->createdAt->format('d.m.Y H:i')}
        </time>
    </div>
</article>