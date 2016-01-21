# Introduction #

Here are some considerations when installing and customizing your Part 139.327 module.


## Details ##



Because the checklist forms the backbone if the OpenAirport system how you create the checklist will impact how you use the system.  An attempt has been made to ensure that the default checklists will work for all airports.  They utilize Part 139.327, applicable Advisory Circulars and other sources of information to ensure that each type of inspection checklist contains the information needed to make accurate assessments of trouble areas on your airport.

No customization of the source code should be needed for this section; however, airport specific data entry is something to be aware of.

  * The OpenAirport system uses a map of your airport as the foundation of where discrepancies are located.  This map must be updated to reflect your airport. Information on the map is contained in file "_gs\_gps\_settings.inc.php_".  Adjust the map name and size accordingly as specified in the file.

  * You will also need to adjust the GPS coordinate variables to reflect the size and shape of your map otherwise exporting the discrepancies to Google Earth will not work.  In the downloads section is an excel spreadsheet to compute what your GPS values should be.  You will need a copy of Google Earth to assist with the calculations.  An alternative is to diable exporting to Google Earth; however, once you see how Google Earth implementation is done you will never want to live without it.

  * You may choose to rename the checklist items, remove them or restructure them as you wish.  Directions on how to modify the contents of a checklist will be forthcoming as more documentation is created.

  * The 327 system is only depth 1 so it only tracks map locations and not items of a specific nature this will mean you do not need to enter any equipment into the inventory tables to have the inspection system work. In general the higher the depth the more data entry will be required by the airport to bring the OpenAirport framework online.