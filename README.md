# HTTP Cache Header Builder
Builder for HTTP cache headers like: Cache-Control, Surrogate-Control, Vary, ETag

Value combinations are being validated so you avoid meaningless cache directives.

PSR-15 example usage:
```php
$middleware = new CacheHeaderMiddleware([
	'/assets/**' => new CacheProfile(
		cacheControl: CacheControlHeader::immutableAsset(),
		vary: VaryBuilder::create()->acceptEncoding()->build(),
	),
	'/api/**' => new CacheProfile(
		cacheControl: CacheControlBuilder::create()
			->public()
			->maxAge(60)
			->sMaxAge(300)
			->mustRevalidate()
			->build(),
		vary: VaryBuilder::create()->acceptEncoding()->accept()->authorization()->build(),
			surrogate: SurrogateControl::maxAge(300)->withStaleWhileRevalidate(60),
		),
	'/**' => new CacheProfile(
		cacheControl: CacheControlHeader::revalidate(),
	),
]);
```
