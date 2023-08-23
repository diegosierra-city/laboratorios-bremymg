<script lang="ts">
	//import WebConstruccion from "$lib/components/WebConstruccion.svelte";
	import { onMount } from 'svelte/internal';
	//import WebMenuC from '$lib/components/WebMenuC.svelte';

	//import WebFooter from '$lib/components/WebFooter.svelte';
	import { fade, fly } from 'svelte/transition';
	import { apiKey, cookie_info, cookie_update } from '../../store';

	import type { BlockContent } from '$lib/types/BlockContent';
	import type { Menu } from '$lib/types/Menu';
	import type { WebContent } from '$lib/types/WebContent';

	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	import WebAccess from '$lib/components/WebAccess.svelte';

	import type { Gallery } from '$lib/types/Gallery';

	import WebList from '$lib/components/WebList.svelte';
	import WebSlider from '$lib/components/WebSlider.svelte';

	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';

	let listItems: Array<Item> = [];
	let listProducts: Array<Product> = [];

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

	let listSlide: Array<string> = [];
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

	const loadCategories= async()=>{
		console.log('Cat',urlAPI + '?ref=listWeb&f=AccessCategories&company_id=' + company_id + '&tokenWeb=' + tokenWeb)
		await fetch(
			urlAPI + '?ref=listWeb&f=AccessCategories&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				listProducts=result
				console.log('Categories',listProducts)
			})
			.catch((error)=>{
console.log('error',error)
			})
	}

	onMount(()=>{
		loadCategories()
	})

	onMount(async () => {
		console.log(
			urlAPI + '?ref=page-web&type=Home&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		);
		await fetch(
			urlAPI + '?ref=page-web&type=Home&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('Homex', result[1]);

				pag = result[0];
				cont = result[1];
				listProducts = result[2];
				//console.table(pag.metadescription);
				if (cont.image1 !== null) listSlide = [cont.image1];
				if (cont.image2 !== null) listSlide = [...listSlide, cont.image2];
				if (cont.image3 !== null) listSlide = [...listSlide, cont.image3];
				if (cont.image4 !== null) listSlide = [...listSlide, cont.image4];
				console.log('kk',listSlide)
			})
			.catch((error) => console.log(error.message));
	});

	let innerWidth: number = 0;
	let innerHeight: number = 0;
	let scrollY: number = 0;
	let online: any;

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

	onMount(() => {
		loadGallery();
	});

	let galleryFolder: string = 'maker_products/';
	let target = 'categorias'

	
</script>

<svelte:head>
	<title>{company_name}</title>
	<meta name="description" content={pag.metadescription} />
	<meta name="keywords" content={pag.metakeywords} />
	<link rel="stylesheet" href="./css/fontawesome-free-6.4.0-web/css/all.css" />
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<!-- <WebMenuC /> -->

<WebSlider bind:listSlide={listSlide} />



<section class="relative mt-12" id="principal" >
<div class="w-10/12 md:w-6/12 mx-auto flex text-primary">
	<div>
		<img src="/maker-files/images/icon-Home.png" alt="" >
	</div>

	<div class="pl-8">
		<h2>{cont.title}</h2>
		<div>{cont.text1}</div>
	</div>
</div>
</section>

<WebList bind:listProducts={listProducts} {target} />

<!-- <WebFooter /> -->


<style>
 .image-container img {
   transition: opacity 0.3s ease-in-out;
 }
 
</style>