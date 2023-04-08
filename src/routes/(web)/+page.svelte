<script lang="ts">
	//import WebConstruccion from "$lib/components/WebConstruccion.svelte";
	import { onMount } from 'svelte/internal';
	import WebMenuB from '$lib/components/WebMenuB.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';
	import { fade, fly } from 'svelte/transition';
	import { apiKey } from '../../store';

	import type { BlockContent } from '$lib/types/BlockContent';
	import type { Menu } from '$lib/types/Menu';
	import type { WebContent } from '$lib/types/WebContent';
	import type { MenuWeb } from '$lib/types/MenuWeb';
	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	import WebAccess from '$lib/components/WebAccess.svelte';


		

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
</script>

<svelte:head>
	<title>{company_name}</title>
	<meta name="description" content={pag.metadescription} />
	<meta name="keywords" content={pag.metakeywords} />
	<link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css" />
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<WebCarrousel {cont} {urlFiles} {prefixFolder} />
<WebMenuB {scrollY} />



<WebAccess {listCont} {urlFiles}/>
<section class="relative" id="principal">

<div class="bg-silver h-10"></div>

	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 container mx-auto my-6 px-4 -mt-6">
		{#each listCont as ct}
			<a class="card_home mx-auto" href="/pagina/{ct.link}">
				<div class="card_img">
					<img src="{urlFiles}/images/maker_pages/M{ct.image1}" alt={ct.menu} />
				</div>
				<div class="card_title">
					<h3 class="bg-secondary">{ct.menu}</h3>
					<!--
						<p class="bg-black opacity-70 p-3 ">
						{ct.text1}
					</p>
					-->
					
				</div>
				<button
					class="btn-green mr-2 flex w-full !mx-auto !rounded-b-lg rounded-t-none relative z-10"
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 mr-1"
						viewBox="0 0 20 20"
						fill="currentColor"
					>
						<path
							fill-rule="evenodd"
							d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
							clip-rule="evenodd"
						/>
					</svg>
					saber más</button
				>
			</a>
		{/each}
	</div>

	<div class="w-11/12 md:w-8/12 mx-auto">
		<h2 class="text-primary">{cont.title}</h2>
		<h3>{cont.subtitle}</h3>
{#if cont.text1}
<p class="m-3 p-3 bg-aliceblue">{@html cont.text1}</p>
{/if}
		
	</div>

	{#if cont.text2}
	<div class="w-11/12 md:w-8/12 mx-auto">
		<p class="m-3 p-3 bg-aliceblue">{@html cont.text2}</p>
</div>
	{/if}

	


</section>

<WebCarrousel {cont} {urlFiles} {prefixFolder} />

<WebFooter />
