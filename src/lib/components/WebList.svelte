<script lang="ts">
	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';
	import { linkMaker } from '$lib/components/LinkMaker';
	import { numberFormat } from '$lib/components/NumberFormat';

	export let listItems: Array<Item> = [];
	export let listProducts: Array<Product> = [];
	export let urlFiles: string;
	console.log('pppx');
	$: console.log(listItems);
</script>

{#if listItems}

<div class="grid grid-cols-1 sm:grid-cols-2 lg:flex container mx-auto my-6 px-4">
	{#each listItems as item}
	
		<a class="card_home mx-2" href="/{item.linkURL}">
			<h4 class="card_titleB">{item.titulo}</h4>
			<div class="card_img">
				<img src="{urlFiles}/images/{item.folder}/M{item.image}" alt={item.titulo} />
			</div>
			<div class="card_text">
				<p>
					{@html item.text}
				</p>
			</div>

			<button class="btn-green mr-2 w-full !rounded-b-lg rounded-t-none relative z-10 items-center">
				<i class="fa fa-check mr-1" /> saber más</button
			>
		</a>
	{/each}
	</div>

{/if}


{#if listProducts}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 container mx-auto my-6 px-4">
{#each listProducts as product}
<a class="card_home mx-2 relative" href="/producto/{linkMaker(product.product)}">
	
	<div class="card_img">
		<img src="{urlFiles}/images/maker_products/M{product.image1}" alt={product.product} />
	</div>
	<div class="reative mb-12 z-40 ">
	<!-- card_text -->
		<h3 class="card_titleC ">{product.product}</h3>
		<p class="text-darkgray px-2">
			{@html product.description}
			</p>
		
		
	</div>
	
<div class="grid grid-cols-2 px-2 my-4 absolute -bottom-4 w-full ">
		<h3 class="text-center rounded-l-lg rounded-r-none p-0 h-9 w-full bg-white">$ {numberFormat(Number(product.price))}</h3>
<button class="btn-green rounded-r-lg rounded-l-none items-center">
		ver este producto</button
	>
		</div>
	
</a>
{/each}
</div>

{/if}
	

