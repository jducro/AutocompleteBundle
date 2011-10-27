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
Ajax
------

  - In your From:
  
```php
    ->add('test', 'autocomplete_entity', array(
        'class' => 'YourSuperBundle:OneSuperEntity',
		'ajax'	=> TRUE,
		'route'	=> 'route_of_my_json_function',
    ))

  - In your controller:

/**
 *
 * @Route("/get_json/", name="route_of_my_json_function")
 */
public function getJson()
{
    $json = array();
    $s = $this->getRequest()->query->get('term');
    if ($s)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('YourSuperBundle:OneSuperEntity')->search($s);
        foreach ($entities as $entity)
        {
            $json_entity['id'] = $entity->getId();
            $json_entity['label'] = (string)$entity;
            $json_entity['value'] = $entity->getId();
            $json[] = $json_entity;
        }
    }
    return new Response(json_encode($json));
}

License
--------
This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE