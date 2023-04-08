////STATIC configuracion ok
import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/kit/vite';

export default {
	preprocess: vitePreprocess(),
  kit: {
    adapter: adapter({
      // default options are shown. On some platforms
      // these options are set automatically — see below
      pages: 'build',
      assets: 'build',
      fallback: 'index.html',//null//'index.html' '404.html' '200.html'//se define para SPA
      precompress: false,
      strict: true ///true
    }),
    
  }
};

// add 2 lines in routes/+layout.ts
//export const prerender = true;//fallback: '404.html'
//export const trailingSlash = 'ignore' //always
// en carpeta dinamica agregar +page.ts
//export const prerender = false;
/**/


/*
///VERCEL OK
import vercel from '@sveltejs/adapter-vercel';
import { vitePreprocess } from '@sveltejs/kit/vite';

/** @type {import('@sveltejs/kit').Config} * /
export default {
  plugins: [
    copy({
      targets: [
        { src: 'node_modules/tinymce/*', dest: 'public/tinymce' }
      ]
    }),
    
  ],
	preprocess: vitePreprocess(),
  kit: {
    // default options are shown
    adapter: vercel({
      // if true, will deploy the app using edge functions
      // (https://vercel.com/docs/concepts/functions/edge-functions)
      // rather than serverless functions
      edge: false,

      // an array of dependencies that esbuild should treat
      // as external when bundling functions
      external: [],

      // if true, will split your app into multiple functions
      // instead of creating a single one for the entire app
      split: false
    })
  }
};
*/


/*
import adapter from '@sveltejs/adapter-auto';
import { vitePreprocess } from '@sveltejs/kit/vite';

/** @type {import('@sveltejs/kit').Config} * /
const config = {
	// Consult https://kit.svelte.dev/docs/integrations#preprocessors
	// for more information about preprocessors
	preprocess: vitePreprocess(),

	kit: {
		// adapter-auto only supports some environments, see https://kit.svelte.dev/docs/adapter-auto for a list.
		// If your environment is not supported or you settled on a specific environment, switch out the adapter.
		// See https://kit.svelte.dev/docs/adapters for more information about adapters.
		adapter: adapter()
	}
};

export default config;
*/