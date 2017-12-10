

//bootstrap fixes

jQuery(function()
{
    jQuery(document).on('show.bs.modal', '.modal', function()
    {
        var maxZ = parseInt(jQuery('.modal-backdrop').css('z-index')) || 1040;

        jQuery('.modal:visible').each(function()
        {
            maxZ = Math.max(parseInt(jQuery(this).css('z-index')), maxZ);
        });
 
        jQuery('.modal-backdrop').css('z-index', maxZ);
        jQuery(this).css("z-index", maxZ + 1);
        jQuery('.modal-dialog', this).css("z-index", maxZ + 2);
    });
    
    jQuery(document).on('hidden.bs.modal', '.modal', function () 
    {
        if (jQuery('.modal:visible').length)
        {
            jQuery(document.body).addClass('modal-open');

           var maxZ = 1040;

           jQuery('.modal:visible').each(function()
           {
               maxZ = Math.max(parseInt(jQuery(this).css('z-index')), maxZ);
           });

           jQuery('.modal-backdrop').css('z-index', maxZ-1);
       }
    });
});


/* Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
// Inspired by base2 and Prototype
(function() {
    var initializing = false, fnTest = /xyz/.test(function() {
        xyz;
    }) ? /\b_super\b/ : /.*/;

    // The base Clazzzz implementation (does nothing)
    this.Clazzzz = function() {
    };

    // Create a new Clazzzz that inherits from this class
    Clazzzz.extend = function(prop) {
        var _super = this.prototype;

        // Instantiate a base class (but only create the instance,
        // don't run the init constructor)
        initializing = true;
        var prototype = new this();
        initializing = false;

        // Copy the properties over onto the new prototype
        for (var name in prop) {
            // Check if we're overwriting an existing function
            prototype[name] = typeof prop[name] == "function" &&
                    typeof _super[name] == "function" && fnTest.test(prop[name]) ?
                    (function(name, fn) {
                        return function() {
                            var tmp = this._super;

                            // Add a new ._super() method that is the same method
                            // but on the super-class
                            this._super = _super[name];

                            // The method only need to be bound temporarily, so we
                            // remove it when we're done executing
                            var ret = fn.apply(this, arguments);
                            this._super = tmp;

                            return ret;
                        };
                    })(name, prop[name]) :
                    prop[name];
        }

        // The dummy class constructor
        function Clazzzz() {
            // All construction is actually done in the init method
            if (!initializing && this.init)
                this.init.apply(this, arguments);
        }

        // Populate our constructed prototype object
        Clazzzz.prototype = prototype;

        // Enforce the constructor to be what we expect
        Clazzzz.prototype.constructor = Clazzzz;

        // And make this class extendable
        Clazzzz.extend = arguments.callee;

        return Clazzzz;
    };
})();


jQuery(function() 
{
    jQuery('a').on('focus', function() 
    {
        this.blur();
    });

    jQuery('div.sidebar-shortcuts button.telenok-sidebar-content').click();

    jQuery('ul.telenok-sidebar ul.submenu li a, ul.telenok-sidebar li.parent-single a').click(function() 
    {
        jQuery('ul.telenok-sidebar li').removeClass('active');
        jQuery(this).parents('ul.telenok-sidebar li').addClass('active');
    });
});

var telenokJS = Clazzzz.extend(
{
    init: function()
    {
        this.presentation = {};
        this.module = {};
        this.router = null;
    },
    setBreadcrumbs: function(param) 
    {
        var $parent = jQuery('div.breadcrumbs ul.breadcrumb');
        jQuery('li:gt(0), .divider', $parent).remove();
        jQuery.each(param, function(i, v){
            $parent.append('<li class="active">' + v + '</li>');
        });
    },
    removeBreadcrumbs: function() 
    {
        var $parent = jQuery('div.breadcrumbs ul.breadcrumb');
        jQuery('li:gt(0), .divider', $parent).remove();
    },
    getModule: function(moduleKey) { return this.module[moduleKey]; },
    setModuleParam: function(moduleKey, param) { this.module[moduleKey] = param; },
    getPresentation: function(presentationModuleKey) { return this.presentation[presentationModuleKey]; },
    addPresentation: function(presentationModuleKey, obj) { this.presentation[presentationModuleKey] = obj; },
    hasPresentation: function(presentationModuleKey) { if (this.presentation[presentationModuleKey]) { return true; } else { return false; } },
    getPresentationDomId: function(presentation) { return 'telenok-' + presentation + '-presentation'; },
    addModule: function(moduleKey, url, callback) 
    {
        if (this.module[moduleKey])
        {
            return callback(moduleKey);
        }
        else
        {
            var _this = this;

            return jQuery.when(jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                _this.module[moduleKey] = data;

            })).then(function() {
                return callback(moduleKey);
            });
        }
    },
    preCallingPresentation: function(moduleKey) 
    {
        var param = this.getModule(moduleKey);

        jQuery('.page-content').append(param.presentationContent);
    },
    postCallingPresentation: function(moduleKey) 
    {
        var param = this.getModule(moduleKey);
        var domId = this.getPresentationDomId(param.presentation);

        jQuery('.page-content div.telenok-presentation').hide();
        jQuery('.page-content div#' + domId).show();
    },
    preConfigure: function(moduleKey)
    {
        var param = this.getModule(moduleKey) || {};

        if (!param.preCallingPresentationFlag)
        {
            this.preCallingPresentation(moduleKey);
        }

        param.preCallingPresentationFlag = true;
        
        this.setModuleParam(moduleKey, param);
    },
    postConfigure: function(moduleKey)
    {
        this.postCallingPresentation(moduleKey); 
    },
    processModuleContent: function(moduleKey) 
    { 
        this.preConfigure(moduleKey);

        var param = this.getModule(moduleKey) || {};

        if (this.hasPresentation(param.presentationModuleKey))
        {
            this.getPresentation(param.presentationModuleKey).callMe(param);
        }

        this.postConfigure(moduleKey);
    },
    updateUserUISetting: function(key, value)
    {
        jQuery.ajax({
            url: '/telenok/user/update/ui-setting',
            method: 'post',
            data: {
                key: key,
                value: value
            } 
        });
    },
    maxZ: function(where_search, what_change)
    {
        var maxZ = 0;

        var $where_search = where_search instanceof jQuery ? where_search : jQuery(where_search);
        var $what_change = what_change instanceof jQuery ? what_change : jQuery(what_change);

        $where_search.each(function(i, el)
        {
            if (parseInt(jQuery(this).css('zIndex')) > maxZ) maxZ = parseInt(jQuery(this).css('zIndex'));
        });    

        $what_change.css('zIndex', maxZ + 1);
    },
    getRouter: function()
    {
        if (this.router === null) {
            var root = null;
            var useHash = true; // Defaults to: false
            //var hash = '#'; // Defaults to: '#'
            this.router = new Navigo(root, useHash/*, hash*/);
        }

        return this.router;
    },
    updatePageLinks: function()
    {
        this.getRouter().updatePageLinks()
    },
    getUrlParameterByName: function (name, url)
    {
        var res = parseUrl().parse(url) || {};

        return res[name];
    }
});

var telenok = new telenokJS();
telenok.init();
