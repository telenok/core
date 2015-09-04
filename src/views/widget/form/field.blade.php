
{!! app('telenok.config.repository')->getObjectFieldController()->get($field->key)->getFormModelContent(
	$controller, 
	$controller->getEventResource()->get('model'), 
	$field, 
	$controller->getUniqueId()) 
!!}