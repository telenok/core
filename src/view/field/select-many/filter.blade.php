<?php

$jsUnique = str_random();

?>

<select class="chosen" multiple data-placeholder="{{$controller->LL('notice.choose')}}" id="input{{$jsUnique}}" name="filter[{{$field->code}}][]">
    <?php
    
    try
    {
        $data = json_decode($field->select_many_data, true);
        
        if (!empty($data))
        {
            $title = array_get($data, 'title.' . config('app.locale'));
            
            if (empty($title))
            {
                $title = array_get($data, 'title.' . config('app.localeDefault'));
            }
            
            $keys = array_get($data, 'key');
            
            foreach ($title as $key => $value)
            {
                echo '<option value="' . $keys[$key] . '">' . e($value) . '</option>';
            }
        }
    } 
    catch (\Exception $e) {}

    
    ?>
</select>
<script type="text/javascript">
    jQuery("#input{{$jsUnique}}").on("chosen:showing_dropdown", function()
    {
        telenok.maxZ("*", jQuery(this).parent().find("div.chosen-drop"));
    })
    .chosen({
        keepTypingMsg: "{{$controller->LL('notice.typing')}}",
        lookingForMsg: "{{$controller->LL('notice.looking-for')}}",
        minTermLength: 1,
        width: '200pxa'
    });
</script>