<script lang="ts">
import WebWhatsapp from "$lib/components/WebWhatsapp.svelte";
import WebMenuC from '$lib/components/WebMenuC.svelte';
import WebFooter from "$lib/components/WebFooter.svelte";
</script>
<svelte:head>
 <link rel="stylesheet" href="../../css/fontawesome-free-6.4.0-web/css/all.css" /> 
</svelte:head>

<main>
 <WebMenuC />
<slot />
<WebFooter />
</main>

<WebWhatsapp />
