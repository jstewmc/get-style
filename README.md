# get-style
Get a Cascading Style Sheet (CSS) inline style from a PHP array.

```php
use Jstewmc\GetStyle;

// define your styles
$styles = [
    // the asterisk is a special name for global style declarations
    '*' => [
        'color'     => 'red',
        'font-size' => 'small'
    ],
    'foo' => [
        'color'     => 'blue',
        'font-size' => 'medium'
    ],
    'bar' => [
        'color'     => 'green'
    ]
];

// instantiate the get-style service
$css = new Get($styles);

// get the styles
$css();           // returns "color: red; font-size: small;"
$css('foo');      // returns "color: blue; font-size: medium;"
$css('bar');      // returns "color: green; font-size: small;"
$css('foo bar');  // returns "color: green; font-size: medium;"
```

This library is a (very) simple PHP Cascading Style Sheet (CSS) reader. It does not claim to be a full CSS parser! There are several of those already. This library is just a quick and easy way to produce inline CSS from an array of styles.

## Names

This library has no concept of CSS selectors! Style names are treated as array keys. They can be any valid string. For example, `p`, `a.foo`, `.foo`, and `foo` are all valid style names. 

Keep in mind, the asterisk (`*`) is a special style name that's treated as the global default style. If an asterisk style is defined, all styles will start with its declarations.

## Declarations

This library merges style declarations like CSS. Given a space-separated string of style names (e.g., `"foo bar baz"`), it will merge the declarations with later declarations taking precedence over previous ones (i.e., `"foo < bar < baz"`).

Keep in mind, this libary is not smart! It requires (very) simple style declarations.

### Use valid declarations

This library makes no effort to validate your declarations. Make sure your CSS declarations are valid. Otherwise, you will have layout issues.

### Avoid short-hand declarations

Short-hand CSS declarations like `"margin" => "0 0 10px"` cannot be merged intelligently with other declarations.

For example:

```php
use Jstewmc\GetStyle;

// this will not work!
$styles1 = [
    '*' => [
        'margin' => '0 0 10px'
    ],
    'foo' => [
        'margin-top' => '15px'   
    ]
];

// this will work!
$styles2 = [
    '*' => [
        'margin-top'    => '0',
        'margin-left'   => '0',
        'margin-right'  => '0',
        'margin-bottom' => '10px'
    ],
    'foo' => [
        'margin-top' => '15px'
    ]  
];

// create two css services
$css1 = new Get($styles1);
$css2 = new Get($styles2);

$css1('foo');  // returns "margin: 0 0 10px; margin-top: 15px;"
$css2('foo');  // returns "margin-top: 15px; margin-left: 0; ... "
```

### Watch quotes

This library will not quote space-separated styles. Make sure you quote any space-separated styles in your declarations like `"Courier New"`. 

```php
use Jstewmc\GetStyle;

// this will not work!
$styles1 = [
    '*' => [
        'font-family' => 'Courier New'
    ]
];

// this will work!
$styles2 = [
    '*' => [
        'font-family' => '\'Courier New\''
    ] 
];

// create two css services
$css1 = new Get($styles1);
$css2 = new Get($styles2);

$css1();  // returns "font-family: Courier New;"
$css2();  // returns "font-family: 'Courier New';"
```

Keep in mind, if you are outputting your css as an inline style, make sure your CSS quotes don't collide with the quotes in your HTML source code. 

For example:

```php
use Jstewmc\GetStyle;

$styles = [
    '*' => [
        'font-family' => '"Courier New"'
    ]
];

$css = new Get($styles);

echo '<p style="'. $css() .'">Hello world!</p>'
```

The example above will produce the following broken HTML:

```html
<p style="font-family: "Courier New"">Hello world!</p>
```

### Avoid space-separated declarations

Even if you quote space-separated declarations correctly, you can still run into issues.

An email's HTML body is aggressively line-wrapped at 80 characters or less by some email clients. Newlines in the source code are replaced by spaces, and newlines characters are inserted where needed. 

If a newline is inserted at the space in a declaration name, the declaration will break, and the corresponding element will be un-styled. At a minimum, this behavior has been confirmed on OSX 10 running Apple Mail 8.

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## License

[MIT](https://github.com/jstewmc/get-style/blob/master/LICENSE)

## Version

### 1.0.0, August 16, 2016

* Major release
* Update `composer.json`
* Update comments

### 0.1.0, August 7, 2016

* Initial release