<script lang="ts">
	import { onMount } from 'svelte';
	import { apiKey, userNow } from '../../store';
	import type { Product } from '$lib/types/Product';
	import { Circle3 } from 'svelte-loading-spinners';
	//import Messages from '$lib/components/Messages.svelte';
	import type { ProductOptions } from '$lib/types/ProductOptions';

	import type { Message } from '$lib/types/Message';

	export let m_show: boolean = false;
	export let message: Message;

	export let show_product: boolean;

	export let prod: Product;

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;

	const saveProd = async () => {
		//console.log("yy:" + prod.id);
		//await fetch(urlAPI + '?ref=save-prod', {
		await fetch(urlAPI + '?ref=save', {	
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				request: prod,
				folder: 'maker_products'
				//product: prod,
				//prod_position: prod_position
				//
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				console.log('cargado');
				console.log(result);
				
					//++++show_message("Save", "Save data", "message-green");
					//console.log("Muy Bien:"+result[0].ok);
					prod = result[0];
					
					message = {
						title: 'Guardar',
						text: 'Se guardó el producto ' + prod.product,
						class: 'message-green',
						accion: ''
					};
					m_show = true;
					//saveOption()
					//console.table(product);
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};

	let fileImage: FileList;

	const upload = async (position: number) => {
		//console.table(fileImage[0]);
		const dataArray = new FormData();
		dataArray.append('user_id', String($userNow.id));
		dataArray.append('time_life', String($userNow.user_time_life));
		dataArray.append('token', $userNow.token);
		dataArray.append('id', String(prod.id));
		dataArray.append('position', String(position));
		dataArray.append('uploadFile', fileImage[0]);

		await fetch(urlAPI + '?ref=upload&folder=maker_products&prefix=', {
			method: 'POST',
			body: dataArray
		})
			.then((response) => response.json())
			.then((result) => {
				// Successfully uploaded
				console.log('upload:');
				console.log(result);

				if (result[0].error) {
					message = {
						title: 'Error',
						text: 'Error: ' + result[0].error,
						class: 'message-red',
						accion: ''
					};
					m_show = true;
				} else {
					prod = result[0];
					message = {
						title: 'Cargar',
						text: 'Imágen cargada',
						class: 'message-green',
						accion: ''
					};
					m_show = true;
				}
			})
			.catch((error) => console.log(error.message));
	};

	let fileinput: any;
	let optionNow: ProductOptions;
	let fileinputPosition: number = 0;

	const uploadOption = async (e: any) => {
		let image = e.target.files[0];
		//console.table(fileinput);
		console.table(optionNow);
		//alert(image_id+'*'+image_position+'*'+category_list[image_position].position);
		//console.table(image);
		listOptions[fileinputPosition].image = 'load';
		const dataArray = new FormData();
		dataArray.append('user_id', String($userNow.id));
		dataArray.append('time_life', String($userNow.user_time_life));
		dataArray.append('token', $userNow.token);
		dataArray.append('id', String(optionNow.id));
		dataArray.append('position', String(optionNow.position));
		dataArray.append('uploadFile', image);
		dataArray.append('product_id', String(prod.id));
		dataArray.append('name', optionNow.name);
		dataArray.append('price', String(optionNow.price));
		dataArray.append('stock', String(optionNow.stock));
		dataArray.append('active', String(optionNow.active));
		await fetch(urlAPI + '?ref=upload&folder=maker_product_versions&prefix=', {
			method: 'POST',
			body: dataArray
		})
			.then((response) => response.json())
			.then((result) => {
				// Successfully uploaded
				console.log('uploadOption:');
				console.log(result);

				if (result[0].error) {
					message = {
						title: 'Error',
						text: 'Error: ' + result[0].error,
						class: 'message-red',
						accion: ''
					};
					m_show = true;
				} else {
					listOptions[fileinputPosition] = result[0];
					message = {
						title: 'Cargar',
						text: 'Archivo cargado',
						class: 'message-green',
						accion: ''
					};
					m_show = true;
				}
			})
			.catch((error) => {
				console.log('error upload option');
				console.log(error.message);
				listOptions[fileinputPosition].image = '';
			});
		// Upload failed
	};

	let listOptions: Array<ProductOptions> = [];

	const loadOptions = async (p: number) => {
		
		await fetch(
			urlAPI +
				'?ref=list&f=maker_product_versions&id=' +
				p +
				'&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('options load:');
				console.table(result);
				
					//console.log("Listado Category Muy Bien:");
					listOptions = result;
				
			})
			.catch((error) => console.log(error.message));
	};

	//$: loadOptions(prod.id);
	onMount(() => {
		loadOptions(prod.id);
	});

	let newOption: ProductOptions = {
		id: 0,
		company_id: $apiKey.companyId,
		product_id: prod.id,
		name: '',
		image: '',
		price: prod.price,
		stock: 1,
		position: 0,
		active: true
	};

	function addOption() {
		//menu_list.push(new_menu) ///esta opción no actuaiza el listado automáticamente
		newOption.id = Date.now();
		let fposition: number = listOptions.length;
		fposition = fposition + 1;
		newOption.position = fposition;
		newOption.price = prod.price;
		//menu_list.push(new_menu)
		listOptions = [...listOptions, newOption];
		newOption = {
			id: 0,
			company_id: $apiKey.companyId,
			product_id: prod.id,
			name: '',
			image: '',
			price: 0,
			stock: 1,
			position: 0,
			active: true
		};
		//console.log('nuevo')
		//show_message("Add Menu", "For save this, click the 'save' button before exiting", "message-green");
	}

	const deleteOption = (id: number) => {
		if (confirm('Borrar esta versión?')) {
			listOptions = listOptions.filter((item) => item.id != id);
			//mensaje("se borró la tarea", "text-bg-danger");
			message = {
				title: 'Borrar',
				text: 'Versión Borrada',
				class: 'message-red',
				accion: ''
			};
			m_show = true;

			saveOption();
		}

		//return menu_list;
	};

	const saveOption = () => {
		//alert('FF')
		console.log('enviando');
		console.table(listOptions);
		//fetch(urlAPI + '?ref=save-options', {
			fetch(urlAPI + '?ref=save-list&folder=maker_product_versions&campo=product_id&campo_id='+prod.id, {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				company_id: $apiKey.companyId,
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				list: listOptions
				//listOptions: listOptions
				//
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				
					listOptions = result;
					//console.log('recibiendo Form:')
					//console.table(result)
				
			});

		//.catch((error) => {console.log(error)})

		//  });
	};

	import Editor from '@tinymce/tinymce-svelte';
	let conf = {
		toolbar: 'h1 h2 h3 bold  italic forecolor aligncenter alignjustify alignleft undo redo ',
		menubar: false,
		height: 200,
		width: '100%'
	};
</script>

<!-- svelte-ignore a11y-click-events-have-key-events -->
<div class="bg-edit" on:click|self={()=>show_product = false}>
	<div class="edit-page">
		<h3>{prod.product}</h3>

		<div class="flex mt-3 bg-aliceblue p-3 rounded-lg">
			<button
				class="btn-green flex"
				on:click={() => {
					show_product = false;
					saveProd();
					saveOption()
				}}
			>
				<i class="fa fa-save mr-1 mt-1" />
				Guardar</button
			>
			<button
				class="ml-4 flex btn-red"
				on:click={() => {
					show_product = false;
				}}
			>
				<i class="fa fa-close mr-1 mt-1" />
				Cerrar</button
			>
		</div>

		<div class="xl:w-10/12 w-full ">
			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="text-xl font-medium pr-2 leading-5 text-dimgray">Imagenes</h1>
						</div>
						<p class="mt-4 text-sm leading-5 text-gray">Cargar imagenes</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								{#if prod.image1 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if prod.image1 != ''}
									<img src="{urlFiles}/images/maker_products/M{prod.image1}" alt={prod.product} />
									<br />
									<button
										class="btn-red flex"
										on:click={() => {
											prod.image1 = '';
										}}
									>
										<i class="fa fa-trash-o mr-2 mt-1" />
										Borrar
									</button>
								{/if}
								Principal Image JPG - PNG <br />
								<small>800 x 600 px</small>

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 1"
									bind:files={fileImage}
									on:change={() => {
										prod.image1 = 'load';
										upload(1);
									}}
								/>
							</div>

							<div class="md:w-64 mx-2">
								{#if prod.image2 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if prod.image2 != ''}
									<img src="{urlFiles}/images/maker_products/M{prod.image2}" alt={prod.product} />
									<br />
									<button
										class="btn-red flex"
										on:click={() => {
											prod.image2 = '';
										}}
									>
										<i class="fa fa-trash-o mr-2 mt-1" />
										Borrar
									</button>
								{/if}
								Image 2 JPG - PNG <br />
								<small>800 x 600 px</small>

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 2"
									bind:files={fileImage}
									on:change={() => {
										prod.image2 = 'load';
										upload(2);
									}}
								/>
							</div>
						</div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								{#if prod.image3 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if prod.image3 != ''}
									<img src="{urlFiles}/images/maker_products/M{prod.image3}" alt={prod.product} />
									<br />
									<button
										class="btn-red flex"
										on:click={() => {
											prod.image3 = '';
										}}
									>
										<i class="fa fa-trash-o mr-2 mt-1" />
										Borrar
									</button>
								{/if}
								Imagen 3 JPG - PNG <br />
								<small>800 x 600 px</small>

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 3"
									bind:files={fileImage}
									on:change={() => {
										prod.image3 = 'load';
										upload(3);
									}}
								/>
							</div>

							<div class="md:w-64 mx-2">
								{#if prod.image4 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if prod.image4 != ''}
									<img src="{urlFiles}/images/maker_products/M{prod.image4}" alt={prod.product} />
									<br />
									<button
										class="btn-red flex"
										on:click={() => {
											prod.image4 = '';
										}}
									>
										<i class="fa fa-trash-o mr-2 mt-1" />
										Borrar
									</button>
								{/if}
								Imagen 4 JPG - PNG <br />
								<small>800 x 600 px</small>

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 4"
									bind:files={fileImage}
									on:change={() => {
										prod.image4 = 'load';
										upload(4);
									}}
								/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="text-xl font-medium pr-2 leading-5 text-dimgray">Datos Basicos</h1>
						</div>
						<p class="mt-4 text-sm leading-5 text-gray">Producto, referencia and otros</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Producto <br />

								<input type="text" class="inputA" placeholder="Product" bind:value={prod.product} />
							</div>

							<div class="md:w-64 mx-2">
								Ref <br />

								<input type="text" class="inputA" placeholder="Ref" bind:value={prod.ref} />
							</div>
						</div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Tamaño<br />
								<input type="text" class="inputA" placeholder="Size" bind:value={prod.size} />
							</div>

							<div class="md:w-64 mx-2">
								Color<br />
								<input type="text" class="inputA" placeholder="Color" bind:value={prod.color} />
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="text-xl font-medium pr-2 leading-5 text-dimgray">Descripción</h1>
						</div>
						<p class="mt-4 text-sm leading-5 text-gray">Textos</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="w-full mx-2">
								Descripción Corta<br />
								<Editor
									apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
									bind:value={prod.description}
									{conf}
								/>
							</div>
							
						</div>
<div class="md:flex items-center lg:ml-24">
	<div class="w-full mx-2">
		Descripción Completa<br />
		<Editor
			apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
			bind:value={prod.description2}
			{conf}
		/>
	</div>
</div>
						
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="text-xl font-medium pr-2 leading-5 text-dimgray">Precio / Existencias</h1>
						</div>
						<p class="mt-4 text-sm leading-5 text-gray">Precio Base</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Precio<br />
								<input type="number" class="inputA" placeholder="Precio" bind:value={prod.price} />
							</div>
<div class="md:w-64 mx-2">
								Existencias<br />
								<input type="number" class="inputA" placeholder="Existencias" bind:value={prod.stock} />
							</div>
							
						</div>
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="text-xl font-medium pr-2 leading-5 text-dimgray">Diferentes Versiones</h1>
						</div>
						<p class="mt-4 text-sm leading-5 text-gray">para este Producto (como Tamaño o Color)</p>
						<div class="mt-4 text-sm leading-5 text-gray">
							<button class="btn-primary flex" on:click={addOption}>
								<i class="fa fa-plus mr-2 mt-1" />
								Agregar Nueva Versión</button
							>
						</div>
					</div>

					{#if listOptions.length > 0}
						<!--fields-->
						<div class="md:flex items-center w-8/12 ml-auto ">
							<table>
								<thead>
									<th scope="col" class="px-2 py-1" />
									<th scope="col" class="px-2 py-1"> Versión de:</th>
									<th scope="col" class="px-2 py-1"> Nombre</th>
									<th scope="col" class="px-2 py-1"> Imagen </th>
									<th scope="col" class="px-2 py-1"> Precio </th>
									<th scope="col" class="px-2 py-1"> Inventario </th>
									<th scope="col" class="px-2 py-1"> Posición </th>
									<th scope="col" class="px-4 py-1" />
								</thead>
								<tbody>
									{#each listOptions as option, i}
										<tr class="bg-white border-b hover:bg-aliceblue">
											<td class="font-bold">{i + 1}</td>
											<td>
												<input
													type="text"
													class="inputA w-20"
													bind:value={prod.options}
													placeholder="Ej: Tamaño,Color..."
												/>
											</td>
											<td
												><input
													type="text"
													class="inputA w-20"
													placeholder="Ej: XS,Rojo..."
													bind:value={option.name}
												/></td
											>
											<td class="">
												<!---->
												{#if option.image == 'load'}
													<Circle3 size="20" unit="px" duration="2s" />
												{:else if option.image != ''}
													<div class="relative">
														<img
															class="w-16 h-auto "
															src="{urlFiles}/images/maker_product_versions/M{option.image}"
															alt={option.name}
														/>
													
													</div>
												{/if}
												<div class="flex items-center ">
{#if option.image != ''}
<button
class="btn-min bg-red mr-2"
on:click={() => {
	option.image = '';
}}
>
<i class="fa fa-trash-o mx-1" />
</button>
{/if}


													<button
														class="btn-min bg-primary"
														on:click={() => {
															optionNow = option;
															fileinputPosition = i;
															fileinput.click();
														}}
													>
														<i class="fa fa-camera mx-1" />
														
													</button>
												</div>
												<!---->
											</td>
											<td class="text-center">
												<input
													type="number"
													min="1"
													max="99"
													class="inputA w-28"
													bind:value={option.price}
												/>
											</td>
											<td class="text-center">
												<input
													type="number"
													min="0"
													class="inputA w-12"
													bind:value={option.stock}
												/>
											</td>
											<td class="text-center">
												<input
													type="number"
													min="1"
													max="99"
													class="inputA w-12"
													bind:value={option.position}
												/>
											</td>

											<td>
												<button
													on:click={() => {
														deleteOption(option.id);
													}}
												>
													<i class="fa fa-trash-o mr-2 mt-1 text-red" />
												</button>
											</td>
										</tr>
									{/each}
								</tbody>
							</table>
						</div>
						<!--the end fields-->
					{/if}
				</div>
			</div>
		</div>

		<div class="flex mt-3 bg-aliceblue p-3 rounded-lg">
			<button
				class="btn-green flex"
				on:click={() => {
					show_product = false;
					saveProd();
					saveOption()
				}}
			>
				<i class="fa fa-save mr-2 mt-1" />
				Guardar</button
			>
			<button
				class="ml-4 flex btn-red"
				on:click={() => {
					show_product = false;
				}}
			>
				<i class="fa fa-close mr-2 mt-1" />
				Cerrar</button
			>
		</div>
	</div>
</div>

<input
	style="display:none"
	type="file"
	accept=".jpg, .jpeg, .png"
	on:change={(e) => {
		uploadOption(e);
	}}
	bind:this={fileinput}
/>
<!--
	<Messages bind:m_show bind:message />
-->

