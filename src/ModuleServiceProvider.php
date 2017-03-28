<?php
/**
 * This file is part of Notadd.
 *
 * @author        Qiyueshiyi <qiyueshiyi@outlook.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime      2017-03-22 16:02
 */

namespace Notadd\Shop;

use Notadd\Shop\Models\Order;
use Notadd\Shop\Models\Person;
use Notadd\Shop\Models\Address;
use Notadd\Shop\Models\Product;
use Notadd\Member\Models\Member;
use Notadd\Shop\Models\Business;
use Illuminate\Events\Dispatcher;
use Notadd\Shop\Injections\Installer;
use Notadd\Shop\Injections\Uninstaller;
use Notadd\Shop\Listeners\RouteRegister;
use Notadd\Shop\Listeners\CsrfTokenRegister;
use Notadd\Foundation\Module\Abstracts\Module;

class ModuleServiceProvider extends Module
{
    public function boot()
    {
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);

        $this->loadTranslationsFrom(realpath(__DIR__ . '/../resources/lang'), 'shop');
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../databases/migrations'));

        $this->publishes([
            realpath(__DIR__ . '/../databases/seeds/') => database_path('seeds/'),
            realpath(__DIR__ . '/../config/shop.php')  => config_path('shop.php'),
        ], 'shop');

        // Injection some relations for member model
        $this->injectionRelationsForMember();

        // Injection some functions for member model
        $this->injectionFunctionsForMember();
    }

    public function injectionRelationsForMember()
    {
        Member::injectionFunction('address', function ($model) {
            return $model->hasMany(Address::class, 'user_id', 'id');
        });

        Member::injectionFunction('profile', function ($model) {
            if ($model->hasGroup(['business', 'nonprofit'])) {
                return $model->hasOne(Business::class, 'user_id', 'id');
            }

            return $model->hasOne(Person::class, 'user_id', 'id');
        });

        Member::injectionFunction('product', function ($model) {
            return $model->hasMany(Product::class, 'user_id', 'id');
        });
    }

    public function injectionFunctionsForMember()
    {
        Member::injectionFunction('relationsToArray', function ($model) {
            return array_merge($model->attributesToArray(), $model->profile ? $model->profile->attributesToArray() : []);
        });

        Member::injectionFunction('isAdmin', function ($model) {
            return $model->hasGroup('admin');
        });

        Member::injectionFunction('isPerson', function ($model) {
            return $model->hasGroup('person');
        });

        Member::injectionFunction('isCompany', function ($model) {
            return $model->hasGroup('business');
        });

        // Cart Manage
        Member::injectionFunction('getCartCount', function ($model) {
            $basicCart = Order::ofType('cart')->where('user_id', $model->id)->first();
            if (! $basicCart) {
                return 0;
            } else {
                $totalItems = 0;
                foreach ($basicCart->details  as $orderDetail) {
                    $totalItems += $orderDetail->quantity;
                }

                return $totalItems;
            }
        });

        Member::injectionFunction('getCartContent', function ($model) {
            $basicCart = Order::ofType('cart')->where('user_id', $model->id)->first();
            if (! $basicCart) {
                return [];
            } else {
                return $basicCart->details;
            }
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            realpath(__DIR__ . '/../config/shop.php'),
            'shop'
        );
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
