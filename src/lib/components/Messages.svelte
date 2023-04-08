<script lang="ts">
	import type { Message } from '$lib/types/Message';

	export let message: Message;
	export let m_show: Boolean;

	const cerrar = () => {
		m_show = false;
	};

	$: if (m_show == true) {
		setTimeout(function () {
			m_show = false;
		}, 3000); /// cierre automático
	}
</script>

{#if m_show == true}
	<!-- content here -->
	<div class="bg-message">
		<div id="znMessage" class={message.class}>
			<div class="message-title">
				<h3>{message.title}</h3>
			</div>

			<p
				class="focus:outline-none text-sm text-gray dark:text-gray-400 pb-3 font-normal message-body"
			>
				{message.text}
			</p>
			{#if message.accion != ''}
				<button
					class="btn-green"
					on:click={() => {
						message.accion;
					}}>Cancel</button
				><button class="btn-red" on:click={cerrar}>Cancel</button>
			{:else}
				<button class="btn-primary" on:click={cerrar}>Close</button>
			{/if}

			<!---->
		</div>
	</div>
{/if}
