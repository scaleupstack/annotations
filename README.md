# ScaleUpStack/Annotations


## About

This library allows to read annotations in the classical DocBlock style including an extended multi-line declaration.

The built-in set of supported annotations can be extended easily by project-specific extensions.


## Installation

Use [Composer] to install this library:

```
$ composer require scaleupstack/annotations
```


## Introduction

This library is not yet documented. But perhaps [Metadata] can be helpful to find out how to use it.

TODO: to be done


## Current State

This library has been developed with a special intention in mind. It will evolve in the context of [Metadata], and [EasyObject].

This results in some known limitations that are no high-priority for this intended purpose.

* E.g. not all allowed class names in `@var` are supported (e.g. UTF-8 special chars).

* Only a sub-set of phpDocumentor annotation tags are built-in.

* Some limitations are perhaps very strict.

If you are missing anything, feel free to contact me, or create a pull request.


Currently those built-in annotations are implemented:

* `@method`

* `@property-read`

* `@var`

All other annotations are represented as `UnknownAnnotation`.

## Contribute

Thanks that you want to contribute to ScaleUpStack/Annotations.

* Report any bugs or issues on the [issue tracker].

* Get the source code from the [Git repository].


## License

Please check [LICENSE.md] in the root dir of this package.


## Copyright

ScaleUpVentures Gmbh, Germany<br>
Thomas Nunninger <thomas.nunninger@scaleupventures.com><br>
[www.scaleupventures.com]



[Composer]: https://getcomposer.org
[Metadata]: https://github.com/scaleupstack/metadata
[EasyObject]: https://github.com/scaleupstack/easy-object
[issue tracker]: https://github.com/scaleupstack/annotations/issues
[Git repository]: https://github.com/scaleupstack/annotations
[LICENSE.md]: LICENSE.md
[www.scaleupventures.com]: https://www.scaleupventures.com/
