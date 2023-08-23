<script lang="ts">
	import { page } from '$app/stores';
	import { onMount } from 'svelte';

	import { apiKey, cookie_info, cookie_update } from '../../../../store';
	import { fade, fly } from 'svelte/transition';
	import type { WebContent } from '$lib/types/WebContent';
	import type { BlockContent } from '$lib/types/BlockContent';
	import type { Gallery } from '$lib/types/Gallery';
	import type { Form } from '$lib/types/Form';

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	
	import WebGalleryA from '$lib/components/WebGalleryA.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';
	import WebForm from '$lib/components/WebForm.svelte';
	import WebList from '$lib/components/WebList.svelte';

	let m_show: boolean = false;
	let message: Message;
	let page_id: number = 0;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	let ContBase: WebContent = {
		id: 0,
		menu: '',
		type: '',
		metadescription: '',
		metakeywords: '',
		content_id: 0,
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
		position: 0,
		link: ''
	};

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

	const loadContent = async (name: string) => {
		//alert('x')
		console.log('contenido:' + name);
		console.log(
			urlAPI +
				'?ref=load-listWebContent&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		);
		await fetch(
			urlAPI +
				'?ref=load-listWebContent&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				ContBase = result[0];
				console.log('contenido::x');
				console.log(result);
				cont = {
					id: ContBase.id,
					menu_id: 0,
					title: ContBase.title,
					subtitle: ContBase.subtitle,
					text1: ContBase.text1,
					text2: ContBase.text2,
					text3: ContBase.text3,
					text4: ContBase.text4,
					image1: ContBase.image1,
					image2: ContBase.image2,
					image3: ContBase.image3,
					image4: ContBase.image4,
					image_text1: ContBase.image_text1,
					image_text2: ContBase.image_text2,
					image_text3: ContBase.image_text3,
					image_text4: ContBase.image_text4,
					image_link1: ContBase.image_link1,
					image_link2: ContBase.image_link2,
					image_link3: ContBase.image_link3,
					image_link4: ContBase.image_link4,
					video: ContBase.video,
					position: ContBase.position,
					link: ContBase.link
				};
				page_id = ContBase.id;
				if (result[0].type == 'Gallery') {
					loadGallery(result[0].content_id);
				}
			})
			.catch((error) => console.log(error.message));
	};

	$: loadContent($page.params.name);
	onMount(()=>{
		loadContent($page.params.name)		
	})
	//console.table($page.params)
	let listGalleries: Array<Gallery> = [];

	const loadGallery = (id: number) => {
		console.log('gallery:' + id);

		console.log(
			urlAPI +
				'?ref=load-listGalleryWeb&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +
				'&id=' +
				id
		);
		/**/
		fetch(
			urlAPI +
				'?ref=load-listGalleryWeb&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +
				'&id=' +
				id
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('Nuevas Galleries:::');
				console.log(result);
			
				listGalleries = result;
			})
			.catch((error) => console.log(error.message));
	};

	let listProducts: Array<any>

	const loadProducts = async() => {
		
		console.log(
			urlAPI +
				'?ref=load-listCategorias&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		);
		/**/
		await fetch(
			urlAPI +
				'?ref=load-listCategorias&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('lp',result);
			
				listProducts = result;
			})
			.catch((error) => console.log(error.message));
	}

	const loadMedicos = async() => {
		//alert('p')
		console.log('zz',
			urlAPI +
				'?ref=load-list&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +'&folder=maker_products&campo=category_id&campoV=26'
		);
		/**/
		await fetch(
			urlAPI +
				'?ref=load-list&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +'&folder=maker_products&campo=category_id&campoV=26'
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('lyy',result);
			
				listProducts = result;
			})
			.catch((error) => console.log(error.message));
	}

	$: innerWidth = 0;
	$: innerHeight = 0;
	$: scrollY = 0;
	let prefixFolder: string = '';

	let input: any;

	//$: console.log('Ancho: '+innerWidth)
	const movil = (w: number) => {
		if (w > 900) {
			//800
			prefixFolder = '';
		} else {
			prefixFolder = 'M';
		}
	};

	$: movil(innerWidth);
	let galleryFolder: string 

	if($page.params.name=='Lineas-de-Producto'){
		onMount(()=>{
			loadProducts()
		})
	}

if($page.params.name=='Portal-Medicos'){
		onMount(()=>{
			loadMedicos()
		})
	}
</script>

<svelte:head>
	<title>{ContBase.menu}</title>
	<meta name="description" content={ContBase.metadescription} />
	<meta name="keywords" content={ContBase.metakeywords} />
<!-- <link rel="stylesheet" href="../../css/fontawesome-free-6.4.0-web/css/all.css" />  -->
</svelte:head>
<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<section>
	<div class="w-10/12 md:w-8/12 mx-auto mt-10">
		<img src="/maker-files/images/maker_pages/{ContBase?.image1}" alt="">
	</div>
</section>


<section>
	<div class="w-11/12 md:w-8/12 mx-auto">
		{#if ContBase.title != null}
			<!-- <h1 class="text-primary">{ContBase.title}</h1> -->
		{:else if ContBase.menu != null}
			<!-- <h1 class="text-primary">{ContBase.menu}</h1> -->
		{/if}
{#if ContBase.text1 != null}
		<p class="pb-4">{@html ContBase.text1}</p>
		{/if}
		{#if ContBase.text2 != null}
			<p class="pb-4">{@html ContBase.text2}</p>
		{/if}
		{#if ContBase.text3 != null}
			<p class="pb-4">{@html ContBase.text3}</p>
		{/if}
		{#if ContBase.text4 != null}
			<p class="pb-4">{@html ContBase.text4}</p>
		{/if}

		{#if $page.params.name=='Lineas-de-Producto' && listProducts?.length>0}
			 <!-- content here -->
				{#each listProducts as categoria}
					<h2 class="text-primary">{categoria.category}</h2>
				<WebList bind:listProducts={categoria.products} target={`product`} />
				{/each}
		{/if}

		{#if $page.params.name=='Portal-Medicos' && listProducts?.length>0}
			 <!-- content here -->
				
				<WebList bind:listProducts={listProducts} target={`product`} />
				
		{/if}

		{#if ContBase.type == 'Gallery'}
			<!--
			<WebGalleryB {listGalleries} {urlFiles}/>
		-->
			<WebGalleryA {listGalleries} {urlFiles} {galleryFolder}/>
		{/if}

		{#if ContBase.type == 'Form'}
			<WebForm bind:page_id bind:ContBase />
		{/if}
	</div>
</section>



{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}