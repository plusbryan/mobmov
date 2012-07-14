<?php
// FETCH COMMON DATA
     
// check number of current users
$mailing_list_count = mysql_query_result("SELECT COUNT(*) AS count FROM mailinglist",360);

// check number of current chapters
$chapters_count = mysql_query_result("SELECT COUNT(*) AS count FROM chapters WHERE approved=1",360);

// check number of current countries
$countries_count = mysql_query_result("SELECT COUNT(DISTINCT country) AS count FROM chapters WHERE approved=1",360);
