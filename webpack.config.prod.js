/*
 * Production assets generation
 * Info: extract-text-webpack-plugin outdated!
 * See: https://github.com/webpack-contrib/extract-text-webpack-plugin
 * Since webpack v4 the extract-text-webpack-plugin should NOT be used for css.
 * Use mini-css-extract-plugin instead.
 */
const path = require('path');
const webpack = require('webpack');
const conf = require('./webpack.configuration');
const autoprefixer = require('autoprefixer');
const merge = require('webpack-merge');
const common = require('./webpack.config.common.js');


const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const WebappWebpackPlugin = require('webapp-webpack-plugin');

module.exports = merge(common, {
    devtool: '',

    output: {
        path: path.join(__dirname, conf.DIST),
        filename: 'js/[name].js',
        publicPath: conf.DIST + '/',
    },

    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                cache: true,
                parallel: true,
                sourceMap: true // set to true if you want JS source maps
            }),
            new OptimizeCSSAssetsPlugin({})
        ]
    },
    module: {
        // Array of rules that tells Webpack how the modules (output)
        // will be created
        rules: [
                // Look for Css files and process them according to the
                // rules specified in the different loaders
            {
                test:/\.css$/,
                use:['style-loader','css-loader']
            },
            {
                // Look for Sass files and process them according to the
                // rules specified in the different loaders
                test: /\.(sa|sc)ss$/,

                // Use the following loaders from right-to-left, so it will
                // use sass-loader first and ending with MiniCssExtractPlugin
                use: [{
                        // Extracts the CSS into a separate file and uses the
                        // defined configurations in the 'plugins' section
                        loader: MiniCssExtractPlugin.loader
                    },
                    /*{   loader: 'style-loader', // inject CSS to page },*/
                    {
                        // Interprets CSS
                        loader: "css-loader", // translates CSS into CommonJS modules
                        options: {
                            // // 0 => no loaders (default); 1 => postcss-loader; 2 => postcss-loader, sass-loader
                            importLoaders: 2,
                            sourceMap: false
                        }
                    },
                    {
                        // Use PostCSS to minify and autoprefix with vendor rules
                        // for older browser compatibility
                        loader: "postcss-loader",  // post css plugins, can be exported to postcss.config.js
                        options: {
                            sourceMap: false,
                            plugins: [
                                autoprefixer({
                                    // If we want to use the same browser list for more tools
                                    // this list should be moved to package.json
                                    // https://evilmartians.com/chronicles/autoprefixer-7-browserslist-2-released
                                    browsers: [
                                        'ie >= 11',
                                        'ie_mob >= 11',
                                        'Safari >= 10',
                                        'Android >= 4.4',
                                        'Chrome >= 44', // Retail
                                        'Samsung >= 4'
                                    ]
                                })
                            ]
                        }
                    },
                    {
                        // Adds support for Sass files, if using Less, then
                        // use the less-loader
                        loader: 'resolve-url-loader'
                    },
                    {
                        // Provides the "url rewriting" that Sass is missing.
                        // Uuse it after the transpiler (such as sass-loader).
                        loader: "sass-loader", // compiles Sass to CSS
                        options: {
                            sourceMap: true,
                            sourceMapContents: false
                        }
                    }
                ]
            },
            {
                // Adds support to load fontawesome in your CSS rules. It looks for
                // .ttf ,.otf, .eot, .svg and .woff within /fontawesome
                test: /fontawesome([^.]+).(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'fonts/',
                        publicPath: '../fonts/'
                    }
                }]
            }, {
                // Adds support to load fonts in your CSS rules. It looks for
                // .ttf ,.otf, .eot, .svg and .woff
                test: /\.(ttf|otf|eot|svg|woff(2)?)$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'fonts/',
                        publicPath: '../fonts/'
                    }
                }]
            }, {
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
        ]
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify('production')
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true,
            debug: false
        }),
        new webpack.optimize.ModuleConcatenationPlugin(),
        /*
        // See https://stackoverflow.com/questions/49053215/webpack-4-how-to-configure-minimize
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: false,
            comments: false
        }),*/

        /* replaced
        new ExtractTextPlugin({
            filename: 'css/[name].css',
            allChunks: true
        }), */
        new MiniCssExtractPlugin({
            filename: 'css/[name].css'
        }),
        new WebappWebpackPlugin({
            logo: path.join(__dirname, conf.SRC) + '/favicon.png',
            prefix: '/icons/',
            statsFilename: conf.DIST + '/icons/iconstats.json',
            icons: {
                android: true,
                appleIcon: true,
                appleStartup: true,
                coast: true,
                favicons: true,
                firefox: true,
                opengraph: true,
                twitter: true,
                yandex: true,
                windows: true
            }
        })
    ]
});
