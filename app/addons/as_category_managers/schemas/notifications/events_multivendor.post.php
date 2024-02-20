<?php

use Tygh\Enum\SiteArea;
use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Mail\MailMessageSchema;
use Tygh\Enum\UserTypes;
use Tygh\Notifications\Transports\Mail\MailTransport;

defined('BOOTSTRAP') or die('Access denied');

$schema['as_category_managers.added_product'] = [
    'id'        => 'as_category_managers.added_product',
    'group'     => 'as_category_managers',
    'name'      => [
        'template' => 'as_category_managers.event.added_product',
    ],
    'receivers' => [
        UserTypes::ADMIN => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => SiteArea::ADMIN_PANEL,
                'from'            => 'company_site_administrator',
                'to'              => DataValue::create('receiver'),
                'template_code'   => 'as_category_managers_added_product',
                'language_code'   => DataValue::create('lang_code', CART_LANGUAGE),
            ]),
        ],
    ],
];

return $schema;