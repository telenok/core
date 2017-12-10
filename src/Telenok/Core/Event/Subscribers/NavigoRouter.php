<?php namespace Telenok\Core\Event\Subscribers;

class NavigoRouter {

    public function onEventFire()
    {
    }

    public function onEventFire1111()
    {
        return '
            jQuery(function()
            {
                // for module+submodule
                var handler = function (params, query)
                    {
                        jQuery("ul.telenok-sidebar li").removeClass("active");
                        jQuery("a[data-menu=\'module-" + params.parent_module + "\']").closest("li").addClass("open active");
                        jQuery("a[data-menu=\'module-" + params.parent_module + "-" + params.child_module + "\']").closest("li").addClass("active");

                        telenok.addModule(
                            params.child_module,
                            decodeURIComponent(params.action_param),
                            function(moduleKey)
                            {
                                var presentationParams = telenok.getModule(moduleKey) || {};
                               
                                telenok.processModuleContent(moduleKey);

                                if (params.url) 
                                {
                                    var getParams = telenok.getUrlParameterByName("params", decodeURI(query)) || {};
                                               
                                    telenok.getPresentation(presentationParams.presentationModuleKey).addTabByURL(jQuery.extend({}, {
                                        url: decodeURIComponent(params.url)
                                    }, getParams));
                                }
                            }
                        );
                    };
            
                // for module+submodule
                telenok
                    .getRouter()
                    .on({
                        "/module/:parent_module/:child_module/action-param/:action_param/" : handler,
                        "/module/:parent_module/:child_module/action-param/:action_param/tab/:tab_type/:url/" : handler
                    }).resolve();


                // for module, if it hasnt submodule
                handler = function (params, query)
                    {
                        jQuery("ul.telenok-sidebar li").removeClass("active");
                        jQuery("a[data-menu=\'module-" + params.child_module + "\']").closest("li").addClass("open active");

                        telenok.addModule(
                            params.child_module,
                            decodeURIComponent(params.action_param),
                            function(moduleKey)
                            {
                                var presentationParams = telenok.getModule(moduleKey) || {};

                                telenok.processModuleContent(moduleKey);

                                if (params.url) 
                                {
                                    var getParams = telenok.getUrlParameterByName("params", query) || {};
                                                
                                    telenok.getPresentation(presentationParams.presentationModuleKey).addTabByURL(jQuery.extend({}, {
                                        url: decodeURIComponent(params.url)
                                    }, getParams));
                                }
                            }
                        );
                    };
                    
                // for module, if it hasnt submodule
                telenok
                    .getRouter()
                    .on({
                        "/module/:child_module/action-param/:action_param/" : handler,
                        "/module/:child_module/action-param/:action_param/tab/:tab_type/:url/" : handler
                    }).resolve();            
            });
        ';
    }
}
