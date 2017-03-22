<?php
/**
 * This file is part of Notadd.
 *
 * @author        Qiyueshiyi <qiyueshiyi@outlook.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime      2017-03-22 16:02
 */

namespace Notadd\Shop;

use Notadd\Shop\Injections\Installer;
use Notadd\Shop\Injections\Uninstaller;
use Notadd\Foundation\Module\Abstracts\Module;

class ModuleServiceProvider extends Module
{
    public function boot()
    {
        
    }

    public static function description()
    {
        return 'Notadd 商城模块';
    }

    public static function install()
    {
        return Installer::class;
    }

    public static function uninstall()
    {
        return Uninstaller::class;
    }

    public static function name()
    {
        return '商城';
    }

    public static function version()
    {
        return '1.0.0';
    }
}