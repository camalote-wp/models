const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const customEntries = {
	'fotoperiodismo/js/admin-page/index': './assets/src/fotoperiodismo/js/admin-page/index.js',

	'fotoperiodismo/css/templates/archive-fotoperiodismo':
		'./assets/src/fotoperiodismo/css/templates/archive-fotoperiodismo.css',

	'fotoperiodismo/css/templates/single-fotoperiodismo':
		'./assets/src/fotoperiodismo/css/templates/single-fotoperiodismo.css',

	'fotoperiodismo/css/patterns/index':
		'./assets/src/fotoperiodismo/css/patterns/index.css',

	'shared/index': './assets/src/shared/index.css',
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
