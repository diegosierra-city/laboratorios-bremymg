<script lang="ts">
	import type { Gallery } from '$lib/types/Gallery';
	import { fade, fly } from 'svelte/transition';
	export let listGalleries: Array<Gallery> = [];

	export let urlFiles: string = '';

	let showGallery: boolean = false;

	let galleryImg: string = '';
	let galleryTitle: string = '';
	let galleryDescription: string = '';
	let galleryTotal: number = 0;
	let galleryActual: number = 0;

	$: galleryTotal = listGalleries.length - 1;

	const playGallery=(position: number)=> {
		if (position > galleryTotal) {
			position = 0;
		} else if (position < 0) {
			position = galleryTotal;
		}
		console.log('position:' + position);
		let gallery: Gallery = listGalleries[position];
		galleryImg = gallery.image;
		galleryTitle = gallery.title;
		galleryDescription = gallery.description;
		galleryActual = position;
		showGallery = true;
	}
</script>

<section>
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 my-4">
		{#each listGalleries as gallery, i}
			<button
				class="card_home border-2 border-silver mx-auto"
				on:click={() => {
					playGallery(i);
				}}
			>
				<div class="card_img">
					<img src="{urlFiles}/images/maker_gallery/M{gallery.image}" alt="" class="w-full h-auto" />
					<img src="{urlFiles}/images/maker_gallery/{gallery.image}" alt="" class="hidden" />
				</div>
				<div class="card_titleB"><h4>{gallery.title}</h4></div>
			</button>
		{/each}
	</div>
</section>



{#if showGallery == true}
	<!--Gallery System-->

	<!-- transition:fade-->
	<button
		class="bg-black opacity-70 fixed top-0 left-0 right-0 bottom-0 z-50"
		on:click={() => {
			showGallery = !showGallery;
		}}
	/>

	<div
		transition:fade
		class="w-9/12 lg:w-6/12 xl:w-5/12 mx-auto  fixed z-50 rounded-xl border-2 border-white "
		style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
	>
		<img src="{urlFiles}/images/maker_gallery/{galleryImg}" alt="" class="rounded-t-xl w-full h-auto" />

		<div class="bg-primary text-white rounded-b-xl p-3">
			<h3>{galleryTitle}</h3>
			<p>{@html galleryDescription}</p>
		</div>

		<button
			class="absolute shadow-lg cursor-pointer"
			style="top: 50%; left: -60px; transform: translate(0, -50%); "
			on:click={() => {
				playGallery(galleryActual - 1);
			}}
		>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				id="arrow-circle-down"
				viewBox="0 0 24 24"
				width="50"
				height="50"
				fill="white"
				class="opacity-80 hover:opacity-100 "
				><path
					d="M24,12A12,12,0,1,0,12,24,12.013,12.013,0,0,0,24,12ZM8,12a2.993,2.993,0,0,1,.752-1.987c.291-.327.574-.637.777-.84L12.353,6.3a1,1,0,0,1,1.426,1.4L10.95,10.58c-.187.188-.441.468-.7.759a1,1,0,0,0,0,1.323c.258.29.512.57.693.752L13.779,16.3a1,1,0,0,1-1.426,1.4L9.524,14.822c-.2-.2-.48-.507-.769-.833A2.99,2.99,0,0,1,8,12Z"
				/></svg
			>
		</button>

		<button
			class="absolute shadow-lg cursor-pointer"
			style="top: 50%; right: -60px; transform: translate(0, -50%); "
			on:click={() => {
				playGallery(galleryActual + 1);
			}}
		>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				id="arrow-circle-down"
				viewBox="0 0 24 24"
				width="50"
				height="50"
				fill="white"
				class="opacity-80 hover:opacity-100 "
				><path
					d="M0,12A12,12,0,1,0,12,0,12.013,12.013,0,0,0,0,12Zm16,0a2.993,2.993,0,0,1-.752,1.987c-.291.327-.574.637-.777.84L11.647,17.7a1,1,0,1,1-1.426-1.4L13.05,13.42c.187-.188.441-.468.7-.759a1,1,0,0,0,0-1.323c-.258-.29-.512-.57-.693-.752L10.221,7.7a1,1,0,1,1,1.426-1.4l2.829,2.879c.2.2.48.507.769.833A2.99,2.99,0,0,1,16,12Z"
				/></svg
			>
		</button>
	</div>
	<!--End Gallery System-->
{/if}
