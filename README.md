# Drupalgeddon 2 Test Scripts

This is a small package to test out if your Drupal 8 site is vulnerable to the latest attack trend reported as [CVE-2018-7600](https://www.drupal.org/sa-core-2018-002) also known as Drupalgeddon 2.

The most common entry point for the attack is the default user registration form provided by core, this is where we focus our test in this package.


## How to use

Download the package by cloning the repo. Then run `composer install`, to install all the dependencies.

Create a json file that is just an array of all sites which you wish to test out, example:

```json
// sites.json
[
    "https://www.drupal.org",
    "https://www.atlarge.com",
    ...
]
```

after creating the json file, just run on the command line:

```bash
$ composer run-script drupalgeddon2 sites.json
```

and you should see a report on the command line of all the test results.