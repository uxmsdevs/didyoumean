# Did You Mean?
Provides correct word suggestions based on a dictionary by levenshtein function.

dictionaries directory can be populated for supporting multilanguage suggestions

### Usage
```php
use Uxms\DidYouMean\MatchWord;
```

```php
$dym = new MatchWord('en', 'Banana');

echo $dym->checkMatch();
```

OR

```php
$dym = new MatchWord;
$dym->setLanguage('en')->setWord('Banana');

echo $dym->checkMatch();
```

OR

```php
$dym = new MatchWord;
$dym->setLanguage('en');
$dym->setWord('Banana');

echo $dym->checkMatch();
```

### Returns
Returns json encoded array like:

    {"status":1,"description":"Exact match","closest":"banana"}

or if not matched:

    {"status":0,"description":"Did you mean","closest":"banana"}