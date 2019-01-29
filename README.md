# yii-api-module

## Multilangual API output

ContentNegotiator works with `Accept-Language` header. If you need multilangual API, you need to add `Accept-Language` header to request and define acceptLanguages array in app params. See example bellow.

```
// config/params.php
return [
    'acceptLanguages' => [
        'en' => 'en-GB', // accept en as en-GB
        'cs-CZ',         // accept cs or cs-CZ as cs-CZ
        'de',            // accept de only (should not be used, better use de => de-DE)
    ]
];
```

See [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) to find language code.

If no `Accept-Language` header is found or language is not supported, first language in acceptLanguages array will be used as default. If no acceptLanguages is defined, is used base app language.

TODO make generating of language list in `acceptLanguages` param automatic, not user defined.
