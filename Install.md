# Introduction #

In order to install OpenAirport a few things must be completed or in hand prior to making the SourceCode effective.

# Details #

  * You must have an active server; either WAMP or LAMP
  * You will need MySQL version 5 or above.
  * You will also need the SQL Insert Statements
  * You will need apache server or a PHP engine

Once those items are installed, the source code can be downloaded from the truck placed in the HTML directory of your server.  Environmental variables should be changed in the _gs\_settings.inc for your database._

Futhure modifications to the database will be needed to customize OpenAirport to your airport; however, they are changes that are specific to your airport and must be made by you. This typically includes data entry like:

  * The name of your airport
  * Your system users
  * List of your equipment
  * etc.

Each module will contain a section based on what you will need to customize for your airport to ensure the system works correctly for you. But this list should only be considered the starting place as you have the ability to customize anything you want.