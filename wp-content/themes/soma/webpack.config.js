const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: './js/main.js',
    mode: 'development',
    output: {
        path: __dirname + '/js',
        filename: 'main.bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [ // Cambiado de 'loader' a 'use'
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.(jpe?g|png|gif|woff|woff2|eot|ttf|svg)(\?[a-z0-9=.]+)?$/,
                loader: 'url-loader',
                options: {
                    limit: 100000
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../css/main.bundle.css'
        })
    ]
};
