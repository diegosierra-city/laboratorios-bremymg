<script lang="ts">
	import { apiKey, userNow } from '../../store';
	import type { Pedido, Comprador } from '$lib/types/Pedido';
	import { numberFormat } from '$lib/components/NumberFormat';
	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';
	import { onMount } from 'svelte';
	import AdminPedido from './AdminPedido.svelte';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;

	let showPedido: boolean = false;
	let pedidoActual: number
	let pedido: Pedido;
	let comprador: Comprador;
	let listPedidos: Array<Pedido>;
	let listCompradores: Array<Comprador>;

	const loadPedidos = async () => {
		console.log(
			urlAPI +
				`?ref=load-list&user_id=${$userNow.id}&time=${$userNow.user_time_life}&token=${$userNow.token}&folder=maker_orders&order=id DESC`
		);
		await fetch(
			urlAPI +
				`?ref=load-list&user_id=${$userNow.id}&time=${$userNow.user_time_life}&token=${$userNow.token}&folder=maker_orders&order=id DESC`
		)
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				listPedidos = result[0];
				listCompradores = result[1];
				console.log('pedidos', listPedidos);
			})
			.catch((error) => console.log(error.message));
	};

	/* const loadCompradores = async () => {
		console.log(urlAPI + `?ref=load-list&user_id=${$userNow.id}&time=${$userNow.user_time_life}&token=${$userNow.token}&folder=maker_buyer&order=order_id`)
			await fetch(urlAPI + `?ref=load-list&user_id=${$userNow.id}&time=${$userNow.user_time_life}&token=${$userNow.token}&folder=maker_buyer&order=id`)
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
			listCompradores = result;
			console.log('compradores',listCompradores)	
			})
			.catch((error) => console.log(error.message));
	} */

	onMount(() => {
		loadPedidos();
	});

	const updateCampo = async (campo: string, id: number, val: any) => {
		await fetch(urlAPI + '?ref=update-campo', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				campo: campo,
				id: id,
				folder: 'maker_orders',
				val: val
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				console.log(result);
				message = {
					title: 'Información',
					text: 'Se actualizó la información',
					class: 'message-green',
					accion: ''
				};
				m_show = true;
			})
			.catch((error) => console.log(error.message));
	};
</script>

<div class="w-11/12 mx-auto pt-12 ">
	<h2>Pedidos:</h2>
	{#if listPedidos}
		<!-- content here -->
		<table>
			<thead>
				<th scope="col" class="px-4 py-2" />
				<th scope="col" class="px-4 py-2"> Pedido </th>
				<th scope="col" class="px-4 py-2"> Cliente </th>
				<th scope="col" class="px-4 py-2"> Dirección Env. </th>
				<th scope="col" class="px-4 py-2 text-center"> Total </th>
				<th scope="col" class="px-4 py-2 text-center"> Pago </th>
				<th scope="col" class="px-4 py-2 text-center"> Enviado </th>
			</thead>
			<tbody>
				{#each listPedidos as pedido, i}
					<tr class="bg-white border-b hover:bg-aliceblue" on:click={()=>{
						pedidoActual=i
						showPedido=true
					}}>
						<td class="font-bold">{i + 1}</td>
						<td class="text-center">N.: {pedido.id}</td>
						<td
							>{@html `${listCompradores[i].nombres} ${listCompradores[i].apellidos} <br>Cel.: ${listCompradores[i].celular}`}</td
						>
						<td>{@html `${listCompradores[i].direccion} <br> ${listCompradores[i].ciudad}`}</td>
						<td class="text-center">${numberFormat(pedido.valor)}</td>
						<td class="text-center">
							<select
								class="inputA w-32"
								bind:value={pedido.pago_estado}
								on:change={() => {
									updateCampo('pago_estado', pedido.id, pedido.pago_estado);
								}}
							>
								<option value="0">Sin Confirmar</option>
								<option value="1">Confirmado</option>
							</select>
						</td>
						<td class="text-center">
							<!-- 	{#if pedido.fecha_envio==='0000-00-00 00:00:00'}
									<input type="datetime-local" class="inputA w-40" bind:value={pedido.fecha_envio}>
									{:else}
									{pedido.fecha_envio}
									{/if} -->
							<div class="flex">
								<input type="datetime-local" class="inputA w-48" bind:value={pedido.fecha_envio} />
								<button
									class="text-green"
									on:click={() => {
										updateCampo('fecha_envio', pedido.id, pedido.fecha_envio);
									}}><i class="fa fa-save" /></button
								>
							</div>
						</td>
					</tr>
					<!---->
				{:else}
					cargando ...
				{/each}
			</tbody>
		</table>
	{/if}
</div>

{#if showPedido}
	 <!-- content here -->
		<AdminPedido pedido={listPedidos[pedidoActual]} comprador={listCompradores[pedidoActual]} bind:showPedido />
{/if}

{#if m_show == true}
	<Messages bind:m_show bind:message/>
{/if}
