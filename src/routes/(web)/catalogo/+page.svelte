<script lang="ts">
import { page } from '$app/stores';
	import { onMount } from 'svelte';

	import { apiKey, cookie_info, cookie_update } from '../../../store';
	import { fade, fly } from 'svelte/transition';
	import type { Item } from '$lib/types/ListItem';
	import type { Product } from '$lib/types/Product';
import type {Category} from '$lib/types/Category'

	import Messages from '$lib/components/Messages.svelte';
	import type { Message } from '$lib/types/Message';
	import WebCarrousel from '$lib/components/WebCarrousel.svelte';
	import WebMenuB from '$lib/components/WebMenuB.svelte';
	import WebGalleryB from '$lib/components/WebGalleryB.svelte';
	import WebGalleryA from '$lib/components/WebGalleryA.svelte';
	import WebFooter from '$lib/components/WebFooter.svelte';
	import WebList from '$lib/components/WebList.svelte';

	let m_show: boolean = false;
	let message: Message;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId;
	const company_name = $apiKey.companyName;
	const tokenWeb = $apiKey.token;

	let listItems: Array<Item> = [];
	let listCategories: Array<Category> = [];
 let listProducts: Array<Product> = []; 
	
	const loadList = async (name: string) => {
		console.log('categoria:' + name);
		console.log(
			urlAPI +
				'?ref=load-list-Web&folder=maker_products' +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		);
		await fetch(
			urlAPI +
				'?ref=load-list-Web&folder=maker_products' +
				'&company_id=' +
				company_id +
				'&tokenWeb=' +
				tokenWeb
		)
			.then((response) => response.json())
			.then((result) => {
				listProducts = result[0];
				console.log('prodt');
				listCategories = result[0];
				listProducts = result[1];
				
			})
			.catch((error) => console.log(error.message));
	};

	onMount(() => {
		loadList($page.params.name);
	});

	$: loadList($page.params.name);
	//console.table($page.params)

	$: innerWidth = 0;
	$: innerHeight = 0;
	$: scrollY = 0;

	let prefixFolder: string = '';

	$: console.log('Ancho: ' + innerWidth);
	const movil = (w: number) => {
		if (w > 900) {
			//800
			prefixFolder = '';
		} else {
			prefixFolder = 'M';
		}
	};

	$: movil(innerWidth);


</script>

<svelte:head>
	<title>Catalogo</title>
	<!-- <link rel="stylesheet" href="../css/fontawesome-free-6.4.0-web/css/all.css" /> -->
</svelte:head>


<div class="w-11/12 md:w-10/12 mx-auto">
 <!-- <div class="flex bg-primary text-white niconne align-middle items-center rounded-t-xl overflow-hidden">
<img src="./logoKdar.jpg" alt="" class="w-32">
<h2 class="pl-8">Bienvenido a Kdar Cosmetics</h2>
 </div> -->

<div class="flex align-middle items-center justify-center">
<img src="../logoKdar.jpg" alt="" class="w-32 rounded-xl"><br>
 
</div>
<h2 class="text-center w-full">Catálogo</h2>
 <!--iconos social-->
 <div class="flex align-middle center justify-center gap-8 mt-4">
  <div>
   <a href="https://www.facebook.com/profile.php?id=100091299960286" target="_blank" rel="noopener noreferrer" class="link-general">
    <i class="fa-brands fa-facebook text-4xl"></i></a>
 </div>
<div>
<a
  href="https://instagram.com/kdarcosmetics?igshid=NTc4MTIwNjQ2YQ=="
  target="_blank"
  rel="noopener noreferrer" class="link-general"
 >
 <i class="fa-brands fa-instagram text-4xl"></i></a>
</div>
<div>
<a
  href="https://www.tiktok.com/@kdarcosmetics?_t=8cD9eguMrPK&_r=1"
  target="_blank"
  rel="noopener noreferrer" class="link-general"
 >
 <i class="fa-brands fa-tiktok text-4xl"></i></a>
</div>
 </div>
 <!---->

 <!--Los Accesos-->

 <section>
  <div class="w-11/12 md:w-10/12 mx-auto">
   <WebList bind:listItems {listProducts} {urlFiles} />
  </div>
 </section>
 
</div>