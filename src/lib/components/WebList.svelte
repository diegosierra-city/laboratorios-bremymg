<script lang="ts">
	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';
	import { linkMaker } from '$lib/components/LinkMaker';
	import { numberFormat } from '$lib/components/NumberFormat';
	import type { PedidoProduct } from '$lib/types/Pedido';
	import { userNow, newPedido, carritoTotal } from '../../store';
	import { onDestroy } from 'svelte';

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	
	let m_show: boolean = false;
	let message: Message;


	export let listItems: Array<Item> = [];
	export let listProducts: Array<Product> = [];
	export let category_id: number;

	let listProductCarrito: Array<PedidoProduct>;

	export let urlFiles: string;
	
	$: console.log('pppx',listItems);

	const updateIC = (lp: Array<Product>) => {
		listProductCarrito = [];
		lp.map((p) => {
			if(category_id==0) category_id=p.category_id
			let newP: PedidoProduct = {
				id: Date.now(),
				company_id: $userNow.company_id,
				order_id: 0,
				category_id: category_id,
				product_id: p.id,
				name: p.product,
				ref: p.ref,
				image: 'maker_products/M' + p.image1,
				description: p.description,
				description2: p.description2,
				size: p.size,
				color: p.color,
				version_type: p.options,
				version: '',
				image_version: 'maker_products/M' + p.image1,
				price: p.price,
				quantity: 1,
				total: p.price
			};
			listProductCarrito.push(newP);
		});
	};

	$: updateIC(listProducts);

	function updateCant(position: number, action: string) {
		if(action === 'mas') listProductCarrito[position].quantity++
		if(action === 'menos') listProductCarrito[position].quantity--;

		if (listProductCarrito[position].quantity <= 0) listProductCarrito[position].quantity = 1;

		if (listProductCarrito[position].quantity > listProducts[position].stock)
			listProductCarrito[position].quantity = listProducts[position].stock;

		listProductCarrito[position].total =
			listProductCarrito[position].quantity * listProductCarrito[position].price;
	}

	function cambioVersion(event:any){
		//let newPrice=listProducts[position]['version'][version]
		let vdata = event.target.value;
		vdata = vdata.split('|');
let [position, newStock, newPrice, newVersion] = vdata;
//console.log('vv',vdata)
//console.log('datos:',position, newStock, newPrice, newVersion)
		//
		listProductCarrito[position].price=newPrice
		listProducts[position].stock=newStock
		listProductCarrito[position].version=listProductCarrito[position].version_type+': '+newVersion
		///
		updateCant(position, 'igual')
	}


	function addCarrito(valor: number, p: PedidoProduct) {
		$carritoTotal += +valor;
		//alert(carrito_total)
		//cookie_update('carrito_total', String(carrito_total));
		$newPedido.valor = $carritoTotal

		$newPedido.productos = [...$newPedido.productos, p];
		//cookie_update('carrito_pedido', JSON.stringify(newPedido));
		message = {
			title: 'Carrito',
			text: 'Se ha agregado este producto a tu carrito.',
			class: 'message-green',
			accion: ''
		};
		m_show = true;
	}
	
</script>

{#if listItems}
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:flex container mx-auto my-6 px-4">
		{#each listItems as item}
			<a class="card_home mx-2" href="/{item.linkURL}">
				<h4 class="card_titleB">{item.titulo}</h4>
				<div class="card_img">
					<img src="{urlFiles}/images/{item.folder}/M{item.image}" alt={item.titulo} />
				</div>
				<div class="card_text">
					<p>
						{@html item.text}
					</p>
				</div>

				<button
					class="btn-green mr-2 w-full !rounded-b-lg rounded-t-none relative z-10 items-center"
				>
					<i class="fa fa-check mr-1" /> saber más</button
				>
			</a>
		{/each}
	</div>
{/if}

{#if listProducts}
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 container mx-auto my-6 px-4">
		{#each listProducts as product, i}
			<div class="card_home mx-2 relative">
				<div class="card_img">
					{#if product.image1}
						<!-- content here -->
						<img
							src="{urlFiles}/images/maker_products/M{product.image1}"
							alt={product.product}
							class="w-full"
						/>
					{/if}
				</div>

				<div class="card_text relative z-10 bg-lightgray h-full">
					<div class="grid grid-cols-2 px-2 relative -top-10 w-full drop-shadow-md">
						<h3
							class="text-center rounded-l-lg rounded-r-none p-0 h-9 w-full bg-white border border-silver "
						>
							$ {numberFormat(Number(listProductCarrito[i].total))}
						</h3>
						<div
							class="bg-secondary text-primary flex rounded-r-lg rounded-l-none items-center justify-center text-xl"
						>
							<!-- svelte-ignore a11y-click-events-have-key-events -->
							<i class="fa fa-minus-circle mr-4 cursor-pointer" on:click={() => updateCant(i, 'menos')} />
							{listProductCarrito[i].quantity}
							<!-- svelte-ignore a11y-click-events-have-key-events -->
							<i class="fa fa-plus-circle ml-4 cursor-pointer" on:click={() => updateCant(i, 'mas')} />
						</div>
						
					</div>

					<div class="flex align-middle justify-center w-full -mt-8">
						{#if product.variants?.length > 0}
							<select class="inputA w-32" on:change={cambioVersion} >
								{#each product.variants as variant}
									<!-- content here -->
									<option value={`${i}|${variant.stock}|${variant.price}|${product.options} : ${variant.name}`}>{product.options + ': ' + variant.name}</option>
								{/each}
							</select>
						{/if}

						<!-- svelte-ignore a11y-invalid-attribute -->
						<a href="" on:click={() => {
							addCarrito(listProductCarrito[i].total, listProductCarrito[i]);
						}}>
						<button class="btn-min bg-green">comprar</button>
</a>
						<a href="/producto/{linkMaker(product.product)}">
							<button class="btn-min bg-secondary">
								detalles</button
							>
						</a>

					</div>

					<!-- card_text -->
					<h3 class="card_titleC relative top-0 ">{product.product}</h3>
					<p class="text-darkgray px-6 relative top-0 text-justify">
						{@html product.description}
					</p>
				</div>
			</div>
		{/each}
	</div>
{/if}

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
