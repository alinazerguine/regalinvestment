/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	if(moduleName=='default'){ //If language set is Hebrew
		if(LangCode=='he'){
			config.language = 'he';
		}
	}
	
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo'  ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
 		{ name: 'insert' },
		'/',
		{ name: 'forms' },
		{ name: 'tliyoutube' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others'  },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' },
		{ name: 'strinsert' },
		{ name: 'wenzgmap' },
 		 
 	];

 	config.extraPlugins = 'strinsert,wenzgmap,tliyoutube';
	
	//config.extraPlugins = 'wenzgmap';     /* extra*/
	//config.extraPlugins = 'tliyoutube';
	
	 
	
	config.allowedContent=true;
	CKEDITOR.dtd.$removeEmpty.span = 0;
	CKEDITOR.dtd.$removeEmpty.i = 0;
	CKEDITOR.config.fillEmptyBlocks = false;
	
	config.strinsert_button_label = 'Content Block';
    config.strinsert_button_title = config.strinsert_button_voice = 'Insert Content Block';
	//config.autoParagraph = false;
	
	config.enterMode = CKEDITOR.ENTER_BR // pressing the ENTER KEY input <br/>
	config.shiftEnterMode = CKEDITOR.ENTER_BR; //pressing the SHIFT + ENTER KEYS input <p>
	config.autoParagraph = false; // stops automatic insertion of <p> on focus
	config.forcePasteAsPlainText = true;
	config.skin = 'moono-dark';
	config.disableNativeSpellChecker = false;
	
	
	if(moduleName=='default'){
		config.filebrowserImageBrowseUrl = baseUrl+'/index/browse';
		config.filebrowserUploadUrl = baseUrl+'/index/upload';
		
	}
	else{
		config.filebrowserImageBrowseUrl = baseUrl+'/controlPanel/index/browse';
		config.filebrowserUploadUrl = baseUrl+'/controlPanel/index/upload';
	}
	
	
 	
	
};


 