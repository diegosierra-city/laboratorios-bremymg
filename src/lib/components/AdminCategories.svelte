<script lang="ts">
	import type { Category } from '../types/Category';
	import { onMount } from 'svelte/internal';
	import { apiKey, userNow } from '../../store';
	import { Circle3 } from 'svelte-loading-spinners';
	import AdminProducts from '$lib/components/AdminProducts.svelte';

	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	//
	let show_products: boolean = false;
	let category_id: number;
	let category_name: string;
	let new_category: Category;
	let categoryN: Category = {
		id: 0,
		category: '',
		description: '',
		position: 1,
		image: '',
		active: true
	};
	new_category = categoryN;

	let category_list: Array<Category> = [];

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	onMount(async () => {
		await fetch(
			urlAPI +
				'?ref=category-list&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					//console.log("Listado Category Muy Bien:");
					category_list = result;
				
			})
			.catch((error) => console.log(error.message));
	});

	const saveCategory = async () => {
		//console.log("yy");
		//// POST

		await fetch(urlAPI + '?ref=save-category', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				categories: category_list
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
						text: 'Save data',
						class: 'message-green',
						accion: ''
					};
					m_show = true;

					//console.log("Muy Bien:"+result[0].ok);
					category_list = result;
					//new_user = result[0]
					//cookie_update('user',JSON.stringify(new_user))
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};

	//$: console.log(category_list);

	function add_category() {
		//category_list.push(new_category) ///esta opción no actuaiza el listado automáticamente
		new_category.id = Date.now();
		new_category.position = (category_list.length+1);
		//category_list.push(new_category)
		category_list = [...category_list, new_category];
		new_category = {
		id: 0,
		category: '',
		description: '',
		position: 1,
		image: '',
		active: true
	}
		//console.log('nuevo')
		//show_message("Add Category", "Information was saved", "message-green");
	}

	const deleteCategory = (id: number) => {
		if (confirm('Delete this Category?')) {
			category_list = category_list.filter((item) => item.id != id);
			//mensaje("se borró la tarea", "text-bg-danger");
			message = {
				title: 'Delete Category',
				text: 'Information was deleted',
				class: 'message-red',
				accion: ''
			};
			m_show = true;

			saveCategory();
			//return category_list;
		}
	};

	let fileinput: any;
	let image_id: number = 0;
	let image_position: number = 0;

	//function upload(id: number, position: number) {
	const upload = (e: any) => {
		//console.log('FF:'+image_id)
		if (image_id > 1000000) {
			message = {
				title: 'Error',
				text: 'First save this Category',
				class: 'message-red',
				accion: ''
			};
			m_show = true;
		} else {
			
			let image = e.target.files[0];
			//console.table(fileinput);
			//alert(image_id+'*'+image_position+'*'+category_list[image_position].position);
			//console.table(image);
			
			const dataArray = new FormData();
			dataArray.append('user_id', String($userNow.id));
			dataArray.append('time_life', String($userNow.user_time_life));
			dataArray.append('token', $userNow.token);
			dataArray.append('id', String(image_id));
			dataArray.append('position', '');
			dataArray.append('uploadFile', image);

			fetch(urlAPI + '?ref=upload&folder=maker_products&prefix=C', {
				method: 'POST',
				body: dataArray
			})
				.then((response) => response.json())
				.then((result) => {
					// Successfully uploaded
					//console.log("RRRY:");
					//console.table(result);

						category_list[image_position] = result[0];
						message = {
							title: 'Upload',
							text: 'File uploaded',
							class: 'message-green',
							accion: ''
						};
						m_show = true;
					
				})
				.catch((error) => console.log(error.message));
		}
	};
</script>

<svelte:head>
	<title>Maker: Categories</title>
</svelte:head>

{#if show_products == false}
	<div class="p-3 w-full mt-14 ">
		<h3>Categories</h3>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="flex">
				<button class="btn-green mr-2 flex" on:click={saveCategory}>
					<i class="fa fa-save mx-2 mt-1" />
					Save</button
				>
				<button class="btn-primary flex" on:click={add_category}>
					<i class="fa fa-plus mx-2 mt-1" />
					Add Category</button
				>
			</div>
			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
				<thead class="text-xs text-white uppercase bg-primary dark:bg-gray-700 dark:text-gray-400">
					<th scope="col" class="px-4 py-2" />
					<th scope="col" class="px-4 py-2"> Category</th>
					<th scope="col" class="px-4 py-2"> Description</th>
					<th scope="col" class="px-4 py-2 text-center"> Position </th>
					<th scope="col" class="px-4 py-2 text-center">
					<div>Image JPG-PNG</div>	 <small>1600-600px</small></th
					>
					<th scope="col" class="px-4 py-2 text-center"> Active </th>
					<th scope="col" class="px-4 py-2 text-center"> Products </th>
					<th scope="col" class="px-4 py-2" />
				</thead>
				<tbody>
					{#each category_list as ct, i}
						<tr class="bg-white border-b hover:bg-aliceblue">
							<td class="text-bold">{i + 1}</td>
							<td>
								<input type="text" class="inputA" bind:value={ct.category} />
							</td>
							<td>
								<input type="text" class="inputA" bind:value={ct.description} />
							</td>
							<td class="text-center">
								<input
									type="number"
									min="1"
									max="99"
									class="inputA w-12"
									bind:value={ct.position}
								/>
							</td>
							<td class="">
								<div class="flex justify-center ">
									{#if ct.image == 'load'}
										<Circle3 size="20" unit="px" duration="2s" />
									{:else if ct.image != ''}
										<div class="flex items-center">
											<img
												class="w-16 h-auto mr-2"
												src="{urlFiles}/images/maker_products/M{ct.image}"
												alt={ct.category}
											/>

											<button
												class="btn-min bg-red mr-2 flex"
												on:click={() => {
													ct.image = '';
												}}
											>
											<i class="fa fa-trash-o m-1" />
											</button
											>
										</div>
									{/if}
									<div class="flex items-center">
										<button
											class="btn-min bg-primary"
											on:click={() => {
												if(ct.id>1000000){
													message = {
				title: 'Error',
				text: 'First save this Category',
				class: 'message-red',
				accion: ''
			};
			m_show = true;	
												}else{
												image_id = ct.id;
												image_position = i;
												fileinput.click();
												ct.image = 'load';	
												}
												
											}}
										>
										<i class="fa fa-camera m-1" />
										
										</button>
									</div>
								</div>
							</td>
							<td class="text-center"><input type="checkbox" bind:checked={ct.active} /></td>

							<td class="text-center">
								<button
									on:click={() => {
										category_id = ct.id;
										category_name = ct.category;
										show_products = true;
									}}
								>
								<i class="fa fa-edit mx-2 mt-1 text-green text-lg" />
								</button>
							</td>
							<td>
								<button
									on:click={() => {
										deleteCategory(ct.id);
									}}
								>
								<i class="fa fa-trash-o mx-2 mt-1 text-red text-lg" />
								</button>
							</td>
						</tr>

						<!---->
					{:else}
						Please Add the First Category
					{/each}
				</tbody>
			</table>
			<input
				style="display:none"
				type="file"
				accept=".jpg, .jpeg, .png"
				on:change={(e) => {
					upload(e);
				}}
				bind:this={fileinput}
			/>
		</div>
	</div>
{:else}
	<AdminProducts {category_id} {category_name} bind:show_products />
{/if}

<Messages bind:m_show bind:message />
