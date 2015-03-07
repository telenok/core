<?php

return array(
    'name' => 'Type',
    
    'header.title' => 'Type of object',
    'header.description' => 'creating and editing',

    'list.name' => 'Types of objects',
    'list.names' => 'Types of objects',
    'list.create' => 'Type creating',
    'list.edit' => 'Type editing',
    'list.btn.edit' => 'Edit type',
    'list.btn.delete' => 'Delete type',

    'field.multilanguage' => 'Multilanguage title',
    
    'error.class_model.name' => 'Wrong model class name. Class name should start from A-Z and only [a-z1-9_\] allowed',
    'error.class_model.store' => 'Dont know how to store php file for model\'s class. Not standart class name. Create file first or class should begin with \\App\\Model',
    'error.class_controller.name' => 'Wrong model class controller name. Class name should start from A-Z and only [a-z1-9_\] allowed',
    'error.class_controller.store' => 'Dont know how to store php file for controller\'s class. Not standart class name. Create file first or class should begin with \\App\\Http\\Controllers',

    'error.namespace.search' => 'There are no such namespace, wich contain part of ":path"',
    'error.table.create' => 'Impossible create table ":table"',
    'error.file.create' => 'Impossible create file by path ":path"',
    'error.class_controller.define' => 'Class not defined or directory not choosed where place file of form class',
    'error.code_empty' => 'Please, first set value in field "Code"',
    'error.class_controller_exists' => 'Class of form already exists. Choose other class name',
    'error.class_model_exists' => 'Class of model already exists. Choose other class name',

    'error' => array(
        'code.regex' => 'Field "Code" should start from latin symbol and contains latin symbol, digits or underline'
    ),
);
