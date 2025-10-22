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

	const baseTeams = (window.rmse_vat_team_selection ?? []).map((x) => ({ label: x.name, value: String(x.id) }));

	// Optionen: normale Teams + „ACF verwenden…“
	const teamOptions = [
		// { label: __('— Select team —', 'rmse-vat-results'), value: '' },
		...baseTeams,
		{ label: __('Use ACF field…', 'rmse-vat-results'), value: '__acf__' },
	];

	
	const matchReportOptions = [
		{ label: __('No match report', 'rmse-vat-results'), value: '' },
		{ label: __('Static URL', 'rmse-vat-results'), value: 'static' },
		{ label: __('Dynamic URL (from ACF)', 'rmse-vat-results'), value: 'acf' },
	];

	// aktueller Wert im Select: mappe Quelle → Select-Wert
	const selectValue = attributes.teamSource === 'acf' ? '__acf__' : (attributes.team ?? '');

	const selectMatchReportValue = attributes.matchreportSource === 'acf' ? '__acf__' : (attributes.matchreport ?? '');

	return (
		<div {...useBlockProps()}>
			{
				<InspectorControls key="settings">
					<h5>{__('Next Games / Last Results of the club', 'rmse-vat-results')}</h5>
					<div className="instructions">
						{__('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'rmse-vat-results')}
					</div>

					<SelectControl
						label={__('Team', 'rmse-vat-results')}
						value={selectValue}
						options={teamOptions}
						onChange={(val) => {
							if (val === '__acf__') {
								setAttributes({ teamSource: 'acf' });
							} else {
								setAttributes({ teamSource: 'static', team: val });
							}
						}}
						help={
							selectValue === '__acf__'
								? __('Team ID is read from the specified ACF field.', 'rmse-vat-results')
								: __('Select team manually.', 'rmse-vat-results')
						}
					/>

					{attributes.teamSource === 'acf' && (
						<TextControl
							label={__('ACF field name/key (liefert Team-ID)', 'rmse-vat-results')}
							value={attributes.teamAcfField ?? ''}
							onChange={(val) => setAttributes({ teamAcfField: val })}
							placeholder="z. B. team_id"
							help={__('Specify the ACF field name that returns the team ID (or a select field with value=ID).', 'rmse-vat-results')}
						/>
					)}
					<TextControl
						label={__('Date Format', 'rmse-vat-results')}
						value={attributes.dateformat}
						onChange={(val) => setAttributes({ dateformat: val })}
					/>
					<CheckboxControl
						label={__('Show Venue?', 'rmse-vat-results')}
						checked={attributes.venue}
						onChange={(val) => setAttributes({ venue: val })}
					/>
					<CheckboxControl
						label={__('Show Logos?', 'rmse-vat-results')}
						checked={attributes.logos}
						onChange={(val) => setAttributes({ logos: val })}
					/>
					<CheckboxControl
						label={__('Show Team Names?', 'rmse-vat-results')}
						checked={attributes.names}
						onChange={(val) => setAttributes({ names: val })}
					/>
					<CheckboxControl
						label={__('Show Halftime Result?', 'rmse-vat-results')}
						checked={attributes.halftime}
						onChange={(val) => setAttributes({ halftime: val })}
					/>
					<CheckboxControl
						label={__('Show Spectators?', 'rmse-vat-results')}
						checked={attributes.spectators}
						onChange={(val) => setAttributes({ spectators: val })}
					/>
					<TextControl
						label={__('Logosize retrieved from server', 'rmse-vat-results')}
						value={attributes.logosize}
						onChange={(val) => setAttributes({ logosize: val })}
					/>
					<SelectControl
						label={__('Match report', 'rmse-vat-results')}
						value={selectMatchReportValue}
						options={matchReportOptions}
						onChange={(val) => {
							if (val === '__acf__') {
								setAttributes({ matchreportSource: 'acf' });
							} else {
								setAttributes({ matchreportSource: 'static', matchreport: val });
							}
						}}
						help={
							selectMatchReportValue === '__acf__'
								? __('Match report is read from the specified ACF field.', 'rmse-vat-results')
								: __('Select match report manually.', 'rmse-vat-results')
						}
					/>

					{(attributes.matchreportSource === 'acf' || attributes.matchreportSource === 'static') && (
						<TextControl
							label={__('ACF field URL (liefert Matchbericht URL)', 'rmse-vat-results')}
							value={attributes.matchreportField ?? ''}
							onChange={(val) => setAttributes({ matchreportField: val })}
							placeholder="z. B. match_report_url or acf field name"
							help={__('Specify the ACF field name that returns an valid URL or a static URL.', 'rmse-vat-results')}
						/>
					)}
					<SelectControl
						label={__('Layout', 'rmse-vat-results')}
						value={attributes.layout}
						options={[
							{ label: __('Legacy (old)', 'rmse-vat-results'), value: 'legacy' },
							{ label: __('Modern (new)', 'rmse-vat-results'), value: 'modern' },
						]}
						onChange={(val) => setAttributes({ layout: val })}
					/>
				</InspectorControls>
			}

			<div className="rmse-vat-results-highlight-result">
				<div className="rmse-vat-results-highlight-home">
					{attributes.logos &&
						<div title="Team A" className="rmse-vat-results-highlight-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Team A" /></div>
					}
					{attributes.names &&
						<div className="rmse-vat-results-highlight-name">Team A</div>
					}
				</div>

				<div className="rmse-vat-results-highlight-info">
					<div className="rmse-vat-results-highlight-info-result">33:31</div>
					{attributes.halftime &&
						<div className="rmse-vat-results-highlight-info-halftime">18:17</div>
					}
					<div className="rmse-vat-results-highlight-info-date">21.10.23</div>
					{attributes.venue &&
						<div className="rmse-vat-results-highlight-info-venue">
							<a href="#">Halle X</a>
						</div>
					}
					{attributes.spectators &&
						<div className="rmse-vat-results-highlight-info-spectators">{__('Spectators', 'rmse-vat-results')}: 200</div>
					}
				</div>
				<div className="rmse-vat-results-highlight-guest">
					{attributes.logos &&
						<div title="Team B" className="rmse-vat-results-highlight-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Team B" /></div>
					}
					{attributes.names &&
						<div className="rmse-vat-results-highlight-name">Team B</div>
					}
				</div>
			</div>
		</div >
	);
}
