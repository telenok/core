CKEDITOR.plugins.add( 'widget_inline', {

// Register the icons.
icons: 'widget_inline',

// The plugin initialization logic goes inside this method.
init: function( editor ) {

editor.addContentsCss( this.path + 'css/style.css' );

// Define an editor command that opens our dialog window.
editor.addCommand( 'widget_inline', function(){ } );

editor.addMenuGroup('widgetInlineGroup', 3);
//editor.addMenuGroup('editWidgetInlineGroup', 4);

if (editor.contextMenu) 
{
editor.addMenuItems({
addWidgetInlineItem:
{
label : 'Add Inline Widget',
group : 'widgetInlineGroup',
icon: this.path + 'icons/widget_inline.png',
order : 21,
getItems : function()
{
return {
addWidgetInlineItem2 : CKEDITOR.TRISTATE_ON,
addWidgetInlineItem3 : CKEDITOR.TRISTATE_ON,
addWidgetInlineItem4 : CKEDITOR.TRISTATE_ON
};
}
},
addWidgetInlineItem2:
{
label : 'Add Inline Widget',
group : 'widgetInlineGroup',
icon: this.path + 'icons/widget_inline.png',
order : 21,
getItems : function()
{
return {
addWidgetHtmlItem : CKEDITOR.TRISTATE_ON
};
}
},
addWidgetInlineItem3:
{
label : 'Add Inline Widget',
group : 'widgetInlineGroup',
icon: this.path + 'icons/widget_inline.png',
order : 21,
getItems : function()
{
return {
addWidgetRteItem : CKEDITOR.TRISTATE_ON
};
}
},
addWidgetInlineItem4:
{
label : 'Add Inline Widget',
group : 'widgetInlineGroup',
icon: this.path + 'icons/widget_inline.png',
order : 21,
getItems : function()
{
return {
addWidgetNewsItem : CKEDITOR.TRISTATE_ON
};
}
},
/*
editWidgetInlineItem:
{
label : 'Edit Inline Widget',
group : 'widgetInlineGroup',
order : 31,
getItems : function()
{
return {
addWidgetHtmlItem : CKEDITOR.TRISTATE_ON,
addWidgetRteItem : CKEDITOR.TRISTATE_ON,
addWidgetNewsItem : CKEDITOR.TRISTATE_ON
};
}
},*/
addWidgetHtmlItem :
{
label : 'Insert a tick',
group : 'widgetInlineGroup',
icon: this.path + 'icons/widget_inline.png',
command : 'widget_inline',
order : 22
},
addWidgetRteItem :
{
label : 'Insert Question Mark',
icon: this.path + 'icons/widget_inline.png',
group : 'widgetInlineGroup',
command : 'widget_inline',
order : 23
},
addWidgetNewsItem :
{
label : 'insert Tick and Question',
icon: this.path + 'icons/widget_inline.png',
group : 'widgetInlineGroup',
command : 'widget_inline',
order : 24
}
});

editor.contextMenu.addListener(function (element, selection)
{
return {
addWidgetInlineItem: CKEDITOR.TRISTATE_ON
};
});

/*  
editor.addMenuGroup( 'widgetInlineGroup' );

editor.addMenuItem( 'widgetInlineAddItem', 
{
label: 'Add Inline Widget',
group : 'wiget_inline',
icon: this.path + 'icons/widget_inline.png',
group: 'widgetInlineGroup'
getItems : function() {
return {
tick_insertTick : CKEDITOR.TRISTATE_OFF,
tick_insertQuestionMark : CKEDITOR.TRISTATE_OFF,
tick_insertTickandQuestion : CKEDITOR.TRISTATE_OFF
};
}

command: 'widget_inline',
});

editor.addMenuItem( 'widgetInlineEditItem', 
{
label: 'Edit Inline Widget',
icon: this.path + 'icons/widget_inline.png',
command: 'widget_inline',
group: 'widgetInlineGroup'
});*/
}
/*
editor.contextMenu.addListener( function( element ) 
{
if ( element.getAscendant( 'widget_inline', true ) ) 
{
return {widgetInlineEditItem: CKEDITOR.TRISTATE_ON};
}

if ( !element.getAscendant( 'widget_inline', true )) 
{
return {widgetInlineAddItem: CKEDITOR.TRISTATE_ON};
}
});
*/
// Register our dialog file -- this.path is the plugin folder path.
//CKEDITOR.dialog.add( 'abbrDialog', this.path + 'dialogs/abbr.js' );
}
});