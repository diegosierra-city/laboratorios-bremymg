<script lang="ts">
 //import json from './maker-access.txt';
 console.log('key:')

 const keys= async() => {
 await fetch('./maker-access.jsonx')
 //.then((response) => response.json())
			.then((result) => {
  console.log('keys*:')
 console.log(result);
 })
.catch(error => {
 console.log('error*:')
 console.log(error.message)})
 }
  /**/
</script>