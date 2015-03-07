<div class="form-group">
    <?php 
    
        $domAttr = ['id' => $parameter->code . '-' . $uniqueId, 'class' => 'col-xs-10 col-sm-5'];

    ?>
	
	{!! Form::label("parameter[{$parameter->code}]", $parameter->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) !!}
	
    <div class="col-sm-9">
		
        {!! Form::text("parameter[{$parameter->code}]", '', $domAttr) !!}
		
        @if ($d = $parameter->translate('description'))
        <span title="" data-content="{{ $d }}" data-placement="right" data-trigger="hover" data-rel="popover" 
              class="help-button" data-original-title="{{\Lang::get('core::default.tooltip.description')}}">?</span>
        @endif 
    </div>
</div>