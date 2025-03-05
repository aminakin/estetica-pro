<?php

use ApiLib\EntryPoints\Basket\BasketController;
use ApiLib\EntryPoints\Catalog\OffersController;
use ApiLib\EntryPoints\Catalog\ProductsController;
use ApiLib\EntryPoints\Catalog\Section\SectionController;
use ApiLib\EntryPoints\Catalog\Section\SectionTreeController;
use ApiLib\EntryPoints\Iblock\IblockEntryController;
use ApiLib\EntryPoints\Iblock\NewsController;
use ApiLib\EntryPoints\Iblock\NewsReviewsController;
use ApiLib\EntryPoints\Order\OrderCanceledController;
use ApiLib\EntryPoints\Order\OrderController;
use ApiLib\EntryPoints\Order\OrderPaymentController;
use ApiLib\EntryPoints\User\ChangePasswordController;
use ApiLib\EntryPoints\User\ForgotPasswdController;
use ApiLib\EntryPoints\User\RegistrationController;
use ApiLib\Swagger\AutoloadAPI;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Main\Routing\RoutingConfigurator;
use ApiLib\EntryPoints\Prop\PropController;
use ApiLib\EntryPoints\Prop\PropEnumController;
use ApiLib\EntryPoints\Catalog\ProductPropsController;
use ApiLib\EntryPoints\Catalog\SearchController;
use ApiLib\EntryPoints\Order\LocationController;
use ApiLib\EntryPoints\User\LoginController;
use ApiLib\EntryPoints\User\LogoutController;
use ApiLib\EntryPoints\Order\DeliveryController;
use ApiLib\EntryPoints\Order\VirtualOrderController;
use ApiLib\EntryPoints\Order\RefreshOrderController;
use ApiLib\EntryPoints\Catalog\ProductManicureSalesController;
use ApiLib\EntryPoints\Catalog\ProductEyelasheSalesController;
use ApiLib\EntryPoints\Catalog\ProductManicureNewsController;
use ApiLib\EntryPoints\Catalog\ProductEyelasheNewsController;
use ApiLib\API\Request\HttpRequestDecorator;
use ApiLib\EntryPoints\Catalog\FavoritesController;
use ApiLib\EntryPoints\User\MyAccountController;
use ApiLib\EntryPoints\Order\PaymentController;
use ApiLib\EntryPoints\Brand\BrandsController;
use ApiLib\EntryPoints\Bonuse\BonusesController;
use ApiLib\EntryPoints\Order\OrderPaymentLinkController;
use ApiLib\EntryPoints\App\AppTokenController;
use ApiLib\EntryPoints\Catalog\NewSection\NewSectionTreeController;
use ApiLib\EntryPoints\Catalog\NewSection\NewSectionController;
use ApiLib\EntryPoints\Catalog\NewProductsController;
use ApiLib\EntryPoints\Subscribe\ProductSubscribeController;
use ApiLib\EntryPoints\User\PushSettingsController;

return function (RoutingConfigurator $routes) {
    /* hand api gen */
    $routes->get('/api-gen', function (){
        return AutoloadAPI::generateDoc();
    });

    $routes->prefix('api')->group(function (RoutingConfigurator $routes) {
        $routes->post('register', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new RegistrationController())->store($request);
        });
        $routes->post('login', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new LoginController())->store($request);
        });
        $routes->post('forgotpasswd', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new ForgotPasswdController())->store($request);
        });
        $routes->post('changepassword', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new ChangePasswordController())->update($request);
        });
        $routes->post('forgotpasswdchange', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new ForgotPasswdController())->update($request);
        });
        $routes->delete('logout', function() {
            return (new LogoutController())->destroy();
        });

        /* slider route */
        $routes->get('slider', function() {
            return IblockEntryController::getSliderData();
        });

        /* news list route */
        $routes->prefix('news')->group(function (RoutingConfigurator  $routes){
            $routes->get('index', function (){
                return NewsController::getNewsData();
            })->name('news.index');
            $routes->post('add-review', function (HttpRequest $request){
                HttpRequestDecorator::decorator($request);
                return NewsReviewsController::addNewsReviewData($request);
            })->name('news.add.review');
        });

        /* sections */
        $routes->prefix('sections')->group(function(RoutingConfigurator $routes) {
            $routes->get('trees', function() {
                return (new SectionTreeController())->index();
            })->name('section.tree.index');
            $routes->get('{parent}', function($parent) {
                return (new SectionController())->index($parent);
            })->name('section.index');
            $routes->get('show/{id}' , function($id) {
                return (new SectionController())->show($id);
            })->name('section.show');
        });

        /* new sections */
        $routes->prefix('new_sections')->group(function(RoutingConfigurator $routes) {
            $routes->get('trees', function() {
                return (new NewSectionTreeController())->index();
            })->name('section.new.tree.index');
            $routes->get('{parent}', function($parent) {
                return (new NewSectionController())->index($parent);
            })->name('section.new.index');
            $routes->get('show/{id}' , function($id) {
                return (new NewSectionController())->show($id);
            })->name('section.new.show');
        });

        /* props */
        $routes->prefix('props')->group(function (RoutingConfigurator $routes) {
            $routes->prefix('iblocks')->group(function (RoutingConfigurator $routes) {
                $routes->get('{iblock}', function($iblock) {
                    return (new PropController())->index($iblock);
                });
                $routes->get('{iblock}/show/{id}', function($iblock, $id) {
                    return (new PropController())->show($iblock, $id);
                });
            });

            $routes->get('{id}/enums', function($id) {
                return (new PropEnumController())->index($id);
            });
        });

        /*search*/
        $routes->get('search', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new SearchController())->index($request);
        });

        /* basket routes */
        $routes->post('baskets/store', function (HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new BasketController)->store($request);
        })->name('basket.add');
        $routes->post('baskets/update/{id}', function (int $id, HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new BasketController)->update($id, $request);
        });
        $routes->post('baskets/destroy/{id}', function (int $id) {
            return (new BasketController)->destroy($id);
        });
        $routes->get('baskets', function (){
            return (new BasketController)->index();
        })->name('basket.index');

        /* get product route */
        $routes->get('products', function (HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new ProductsController())->index($request);
        });
        $routes->get('new_products', function (HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new NewProductsController())->index($request);
        });
        $routes->prefix('products')->group(function (RoutingConfigurator  $routes){
            $routes->get('show/{id}', function ($id){
                return (new ProductsController)->getProductCardData($id);
            })->name('product.show');
            $routes->get('favorite-toggle/{id}', function ($id){
                return (new FavoritesController())->toggleProductFavorite($id);
            })->name('product.favorite');
            $routes->get('favorites', function (HttpRequest $request){
                HttpRequestDecorator::decorator($request);
                return (new FavoritesController())->index($request);
            })->name('product.favorites.index');
            $routes->get('favorites/ids', function (){
                return (new FavoritesController())->indexIds();
            })->name('product.favorites.index.ids');
            $routes->get('{product_id}/offers', function ($product_id) {
                return (new OffersController())->index($product_id);
            });
            $routes->get('{product_id}/props', function ($product_id) {
                return (new ProductPropsController())->index($product_id);
            });
            $routes->get('manicure_sales', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new ProductManicureSalesController())->index($request);
            });
            $routes->get('eyelashe_sales', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new ProductEyelasheSalesController())->index($request);
            });
            $routes->get('manicure_news', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new ProductManicureNewsController())->index($request);
            });
            $routes->get('eyelashe_news', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new ProductEyelasheNewsController())->index($request);
            });

            $routes->group(function (RoutingConfigurator  $routes){
                $routes->get('subscribe', function (HttpRequest $request){
                    HttpRequestDecorator::decorator($request);
                    return (new ProductSubscribeController)->index($request);
                })->name('product.subscribe.index');
                $routes->get('subscribe/ids', function (HttpRequest $request){
                    return (new ProductSubscribeController)->ids();
                })->name('product.subscribe.ids');
                $routes->post('subscribe', function (HttpRequest $request){
                    HttpRequestDecorator::decorator($request);
                    return (new ProductSubscribeController)->store($request);
                })->name('product.subscribe.store');
                $routes->delete('subscribe/{id}', function ($id){
                    return (new ProductSubscribeController)->destroy($id);
                })->name('product.subscribe.store');
            });
        });

        $routes->prefix('orders')->group(function (RoutingConfigurator $routes) {
            $routes->get('locations', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new LocationController())->index($request);
            });
            $routes->get('deliveries', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new DeliveryController())->index($request);
            });
            $routes->get('payments', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new PaymentController())->index($request);
            });
            $routes->get('show', function(HttpRequest $request) {
                // TODO: use DTO
                HttpRequestDecorator::decorator($request);
                return (new VirtualOrderController())->show($request);
            });
            $routes->post('show', function(HttpRequest $request) {
                // TODO: use DTO
                HttpRequestDecorator::decorator($request);
                return (new RefreshOrderController())->show($request);
            });
            $routes->get('payments/{order_id}', function(HttpRequest $request, $order_id) {
                // TODO: use DTO
                HttpRequestDecorator::decorator($request);
                return (new OrderPaymentController())->show($order_id);
            });
        });
        $routes->post('orders', function(HttpRequest $request) {
            // TODO: use DTO
            HttpRequestDecorator::decorator($request);
            return (new OrderController())->store($request);
        });
        $routes->get('orders', function(HttpRequest $request) {
            // TODO: use DTO
            HttpRequestDecorator::decorator($request);
            return (new OrderController())->index($request);
        });
        $routes->post('orders/canceled/{order_id}', function(HttpRequest $request, $order_id) {
            // TODO: use DTO
            return (new OrderCanceledController())->update($request, $order_id);
        });
        $routes->get('orders/payment_link/{order_id}', function(HttpRequest $request, $order_id) {
            return (new OrderPaymentLinkController())->show($request, $order_id);
        });

        $routes->get('my_account', function(HttpRequest $request) {
            return (new MyAccountController())->show($request);
        });

        $routes->put('my_account', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);
            return (new MyAccountController())->update($request);
        });

        $routes->delete('my_account', function() {
            return (new MyAccountController())->destroy();
        });

        $routes->prefix('my_account')->group(function (RoutingConfigurator $routes) {
            $routes->get('push', function() {
                return (new PushSettingsController())->index();
            });
            $routes->put('push', function(HttpRequest $request) {
                HttpRequestDecorator::decorator($request);
                return (new PushSettingsController())->update($request);
            });
        });

        $routes->get('brands', function() {
            return (new BrandsController())->index();
        });

        $routes->get('bonuses', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);

            return (new BonusesController())->index($request);
        });

        $routes->prefix('bonuses')->group(function (RoutingConfigurator  $routes){
            $routes->get('show', function (){

                return (new BonusesController)->show();
            })->name('bonuses.show');
        });

        $routes->post('app_token', function(HttpRequest $request) {
            HttpRequestDecorator::decorator($request);

            return (new AppTokenController())->store($request);
        });
    });


};
