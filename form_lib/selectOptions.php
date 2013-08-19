<?php

$state_list = 
array(
                   '' => "--Select--",
                  'AL'=>"Alabama",
                    'AK'=>"Alaska",
                    'AZ'=>"Arizona",
                    'AR'=>"Arkansas",
                    'CA'=>"California",
                    'CO'=>"Colorado",
                    'CT'=>"Connecticut",
                    'DE'=>"Delaware",
                    'DC'=>"District Of Columbia",
                    'FL'=>"Florida",
                    'GA'=>"Georgia",
                    'HI'=>"Hawaii",
                    'ID'=>"Idaho",
                    'IL'=>"Illinois",
                    'IN'=>"Indiana",
                    'IA'=>"Iowa",
                    'KS'=>"Kansas",
                    'KY'=>"Kentucky",
                    'LA'=>"Louisiana",
                    'ME'=>"Maine",
                    'MD'=>"Maryland",
                    'MA'=>"Massachusetts",
                    'MI'=>"Michigan",
                    'MN'=>"Minnesota",
                    'MS'=>"Mississippi",
                    'MO'=>"Missouri",
                    'MT'=>"Montana",
                    'NE'=>"Nebraska",
                    'NV'=>"Nevada",
                    'NH'=>"New Hampshire",
                    'NJ'=>"New Jersey",
                    'NM'=>"New Mexico",
                    'NY'=>"New York",
                    'NC'=>"North Carolina",
                    'ND'=>"North Dakota",
                    'OH'=>"Ohio",
                    'OK'=>"Oklahoma",
                    'OR'=>"Oregon",
                    'PA'=>"Pennsylvania",
                    'RI'=>"Rhode Island",
                    'SC'=>"South Carolina",
                    'SD'=>"South Dakota",
                    'TN'=>"Tennessee",
                    'TX'=>"Texas",
                    'UT'=>"Utah",
                    'VT'=>"Vermont",
                    'VA'=>"Virginia",
                    'WA'=>"Washington",
                    'WV'=>"West Virginia",
                    'WI'=>"Wisconsin",
                    'WY'=>"Wyoming",
                    'Outside' => "Outside US");

$month_list = array();
 for ($i = 1; $i <= 12; $i++)
                {
                       $month_list[$i] = $i;
                }
array_unshift_assoc($month_list, "", "Month");
                
$year_list = array();
$year = date("Y");
                $final_year = $year + 16;
                for ($i = $year; $i <= $final_year; $i++)
                {
                    $year_list[$i] = $i;
                }
array_unshift_assoc($year_list, "", "Year");




function array_unshift_assoc(&$arr, $key, $val)
                {
                    $arr = array_reverse($arr, true);
                    $arr[$key] = $val;
                    $arr = array_reverse($arr, true);
                    return count($arr);
                }

?>
