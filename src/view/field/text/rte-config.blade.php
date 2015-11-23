
CKEDITOR.plugins.addExternal('widget_inline', '/packages/telenok/core/js/ckeditor_addons/plugins/widget_inline/')

CKEDITOR.config.allowedContent = true;

//CKEDITOR.config.protectedSource.push(/<\widget_inline[\s\S]*?\>[\s\S]*?<\/widget_inline\>/g);

//CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);

CKEDITOR.editorConfig = function(config)
{
    config.extraPlugins = 'widget_inline';
};