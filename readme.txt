=== IceCaptcha ===
Contributors: IceCaptcha
Donate link: http://IceCaptcha.com/
Tags: antispam, anti-spam, spam, captcha, comment, comments, login, security, captcha on comments, captcha on registration, captcha protection, free captcha
Requires at least: 2.7
Tested up to: 3.5
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is the new generation IceCaptcha plugin. The Anti-Spam security system misses no bots and does not disturb a human being.

== Description ==

The new generation “IceCaptcha” plugin. 

The new security system presupposes clicking on the circle of a particular color instead of entering numbers or other redundant actions. It is done instead of clicking on the button, i.e. no spare action is required!

As for the bots or foreign captcha entering services, the task practically can’t be solved as the circle is drawn randomly in various places, it is of random color and the background (the picture itself) is also different every time. The click spot coordinates are encrypted and sent to the server where the checkup is performed. This all brings us to the fact that there is no chance of switching or calculating the correct result for any third party software.

You can use our captcha for:
•	Registering new users
•	Authenticating users
•	Commenting anti spam security

== Installation ==
You have to perform just a few simple steps to install IceCaptcha:

1. Upload plugin to the `/wp-content/plugins/` folder
1. Activate the plugin in the admin panel
1. Configure the plugin in the plugin menu if you feel the necessity to do it.

== Frequently Asked Questions ==

= How do I change the size of the circle? =

You can indicate optional circle radius in the plugin settings inside your admin panel. The default size is set to 10.

= What do I need the `scale` for? =

Scale is used to change the size of the captcha itself (the picture). Let’s say you’ve got some specific commentaries’ form design with no room for the standard “big” captcha, in this case you can adjust the size of the captcha and make it smaller (e.g. 0.8) to have it perfectly fitting your design.

= What are the numbers in the scale? =

That’s quite simple, 1 constitutes 100% of the standard captcha size. Wordpress uses 0.92, which corresponds to the 92%. If you need a bigger captcha you are welcome to set any value larger than 1, e.g., 1.2 or 1.33 (120% or 133% correspondingly), if you want a smaller one, than it will work vice versa, e.g - 0.65 (65%).

= Is Icecaptcha free? =
Yes!

== Screenshots ==

1. IceCaptcha on login form.
2. IceCaptcha on comment form.
3. IceCaptcha dashboard settings.

== Changelog ==

= 2.1 =
* fixed admin panel bugs

= 2.0 =
* fixed many bugs

= 1.2 =
* fixed some bugs

= 1.1 =
* fixed link bug when blog is located in the subfolder

= 1.0 =
* Scale parameter is added
* Radius of the Circle parameter is added 

== Upgrade Notice ==

= 2.0 =
Current version.