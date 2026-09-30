{foreach $categories as $category}

    <h2>{$category->name}</h2>

    <p>{$category->description}</p>

    {foreach $articlesByCategory[$category->id] as $article}

        <div>
            <h3>{$article->name}</h3>
            <p>{$article->description}</p>
        </div>

    {/foreach}

{/foreach}