<script lang="ts">
	import { goto } from '$app/navigation';
	import type { Gallery } from '$lib/types/Gallery';

	export let listGalleries: Array<Gallery> = [];
	export let urlFiles: string = '';
	export let galleryFolder: string | any | null;

	let folder: string = 'maker_gallery/';
	if (galleryFolder && galleryFolder != '') {
		folder = galleryFolder;
	}

</script>

<section class="gallery">
	{#each listGalleries as gallery, i}
		<!-- svelte-ignore a11y-click-events-have-key-events -->
		<div class="gallery-item relative cursor-pointer" style="background: url({urlFiles}/images/{folder}{gallery.image}); background-size: cover; background-position: center center" on:click={()=>{
      goto(gallery.linkURL)
    }}>
		<div class="gallery-title absolute lowercase text-white bg-black bg-opacity-60 w-full left-0 pl-3 mt-2">{gallery.title}</div>
		<div class="gallery-button absolute bottom-4 w-full" >
			<button class="btn-green mx-auto border">Ver Lista de Productos</button>
		</div>
		</div>
	{/each}
</section>

<!--
<section class="gallery">
	{#each listGalleries as gallery, i}
		
			<img src="{urlFiles}/images/{folder}{gallery.image}" alt=""  />
			
	{/each}
</section>  
-->
