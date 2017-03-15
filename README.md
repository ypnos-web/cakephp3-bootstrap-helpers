CakePHP 3.x Helpers for Bootstrap
=================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Travis](https://img.shields.io/travis/Holt59/cakephp3-bootstrap-helpers/master.svg?style=flat-square)](https://travis-ci.org/Holt59/cakephp3-bootstrap-helpers)
[![Packagist](https://img.shields.io/packagist/dt/holt59/cakephp3-bootstrap-helpers.svg?style=flat-square)](https://packagist.org/packages/holt59/cakephp3-bootstrap-helpers)

CakePHP 3.x Helpers to generate HTML with @Twitter Boostrap style: `Breadcrumbs`, `Flash`, `Form`, `Html`, `Modal`, `Navbar`, 
`Panel` and `Paginator` helpers available!

How to... ?
===========

#### Installation

If you want the latest **Bootstrap 3** version of the plugin:

- Add the plugin to your `composer.json` (see below if you want to use another branch / version):

```
composer require holt59/cakephp3-bootstrap-helpers:dev-master
```

- Load the plugin in your `config/bootstrap.php`:

```php
Plugin::load('Bootstrap');
```

- [Load the helpers](https://book.cakephp.org/3.0/en/views/helpers.html#configuring-helpers) you want in your `View/AppView.php`:

```php
$this->loadHelper('Html', [
    'className' => 'Bootstrap.Html',
    // Other configuration options...
]);
```

The full plugin documentation is available at https://holt59.github.io/cakephp3-bootstrap-helpers/.

*Migrating to the latest version (>= 3.1.0)? Check the migration guide below!*

#### Table of version and requirements

| Version | Bootstrap version | CakePHP version | Information |
|---------|-------------------|-----------------|-------------|
| master | 3 | >= 3.4.0 | Current active branch. |
| > 3.0.5, <= 3.1.1 | 3 | >= 3.2.3, < 3.4.0 | Bug will be fixed (latest version only). | 
| <= 3.0.5 | 3 | >= 3.0.0 | Not actively maintained (open issues if necessary). |
| 4.0.0-alpha | 4 | N/A | Outdated, do not use. |
| 4.0.1-alpha | 4 | >= 3.4.0 | Coming soon... |

#### Contributing

Do not hesitate to [**post a github issue**](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/new) or [**submit a pull request**](https://github.com/Holt59/cakephp3-bootstrap-helpers/pulls) if you find a bug or want a new feature.


Version 3.1 of the helpers is out!
==================================

A new major version **3.1** of the helpers is now out. This version brings major changes to the way helpers internally works by using
templates instead of the standard `tag()` and `div()` method.

#### Changes

- Most methods are now based on templates, meaning that:
    - Options like `tag`, `aria-*`, `data-*`, ..., have been dropped from various methods.
    - The `templateVars` options is now usable with most methods.
    - There might be escaping issue since the old `div()` and `tag()` methods did not escape content be default, while
the template based methods do. Feel free to open an [issue](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/new) if 
you encounter problems with escaping.

Some minor changes that do not impact the user interface:
- The `BootstrapTrait` class has been split in two classes: `ClassTrait` and `EasyIconTrait`. 
- The test cases have been updated and strenghten to avoid bad modification in the code.

#### Migrating to 3.1

List of changes that need refactoring in your code:

- `BootstrapHtmlHelper`
    - The `faIcon` and `glIcon` have been dropped.
    - The `useFontAwesome` options has been dropped, the new way is to customize the `icon` template.
    - It is no longer possible to use custom `tag` to render labels, badges, alerts (still possible for `tooltip`).
- `BootstrapNavbarHelper`
    - The `autoButtonLink` options has been dropped, this was misleading for many users.

Some options such as `aria-*`, `data-*`, have been dropped from various methods since these are now included in the templates,
if you want to customize them, you should modify the template.

Who is using it?
================

Non-exhaustive list of projects using these helpers, if you want to be in this list, do not hesitate to [email me](mailto:capelle.mikael@gmail.com) or post a comment on [this issue](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/32).

 - [**CakeAdmin**] (https://github.com/cakemanager/cakeadmin-lightstrap), LightStrap Theme for CakeAdmin

Copyright and license
=====================

The MIT License (MIT)

Copyright (c) 2013-2017, Mikaël Capelle.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

See [LICENSE](LICENSE).
