<?php
$config['img'] = [
    'upload_path' => './upload/imgs/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => 500,
    'max_width' => 1200,
    'max_height' => 1200,
    'overwrite' => true,
];
$config['csv'] = [
    'upload_path' => './upload/csv/',
    'allowed_types' => 'csv',
    'max_size' => 0, // 无限制
    'overwrite' => true,
];
