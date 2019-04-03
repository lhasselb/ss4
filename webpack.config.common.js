/*
 * Common Environment
 */


const path = require('path');
const webpack = require('webpack');
const conf = require('./webpack.configuration');

// Added to copy svg logo (not referenced within scss)
const CopyWebpackPlugin = require('copy-webpack-plugin')
// Added for moment.js used by fullcalendar
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

const includes = {
    app: path.join(__dirname, conf.SRC, 'js/app.js'),
};

const _getAllFilesFromFolder = function (dir) {
    dir = path.resolve(__dirname, dir);

    const filesystem = require('fs');
    let results = [];

    filesystem.readdirSync(dir).forEach((file) => {
        if (file === '_notes') {
            return;
        }

        file = `${dir}/${file}`;
        const stat = filesystem.statSync(file);

        if (stat && stat.isDirectory()) {
            results = results.concat(_getAllFilesFromFolder(file));
        } else {
            results.push(file);
        }
    });

    return results;
};

// add page specific scripts
const pageScripts = _getAllFilesFromFolder(conf.TYPESJS);
pageScripts.forEach((file) => {
    includes[path.basename(file, '.js')] = file;
});

// add page specific scss
const scssIncludes = _getAllFilesFromFolder(conf.TYPESSCSS);
scssIncludes.forEach((file) => {
    includes[path.basename(file, '.scss')] = file;
});

module.exports = {
    entry: includes,
    devtool: 'source-map',
    externals: {
        // shows how we can rely on browser globals instead of bundling these dependencies,
        // in case we want to access jQuery from a CDN or if we want an easy way to
        // avoid loading all moment locales: https://github.com/moment/moment/issues/1435
        //moment: 'moment'; <= does not work as expected
        jquery: 'jQuery'
    },
    module: {
        rules: [{
            test: /\.jsx?$/,
            exclude: /node_modules/,
            use: {
                loader: 'babel-loader',
                options: {
                    presets: [
                        ['@babel/preset-env', {
                            modules: false,
                        }],
                        //['@babel/preset-stage-2'],
                    ],
                    plugins: [
                        ['transform-react-jsx'],
                        ['react-hot-loader/babel'],
                    ],
                },
            },
        },
        {
            test: /\.tsx?$/,
            use: 'ts-loader',
            exclude: /node_modules/,
        },
        {
            test: /\.coffee?$/,
            use: 'coffee-loader',
        },
/*
        {
            // Adds support to load images in your CSS rules. It looks for
            // .png, .jpg, .jpeg, .gif, svg and cur
            test: /\.(png|jpg|jpeg|gif|svg|cur)$/,
            loader: 'file-loader',
            options: {
                // The image will be named with the original name and
                // extension
                name: '[name].[ext]',
                outputPath: 'img/',
                publicPath: '../img/',
                //emitFile: true,
                //useRelativePaths: true
            }
        },
*/
        {
            test: /\.worker\.js$/,
            use: {
                loader: 'worker-loader',
            },
        }],
    },
    resolve: {
        modules: [
            path.resolve(__dirname, 'public'),
            'node_modules'
        ],
        alias: {
            'jquery': require.resolve('jquery'),
            'jQuery': require.resolve('jquery'),
            'TweenLite': require.resolve('gsap')
        },
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            Popper: ['popper.js', 'default'],
            Util: 'exports-loader?Util!bootstrap/js/dist/util',
            Alert: 'exports-loader?Alert!bootstrap/js/dist/alert',
            Button: 'exports-loader?Button!bootstrap/js/dist/button',
            Carousel: 'exports-loader?Carousel!bootstrap/js/dist/carousel',
            Collapse: 'exports-loader?Collapse!bootstrap/js/dist/collapse',
            Dropdown: 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
            Modal: 'exports-loader?Modal!bootstrap/js/dist/modal',
            Tooltip: 'exports-loader?Tooltip!bootstrap/js/dist/tooltip',
            Popover: 'exports-loader?Popover!bootstrap/js/dist/popover',
            Scrollspy: 'exports-loader?Scrollspy!bootstrap/js/dist/scrollspy',
            Tab: 'exports-loader?Tab!bootstrap/js/dist/tab',
        }),
        new MomentLocalesPlugin({
            localesToKeep: ['de'],
        }),
        // Copy assets not referenced within scss or js
        new CopyWebpackPlugin([
            // Copy logo
            {
                from: 'app/client/src/img/logo',
                to: 'img/logo'
            },
            // Copy ModelAdmin and Page icons
            {
                from: 'app/client/src/icons',
                to: 'img'
            },
            // revolution-slider
            {
                from: 'app/client/src/img/layout/sliders/revo-slider/base/blank.png',
                to: 'img/blank.png'
            },
            {
                from: 'app/client/src/thirdparty/revolution/js',
                to: 'js/thirdparty/revolution',
                ignore: ['*.php']
            },
            {
                from: 'app/client/src/thirdparty/revolution/css',
                to: 'css/thirdparty/revolution',
                ignore: ['*.php']
            },
            // cube portfolio
            {
                from: 'app/client/src/thirdparty/cubeportfolio/js',
                to: 'js/thirdparty/cubeportfolio'
            },
            {
                from: 'app/client/src/thirdparty/cubeportfolio/css',
                to: 'css/thirdparty/cubeportfolio'
            },
            // galleria
            {
                from: 'app/client/src/thirdparty/galleria/themes/',
                to: 'js/thirdparty/galleria/themes',
                //ignore: ['*.gif','*.png','*.html', 'css']
            },
            {
                from: 'app/client/src/thirdparty/galleria/themes/',
                to: 'css/thirdparty/galleria/themes',
                ignore: ['*.js','*.html']
            },
            /*
            // Skip fonts because reset (within Jimev.Pages.HomePageController.scss) will copy them
            {   from: 'app/client/src/thirdparty/revolution/fonts',
                to: 'fonts/thirdparty/revolution',
                ignore: [ '*.php' ]
            },
            */
        ])
    ],
};
