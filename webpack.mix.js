const mix = require('laravel-mix');

mix.options({
    postCss: [
        require('autoprefixer'),
    ],
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.js('resources/js/jwt.js', 'public/js');
mix.js('resources/js/tests/sql-injection.js', 'public/js');

mix.js('resources/js/libs/ui/full-page-scroll.js', 'public/js');

mix.js('resources/js/controllers/page/main.js', 'public/js');
mix.js('resources/js/controllers/page/forum.js', 'public/js');
mix.js('resources/js/controllers/page/login-defer.js', 'public/js');
mix.js('resources/js/controllers/page/support.js', 'public/js');
mix.js('resources/js/controllers/page/support-defer.js', 'public/js');
mix.js('resources/js/controllers/page/register.js', 'public/js');
mix.js('resources/js/controllers/page/create-post.js', 'public/js');
mix.js('resources/js/controllers/page/update-post.js', 'public/js');
mix.js('resources/js/controllers/page/sales.js', 'public/js');
mix.js('resources/js/controllers/page/peers.js', 'public/js');
mix.js('resources/js/controllers/page/purchase_history.js', 'public/js');
mix.js('resources/js/controllers/page/broadcast.js', 'public/js');
mix.js('resources/js/controllers/page/verify-email.js', 'public/js');
mix.js('resources/js/controllers/page/user-info.js', 'public/js');

mix.js('resources/js/controllers/components/navbar.js', 'public/js');


mix.postCss('resources/css/pages/chatbox.css', 'public/css');
mix.postCss('resources/css/pages/footer.css', 'public/css');
mix.postCss('resources/css/common.css', 'public/css');
mix.postCss('resources/css/pages/login.css', 'public/css');
mix.postCss('resources/css/pages/dashboard.css', 'public/css');
mix.postCss('resources/css/pages/support.css', 'public/css');
mix.postCss('resources/css/pages/main.css', 'public/css');
mix.postCss('resources/css/pages/register.css', 'public/css');
mix.postCss('resources/css/pages/update-post.css', 'public/css');
mix.postCss('resources/css/pages/create-post.css', 'public/css');
mix.postCss('resources/css/pages/emergency-broadcast.css', 'public/css');
mix.postCss('resources/css/pages/peers.css', 'public/css');
mix.postCss('resources/css/pages/purchase-history.css', 'public/css');
mix.postCss('resources/css/pages/cart.css', 'public/css');
mix.postCss('resources/css/pages/sales.css', 'public/css');
mix.postCss('resources/css/pages/user-info.css', 'public/css');


mix.postCss('resources/css/components/navbar.css', 'public/css');
