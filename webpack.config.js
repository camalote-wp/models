const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const customEntries = {
	// 'shared/index': './assets/src/shared/index.css',
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
