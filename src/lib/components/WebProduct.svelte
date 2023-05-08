<script lang="ts">
	import { numberFormat } from '$lib/components/NumberFormat';
	import type { Pedido, Comprador, PedidoProduct } from '$lib/types/Pedido';
	import type { Product } from '$lib/types/Product';
	import { apiKey, cookie_info, cookie_update, userNow } from '../../store';
	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	const date = new Date(); 
	const dateToday = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

	

	let pedidoComprador: Comprador = {
		id: 0,
		nombres: '',
		apellidos: '',
		documento: '',
		email: '',
		celular: '',
		pais: '',
		ciudad: '',
		direccion: ''
	};

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

	export let prefixFolder: string = '';

	let imagen_principal: string;
	$: imagen_principal = urlFiles + '/images/maker_products/' + prefixFolder + product.image1;
	function change_image(url: string) {
		imagen_principal = urlFiles + '/images/maker_products/' + prefixFolder + url;
		//alert(imagen_pricipal)
	}

	let tarifa: number;
	let cantidad: number = 1;
	let total: number = 0;

	function totalizar(t: number, c: number) {
		console.log(t + '*' + c);
		total = t * c;
	}

	const cambiarCantidad = (accion: string) => {
		if (accion == 'mas') {
			cantidad++;
		} else {
			cantidad--;
		}

		if (cantidad <= 0) {
			cantidad = 1;
		} else if (cantidad > product.stock) {
			cantidad = product.stock;
		}
	};

	let showBooking: boolean = false;

	const reservaSave = async () => {
		console.log('enviando reserva:');
		console.log(newPedido);
		console.log(pedidoComprador);
		console.log('Fin envio');

		await fetch(urlAPI + '?ref=reservaSave', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				tokenWeb: tokenWeb,
				pedido: newPedido,
				depidoComprador: pedidoComprador
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			.then((result) => {
				//urlMotorResults = result[0].url_link;
				message = {
					title: 'Reserva',
					text: 'SE ha registrado su reserva, un email de confirmación se ha enviado a su email',
					class: 'message-green',
					accion: ''
				};
				m_show = true;
				showBooking = false;
			})
			.catch((error) => console.log(error.message));
	};

	function abrir_pedido() {
		//alert(total+'*'+adultos)
		if (cantidad == 0) {
			message = {
				title: 'error',
				text: 'Define la cantidad de productos',
				class: 'message-red',
				accion: ''
			};
			m_show = true;
		} else {
			//alert(total+'+'+adultos)
			showBooking = true;
		}
	}

	$: tarifa = product.price;
	$: totalizar(tarifa, cantidad);

export let carrito_total: number = 0;
export let newPedido: Pedido = {
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
//cookie_update('carrito_total','');
//cookie_update('carrito_pedido','');


	if (cookie_info('carrito_total') && cookie_info('carrito_pedido')) {
		//alert(cookie_info('carrito_pedido'))
		carrito_total = Number(cookie_info('carrito_total'));
		let carrito_pedido:any = cookie_info('carrito_pedido')
		newPedido = JSON.parse(carrito_pedido);
	}




function addCarrito(valor:number,p:Product){
carrito_total+=valor
cookie_update('carrito_total',String(carrito_total))
newPedido.valor=carrito_total
let newProduct:PedidoProduct = {
	id: p.id,
 category_id: p.category_id,
 product: p.product,
 ref: p.ref,
 description: p.description,
 description2: p.description2,
 size: p.size,
 color: p.color,
 image1: p.image1,
 price: tarifa,
 quantity: cantidad,
 total: total,
}
newPedido.productos = [...newPedido.productos,newProduct]
cookie_update('carrito_pedido',JSON.stringify(newPedido))
message = {
					title: 'Carrito',
					text: 'Se ha agregado este producto a tu carrito.',
					class: 'message-green',
					accion: ''
				};
				m_show = true;
	}

</script>

{#if showBooking}
	<section>
		<button class="bg-message" on:click|self={() => (showBooking = false)}>
			<div class="zona-message">
				<h3>Pedido</h3>

				<form
					on:submit|preventDefault={reservaSave}
					class="message-body pestana-body grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2"
				>
					<div>
						<div>Documento:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={pedidoComprador.documento}
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
								bind:value={pedidoComprador.nombres}
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
								bind:value={pedidoComprador.apellidos}
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
								bind:value={pedidoComprador.email}
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
								bind:value={pedidoComprador.celular}
							/>
						</div>
					</div>

					<div>
						<div>Pais:</div>
						<div class="col-span-2">
							<input
								type="text"
								class="inputA"
								autocomplete="on"
								required
								bind:value={pedidoComprador.pais}
							/>
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
								bind:value={pedidoComprador.ciudad}
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
								bind:value={pedidoComprador.direccion}
							/>
						</div>
					</div>

					<div>
						<button type="submit" class="btn-primary-full py-2 md:mt-7">Reservar</button>
					</div>
				</form>
			</div>
		</button>
	</section>
{/if}

<div class="w-8/12 mx-auto mt-4 grid grid-cols-2">
	<div>
		<img src={imagen_principal} alt={product.product} class="product-image" />
		<div class="flex mt-2">
			{#if product.image2 != ''}
				<button
					on:click={() => {
						change_image(product.image1);
					}}
					class="mr-2 w-40"
				>
					<img
						src="{urlFiles}/images/maker_products/M{product.image1}"
						alt=""
						class="product-image-small"
					/></button
				>

				<button
					on:click={() => {
						change_image(product.image2);
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
						change_image(product.image3);
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
						change_image(product.image4);
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
				<h3>Precio: ${numberFormat(total)}</h3>

				<div class="text-lg">
					<button on:click={() => cambiarCantidad('menos')}
						><i class="fa fa-minus-square mr-2 text-green" /></button
					>
					{cantidad}
					<button on:click={() => cambiarCantidad('mas')}
						><i class="fa fa-plus-square ml-2 text-green" /></button
					>
				</div>
			</div>

			<div>
				<button
					class="btn-green"
					on:click={() => {
						addCarrito(total, product);
					}}><i class="fa fa-cart-plus" /> Agregar al Carrito</button
				>
			</div>
		</div>
	</div>
</div>

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
