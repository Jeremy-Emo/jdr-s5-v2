var Encore = require('@symfony/webpack-encore');

const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addEntry('searchBar', './assets/js/searchBarForStupidtable.js')
    .addEntry('collectionType', './assets/js/collectionType.js')
    .addEntry('skillList', './assets/js/skillList.js')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enableSassLoader()
    .configureFilenames({
        images: '[path][name].[hash:8].[ext]',
    })

    .addPlugin(new UglifyJSPlugin())
    .addPlugin(new CompressionPlugin())
;

module.exports = Encore.getWebpackConfig();