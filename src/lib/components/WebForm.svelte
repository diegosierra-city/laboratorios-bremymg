<script lang="ts">
	import { onMount } from 'svelte';
	import type { Form } from '$lib/types/Form';
	import { apiKey } from '../../store';
	
import type {WebContent} from '$lib/types/WebContent'

	const urlAPI = $apiKey.urlAPI_Maker;
	const urlFiles = $apiKey.urlFiles;
	const company_id = $apiKey.companyId
	const company_name = $apiKey.companyName
	const tokenWeb = $apiKey.token;

	let listForm: Array<Form> = [];
   let listFormReset: Array<Form> = []; 
				export let page_id:number 

				import Messages from '$lib/components/Messages.svelte';
				import type { Message } from '$lib/types/Message';
				let m_show: boolean = false;
	let message: Message;

	
	const loadForm = (id: number) => {
		console.log(
			urlAPI + '?ref=form-listWeb&company_id=' + company_id + '&tokenWeb=' + tokenWeb + '&id=' + id
		);
		fetch(
			urlAPI + '?ref=form-listWeb&company_id=' + company_id + '&tokenWeb=' + tokenWeb + '&id=' + id
		)
			.then((response) => response.json())
			.then((result) => {
				console.log('recibiendo formulario:');
				//console.table(result);
			
					console.log('Formulario:');
					console.log(result);
					//console.log('ttt:'+result.length)
					if (result.length > 0) {
						listForm = result;
                 //listFormReset = [...result];
                  
					}
				
			})
   .catch((error)=>console.log(error.message))
		//});
		//}

		//load_form (cont_id)
	};

	//$: loadForm(page_id)
	onMount(()=>{
		loadForm(page_id)
	})

	export let ContBase: WebContent

	const form_run = () => {
      
						fetch(urlAPI + '?ref=save-formWeb&company_id=' + company_id + '&tokenWeb=' + tokenWeb, {
							method: 'POST', //POST - PUT - DELETE
							body: JSON.stringify({
								listForm: listForm,
								page: ContBase.menu,
								sendTo: ContBase.link,
								//password: pass,
							}),
							headers: {
								'Content-type': 'application/json; charset=UTF-8'
							}
						})
							.then((response) => response.json())
							//.then(result => console.log(result))
							.then((result) => {
								//console.log('ok:'+new_user[0].error)
				
								if (result[0].error) {
									console.error(result[0].error);
								} else {
																			
									console.log(result[0]);
									message = {
										title: 'Formulario Enviado',
										text: 'La información ha sido enviada, gracias',
										class: 'message-green',
										accion: ''
									};
									m_show = true;
																			
				
																		//listForm= [...listFormReset ]
																		bt_reset.click()
								}
							})
				.catch(error => console.log(error.message))
				
						//  });
					}
				
					let bt_reset:any
</script>

<form on:submit|preventDefault={form_run}>
	<div
		class="grid grid-cols-1 gap-0 bg-aliceblue p-4 rounded-lg my-4 w-full md:w-8/12 xl:w-5/12 mx-auto"
	>
		{#each listForm as item}
			{#if item.type != 'checkbox'}
				<div class="">{item.name}:</div>
			{/if}

			{#if item.type == 'text'}
				<input
					type="text"
					class="inputA"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'number'}
				<input
					type="number"
					class="inputA"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'date'}
				<input
					type="date"
					class="inputA"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'email'}
				<input
					type="email"
					class="inputA"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'phone'}
				<input
					type="phone"
					class="inputA"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'longtext'}
				<textarea
					class="inputA h-28"
					required={item.required == true}
					bind:value={item.response}
					placeholder={item.name}
				/>
			{:else if item.type == 'checkbox'}
				<div class="flex align-middle items-center">
					<input
						type="checkbox"
						class="inputA w-8 mr-2"
						required={item.required == true}
						bind:checked={item.response}
					/> <span>{item.name}</span>
				</div>
			{:else}
				<!-- else content here -->
			{/if}
			{:else}
			cargando...
		{/each}

		

		<div class="mt-3">
			<button type="submit" class="btn-green w-2/4">Enviar</button>
			<button type="reset" class="hidden" bind:this={bt_reset}> Limpiar</button>
		</div>
	</div>
</form>
