<script lang="ts">
import { onMount } from 'svelte';
	import { page } from '$app/stores';
	import { apiKey } from '../../store';
	import { fade } from 'svelte/transition';
	//import { bars, faCaretUp } from '@fortawesome/free-solid-svg-icons/index.es'

	import type { MenuWeb } from '$lib/types/MenuWeb';

	console.log(
		$apiKey.urlAPI_Maker +
			'?ref=menu-web-head&company_id=' +
			$apiKey.companyId +
			'&tokenWeb=' +
			$apiKey.token
	);

let listMenu: Array<MenuWeb> = [];
	let logoURL:string

console.log(
			$apiKey.urlAPI_Maker +
				'?ref=menu-web-head&company_id=' +
				$apiKey.companyId +
				'&tokenWeb=' +
				$apiKey.token
		)
		
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
				console.log('menu header')
				console.table(listMenu);
				//return;
			})
			.catch((error) => console.log(error.message));
	};

	onMount(()=>{
			loadMenu()
		})
		
	//let load = loadMenu();

	let movil_menu: boolean = true;
	let urlFiles = $apiKey.urlFiles;
	export let scrollY:number
</script>

<header class='menu-top'>
 <div class="containe mx-auto ">
	
	<div class="rounded-lg bg-white overflow-hidden w-40 absolute -top-4 left-2 ">
	
			<img src="{urlFiles}/images/maker_companies/{logoURL}" class="mx-auto" alt="" />
		<!--	-->
		
	
	</div>

		<!--
{$page.url.pathname}	
-->
		<button
			class="flex justify-end w-full md:hidden"
			on:click={() => {
				movil_menu = !movil_menu;
			}}
		>
		<i class="fa fa-bars text-white cursor-pointer hover:bg-black my-2 mr-2" />
			
		</button>
		{#if movil_menu == true}
			<nav transition:fade class="menu_up_movil md:hidden">
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

		{#if listMenu.length>0}
		
		<nav class="menu_up hidden md:block">
			<ul>
				{#each listMenu as menu}
					<li
						class:active={$page.url.pathname === menu.link + '/' ||
							$page.url.pathname === menu.link}
					>
						<a data-sveltekit-preload-data href={menu.link}>{menu.menu}</a>
					</li>
				{/each}
			</ul>
		</nav>
	{/if}
	
</div>
</header>
