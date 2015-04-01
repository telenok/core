@extends('core::layout.email')

@section('header-row')
<table class="header-row" width="378" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
  <tbody>
	<tr>
	  <td class="header-row-td" width="378" style="font-family: Arial, sans-serif; font-weight: normal; line-height: 19px; color: #478fca; margin: 0px; font-size: 18px; padding-bottom: 10px; padding-top: 15px;" 
		  valign="top" align="left">
		{{trans('core::controller/backend-password-reset.reset.title')}}
	  </td>
	</tr>
  </tbody>
</table>
@stop

@section('description')
<div style="font-family: Arial, sans-serif; line-height: 20px; color: #444444; font-size: 13px;">
	{{trans('core::controller/backend-password-reset.reset.description')}}
</div>
@stop

@section('command-line')
<div style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; text-align: center;">
  <a href="{!! route('cmf.password.reset.token', ['token' => $token]) !!}" style="color: #ffffff; text-decoration: none; margin: 0px; text-align: center; vertical-align: baseline; border: 4px solid #6fb3e0; 
	 padding: 4px 9px; font-size: 15px; line-height: 21px; background-color: #6fb3e0;">
	&nbsp; {{trans('core::controller/backend-password-reset.btn.reset')}} &nbsp;</a>
</div>
@stop

@section('footer-raw')
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
  <tbody>
	<tr>
	  <td width="100%" align="center" bgcolor="#f5f5f5" style="font-family: Arial, sans-serif; line-height: 24px; color: #bbbbbb; font-size: 13px; font-weight: normal; text-align: center; padding: 9px; border-width: 1px 0px 0px; border-style: solid; border-color: #e3e3e3; background-color: #f5f5f5;" valign="top">
		<a href="#" style="color: #428bca; text-decoration: none; background-color: transparent;">{{config('app.backend.brand')}}</a>
		<br>

		<?php $socialLink = []; ?>

		@if ($url = config('app.social.twitter.url'))
			<?php $socialLink[] = '<a href="' . $url . '" style="color: #478fca; text-decoration: none; background-color: transparent;">twitter</a>'; ?>
		@endif

		@if ($url = config('app.social.facebook.url'))
			<?php $socialLink[] = '<a href="' . $url . '" style="color: #5b7a91; text-decoration: none; background-color: transparent;">facebook</a>'; ?>
		@endif

		@if ($url = config('app.social.vk.url'))
			<?php $socialLink[] = '<a href="' . $url . '" style="color: #537599; text-decoration: none; background-color: transparent;">vkontakte</a>'; ?>
		@endif

		@if ($url = config('app.social.google-plus.url'))
			<?php $socialLink[] = '<a href="' . $url . '" style="color: #dd5a43; text-decoration: none; background-color: transparent;">google+</a>'; ?>
		@endif

		@if ($url = config('app.social.youtube.url'))
			<?php $socialLink[] = '<a href="' . $url . '" style="color: #cc181e; text-decoration: none; background-color: transparent;">youtube</a>'; ?>
		@endif

		{!! implode(" . ", $socialLink) !!}

	  </td>
	</tr>
  </tbody>
</table>
@stop