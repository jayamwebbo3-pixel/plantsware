const fs = require('fs');
const path = require('path');
const CleanCSS = require('clean-css');
const { minify } = require('terser');

const baseCssDir = path.resolve('public/assets/css');
const baseJsDir = path.resolve('public/assets/js');

async function minifyAssets() {
    console.log('Starting Asset Minification...');

    // 1. Minify CSS files
    const cssFiles = ['style.css', 'customized.css', 'responsive.css'];
    const cleanCss = new CleanCSS({ level: 1 });

    for (const file of cssFiles) {
        const srcPath = path.join(baseCssDir, file);
        const destPath = path.join(baseCssDir, file.replace('.css', '.min.css'));
        
        if (fs.existsSync(srcPath)) {
            console.log(`Minifying CSS: ${file}...`);
            const input = fs.readFileSync(srcPath, 'utf8');
            const output = cleanCss.minify(input);
            
            if (output.errors.length) {
                console.error(`Errors minifying ${file}:`, output.errors);
            } else {
                fs.writeFileSync(destPath, output.styles, 'utf8');
                console.log(`Saved minified CSS to: ${path.basename(destPath)}`);
            }
        } else {
            console.warn(`File not found: ${srcPath}`);
        }
    }

    // 2. Minify JS files
    const jsFiles = ['custom.js'];

    for (const file of jsFiles) {
        const srcPath = path.join(baseJsDir, file);
        const destPath = path.join(baseJsDir, file.replace('.js', '.min.js'));
        
        if (fs.existsSync(srcPath)) {
            console.log(`Minifying JS: ${file}...`);
            const input = fs.readFileSync(srcPath, 'utf8');
            try {
                const result = await minify(input, {
                    compress: {
                        passes: 2
                    },
                    mangle: true
                });
                
                if (result.code) {
                    fs.writeFileSync(destPath, result.code, 'utf8');
                    console.log(`Saved minified JS to: ${path.basename(destPath)}`);
                }
            } catch (err) {
                console.error(`Error minifying ${file}:`, err);
            }
        } else {
            console.warn(`File not found: ${srcPath}`);
        }
    }

    console.log('Asset Minification Finished!');
}

minifyAssets().catch(console.error);
