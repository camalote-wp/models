const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const customEntries = {
	'fotoperiodismo/js/admin-page/index': './assets/src/fotoperiodismo/js/admin-page/index.js',

	'fotoperiodismo/css/templates/archive-fotoperiodismo':
		'./assets/src/fotoperiodismo/css/templates/archive-fotoperiodismo.css',
};

console.log(defaultConfig);

module.exports = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry(),
		...customEntries,
	},
	plugins: [new RemoveEmptyScriptsPlugin(), ...defaultConfig.plugins],
};
