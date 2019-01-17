const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    mode: 'development',
    entry: {
        admin: './resources/assets/js/typescript/main.ts'
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: [/node_modules/, /vendor/, /lib/, /scss/, /\.css$/, /\.scss$/, /\.js$/]
            },
            {
                test: /\.vue$/,
                use: 'vue-loader'
            },
            {
                test: /\.css$/,
                loader:[ 'style-loader', 'css-loader' ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin()
    ],
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        },
        extensions: ['.tsx', '.ts', '.js', '.vue']
    },
    output: {
        filename: '[name].bundle.js',
        chunkFilename: 'vendor.bundle.js',
        path: path.resolve(__dirname, 'resources/assets/js')
    }
};