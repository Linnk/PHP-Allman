PHP Allman Coding Style Fixer
=============================

The PHP Allman fixer is exactly as the `PHP Coding Standards Fixer`_, but with
two major differences:

1. I believe in Allman indent style.
2. I believe in tabs instead of 4 spaces.

Since this is just about esoteric-taste, I decided to port the php-cs-fixer
into something I feel comfortable.


Installation
------------

Download the source code:

.. code-block:: bash

	$ git clone https://github.com/Linnk/PHP-Allman.git ~/your-path/PHP-Allman/


Install dependencies with `Composer`_:

.. code-block:: bash

	$ cd ~/your-path/PHP-Allman/
	$ composer install


Build the php-allman.phar and create a symbolic link alias php-cs-fixer in
order to make `TextMate`_ to believe this is the mainstream fixer.

.. code-block:: bash

	$ box build -v
	$ sudo ln -s ~/your-path/PHP-Allman/php-allman.phar /usr/local/bin/php-cs-fixer


Now you can use it in TextMate pressing ^ + ⇧ + H.


.. _PHP Coding Standards Fixer:    https://github.com/fabpot/php-cs-fixer
.. _Composer:                      https://getcomposer.org/
.. _TextMate:                      https://github.com/textmate/textmate
