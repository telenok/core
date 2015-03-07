<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:b3mn="http://b3mn.org/2007/b3mn" xmlns:ext="http://b3mn.org/2007/ext" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:atom="http://b3mn.org/2007/atom+xhtml">
	<head profile="http://purl.org/NET/erdf/profile">
		<link rel="icon" href="/designer/favicon.ico"></link>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"></meta>
		<title>Process Designer</title>

        {!! HTML::style('packages/telenok/core/js/oryx/css/theme_norm.css') !!}

        {!! HTML::style('packages/telenok/core/css/jquery-ui.css') !!}
        {!! HTML::style('packages/telenok/core/css/jquery.gritter.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/bootstrap.min.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/font-awesome.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace-fonts.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/css/ace-skins.min.css') !!}
        {!! HTML::style('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.css') !!}

        {!! HTML::style('packages/telenok/core/js/dropzone/dropzone.css') !!}

        {!! HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/ext-all.css') !!}
        {!! HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/xtheme-gray.css') !!}
        
        {!! HTML::style('packages/telenok/core/css/style.css') !!}

        {!! HTML::script('packages/telenok/core/js/jquery.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery-ui.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.gritter.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.punch.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.js') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.jstree/jstree.js') !!}

        {!! HTML::style('packages/telenok/core/js/jquery.chosen/chosen.css') !!}
        {!! HTML::script('packages/telenok/core/js/jquery.chosen/chosen.js') !!}

        <script type="text/javascript">
			if ("ontouchend" in document)
            {
				document.write("<script src='packages/telenok/core/js/jquery.mobile.custom.min.js' type='text/javascript'>" + "<" + "/script>");
            }
        </script>

        {!! HTML::script('packages/telenok/core/js/fuelux/fuelux.wizard.min.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/bootstrap.min.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace-extra.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace-elements.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/js/ace.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/lib/moment.js') !!}
        {!! HTML::script('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.js') !!}
 
        
        {!! HTML::script('packages/telenok/core/js/dropzone/dropzone.js') !!}
        {!! HTML::script('packages/telenok/core/js/script.js') !!} 

        {!! HTML::script('packages/telenok/core/js/prototype-1.5.1.js') !!} 
        {!! HTML::script('packages/telenok/core/js/oryx/path_parser.js') !!} 

        {!! HTML::script('packages/telenok/core/js/ext-2.0.2/adapter/ext/ext-base.js') !!}
        {!! HTML::script('packages/telenok/core/js/ext-2.0.2/ext-all-debug.js') !!}
        {!! HTML::script('packages/telenok/core/js/ext-2.0.2/plugin/color-field.js') !!}
        {!! HTML::script('packages/telenok/core/js/ext-2.0.2/plugin/grid.search.js') !!}

        <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/"></link>
        <link rel="schema.dcTerms" href="http://purl.org/dc/terms/"></link>
        <link rel="schema.b3mn" href="http://b3mn.org"></link>
        <link rel="schema.oryx" href="http://oryx-editor.org/"></link>
        <link rel="schema.raziel" href="http://raziel.org/"></link>

        {!! HTML::script('packages/telenok/core/js/oryx/kickstart.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/config.js') !!}  
        {!! HTML::script('packages/telenok/core/js/oryx/oryx.js') !!}         
        {!! HTML::script('packages/telenok/core/js/oryx/clazz.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/main.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/utils.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/erdfparser.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/datamanager.js') !!} 
        {!! HTML::script('packages/telenok/core/js/oryx/Core/Math/math.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/editpathhandler.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/minmaxpathhandler.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/pointspathhandler.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/svgmarker.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/svgshape.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/SVG/label.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencil.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/property.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/propertyitem.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/complexpropertyitem.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/rules.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencilset.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencilsets.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/command.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/bounds.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/uiobject.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/abstractshape.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/canvas.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/svgDrag.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/shape.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/Controls/control.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/Controls/magnet.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/Controls/docker.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/node.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/edge.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/abstractPlugin.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Core/abstractLayouter.js') !!}

        {!! HTML::script('packages/telenok/core/js/oryx/i18n/translation_en.js') !!}


        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/shaperepository.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/dragdropresize.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/shapeHighlighting.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/dragDocker.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/propertywindow.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/edit.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/shapemenu.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/undo.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/dockerCreation.js') !!}

        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/canvasResize.js') !!}
        {!! HTML::script('packages/telenok/core/js/oryx/Plugins/selectionframe.js') !!}

        <script type="text/javascript">

			jQuery.noConflict(); 

			var allPlugins = {};
			[
				{
					"core": false,
					"name": "ORYX.Plugins.ShapeRepository",
					"properties": []
				}, 
				{
					"core" : false,
					"name" : "ORYX.Plugins.DragDropResize",
					"properties" : []
				},    
				{
					"core" : false,
					"name" : "ORYX.Plugins.ShapeHighlighting",
					"properties" : []
				},   
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DragDocker",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.PropertyWindow",
					"properties" : []
				},
				{ 
					"core" : true,
					"name" : "ORYX.Plugins.Edit",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DockerCreation",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Undo",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.ShapeMenuPlugin",
					"properties" : []
				},
				{ 
					"core" : true,
					"name" : "ORYX.Plugins.CanvasResize",
					"properties" : []
				},
				{ 
					"core" : true,
					"name" : "ORYX.Plugins.SelectionFrame",
					"properties" : []
				}
			].each(function(p) {
				allPlugins[p.name] = p;
			}.bind(allPlugins));

			// install the current plugins
			ORYX.availablePlugins = [];
			[
				"ORYX.Plugins.ShapeRepository",
				"ORYX.Plugins.ShapeHighlighting",
				"ORYX.Plugins.DragDocker",
				"ORYX.Plugins.DragDropResize",
				"ORYX.Plugins.Edit",
				"ORYX.Plugins.PropertyWindow",
				"ORYX.Plugins.Undo",
				"ORYX.Plugins.DockerCreation",
				"ORYX.Plugins.CanvasResize",
				"ORYX.Plugins.SelectionFrame",
				"ORYX.Plugins.ShapeMenuPlugin"
			].each(function(pluginName)
			{
				p = allPlugins[pluginName];

				if (p)
				{
					ORYX.availablePlugins.push(p);
				}
				else
				{
					ORYX.Log.error("missing plugin " + pluginName);
				}
			}.bind(allPlugins));

			function init()
			{
				ORYX_LOGLEVEL = 0;
				ORYX.PATH = "{!! \Config::get('app.url') !!}/packages/telenok/core/js/oryx/";

                Ext.BLANK_IMAGE_URL = "{!! \Config::get('app.url') !!}/packages/telenok/core/js/ext-2.0.2/resources/images/default/s.gif";

				var editor_parameters = {
					id: "processdata", 
					stencilset: {
						url: "{!! URL::route("cmf.module.workflow-process.diagram.stensilset") !!}"
					}
				};

				window.oryxEditor = new ORYX.Editor(editor_parameters);

				if (typeof importJSONFromTop !== 'undefined' && jQuery.isFunction(importJSONFromTop) && importJSONFromTop())
				{
					setTimeout(function() 
                    {   
                        oryxEditor.importJSON(importJSONFromTop(), true);  
                    }, 1000);
				}
			}

            jQuery(function()
            { 
                jQuery('#processdata').height(jQuery(window).height());
            });
		</script> 

	</head>
	<body class="no-skin">
		<div id="main-container" class="main-container">
			<div class="sidebar responsive" id="sidebar">
				<ul class="nav nav-list telenok-sidebar"></ul>
			</div>
            <div class="main-content" id="processdata" style="overflow: auto; text-align: left; height: 450px;position: relative"></div>
		</div>
	</body>
</html>

