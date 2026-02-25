const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const customEntries = {
	'fotoperiodismo/js/admin-page/index': './assets/src/fotoperiodismo/js/admin-page/index.js',

	'fotoperiodismo/css/templates/archive-fotoperiodismo':
		'./assets/src/fotoperiodismo/css/templates/archive-fotoperiodismo.css',
};

module.exports = {
	...defaultConfig,

	entry: {
		...defaultConfig.entry(),
		...customEntries,
	},
};
