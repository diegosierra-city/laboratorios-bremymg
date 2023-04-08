<script lang="ts">
	import type { Menu } from '$lib/types/Menu';
	import { onMount } from 'svelte/internal';
	import { cookie_info, apiKey, userNow } from '../../store';

	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	import AdminCont from './AdminCont.svelte';

	let submenu_show: boolean = false;
	let new_menu: Menu = {
		id: 0,
		menu_id: 0,
		menu: '',
		type: '',
		link: '',
		head: false,
		foot: false,
		side: false,
		position: 1,
		submenu: false,
		submenus: [],
		metadescription: '',
		metakeywords: ''
	};

	let menu_list: Array<Menu> = [];

	const urlAPI = $apiKey.urlAPI_Maker;
	//// GET
//alert('*'+urlAPI)
	onMount(async () => {
		await fetch(
			urlAPI +
				'?ref=menu-list&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					//console.log("Listado Menu Muy Bien:");
					menu_list = result;
				
			})
			.catch((error) => console.log(error.message));
	});

	const saveMenu = async () => {
		//console.log("guardando...");
		//// POST

		await fetch(urlAPI + '?ref=save-menu', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				menu: menu_list
				//password: pass,
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				
					message = {
						title: 'Save',
						text: 'Save succesfully',
						class: 'message-green',
						accion: ''
					};
					m_show = true;

					//console.log("Muy Bien:"+result[0].ok);
					menu_list = result;
				
			})

			.catch((error) => console.log(error.message));

		//  });
	};

	//$: console.log(menu_list);

	function add_menu() {
		//menu_list.push(new_menu) ///esta opción no actuaiza el listado automáticamente

		new_menu.id = Date.now();
		new_menu.position = menu_list.length + 1;

		console.log('Add:' + new_menu.position + '-');
		//menu_list.push(new_menu)
		menu_list = [...menu_list, new_menu];
		new_menu = {
			id: 0,
			menu_id: 0,
			menu: '',
			type: '',
			link: '',
			head: false,
			foot: false,
			side: false,
			position: 1,
			submenu: false,
			submenus: [],
			metadescription: '',
			metakeywords: ''
		};
		//console.log('nuevo')
		//show_message("Add Menu", "For save this, click the 'save' button before exiting", "message-green");
	}

	const deleteMenu = (id: number) => {
		if (confirm('Delete this Page?')) {
			menu_list = menu_list.filter((item) => item.id != id);
			//mensaje("se borró la tarea", "text-bg-danger");
			message = {
				title: 'Delete Menu',
				text: 'Information was deleted',
				class: 'message-red',
				accion: ''
			};
			m_show = true;

			saveMenu();
		}

		//return menu_list;
	};

	/// Edit Cont
	let show_cont: boolean = false;
	let cont_id: number = 0;
	let page_name: string;
	let page_type: string;

	const contMenu = (id: number, page: string, type: string) => {
		show_cont = true;
		cont_id = id;
		page_name = page;
		page_type = type;
	};

	let metaD: boolean = false;
	let metaK: boolean = false;
	let metaP: number = 0;

	function edit_meta(position: number) {
		metaP = position;
		metaD = !metaD;
	}
</script>

<svelte:head>
	<title>Admin Pages</title>
</svelte:head>

<div class="p-3 w-full mt-14 ">
	<div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
		<div class="flex">
			<button class="btn-green mr-2 flex" on:click={saveMenu}>
				<i class="fa fa-save mx-2 mt-1" />
				Save</button
			>
			<button class="btn-primary flex" on:click={add_menu}>
				<i class="fa fa-plus mx-2 mt-1" />
				Add New Page</button
			>
		</div>
		<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
			<thead class="text-xs text-white uppercase bg-primary dark:bg-gray-700 dark:text-gray-400">
				<th scope="col" class="px-4 py-2" />
				<th scope="col" class="px-4 py-2"> Page Name </th>
				<th scope="col" class="px-4 py-2"> Type </th>
				<th scope="col" class="px-4 py-2"> External URL </th>
				<th scope="col" class="px-4 py-2 text-center"> Header </th>
				<th scope="col" class="px-4 py-2 text-center"> Footer </th>
				<th scope="col" class="px-4 py-2 text-center"> Side </th>
				<th scope="col" class="px-4 py-2 text-center"> Position </th>
				{#if submenu_show}
					<th scope="col" class="px-4 py-2"> Submenu </th>
				{/if}

				<th scope="col" class="px-4 py-2 text-center"> SEO </th>
				<th scope="col" class="px-4 py-2 text-center"> EDIT </th> 
				<th scope="col" class="px-4 py-2" />
			</thead>
			<tbody>
				{#each menu_list as mn, i}
					<tr class="bg-white border-b hover:bg-aliceblue">
						<td class="font-bold">{i + 1}</td>
						<td>
							<input type="text" class="inputA" bind:value={mn.menu} />
						</td>
						<td>
							<select class="inputA" bind:value={mn.type}>
								<option value="">Select</option>
								<option value="Home">Home</option>
								<option value="Info">Info</option>
								<option value="Form">Form</option>
								<option value="Gallery">Gallery</option>
								<option value="News">News</option>
								<option value="Events">Events</option>
								<option value="Products">Products Here</option>
								<option value="Products Sub">Products Sub</option>
								<option value="External Link">External Link</option>
							</select>
						</td>
						<td>
							<input type="text" class="inputA" bind:value={mn.link} />
						</td>
						<td class="text-center"><input type="checkbox" bind:checked={mn.head} /></td>
						<td class="text-center"><input type="checkbox" bind:checked={mn.foot} /></td>
						<td class="text-center"><input type="checkbox" bind:checked={mn.side} value="1" /></td>

						<td class="text-center">
							<input type="number" min="1" max="99" class="inputA w-12" bind:value={mn.position} />
						</td>
						{#if submenu_show}
							<td class="text-center"><input type="checkbox" bind:checked={mn.submenu} /></td>
						{/if}

						<td class="flex pt-3 relative justify-center">
							<!--
							<svg class="h-5 w-5 text-primary cursor-pointer hover:black mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" on:click={() => {edit_meta('D',mn.id)}}>
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
							  </svg>
-->
							<button
								on:click={() => {
									edit_meta(mn.id);
								}}
							>
								<svg
									class="h-5 w-5 text-primary cursor-pointer hover:black mr-4"
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor"
									stroke-width="2"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
									/>
								</svg>
							</button>
						</td>
						<td class=" text-center">
							<button
								on:click={() => {
									contMenu(mn.id, mn.menu, mn.type);
								}}
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									class="h-5 w-5 text-green cursor-pointer hover:black"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor"
									stroke-width="2"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
									/>
								</svg>
							</button>
						</td>
						<td>
							<button
								on:click={() => {
									deleteMenu(mn.id);
								}}
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									class="h-5 w-5 text-red cursor-pointer hover:black"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor"
									stroke-width="2"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
									/></svg
								>
							</button>
						</td>
					</tr>
					{#if metaD == true && metaP == mn.id}
						<tr>
							<td colspan="4">
								<span class="font-bold">Meta Description:</span><br />
								<input type="text" class="inputA w-full" bind:value={mn.metadescription} />
							</td>
							<td colspan="8">
								<span class="font-bold">Meta Key Words:</span><br />
								<input type="text" class="inputA w-full" bind:value={mn.metakeywords} />
							</td>
						</tr>
					{/if}

					{#each mn.submenus as submn, i}
						<tr class="bg-white border-b hover:bg-aliceblue">
							<td class="font-bold">{i + 1}</td>
							<td>
								<input type="text" class="inputA" bind:value={submn.menu} />
							</td>
							<td>
								<select class="inputA" bind:value={submn.type}>
									<option value="">Select</option>
									<option value="Home">Home</option>
									<option value="Info">Info</option>
									<option value="Form">Form</option>
									<option value="Gallery">Gallery</option>
									<option value="News">News</option>
									<option value="Events">Events</option>
									<option value="Products">Products Here</option>
									<option value="Products Sub">Products Sub</option>
									<option value="External Link">External Link</option>
								</select>
							</td>
							<td>
								<input type="text" class="inputA" bind:value={submn.link} />
							</td>
							<td class="text-center"><input type="checkbox" bind:checked={submn.head} /></td>
							<td class="text-center"><input type="checkbox" bind:checked={submn.foot} /></td>
							<td class="text-center"
								><input type="checkbox" bind:checked={submn.side} value="1" /></td
							>

							<td class="text-center">
								<input
									type="number"
									min="1"
									max="99"
									class="inputA w-12"
									bind:value={submn.position}
								/>
							</td>
							<td class="text-center"><input type="checkbox" bind:checked={submn.submenu} /></td>
							<td>
								<svg
									class="h-4 w-4 text-green cursor-pointer hover:black mr-4"
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke="currentColor"
									stroke-width="2"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
									/>
								</svg>
							</td>
							<td>
								<button
									on:click={() => {
										contMenu(submn.id, submn.menu, submn.type);
									}}
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										class="h-4 w-4 text-green cursor-pointer hover:black"
										fill="none"
										viewBox="0 0 24 24"
										stroke="currentColor"
										stroke-width="2"
									>
										<path
											stroke-linecap="round"
											stroke-linejoin="round"
											d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
										/>
									</svg>
								</button>
							</td>
							<td>
								<button
									on:click={() => {
										deleteMenu(submn.id);
									}}
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										class="h-4 w-4 text-red cursor-pointer hover:black"
										fill="none"
										viewBox="0 0 24 24"
										stroke="currentColor"
										stroke-width="2"
									>
										<path
											stroke-linecap="round"
											stroke-linejoin="round"
											d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
										/></svg
									>
								</button>
							</td>
						</tr>
					{:else}
						<!--submenu 0-->
					{/each}
					<!---->
				{:else}
					Please Add the First Menu
				{/each}
			</tbody>
		</table>
	</div>
</div>

{#if cont_id > 0 && show_cont == true}
	<AdminCont bind:show_cont {cont_id} {page_name} {page_type} bind:m_show bind:message />
{/if}

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
