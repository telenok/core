<?php

class SeedConfigTable extends \App\Vendor\Telenok\Core\Support\Migrations\Migration {

    public function up()
    {
        parent::up();

        (new \App\Vendor\Telenok\Core\Model\Object\Type())->storeOrUpdate([
            'title'            => ['ru' => 'Конфигурация', 'en' => 'Configuration'],
            'title_list'       => ['ru' => 'Конфигурация', 'en' => 'Configuration'],
            'code'             => 'config',
            'active'           => 1,
            'multilanguage'    => 1,
            'model_class'      => '\App\Vendor\Telenok\Core\Model\System\Config',
            'controller_class' => '\App\Vendor\Telenok\Core\Module\System\Config\Controller',
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => SeedConfigTableTranslation::get('field.code'),
            'title_list' => SeedConfigTableTranslation::get('field.code'),
            'key' => 'string',
            'code' => 'code',
            'active' => 1,
            'field_object_type' => 'config',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'required' => 1,
            'field_order' => 7,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => SeedConfigTableTranslation::get('field.controller_class'),
            'title_list' => SeedConfigTableTranslation::get('field.controller_class'),
            'key' => 'string',
            'code' => 'controller_class',
            'active' => 1,
            'field_object_type' => 'config',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 1,
            'allow_create' => 1,
            'allow_update' => 1,
            'required' => 0,
            'field_order' => 7,
        ]);

        (new \App\Vendor\Telenok\Core\Model\Object\Field())->storeOrUpdate([
            'title' => SeedConfigTableTranslation::get('field.value'),
            'title_list' => SeedConfigTableTranslation::get('field.value'),
            'key' => 'complex-data',
            'code' => 'value',
            'active' => 1,
            'field_object_type' => 'config',
            'field_object_tab' => 'main',
            'multilanguage' => 0,
            'show_in_form' => 1,
            'show_in_list' => 1,
            'allow_search' => 0,
            'allow_create' => 1,
            'allow_update' => 1,
            'required' => 0,
            'field_order' => 8,
        ]);
	}
}

class SeedConfigTableTranslation extends \Telenok\Core\Abstraction\Translation\Controller {

	public static $keys = [
        'field' => [
            'code' => [
                'ru' => "Код",
                'en' => "Code",
            ],
            'value' => [
                'ru' => "Значение",
                'en' => "Value",
            ],
            'controller_class' => [
                'ru' => "Класс контроллера",
                'en' => "Class controller",
            ],
        ],
    ];
}