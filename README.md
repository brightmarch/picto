# [Picto][picto]
Picto is a small open source application that makes uploading and sharing images very simple. It takes advantage of Postgres, Redis, and Amazon S3 for data storage.

Picto is a Symfony application and distributed under the MIT License. Getting started with it is very simple.

## Installation
Building and hacking Picto only requires Vagrant and VirtualBox. Vagrant will bootstrap an environment complete with PHP 5.5, Postgres 9.2, Redis 2.8, and Ruby 2.0.

To start, install the following software:

* [Vagrant 1.3.5][vagrant]
* [VirtualBox 4.3.2][virtualbox]

After cloning the repository, run the following command to build the Vagrant virtual machine.

    vagrant up

Building the virtual machine will take around 20-30 minutes. Once the Vagrant virtual machine is built, run the following command to SSH into it.

    vagrant ssh

All commands executed for the installation and deployment processes will take place from within the virtual machine itself. After you have connected to the virtual machine, run the following commands to go to the directory where the Picto repository is synced on the virtual machine.

    goapps
    cd picto

From within the root of the repository, copy the `build.settings.template` file to `build.settings` with the following command.

    cp app/config/build.settings.template app/config/build.settings

Open the `build.settings` file and set the following configuration settings:

* `aws_key`
* `aws_secret`
* `image_bucket`
* `secret`

The database settings can be left alone as they are the default values for connecting to Postgres and Redis. Picto does not send any mail currently, so the mailer settings can be left alone for now.

After the `build.settings` file has been updated, run the following command in the root of the repository to build your development environment. 

    ./build-dev

This may take a while as Composer will have to download all of the vendors initially.

Next, create the directory with the following command where Picto will store all of the uploaded images as specified in the `build.settings` file.

    sudo mkdir -p /var/apps/data/images/
    chown -R vagrant:vagrant /var/apps/data

Once Picto is successfully built and the data directory created, run the following command to start the development server.

    ./run-server

## Development
Picto is a fairly standard Symfony application, so developing on it should be simple for anyone familiar with the Symfony bundle layout. All code is stored in the `src` directory, and all configuration data is stored in the `app/config/` directory.

Picto uses the PHP port of Resque for handling background jobs. Currently, Picto uses a Resque job to upload an image to Amazon S3.

To start Resque, you can use either of the following commands.

    ./run-resque
    ./symfony bcc:resque:worker-start upload --foreground

### Databases
Picto uses Postgres as a long term data store. You can access it with the following command.

    psql -hlocalhost -Upicto picto

You can also add this an as alias to your `.bash_aliases` file as `db-picto` to access it quickly.

Redis is available with the following command.

    redis-cli

### Testing
Despite its simplicity, Picto even comes with a few tests. Run the following command to execute the tests.

    ./run-tests

Not all of the tests are great, but at least they are there. They can always improve.

### Style
Picto uses Compass, SASS, and ZURB Foundation for styling. No actual CSS is stored with the project; you must compile the CSS yourself. Run the following commands from within the root of the repository to compile the SASS.

    cd src/Picto/AppBundle/Resources/config/compass/
    compass compile

This will create a minified CSS file named `picto.css` in `src/Picto/AppBundle/Resources/public/css/`. During development, you can run `compass watch` instead of `compass compile` to regenerate the CSS with every change to the SASS.

## Deployment
Picto can be deployed to your server using Capistrano. The virtual machine has Capistrano 2.15.5 installed with SSH agent forwarding enabled so you can deploy directly from it.

Before you deploy, you will have to ensure a production ready `build.settings` file is placed in the standard `shared/system/` directory structure that Capistrano maintains. The Capistrano tasks will use it to build the production ready `parameters.yml` file.

The Capistrano tasks are stored in the `config` directory. You will have to update the `config/deploy/staging.rb` and `config/deploy/production.rb` configuration files to point to your server and directory structure.

I have a great book on [deploying PHP applications][expert-php-deployments] that can really give you a lot more insight on how to deploy Picto (and any other PHP application, for that matter).

## License
The MIT License (MIT)

Copyright (c) 2013 Vic Cherubini, Bright March

[https://picto.io][picto]

[picto]: https://picto.io
[vagrant]: http://downloads.vagrantup.com/
[virtualbox]: https://www.virtualbox.org/wiki/Downloads
[expert-php-deployments]: http://growingsoftware.org/expert-php-deployments/
