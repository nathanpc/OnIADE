# 🏢 OnIADE

A web application to track people inside [IADE](https://www.iade.europeia.pt/)'s building. This project was developed for the **Project III** class of the [Creative Technologies](https://www.iade.europeia.pt/cursos/licenciaturas/licenciaturas-globais/creative-technologies) course.


## Setting Up

If you want to test this project you'll have to do a couple of steps before you
can have everything up and running.

Before we can start, make sure that the project is at the root level of your web
server, since this project wasn't designed to be run from a sub-folder. You can
do this either by setting your web server's document root directory to the
project directory or by adding an entry to your `hosts` file like this:

```
127.0.0.1	oniade.local
```

Then you can [add a new site to your web server using virtual hosts](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-18-04)
with the following configuration making sure you put the appropriate values in
the `ServerName` and `DocumentRoot` fields:

```apacheconf
<VirtualHost *:80>
    ServerAdmin admin@oniade.local
    ServerName oniade.local
    DocumentRoot /var/www/oniade
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

The first thing you'll have to do is create
the database inside of MySQL. This database **must** be called `oniade`, since
no provision was made for different names. This is as simple as:

```sql
DROP DATABASE IF EXISTS oniade;
CREATE DATABASE oniade;
```

After that has been taken care of you'll need to decide if you want to create a
database with empty tables to start fresh or if you just want to try it out.
Either way it's just a matter of using either `sql/initialize.sql` for a blank
slate, or `sql/example_database.sql` for a pre-populated one:

```bash
# For a completely blank database use this command:
mysql -u $USER -p oniade < ./sql/initialize.sql

# For a database with some example data use this command:
mysql -u $USER -p oniade < ./sql/example_database.sql
```

Now that the database is ready to go it's time to setup your environment. First
of all make sure you have [PHP 7](https://www.php.net/downloads.php#v7.4.13)
installed together with preferably [Apache](https://httpd.apache.org/). The
second dependency that we'll need is [Nmap](https://nmap.org/download.html),
which is used to scan the network for active devices. Make sure that after you
install Nmap that you make it available in your system `PATH`, since the script
expects it there.

After having all of the external dependencies installed it's time to actually
setup the PHP dependencies. For this you'll need to have
[Composer](https://getcomposer.org/download/) installed on your system. Then
it's just a matter of running:

```bash
composer install
```

If that went well you're now ready to go! Fire up your browser and check out the
web application!


## License

This project is licensed under the **MIT License**.