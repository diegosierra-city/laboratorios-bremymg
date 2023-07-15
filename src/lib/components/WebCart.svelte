<script lang="ts">
	import type { Pedido, Comprador, PedidoProduct } from '$lib/types/Pedido';
	import { apiKey, cookie_info, cookie_update, newPedido, pedidoComprador, carritoTotal, userNow } from '../../store';
	import type { Message } from '$lib/types/Message';
	import Messages from '$lib/components/Messages.svelte';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	const date = new Date();
	const dateToday = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

	
	export let showCarrito: boolean;
	let paso: number = 1;
	let pasoOk: number = 1;
	

	//$: console.log('Pedido:', newPedido);

	const pedidoSave = async () => {
		// console.log('Pedido', newPedido);
		// console.log('Comprador', pedidoComprador);
		// console.log('Productos', $newPedido.productos);

		await fetch(urlAPI + '?ref=pedidoSave', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				company_id: $apiKey.companyId,
				tokenWeb: tokenWeb,
				pedido: $newPedido,
				pedidoComprador: $pedidoComprador,
				productos: $newPedido.productos
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			.then((result) => {
				console.log('resultado', result);
				//urlMotorResults = result[0].url_link;
				message = {
					title: 'Reserva',
					text: 'Se ha registrado su reserva, un email de confirmación se ha enviado a su email',
					class: 'message-green',
					accion: ''
				};
				m_show = true;
				 
			/// seteamos las stores del carrito
		$newPedido = {
	id: Date.now(),
		company_id: $apiKey.companyId,
		order_id: 0,
		comprador_id: 0,
		productos:[],
		fecha: '',
		valor: 0,
		estado: '',
		pago_estado: '',
		pago_id: '',
		notas: '',
		fecha_envio: '',
		origen: 'WEB'
}

$pedidoComprador = {
	id: Date.now(),
	company_id: $apiKey.companyId,
	nombres: '',
	apellidos: '',
	documento: '',
	email: '',
	celular: '',
	pais: 'Colombia',
	ciudad: '',
	direccion: ''
}

$carritoTotal = 0

				/// fin resetear
				showCarrito = false;
				
			})
			.catch((error) => console.log(error.message));
	};

	const cambiarPaso = (n: number) => {
		paso = n;
	};

	const carritoDelete = (ind: number) => {
		if(ind>-1){
	if(confirm('Borrar este Producto?')) {

			$newPedido.productos.splice(ind, 1);
			//
			let tt = 0;
			for (let prod of $newPedido.productos) {
				tt += Number(prod.total);
			}
			$newPedido.valor = tt;
			$carritoTotal = tt
			// cookie_update('carrito_pedido', JSON.stringify(newPedido));
			// cookie_update('$carritoTotal', String(tt));
			// console.log('total carrito', tt);
			//
			message = {
				title: 'Borrar',
				text: 'Se borró el producto',
				class: 'message-red',
				accion: ''
			};
			m_show = true;
		}
	}else{
		if (confirm('Desea Borrar TODO el carrito?')) {

			$newPedido = {
					id: Date.now(),
					company_id: company_id,
					order_id: 0,
					comprador_id: 0,
					productos: [],
					fecha: String(dateToday),
					valor: 0,
					estado: '',
					pago_estado: '',
					pago_id: '',
					notas: '',
					fecha_envio: '',
					origen: 'WEB'
				}

				$carritoTotal = 0
// cookie_update('carrito_pedido', JSON.stringify(newPedido));
// cookie_update('$carritoTotal', String(0));
//
message = {
	title: 'Borrar',
	text: 'Se borró el Carrito',
	class: 'message-red',
	accion: ''
};
m_show = true;
}
	}
	//console.log('Ps',$newPedido.productos)
	};
//console.log('Psx',$newPedido.productos)
//	$: console.log('Psx',$newPedido.productos)
</script>

{#if $carritoTotal > 0}
	<button
		class="fixed w-full h-full bg-black/70 z-40 overflow-y-hidden top-0 left-0 bottom-0 cursor-pointer"
		on:click|self={() => (showCarrito = false)}
	/>

	<div
		class="carrito fixed z-40 top-12 w-11/12 max-w-full md:w-8/12 mx-auto bg-white p-1 md:p-4 rounded-md overflow-auto"
		style="left: 50%; transform: translateX(-50%); max-height:85vh"
	>
		<div class="pasos_carrito">
			<ul>
				<li class:active={paso === 1}>
					<button><i class="fa-solid fa-box-open" /> <span>Pedido</span> </button>
				</li>
				<li class:active={paso === 2}>
					<button><i class="fa-solid fa-person-circle-check" /> Comprador </button>
				</li>
				<li class:active={paso === 3}>
					<button><i class="fa-solid fa-truck" /> Envio </button>
				</li>
			</ul>
		</div>

		<!-- content here -->
		<div class="carrito-paso" class:hidden={paso != 1}>
			<h3>Pedido</h3>

			<div class="list-carrito">
				{#if $carritoTotal > 0}
					<div class="list-carrito-titulo">Imagen</div>
					<div class="list-carrito-titulo">Producto</div>
					<div class="list-carrito-titulo">Precio</div>
					<div class="list-carrito-titulo">Cantidad</div>
					<div class="list-carrito-titulo">Total</div>
					<!-- content here -->
					{#each $newPedido.productos as producto, i}
						<!-- content here -->
						<div class="flex">
							<span class="font-bold">{i + 1}.</span>
							<img src="{urlFiles}/images/{producto.image}" alt="" class="w-24" />
						</div>
						<div>{producto.name}
					<small>	{@html producto.version!=''? '<strong>Versión:</strong> '+producto.version_type+' '+producto.version : ''} </small>
						</div>
						<div class="text-right">{producto.price}</div>
						<div class="text-center">{producto.quantity}</div>
						<div class="text-right">
							{producto.total}
							<button
								class="ml-4"
								on:click={() => {
									carritoDelete(i);
								}}
								><i class="fa-solid fa-circle-xmark text-red hover:text-primary text-lg" /></button
							>
						</div>
					{/each}

					<div class="col-span-4 text-right">TOTAL:</div>
					<div class="text-center text-xl font-bold pr-4">${$carritoTotal}
					
						<button
							class="ml-4"
							on:click={() => {
								carritoDelete(-1);
							}}
							><i class="fa-solid fa-circle-xmark text-red hover:text-primary text-lg" /></button
						>
					</div>

			
				{/if}
			</div>

			<div>
				<button
					class="btn-primary-full py-2 md:mt-7"
					on:click={() => {
						cambiarPaso(2);
					}}>Siguiente <i class="fa-solid fa-circle-right" /></button
				>
			</div>
		</div>

		<!-- else if content here -->
		<div class="carrito-paso" class:hidden={paso != 2}>
			<h3>Comprador</h3>
			<form on:submit|preventDefault={() => cambiarPaso(3)}>
				<div class="pestana-body grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
					<div>
						<div>Documento:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.documento}
							/>
						</div>
					</div>

					<div>
						<div>Nombres</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.nombres}
							/>
						</div>
					</div>

					<div>
						<div>Apellidos:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.apellidos}
							/>
						</div>
					</div>

					<div>
						<div>Email:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.email}
							/>
						</div>
					</div>

					<div>
						<div>Celular:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.celular}
							/>
						</div>
					</div>
				</div>

				<div class="flex">
					<button
						class="btn-primary-full py-2 md:mt-7"
						on:click={() => {
							cambiarPaso(paso - 1);
						}}><i class="fa-solid fa-circle-left" /> Anterior</button
					>
					<button type="submit" class="btn-primary-full py-2 md:mt-7"
						>Siguiente <i class="fa-solid fa-circle-right" /></button
					>
				</div>
			</form>
		</div>

		<!-- else content here -->
		<div class="carrito-paso" class:hidden={paso != 3}>
			<h3>Datos de Envío</h3>

			<form on:submit|preventDefault={() => pedidoSave()}>
				<div class="pestana-body grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
					<div>
						<div>Pais:</div>
						<div class="col-span-2 font-bold">
							{$pedidoComprador.pais}
						</div>
					</div>

					<div>
						<div>Ciudad:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={$pedidoComprador.ciudad}
							/>
						</div>
					</div>

					<div>
						<div>Dirección:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								bind:value={$pedidoComprador.direccion}
							/>
						</div>
					</div>
				</div>

				<div class="flex">
					<button
						class="btn-primary-full py-2 md:mt-7"
						on:click={() => {
							cambiarPaso(paso - 1);
						}}><i class="fa-solid fa-circle-left" /> Anterior</button
					>
					<button type="submit" class="btn-primary-full py-2 md:mt-7"
						>Siguiente <i class="fa-solid fa-circle-right" /></button
					>
				</div>
			</form>
		</div>
	</div>
{/if}

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
