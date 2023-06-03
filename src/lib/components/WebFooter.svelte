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

<footer class="relative top-28 sm:top-30 border-t-2 border-t-black">
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
					<a href="https://www.facebook.com/profile.php?id=100091299960286" target="_blank" rel="noopener noreferrer">
						<i class="fa-brands fa-facebook text-lg"></i> Facebook</a>
			</li>
<li>
	<a
				href="https://instagram.com/kdarcosmetics?igshid=NTc4MTIwNjQ2YQ=="
				target="_blank"
				rel="noopener noreferrer"
			>
			<i class="fa-brands fa-instagram text-lg"></i> Instagram</a>
</li>
		<li>
	<a
				href="https://www.tiktok.com/@kdarcosmetics?_t=8cD9eguMrPK&_r=1"
				target="_blank"
				rel="noopener noreferrer"
			>
			<i class="fa-brands fa-tiktok text-lg"></i> TikTok</a>
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



	<div class="w-full text-center "><small>KDAR Cosmetics 2023 - Todos los derechos reservados</small></div>
</footer>
