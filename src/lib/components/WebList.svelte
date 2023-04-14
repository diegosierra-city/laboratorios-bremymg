<script lang="ts">
	import type { Item } from '$lib/types/ListItem';
	export let listItems: Array<Item> = [];
	export let urlFiles: string;
console.log('pppx')
$: console.log(listItems)
</script>
<!--
 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 container mx-auto my-6 px-4 -mt-6
-->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:flex container mx-auto my-6 px-4">

	
	{#each listItems as item}

		<a class="card_home mx-2" href="/{item.linkURL}">
			<div class="card_titleB">{item.titulo}</div>
			<div class="card_img">
				<img src="{urlFiles}/images/{item.folder}/M{item.image}" alt={item.titulo} />
			</div>
			<div class="card_text">
						
     <p>
     {@html item.text}
    </p>
    
			</div>
			<button
				class="btn-green mr-2 w-full !rounded-b-lg rounded-t-none relative z-10 items-center"
			>
			<i class="fa fa-check mr-1" /> saber más</button
			>
		</a>

	{/each}

		
	
	
</div>
