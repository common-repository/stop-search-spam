// import custom CSS styles
import './index.scss';

// import dependencies
import domReady from '@wordpress/dom-ready';
import apiFetch from '@wordpress/api-fetch';
import { createRoot, useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelect } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

// import components
import { Panel, PanelBody, PanelRow, ToggleControl, Button, NoticeList } from '@wordpress/components';

// import experimental components
import { __experimentalNumberControl as NumberControl, __experimentalHeading as Heading } from '@wordpress/components';

/**
 * Get and Set settings data
 */
const useSettings = () => {

	const [ limitSearchLength, setLimitSearchLength ] = useState( false );
	const [ searchLengthSize, setSearchLengthSize ] = useState( 100 );
	const [ disallowEmojis, setDisallowEmojis ] = useState( false );
	const [ disallowChineseCharacters, setDisallowChineseCharacters ] = useState( false );
	const [ disallowCyrillicCharacters, setDisallowCyrillicCharacters ] = useState( false );
	const [ disallowJapaneseCharacters, setDisallowJapaneseCharacters ] = useState( false );

	const { createSuccessNotice, removeAllNotices } = useDispatch( noticesStore );

	// get options
	useEffect( () => {
		apiFetch( { path: '/wp/v2/settings' } ).then( ( settings ) => {

			const { limitSearchLength, searchLengthSize, disallowEmojis, disallowChineseCharacters, disallowCyrillicCharacters, disallowJapaneseCharacters } = settings.rls_stop_search_spam;

			setLimitSearchLength( limitSearchLength );
			setSearchLengthSize( searchLengthSize );
			setDisallowEmojis( disallowEmojis );
			setDisallowChineseCharacters( disallowChineseCharacters );
			setDisallowCyrillicCharacters( disallowCyrillicCharacters );
			setDisallowJapaneseCharacters( disallowJapaneseCharacters );

		} );
	}, [] );

	// set options
	const saveSettings = () => {

		apiFetch( {
			path: '/wp/v2/settings',
			method: 'POST',
			data: {
				rls_stop_search_spam: {
					limitSearchLength,
					searchLengthSize,
					disallowEmojis,
					disallowChineseCharacters,
					disallowCyrillicCharacters,
					disallowJapaneseCharacters,
				},
			},
		} ).then( () => {

			removeAllNotices();
			createSuccessNotice(
				__( 'Settings saved', 'stop-search-spam' )
			);

	} );;
	};
	
	return {
		limitSearchLength,
		setLimitSearchLength,
		searchLengthSize,
		setSearchLengthSize,
		disallowEmojis,
		setDisallowEmojis,
		disallowChineseCharacters,
		setDisallowChineseCharacters,
		disallowCyrillicCharacters,
		setDisallowCyrillicCharacters,
		disallowJapaneseCharacters,
		setDisallowJapaneseCharacters,
		saveSettings,
	};
};

/**
 * Display notices after settings update
 */
const Notices = () => {
	const { removeNotice } = useDispatch( noticesStore );

	const notices = useSelect( ( select ) =>
		select( noticesStore ).getNotices()
	);

	if ( notices.length === 0 ) {
		return null;
	}

	return <NoticeList notices={ notices } onRemove={ removeNotice } />;
};

/**
 * Settings Page Markup
 */
const SettingsPage = () => {

	const {
		limitSearchLength,
		setLimitSearchLength,
		searchLengthSize,
		setSearchLengthSize,
		disallowEmojis,
		setDisallowEmojis,
		disallowChineseCharacters,
		setDisallowChineseCharacters,
		disallowCyrillicCharacters,
		setDisallowCyrillicCharacters,
		disallowJapaneseCharacters,
		setDisallowJapaneseCharacters,
		saveSettings,
	} = useSettings();

	return <>

		<Heading level={ 1 }>
			{ __( 'Stop Search Spam', 'stop-search-spam' ) }
		</Heading>

		<Notices />

		<Panel>
			<PanelBody>
				<PanelRow>
						<ToggleControl
							label={ __( 'Limit length of search query', 'stop-search-spam' ) } 
							checked={ limitSearchLength }
							onChange={ setLimitSearchLength } 
							__nextHasNoMarginBottom
						/>
				</PanelRow>
			</PanelBody>
			<PanelBody initialOpen={ limitSearchLength }>
				<PanelRow>
					<NumberControl
						label={ __( 'Search query max characters length', 'stop-search-spam' ) }
						isShiftStepEnabled={ true } 
						labelPosition="side"
						shiftStep={ 10 } 
						value={ searchLengthSize }
						onChange={ setSearchLengthSize } 
						min="20"
						max="999"
						__nextHasNoMarginBottom 
					/>
				</PanelRow>
			</PanelBody>
			<PanelBody>
				<PanelRow>
					<ToggleControl
						label={ __( 'Disallow emojis', 'stop-search-spam' ) } 
						checked={ disallowEmojis }
						onChange={ setDisallowEmojis } 
						__nextHasNoMarginBottom
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						label={ __( 'Disallow Chinese characters', 'stop-search-spam' ) } 
						checked={ disallowChineseCharacters }
						onChange={ setDisallowChineseCharacters } 
						__nextHasNoMarginBottom
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						label={ __( 'Disallow Cyrillic characters', 'stop-search-spam' ) } 
						checked={ disallowCyrillicCharacters }
						onChange={ setDisallowCyrillicCharacters } 
						__nextHasNoMarginBottom
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						label={ __( 'Disallow Japanese characters', 'stop-search-spam' ) } 
						checked={ disallowJapaneseCharacters }
						onChange={ setDisallowJapaneseCharacters } 
						__nextHasNoMarginBottom
					/>
				</PanelRow>
				<PanelRow>
					<Button variant="primary" onClick={ saveSettings } __next40pxDefaultSize>
						{ __( 'Save Settings', 'stop-search-spam' ) }
					</Button>
				</PanelRow>
			</PanelBody>
		</Panel>
	</>;
};

/**
 * Render the settings page
 */
domReady( () => {
	const root = createRoot(
		document.getElementById( 'rsl-stop-search-spam-settings' )
	);

	root.render( <SettingsPage /> );
} );