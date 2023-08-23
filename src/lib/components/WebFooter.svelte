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

<footer class="relative top-28 sm:top-30 border-t-2 border-t-black pt-8">
	<!--
{$page.url.pathname}     
-->
	<div class="grid grid-cols-2 mb-3 sm:grid-cols-3 gap-2 ">
		<div class="grid place-content-center place-items-center">
			
<!-- <img src="/maker-files/images/logo-LaboratoriosBremymg-200.png" class="logo_down md:mr-4" alt="logo Bremymg" /> -->
		<h3>¡SÍGUENOS!</h3>
		<div class="flex mt-4">
<img src="/maker-files/images/icon-Instagram.png" alt=""> <h3 class="mt-4 ml-4">@BREMYMG</h3>
		</div>
			
		</div>

		<div >
			<div class="flex mt-4">
				<img src="/maker-files/images/icon-Phone.png" alt="" style="width:51px; height:53px"> 
				<div class="pl-4">
				<h3 class="mt-4">TELÉFONOS:</h3>	
				<h3>601 369 04 75 | 329 51 60 <br />
					329 51 62 | 329 51 63 <br /> 
					329 51 64</h3>
				</div>
				
						</div>
			<!-- <ul>
				<li>
					<a href="" target="_blank" rel="noopener noreferrer">
						<i class="fa-brands fa-facebook text-lg"></i> Facebook</a>
			</li>
<li>
	<a
				href=""
				target="_blank"
				rel="noopener noreferrer"
			>
			<i class="fa-brands fa-instagram text-lg"></i> Instagram</a>
</li>
		<li>
	<a
				href=""
				target="_blank"
				rel="noopener noreferrer"
			>
			<i class="fa-brands fa-tiktok text-lg"></i> TikTok</a>
</li>		
			</ul> -->
					
		</div>

		<div>
			<div class="flex mt-4">
				<img src="/maker-files/images/icon-Celular.png" alt="" style="width:51px; height:53px"> 
				<div class="pl-4">
				<h3 class="mt-4">CELULAR:</h3>	
				<h3>(+57) 313 328 57 38</h3>
				</div>
				
						</div>			
		</div>

		
		
	</div>


<div class="flex justify-between w-full footer-down mt-8">
	{#each listMenu as menu}
						
	<a data-sveltekit-preload-data href={menu.link}>{menu.menu.toUpperCase()}</a>

{:else}
cargando...
{/each}
<a href="https://gmarketingypublicidad.com/" target="_blank" rel="noopener noreferrer">BY G MARKETING Y PUBLICIDAD</a>
</div>
	
</footer>
