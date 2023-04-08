<script lang="ts">
	import MenuAdmin from '$lib/components/MenuAdmin.svelte';
	import { userNow } from '../../store';

	
  
</script>

<main class="flex -mt-12 ">
	{#if $userNow.id > 0}
		<MenuAdmin />
	{/if}
	<slot />
</main>
