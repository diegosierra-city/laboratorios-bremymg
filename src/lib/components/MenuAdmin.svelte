<script lang="ts">
	import type { User } from '$lib/types/User';
	import { cookie_update, cookie_info, moduleAdmin, userNow } from '../../store';

	//export let userNow = cookie_info('user')

	let userNew: User = {
		id: 0,
		company_id: 0,
		name: '',
		email: '',
		type: '',
		user_time_life: 0,
		token: '',
	};

	const salir = () => {
		cookie_update('user', JSON.stringify(userNew));
		$userNow = userNew;
	};
</script>

<div class="relative">
	<div class="w-14 lg:w-52" />

	<div class="w-14 lg:w-52 fixed bg-primary shadow top-0 bottom-0 flex-col justify-between ">
		<div class="px-2 lg:px-4">
			<div class="h-16 w-full items-center pt-5">
				<h3 class="text-white hidden lg:block">Maker</h3>
				<small class="text-white hidden lg:block">{$userNow.name}</small>
			</div>
			<ul class="mt-8 pb-2 border-b border-white boton_admin">
				<li class:boton_admin_active={$moduleAdmin === 'company'}>
					<button
						class="flex items-center"
						on:click={() => {
							$moduleAdmin = 'company';
						}}
					>
						<i class="fa fa-building-o" />
						<span class="text-sm ml-2 hidden lg:block">Company</span>
					</button>
					<!--
                    <div class="boton_alert">5</div>
                    -->
				</li>
				<li class:boton_admin_active={$moduleAdmin === 'menu'}>
					<button
						class="flex items-center"
						on:click={() => {
							$moduleAdmin = 'menu';
						}}
					>
						<i class="fa fa-newspaper-o" />
						<span class="text-sm ml-2 hidden lg:block">Pages</span>
					</button>
					<!--
                    <div class="boton_alert">5</div>
                    -->
				</li>

				

				<li class:boton_admin_active={$moduleAdmin === 'forms'}>
					<button
						class="flex items-center"
						on:click={() => {
							$moduleAdmin = 'forms';
						}}
					>
					<i class="fa fa-commenting-o" />
						<span class="text-sm ml-2 hidden lg:block">Forms</span>
					</button>
				</li>

				<li class:boton_admin_active={$moduleAdmin === 'categories'}>
					<button
						class="flex items-center"
						on:click={() => {
							$moduleAdmin = 'categories';
						}}
					>
						<i class="fa fa-cubes" />
						<span class="text-sm ml-2 hidden lg:block">Products</span>
					</button>
				</li>
				<!-- Landing Pages -->
				
			</ul>

			
		</div>
		<div class="px-0 lg:px-8 border-t border-white absolute bottom-0 w-full">
			<ul class="w-full lg:flex items-center justify-between ">
				<li class="cursor-pointer text-white pt-0 pb-3">
					<button class="icono_admin" on:click={salir}>
						<i class="fa fa-power-off text-red" />
					</button>
				</li>
				<li class="cursor-pointer text-white pt-0 pb-3">
					<button aria-label="open chats" class="icono_admin">
						<i class="fa fa-user" />
					</button>
				</li>
				<li class="cursor-pointer text-white pt-0 pb-3">
					<button aria-label="open settings" class="icono_admin">
						<i class="fa fa-life-ring" />
					</button>
				</li>
			</ul>
		</div>
	</div>
</div>
