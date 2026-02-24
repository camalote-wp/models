import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import '@wordpress/core-data'; // This registers the 'core' store
import { MigrationDataview } from './components';

domReady(() => {
	const rootElement = document.getElementById('fotoperiodismo-migration-page');

	if (rootElement) {
		const root = createRoot(rootElement);
		root.render(<MigrationDataview />);
	} else {
		console.error('Root element not found');
	}
});
