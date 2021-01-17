<?php
namespace FreeOffice\Http;

/**
 * FreeOffice routes
 *
 * @author jeromeklam
 */
class FreeFW
{

    /**
     * Retourne une liste de routes au format FreeFW
     *
     * @return \FreeFW\Router\RouteCollection
     */
    public static function getRoutes()
    {
        /**
         * @var \Psr\Cache\CacheItemPoolInterface $cache
         */
        $cache   = \FreeFW\DI\DI::getShared('cache');
        if ($cache && $cache->hasItem('FreeOffice.routes')) {
            $item = $cache->getItem('FreeOffice.routes');
            if ($item && $item->isHit()) {
                return $item->get();
            }
        }
        $routes  = new \FreeFW\Router\RouteCollection();
        $paths   = [];
        $paths[] = __DIR__ . '/../resource/routes/restful/routes.php';
        foreach ($paths as $onePath) {
            $apiRoutes = @include($onePath);
            if (is_array($apiRoutes)) {
                foreach ($apiRoutes as $routeId => $apiRoute) {
                    $myRoute = new \FreeFW\Router\Route();
                    $myRoute
                        ->setId($routeId)
                        ->setMethod($apiRoute[\FreeFW\Router\Route::ROUTE_METHOD])
                        ->setUrl($apiRoute[\FreeFW\Router\Route::ROUTE_URL])
                        ->setController($apiRoute[\FreeFW\Router\Route::ROUTE_CONTROLLER])
                        ->setFunction($apiRoute[\FreeFW\Router\Route::ROUTE_FUNCTION])
                    ;
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_ROLE])) {
                        $myRoute->setRole($apiRoute[\FreeFW\Router\Route::ROUTE_ROLE]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_AUTH])) {
                        $myRoute->setAuth($apiRoute[\FreeFW\Router\Route::ROUTE_AUTH]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_INCLUDE])) {
                        $myRoute->setInclude($apiRoute[\FreeFW\Router\Route::ROUTE_INCLUDE]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_MODEL])) {
                        $myRoute->setDefaultModel($apiRoute[\FreeFW\Router\Route::ROUTE_MODEL]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_COLLECTION])) {
                        $myRoute->setCollection($apiRoute[\FreeFW\Router\Route::ROUTE_COLLECTION]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_COMMENT])) {
                        $myRoute->setComment($apiRoute[\FreeFW\Router\Route::ROUTE_COMMENT]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_PARAMETERS])) {
                        $myRoute->setParameters($apiRoute[\FreeFW\Router\Route::ROUTE_PARAMETERS]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_RESULTS])) {
                        $myRoute->setResponses($apiRoute[\FreeFW\Router\Route::ROUTE_RESULTS]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_SCOPE])) {
                        $myRoute->setScope($apiRoute[\FreeFW\Router\Route::ROUTE_SCOPE]);
                    }
                    if (isset($apiRoute[\FreeFW\Router\Route::ROUTE_MIDDLEWARE])) {
                        $myRoute->setMiddleware($apiRoute[\FreeFW\Router\Route::ROUTE_MIDDLEWARE]);
                    }
                    $routes->addRoute($myRoute);
                }
            }
        }
        if ($cache) {
            $item = new \FreeFW\Cache\Item('FreeOffice.routes');
            $item->set($routes);
            $cache->save($item);
        }
        return $routes;
    }
}
