<script lang="ts">
	import { onMount } from 'svelte';
	import { apiKey, cookie_info, userNow } from '../../store';
	import { Circle3 } from 'svelte-loading-spinners';

	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	import type { Company } from '$lib/types/Company'; 
	import type { User } from '$lib/types/User';


	//console.table(user)

	let infoCompany: Company = {
  id: 0,
		company: '',
    document: '',
    country: '',
    city: '',
    address: '',
    phone: '',
    email: '',
    contact: '',
    contact_phone: '',
    contact_email: '',
    image1: '',
    tokenWeb: '',
    urlAPI: '',
    lat: '',
    lon: '',
    active: true,
    bank1: '',
    bank2: '',
    bank3: '',
	};

	const urlAPI  = $apiKey.urlAPI_Maker ;
	const urlFiles = $apiKey.urlFiles;

	const loadCompany = async (h: any) => {
		console.log(
			urlAPI  +
				'?ref=loadID&folder=maker_companies&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		);
		await fetch(
			urlAPI  +
				'?ref=loadID&folder=maker_companies&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					//console.log(result);
					infoCompany = result;
					//cont = JSON.parse(result[1]);
				
			})
			.catch((error) => console.log(error.message));
	};

	
	$: loadCompany($userNow.company_id);

	const saveCompany = async () => {
		//console.log("yy:" + infoCompany.id);

		await fetch(urlAPI  + '?ref=save', {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				folder: 'maker_companies',
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				company_id: $userNow.company_id,
				request: infoCompany
				//
			}),
			headers: {
				'Content-type': 'application/json; charset=UTF-8'
			}
		})
			.then((response) => response.json())
			//.then(result => console.log(result))
			.then((result) => {
				
					//console.log("Muy Bien:"+result[0].ok);
					infoCompany = result[0];
					console.log('se guardo')
					console.log(infoCompany);
					message = {
						title: 'Guardar',
						text: 'Información Guardada',
						class: 'message-green',
						accion: ''
					};
					m_show = true;
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};

	let fileImage: FileList;

	const upload = async (id: number, position: number) => {
		//console.table(fileImage[0]);
		const dataArray = new FormData();
		dataArray.append('user_id', String($userNow.id));
		dataArray.append('time_life', String($userNow.user_time_life));
		dataArray.append('token', $userNow.token);
		dataArray.append('id', String(id));
		dataArray.append('position', String(position));
		dataArray.append('uploadFile', fileImage[0]);
		dataArray.append('folder', 'maker_companies');

		await fetch(urlAPI  + '?ref=upload&folder=maker_companies', {
			method: 'POST',
			body: dataArray
		})
			.then((response) => response.json())
			.then((result) => {
				
					infoCompany = result[0];

					message = {
						title: 'Publicar',
						text: 'Imagen Publicada',
						class: 'message-green',
						accion: ''
					};
					m_show = true;
				
			})
			.catch((error) => console.log(error.message))
	}

	import Editor from '@tinymce/tinymce-svelte';
	let conf = {
   toolbar: 'h1 h2 h3 bold  italic forecolor aligncenter alignjustify alignleft undo redo ',
   menubar: false,
			height: 200,
			width: '100%'
 }
</script>

<svelte:head>
	<title>{infoCompany.company}</title>
	
</svelte:head>

<div class="p-3 w-full ">

	
	<div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full bg-aliceblue p-3">
		<h3 class="text-primary">{infoCompany.company}</h3>

		<div class="flex ">
			<button
				class="btn-green flex"
				on:click={() => {
					saveCompany();
				}}
			>
			Guardar</button
			>
		</div>

		<div class="focus:outline-none w-full lg:w-10/12 mx-auto">
			<div class="">
				<div class="mb-6 grid grid-cols-12 gap-2 border-b border-silver pb-4">
					<div class="col-span-12 md:col-span-3">
						<div class="flex items-center">
							<h1 class="focus:outline-none text-xl font-medium pr-2 text-dimgray">
								Logo
							</h1>
						</div>
						
					</div>

					<div class="col-span-12 md:col-span-9">
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								{#if infoCompany.image1 == 'load'}
									<Circle3 size="60" unit="px" duration="2s" />
								{:else if infoCompany.image1 != ''}
									<img src="{urlFiles}/images/maker_companies/M{infoCompany.image1}" alt={infoCompany.company} />
									<br />
									<button
										class="btn-red flex"
										on:click={() => {
											infoCompany.image1 = '';
										}}
									>
										
										Borrar
									</button><br />
								{/if}
								Logo JPG - PNG <br />
								<small class="text-blue">800 x 600 px</small><br />

								<input
									type="file"
									accept=".jpg, .jpeg, .png"
									class="inputA"
									placeholder="Imagen 1"
									bind:files={fileImage}
									on:change={() => {
										infoCompany.image1 = 'load';
										upload(infoCompany.id, 1);
									}}
								/>
							</div>

							
						</div>
						

					</div>
				</div>
			</div>

			<div class="">
				<div class="mb-6 grid grid-cols-12 gap-2 border-b border-silver pb-4">
					<div class="col-span-12 md:col-span-3">
						<div class="flex items-center">
							<h1
								
								class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray"
							>
								Información
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
							Información Básica
						</p>
					</div>
					<div class="col-span-12 md:col-span-9">
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Nombre<br />

								<input
									readonly
									type="text"
									class="inputA"
									placeholder="Nombre de la Empresa"
									bind:value={infoCompany.company}
								/>
							</div>

							<div class="mx-2">
								Nit <br />

								<input
									type="text"
									class="inputA"
									placeholder="Nit"
									bind:value={infoCompany.document}
								/>
							</div>
						</div>
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Ciudad<br />
								<input
									type="text"
									class="inputA"
									placeholder="Ciudad"
									bind:value={infoCompany.city}
								/>
							</div>

							<div class="mx-2">
								Dirección: <br />
								<input
									type="text"
									class="inputA"
									placeholder="Dirección"
									bind:value={infoCompany.address}
								/>
							</div>
						</div>

						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Telefono<br />
								<input
									type="phone"
									class="inputA"
									placeholder="Telefono"
									bind:value={infoCompany.phone}
								/>
							</div>

							<div class="mx-2">
								Email: <br />
								<input
									type="email"
									class="inputA"
									placeholder="Email"
									bind:value={infoCompany.email}
								/>
							</div>
						</div>
						
					</div>
				</div>
			</div>

			<div class="">
				<div class="mb-6 grid grid-cols-12 gap-2 border-b border-silver pb-4">
					<div class="col-span-12 md:col-span-3">
						<div class="flex items-center">
							<h1
								
								class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray"
							>
								Contacto
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
							Datos Privados del contacto administrativo
						</p>
					</div>
					<div class="col-span-12 md:col-span-9">
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Nombre <br />

								<input
									type="text"
									class="inputA"
									placeholder="Nombre Contacto"
									bind:value={infoCompany.contact}
								/>
							</div>

							<div class="mx-2">
								Telefono <br />

								<input
									type="text"
									class="inputA"
									placeholder="Telefono"
									bind:value={infoCompany.contact_phone}
								/>
							</div>
						</div>
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Email<br />
								<input
									type="text"
									class="inputA"
									placeholder="Email"
									bind:value={infoCompany.contact_email}
								/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="">
				<div class="mb-6 grid grid-cols-12 gap-2 border-b border-silver pb-4">
					<div class="col-span-12 md:col-span-3">
						<div class="flex items-center">
							<h1
								
								class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray"
							>
								Cuentas Bancarias 
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
							Cuentas para publicar
						</p>
					</div>

     <div class="col-span-12 md:col-span-9">
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Cuenta 1 <br />
        <Editor
        apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
        bind:value={infoCompany.bank1}
        {conf}
       />
								
							</div>

						</div>
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Cuenta 2<br />
								<Editor
        apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
        bind:value={infoCompany.bank2}
        {conf}
       />
							</div>
						</div>

      <div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Cuenta 3<br />
								<Editor
        apiKey="6omiyxavakt13jx418pdqk4jh453k7vgjz33blqckjrskk88"
        bind:value={infoCompany.bank3}
        {conf}
       />
							</div>
						</div>

					</div>
					
				</div>
			</div>

			<div class="">
				<div class="mb-6 grid grid-cols-12 gap-2 border-b border-silver pb-4">
					<div class="col-span-12 md:col-span-3">
						<div class="flex items-center">
							<h1
								
								class="focus:outline-none text-xl font-medium pr-2 leading-5 text-dimgray"
							>
								Ubicación Mapa
							</h1>
						</div>
						<p class="focus:outline-none mt-4 text-sm leading-5 text-gray">
							Coordenadas en mapa
						</p>
					</div>
					<div class="col-span-12 md:col-span-9">
						<div class="md:flex items-center lg:ml-2">
							<div class="mx-2">
								Latitud <br />

								<input
									type="text"
									class="inputA"
									placeholder="Latitud"
									bind:value={infoCompany.lat}
								/>
							</div>

							<div class="mx-2">
								Longitud <br />

								<input
									type="text"
									class="inputA"
									placeholder="Longitud"
									bind:value={infoCompany.lon}
								/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="flex ">
			<button
				class="btn-green flex"
				on:click={() => {
					saveCompany();
				}}
			>
				
				Guardar</button
			>
		</div>
	</div>
</div>

{#if m_show == true}
	<Messages bind:m_show bind:message />
{/if}
