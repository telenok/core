@extends('core::layout.model')

	<?php

		$jsContentUnique = str_random();

	?>

@section('script')

	@parent

var composer{{$jsContentUnique}} = function()
{
    this.form = jQuery('#model-ajax-{{$uniqueId}}');

    this.divId = this.form.closest('div.tab-pane').attr('id');

    this.btnSave = jQuery("#btn-save-{{$jsContentUnique}}");
	this.btnSaveClose = jQuery("#btn-save-close-{{$jsContentUnique}}");
	this.btnClose = jQuery("#btn-close-{{$jsContentUnique}}");
	this.btnComposerDry = jQuery("#btn-update-dry-{{$jsContentUnique}}");
	this.btnComposerFinish = jQuery("#btn-update-finish-{{$jsContentUnique}}");

	this.inputContent = jQuery("input#content-{{$jsContentUnique}}");
	this.inputAction = jQuery("input#action-{{$jsContentUnique}}");

    this.contentJson = jQuery('#pre-{{$jsContentUnique}}');
    this.contentUpdateDry = jQuery('#composer-dry-content-{{$jsContentUnique}}');
    this.contentUpdateFinish = jQuery('#composer-finish-content-{{$jsContentUnique}}');

    this.tabTitleFirst = jQuery('#composer-tab-title-first-{{$jsContentUnique}}');
    this.tabTitleSecond = jQuery('#composer-tab-title-second-{{$jsContentUnique}}');
    this.tabContentFirst = jQuery('#composer-tab-content-first-{{$jsContentUnique}}');
    this.tabContentSecond = jQuery('#composer-tab-content-second-{{$jsContentUnique}}');

    this.spinUpdateDry = jQuery('#spind-update-dry-{{$jsContentUnique}}');

    this.intervalUpdateDry;
    this.intervalUpdateDryTicket = 0;

    this.intervalUpdateFinish;
    this.intervalUpdateFinishTicket = 0;

    this.btnAll = jQuery('.form-actions button', this.form);
};

composer{{$jsContentUnique}}.prototype.btnAllDisabled = function(status)
{
    if (status === true)
    {
        this.btnAll.attr('disabled', 'disabled');
    }
    else
    {
        this.btnAll.removeAttr('disabled');
    }
};

composer{{$jsContentUnique}}.prototype.clearIntervalUpdateDry = function()
{
    clearInterval(this.intervalUpdateDry);
    this.intervalUpdateDryTicket = 0;
};

composer{{$jsContentUnique}}.prototype.clearIntervalUpdateFinish = function()
{
    clearInterval(this.intervalUpdateFinish);
    this.intervalUpdateFinishTicket = 0;
};

composer{{$jsContentUnique}}.prototype.clickProcess = function(btnType)
{
    this.btnAllDisabled(true);
    this.hideFaTimes(true);

    this.btnComposerDry.show();
    this.btnComposerFinish.hide();

    if (btnType == 'composer.validate')
    {
        this.inputContent.val(this.contentJson.text());
		this.inputAction.val(btnType);
    }
};

composer{{$jsContentUnique}}.prototype.ajaxDoneProcess = function(btnType)
{
    this.hideSpin(true);

    if (btnType == 'composer.validate')
    {
        this.form.data('btn-clicked', "composer.update.dry");
        this.inputAction.val("composer.update.dry");
        this.contentUpdateDry.show();
        this.contentUpdateDry.find('pre').empty();
        this.hideSpin();
        this.hideFaTimes();

        this.tabTitleSecond.find('a').tab('show');

        this.intervalUpdateDry = setInterval((function()
        {
            if (this.intervalUpdateDryTicket++ > 1000)
            {
                this.clearIntervalUpdateDry();
            }

            jQuery.ajax({
                url: "{!! route('telenok.module.composer-manager.composer-json.output') !!}",
                type: 'get',
                dataType: "html",
                success: (function(data) {
                    this.contentUpdateDry.find('pre').html(data);
                }).bind(this)
            });
        }).bind(this), 2000);

        this.btnAllDisabled(true);

        this.form.submit();
    }
    else if (btnType == 'composer.update.dry')
    {
        this.btnAllDisabled();

        this.form.data('btn-clicked', "composer.update.finish");

        this.inputAction.val("composer.update.finish");

        setTimeout(function() { composerObject{{$jsContentUnique}}.clearIntervalUpdateDry(); }, 2000);
    }
    else if (btnType == 'composer.update.finish')
    {
        this.btnComposerDry.hide();
        this.btnComposerFinish.show();

        this.btnAllDisabled();

        this.intervalUpdateFinish = setInterval((function()
        {
            if (this.intervalUpdateFinish++ > 1000)
            {
                this.clearIntervalUpdateFinish();
            }

            jQuery.ajax({
                url: "{!! route('telenok.module.composer-manager.composer-json.output') !!}",
                type: 'get',
                dataType: "html",
                success: (function(data) {
                    this.contentUpdateFinish.find('pre').html(data);
                }).bind(this)
            });
        }).bind(this), 2000);
    }
};

composer{{$jsContentUnique}}.prototype.hideFaTimes = function(status)
{
    if (status)
    {
        jQuery('#composer-alert-{{$jsContentUnique}}').addClass('hidden');
    }
    else
    {
        jQuery('#composer-alert-{{$jsContentUnique}}').removeClass('hidden');
    }

    this.form.find('.error-container .close').click();
};

composer{{$jsContentUnique}}.prototype.hideSpin = function(status)
{
    if (status)
    {
        this.spinUpdateDry.addClass('hidden');
    }
    else
    {
        this.spinUpdateDry.removeClass('hidden');
    }
};


    var composerObject{{$jsContentUnique}} = new composer{{$jsContentUnique}}();

    @section('buttonType')
        composerObject{{$jsContentUnique}}.clickProcess(button_type);
    @stop

    @section('ajaxDone')
        @parent

        composerObject{{$jsContentUnique}}.ajaxDoneProcess(button_type);

    @stop

    @section('ajaxFail')
        @parent

        composerObject{{$jsContentUnique}}.clearIntervalUpdateDry();
        composerObject{{$jsContentUnique}}.clearIntervalUpdateFinish();

    @stop

@stop



<div id="composer-alert-{{$jsContentUnique}}" class="alert alert-block alert-danger hidden">
    <button data-dismiss="alert" class="close" type="button">
        <i class="fa fa-times"></i>
    </button>
    <p>
        <strong>
            <i class="fa fa-check"></i>
            {{ $controller->LL('notice.validate.fail') }}
        </strong>
    </p>
</div>


@section('form')

	@parent

	@section('formField') 

	<input type="hidden" name="content" value="" id="content-{{$jsContentUnique}}" />
	<input type="hidden" name="action" value="" id="action-{{$jsContentUnique}}" />

	<div class="form-group">
		<div class="col-sm-12">

            <ul class="nav nav-tabs" >
                <li id="composer-tab-title-first-{{$jsContentUnique}}" class="active in">
                    <a data-toggle="tab" href="#composer-tab-content-first-{{$jsContentUnique}}">
                        {{$controller->LL('tab.title.composer')}}
                    </a>
                </li>
                <li id="composer-tab-title-second-{{$jsContentUnique}}" class="">
                    <a data-toggle="tab" href="#composer-tab-content-second-{{$jsContentUnique}}">
                        {{$controller->LL('tab.title.update')}}
                    </a>
                </li>
            </ul>
            <div class="tab-content" style="overflow: visible;">
                <div id="composer-tab-content-first-{{$jsContentUnique}}" class="tab-pane in active">
                    <div id="composer-content-{{$jsContentUnique}}">
                        <pre id="pre-{{$jsContentUnique}}" style="min-height: 400px;" contenteditable="true">{{$content}}</pre>
                    </div>
                </div>
                <div id="composer-tab-content-second-{{$jsContentUnique}}" class="tab-pane">
                    <div id="composer-dry-content-{{$jsContentUnique}}" style="display: none;">
                        <h3>
                            {{$controller->LL('title.update.dry')}}
                            <i id="spind-update-dry-{{$jsContentUnique}}" class="ace-icon fa fa-spinner fa-spin orange bigger-125 hidden"></i>
                        </h3>
                        <pre></pre>
                    </div>
                    <div id="composer-finish-content-{{$jsContentUnique}}" style="display: none;">
                        <h3>{{$controller->LL('title.update.finish')}}</h3>
                        <pre></pre>
                    </div>
                </div>
            </div>

			<script>
				jQuery('#pre-{{$jsContentUnique}} code').each(function(i, block)
				{
                    //hljs.highlightBlock(block);
				});
			</script>
		</div>
	</div>
	@stop

	@section('formBtn')
    <div class='form-actions center no-margin'>
        <button id="btn-save-{{$jsContentUnique}}" type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');" autofocus="autofocus">
            {{$controller->LL('btn.save')}}
        </button>
        <button id="btn-save-close-{{$jsContentUnique}}" type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            {{$controller->LL('btn.save.close')}}
        </button>
        <button id='btn-update-dry-{{$jsContentUnique}}' type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'composer.validate');">
            {{$controller->LL('btn.composer.validate')}}
        </button>
		<button id='btn-update-finish-{{$jsContentUnique}}' type="submit" class="btn btn-danger hidden" onclick="jQuery(this).closest('form').data('btn-clicked', 'composer.update.finish');">
			{{$controller->LL('btn.composer.update.finish')}}
		</button>
        <button id='btn-close-{{$jsContentUnique}}' type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            {{$controller->LL('btn.close')}}
        </button>
    </div>
	@stop

@stop
 
