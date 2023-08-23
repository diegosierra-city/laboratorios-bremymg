<script lang="ts">
	import { onMount } from 'svelte';

	export let listSlide: Array<string>;
	let images: NodeListOf<HTMLImageElement>;
	let currentIndex = 0;

	function changeImage() {
		if (images[currentIndex]) images[currentIndex].style.opacity = '0';
		currentIndex = (currentIndex + 1) % images.length;
		if (images[currentIndex]) images[currentIndex].style.opacity = '1';
	}

	let interval: NodeJS.Timeout;

	onMount(() => {
		if (listSlide?.length > 0) {
			// alert(listSlide.length)
			images = document.querySelectorAll('.image-container img');
			interval = setInterval(changeImage, 5000);
		}

		// Cleanup interval on component unmount
		return () => clearInterval(interval);
	});
	let pref = '';
	$: console.log('SS', listSlide);

	$: innerWidth = 0;
	$: innerHeight = 0;
	$: scrollY = 0;

	const movil = (w: number) => {
		if (w > 900) {
			//800
			pref = '';
		} else {
			pref = 'M';
		}
	};

	$: movil(innerWidth);
</script>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<div class="relative image-container w-10/12 md:w-8/12 mx-auto mt-10 ">
	{#if listSlide?.length>0}
		{#each listSlide as imagen, i}
			<img
				class="{i == 0 ? 'relative' : 'absolute'} top-0 left-0 right-0 w-full"
				style="z-index: {i}"
				src={`/maker-files/images/maker_pages/${pref}${imagen}`}
				alt=""
			/>
		{/each}

		<img
			class="absolute top-0 h-full w-auto"
			src="/maker-files/images/logo-LaboratoriosBremymg-400.png"
			alt=""
			style="z-index: -1; left: 50%; margin-left:-25%"
		/>
	{/if}
</div>

<style>
	.image-container img {
		transition: opacity 0.3s ease-in-out;
	}
</style>
