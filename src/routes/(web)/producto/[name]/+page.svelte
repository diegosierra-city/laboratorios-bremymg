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
let newPedido: Pedido = {
	id: Date.now(),
		comprador_id: 0,
		productos: [],
		fecha: dateToday,
		valor: 0,
		estado: '',
		pago_estado: '',
		pago_id: '',
		notas: '',
		origen: 'WEB'
}


if(cookie_info('carrito_total')){
		carrito_total=Number(cookie_info('carrito_total'));
		let carrito_pedido:any = cookie_info('carrito_pedido')
		newPedido = JSON.parse(carrito_pedido);
	}
</script>

<svelte:head>
	<title>{$page.params.name}</title>
	<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.css" />
</svelte:head>

<svelte:window bind:innerWidth bind:innerHeight bind:scrollY />

<div
	class="relative"
	style="background: url({urlFiles}/images/maker_products/{prefixFolder}{imageTop}); background-size: auto 100%;  background-position: center center; height: 35vh"
>
	<div
		class="absolute bottom-6 left-0 w-full MrDafoe lowercase text-center text-white px-4"
		style="font-size: 4vw; text-shadow: 2px 2px 3px #000000;"
	>{product.product}</div>
</div>


<WebMenuB {carrito_total} {newPedido}/>

<section>
	<WebProduct bind:product bind:prefixFolder bind:carrito_total bind:newPedido />
</section>

<WebFooter />

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
