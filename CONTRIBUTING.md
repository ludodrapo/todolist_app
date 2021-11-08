# Contributing to My Lil' 2Do List App

:+1: First of all, thank you for taking the time to contribute! :+1:

#### Table Of Contents

[Installing the project](#installing-the-project)

[Contributing](#contributing)

[Code standard](#code-standard)

[Code of Conduct](#code-of-conduct)

[I just have a question!!!](#i-just-have-a-question)

## Installing the project

If you wish to contribute, which I already thank you sincerely for, the first thing to do might be to fork or download the files and follow the [Readme.md](README.md) file instructions.

## Contributing

You can propose any change, visual enhancement, functionality adding or code improvement by simply create an issue with the right label or even directly in a pull request with a short and understantable title. In that last case, please make sure you did pass all the basic tests by running ```vendor/bin/phpunit``` and that your code respect the standards.

## Code standard

To check if your code is respectful of the main standards, the project comes with a few static analysis tools you might like to use :

### Backend

* Phpinsights by running ```vendor/bin/phpinsights``` or ```vendor/bin/phpinsights analyse ./path/to/your/file.php```
* Phpstan by running ```vendor/bin/phpstan``` or ```vendor/bin/phpstan analyse ./path/to/your/file.php```
* Phpcs by running ```vendor/bin/phpcs```
* Phpcpd by running ```php phpcpd.phar ./path/to/your/file.php```

### Frontend

* Stylelint by running ```npx stylelint ./assets/styles/```
* Twigcs  by running ```vendor/bin/twigcs ./templates/```

## Code of conduct

This project and everyone participating in it is governed by the [Project code of conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to [ludodrapo@gmail.com](mailto:ludodrapo@gmail.com).

## I just have a question!!!

**Note:** [Please don't file an issue to ask a question.]
You'll get faster results by sending an simple email to [ludodrapo@gmail.com](mailto:ludodrapo@gmail.com)
