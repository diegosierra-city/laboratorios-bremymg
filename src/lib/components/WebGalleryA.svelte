<script lang="ts">
import type { Gallery } from '$lib/types/Gallery';

export let listGalleries: Array<Gallery> = [];
export let urlFiles: string = '';
 </script>

 <section class="gallery mt-12">
  
   {#each listGalleries as gallery, i}
         
      <img src="{urlFiles}/images/maker_gallery/{gallery.image}" alt=""/>
          
    <!--
     <div class="card_titleB"><h4>{gallery.title}</h4></div>
    -->
   {/each}
 
 </section>

 