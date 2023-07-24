/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

import { TextControl, CheckboxControl, __experimentalNumberControl as NumberControl, Flex, FlexBlock } from '@wordpress/components';

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
			{
				<InspectorControls key="settings">
					<h5>{__('Next Games / Last Results of the club', 'rmse-vat-results')}</h5>
					<div className="instructions">
						{__('Choose how many elements (last results and next games) should be displayed. 0 or less means it will not be shown at all. Will add a preview in a future version.', 'rmse-vat-results')}
					</div>
					<Flex>
						<FlexBlock>
							<NumberControl
								isShiftStepEnabled={true}
								label={__('Last Results', 'rmse-vat-results')}
								shiftStep={5}
								value={attributes.results}
								onChange={(val) => setAttributes({ results: val })}
							/>
						</FlexBlock>
						<FlexBlock>
							<NumberControl
								isShiftStepEnabled={true}
								label={__('Scheduled Games', 'rmse-vat-results')}
								shiftStep={5}
								value={attributes.scheduled}
								onChange={(val) => setAttributes({ scheduled: val })}
							/>
						</FlexBlock>
					</Flex>
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
						label={__('Show game type?', 'rmse-vat-results')}
						checked={attributes.type}
						onChange={(val) => setAttributes({ type: val })}
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
							{attributes.type &&
								<th className='rmse-vat-results-type'>
									{__('Type', 'rmse-vat-results')}
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
								Club Internal
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
								Halle X
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
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
							Halle X
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
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
							Halle X
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
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
							Halle X
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
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
							Halle X
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
						{attributes.type &&
							<td className='rmse-vat-results-type'>
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
							Halle X
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
							</td>
						}
					</tr>

					<tr className="rmse-vat-results-game-planned rmse-vat-results-game-type-cup">
					<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.type &&
							<td className='rmse-vat-results-type'>
							Cupspiel
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
							Halle X
							</td>
						}
						{attributes.with_result &&
							<td className='rmse-vat-results-result'>
							</td>
						}
					</tr>
					<tr className="rmse-vat-results-game-planned rmse-vat-results-game-type-ms">
					<td className='rmse-vat-results-date'>
							21.10.23 14:25
						</td>
						{attributes.type &&
							<td className='rmse-vat-results-type'>
							Meisterschaftsspiel
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
							Halle X
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
