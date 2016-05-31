#!/bin/bash
# bash ./sedExport.sh ListExportFile.txt

echo "Start: sedExport fileINput"
# Check that exactly 1 value were passed in
if [ $# -ne 1 ]; then
echo “This script loops on the file provided as a command parameter string to filter, update and concatenate WP export files and to buddypress WP. Usage: bash ./sedexport.sh filename ”
exit 127
fi

# read the input file with export.xml file with old and new id separated with semi-colon
while  IFS=";" read FILE Slug Old_id New_id Title SubTitle
do
#  echo $p
  # extract three parameter from the lines in the input file
  #read FILE Slug Old_id New_id Title SubTitle<<<$(IFS=";"; echo $p)
  echo "$Slug $FILE with $Old_id to $New_id for $Title -- $SubTitle"
  #cat $FILE |sed "s/<wp:post_parent>$2</<wp:post_parent>$3</g"
  #
  cat "$FILE" |sed "s/<wp:post_parent>$Old_id</<wp:post_parent>$New_id</g" |
  sed "s@<generator>http://wordpress.org/?v=3.5</generator>@<generator>http://wordpress.org/?v=3.5</generator>\\
  <item>\\
   <title>$Title</title>\\
   <link>http://capexpecommunity.org/?post_type=gpages&#038;p=$New_id</link>\\
   <pubDate>Sun, 29 May 2016 08:45:05 +0000</pubDate>\\
   <dc:creator><![CDATA[dsnyers]]></dc:creator>\\
   <guid isPermaLink=\"false\">http://capexpecommunity.org/?post_type=gpages&#038;p=$New_id</guid>\\
   <description></description>\\
   <content:encoded><![CDATA[$SubTitle]]></content:encoded>\\
   <excerpt:encoded><![CDATA[]]></excerpt:encoded>\\
   <wp:post_id>$New_id</wp:post_id>\\
   <wp:post_date><![CDATA[2016-05-29 08:45:05]]></wp:post_date>\\
   <wp:post_date_gmt><![CDATA[2016-05-29 08:45:05]]></wp:post_date_gmt>\\
   <wp:comment_status><![CDATA[closed]]></wp:comment_status>\\
   <wp:ping_status><![CDATA[closed]]></wp:ping_status>\\
   <wp:post_name><![CDATA[$Slug]]></wp:post_name>\\
   <wp:status><![CDATA[publish]]></wp:status>\\
   <wp:post_parent>0</wp:post_parent>\\
   <wp:menu_order>0</wp:menu_order>\\
   <wp:post_type><![CDATA[gpages]]></wp:post_type>\\
   <wp:post_password><![CDATA[]]></wp:post_password>\\
   <wp:is_sticky>0</wp:is_sticky>\\
</item>@"|
  sed 's/<wp:post_type>page</<wp:post_type><![CDATA[gpages]]></g' |
  sed 's/<wp:post_type>post</<wp:post_type><![CDATA[gpages]]></g' |
  # remove beginning of the xml exported files and of the last two lines before concatenation
  sed '1,57d' |sed '$d' |sed '$d' > "New/$FILE"
done <$1

param=""
while  IFS=";" read FILE Slug Old_id New_id Title SubTitle
do
  param="$param New/$Slug.xml"
done <$1
param="New/a_Begin.xml $param New/a_End.xml"
echo $param
cat $param > New/concatanedExport.xml
