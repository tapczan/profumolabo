const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const cssExtractedFileName = 'theme';

process.env.PWD = process.cwd()

// PrestShop folders, we use process.env.PWD instead of __dirname in case the module is symlinked
const psRootDir = path.resolve(process.env.PWD, '../../../');
const psJsDir = path.resolve(psRootDir, 'back-office/themes/new-theme/js');
const psComponentsDir = path.resolve(psJsDir, 'components');
const psAppDir = path.resolve(psJsDir, 'app');


console.log(psComponentsDir);

module.exports = {
  externals: {
    jquery: 'jQuery',
  },
  entry: {
    createit_accordion: './createit_accordion',
    createit_accordion_form: './createit_accordion/form',
  },
  output: {
    path: path.resolve(__dirname, '../../views/js'),
    filename: '[name].bundle.js',
    libraryTarget: 'window',
    library: '[name]',
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      '@app': psAppDir,
      '@components': psComponentsDir,
    },
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        include: path.resolve(__dirname, '../createit_accordion'),
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              ['es2015', {modules: false}],
            ],
          },
        }],
      },
      {
        test: /\.js$/,
        include: psJsDir,
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              ['es2015', {modules: false}],
            ],
          },
        }],
      },
      {
        test: /jquery-ui\.js/,
        use: 'imports-loader?define=>false&this=>window',
      },
      {
        test: /jquery\.magnific-popup\.js/,
        use: 'imports-loader?define=>false&exports=>false&this=>window',
      },
      // FILES
      {
        test: /.(jpg|png|woff2?|eot|otf|ttf|svg|gif)$/,
        loader: 'file-loader?name=[hash].[ext]',
      },
    ],
  },
  plugins: [
    new CleanWebpackPlugin(['js'], {
      root: path.resolve(__dirname, '../../views')
    }),
    new webpack.ProvidePlugin({
      $: 'jquery', // needed for jquery-ui
      jQuery: 'jquery',
    }),
  ],
};
