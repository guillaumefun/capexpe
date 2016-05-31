
This is the folder to iteratively update the .xml file from export before importing to new system

1) use "bash ./export.sh" to download all the blog WP export files from capexep.org. All the slug.xml files will be in this directory
2) in the file "ListExportFile.txt" we should have the list of file names on these xml file plus old_id and New_id separated by seli colon;
      traversehaukeliseter-finseski.wordpress.2016-05-28.xml;0;100
      semainealpinedukapexp.wordpress.2016-05-28.xml;0;200
      ...
3) The directory New should exist be empty. It will received the updated files
4) start the bash script : "bash ./sedExport.sh ListExportFile.txt" to update the xml files and concatenate them in a single one called "New/concatanedExport.xml"
5) Edit New/concatanedExport.xml with Oxygen Xml editor
6) Create a xslt transformation scenario with the "capexpexporttransform.xsl" file.
7) Transform the concatanedExport.xml (It will remove item with status= draft and title= Album de l'Expe)
8) create the new groups/expe
9) upload the resulting file on the new site
10) start the scrip to reconnect the child group pages to the root group page
