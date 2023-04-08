<script lang="ts">
	import { onMount } from 'svelte';
	import { page } from '$app/stores';

	import { apiKey } from '../../store';
	import type { MenuWeb } from '$lib/types/MenuWeb';

	const urlAPI = $apiKey.urlAPI_Maker;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	export let listMenu: Array<MenuWeb> = [];

	async function loadMenu() {
		const result = await fetch(
			urlAPI + '?ref=menu-web-footer&company_id=' + company_id + '&tokenWeb=' + tokenWeb
		)
			.then((result) => result.json())
			.then((response)=>{
				console.log('pie')
				listMenu=response[0]
			})
			.catch((error) => console.log(error.message));
	}

	//let load = loadMenu();
	onMount(()=>{
		loadMenu();
	})
</script>

<footer class="relative top-28 sm:top-30">
	<!--
{$page.url.pathname}     
-->
	<div class="grid grid-cols-1 mb-3 sm:grid-cols-3 gap-2 ">
		<div class="grid place-content-center place-items-center">
			<!--
<img src="/maker-files/images/Logo-Cootraesturz-200.png" class="logo_down md:mr-4" alt="Cootraesturz" />
			-->
			
		</div>

		<div class="menu_footer">
			<ul>
				<li>
					<a href="Https://facebook.com" target="_blank" rel="noopener noreferrer">
				<i class="fa fa-facebook"></i> Facebook</a>
			</li>
<li>
	<a
				href="https://instagram.com"
				target="_blank"
				rel="noopener noreferrer"
			>
					<i class="fa fa-instagram"></i> Instagram</a>
</li>
				
			</ul>
					
		</div>

		<div>
			<nav>
				<ul class="menu_footer">
					{#each listMenu as menu}
						<li
							class:active={$page.url.pathname === menu.link + '/' ||
								$page.url.pathname === menu.link}
						>
							<a data-sveltekit-preload-data href={menu.link}>{menu.menu}</a>
						</li>
					{:else}
						cargando...
					{/each}
				</ul>
			</nav>

			
		</div>

		
		
	</div>

	<div class="grid grid-cols-1 mb-3 sm:grid-cols-3 gap-2 ">
		<!--
			<div class="sm:col-start-2">
			<h4>Cootraesturz Ltda</h4>
			PBX: (57 1) 713 00 99 - (57 1) 713 01 00<br />
			info@cootraesturz.com<br />
			</div>
			<div>
			servicioacliente@cootraesturz.com<br>
			Diagonal 52A Sur # 29-58 <br>
			Bogotá - Colombia
		</div>

		-->
	
</div>

	<div class="w-full text-center "><small>2023 - Todos los derechos reservados</small></div>
</footer>
