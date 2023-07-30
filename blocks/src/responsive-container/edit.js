import { __ } from '@wordpress/i18n';
import {
	InnerBlocks,
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';
import './editor.scss';

import { PanelBody, PanelRow, TextControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param  props
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit(props) {
	const { attributes, setAttributes, clientId } = props;

	useEffect(() => {
		!attributes.blockId && setAttributes({ blockId: clientId });
	}, []);

	const { desktop, tablet, mobile } = attributes.visibility;
	const tabletWidth = attributes.breakpoint.tablet,
		mobileWidth = attributes.breakpoint.mobile;

	const getSideControls = () => {
		return (
			<InspectorControls>
				<PanelBody
					title={__('Visibility settings', 'woofilters')}
					initialOpen={true}
				>
					<PanelRow>
						<div className="woofilters-group">
							<div
								className={`woofilters-group-checkbox ${
									desktop ? 'active' : ''
								}`}
								role="presentation"
								onClick={() =>
									setAttributes({
										visibility: {
											desktop: !desktop,
											tablet,
											mobile,
										},
									})
								}
							>
								<span className="dashicon dashicons dashicons-desktop"></span>
							</div>
							<div
								className={`woofilters-group-checkbox ${
									tablet ? 'active' : ''
								}`}
								role="presentation"
								onClick={() =>
									setAttributes({
										visibility: {
											desktop,
											tablet: !tablet,
											mobile,
										},
									})
								}
							>
								<span className="dashicon dashicons dashicons-tablet"></span>
							</div>
							<div
								className={`woofilters-group-checkbox ${
									mobile ? 'active' : ''
								}`}
								role="presentation"
								onClick={() =>
									setAttributes({
										visibility: {
											desktop,
											tablet,
											mobile: !mobile,
										},
									})
								}
							>
								<span className="dashicon dashicons dashicons-smartphone"></span>
							</div>
						</div>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Tablet width breakpoint (px)"
							value={tabletWidth}
							onChange={(value) =>
								setAttributes({
									breakpoint: {
										tablet: value,
										mobile: mobileWidth,
									},
								})
							}
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Mobile width breakpoint (px)"
							value={mobileWidth}
							onChange={(value) =>
								setAttributes({
									breakpoint: {
										tablet: tabletWidth,
										mobile: value,
									},
								})
							}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
		);
	};

	return (
		<div {...useBlockProps()}>
			{getSideControls()}
			<InnerBlocks
				placeholder="Empty responsive container"
				renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
			/>
		</div>
	);
}
