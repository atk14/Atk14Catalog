{assign var=related_cards value=$card->getRelatedCards()}

{if $related_cards}
	<section class="related-cards">
		<h3>{t}Related products{/t}</h3>
		<ul>
			{foreach $related_cards as $c}
				<li>
					{a action="cards/detail" id=$c}
						{$c->getName()}
					{/a}
				</li>
			{/foreach}
		</ul>
	</section>
{/if}
