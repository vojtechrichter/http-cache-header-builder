<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vojtechrichter\HttpCacheHeaderBuilder\CacheProfile;

/**
 * PSR-15 middleware that applies cache headers to responses
 * based on route pattern matching.
 *
 * Usage:
 *   $middleware = new CacheHeaderMiddleware([
 *       '/assets/**' => new CacheProfile(
 *           cacheControl: CacheControlHeader::immutableAsset(),
 *           vary: VaryBuilder::create()->acceptEncoding()->build(),
 *       ),
 *       '/api/**' => new CacheProfile(
 *           cacheControl: CacheControlBuilder::create()
 *               ->public()
 *               ->maxAge(60)
 *               ->sMaxAge(300)
 *               ->mustRevalidate()
 *               ->build(),
 *           vary: VaryBuilder::create()->acceptEncoding()->accept()->authorization()->build(),
 *           surrogate: SurrogateControl::maxAge(300)->withStaleWhileRevalidate(60),
 *       ),
 *       '/**' => new CacheProfile(
 *           cacheControl: CacheControlHeader::revalidate(),
 *       ),
 *   ]);
 *
 * Routes are matched in order — first match wins.
 */
final readonly class CacheHeaderMiddleWare implements MiddlewareInterface
{
    /** @var list<array{pattern: RoutePattern, profile: CacheProfile}> */
    private array $rules;

    public function __construct(
        array $routes,
    ) {
        $rules = [];

        foreach ($routes as $pattern => $profile) {
            $rules[] = [
                'pattern' => new RoutePattern($pattern),
                'profile' => $profile,
            ];
        }

        $this->rules = $rules;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();

        $profile = $this->match($path);

        if ($profile === null) {
            return $response;
        }

        return $this->applyProfile($response, $profile);
    }

    private function match(string $path): ?CacheProfile
    {
        foreach ($this->rules as $rule) {
            if ($rule['pattern']->matches($path)) {
                return $rule['profile'];
            }
        }

        return null;
    }

    private function applyProfile(ResponseInterface $response, CacheProfile $cacheProfile): ResponseInterface
    {
        $response = $response->withHeader('Cache-Control', $cacheProfile->cacheControlHeader->toString());

        if ($cacheProfile->varyHeader !== null) {
            $response = $response->withHeader('Vary', $cacheProfile->varyHeader->toString());
        }

        if ($cacheProfile->surrogateControl !== null) {
            $headerName = $cacheProfile->surrogateControl->getHeaderName($cacheProfile->surrogateHeaderType);
            $response = $response->withHeader($headerName, $cacheProfile->surrogateControl->toSurrogateControl());
        }

        return $response;
    }
}
