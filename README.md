Jedirect
========
[![Version](http://img.shields.io/github/release/amusablelemur/jedirect.svg)](https://github.com/AmusableLemur/Jedirect/releases) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/1c21e587-11b1-4d9e-913e-b79a36ac6a0d/mini.png)](https://insight.sensiolabs.com/projects/1c21e587-11b1-4d9e-913e-b79a36ac6a0d)

A small and simple link shortener service. This web-based app makes shared links more secure by only allowing them to be used once and within 24 hours.

Requirements
------------
 * PHP 5.3
 * MySQL
 * mod_rewrite (if using Apache)

Installation
------------
You need to first set up a database in MySQL and import the file `schema.sql`. Other databases should work but might require minor modifications to the code. Update the configuration in `config-sample.json` to match your environment and save it as `config.json`.

License
-------
The MIT License (MIT)

Copyright (c) 2014 Rasmus Larsson

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
