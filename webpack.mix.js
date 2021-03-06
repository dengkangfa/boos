let mix = require('laravel-mix');

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
mix.webpackConfig({
  resolve: {
    alias: {
      Api: path.resolve(__dirname, 'resources/assets/js/api'),
      Mixin: path.resolve(__dirname, 'resources/assets/js/mixin'),
      Base: path.resolve(__dirname, 'resources/assets/js/components/base'),
      Helpers: path.resolve(__dirname, 'resources/assets/js/helpers'),
      Components: path.resolve(__dirname, 'resources/assets/js/components'),
      InfoBase: path.resolve(__dirname, 'resources/assets/js/components/info/base')
    }
  },
  module: {
    rules: [
      {
        test: /\.(jpg|png|gif)$/,
        use: [
          'file-loader?name=[name].[ext]&outputPath=images/'
        ]
      }
    ]
  }
});

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.sass', 'public/css');
