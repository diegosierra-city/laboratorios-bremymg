<script lang="ts">
	//import { goto } from '$app/navigation';
	//import { browser } from '$app/environment';
	import { apiKey, cookie_info, cookie_update, userNow } from '../../store';
	//import type { User } from '$lib/types/User';

	import Messages from '$lib/components/Messages.svelte';

	import type { Message } from '$lib/types/Message';

	let m_show: boolean = false;
	let message: Message;
	
	const urlAPI_Maker = $apiKey.urlAPI_Maker;
	let login = {
		email: '',
		pass: '',
		pass2: ''
	};

	const login_run = async () => {
		if (action == 'Change Password' && login.pass2 != login.pass) {
			message = {
				title: 'Error',
				text: 'Passwords do not match',
				class: 'message-red',
				accion: ''
			};
			m_show = true;
		} else {
			//console.log("xx");
			//// POST
			//onMount( async () => {
			await fetch(urlAPI_Maker + '?ref=' + action, {
				method: 'POST', //POST - PUT - DELETE
				body: JSON.stringify({
					email: login.email,
					password: login.pass,
					passwordB: login.pass2
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

						message = {
							title: 'Error',
							text: result[0].error,
							class: 'message-red',
							accion: ''
						};
						m_show = true;
					} else {
						if (action == 'Login') {
							$userNow = result[0];
							cookie_update('user', JSON.stringify(result[0]));
							console.log('Muy Bien:');
							console.log(cookie_info('user'));
						} else {
							message = {
								title: 'Change Password',
								text: result[0].ok,
								class: 'message-green',
								accion: ''
							};
							m_show = true;
							forgotP(false);
							login.email = '';
							login.pass = '';
						}
					}
				})

				.catch((error) => console.log(error.message));

			//  });
		}
	};

	let forgot: string = 'Forgot your password?';
	let action: string = 'Login';
	let preP: string = '';

	function forgotP(state: boolean) {
		if (state == true) {
			forgot = '';
			action = 'Change Password';
			preP = 'New ';
		} else {
			forgot = 'Forgot your password?';
			action = 'Login';
			login.pass2 = '';
			preP = '';
		}
	}

</script>

<svelte:head>
	<title>Site-Maker</title>
</svelte:head>

<div class="w-full ">
	<div
		class="grid grid-cols-1 place-content-center place-items-center w-11/12 sm:w-8/12 md:w-6/12 xl:w-3/12 mt-6 mx-auto"
	>
		<div class="bg-white shadow-lg rounded w-full p-4 md:col-span-3 xl:col-span-2">
			<div class="mt-4">
				<!--
					<img src="/maker-files/images/Logo-Cootraesturz-200.png" class="mx-auto" alt="" />
				-->
				
			</div>
			<p class="text-2xl font-bold leading-6 text-primary">{action}</p>

			
            <!--    
            <button aria-label="Continue with google" class="focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-700 p-3 border rounded-lg border-gray-700 flex items-center w-full mt-10 hover:bg-gray-100" on:click={()=>signInWithGoogle()}>
               <img src="https://tuk-cdn.s3.amazonaws.com/can-uploader/sign_in_2-svg2.svg" alt="google">
                <p class="text-base font-medium ml-4 text-gray-700">Continue with Google</p>
            </button>
            <button aria-label="Continue with github" class="focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-700 p-3 border rounded-lg border-gray-700 flex items-center w-full mt-4 hover:bg-gray-100" on:click={()=>signInWithFacebook()}>
													<Icon data={facebookSquare} scale="1.5" class="text-royalblue"/>
                <p class="text-base font-medium ml-4 text-gray-700">  Continue with Faceook</p>
            </button>
            
       -->
			<form on:submit|preventDefault={login_run}>
				<div class="mt-4">
					<label for="email" class="text-sm font-medium leading-none text-dimgray"> Email</label>
					<input
						autocomplete="email"
						required
						type="email"
						class="inputA"
						placeholder="e.g: john@gmail.com "
						bind:value={login.email}
					/>
				</div>
				<div class="mt-6 w-full">
					<div class="text-sm font-medium leading-none text-dimgray grid grid-cols-2">
						<div>{preP}Password</div>
						<button class="text-right link" on:click={() => forgotP(true)}>{forgot}</button>
					</div>

					<div class="relative flex items-center justify-center">
						<input
							type="password"
							class="inputA"
							autocomplete="current-password"
							required
							bind:value={login.pass}
						/>
					</div>
				</div>

				{#if forgot == ''}
					<div class="mt-6 w-full">
						<div class="text-sm font-medium leading-none text-dimgray grid grid-cols-2">
							<div>Confirm Password</div>
						</div>
						<div class="relative flex items-center justify-center">
							<input
								type="password"
								class="inputA"
								autocomplete="current-password"
								required
								bind:value={login.pass2}
							/>
						</div>
					</div>
				{/if}

				<div class="mt-8">
					<button type="submit" class="btn-primary-full">{action}</button>
				</div>

				{#if forgot == ''}
					<button class="text-right link" on:click={() => forgotP(false)}>Return Login</button>
				{/if}
			</form>
		</div>
		
	</div>
</div>

<Messages bind:m_show bind:message />