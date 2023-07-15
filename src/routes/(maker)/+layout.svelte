<script lang="ts">
	import MenuAdmin from '$lib/components/MenuAdmin.svelte';
	import { userNow } from '../../store';
  
</script>
<svelte:head>
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.css" />
</svelte:head>

<main class="flex -mt-12 ">
	{#if $userNow.id > 0}
		<MenuAdmin />
	{/if}
	<slot />
</main>
