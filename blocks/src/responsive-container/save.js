import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function Edit(props) {
	const { attributes } = props;
	const blockProps = useBlockProps.save();

	const { blockId } = attributes;
	const { desktop, tablet, mobile } = attributes.visibility;
	const tabletWidth = attributes.breakpoint.tablet,
		mobileWidth = attributes.breakpoint.mobile;

	const style = `
	.woofilters-responsive-${blockId}-parent, #woofilters-responsive-${blockId} {
		display: ${desktop ? 'inherit' : 'none'}
	}
	@media (max-width: ${tabletWidth}px) {
		.woofilters-responsive-${blockId}-parent, #woofilters-responsive-${blockId} {
			display: ${tablet ? 'inherit' : 'none'}
		}
	}
	@media (max-width: ${mobileWidth}px) {
		.woofilters-responsive-${blockId}-parent, #woofilters-responsive-${blockId} {
			display: ${mobile ? 'inherit' : 'none'}
		}
	}
	`;

	const script = `
	document.getElementById('woofilters-responsive-${blockId}').parentNode.classList.add('woofilters-responsive-${blockId}-parent');
	`;

	return (
		<div {...blockProps} id={`woofilters-responsive-${blockId}`}>
			<style>{style}</style>
			<script type="text/javascript">{script}</script>
			<InnerBlocks.Content />
		</div>
	);
}
