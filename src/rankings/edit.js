/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl, SelectControl, TextControl } from '@wordpress/components';

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

	const teams = rmse_vat_team_selection.map((x) => ({ label: x.name, value: x.id }));

	return (
		<div {...useBlockProps()}>
			<InspectorControls key="settings">
				<h5>{__('Ranking of a team\'s group', 'rmse-vat-results')}</h5>
				<div className="instructions">
					{__('Choose the team and whether headers should be displayed as well as whether the HTML from the tanking should be shown', 'rmse-vat-results')}
				</div>
				<SelectControl
					label={__('Team', 'rmse-vat-results')}
					value={attributes.team}
					options={teams}
					onChange={(val) => setAttributes({ team: val })}
				/>
				<CheckboxControl
					label={__('Show Header?', 'rmse-vat-results')}
					checked={attributes.header}
					onChange={(val) => setAttributes({ header: val })}
				/>
				<CheckboxControl
					label={__('Show Logo?', 'rmse-vat-results')}
					checked={attributes.logo}
					onChange={(val) => setAttributes({ logo: val })}
				/>
				<TextControl
						label={__('Logosize retrieved from server', 'rmse-vat-results')}
						value={attributes.logosize}
					onChange={(val) => setAttributes({ logosize: val })}
				/>
			</InspectorControls>

			<div>
				<h3 className="rmse-vat-results-rankings-header">Leaguename (LG)</h3>
				<table className="rmse-vat-results-table">
					{attributes.header &&
						<thead>
							<tr>
								<th className="rmse-vat-results-rankings-rank">{__('Rank', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-team" colSpan={attributes.logo ? 2 : 1}>{__('Team', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-games">{__('G', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-wins">{__('W', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-draws">{__('D', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-losses">{__('L', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-diff">{__('+/-', 'rmse-vat-results')}</th>
								<th className="rmse-vat-results-rankings-points">{__('Pts', 'rmse-vat-results')}</th>
							</tr>
						</thead>
					}
					<tbody>
						<tr className="rmse-vat-results-rankings-promotion">
							<td className="rmse-vat-results-rankings-rank">1</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Promotion Team', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">5</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">0</td>
							<td className="rmse-vat-results-rankings-diff">100 (100:0)</td>
							<td className="rmse-vat-results-rankings-points">10</td>
						</tr>
						<tr className="rmse-vat-results-rankings-promotion-candidate">
							<td className="rmse-vat-results-rankings-rank">2</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Promotion Candidate', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">4</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">1</td>
							<td className="rmse-vat-results-rankings-diff">90 (100:10)</td>
							<td className="rmse-vat-results-rankings-points">8</td>
						</tr>
						<tr className="">
							<td className="rmse-vat-results-rankings-rank">3</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Midfield Team', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">4</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">1</td>
							<td className="rmse-vat-results-rankings-diff">90 (100:10)</td>
							<td className="rmse-vat-results-rankings-points">8</td>
						</tr>
						<tr className="rmse-vat-results-rankings-own-team">
							<td className="rmse-vat-results-rankings-rank">4</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Our own team', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">4</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">1</td>
							<td className="rmse-vat-results-rankings-diff">90 (100:10)</td>
							<td className="rmse-vat-results-rankings-points">8</td>
						</tr>
						<tr className="rmse-vat-results-rankings-relegation-candidate">
							<td className="rmse-vat-results-rankings-rank">5</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Relegation Candidate', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">4</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">1</td>
							<td className="rmse-vat-results-rankings-diff">90 (100:10)</td>
							<td className="rmse-vat-results-rankings-points">8</td>
						</tr>
						<tr className="rmse-vat-results-rankings-relegation">
							<td className="rmse-vat-results-rankings-rank">5</td>
							{attributes.logo &&
								<td className="rmse-vat-results-rankings-team-logo"><img src={logo} height={attributes.logosize} width={attributes.logosize} alt="Logo" /></td>
							}
							<td className="rmse-vat-results-rankings-team">{__('Relegation', 'rmse-vat-results')}</td>
							<td className="rmse-vat-results-rankings-games">5</td>
							<td className="rmse-vat-results-rankings-wins">4</td>
							<td className="rmse-vat-results-rankings-draws">0</td>
							<td className="rmse-vat-results-rankings-losses">1</td>
							<td className="rmse-vat-results-rankings-diff">90 (100:10)</td>
							<td className="rmse-vat-results-rankings-points">8</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	);
}
