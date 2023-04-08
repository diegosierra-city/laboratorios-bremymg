<script lang="ts">
	import { apiKey, userNow } from '../../store';
	import { Circle3 } from 'svelte-loading-spinners';
	import type { BlockContent } from '$lib/types/BlockContent';
	import type { Form } from '$lib/types/Form';
	import { onMount } from 'svelte/internal';

	//import Messages from '$lib/components/Messages.svelte';

	//import type { Message } from '$lib/types/Message';
	import type { Gallery } from '$lib/types/Gallery';

	export let m_show: boolean = false;
	export let message;

	export let show_cont: boolean;
	export let cont_id: number;
	export let page_name: string;
	export let page_type: string;

	let content: BlockContent = {
		id: 0,
		menu_id: cont_id,
		title: '',
		subtitle: '',
		text1: '',
		text2: '',
		text3: '',
		text4: '',
		image1: '',
		image2: '',
		image3: '',
		image4: '',
		image_text1: '',
		image_text2: '',
		image_text3: '',
		image_text4: '',
		image_link1: '',
		image_link2: '',
		image_link3: '',
		image_link4: '',
		video: '',
		position: 1,
		link: ''
	};

	let listGalleries: Array<Gallery> = [];

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;

	const loadContent = async (id: number) => {
		console.log('contenido:' + id);

		console.log(
			urlAPI +
				'?ref=loadID&folder=maker_content_blocks&id=' +
				id +
				'&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		);
		/**/
		await fetch(
			urlAPI +
				'?ref=loadID&folder=maker_content_blocks&id=' +
				id +
				'&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('Nuevo Contenido:');
				console.table(result);
				
				content = result[0];

				if (page_type == 'Gallery') {
					loadGallery(result.id);
				}
			})
			.catch((error) => console.log(error.message));
	};

	const loadGallery = async (id: number) => {
		console.log('gallery:' + id);

		console.log(
			urlAPI +
				'?ref=load-listGallery&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token +
				'&id=' +
				id
		);
		/**/
		await fetch(
			urlAPI +
				'?ref=load-listGallery&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token +
				'&id=' +
				id
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('Nuevas Galleries:');
				console.log(result);
				
				listGalleries = result;
			})
			.catch((error) => console.log(error.message));
	};

	let newGallery: Gallery = {
		id: 0,
		content_id: cont_id,
		image: '',
		title: '',
		description: '',
		position: 0
	};

	function addGallery() {
		//menu_list.push(new_menu) ///esta opción no actuaiza el listado automáticamente
		newGallery.id = Date.now();
		newGallery.position = listGalleries.length + 1;
		//menu_list.push(new_menu)
		listGalleries = [...listGalleries, newGallery];
		newGallery = {
			id: 0,
			content_id: cont_id,
			image: '',
			title: '',
			description: '',
			position: 0
		};
		//console.log('nuevo')
		//show_message("Add Menu", "For save this, click the 'save' button before exiting", "message-green");
	}

	const saveCont = async (id: number) => {
		//console.log("yy:" + content.id);
		console.log(content)
		if (id > 0) {
			await fetch(urlAPI + '?ref=save', {
				method: 'POST', //POST - PUT - DELETE
				body: JSON.stringify({
					user_id: $userNow.id,
					time_life: $userNow.user_time_life,
					token: $userNow.token,
					cont_id: id,
					request: content,
					folder: 'maker_content_blocks'
					//
				}),
				headers: {
					'Content-type': 'application/json; charset=UTF-8'
				}
			})
				.then((response) => response.json())
				//.then(result => console.log(result))
				.then((result) => {
					
						//++++show_message("Save", "Save data", "message-green");
						//console.log("Muy Bien:"+result[0].ok);
						console.log('save cont')
						console.log(result)
						//content = result[0];

						message = {
							title: 'Save',
							text: 'Save data',
							class: 'message-green',
							accion: ''
						};
						m_show = true;

						show_cont = false;
					
				})
				.catch((error) => console.error(error));

			//  });
		}
	};

	let fileImage: FileList;

	function upload(id: number, position: number) {
		//console.log('PP')
		//console.table(fileImage[0]);
		const dataArray = new FormData();
		dataArray.append('user_id', String($userNow.id));
		dataArray.append('time_life', String($userNow.user_time_life));
		dataArray.append('token', $userNow.token);
		dataArray.append('id', String(id));
		dataArray.append('position', String(position));
		dataArray.append('uploadFile', fileImage[0]);
		//console.log(urlAPI + '?ref=upload&folder=maker_pages&prefix=')
		//console.log(dataArray)

		fetch(urlAPI + '?ref=upload&folder=maker_pages&prefix=', {
			method: 'POST',
			body: dataArray
		})
			.then((response) => response.json())
			.then((result) => {
				// Successfully uploaded
				console.log('upload:');
				console.table(result[0]);

					content = result[0];
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

	function uploadGallery(g: Gallery, position: number) {
		console.log('GG');
		//console.table(fileImage[0]);
		const dataArray = new FormData();
		dataArray.append('user_id', String($userNow.id));
		dataArray.append('time_life', String($userNow.user_time_life));
		dataArray.append('token', $userNow.token);
		dataArray.append('id', String(g.id));
		dataArray.append('content_id', String(content.id));
		dataArray.append('position', String(g.position));
		dataArray.append('title', String(g.title));
		dataArray.append('description', String(g.description));
		dataArray.append('uploadFile', fileImage[0]);

		fetch(urlAPI + '?ref=uploadmaker_gallery', {
			method: 'POST',
			body: dataArray
		})
			.then((response) => response.json())
			.then((result) => {
				// Successfully uploaded
				console.log('upload Gallery:');
				console.log(result);

				
					listGalleries[position] = result[0];
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

	const deleteGallery = (id: number) => {
		if (confirm('Delete this Item?')) {
			listGalleries = listGalleries.filter((item) => item.id != id);
			//mensaje("se borró la tarea", "text-bg-danger");
			message = {
				title: 'Delete Item',
				text: 'Information was deleted',
				class: 'message-red',
				accion: ''
			};
			m_show = true;

			saveGallery();
		}

		//return menu_list;
	};

	const saveGallery = async () => {
		//alert('FF')
		console.log('enviando gallery');

		await fetch(urlAPI + '?ref=save-gallery', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				listGalleries: listGalleries
				//
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				
					listGalleries = result;
					//console.log('recibiendo Form:')
					//console.table(result)
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};

	content = {
		id: 1000000,
		menu_id: cont_id,
		title: '',
		subtitle: '',
		text1: '',
		text2: '',
		text3: '',
		text4: '',
		image1: '',
		image2: '',
		image3: '',
		image4: '',
		image_text1: '',
		image_text2: '',
		image_text3: '',
		image_text4: '',
		image_link1: '',
		image_link2: '',
		image_link3: '',
		image_link4: '',
		video: '',
		position: 1,
		link: ''
	};

	$: loadContent(cont_id);

	//$: console.log(content);
	let time = Date.now();

	let listForm: Array<Form>;
	$: listForm = [
		{
			id: time,
			menu_id: cont_id,
			name: 'Name',
			type: 'text',
			required: true,
			response: '',
			position: 1
		},
		{
			id: time + 1,
			menu_id: cont_id,
			name: 'Email',
			type: 'email',
			required: true,
			response: '',
			position: 2
		},
		{
			id: time + 2,
			menu_id: cont_id,
			name: 'Phone',
			type: 'phone', //text-longtext-number-email-phone-date-checkbox
			required: true,
			response: '',
			position: 3
		}
	];

	let newForm: Form = {
		id: 0,
			menu_id: cont_id,
			name: '',
			type: 'text',
			required: true,
			response: '',
			position: 0
	}
	

	function add_form() {
		//menu_list.push(new_menu) ///esta opción no actuaiza el listado automáticamente
		newForm.id = Date.now();
		let fposition: number = listForm.length;
		fposition = fposition + 1;
		newForm.position = fposition;
		//menu_list.push(new_menu)
		listForm = [...listForm, newForm];
		newForm = {
			id: 0,
			menu_id: cont_id,
			name: '',
			type: 'text',
			required: true,
			response: '',
			position: 0
		};
		//console.log('nuevo')
		//show_message("Add Menu", "For save this, click the 'save' button before exiting", "message-green");
	}

	const deleteForm = (position: number) => {
		if (confirm('Delete this Field?')) {
			listForm = listForm.filter((item) => item.position != position);
			//mensaje("se borró la tarea", "text-bg-danger");
			message = {
				title: 'Delete Field',
				text: 'Information was deleted',
				class: 'message-red',
				accion: ''
			};
			m_show = true;

			saveForm();
		}

		//return menu_list;
	};

	const saveForm = async () => {
		//alert('FF')
		console.log('enviando');
		console.table(listForm);
		await fetch(urlAPI + '?ref=save-form', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				listForm: listForm
				//
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				
					listForm = result;
					//console.log('recibiendo Form:')
					//console.table(result)
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};

	//alert('C'+cont_id+':'+urlAPI)
	//$: const load_form = (menu_id: number) =>{
	///+++++onMount(async () => {
	const loadForm = async (menu_id: number) => {
		console.log(
			urlAPI +
							'?ref=form-list&user_id=' +
							$userNow.id +
							'&time=' +
							$userNow.user_time_life +
							'&token=' +
							$userNow.token +
							'&menu_id=' +
							menu_id
					);
		await fetch(
			urlAPI +
				'?ref=form-list&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token +
				'&menu_id=' +
				menu_id
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('recibiendo formulario:');
				//console.table(result);
							
					console.log(result);
					//alert(cont_id+':'+result.length)
					if (result.length > 0) {
					listForm = result;
					}
				
			})
			.catch((error) => console.log(error.message));
		//});
		//}

		//load_form (cont_id)
	};

	$: if (page_type == 'Form' && cont_id != 0) {
		onMount(()=>{
			loadForm(cont_id);
		})
		
	}

	import Editor from '@tinymce/tinymce-svelte';
	let conf = {
		toolbar: 'h1 h2 h3 bold  italic forecolor aligncenter alignjustify alignleft undo redo ',
		menubar: false,
		height: 200,
		width: '100%'
	};
</script>

<div class="bg-edit">
	<div class="edit-page">
		<h3 class="text-primary">Page: {page_name}</h3>

		<div class="flex mt-3 bg-aliceblue p-3 rounded-lg">
			{#if page_type == 'Form'}
				<button
					class="btn-green flex"
					on:click={() => {
						saveCont(content.id);
						saveForm();
					}}
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 mr-1"
						viewBox="0 0 20 20"
						fill="currentColor"
					>
						<path
							fill-rule="evenodd"
							d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
							clip-rule="evenodd"
						/>
					</svg>
					Save</button
				>
			{:else if page_type == 'Gallery'}
				<button
					class="btn-green flex"
					on:click={() => {
						saveCont(content.id);
						saveGallery();
					}}
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 mr-1"
						viewBox="0 0 20 20"
						fill="currentColor"
					>
						<path
							fill-rule="evenodd"
							d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
							clip-rule="evenodd"
						/>
					</svg>
					Save</button
				>
			{:else}
				<button
					class="btn-green flex"
					on:click={() => {
						show_cont = false;
						saveCont(content.id);
					}}
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 mr-1"
						viewBox="0 0 20 20"
						fill="currentColor"
					>
						<path
							fill-rule="evenodd"
							d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
							clip-rule="evenodd"
						/>
					</svg>
					Save</button
				>
			{/if}
			<button
				class="ml-4 flex btn-red"
				on:click={() => {
					show_cont = false;
				}}
			>
				<svg
					xmlns="http://www.w3.org/2000/svg"
					class="h-5 w-5 mr-1"
					viewBox="0 0 20 20"
					fill="currentColor"
				>
					<path
						fill-rule="evenodd"
						d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
						clip-rule="evenodd"
					/>
				</svg>
				Cancel</button
			>
		</div>

		<div class="focus:outline-none xl:w-10/12 w-full ">
			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray">
								Images
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">Upload the images</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 m-2">
								
								{#if content.image1 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if content.image1 != ''}
									<img src="{urlFiles}/images/maker_pages/M{content.image1}" alt={page_name} /> <br />
									<button
										class="btn-red flex"
										on:click={() => {
											content.image1 = '';
										}}
									>
										<i class="fa fa-trash-o mx-2 mt-1" />
										Delete
									</button>
								{/if}
								Image 1 JPG - PNG <br />
								{#if page_type == 'News/Events'}
									<small>800 x 600 px</small>
								{:else}
									<small>1600 x 600 px</small>
								{/if}

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 1"
									bind:files={fileImage}
									on:change={() => {
										content.image1 = 'load';
										upload(content.id, 1);
									}}
								/>
								<h5>Text:</h5>
								<input type="text" class="inputA" bind:value={content.image_text1} />
								<h5>link:</h5>
								<input type="text" class="inputA" bind:value={content.image_link1} />
							</div>

							<div class="md:w-64 mx-2">
								{#if content.image2 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if content.image2 != ''}
									<img src="{urlFiles}/images/maker_pages/M{content.image2}" alt={page_name} /> <br />
									<button
										class="btn-red flex"
										on:click={() => {
											content.image2 = '';
										}}
									>
										<i class="fa fa-trash-o" />
										Delete
									</button>
								{/if}
								Image 2 JPG - PNG <br />
								{#if page_type == 'News/Events'}
									<small>800 x 600 px</small>
								{:else}
									<small>1600 x 600 px</small>
								{/if}

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 2"
									bind:files={fileImage}
									on:change={() => {
										content.image2 = 'load';
										upload(content.id, 2);
									}}
								/>
								<h5>Text:</h5>
								<input type="text" class="inputA" bind:value={content.image_text2} />
								<h5>link:</h5>
								<input type="text" class="inputA" bind:value={content.image_link2} />
							</div>
						</div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								{#if content.image3 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if content.image3 != ''}
									<img src="{urlFiles}/images/maker_pages/M{content.image3}" alt={page_name} /> <br />
									<button
										class="btn-red flex"
										on:click={() => {
											content.image3 = '';
										}}
									>
									<i class="fa fa-trash-o" />
										Delete
									</button>
								{/if}
								Image 3 JPG - PNG <br />
								{#if page_type == 'News/Events'}
									<small>800 x 600 px</small>
								{:else}
									<small>1600 x 600 px</small>
								{/if}

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 3"
									bind:files={fileImage}
									on:change={() => {
										content.image3 = 'load';
										upload(content.id, 3);
									}}
								/>
								<h5>Text:</h5>
								<input type="text" class="inputA" bind:value={content.image_text3} />
								<h5>link:</h5>
								<input type="text" class="inputA" bind:value={content.image_link3} />
							</div>

							<div class="md:w-64 mx-2">
								{#if content.image4 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if content.image4 != ''}
									<img src="{urlFiles}/images/maker_pages/M{content.image4}" alt={page_name} /> <br />
									<button
										class="btn-red flex"
										on:click={() => {
											content.image4 = '';
										}}
									>
									<i class="fa fa-trash-o" />
										Delete
									</button>
								{/if}
								Image 4 JPG - PNG <br />
								{#if page_type == 'News/Events'}
									<small>800 x 600 px</small>
								{:else}
									<small>1600 x 600 px</small>
								{/if}

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Image 4"
									bind:files={fileImage}
									on:change={() => {
										content.image4 = 'load';
										upload(content.id, 4);
									}}
								/>
								<h5>Text:</h5>
								<input type="text" class="inputA" bind:value={content.image_text4} />
								<h5>link:</h5>
								<input type="text" class="inputA" bind:value={content.image_link4} />
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray">
								Title - Links
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
							Titles, Video and External Links
						</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Title <br />

								<input type="text" class="inputA" placeholder="Title" bind:value={content.title} />
							</div>

							<div class="md:w-64 mx-2">
								Sub Title <br />

								<input
									type="text"
									class="inputA"
									placeholder="Subtitle"
									bind:value={content.subtitle}
								/>
							</div>
						</div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Code Video Youtube <small>(only code)</small><br />
								<input
									type="text"
									class="inputA"
									placeholder="CODE Youtube"
									bind:value={content.video}
								/>
							</div>

							<div class="md:w-64 mx-2">
								{#if page_type != 'Form'}
									URL External Link<br />
									<input
										type="text"
										class="inputA"
										placeholder="URL External Link"
										bind:value={content.link}
									/>
								{:else}
									Send Copy to Email: <br />
									<input
										type="email"
										class="inputA"
										placeholder="Send Copy to Email"
										bind:value={content.link}
									/>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="xl:px-8">
				<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
					<div class="w-80">
						<div class="flex items-center">
							<h1 class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray">
								Texts
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">Blocks of text</p>
					</div>
					<div>
						<div class="md:flex items-center lg:ml-24">
							<div class="md:w-64 mx-2">
								Text 1<br />
								<Editor
									apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
									bind:value={content.text1}
									{conf}
								/>
							</div>

							<div class="md:w-64 mx-2">
								Text 2<br />
								<Editor
									apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
									bind:value={content.text2}
									{conf}
								/>
							</div>
						</div>

						{#if page_type != 'Form' && page_type != 'Home'}
							<div class="md:flex items-center lg:ml-24">
								<div class="md:w-64 mx-2">
									Text 3<br />
									<Editor
										apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
										bind:value={content.text3}
										{conf}
									/>
								</div>

								<div class="md:w-64 mx-2">
									Text 4<br />
									<Editor
										apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
										bind:value={content.text4}
										{conf}
									/>
								</div>
							</div>
						{/if}
					</div>
				</div>
			</div>

			{#if page_type == 'Form'}
				<div class="xl:px-8">
					<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
						<div class="w-80">
							<div class="flex items-center">
								<h1 class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray">
									Fields
								</h1>
							</div>
							<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
								<button class="btn-primary flex" on:click={add_form}>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										class="h-5 w-5 mr-1"
										viewBox="0 0 20 20"
										fill="currentColor"
									>
										<path
											fill-rule="evenodd"
											d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
											clip-rule="evenodd"
										/>
									</svg>
									Add Field</button
								>
							</p>
						</div>
						<div>
							<!--fields-->
							<div class="md:flex items-center">
								<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
									<thead
										class="text-xs text-white uppercase bg-primary dark:bg-gray-700 dark:text-gray-400"
									>
										<th scope="col" class="px-2 py-1" />
										<th scope="col" class="px-2 py-1"> Field's Name </th>
										<th scope="col" class="px-2 py-1"> Type </th>
										<th scope="col" class="px-2 py-1"> Required </th>
										<th scope="col" class="px-2 py-1"> Position </th>
										<th scope="col" class="px-4 py-1" />
									</thead>
								{#if listForm.length>0}
									 
									<tbody>
										{#each listForm as form, i}
											<tr class="bg-white border-b hover:bg-aliceblue">
												<td class="font-bold">{i + 1}</td>
												<td
													><input
														type="text"
														class="inputA"
														placeholder="Field Name"
														bind:value={form.name}
													/></td
												>
												<td>
													<select class="inputA" bind:value={form.type}>
														<option value="text">text</option>
														<option value="longtext">longtext</option>
														<option value="number">number</option>
														<option value="email">email</option>
														<option value="phone">phone</option>
														<option value="date">date</option>
														<option value="checkbox">checkbox</option>
													</select>
												</td>
												<td class="text-center">
													<input type="checkbox" bind:checked={form.required} />
												</td>
												<td class="text-center">
													<input
														type="number"
														min="1"
														max="99"
														class="inputA w-12"
														bind:value={form.position}
													/>
												</td>

												<td>
													<button
														on:click={() => {
															deleteForm(form.position);
														}}
													>
														<i class="fa fa-trash-o text-lg text-red" />
													</button>
												</td>
											</tr>
											
										{/each}
									</tbody>
									
								{/if}
								</table>
							</div>
							<!--the end fields-->
						</div>
					</div>
				</div>
			{/if}

			{#if page_type == 'Gallery'}
				<div class="xl:px-8">
					<div class="mt-16 lg:flex justify-between border-b border-silver pb-4">
						<div class="w-80">
							<div class="flex items-center">
								<h1 class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray">
									Galleries
								</h1>
							</div>
							<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
								<button class="btn-primary flex" on:click={addGallery}>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										class="h-5 w-5 mr-1"
										viewBox="0 0 20 20"
										fill="currentColor"
									>
										<path
											fill-rule="evenodd"
											d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
											clip-rule="evenodd"
										/>
									</svg>
									Add New Image</button
								>
							</p>
						</div>
						<div>
							<div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 lg:ml-24">
								{#each listGalleries as galle, i}
									<div class="mx-2">
										{#if galle.image == 'load'}
											<Circle3 size="60" unit="px" duration="2s" />
										{:else if galle.image != ''}
											<img
												src="{urlFiles}/images/maker_gallery/M{galle.image}"
												alt={page_name}
												class="w-full h-auto"
											/> <br />
											<button
												class="btn-red flex"
												on:click={() => {
													galle.image = '';
												}}
											>
												<svg
													xmlns="http://www.w3.org/2000/svg"
													class="h-5 w-5 mr-1 "
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
												Delete Image
											</button>
										{/if}

										Image
										<input
											type="number"
											min="1"
											max="99"
											class="inputA w-12"
											bind:value={galle.position}
										/>
										JPG - PNG

										<br />
										<small>800 x 600 px</small>

										<input
											type="file"
											accept=".jpg, .jpeg, .png"
											class="inputA"
											placeholder="Image 1"
											bind:files={fileImage}
											on:change={() => {
												galle.image = 'load';
												uploadGallery(galle, i);
											}}
										/>
										<input
											type="text"
											placeholder="Title"
											class="inputA"
											bind:value={galle.title}
										/>

										<textarea
											class="inputA"
											placeholder="Description"
											bind:value={galle.description}
										/>

										<button
											class="btn-red flex"
											on:click={() => {
												deleteGallery(galle.id);
											}}
										>
											<svg
												xmlns="http://www.w3.org/2000/svg"
												class="h-5 w-5 mr-1 "
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
											Delete this Item
										</button>
									</div>
								{/each}
							</div>
						</div>
					</div>
				</div>
			{/if}

			<div class="flex mt-3 bg-aliceblue p-3 rounded-lg">
				{#if page_type == 'Form'}
					<button
						class="btn-green flex"
						on:click={() => {
							saveCont(content.id);
							saveForm();
						}}
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="h-5 w-5 mr-1"
							viewBox="0 0 20 20"
							fill="currentColor"
						>
							<path
								fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
								clip-rule="evenodd"
							/>
						</svg>
						Save</button
					>
				{:else if page_type == 'Gallery'}
					<button
						class="btn-green flex"
						on:click={() => {
							saveCont(content.id);
							saveGallery();
						}}
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="h-5 w-5 mr-1"
							viewBox="0 0 20 20"
							fill="currentColor"
						>
							<path
								fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
								clip-rule="evenodd"
							/>
						</svg>
						Save</button
					>
				{:else}
					<button
						class="btn-green flex"
						on:click={() => {
							show_cont = false;
							saveCont(content.id);
						}}
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="h-5 w-5 mr-1"
							viewBox="0 0 20 20"
							fill="currentColor"
						>
							<path
								fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
								clip-rule="evenodd"
							/>
						</svg>
						Save</button
					>
				{/if}

				<button
					class="ml-4 flex btn-red"
					on:click={() => {
						show_cont = false;
					}}
				>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 mr-1"
						viewBox="0 0 20 20"
						fill="currentColor"
					>
						<path
							fill-rule="evenodd"
							d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
							clip-rule="evenodd"
						/>
					</svg>
					Cancel</button
				>
			</div>
		</div>
	</div>
</div>

<!--
{#if m_show==true}
<Messages bind:m_show bind:message />	 
{/if}
-->
