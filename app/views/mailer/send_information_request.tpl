{t}Name{/t}: {!$name}
{t}Email{/t}: {!$email}
{t}Signing up for newsletter{/t}: {$sign_up_for_newsletter|display_bool}
{t}Phone{/t}: {!$phone|default:"-"}
{t}URL{/t}: {$request->getUrl()}
{t}Product{/t}: {if $product}{$product->getName()} ({$product->getCatalogId()}){elseif $card}{$card->getName()}{else}-{/if}

{t}IP address{/t}: {!$request->getRemoteAddr()}
{t}Logged user:{/t} {!$logged_user|default:"-"}

{!$body}

