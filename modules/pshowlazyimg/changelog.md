PSHOW LAZYIMG CHANGELOG
==========

pre-release
* updated dependencies

v2.8 - 2021.10.13
-----
* fixes for old browsers which do not support webp format

v2.7 - 2021.05.10
-----
* you can set whether the module should use <picture> tag for faster loading of webp graphics
* fixed small bugs

v2.6 - 2021.04.27
-----
* module has been optimized for best performance
* product images can be converted using cron task or manually by url
* all images will be dynamically converted to webp during first load
* from now clearing shop cache will not remove webp images
* images in webp format are now stored next to the original files
* removed lazy load with low resolution placeholder (due to lower performance)
* removed lazy load with shop logo as placeholder (due to lower performance)

v2.5 - 2020.03.27
-----
* fixed bugs

v2.4 - 2020.03.27
-----
* improved WebP conversion
* fixed bugs

v2.3 - 2020.03.12
-----
* from now module support converting images to WebP format on-the-fly

v2.2 - 2019.07.25
-----
* in case of error, try to lazy load without placeholder

v2.1 - 2019.07.24
-----
* improved placeholder generator
* errors will be logged in /var/logs/*_pshowlazyimg.log
* other small fixes

v2.0 - 2019.07.18
-----
* changed way of loading images

v1.14 - 2019.03.08
-----
* fixed a problem with replacing urls for photos with null
* fixed a problem with update of module

v1.13 - 2019.01.29
-----
* bugs fixes

v1.12 - 2019.01.18
-----
* shop logo as default loading image
* fix for dynamically loaded content

v1.11 - 2019.01.02
-----
* bugs fixes

v1.1 - 2019.01.01
-----
* simplified photos uploading
* added translations
* added the ability to upload own loading pictures
* bugs fixes

v1.0
-----
* first version of the module
