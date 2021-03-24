const path = require('path');
const StyleLintPlugin = require('stylelint-webpack-plugin');

module.exports = new StyleLintPlugin({
  context: path.resolve(__dirname, '../../src/assets/css'),
  files: '**/*.css',
  failOnError: false,
  quiet: true,
});
