let mix = require('laravel-mix')

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .postCss('resources/css/tool.css', 'dist/css', [
    require('tailwindcss')('tailwind.js'),
    require('autoprefixer'),
    require('@fullhuman/postcss-purgecss')({
      // Specify the paths to all of the template files in your project
      content: [
        './resources/js/components/*.vue',
      ],
      // This extractor will tell PurgeCSS to ignore all CSS selectors and tags used in your files
      defaultExtractor: content => Array.from(content.matchAll(/:?([A-Za-z0-9-_:/]+)/g)).map(x => x[1]) || []
    }),
  ]);
