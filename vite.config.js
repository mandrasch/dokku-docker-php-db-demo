import { resolve } from 'path'

export default {
    // config options
    root: 'src',
    build: {
        // generate .vite/manifest.json in outDir
        manifest: true,
        rollupOptions: {
            // overwrite default .html entry
            // TODO: docker needed src/main.js instead of main.js?
            input: 'src/main.js'
        },
        outDir: '../public/dist',     // relative to root
    },

}