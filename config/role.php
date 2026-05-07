<?php
$employee_role_types = [
    'admin' => 1,
    'nhansu' => 2,
    'ketoan' => 3,
    'nhanvien' => 4,
];
$employee_roles = [
    'admin' => [
        'label' => 'admin',
        'value' => $employee_role_types['admin']
    ],
    'nhansu' => [
        'label' => 'hr',
        'value' => $employee_role_types['nhansu']
    ],
    'ketoan' => [
        'label' => 'accountant',
        'value' => $employee_role_types['ketoan']
    ],
    'nhanvien' => [
        'label' => 'employee',
        'value' => $employee_role_types['nhanvien']
    ],
];
$employee_scopes = [
    $employee_role_types['admin'] => [
        'contract',
        'department',
        'employee',
        'payroll',
        'payroll_detail',
        'attendance',
        'evaluation',
        'reward_discipline',
        'asset',
        'asset_assignment'
    ],
    $employee_role_types['nhansu'] => [
        'contract',
        'employee',
         'evaluation',
         'reward_discipline',
         'asset',
         'asset_assignment'
    ],
    $employee_role_types['ketoan'] => [
        'attendance',
        'payroll',
        'payroll_detail',
    ],
    $employee_role_types['nhanvien'] => [
        'attendance',
        'payroll',
        'payroll_detail',
        'evaluation',
        'reward_discipline'
    ]
];
$contract_types = [
    'fulltime' => [
        'label' => 'Fulltime',
        'value' => 1
    ],
    'parttime' => [
        'label' => 'Parttime',
        'value' => 2
    ],
];

define('employee_roles', $employee_roles);
define('employee_role_types', $employee_role_types);
define('employee_scopes', $employee_scopes);
define('contract_types', $contract_types);
