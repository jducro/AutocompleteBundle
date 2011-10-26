jducroAutocompleteBundle
============

What's that?
--------------
A bundle to provide a JQueryAutocompleteType to use in your forms.
Inspired from winzouAutocompleteBundle

Requirements
------------
jQuery and jQuery UI including a UI theme

Contribution
------------

Usage
------

  - Register this bundle, and publish the assets
  - In your config.yml:
  
```yaml
twig:
    form:
        resources:
            - 'jducroAutocompleteBundle:Form:fields.html.twig'
```

  - In your From:
  
```php
    ->add('test', 'autocomplete_entity', array(
        'class' => 'YourSuperBundle:OneSuperEntity',
    ))
    
```

  - In your view:
  
```html

<form method="post">
    {{ form_widget(form) }}
    <input type="submit" />
</form>
```
  - Autocomplete with ajax isn't yet ready, it's comming. For now you just pass the choices in the HTML page, and jQuery doest the rest.

Todo
-----
  - Ajax autocompletion

License
--------
This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE