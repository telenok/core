
{!! app('telenok.repository')->getObjectFieldController($field->key)->getFormModelContent(
	$controller, 
	$controller->getEventResource()->get('model'), 
	$field, 
	$controller->getUniqueId()) 
!!}