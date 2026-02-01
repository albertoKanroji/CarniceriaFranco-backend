<?php

namespace App\Config;

class MenuConfig
{
    public static function getMenuItems()
    {
        return [
            [
                'id' => 'dashboard',
                'title' => 'Administración',
                'icon' => [
                    'viewBox' => '0 0 24 24',
                    'paths' => [
                        '<rect x="3" y="3" width="7" height="7"></rect>',
                        '<rect x="14" y="3" width="7" height="7"></rect>',
                        '<rect x="14" y="14" width="7" height="7"></rect>',
                        '<rect x="3" y="14" width="7" height="7"></rect>'
                    ]
                ],
                'submenu' => [
                    [
                        'url' => 'categorias',
                        'title' => 'Categorías',
                        'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'shadow' => 'rgba(102, 126, 234, 0.3)',
                        'icon' => [
                            'name' => 'feather-grid',
                            'paths' => [
                                '<rect x="3" y="3" width="7" height="7"></rect>',
                                '<rect x="14" y="3" width="7" height="7"></rect>',
                                '<rect x="14" y="14" width="7" height="7"></rect>',
                                '<rect x="3" y="14" width="7" height="7"></rect>'
                            ]
                        ]
                    ],
                    [
                        'url' => 'prod/productos',
                        'title' => 'Productos',
                        'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'shadow' => 'rgba(240, 147, 251, 0.3)',
                        'icon' => [
                            'name' => 'feather-package',
                            'paths' => [
                                '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>',
                                '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>',
                                '<polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>',
                                '<line x1="12" y1="22.08" x2="12" y2="12"></line>'
                            ]
                        ]
                    ],
                    [
                        'url' => 'ventas',
                        'title' => 'Ventas',
                        'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'shadow' => 'rgba(79, 172, 254, 0.3)',
                        'icon' => [
                            'name' => 'feather-shopping-cart',
                            'paths' => [
                                '<circle cx="9" cy="21" r="1"></circle>',
                                '<circle cx="20" cy="21" r="1"></circle>',
                                '<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 'clientes',
                'title' => 'Clientes',
                'icon' => [
                    'viewBox' => '0 0 24 24',
                    'paths' => [
                        '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>',
                        '<circle cx="12" cy="7" r="4"></circle>'
                    ]
                ],
                'submenu' => [
                    [
                        'url' => 'clientes',
                        'title' => 'Clientes',
                        'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'shadow' => 'rgba(250, 112, 154, 0.3)',
                        'icon' => [
                            'name' => 'feather-users',
                            'paths' => [
                                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>',
                                '<circle cx="9" cy="7" r="4"></circle>',
                                '<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>',
                                '<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'
                            ]
                        ]
                    ],
                    [
                        'url' => 'despachos',
                        'title' => 'Despachos',
                        'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'shadow' => 'rgba(250, 112, 154, 0.3)',
                        'icon' => [
                            'name' => 'feather-users',
                            'paths' => [
                                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>',
                                '<circle cx="9" cy="7" r="4"></circle>',
                                '<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>',
                                '<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 'sistema',
                'title' => 'Sistema',
                'icon' => [
                    'viewBox' => '0 0 24 24',
                    'paths' => [
                        '<circle cx="12" cy="12" r="3"></circle>',
                        '<path d="M12 1v6m0 6v6m-9-9h6m6 0h6"></path>',
                        '<path d="M20.2 7.8l-4.2 4.2m0 0l-4.2 4.2m4.2-4.2l4.2 4.2M7.8 3.8L3.6 8m4.2 0L3.6 12.2"></path>'
                    ]
                ],
                'submenu' => [
                    [
                        'url' => 'users',
                        'title' => 'Usuarios',
                        'gradient' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                        'shadow' => 'rgba(48, 207, 208, 0.3)',
                        'icon' => [
                            'name' => 'feather-users',
                            'paths' => [
                                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>',
                                '<circle cx="9" cy="7" r="4"></circle>',
                                '<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>',
                                '<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'
                            ]
                        ]
                    ],
                    [
                        'url' => 'roles',
                        'title' => 'Roles',
                        'gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                        'shadow' => 'rgba(168, 237, 234, 0.3)',
                        'icon' => [
                            'name' => 'feather-grid',
                            'paths' => [
                                '<rect x="3" y="3" width="7" height="7"></rect>',
                                '<rect x="14" y="3" width="7" height="7"></rect>',
                                '<rect x="14" y="14" width="7" height="7"></rect>',
                                '<rect x="3" y="14" width="7" height="7"></rect>'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function renderIcon($icon, $size = '22')
    {
        $paths = implode('', $icon['paths']);
        return "
            <svg viewBox=\"{$icon['viewBox']}\" width=\"{$size}\" height=\"{$size}\" stroke=\"currentColor\"
                stroke-width=\"2.5\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"
                class=\"css-i6dzq1\" style=\"filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));\">
                {$paths}
            </svg>
        ";
    }

    public static function renderSubmenuIcon($icon, $size = '20')
    {
        $paths = implode('', $icon['paths']);
        return "
            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$size}\" height=\"{$size}\"
                viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\"
                stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather {$icon['name']}\">
                {$paths}
            </svg>
        ";
    }
}
