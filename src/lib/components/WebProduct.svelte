<script lang="ts">
	import { onMount } from 'svelte';
	import { numberFormat } from '$lib/components/NumberFormat';
	import type { Pedido, Comprador, PedidoProduct } from '$lib/types/Pedido';
	import type { Product } from '$lib/types/Product';
	import { apiKey, cookie_info, cookie_update, newPedido, carritoTotal, userNow } from '../../store';
	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	import type { ProductOptions } from '$lib/types/ProductOptions';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	const date = new Date();
	const dateToday = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

	export let product: Product = {
		id: Date.now(),
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
		active: true,
		variants: []
	};

	export let listProductOptions: Array<ProductOptions> = [];

	export let prefixFolder: string = '';

	let imagen_principal: string;

	function change_image(url: string) {
		let[folder,file]=url.split('M')
		imagen_principal = urlFiles + '/images/'+ folder + prefixFolder + file;
		//console.log('CambiarImagen',imagen_principal)
	}

	
	let image_version: string = '';
	let versionActual: number = -1;
	let stock: number;
	//let tarifa: number;
	//let cantidad: number = 1;
	//let total: number = 0;

	/* function totalizar(t: number, c: number) {
		console.log(t + '*' + c);
		total = t * c;
	} */

	const cambiarCantidad = (accion: string) => {
		if (accion == 'mas') {
			newProduct.quantity++;
		} else {
			newProduct.quantity--;
		}

		if (newProduct.quantity <= 0) {
			newProduct.quantity = 1;
		} else if (newProduct.quantity > stock) {
			newProduct.quantity = stock;
			alert('No tenemos más existencias en este momento'+stock)
		}
		newProduct.total = newProduct.price * newProduct.quantity;
	};

	
	let newProduct: PedidoProduct;

	$: if (product.id) {
		change_image('maker_products/M'+product.image1);
		stock= product.stock;
		newProduct = {
			id: Date.now(),
			company_id: $apiKey.companyId,
			order_id: 0,
			category_id: product.category_id,
			product_id: product.id,
			name: product.product,
			ref: product.ref,
			image: 'maker_products/M'+product.image1,
			description: product.description,
			description2: product.description2,
			size: product.size,
			color: product.color,
			version_type: product.options,
			version: '',
			image_version: image_version,
			price: product.price,
			quantity: 1,
			total: product.price
		};
	}

	function addCarrito(valor: number, p: PedidoProduct) {
		$carritoTotal += (valor*1);
		//alert(carrito_total)
		//cookie_update('carrito_total', String(carrito_total));
		$newPedido.valor = $carritoTotal

		$newPedido.productos = [...$newPedido.productos, newProduct];
		//cookie_update('carrito_pedido', JSON.stringify(newPedido));
		message = {
			title: 'Carrito',
			text: 'Se ha agregado este producto a tu carrito.',
			class: 'message-green',
			accion: ''
		};
		m_show = true;
	}

	
	const cambioVersion = (posicion: number) => {
		if (posicion != -1) {
			newProduct['price'] = listProductOptions[posicion]['price'];
			stock = listProductOptions[posicion]['stock'];
			if (listProductOptions[posicion]['image'] != '') {
				newProduct['image'] = 'maker_product_versions/M'+listProductOptions[posicion]['image'];
				//alert(newProduct['image'])
				change_image(newProduct['image']);
			}else{
				change_image('maker_products/M'+product.image1);
			}
			newProduct.total = newProduct.price * newProduct.quantity;
		}
		console.log('NuevoProductox', newProduct);
	};

	$: console.log('NuevoProducto', newProduct);
	cambioVersion(versionActual);

	$: if (listProductOptions.length > 0 && newProduct && versionActual == -1) {
		versionActual = 0;
		cambioVersion(0);
		newProduct['version'] = listProductOptions[0]['name']
	}
</script>

<div class="w-11/12 md:w-8/12 mx-auto mt-20 md:mt-4 grid grid-cols-1 md:grid-cols-2 ">
	<div>
		<img src={imagen_principal} alt={product.product} class="product-image" />
		<div class="flex mt-2 mb-6">
			{#if product.image1 != '' && product.image2 != ''}
				<button
					on:click={() => {
						change_image('maker_products/M'+product.image1);
					}}
					class="mx-2 w-40"
				>
					<img
						src="{urlFiles}/images/maker_products/M{product.image1}"
						alt=""
						class="product-image-small"
					/></button
				>

				<button
					on:click={() => {
						change_image('maker_products/M'+product.image2);
					}}
					class="mr-2 w-40"
				>
					<img
						src="{urlFiles}/images/maker_products/M{product.image2}"
						alt=""
						class="product-image-small"
					/></button
				>
			{/if}
			{#if product.image3 != ''}
				<button
					on:click={() => {
						change_image('maker_products/M'+product.image3);
					}}
					class="mr-2 w-40"
				>
					<img
						src="{urlFiles}/images/maker_products/M{product.image3}"
						alt=""
						class="product-image-small"
					/></button
				>
			{/if}
			{#if product.image4 != ''}
				<button
					on:click={() => {
						change_image('maker_products/M'+product.image4);
					}}
					class="mr-2 w-40"
				>
					<img
						src="{urlFiles}/images/maker_products/M{product.image4}"
						alt=""
						class="product-image-small"
					/></button
				>
			{/if}
		</div>
	</div>

	<div class="px-2">
		<h2 class="text-primary">{product.product}</h2>
		<div>{product.ref}</div>
		<p>
			{#if product.description2 != ''}
				{@html product.description2}
			{:else}
				{@html product.description}
			{/if}
		</p>
		<div class="mt-4 border-t border-dotted">
			<div class="mt-1">
				{#if newProduct}
					<!-- content here -->
					<h3>Precio: ${numberFormat(newProduct.total)}</h3>
					<!-- {newProduct['version']}-{versionActual} -->
				{/if}

				{#if listProductOptions && listProductOptions.length > 0}
					{#each listProductOptions as option, i}
						<div>
							<!--	
								<input type="radio" bind:group={versionActual} name="versionActual" value={i} on:click={()=>cambioVersion(i)}/>
							-->
							<input type="radio" checked={i===0 && true}  name="versionActual" value={i} on:click={()=> {
								newProduct['version']=option.name
								cambioVersion(i)
								versionActual=i
								}}/>
							{product.options+': '+option.name}
						</div>
					{/each}
				{/if}

				{#if newProduct?.quantity}
				<div class="text-xl">
					<button on:click={() => cambiarCantidad('menos')}
						><i class="fa fa-minus-square mr-2 text-primary" /></button
					>
					{newProduct.quantity}
					<button on:click={() => cambiarCantidad('mas')}
						><i class="fa fa-plus-square ml-2 text-primary" /></button
					>
				</div>
				{/if}
				

			</div>

			{#if newProduct}
				 <!-- content here -->
					<div>
						<button
							class="btn-green bg-primary"
							on:click={() => {
								addCarrito(newProduct.total, newProduct);
							}}><i class="fa fa-cart-plus" /> Agregar al Carrito</button
						>
					</div>
			{/if}
			

		</div>
	</div>
</div>

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
