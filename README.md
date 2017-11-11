Flickr for idno
===============

This plugin provides OAuthed Flickr POSSE support for idno.

Installation
------------

* Prerequisite PHP5 OAuth (pecl?) php5-oauth debian package

* Drop the Flickr folder into the IdnoPlugins folder of your idno installation.
* Log into idno and click on Administration.
* Click "install" next to Flickr.

* Proceed to [Flickr Services](https://www.flickr.com/services/)
* [Create an app](https://www.flickr.com/services/apps/create/apply/) to obtain credentials
* Fill-in Administration/Flickr, ensuring that the callback is filled in at Flickr

* Go to Account/Flickr for each person using your Known instance and Flickr

Contains
--------

* No longer contains Rasmus Lerdorf's Flickr class: http://toys.lerdorf.com/archives/34-Flickr-API-Fun.html
* Contains a modified version of David Wilkinson's DPZFlickr class: https://github.com/lucasgd/DPZFlickr/
