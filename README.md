What is Wikisource Export Website ?
==========================

Wikisource export website is a web site for easy downloads of books from Wikisource in various electronic formats.
It also provides an OPDS catalog.

Installation
============

The repository contains a git submodule repository. After cloning
this repository, you also need to initiate the submodule with:
     git submodule init
     git submodule update 

You will also need to download Jelix 1.3 <a href="http://jelix.org/articles/fr/telechargement/stable/1.3">here</a>. The
lib directory should be put in the main directory (next to wsexport),
you also have to create temp/wsexport and make it writable by your web
server.

The database configuration is located in wsexport/var/config/profiles.ini.php
The database schema can be find in wsexport/modules/wsexport/install/sql/install.sql

Composition
===========

This website is a jelix application named wsexport with only one module wsexport.

Licence
=======

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
