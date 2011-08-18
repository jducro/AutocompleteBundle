winzouAutocompleteBundle
============

What's that?
--------------
A still-in-dev bundle, which will provide a JQueryAutocompleteType to use in your forms.
As well as plain array autocomplete actually.

Contribution
----------
I'm looking for a way to hugely improve this bundle, I'm not that happy with it.

Usage
------
  - Register this bundle.
  - Register winzouCacheBundle : https://github.com/winzou/CacheBundle (no, Symfony doesn't have a cache system on its own)
  - In your controller:
    $form = $this->createFormBuilder()
        ->add('test', 'autocomplete_entity', array(
            'class' => 'AssoBookBundle:Category',
            'property' => 'name',
            'field_id' => 'actest',
            'em' => $this->getDoctrine()->getEntityManager()
        ))
        ->getForm();
  - Autocomplete with ajax isn't yet ready, it's comming. For now you just pass the choices in the HTML page, and jQuery doest the rest.

Todo
-----
  - Ajax autocompletion
  - Find a way to handle plain/entity autocomplete
  - More than jQuery, it would be nice to provide Prototype or even plain JS compatibility

License
--------
This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE