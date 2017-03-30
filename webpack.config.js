// Register docker specific workarounds
require('./webpack.docker').register();

const Webpack = require('webpack');
const CleanPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const pkg = require('./package.json');
const path = require('path');
const PATHS = {
    app: path.join(__dirname, 'src/Yay/Bundle/ApiBundle/Resources/public/jsx'),
    style: path.join(__dirname, 'src/Yay/Bundle/ApiBundle/Resources/public/sass'),
    build: path.join(__dirname, 'web/build')
};

module.exports  = {
    entry: {
        bundle: PATHS.app,
        vendor: Object.keys(pkg.dependencies)
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    output: {
        path: PATHS.build,
        filename: '[name].js',
        chunkFilename: '[chunkhash].js'
    },
    module: {
        loaders: [
            // Transpile JSX to JS
            {
                test: /\.jsx?$/,
                loaders: ['babel?cacheDirectory'],
                include: PATHS.app
            },
            // Generate CSS from SASS files
            {
                test: /\.scss$/,
                loader: ExtractTextPlugin.extract('css-loader!sass-loader'),
                include: PATHS.style
            },
            // Extract CSS during build
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract('style', 'css'),
                include: PATHS.style
            }
        ]
    },
    plugins: [
        // Removes all build files, including unknown files
        new CleanPlugin([PATHS.build]),
        // Output extracted CSS to a file
        new ExtractTextPlugin('bundle.css'),
        new Webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
        new Webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify('production')
        }),
        new Webpack.optimize.OccurrenceOrderPlugin(),
        new Webpack.optimize.AggressiveMergingPlugin(),
        new Webpack.optimize.CommonsChunkPlugin({
            names: ['vendor']
        }),
        // If multiple libs require the same third party libriaries webpack can remove dupes
        new Webpack.optimize.DedupePlugin(),
        // Surpress UglifyJS warnings
        new Webpack.optimize.UglifyJsPlugin({
            compress: { warnings: false },
            comments: false,
            sourceMap: false,
            mangle: true,
            minimize: true
        })
    ]
};
