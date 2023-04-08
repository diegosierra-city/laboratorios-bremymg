
import { onMount } from "svelte";
import { writable } from "svelte/store";
import { browser } from "$app/environment";
import type { Keys } from "$lib/types/Keys";

//export let cookie_name
//export let cookie_value

export const cookiesLibrary = writable([]);

export const cookie_update = (cookie_name: string, cookie_value: string) => {
	if (cookie_value == '') {
		cookiesLibrary.subscribe((val) => browser && localStorage.removeItem(cookie_name));///delete
	} else {
		cookiesLibrary.subscribe((val) => browser && localStorage.setItem(cookie_name, cookie_value));///update	
	}

}

export const cookie_info = (name: string) => {
	return browser && localStorage.getItem(name)
}

export const moduleAdmin = writable('menu')//first module

export const userNow = writable({
	id: 0,
	company_id: 0,
	name: '',
	email: '',
	type: '',
	user_time_life: 0,
	token: ''
})


/*
let credentials:Keys = {
	urlAPI_Maker: '',
	urlFiles: '',
	token: '',
	companyName: '',
	companyId: 0
}

export const loadCredentials = async () => {
	await fetch('/credentials.json')
	.then(response => response.json())
	.then(res=>{
		console.log('datos:')
		console.log(res)
		credentials = res
		return res
	})  
	}
	
	//loadCredentials()	
	*/


export const apiKey = writable({
	urlAPI_Maker: "https://maker.cityciudad.com/api/api-Maker.php",
	urlFiles: "https://maker.cityciudad.com/maker-files",
	token: "48aeca28238a599d9bdde0f280727cfa",
	companyName: 'KDAR Cosmetics',
	companyId: 2
	/*
	urlAPI_Maker: credentials.urlAPI_Maker,
	urlFiles: credentials.urlFiles,
	token: credentials.token,
	companyName: credentials.companyName,
	companyId: credentials.companyId
	*/
	
	/*
token: "c1ce08031b26c72b5deaa1026acec30b",
	companyName: 'Vender o Arrendar',
	companyId: 3
	*/
})

