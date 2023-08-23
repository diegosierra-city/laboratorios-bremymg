<script lang="ts">
	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';
	import { linkMaker } from '$lib/components/LinkMaker';
	import { numberFormat } from '$lib/components/NumberFormat';
	import type { PedidoProduct } from '$lib/types/Pedido';
	import { apiKey, userNow, newPedido, carritoTotal } from '../../store';
	import { onDestroy } from 'svelte';

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	const urlFiles = $apiKey.urlFiles;
	export let listProducts: Array<any>
	export let target: string;
	//let listProductCarrito: Array<PedidoProduct>;

/* 	function updateCant(position: number, action: string) {
		if (action === 'mas') listProductCarrito[position].quantity++;
		if (action === 'menos') listProductCarrito[position].quantity--;

		if (listProductCarrito[position].quantity <= 0) listProductCarrito[position].quantity = 1;

		if (listProductCarrito[position].quantity > listProducts[position].stock)
			listProductCarrito[position].quantity = listProducts[position].stock;

		listProductCarrito[position].total =
			listProductCarrito[position].quantity * listProductCarrito[position].price;
	} */

/* 	function cambioVersion(event: any) {
		//let newPrice=listProducts[position]['version'][version]
		let vdata = event.target.value;
		vdata = vdata.split('|');
		let [position, newStock, newPrice, newVersion] = vdata;
		//
		listProductCarrito[position].price = newPrice;
		listProducts[position].stock = newStock;
		listProductCarrito[position].version =
			listProductCarrito[position].version_type + ': ' + newVersion;
		///
		updateCant(position, 'igual');
	} */

/* 	function addCarrito(valor: number, p: PedidoProduct) {
		$carritoTotal += +valor;
		//alert(carrito_total)
		//cookie_update('carrito_total', String(carrito_total));
		$newPedido.valor = $carritoTotal;

		$newPedido.productos = [...$newPedido.productos, p];
		//cookie_update('carrito_pedido', JSON.stringify(newPedido));
		message = {
			title: 'Carrito',
			text: 'Se ha agregado este producto a tu carrito.',
			class: 'message-green',
			accion: ''
		};
		m_show = true;
	} */

//	$: console.log('PPP',listProducts)
</script>

{#if listProducts?.length>0}
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 container mx-auto my-6 px-4 ">
		{#each listProducts as product, i}
		
		<a href={target==='categorias'? '/pagina/Lineas-de-Producto' : linkMaker(`/producto/${product.product}`)} >
			<div class="mx-2 relative justify-center items-center content-center align-middle text-center">
				<div class="card_img">
					{#if product.image1}
						<!-- content here -->
						<img
							src="{urlFiles}/images/maker_products/M{product.image1}"
							alt={product.product}
							class="mx-auto my-4" style="box-shadow: 10px 10px 0px #666"
						/>
					{/if}
				</div>

				<div class="card_text relative z-10 h-full">
									

					<!-- card_text -->
					<div class="flex items-center justify-center">
<img src="/maker-files/iconC{product.category_id}.png" alt="" class="w-10 md:w-14" >
<div class="flex flex-col pl-2">
<h2 class="card_titleC m-0 w-full text-left">{product.product}</h2>
<h3 class="text-primary -mt-2 text-left" style="line-height: 1;">{@html product.ref}</h3>	
</div>

</div>

				</div>
			</div>
		</a>
		{/each}
	</div>
{/if}

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
