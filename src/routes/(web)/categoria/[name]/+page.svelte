<script lang="ts">
	import { page } from '$app/stores';
	import { onMount } from 'svelte';

	import { apiKey, cookie_info, cookie_update } from '../../../../store';
	import { fade, fly } from 'svelte/transition';
	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	import WebMenuB from '$lib/components/WebMenuB.svelte';
	import WebGalleryB from '$lib/components/WebGalleryB.svelte';
	import WebGalleryA from '$lib/components/WebGalleryA.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';
	import WebList from '$lib/components/WebList.svelte';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	let listItems: Array<Item> = [];
	let listProducts: Array<Product> = [];
	let imageTop: string = '';
	let titulo: string;
	let subtitulo: string;

	const loadList = async (name: string) => {
		console.log('categoria:' + name);
		console.log(
			urlAPI +
				'?ref=load-list-Web&folder=maker_categories&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		);
		await fetch(
			urlAPI +
				'?ref=load-list-Web&folder=maker_categories&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				listProducts = result[0];
				console.log('prodt');
				imageTop = result[1];
				titulo = result[2];
				subtitulo = result[3];
				console.log(listProducts);
			})
			.catch((error) => console.log(error.message));
	};

	onMount(() => {
		loadList($page.params.name);
	});

	$: loadList($page.params.name);
	//console.table($page.params)

	$: innerWidth = 0;
	$: innerHeight = 0;
	$: scrollY = 0;

	let prefixFolder: string = '';

	$: console.log('Ancho: ' + innerWidth);
	const movil = (w: number) => {
		if (w > 900) {
			//800
			prefixFolder = '';
		} else {
			prefixFolder = 'M';
		}
	};

	$: movil(innerWidth);

		
</script>

<svelte:head>
	<title>{$page.params.name}</title>
	<link rel="stylesheet" href="../../css/fontawesome-free-6.4.0-web/css/all.css" />
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<div class="relative overflow-hidden" style="height: 16vw">
	{#if imageTop}
		 <!-- content here -->
			<img src="{urlFiles}/images/maker_products/{prefixFolder}{imageTop}" alt="" class="w-full" />
	{/if}
	
	<!-- <div
		class="absolute bottom-6 left-0 w-full MrDafoe lowercase text-center text-white px-4"
		style="font-size: 12vw; text-shadow: 2px 2px 3px #000000;"
	>
	
		{titulo}
		<div style="font-size: 3vw;" class="-mt-24">{subtitulo}</div>
	</div> -->
</div>

<WebMenuB />

<section>
	<div class="w-11/12 md:w-10/12 mx-auto">
		<WebList bind:listItems {listProducts} {urlFiles} />
	</div>
</section>

<WebFooter />

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
