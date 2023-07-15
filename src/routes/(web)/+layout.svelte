<script lang="ts">
import WebWhatsapp from "$lib/components/WebWhatsapp.svelte";
</script>
<svelte:head>
 <link rel="stylesheet" href="../../css/fontawesome-free-6.4.0-web/css/all.css" /> 
</svelte:head>

<main>
<slot />
</main>

<WebWhatsapp />
