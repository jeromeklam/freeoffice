<?php
namespace FreeOffice\Http;

/**
 * FreeEDMS routes
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
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_ROLE, $apiRoute)) {
                        $myRoute->setRole($apiRoute[\FreeFW\Router\Route::ROUTE_ROLE]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_AUTH, $apiRoute)) {
                        $myRoute->setAuth($apiRoute[\FreeFW\Router\Route::ROUTE_AUTH]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_INCLUDE, $apiRoute)) {
                        $myRoute->setInclude($apiRoute[\FreeFW\Router\Route::ROUTE_INCLUDE]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_MODEL, $apiRoute)) {
                        $myRoute->setDefaultModel($apiRoute[\FreeFW\Router\Route::ROUTE_MODEL]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_COLLECTION, $apiRoute)) {
                        $myRoute->setCollection($apiRoute[\FreeFW\Router\Route::ROUTE_COLLECTION]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_COMMENT, $apiRoute)) {
                        $myRoute->setComment($apiRoute[\FreeFW\Router\Route::ROUTE_COMMENT]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_PARAMETERS, $apiRoute)) {
                        $myRoute->setParameters($apiRoute[\FreeFW\Router\Route::ROUTE_PARAMETERS]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_RESULTS, $apiRoute)) {
                        $myRoute->setResponses($apiRoute[\FreeFW\Router\Route::ROUTE_RESULTS]);
                    }
                    if (array_key_exists(\FreeFW\Router\Route::ROUTE_SCOPE, $apiRoute)) {
                        $myRoute->setScope($apiRoute[\FreeFW\Router\Route::ROUTE_SCOPE]);
                    }
                    $routes->addRoute($myRoute);
                }
            }
        }
        return $routes;
    }
}
