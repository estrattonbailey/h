# h
Functional components *a la* javascript-land, but in PHP.

## Features
1. It's "just PHP", no compiling
2. Fast [(preliminary tests)](https://gist.github.com/estrattonbailey/920042f0ff220fdabf322be84d9317fd)
3. Testable: it's just functions that return markup

## Usage
This is a functional component library. Pass tag name, attributes, and children to the `h()` function and it will return a formatted string representing the markup you've requested.

```php
require 'path/to/h.php'; // or composer (soon)

echo h('h1')([ 'style' => 'color: tomato' ])( 'Hello World' );

// generates: <h1 style="color: tomato">Hello world</h1>
```

## Components
The `h()` function returns another function unless it has been passed children, in which case it returns a string of markup. This allows you to create styled components for later reuse and composition.

```php
$button = h('button')(['class'=>'button button--secondary']);

echo $button('Hello world!');

// generates: <h1 class="button button--secondary">Hello world!</button>
```

Passed attributes are merged with any previously defined attributes.

```php
echo $button([
  'class'=>'button--rounded',
  'style'=>'font-style: italic'
])('Hello world!');

// generates: <button class="button button--secondary button--rounded" style="font-style: italic">Hello world!</button>
```

To add children to an existing component, either pass a single `h()` component to the returned function, or an array of children.

```php
$container = h('div')(['class'=>'wrapper']);

echo $container(
  h('h1')('Hello world!')
);

// generates: <div class="wrapper"><h1>Hello world!</h1></div>

echo $container([
  h('h1')('Heading One'),
  $button('Click me!')
]);

/* generates:
<div class="wrapper">
  <h1>Heading One</h1>
  <button class="button button--secondary">Click me!</button>
</div>
*/
```

You can also easily turn arrays of data into markup:
```php
$arr = [
  'Book Title One',
  'Book Title Two'
];

echo $container(
  array_reduce($arr, function($return, $data){
    $return .= h('p')(['style'=>'block'])($data);
    return $return;
  }, '')
);

/* generates:
<div class="wrapper">
  <p style="display: block">Book Title One</p>
  <p style="display: block">Book Title Two</p>
</div>
*/
```

For more complex components, create a higher order function that returns a formatted `h()` function:

```php
$table = function( $children ){
  return h('table')(['class'=>'wrapper__table'])(
    h('thead')(
      h('tr')( $children )
    )
  );
};

echo $table([
  h('td')('tabular content'),
  h('td')('tabular content'),
  h('td')('tabular content'),
  h('td')('tabular content')
]);

/* generates:
<table class="wrapper__table">
  <thead>
    <tr>
      <td>tabular content</td>
      <td>tabular content</td>
      <td>tabular content</td>
      <td>tabular content</td>
    </tr>
  </thead>
</table>
*/
```
