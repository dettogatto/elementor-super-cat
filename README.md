# Elementor Super Cat
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=27Q6J6NGK6JJ2&source=url)

Hi there!
This plugin is meant to hack Elementor Pro and add functionality.
Install it just as a regular plugin (download ZIP archieve and upload in WP > Plugins > Add New) and you will find new widgets in the Elementor editor, all the way down, in the category Super Cat.

The widgets and their functionalities:

## Form Poster

This widget needs to be used next to an Elementor Form and it turns it into a regular form that sends a post request to a page.

The widget has 3 options:
* `CSS ID` needs to be set with the ID of the form you want to tweak
* `Action URL` is the URL of the page you want the form to submit to
* `replace` gives you the option to turn underscore separated numbers in the name of the fields into an array-like notation. If set to "yes", for example, it will turn `field_1_3` into `field[1][3]`

Note that every other action set in the Elementor Form widget will NOT be executed.
To set names of fields, default values, style, etc., act on the original form.

## Post Filter Bar

This widget, used in combination with the Elementor Post widget, gives you the same live filtering bar that the Portfolio widget has.

The widget has 3 options:
* `CSS ID` needs to be set with the ID of the Post widget you want to give the filter bar to
* `taxonomy` is the name of the taxonomy you want to use as a filter. Since the widget runs with Javascript the value you want to give to this field can be found inspecting the Post widget with a web inspector. Inspect the class names of a single <article> tag to find out your options. The last classes should contain the taxonomy names.
E.g `class="elementor-post elementor-grid-item post-329 post type-post status-publish format-standard has-post-thumbnail hentry category-news"` -> `category` is the name of the taxonomy you need to use.
* `Show all text` just in case you need something different from "All"

You can also style this widget the same way you would do for a Filter Bar in the Portfolio widget.

---

### Plugin folder structure

* `assets` directory - holds plugin JavaScript and CSS assets
  * `/js` directory - Holds plugin Javascript Files
  * `/css` directory - Holds plugin CSS Files
* `widgets` directory - Holds Plugin widgets
  * `/form-poster.php` - Form Poster Widget class
  * `/post-filter.php` - Post Filter Bar Widget class
* `index.php`	- Prevent direct access to directories
* `elementor-super-cat.php`	- Main plugin file, used as a loader if plugin minimum requirements are met.
* `plugin.php` - The actual Plugin file/Class.

### Donate

If you find this plugin useful consider offering this cat a cup of milk, so it can keep on hacking!

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=27Q6J6NGK6JJ2&source=url)
