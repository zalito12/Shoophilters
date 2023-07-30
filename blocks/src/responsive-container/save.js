import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function Edit(props) {
	const { attributes } = props;
	const blockProps = useBlockProps.save();

	const { blockId } = attributes;
	const { desktop, tablet, mobile } = attributes.visibility;
	const tabletWidth = attributes.breakpoint.tablet,
		mobileWidth = attributes.breakpoint.mobile;

	const style = `
	.shoophilters-responsive-${blockId}-parent, #shoophilters-responsive-${blockId} {
		display: ${desktop ? 'inherit' : 'none'}
	}
	@media (max-width: ${tabletWidth}px) {
		.shoophilters-responsive-${blockId}-parent, #shoophilters-responsive-${blockId} {
			display: ${tablet ? 'inherit' : 'none'}
		}
	}
	@media (max-width: ${mobileWidth}px) {
		.shoophilters-responsive-${blockId}-parent, #shoophilters-responsive-${blockId} {
			display: ${mobile ? 'inherit' : 'none'}
		}
	}
	`;

	const script = `
	document.getElementById('shoophilters-responsive-${blockId}').parentNode.classList.add('shoophilters-responsive-${blockId}-parent');
	`;

	return (
		<div {...blockProps} id={`shoophilters-responsive-${blockId}`}>
			<style>{style}</style>
			<script type="text/javascript">{script}</script>
			<InnerBlocks.Content />
		</div>
	);
}
