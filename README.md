What is Wikisource Export Website ?
==========================

Wikisource export website is a web site for easy downloads of books from Wikisource in various electronic formats.
It also provides an OPDS catalog.

Installation
============
You have to download files and use it with jelix 1.3, that is downloadable <a href="http://jelix.org/articles/fr/telechargement/stable/1.3">here</a>. You put lib file in the main directory, you create temp/wsexport and you set it writable by the application. You need also a sql database you create with the wsexport/modules/wsexport/install/sql/install.sql file. You can also use the install command of Jelix. The configuation of the database is here : wsexport/var/config/profiles.ini.php

Composition
===========

This website is a jelix application named wsexport with only one module wsexport.

Licence
=======

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
