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
import { useEffect } from "@wordpress/element";

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
	// this is actually a little bit dirty with global variables, but...

	const teams = rmse_vat_team_selection.map((x) => ({ label: x.name, value: x.id }));

	return (
		<div {...useBlockProps()}>
			{
				<InspectorControls key="settings">
					<h5>{__('Next Games / Last Results of a team', 'rmse-vat-results')}</h5>
					<div className="instructions">
						{__('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'rmse-vat-results')}
					</div>
					<FlexBlock>
						<NumberControl
							isShiftStepEnabled={true}
							label={__('Last Results', 'rmse-vat-results')}
							shiftStep={5}
							value={attributes.results}
							onChange={(val) => setAttributes({ results: parseInt(val) })}
						/>
					</FlexBlock>
					<FlexBlock>
						<NumberControl
							isShiftStepEnabled={true}
							label={__('Scheduled Games', 'rmse-vat-results')}
							shiftStep={5}
							value={attributes.scheduled}
							onChange={(val) => setAttributes({ scheduled: parseInt(val) })}
						/>
					</FlexBlock>
					<SelectControl
						label={__('Team', 'rmse-vat-results')}
						value={attributes.team}
						options={teams}
						onChange={(val) => setAttributes({ team: val })}
					/>
					<TextControl
						label={__('Date Format', 'rmse-vat-results')}
						value={attributes.dateformat}
						onChange={(val) => setAttributes({ dateformat: val })}
					/>
					<CheckboxControl
						label={__('Show Header?', 'rmse-vat-results')}
						checked={attributes.header}
						onChange={(val) => setAttributes({ header: val })}
					/>
					<CheckboxControl
						label={__('Show Group?', 'rmse-vat-results')}
						checked={attributes.group}
						onChange={(val) => setAttributes({ group: val })}
					/>
					<CheckboxControl
						label={__('Show Venue?', 'rmse-vat-results')}
						checked={attributes.venue}
						onChange={(val) => setAttributes({ venue: val })}
					/>
					<CheckboxControl
						label={__('Show Results?', 'rmse-vat-results')}
						checked={attributes.with_result}
						onChange={(val) => setAttributes({ with_result: val })}
					/>
				</InspectorControls>
			}

			<table className="rmse-vat-results-table">
				{attributes.header &&
					<thead>
						<tr>
							<th className='rmse-vat-results-date'>
								{__('Date / Time', 'rmse-vat-results')}
							</th>
							{attributes.group &&
								<th className='rmse-vat-results-group'>
									{__('Group', 'rmse-vat-results')}
								</th>
							}
							<th className='rmse-vat-results-hometeam'>
								{__('Home', 'rmse-vat-results')}
							</th>
							<th className='rmse-vat-results-guestteam'>
								{__('Guest', 'rmse-vat-results')}
							</th>
							{attributes.venue &&
								<th className='rmse-vat-results-venue'>
									{__('Venue', 'rmse-vat-results')}
								</th>
							}
							{attributes.with_result &&
								<th className='rmse-vat-results-result'>
									{__('Result', 'rmse-vat-results')}
								</th>
							}
						</tr>
					</thead>
				}
				<tbody>
					<tr className="rmse-vat-results-game-played rmse-vat-results-game-club-internal">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Clubintern
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team A
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team B
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
								33:30 (16:17)
							</td>
						}
					</tr>
					<tr className="rmse-vat-results-game-played  rmse-vat-results-game-win">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Sieg
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team A
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team B
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
								33:30 (16:17)
							</td>
						}
					</tr>
					<tr className="rmse-vat-results-game-played  rmse-vat-results-game-draw">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Unentschieden
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team A
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team B
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
								33:33 (16:17)
							</td>
						}
					</tr>
					<tr className="rmse-vat-results-game-played  rmse-vat-results-game-loss">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Niederlage
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team B
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team A
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
								30:33 (16:17)
							</td>
						}
					</tr>

					<tr className="rmse-vat-results-game-planned rmse-vat-results-game-home">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Heimspiel
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team A
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team B
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
							</td>
						}
					</tr>
					<tr className="rmse-vat-results-game-planned rmse-vat-results-game-away">
						<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.group &&
							<td className='rmse-vat-results-group'>
								Auswärtsspiel
							</td>
						}
						<td className='rmse-vat-results-hometeam'>
							Team A
						</td>
						<td className='rmse-vat-results-guestteam'>
							Team B
						</td>
						{attributes.venue &&
							<td className='rmse-vat-results-venue'>
								<a href="#">Halle X</a>
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
							</td>
						}
					</tr>
				</tbody>
			</table>
		</div >
	);
}
