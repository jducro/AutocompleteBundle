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
  - Register this bundle, and publish the assets
  - Register winzouCacheBundle : https://github.com/winzou/CacheBundle (no, Symfony doesn't have a cache system on its own)
  - In your config.yml:
    ``twig:
        form:
            resources:
                - 'winzouAutocompleteBundle:Form:fields.html.twig'``
  - In your controller:
    ``$form = $this->createFormBuilder()
        ->add('test', 'autocomplete_entity', array(
            'class' => 'YourSuperBundle:OneSuperEntity',
            'property' => 'YourProperty',
            'field_id' => 'autocomplete_test',
            'em' => $this->getDoctrine()->getEntityManager()
        ))
        ->getForm();``
  - In your view:
    ``<script src="{{ assets('bundles/winzouautocomplete/js/jquery-1.6.2.min.js') }}"></script>
    <script src="{{ assets('bundles/winzouautocomplete/js/jquery-ui-1.8.15.custom.min.js') }}"></script>
    <link rel="stylesheet" href="{{ assets('bundles/winzouautocomplete/css/ui-lightness/jquery-ui-1.8.15.custom.css') }}" type="text/css" media="screen" />

    <form method="post">
        {{ form_widget(form) }}
        <input type="submit" />
    </form>``
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