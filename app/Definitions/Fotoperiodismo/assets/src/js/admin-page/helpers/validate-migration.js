import { validateObjectShape } from './validate';
import { normalizeUrl } from './shared';

export const MIGRATION_STATUS = {
	VALID: 'valid',
	INVALID: 'invalid',
	INCOMPLETE: 'incomplete',
	NO_LEGACY: 'no_legacy',
};

export const MIGRATION_MESSAGES = {
	valid: 'Migration complete',
	invalid: 'Invalid migration',
	incomplete: 'Not migrated',
	no_legacy: 'No migration needed',
};

function hasLegacyData(meta) {
	if (!meta || typeof meta !== 'object') return false;
	const gallery = meta._crb_enfantterrible_fotoperiodismo_gallery;
	const authors = meta._crb_enfantterrible_fotoperiodismo_authors;
	return (
		(Array.isArray(gallery) && gallery.length > 0) ||
		(Array.isArray(authors) && authors.length > 0)
	);
}

function validateImagesMigration(meta) {
	const legacy = meta._crb_enfantterrible_fotoperiodismo_gallery || [];
	const current = meta['et-models_fotoperiodismo_images'] || [];
	if (!legacy.length) return true;
	if (!Array.isArray(current) || current.length !== legacy.length) return false;
	const legacyIds = legacy.map((id) => String(parseInt(id, 10)));
	const currentIds = current.map((img) => img.id);
	return legacyIds.every((id, i) => id === currentIds[i]);
}

export function getAuthorSignatures(authors) {
	return authors.map((a) => {
		const name = (a.name || a.nombre || '').trim().toLowerCase();
		const url = normalizeUrl(a.url || a.link || '');
		return `${name}|${url}`;
	});
}

function validateAuthorsMigration(meta) {
	const legacy = (meta._crb_enfantterrible_fotoperiodismo_authors || []).filter((a) =>
		(a.nombre || a.link || '').trim(),
	);
	const current = meta['et-models_fotoperiodismo_authors'] || [];
	if (!legacy.length) return true;
	const legacySigs = getAuthorSignatures(legacy).sort();
	const currentSigs = getAuthorSignatures(current).sort();
	return legacySigs.every((sig, i) => sig === currentSigs[i]);
}

export async function getMigrationInfo(meta) {
	if (!hasLegacyData(meta)) {
		return {
			status: MIGRATION_STATUS.NO_LEGACY,
			message: MIGRATION_MESSAGES.no_legacy,
			needsMigration: false,
		};
	}

	if (!validateObjectShape(meta)) {
		return {
			status: MIGRATION_STATUS.INCOMPLETE,
			message: MIGRATION_MESSAGES.incomplete,
			needsMigration: true,
		};
	}

	const imagesValid = validateImagesMigration(meta);
	const authorsValid = validateAuthorsMigration(meta);

	if (imagesValid && authorsValid) {
		return {
			status: MIGRATION_STATUS.VALID,
			message: MIGRATION_MESSAGES.valid,
			needsMigration: false,
		};
	}

	return {
		status: MIGRATION_STATUS.INVALID,
		message: MIGRATION_MESSAGES.invalid,
		needsMigration: true,
	};
}
