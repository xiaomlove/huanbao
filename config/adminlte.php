<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Huanbao',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Huanbao</b>',

    'logo_mini' => '<b>H</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'admin',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        '论坛',
        [
            'text' => '帖子',
            'url'  => 'admin/topic',
            'icon' => 'pencil',
            'can' => 'admin.topic.index',
        ],
        [
            'text' => '回复',
            'url'  => 'admin/comment',
            'icon' => 'comment',
            'can' => 'admin.comment.index',
        ],
        [
            'text' => '报告',
            'url'  => 'admin/report',
            'icon' => 'comment',
            'can' => 'admin.report.index',
        ],
        [
            'text' => '版块',
            'icon' => 'table',
            'submenu' => [
                [
                    'text' => '列表',
                    'url'  => 'admin/forum',
                    'can' => 'admin.forum.index',
                ],
                [
                    'text' => '分类',
                    'url'  => 'admin/forumtaxonomy',
                    'can' => 'admin.forumtaxonomy.index',
                ],

            ],
        ],
        '用户',
        [
            'text' => '列表',
            'url'  => 'admin/user',
            'icon' => 'user',
            'can' => 'admin.user.index',
        ],
        [
            'text' => '新增',
            'url'  => 'admin/user/create',
            'icon' => 'plus',
            'can' => 'admin.user.create',
        ],
        'JS & HS',
        [
            'text' => 'JS',
            'url'  => 'admin/jishi',
            'icon' => 'female',
            'can' => 'admin.jishi.index',
        ],
        [
            'text' => 'HS',
            'url'  => 'admin/huisuo',
            'icon' => 'h-square',
            'can' => 'admin.huisuo.index',
        ],
        [
            'text' => '关联',
            'url'  => 'admin/huisuojishi',
            'icon' => 'h-square',
            'can' => 'admin.huisuojishi.index',
        ],
        '附件',
        [
            'text' => '图片',
            'url'  => 'admin/attachment',
            'icon' => 'file',
            'can' => 'admin.attachment.index',
        ],
        '权限',
        [
            'text' => '角色',
            'url'  => 'admin/role',
            'icon' => 'address-book',
            'can' => 'admin.permission.index',
        ],
        [
            'text' => '权限',
            'url'  => 'admin/permission',
            'icon' => 'list',
            'can' => 'admin.role.index',
        ],


        [
            'text'    => 'Multilevel',
            'icon'    => 'share',
            'submenu' => [
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
                [
                    'text'    => 'Level One',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'Level Two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'Level Two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
            ],
        ],
        'LABELS',
        [
            'text'       => 'Important',
            'icon_color' => 'red',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => false,
        'select2'    => true,
    ],
];
