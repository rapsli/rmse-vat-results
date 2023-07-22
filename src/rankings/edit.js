/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl, SelectControl } from '@wordpress/components';
import { useEffect } from "@wordpress/element";

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
export default function Edit({attributes, setAttributes}) {
	// this is actually a little bit dirty with global variables, but...

	var selTeams=[];
	const teams = tc_shv_team_selection.map((x) => ({ label: x.name, value: x.id }));


	useEffect(() => {
		const teams = tc_shv_team_selection.map((x) => ({ label: x.name, value: x.id }));

		console.log(teams);

		selTeams = teams;

		setAttributes('teams', teams);
	}, [tc_shv_team_selection])

	return (
		<div {...useBlockProps()}>
			<h5>{__('Ranking of a team\'s group', 'tc-shv-results')}</h5>
			<div className="instructions">
				{__('Choose the team and whether headers should be displayed as well as whether the HTML from the tanking should be shown', 'tc-shv-results')}
			</div>
			<SelectControl
				label={__('Team', 'tc-shv-results')}
				value={ attributes.team }
				options={ teams }
				onChange={(val) => setAttributes({ team: val })}
			/>
			<CheckboxControl
				label={__('Show Header?', 'tc-shv-results')}
				checked={attributes.header}
				onChange={(val) => setAttributes({ header: val })}
			/>
			<CheckboxControl
				label={__('Show Logo?', 'tc-shv-results')}
				checked={attributes.logo}
				onChange={(val) => setAttributes({ logo: val })}
			/>
		</div>
	);
}
