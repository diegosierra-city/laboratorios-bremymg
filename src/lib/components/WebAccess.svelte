<script lang="ts">
	import type { WebContent } from '$lib/types/WebContent';
	export let listCont: Array<WebContent>;
	export let urlFiles: string;
</script>
<!--
 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 container mx-auto my-6 px-4 -mt-6
-->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:flex container mx-auto my-6 px-4 -mt-6">
	{#each listCont as ct}
		<a class="card_home mx-2" href="./pagina/{ct.link}">
			<div class="card_img">
				<img src="{urlFiles}/images/pages/M{ct.image1}" alt={ct.menu} />
			</div>
			<div class="card_title">
				<h4 class="bg-secondary shadow-lg">{ct.menu}</h4>
				<!--
     <p class="bg-black opacity-70 p-3 ">
     {ct.text1}
    </p>
    -->
			</div>
			<button
				class="btn-green mr-2 flex w-full !mx-auto !rounded-b-lg rounded-t-none relative z-10"
			>
				saber más</button
			>
		</a>
	{/each}
</div>
