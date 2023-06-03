<script lang="ts">
import { goto } from "$app/navigation";
export let intro:boolean
</script>

<button class="fixed z-50 top-0 bottom-0 left-0 right-0 bg-secondary" on:click|self={()=>intro=false} >

 <div class="w-10/12 md:w-8/12 lg:w-4/12 mx-auto">
  <!-- <div class="flex bg-primary text-white niconne align-middle items-center rounded-t-xl overflow-hidden">
<img src="./logoKdar.jpg" alt="" class="w-32">
 <h2 class="pl-8">Bienvenido a Kdar Cosmetics</h2>
  </div> -->

<div class="flex align-middle items-center justify-center">
<img src="./logoKdar.jpg" alt="" class="w-48 rounded-xl">
</div>

  <!--iconos social-->
  <div class="flex align-middle center justify-center gap-8 mt-4">
   <div>
    <a href="https://www.facebook.com/profile.php?id=100091299960286" target="_blank" rel="noopener noreferrer" class="link-general">
     <i class="fa-brands fa-facebook text-4xl"></i></a>
  </div>
<div>
<a
   href="https://instagram.com/kdarcosmetics?igshid=NTc4MTIwNjQ2YQ=="
   target="_blank"
   rel="noopener noreferrer" class="link-general"
  >
  <i class="fa-brands fa-instagram text-4xl"></i></a>
</div>
 <div>
<a
   href="https://www.tiktok.com/@kdarcosmetics?_t=8cD9eguMrPK&_r=1"
   target="_blank"
   rel="noopener noreferrer" class="link-general"
  >
  <i class="fa-brands fa-tiktok text-4xl"></i></a>
</div>
  </div>
  <!---->

  <!--Los Accesos-->

  <button class="acceso mt-10" on:click={()=>intro=false}>
   <i class="fa-solid fa-cart-shopping text-xl"></i> Compra en Nuestra Tienda onLine
  </button>
<a href="/catalogo">
 <button class="acceso">
  
  <i class="fa-solid fa-barcode text-xl"></i> Catalogo
  
  </button>
</a>
 <a href="https://wa.me/573238802691?text=Hola!%20Estoy%20en%20tu%20p%C3%A1gina%20y%20quiero%20resolver%20algunas%20dudas%2C%20me%20puedes%20ayudar%3F">
<button class="acceso">
 
   <i class="fa-brands fa-whatsapp text-xl"></i> Asesoría por WhatsApp
 
</button>
 </a>
  
 </div>
</button>