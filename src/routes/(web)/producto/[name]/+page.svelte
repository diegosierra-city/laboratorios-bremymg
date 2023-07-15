<script lang="ts">
	import { page } from '$app/stores';
	import { onMount } from 'svelte';

	import { apiKey, cookie_info, cookie_update } from '../../../../store';
	import { fade, fly } from 'svelte/transition';
 import type { Pedido, Comprador } from '$lib/types/Pedido';
	import type { Product } from '$lib/types/Product';

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	import WebMenuB from '$lib/components/WebMenuB.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';

	import WebProduct from '$lib/components/WebProduct.svelte';
	import type { ProductOptions } from '$lib/types/ProductOptions';

	let m_show: boolean = false;
	let message: Message;

 const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	export let product: Product = {
		id: 0,
		company_id: company_id,
		category_id: 0,
		product: '',
		ref: '',
		description: '',
		description2: '',
		price: 0,
		size: '',
		color: '',
		stock: 0,
		image1: '',
		image2: '',
		image3: '',
		image4: '',
		position: 0,
		options: '',
		home: false,
		active: true
	};
	let imageTop: string = '';
	let listProductOptions: Array<ProductOptions>= []
	
	const loadProductOptions = async (id: number) => {
		
		console.log(
			urlAPI +
				'?ref=load-listWeb&folder=maker_product_versions' +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +
				'&campo=product_id&campoV=' +
				id
		);
		await fetch(
			urlAPI +
				'?ref=load-listWeb&folder=maker_product_versions' +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb +
				'&campo=product_id&campoV=' +
				id
		)
			.then((response) => response.json())
			.then((result) => {
				listProductOptions = result;
				console.log('Versiones',listProductOptions);
				
			})
			.catch((error) => console.log(error.message));
	}



	const loadProducto = async (name: string) => {
		//	console.log('contenido:' + name);
		console.log(
			urlAPI +
				'?ref=loadIDWeb&folder=maker_products&field=product&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		);
		await fetch(
			urlAPI +
				'?ref=loadIDWeb&folder=maker_products&field=product&name=' +
				name +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				product = result[0];
				console.log('producto');
				console.log(result[0]);
				imageTop = result[1]['image'];
				loadProductOptions(product.id)
			})
			.catch((error) => console.log(error.message));
	};

	onMount(() => {
		loadProducto($page.params.name);
	});

	$: loadProducto($page.params.name);
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

const date = new Date(); 
	const dateToday = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

let carrito_total: number = 0;

</script>

<svelte:head>
	<title>{product.product}</title>
<!-- 	<link rel="stylesheet" href="../../css/fontawesome-free-6.4.0-web/css/all.css" /> -->
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<div class="relative overflow-hidden" style="height: 16vw">
	{#if imageTop}
		 <!-- content here -->
			<img src="{urlFiles}/images/maker_products/{prefixFolder}{imageTop}" alt="" class="w-full" />
	{/if}
</div>


<WebMenuB bind:carrito_total />

<section>
	<WebProduct bind:product bind:prefixFolder bind:listProductOptions />
</section>

<WebFooter />

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
