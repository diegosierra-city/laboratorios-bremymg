<script lang="ts">
	//import WebConstruccion from "$lib/components/WebConstruccion.svelte";
	import { onMount } from 'svelte/internal';
	import WebMenuB from '$lib/components/WebMenuB.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';
	import { fade, fly } from 'svelte/transition';
	import { apiKey, cookie_info, cookie_update } from '../../store';

	import type { BlockContent } from '$lib/types/BlockContent';
	import type { Menu } from '$lib/types/Menu';
	import type { WebContent } from '$lib/types/WebContent';
	import type { MenuWeb } from '$lib/types/MenuWeb';
	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	import WebAccess from '$lib/components/WebAccess.svelte';

	import type { Gallery } from '$lib/types/Gallery';
	import WebGalleryB from '$lib/components/WebGalleryB.svelte';
	import WebGalleryA from '$lib/components/WebGalleryA.svelte';
	
	import type { Pedido, Comprador, PedidoProduct } from '$lib/types/Pedido';
	import WebList from '$lib/components/WebList.svelte';
	

	let cont: BlockContent = {
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
	let pag: Menu = {
		id: 0,
		menu_id: 0,
		menu: '',
		type: '',
		link: '',
		head: false,
		foot: false,
		side: false,
		position: 1,
		submenu: false,
		submenus: [],
		metadescription: '',
		metakeywords: ''
	};
	let listCont: Array<WebContent> = [];

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	onMount(async () => {
		await fetch(
			urlAPI +
				'?ref=load-listWebContent&type=Gallery&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					console.log('ok');
					console.log(result);

					listCont = result;
				
			})
			.catch((error) => console.log(error.message));
	});

	onMount(async () => {
		console.log(
			urlAPI + '?ref=page-web&type=Home&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		)
		await fetch(
			urlAPI + '?ref=page-web&type=Home&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					console.log('contenido:');
					console.log(result);
					pag = result[0];
					cont = result[1];
					//console.table(pag.metadescription);
				
			})
			.catch((error) => console.log(error.message));
	});


	let innerWidth: number = 0;
	let innerHeight: number = 0;
	let scrollY: number = 0;
	let online: any ;

	/*
	$: innerWidth
	$: innerHeight
	$: scrollY 
	$: online
	*/

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
	$: console.log('Online: ' + online);

	let listGalleries: Array<Gallery> = [];

const loadGallery = () => {
	console.log('gallery:');
	console.log(
		urlAPI +
			'?ref=load-listGalleryWeb&company_id=' +
			company_id +
			'&tokenWeb=' +
			tokenWeb +
			'&folder=maker_categories'			
	);
	/**/
	fetch(
		urlAPI +
			'?ref=load-listGalleryWeb&company_id=' +
			company_id +
			'&tokenWeb=' +
			tokenWeb +
			'&folder=maker_categories'
	)
		.then((response) => response.json())
		.then((result) => {
			console.log('Nuevas Galleries:::');
			console.log(result);
		
			listGalleries = result;
		})
		.catch((error) => console.log(error.message));
};

onMount(()=>{
loadGallery()
})

let galleryFolder:string = 'maker_products/'


	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';
	import WebIntro from '$lib/components/WebIntro.svelte';
	let listItems: Array<Item> = [];
	let listProducts: Array<Product> = [];

		
let intro = true 

</script>

<svelte:head>
	<title>{company_name}</title>
	<meta name="description" content={pag.metadescription} />
	<meta name="keywords" content={pag.metakeywords} />
	<link rel="stylesheet" href="./css/fontawesome-free-6.4.0-web/css/all.css" />
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />
<!--
	<WebGalleryB {listGalleries} {urlFiles} {galleryFolder}/>
-->

<!-- 	{#if innerWidth>900}
	<WebGalleryA {listGalleries} {urlFiles} {galleryFolder} />
	{/if} -->

{#if intro}
	 <!-- content here -->
		<WebIntro bind:intro/>
{/if}

<WebCarrousel {cont} {urlFiles} {prefixFolder} />
<WebMenuB />

<!-- <WebAccess {listCont} {urlFiles}/> -->

<section class="relative" id="principal">

<div class="h-20 md:h-10"></div>

	

	<div class="w-11/12 md:w-8/12 mx-auto">
		<h2 class="text-primary">{cont.title}</h2>
		<h3>{cont.subtitle}</h3>
{#if cont.text1}
<p class="m-3 p-3 ">{@html cont.text1}</p>
{/if}
		
	</div>

	{#if cont.text2}
	<div class="w-11/12 md:w-8/12 mx-auto">
		<p class="m-3 p-3 ">{@html cont.text2}</p>
</div>
	{/if}

	
	<!-- <WebList {listProducts} {listItems} {urlFiles}/> -->


</section>



<WebFooter />


