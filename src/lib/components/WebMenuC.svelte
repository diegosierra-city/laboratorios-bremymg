<script lang="ts">
	import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import { apiKey, cookie_info, carritoTotal, cookie_update, userNow } from '../../store';
	import { fade } from 'svelte/transition';
	import { IconMenu2 } from '@tabler/icons-svelte';
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
		console.log('menu',$apiKey.urlAPI_Maker +
				'?ref=menu-web-head&company_id=' +
				$apiKey.companyId +
				'&tokenWeb=' +
				$apiKey.token)
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
				console.log('menu header',listMenu);
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
/// numero entero de dividir en 2
let position_logo:number = 0
	$: position_logo = Math.floor(listMenu.length/2)
</script>

<header class="menu-top">
	<div class="w-full mx-auto ">
		

		<div class="mr-2">
			<div class="flex justify-between lg:hidden barra-menu-movil">
			<!-- 	<button class="flex text-xs text-white mt-2 mr-4" on:click={() => (showCarrito = true)}
					><i class="fa fa-shopping-cart mt-1" /> ${numberFormat(carrito_total)}</button
				> -->
				<div></div>

				<div class="">
				<a data-sveltekit-preload-data href={'/'}>
					<img src="/maker-files/images/logo-LaboratoriosBremymg-Header.png" alt="" class="logo-movil">
						</a>	
				</div>
				

				<button
					class="flex justify-end items-center text-lg mr-2"
					on:click={() => {
						movil_menu = !movil_menu;
					}}
				>
				<IconMenu2 />
					<!-- <i class="fa fa-bars text-white cursor-pointer hover:bg-black my-2 mr-2" /> -->
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
			<div class="w-full flex right-0 justify-center p-0 ">
				<nav class="menu_up hidden lg:block m-0 p-0 barra-menu textYanone" >
					<ul>
						{#each listMenu as menu, i}
							<li
								class:active={$page.url.pathname === menu.link + '/' ||
									$page.url.pathname === menu.link}
									style="text-transform: uppercase; border:0"
							>
							{#if i!=1}
								 <!-- content here -->
								<a data-sveltekit-preload-data href={menu.link} class="flex whitespace-nowrap textYanone"> <img src={`/maker-files/icono${i+1}.png`} alt="" class="w-7 mr-2"> {menu.menu}</a>	
							{:else}
								 <!-- else content here -->
									<a data-sveltekit-preload-data href={menu.link} class="flex whitespace-nowrap">  {menu.menu} <img src={`/maker-files/icono${i+1}.png`} alt="" class="w-7 ml-2"></a>	
							{/if}
								

							</li>

							{#if i===(position_logo-1)}
							<li style=" border:0">
							<a data-sveltekit-preload-data href={'/'}>
						<img src="/maker-files/images/logo-LaboratoriosBremymg-Header.png" alt="" class="logo">
							</a>
						</li>
							{/if}
						{/each}
						<!-- <li>
							<button class="text-sm" on:click={() => (showCarrito = true)}
								><i class="fa fa-shopping-cart" /> ${numberFormat($carritoTotal)}</button
							>
						</li> -->
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


<style>
	.logo{
		margin-top:15px; width:280px
	}
	.barra-menu{
		height: 95px; 
	}
	.barra-menu li{margin-top:-30px; }
	.barra-menu-movil{
		height: 92px; 
	}
	.logo-movil{
		margin-top:0px; width:250px
	}
</style>