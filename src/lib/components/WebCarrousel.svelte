<script lang="ts">
	import type { BlockContent } from '$lib/types/BlockContent';
		import { fade, fly } from 'svelte/transition';
		export let cont: BlockContent = {
		id: 0,
		menu_id: 0,
		title: '',
		subtitle: '',
		text1: '',
		text2: '',
		text3: '',
		text4: '',
		image1: '',
		image2: '',
		image3: '',
		image4: '',
		image_text1: '',
		image_text2: '',
		image_text3: '',
		image_text4: '',
		image_link1: '',
		image_link2: '',
		image_link3: '',
		image_link4: '',
		video: '',
		position: 1,
		link: ''
	};
	export let urlFiles: string;
	export let prefixFolder: string;

	let slide: number = 1;
	let slideView: number = 1;

	$: if (cont.image2 != '') {
		slide = 2;
		if (cont.image3 != '') {
			slide++;
		}
		if (cont.image4 != '') {
			slide++;
		}
	}

	function slidePlay(s: number) {
		//console.log('inicio:'+s)
		if (s > 1) {
			setInterval(() => {
				if (slideView == s) {
					slideView = 0;
				} else if (slideView == 0) {
					slideView = s - 1;
				}
				slideView++;
				//console.log('slid*:'+slideView+'+'+s)
			}, 7000);
		}
	}

	function slideControl(p: number) {
		if (p > slide) {
			slideView = 1;
		} else if (p == 0) {
			slideView = slide;
		} else {
			slideView = p;
		}
		//console.log('slide:'+slideView+'('+p+')'+slide)
	}

	$: slidePlay(slide);
</script>

<section class="carrusel relative" >
<div class="bg-primary md:bg-secondary h-20 md:h-2"></div>	
	<!--Image-->
	{#if cont.image1 != '' && cont.image2 == '' && cont.image_link1 == ''}
		<img src="{urlFiles}/images/maker_pages/{cont.image1}" alt="" class="w-full h-auto" />
	{:else if cont.image1 != '' && cont.image2 == '' && cont.image_link1 != ''}
		<a href={cont.image_link1}
			><img src="{urlFiles}/images/maker_pages/{cont.image1}" alt="" class="w-full h-auto" /></a
		>
	{/if}

	<!--Carousel-->

	{#if cont.image1 != '' && cont.image2 != ''}
		<div class="relative w-full overflow-hidden">
			<img
				src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image1}"
				alt=""
				class="w-full h-auto relative z-0"
			/>
			<img src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image2}" alt="" class="hidden" />
			{#if cont.image3 != ''}
				<img src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image3}" alt="" class="hidden" />
			{/if}
			{#if cont.image4 != ''}
				<img
					transition:fade
					src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image4}"
					alt=""
					class="hidden"
				/>
			{/if}

			{#if slideView == 1}
				<a href={cont.image_link1}>
					<img
						transition:fade
						src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image1}"
						alt=""
						class="w-full h-auto absolute top-0 left-0 z-30 "
					/>
				</a>
				{#if cont.image_text1}
				<div
					class="absolute bottom-10 left-0 z-30 w-full text-white drop-shadow-lg shadow-black "
					transition:fly={{ x: 200, duration: 2000 }}
				>
					<h2 class=" w-10/12 mx-auto">{cont.image_text1}</h2>
				</div>
				{/if}

			{/if}

			{#if slideView == 2}
				<a href={cont.image_link2}>
					<img
						transition:fade
						src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image2}"
						alt=""
						class="w-full h-auto absolute top-0 left-0 z-20"
					/></a
				>
				{#if cont.image_text2}
				<div
					class="absolute bottom-10 left-0 z-20 w-full text-white drop-shadow-lg shadow-black "
					transition:fly={{ x: 200, duration: 2000 }}
				>
					<h2 class=" w-10/12 mx-auto">{cont.image_text2}</h2>
				</div>
{/if}
				
			{/if}
			{#if cont.image3 != '' && slideView == 3}
				<a href={cont.image_link3}>
					<img
						transition:fade
						src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image3}"
						alt=""
						class="w-full h-auto absolute top-0 left-0 z-10"
					/></a
				>
				{#if cont.image_text3}
				<div
					class="absolute bottom-10 left-0 z-10 w-full text-white drop-shadow-lg shadow-black "
					transition:fly={{ x: 200, duration: 2000 }}
				>
					<h2 class=" w-10/12 mx-auto">{cont.image_text3}</h2>
				</div>
{/if}
				
			{/if}
			{#if cont.image4 != '' && slideView == 4}
				<a href={cont.image_link4}>
					<img
						transition:fade
						src="{urlFiles}/images/maker_pages/{prefixFolder}{cont.image4}"
						alt=""
						class="w-full h-auto absolute top-0 left-0 z-0"
					/></a
				>

				{#if cont.image_text4}
				<div
					class="absolute bottom-10 left-0 z-0 w-full text-white drop-shadow-lg shadow-black "
					transition:fly={{ x: 200, duration: 2000 }}
				>
					<h2 class=" w-10/12 mx-auto">{cont.image_text4}</h2>
				</div>
{/if}
				
			{/if}

			<button
				class="absolute h-full z-40 left-2 bottom-2 rounded-lg  p-1  cursor-pointer hover:text-white"
				on:click={() => {
					slideControl(slideView - 1);
				}}
			>
				
				<i class="fa fa-arrow-circle-left fa-2x text-white opacity-70 hover:opacity-100"></i>
			</button>

			<button
				class="absolute h-full z-40 right-2 bottom-2 rounded-lg p-1 cursor-pointer hover:text-white"
				on:click={() => {
					slideControl(slideView + 1);
				}}
			>
			<i class="fa fa-arrow-circle-right fa-2x text-white opacity-70 hover:opacity-100"></i>
			</button>
		</div>
	{/if}

	<!--video-->

	{#if cont.video}
		<iframe
			style="width:100%; height:60vw"
			src="https://www.youtube.com/embed/{cont.video}"
			title="YouTube video player"
			frameborder="0"
			allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
			allowfullscreen
		/>
		+++{cont.video}--
	{/if}

	
</section>
