import { onMount } from "svelte";

import type { Keys } from "$lib/types/Keys";
let k:Keys = {
 urlAPI_Maker: '',
 urlFiles: '',
 token: '',
 companyName: '',
 companyId: 0
}
/*
		onMount(async () => {
			
	//	k = async () => {
			await fetch('./maker-access.json')
				.then((response) => response.json())
				.then((result) => {
					//console.log('keys*:');
					console.log(result);
								k= result
				})
				.catch((error) => {
					return error.message
				});
			})
			*/

			export const key = k
			
		//})

	



