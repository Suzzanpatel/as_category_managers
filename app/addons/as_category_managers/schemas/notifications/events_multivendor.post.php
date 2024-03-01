<?php

use Tygh\Addons\VendorDataPremoderation\Notifications\DataProviders\PremoderationDataProvider;
use Tygh\Enum\Addons\VendorDataPremoderation\PremoderationStatuses;
use Tygh\Enum\SiteArea;
use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Mail\MailMessageSchema;
use Tygh\Enum\UserTypes;
use Tygh\Notifications\Transports\Mail\MailTransport;

defined('BOOTSTRAP') or die('Access denied');

/** @var array<string, array<string,string>> $schema */
$schema['as_category_managers.product_status.approved'] = [
    'group'     => 'as_category_managers',
    'name'      => [
        'template' => 'vendor_data_premoderation.event.product_status.approved.name',
        'params'   => [],
    ],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId()     => MailMessageSchema::create([
                'area'            => SiteArea::ADMIN_PANEL,
                'from'            => 'company_site_administrator',
                'to'              => DataValue::create('to', 'company_support_department'),
                'template_code'   => 'as_category_managers_notification',
                'company_id'      => 0,
                'to_company_id'   => DataValue::create('to_company_id'),
                'language_code'   => DataValue::create('lang_code', CART_LANGUAGE),
                'data_modifier'   => static function (array $data) {
                    $company_placement_info = fn_get_company_placement_info($data['company_id']);

                    return array_merge($data, [
                        'status' => PremoderationStatuses::APPROVED,
                        'to' => isset($company_placement_info['company_support_department'])
                            ? $company_placement_info['company_support_department']
                            : 'company_support_department',
                    ]);
                }
            ]),
        ],
    ],
];

$schema['as_category_managers.product_status.disapproved'] = [
    'group'     => 'as_category_managers',
    'name'      => [
        'template' => 'vendor_data_premoderation.event.product_status.disapproved.name',
    ],
    'data_provider' => [PremoderationDataProvider::class, 'factory'],
    'receivers' => [
        UserTypes::VENDOR => [
            MailTransport::getId()     => MailMessageSchema::create([
                'area'            => SiteArea::ADMIN_PANEL,
                'from'            => 'company_site_administrator',
                'to'              => DataValue::create('to', 'company_support_department'),
                'template_code'   => 'as_category_managers_notification',
                'company_id'      => 0,
                'to_company_id'   => DataValue::create('to_company_id'),
                'language_code'   => DataValue::create('lang_code', CART_LANGUAGE),
                'data_modifier'   => static function (array $data) {
                    $company_placement_info = fn_get_company_placement_info($data['company_id']);

                    return array_merge($data, [
                        'status' => PremoderationStatuses::DISAPPROVED,
                        'to' => isset($company_placement_info['company_support_department'])
                            ? $company_placement_info['company_support_department']
                            : 'company_support_department',
                    ]);
                }
            ]),
        ],
    ],
];

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