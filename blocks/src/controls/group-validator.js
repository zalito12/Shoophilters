const normalizeId = (value) => {
	const id = (value || '')
		.replace(/ /g, '-')
		.replace(/--/g, '-')
		.replace(/^[0-9]/, '')
		.replace(/^[-]/, '')
		.replace(/[^A-Za-z0-9-]/g, '')
		.toLowerCase();

	return id || 'default';
};

export default normalizeId;
