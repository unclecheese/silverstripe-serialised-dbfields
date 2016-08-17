# Serialised DB Fields for SilverStripe

Provides serialised data (YAML, JSON) in text fields that are traverseable as nested ViewableData objects.

## Installation
`composer require uncelcheese/silverstripe-serialised-db-fields`

## Recommended add-ons

If you plan on editing the serialised data in the CMS, you'll probably want to install [CodeEditorField](https://github.com/nathancox/silverstripe-codeeditorfield).

`composer require nathancox/codeeditorfield`

The database fields are set up to automatically scaffold `CodeEditorField` when available.

## Usage

*mysite/code/MyPageType.php*
```php
class MyPageType extends Page {
  
  private static $db = [
    'MyJSONData' => 'JSONField',
    'MyYAMLData' => 'YAMLField'
  ];
  
  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->addFieldToTab('Root.JSONData', CodeEditorField::create('MyJSONData')
      ->setMode('json')
    );
    $fields->addFieldToTab('Root.YAMLData', CodeEditorField::create('MyYAMLData')
      ->setMode('yaml')
    );
    
    return $fields;
  }
}
```

*$MyJSONData*
```json
{
  "Category": "Lollies",
  "BannerImage": "/path/to/image.png",
  "Products": [
    {
      "Title": "Snake",
      "Colour": "Red"
    },
    {
      "Title": "Jet plane",
      "Colour": "Purple"
    }
  ]
}
```


*themes/mytheme/templates/Layout/MyPageType.ss*
```html
<% with $MyJSONData %>
  Category: $Category<br>
  <img src="$BannerImage"><br>
  <ul>
  $Products.count total products
  <% loop $Products %>
  	<li>$Title ($Colour)</li>
  <% end_loop %>
  </ul>
<% end_with %>
```

## Casting

By default, all scalar values fall back on the `$default_cast` setting of `ViewableData` (Text). To customise the casting, use the `|` symbol in your field value.

```yaml
Price: Currency|20.00
StartDate: Date|2016-01-01
```

## Tests

`framework/sake dev/tests/SerialisedDBFeildsTest`

## Troubleshooting

Ring Uncle Cheese.
