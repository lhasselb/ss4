/*
 * Development assets generation
 */
const path = require("path");
const webpack = require('webpack');
const conf = require('./webpack.configuration');
const autoprefixer = require('autoprefixer');
const merge = require('webpack-merge');
const common = require('./webpack.config.common.js');


// Added for webpack 4
const ExtractCssChunks = require("extract-css-chunks-webpack-plugin");

const IP = process.env.IP || conf.HOSTNAME;
const PORT = process.env.PORT || conf.PORT;

const config = merge.strategy({
    entry: 'prepend'
})(common, {

    entry: {
        app: [
            'react-hot-loader/patch',
            'webpack-dev-server/client?https://' + conf.HOSTNAME + ':' + conf.PORT,
            'webpack/hot/only-dev-server',
        ]
    },

    output: {
        path: path.join(__dirname, conf.DIST),
        filename: '[name].js',
        // necessary for HMR to know where to load the hot update chunks
        publicPath: 'https://' + conf.HOSTNAME + ':' + conf.PORT + '/'
    },

    module: {
        rules: [
            // Look for Css files and process them according to the
            // rules specified in the different loaders
            {
                test:/\.css$/,
                use:['style-loader','css-loader']
            },
            {
            test: /\.scss$/,
            use: [{
                // HMR can only work with “loaders” that implement and understand HMR API
                loader: ExtractCssChunks.loader
            },
            /** {
                loader: 'style-loader',
                options: {
                    sourceMap: true
                }
            },*/
            {
                loader: 'css-loader',
                options: {
                    sourceMap: true
                }
            },
            {
                loader: 'postcss-loader',
                options: {
                    sourceMap: true,
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
            {   loader: 'resolve-url-loader' },
            {
                loader: 'sass-loader',
                options: {
                    sourceMap: true
                }
            }, ]
        }, {
            test: /fontawesome([^.]+).(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
            use: [{
                loader: 'url-loader'
            }]
        }, {
            test: /\.(cur|gif|png|jpg|jpeg|ttf|otf|eot|svg|woff(2)?)$/,
            use: [{
                loader: 'url-loader'
            }]
        }]
    },
    plugins: [
        new ExtractCssChunks(
            {
              // Options similar to the same options in webpackOptions.output
              // both options are optional
              filename: "[name].css",
              chunkFilename: "[id].css",
              hot: true, // if you want HMR - we try to automatically inject hot reloading but if it's not working, add it to the config
              orderWarning: true, // Disable to remove warnings about conflicting order between imports
              reloadAll: true, // when desperation kicks in - this is a brute force HMR flag
              cssModules: true // if you use cssModules, this can help.
            }
        ),
        new webpack.NamedModulesPlugin(),
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoEmitOnErrorsPlugin(),
    ],
    /*
    resolve:{
        alias: {
          "TweenLite": "gsap"
        }
    },
    */
    devServer: {
        host: IP,
        port: PORT,
        historyApiFallback: true,
        hot: false,
        clientLogLevel: 'info',
        contentBase: [
            path.resolve(__dirname, 'public'),
            'node_modules'
        ],
        //watchContentBase: true,
        overlay: {
            warnings: true,
            errors: true
        },
        headers: {
            'Access-Control-Allow-Origin': '*'
        }
    },
});

module.exports = config;
