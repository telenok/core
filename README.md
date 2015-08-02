## Telenok CMS Core

[![Total Downloads](https://poser.pugx.org/telenok/core/downloads.svg)](https://packagist.org/packages/telenok/core)
[![Latest Stable Version](https://poser.pugx.org/telenok/core/v/stable.svg)](https://packagist.org/packages/telenok/core)
[![Latest Unstable Version](https://poser.pugx.org/telenok/core/v/unstable.svg)](https://packagist.org/packages/telenok/core)
[![License](https://poser.pugx.org/telenok/core/license.svg)](https://packagist.org/packages/telenok/core)


# Documentation

Please, visit site http://telenok.com/docs


1. Есть - виртуальные поля, которые не сохраняются в бд, например у поля Upload есть виртуальное поле, которое равно $model->{$field->code}->isImage()... и тп
2. Для пользователя в Амазон S3 добавить дополнительного пользователя с правами лист, апдейд, делит и полиси AmazonS3FullAccess