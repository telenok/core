
CKEDITOR.plugins.addExternal('widget_inline', '/packages/telenok/core/js/ckeditor_addons/plugins/widget_inline/')

CKEDITOR.config.allowedContent = false;

//CKEDITOR.config.protectedSource.push(/<\widget_inline[\s\S]*?\>[\s\S]*?<\/widget_inline\>/g);

//CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);

CKEDITOR.editorConfig = function(config)
{
    //config.extraPlugins = 'widget_inline';
};

CKEDITOR.on('dialogDefinition', function (event) {
    var editor = event.editor;
    var dialogDefinition = event.data.definition;

    var dialogName = event.data.name;

    if (dialogName == 'link')
    {
        dialogDefinition.getContents('info').get('protocol')['default'] = '';
    }
    
    if (dialogName == 'link' || dialogName == 'image')
    {
        var tabCount = dialogDefinition.contents.length;

        for (var i = 0; i < tabCount; i++) 
        {
            var browseButton = dialogDefinition.contents[i].get('browse');

            if (browseButton !== null) 
            {
                browseButton.hidden = false;
            
                browseButton.onClick = function (dialog, i)
                {
                    editor._.filebrowserSe = this;

                    if (!jQuery('#modal-ckeditor').size())
                    {
                        jQuery('body').append('<div id="modal-ckeditor" class="modal" role="dialog" aria-labelledby="label"></div>');
                    }

                    var $modal = jQuery('#modal-ckeditor');

                    $modal.data('setFileSrc', function(data)
                    {
                        CKEDITOR.tools.callFunction(editor._.filebrowserFn, data.src);
                    });

                    $modal.on('hidden.bs.modal', function()
                    {
                        jQuery(this).empty();
                    });

                    jQuery.ajax({
                        url: (dialogName == 'link' ? "{{route('telenok.ckeditor.file')}}" : "{{route("telenok.ckeditor.image")}}"),
                        method: 'get',
                        dataType: 'html'
                    })
                    .done(function(data) 
                    {
                        $modal.css('z-index', maxZ).html(data).modal('show');

                        var maxZ = 0;

                        jQuery('*').each(function()
                        {
                            if (parseInt(jQuery(this).css('zIndex')) > maxZ) maxZ = parseInt(jQuery(this).css('zIndex'));
                        });

                        $modal.css('zIndex', maxZ + 1);
                    });
                }
            }
        }
    }
});