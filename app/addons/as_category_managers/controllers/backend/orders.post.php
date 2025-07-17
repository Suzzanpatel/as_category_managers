<?php

use Tygh\Tygh;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'details') {
    $freight_terms = [
        'to_pay_dd' => 'To Pay (DD)',
        'to_pay_gd' => 'To Pay (GD)',
        'paid_charge_in_bill_dd' => 'Paid and Charge in bill (DD)',
        'paid_charge_in_bill_gd' => 'Paid and Charge in bill (GD)',
        'free_delivery_dd' => 'Free Delivery (DD)',
        'free_delivery_gd' => 'Free Delivery (GD)',
        'self_pickup' => 'Self Pick-up'
    ];

    $insurance_terms = [
        'ameya_to_bear' => 'Ameya To Bear',
        'charge_to_customer_0_5' => 'Charge To Customer 0.5%',
        'charge_to_customer_1_0' => 'Charge To Customer 1.0%',
        'customer_to_bear_self_pickup' => 'Customer To Bear(self pick up)',
        'customer_to_bear_policy' => 'Customer To Bear(policy/He will take care)'
    ];

    $payment_terms = [
        'advance' => 'Advance',
        'advance_50_before_dispatch' => '50% Advance Reliased Before Dispatch',
        'balance' => 'Balance',
        'advance_50_balance_after_dispatch' => '50% Advance Reliased Bal.After Dispatch',
        'cod_current_dated_cheque' => 'COD on delivery (Current Dtd Chq)',
        'against_delivery_1_2_days' => 'Against delivery (1-2 days)',
        'pdc_on_delivery_against_1_2_days' => 'PDC on Delivery(Against delivery (1-2 days))',
        'credit_7_days' => '7 days credit',
        'pdc_7_days_after_dispatch' => '7 days PDC after Dispatch',
        'pdc_7_days_in_advance' => '7 days PDC in Advance',
        'credit_10_days' => '10 days credit',
        'credit_15_days' => '15 days credit',
        'pdc_15_days_after_dispatch' => '15 days PDC after dispatch',
        'pdc_15_days_on_cod' => '15 days PDC on COD',
        'credit_20_days' => '20 days credit',
        'credit_21_days' => '21 days credit',
        'credit_30_days' => '30 days credit',
        'pdc_30_days_in_advance' => '30 days PDC in Advance',
        'pdc_30_days_after_dispatch' => '30 days PDC after Dispatch',
        'credit_40_days' => '40 days credit',
        'pdc_40_days_after_dispatch' => '40 days PDC after Dispatch',
        'credit_45_days' => '45 days credit',
        'pdc_45_days_in_advance' => '45 days PDC in advance',
        'credit_60_days' => '60 days credit',
        'pdc_60_days_after_dispatch' => '60 days PDC after Dispatch',
        'credit_75_days' => '75 days credit',
        'pdc_90_days_after_dispatch' => '90 days PDC after Dispatch',
        'credit_90_days' => '90 days credit',
        'credit_120_days' => '120 Days',
        'foc_sending' => 'FOC Sending',
        'advance_80_20_on_delivery' => '80% Advance 20% against delivery'
    ];

    Tygh::$app['view']->assign('freight_terms', $freight_terms);
    Tygh::$app['view']->assign('insurance_terms', $insurance_terms);
    Tygh::$app['view']->assign('payment_terms', $payment_terms);
}