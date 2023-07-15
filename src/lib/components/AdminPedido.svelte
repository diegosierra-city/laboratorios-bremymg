<script lang="ts">
import type {Pedido,Comprador,PedidoProduct} from '$lib/types/Pedido'
	import { onMount } from 'svelte';
import { apiKey, userNow } from '../../store';

import { numberFormat } from '$lib/components/NumberFormat';

const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;

export let pedido:Pedido
export let comprador:Comprador
export let showPedido:boolean
let listProducts: Array<PedidoProduct>

	const loadProducts=async(id:number)=>{
		await fetch(
			urlAPI +
				`?ref=load-list&user_id=${$userNow.id}&time=${$userNow.user_time_life}&token=${$userNow.token}&folder=maker_order_product&order=id DESC&campo=order_id&campoV=`+id
		)
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				listProducts = result;
							})
			.catch((error) => console.log(error.message));
	}

onMount(()=>{
	loadProducts(pedido.id)
})
</script>

<button class="bg-edit" on:click|self={()=>showPedido=false}>

	<div class="edit-page text-left">
<h3 class="text-secondary">Pedido WEB: {pedido.id}</h3>
<strong>Comprador:</strong><br />
{comprador.nombres} {comprador.apellidos} <br/>
Documento:{comprador.documento} | Celular:{comprador.celular} <br />Email: {comprador.email} }
<br /><strong>Destino Envío:</strong><br />{comprador.pais} - {comprador.ciudad}<br />{comprador.direccion}<br /><br /><strong>Compra:</strong><br />
{#if listProducts}
	 <!-- content here -->

<table style="border:solid 1px black; width: 100%">
	<tr><td style="border-bottom:solid 1px black">Producto</td><td
			style="border-bottom:solid 1px black">Imagen</td
		><td style="border-bottom:solid 1px black">Precio</td><td style="border-bottom:solid 1px black"
			>Cantidad</td
		><td style="border-bottom:solid 1px black">Total</td>
		</tr>
		{#each listProducts as prod,i}
		<tr>
			<td style="border-bottom:solid 1px black">{@html prod.name}</td>
			<td
			style="border-bottom:solid 1px black"><img src="{urlFiles}/images/{prod.image}" class="w-36" alt=""></td
		>
		<td style="border-bottom:solid 1px black">{prod.price}</td>
		<td style="border-bottom:solid 1px black"
			>{prod.quantity}</td
		>
		<td style="border-bottom:solid 1px black">{prod.total}</td>
		</tr>
		{/each}


		<tr>
		<td /><td /><td /><td>Total</td><td><strong>${numberFormat(pedido.valor)}</strong></td>
		</tr>
</table>
{/if}
</div>
</button>