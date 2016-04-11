{t escape=no }Greetings{/t}

{trim}

{if !$card}

{t hostname=$hostname}I would like to know more about products and services you offered on the web site %1.{/t}

{elseif $product}

{t}I would like to know more about the product{/t}
{$card->getName()|strip_tags} - {$product->getName()|strip_tags}

{else}

{t}I would like to know more about the product{/t}
{$card->getName()|strip_tags}

{/if}

{/trim}

{t}Please contact me.{/t}

