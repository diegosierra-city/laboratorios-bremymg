<script lang="ts">
	import type { Category } from '$lib/types/Category';
	import { onMount } from 'svelte/internal';
	import { apiKey, userNow } from '../../store';

	import type { FormReport } from '$lib/types/FormReport';
	//
	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;

	let date = new Date();
	let FirstDay = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().slice(0, 10);
	let LastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().slice(0, 10);
	console.log(FirstDay + '*' + LastDay);
	let date1: any = FirstDay;
	let date2: any = LastDay;

	let listForm: Array<FormReport> = [];

	const urlAPI = $apiKey.urlAPI_Maker;

	const listF = async () => {
		await fetch(
			urlAPI +
				'?ref=form-list-report&user_id=' +
				$userNow.id +
				'&time=' +
				$userNow.user_time_life +
				'&token=' +
				$userNow.token +
				'&date1=' +
				date1 +
				'&date2=' +
				date2
		)
			.then((response) => response.json())
			.then((result) => {
				//console.table(result);
				
					console.log('listado:' + result[0] + '*');
					console.log(result);
					if (result.length > 0) {
						listForm = result;
					}
				
			})
			.catch((error) => console.log(error.message));
	};

	listF();

	const saveAnswer = async () => {
		//console.log("yy");
		//// POST

		await fetch(urlAPI + '?ref=save-form-answer' + '&date1=' + date1 + '&date2=' + date2, {
			method: 'POST', //POST - PUT - DELETE
			body: JSON.stringify({
				user_id: $userNow.id,
				time_life: $userNow.user_time_life,
				token: $userNow.token,
				listForm: listForm
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
					listForm = result;
					//new_user = result[0]
					//cookie_update('user',JSON.stringify(new_user))
				
			})
			.catch((error) => console.log(error.message));

		//  });
	};
</script>

<svelte:head>
	<title>Forms Report</title>
</svelte:head>

<div class="p-3 w-full">
	<div class="flex w-10/12 items-start mb-4">
		<input type="date" class="inputA mr-4" bind:value={date1} />
		<input type="date" class="inputA mr-4" bind:value={date2} />
		<button class="btn-primary mr-2 flex whitespace-nowrap" on:click={listF}>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				class="h-5 w-5 mr-2"
				viewBox="0 0 20 20"
				fill="currentColor"
			>
				<path
					fill-rule="evenodd"
					d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
					clip-rule="evenodd"
				/>
			</svg>
			Update Report</button
		>

		<button
			class="btn-green flex"
			on:click={() => {
				saveAnswer();
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
			<span class="whitespace-nowrap">Save Changes</span></button
		>
	</div>

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
		<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
			<thead class="text-xs text-white uppercase bg-primary dark:bg-gray-700 dark:text-gray-400">
				<th scope="col" class="px-4 py-2" />
				<th scope="col" class="px-4 py-2"> Date</th>

				<th scope="col" class="px-4 py-2 text-center"> Page </th>
				<th scope="col" class="px-4 py-2 text-center"> Message</th>
				<th scope="col" class="px-4 py-2 text-center"> Response </th>
				<th scope="col" class="px-4 py-2 text-center"> State </th>
			</thead>
			<tbody>
				{#each listForm as fm, i}
					<tr class="bg-white border-b hover:bg-aliceblue">
						<td class="text-bold">{i + 1}</td>
						<td>
							{fm.date}
						</td>
						<td>
							{fm.page}
						</td>
						<td class="">
							{@html fm.request}
						</td>
						<td>
							<textarea class="inputA" bind:value={fm.response} />
						</td>

						<td class="text-center">
							<select class="inputA" bind:value={fm.state}>
								<option value="open">open</option>
								<option value="close">close</option>
							</select>
						</td>
					</tr>

					<!---->
				{:else}
					There are no forms for these dates
				{/each}
			</tbody>
		</table>
	</div>
</div>

<Messages bind:m_show bind:message />
