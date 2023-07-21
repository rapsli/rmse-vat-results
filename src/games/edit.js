/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import { TextControl, CheckboxControl, Placeholder, __experimentalNumberControl as NumberControl } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	return (
		<div {...useBlockProps()}>
			<h5>{__('Next Games / Last Results of the club', 'tc-shv-results')}</h5>
			<div class="instructions">
				{__('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'tc-shv-results')}
			</div>
			<NumberControl
				isShiftStepEnabled={true}
				label={__('Last Results', 'tc-shv-results')}
				shiftStep={5}
				value={attributes.results}
				onChange={(val) => setAttributes({ results: val })}
			/>
			<NumberControl
				isShiftStepEnabled={true}
				label={__('Scheduled Games', 'tc-shv-results')}
				shiftStep={5}
				value={attributes.scheduled}
				onChange={(val) => setAttributes({ scheduled: val })}
			/>
			<TextControl
				label={__('Date Format', 'tc-shv-results')}
				value={attributes.dateformat}
				onChange={(val) => setAttributes({ dateformat: val })}
			/>
			<CheckboxControl
				label={__('Show Header?', 'tc-shv-results')}
				checked={attributes.header}
				onChange={(val) => setAttributes({ header: val })}
			/>
			<CheckboxControl
				label={__('Show game type?', 'tc-shv-results')}
				checked={attributes.type}
				onChange={(val) => setAttributes({ type: val })}
			/>
			<CheckboxControl
				label={__('Show Venue?', 'tc-shv-results')}
				checked={attributes.venue}
				onChange={(val) => setAttributes({ venue: val })}
			/>
		</div>
	);
}
