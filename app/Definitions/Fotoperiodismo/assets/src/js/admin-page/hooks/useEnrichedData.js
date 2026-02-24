import { useState, useEffect, useRef, useMemo } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { getMigrationInfo } from '../helpers/validate-migration';

function useEnrichedData(view) {
	const queryArgs = useMemo(() => {
		const filters = {};
		view.filters.forEach((filter) => {
			if (filter.field === 'status' && filter.operator === 'isAny') {
				filters.status = filter.value;
			}
			if (filter.field === 'author' && filter.operator === 'is') {
				filters.author = filter.value;
			}
		});
		return {
			per_page: view.perPage,
			page: view.page,
			search: view.search,
			status: 'any',
			orderby: view.sort?.field || 'id',
			order: view.sort?.direction || 'desc',
			context: 'edit',
			...filters,
		};
	}, [view.filters, view.perPage, view.page, view.search, view.sort]);

	// Stabilize posts reference — getEntityRecords returns a new array
	// reference every call even when data hasn't changed
	const postsRef = useRef([]);

	const { posts, totalItems, isLoading } = useSelect(
		(select) => {
			const { getEntityRecords, getEntityRecordsTotalItems, isResolving } = select('core');
			const fetched = getEntityRecords('postType', 'fotoperiodismo', queryArgs) || [];

			const prevIds = postsRef.current.map((p) => p.id).join(',');
			const nextIds = fetched.map((p) => p.id).join(',');
			if (prevIds !== nextIds) postsRef.current = fetched;

			return {
				posts: postsRef.current,
				totalItems:
					getEntityRecordsTotalItems('postType', 'fotoperiodismo', queryArgs) || 0,
				isLoading: isResolving('getEntityRecords', [
					'postType',
					'fotoperiodismo',
					queryArgs,
				]),
			};
		},
		[queryArgs],
	);

	const base = useMemo(
		() =>
			posts.map((post) => ({
				id: post.id,
				title: post.title,
				date: post.date,
				status: post.status,
				meta: post.meta,
				link: post.link,
			})),
		[posts],
	);

	// Cache lives in a ref so reading it doesn't trigger re-renders
	const enrichCacheRef = useRef({});
	const [enrichedMap, setEnrichedMap] = useState({});

	// Reset cache when query changes (page, search, filters)
	useEffect(() => {
		enrichCacheRef.current = {};
		setEnrichedMap({});
	}, [queryArgs]);

	// Enrich base with migration status
	useEffect(() => {
		let cancelled = false;

		base.forEach(async (item) => {
			const cached = enrichCacheRef.current[item.id];
			if (cached && cached._metaRef === item.meta) return;

			const migration_status = await getMigrationInfo(item.meta);

			if (!cancelled) {
				enrichCacheRef.current[item.id] = { migration_status, _metaRef: item.meta };
				setEnrichedMap((prev) => ({ ...prev, [item.id]: migration_status }));
			}
		});

		return () => {
			cancelled = true;
		};
	}, [base]);

	const data = base.map((item) => ({
		...item,
		migration_status: enrichedMap[item.id],
	}));

	return { data, totalItems, isLoading };
}

export default useEnrichedData;
