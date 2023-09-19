const mix = require('laravel-mix');
const fs = require('fs');
const tmp = 'tmp.js';

mix.options({ manifest: false });

mix.js('/js/app.js', tmp).vue().copy(tmp, '/var/www/html/app.js').then(() => {
    fs.unlinkSync(tmp);
});


