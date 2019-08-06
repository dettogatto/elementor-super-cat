# Elementor Super Cat
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=27Q6J6NGK6JJ2&source=url)

**DISCLAIMER**: This is still a personal project, shared by love. It should not be used in production without prior testing.

Hi there!
This plugin is meant to hack Elementor Pro and add functionality.
Install it just as a regular plugin (download ZIP archieve and upload in WP > Plugins > Add New) and you will find new widgets in the Elementor editor, all the way down, in the category Super Cat, and a new Settings page, called Super Cat.

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

This widget, used in combination with the Elementor Post widget, gives you the same live filtering bar the Portfolio widget has.

The widget has 3 options:
* `CSS ID` needs to be set with the ID of the Posts widget you want to give the filter bar to
* `taxonomy` is the taxonomy you want to use as a filter. Make sure the taxonomy is used by the posts shown in the Posts widget.
* `Show all text` just in case you need something different from "All"

You can also style this widget the same way you would do for a Filter Bar in the Portfolio widget.

## Post Dropdown Filter

A mobile friendly alternative to the Filter Bar

## Post Checkbox Filter

This widget, used in combination with the Elementor Post widget, gives you live filtering with checkbox. Useful with multiple custom taxonomies to filter elements like in e-shops.

The widget has 3 options:
* `CSS ID` needs to be set with the ID of the Posts widget you want to give the filter bar to
* `taxonomy` is the taxonomy you want to use as a filter. Make sure the taxonomy is used by the posts shown in the Posts widget.
* `Order by` you can choose if the filters should be sorted by name or slug

## Param Button

This widget is identical to a normal Elementor Button except you can place received GET and POST parameters in the link field

## Video CTA

This widget is similar to the built-in Elementor Video Widget, but it gives you the ability to show a Call to Action overlayed to the paused video and to open a Popup or a link at the chosen end-time of video.

---

### Donate

If you find this plugin useful consider offering this cat a cup of milk, so it can keep on hacking!

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=27Q6J6NGK6JJ2&source=url)
