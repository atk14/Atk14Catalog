{assign var=accessories value=$card->getAccessories()}

{if $accessories}
	<section class="accessories">
		<h3>{t}Accessories{/t}</h3>
		<ul>
			{foreach $accessories as $c}
				<li>
					{a action="cards/detail" id=$c}
						{$c->getName()}
					{/a}
				</li>
			{/foreach}
		</ul>
	</section>
{/if}
