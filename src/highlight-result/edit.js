/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import { TextControl, CheckboxControl, __experimentalNumberControl as NumberControl, Flex, FlexBlock, SelectControl } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import logo from '../../assets/demo-logo.svg';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	// this is actually a little bit dirty with global variables, but...

	const teams = tc_shv_team_selection.map((x) => ({ label: x.name, value: x.id }));

	return (
		<div {...useBlockProps()}>
			{
				<InspectorControls key="settings">
					<h5>{__('Next Games / Last Results of the club', 'tc-shv-results')}</h5>
					<div className="instructions">
						{__('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'tc-shv-results')}
					</div>
					<SelectControl
						label={__('Team', 'tc-shv-results')}
						value={attributes.team}
						options={teams}
						onChange={(val) => setAttributes({ team: val })}
					/>
					<TextControl
						label={__('Date Format', 'tc-shv-results')}
						value={attributes.dateformat}
						onChange={(val) => setAttributes({ dateformat: val })}
					/>
					<CheckboxControl
						label={__('Show Venue?', 'tc-shv-results')}
						checked={attributes.venue}
						onChange={(val) => setAttributes({ venue: val })}
					/>
					<CheckboxControl
						label={__('Show Logos?', 'tc-shv-results')}
						checked={attributes.logos}
						onChange={(val) => setAttributes({ logos: val })}
					/>
					<CheckboxControl
						label={__('Show Team Names?', 'tc-shv-results')}
						checked={attributes.names}
						onChange={(val) => setAttributes({ names: val })}
					/>
					<CheckboxControl
						label={__('Show Halftime Result?', 'tc-shv-results')}
						checked={attributes.halftime}
						onChange={(val) => setAttributes({ halftime: val })}
					/>
					<CheckboxControl
						label={__('Show Spectators?', 'tc-shv-results')}
						checked={attributes.spectators}
						onChange={(val) => setAttributes({ spectators: val })}
					/>
					<TextControl
						label={__('Logo Size', 'tc-shv-results')}
						value={attributes.logosize}
						onChange={(val) => setAttributes({ logosize: val })}
					/>
				</InspectorControls>
			}

			<div className="tc-shv-results-highlight-result">
				<div className="tc-shv-results-highlight-home">
					{attributes.logos &&
						<div className="tc-shv-results-highlight-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Team A"/></div>
					}
					{attributes.names &&
						<div className="tc-shv-results-highlight-name">Team A</div>
					}
				</div>

				<div className="tc-shv-results-highlight-info">
					<div className="tc-shv-results-highlight-info-result">33:31</div>
					{attributes.halftime &&
						<div className="tc-shv-results-highlight-info-halftime">18:17</div>
					}
					<div className="tc-shv-results-highlight-info-date">21.10.23</div>
					{attributes.venue &&
						<div className="tc-shv-results-highlight-info-venue">Halle X</div>
					}
					{attributes.spectators &&
						<div className="tc-shv-results-highlight-info-spectators">{ __('Spectators', 'tc-shv-results') }: 200</div>
					}
				</div>
				<div className="tc-shv-results-highlight-guest">
					{attributes.logos &&
						<div className="tc-shv-results-highlight-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Team B"/></div>
					}
					{attributes.names &&
						<div className="tc-shv-results-highlight-name">Team B</div>
					}
				</div>
			</div>
		</div >
	);
}
