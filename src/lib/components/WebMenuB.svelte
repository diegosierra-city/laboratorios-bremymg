<script lang="ts">
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import { apiKey, cookie_info, carritoTotal, cookie_update, userNow } from '../../store';
	import { fade } from 'svelte/transition';
	//import { bars, faCaretUp } from '@fortawesome/free-solid-svg-icons/index.es'
	import { numberFormat } from '$lib/components/NumberFormat';
	import type { MenuWeb } from '$lib/types/MenuWeb';

	import WebCart from './WebCart.svelte';

	console.log(
		$apiKey.urlAPI_Maker +
			'?ref=menu-web-head&company_id=' +
			$apiKey.companyId +
			'&tokenWeb=' +
			$apiKey.token
	);

	let listMenu: Array<MenuWeb> = [];
	let logoURL: string;

	console.log(
		$apiKey.urlAPI_Maker +
			'?ref=menu-web-head&company_id=' +
			$apiKey.companyId +
			'&tokenWeb=' +
			$apiKey.token
	);

	const loadMenu = async () => {
		const res = await fetch(
			$apiKey.urlAPI_Maker +
				'?ref=menu-web-head&company_id=' +
				$apiKey.companyId +
				'&tokenWeb=' +
				$apiKey.token
		)
			.then((response) => response.json())
			.then((result) => {
				listMenu = result[0];
				logoURL = result[1];
				//console.log('menu header');
				//console.table(listMenu);
				//return;
			})
			.catch((error) => console.log(error.message));
	};

	onMount(() => {
		loadMenu();
	});

	//let load = loadMenu();

	let movil_menu: boolean = false;
	let urlFiles = $apiKey.urlFiles;
	//export let scrollY: number;

	let showCarrito: boolean = false;

	import type { Pedido, Comprador, PedidoProduct } from '$lib/types/Pedido';

	const date = new Date();
	const dateToday = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

	export let carrito_total: any = 0;

	if (cookie_info('carrito_total')) {
		carrito_total = Number(cookie_info('carrito_total'));
	}
</script>

<header class="menu-top">
	<div class="w-full mx-auto ">
		<div class="rounded-lg bg-white overflow-hidden w-40 absolute -top-4 left-2 ">
			{#if logoURL}
				<!-- content here -->
				<a href="/"
					><img src="{urlFiles}/images/maker_companies/{logoURL}" class="mx-auto" alt="" /></a
				>
			{/if}
		</div>

		<div class="mr-2">
			<div class="flex justify-end lg:hidden">
				<button class="flex text-xs text-white mt-2 mr-4" on:click={() => (showCarrito = true)}
					><i class="fa fa-shopping-cart mt-1" /> ${numberFormat(carrito_total)}</button
				>

				<button
					class="flex justify-end items-center text-lg mr-2"
					on:click={() => {
						movil_menu = !movil_menu;
					}}
				>
					<i class="fa fa-bars text-white cursor-pointer hover:bg-black my-2 mr-2" />
					<small class="text-white text-sm">menú</small>
				</button>
			</div>
		</div>
		{#if movil_menu == true}
			<nav transition:fade class="menu_up_movil lg:hidden">
				<ul>
					{#each listMenu as menu}
						<li
							class:active={$page.url.pathname === menu.link + '/' ||
								$page.url.pathname === menu.link}
						>
							<a
								data-sveltekit-preload-data
								href={menu.link}
								on:click={() => {
									movil_menu = false;
								}}>{menu.menu}</a
							>
						</li>
					{/each}
				</ul>
			</nav>
		{/if}

		{#if listMenu.length > 0}
			<div class="w-full flex right-0 justify-end ">
				<nav class="menu_up hidden lg:block ">
					<ul>
						{#each listMenu as menu}
							<li
								class:active={$page.url.pathname === menu.link + '/' ||
									$page.url.pathname === menu.link}
							>
								<a data-sveltekit-preload-data href={menu.link}>{menu.menu}</a>
							</li>
						{/each}
						<li>
							<button class="text-sm" on:click={() => (showCarrito = true)}
								><i class="fa fa-shopping-cart" /> ${numberFormat($carritoTotal)}</button
							>
						</li>
					</ul>
				</nav>
			</div>
		{/if}
	</div>
</header>

{#if showCarrito}
	<!-- content here -->
	<WebCart bind:showCarrito />
{/if}
