<script lang="ts">
	import { page } from '$app/stores'

	import Login from '$lib/components/Login.svelte';
	import AdminMenu from '$lib/components/AdminMenu.svelte';
	import AdminCategories from '$lib/components/AdminCategories.svelte';
	import AdminForms from '$lib/components/AdminForms.svelte';

	import { cookie_info, cookie_update, moduleAdmin, userNow } from '../../../store';
	import type { User } from '$lib/types/User';
	import AdminCompany from '$lib/components/AdminCompany.svelte';

	let userN: any;
	let user: User;
	let userNew: User = {
		id: 0,
		company_id: 0,
		name: '',
		email: '',
		type: '',
		user_time_life: 0,
		token: ''
	};
	user = userNew;

	let time_now = Date.now() / 1000;

	if (cookie_info('user')) {
		//console.log('pppp')
		userN = cookie_info('user');
		user = JSON.parse(userN);
		$userNow = user;
		if (user.user_time_life < time_now) {
			cookie_update('user', '');
			user = userNew;
			$userNow = userNew;
		}
	}
	/*
1:{user.name}-2:{$userNow.id}-3:{userN}*
	*/
</script>

<svelte:head>
	<title>Maker-{$moduleAdmin}</title>
	<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.css" />
</svelte:head>

<div class="w-full">


	{#if $userNow.id == 0 || $userNow.id == undefined}
		<Login />
	{:else if $moduleAdmin == 'company'}
		<AdminCompany />
		{:else if $moduleAdmin == 'menu'}
		<AdminMenu />
	{:else if $moduleAdmin == 'categories'}
		<AdminCategories />
	{:else if $moduleAdmin == 'forms'}
		<AdminForms />
	{/if}
</div>
