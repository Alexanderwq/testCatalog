<div class="article-card">
    <h3>
        <a href="/article/{$item->id}">{$item->name|escape}</a>
    </h3>

    <p class="card-content">{$item->content|escape}</p>

    <p class="card-meta">
        {$item->createdAt->format('d.m.Y')}
        &middot;
        Просмотров: {$item->viewsCount|number_format:0:',':' '}
    </p>
</div>