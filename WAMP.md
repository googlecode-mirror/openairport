# W.A.M.P. #

A WAMP Server is a server that is running Microsoft Windows, uses Apache as the web service, MySQL as the database backend, and PHP as the scripting language.

## Types of WAMPs ##

There are many different configurations you can use to run a WAMP server a comprehensive list of different system setups can be found [here](http://en.wikipedia.org/wiki/Comparison_of_WAMPs), two very good applications that run straight out of the download with minimum configuration are:

  * [WAMPServer](http://www.wampserver.com/en/)
  * [XAMPP](http://www.apachefriends.org/en/xampp.html)

Both are great packages, I like XAMPP; however, tastes vary and so do system configurations.

## Installing OpenAirport into a WAMP ##

To install OpenAirport onto a WAMP server two things must be done:

  * Import the OpenAirport SQL Statement using either MySQL Administrative Tools or PHPMyadmin.  Either is good; however, importing with PHPMyadmin can break Index and InnoDB connections.

  * Copy the OpenAirport code to the wwwroot directory of the WAMP Server (htdocs: Xampp, www: WAMPServer).  This is best done in its own subdirectory like C:/Xampp/htdocs/yourairport/openairport.


Once OpenAirport is installed onto the web server, you can test the install by turning on the web server and navigating your web browser to your localhost.  An airport will need to follow the [customization](customization.md) procedures outlined in the [customization](customization.md) page.