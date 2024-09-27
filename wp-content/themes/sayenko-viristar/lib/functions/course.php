<?php

function viristar_get_course_name($product_id) {
	
}

function vs_get_course_category( string $value = null, $course ) {

	if(!is_object($course)) {
		return false;
	}

	if(! in_array($value, ['term_id', 'name', 'slug']) ) {
		return false;
	}

	
}


function vs_course_date( $post_id )
{

	$date = 'No Date';

	if(empty($post_id)) {
		$post_id = get_the_ID();
	}

	$start = get_field('start_date', $post_id );
	$end = get_field('end_date', $post_id );

	if( empty($start) || empty($end)) {
		return $date;
	}



	$custom_course_date = '';

	$start = strtotime( $start );
	$end = strtotime( $end );

	// Only a start date
	if (empty($end)) {
		$date = date('j F, Y', $start);
	}

	// Same day
	elseif (date('F j', $start) == date('F j', $end)) {
		$date = date('j F, Y', $start);
	}

	// Same Month
	elseif (date('F', $start) == date('F', $end)) {
		$date = date('j F', $start) . ' - ' . date('j F, Y', $end);
	}

	// Same Year
	elseif (date('Y', $start) == date('Y', $end)) {
		$date = date('j F', $start) . ' - ' . date('j F, Y', $end);
	}

	// Any other dates
	else {
		$date = date('j F, Y', $start) . ' - ' . date('j F, Y', $end);
	}

	$date = $custom_course_date ? $custom_course_date : $date;

	return $date;
}


function vs_course_month_year( $post_id )
{

	$date = 'No Date';
	
	if(empty($post_id)) {
		$post_id = get_the_ID();
	}

	$start = get_field('start_date', $post_id );
	$end = get_field('end_date', $post_id );

	if( empty($start) || empty($end)) {
		return $date;
	}

	
	$custom_course_date = '';

	$start = strtotime( $start );
	$end = strtotime( $end );

	if (date('Y', $start) == date('Y', $end)) {
		$date = date('F Y', $end);
	}

	// Any other dates
	else {
		$date = date('F Y', $start) . ' - ' . date('F Y', $end);
	}

	$date = $custom_course_date ? $custom_course_date : $date;

	return $date;
}

function vs_course_start_date( $post_id )
{

	$date = 'No Date';
	
	if(empty($post_id)) {
		$post_id = get_the_ID();
	}

	$start = get_field('start_date', $post_id );


	$start = get_field('start_date', $post_id );

	if( empty($start) || empty($end)) {
		return $date;
	}


	$custom_course_date = '';

	$start = strtotime( $start );

	$date = date('j F, Y', $start);
	$date = $custom_course_date ? $custom_course_date : $date;

	return $date;
}



/**
 * Get the country name from the country code.
 *
 * @param string $country_code A country code.
 * @return string The country name.
 */
function vs_getcountry_name($country_code)
{
	$country_code = strtoupper($country_code);
	$country = '';
	if ($country_code == 'AF') $country = 'Afghanistan';
	if ($country_code == 'AX') $country = 'Aland Islands';
	if ($country_code == 'AL') $country = 'Albania';
	if ($country_code == 'DZ') $country = 'Algeria';
	if ($country_code == 'AS') $country = 'American Samoa';
	if ($country_code == 'AD') $country = 'Andorra';
	if ($country_code == 'AO') $country = 'Angola';
	if ($country_code == 'AI') $country = 'Anguilla';
	if ($country_code == 'AQ') $country = 'Antarctica';
	if ($country_code == 'AG') $country = 'Antigua and Barbuda';
	if ($country_code == 'AR') $country = 'Argentina';
	if ($country_code == 'AM') $country = 'Armenia';
	if ($country_code == 'AW') $country = 'Aruba';
	if ($country_code == 'AU') $country = 'Australia';
	if ($country_code == 'AT') $country = 'Austria';
	if ($country_code == 'AZ') $country = 'Azerbaijan';
	if ($country_code == 'BS') $country = 'Bahamas the';
	if ($country_code == 'BH') $country = 'Bahrain';
	if ($country_code == 'BD') $country = 'Bangladesh';
	if ($country_code == 'BB') $country = 'Barbados';
	if ($country_code == 'BY') $country = 'Belarus';
	if ($country_code == 'BE') $country = 'Belgium';
	if ($country_code == 'BZ') $country = 'Belize';
	if ($country_code == 'BJ') $country = 'Benin';
	if ($country_code == 'BM') $country = 'Bermuda';
	if ($country_code == 'BT') $country = 'Bhutan';
	if ($country_code == 'BO') $country = 'Bolivia';
	if ($country_code == 'BA') $country = 'Bosnia and Herzegovina';
	if ($country_code == 'BW') $country = 'Botswana';
	if ($country_code == 'BV') $country = 'Bouvet Island (Bouvetoya)';
	if ($country_code == 'BR') $country = 'Brazil';
	if ($country_code == 'IO') $country = 'British Indian Ocean Territory (Chagos Archipelago)';
	if ($country_code == 'VG') $country = 'British Virgin Islands';
	if ($country_code == 'BN') $country = 'Brunei Darussalam';
	if ($country_code == 'BG') $country = 'Bulgaria';
	if ($country_code == 'BF') $country = 'Burkina Faso';
	if ($country_code == 'BI') $country = 'Burundi';
	if ($country_code == 'KH') $country = 'Cambodia';
	if ($country_code == 'CM') $country = 'Cameroon';
	if ($country_code == 'CA') $country = 'Canada';
	if ($country_code == 'CV') $country = 'Cape Verde';
	if ($country_code == 'KY') $country = 'Cayman Islands';
	if ($country_code == 'CF') $country = 'Central African Republic';
	if ($country_code == 'TD') $country = 'Chad';
	if ($country_code == 'CL') $country = 'Chile';
	if ($country_code == 'CN') $country = 'China';
	if ($country_code == 'CX') $country = 'Christmas Island';
	if ($country_code == 'CC') $country = 'Cocos (Keeling) Islands';
	if ($country_code == 'CO') $country = 'Colombia';
	if ($country_code == 'KM') $country = 'Comoros the';
	if ($country_code == 'CD') $country = 'Congo';
	if ($country_code == 'CG') $country = 'Congo the';
	if ($country_code == 'CK') $country = 'Cook Islands';
	if ($country_code == 'CR') $country = 'Costa Rica';
	if ($country_code == 'CI') $country = "Cote d\\`Ivoire";
	if ($country_code == 'HR') $country = 'Croatia';
	if ($country_code == 'CU') $country = 'Cuba';
	if ($country_code == 'CY') $country = 'Cyprus';
	if ($country_code == 'CZ') $country = 'Czech Republic';
	if ($country_code == 'DK') $country = 'Denmark';
	if ($country_code == 'DJ') $country = 'Djibouti';
	if ($country_code == 'DM') $country = 'Dominica';
	if ($country_code == 'DO') $country = 'Dominican Republic';
	if ($country_code == 'EC') $country = 'Ecuador';
	if ($country_code == 'EG') $country = 'Egypt';
	if ($country_code == 'SV') $country = 'El Salvador';
	if ($country_code == 'GQ') $country = 'Equatorial Guinea';
	if ($country_code == 'ER') $country = 'Eritrea';
	if ($country_code == 'EE') $country = 'Estonia';
	if ($country_code == 'ET') $country = 'Ethiopia';
	if ($country_code == 'FO') $country = 'Faroe Islands';
	if ($country_code == 'FK') $country = 'Falkland Islands (Malvinas)';
	if ($country_code == 'FJ') $country = 'Fiji the Fiji Islands';
	if ($country_code == 'FI') $country = 'Finland';
	if ($country_code == 'FR') $country = 'France, French Republic';
	if ($country_code == 'GF') $country = 'French Guiana';
	if ($country_code == 'PF') $country = 'French Polynesia';
	if ($country_code == 'TF') $country = 'French Southern Territories';
	if ($country_code == 'GA') $country = 'Gabon';
	if ($country_code == 'GM') $country = 'Gambia the';
	if ($country_code == 'GE') $country = 'Georgia';
	if ($country_code == 'DE') $country = 'Germany';
	if ($country_code == 'GH') $country = 'Ghana';
	if ($country_code == 'GI') $country = 'Gibraltar';
	if ($country_code == 'GR') $country = 'Greece';
	if ($country_code == 'GL') $country = 'Greenland';
	if ($country_code == 'GD') $country = 'Grenada';
	if ($country_code == 'GP') $country = 'Guadeloupe';
	if ($country_code == 'GU') $country = 'Guam';
	if ($country_code == 'GT') $country = 'Guatemala';
	if ($country_code == 'GG') $country = 'Guernsey';
	if ($country_code == 'GN') $country = 'Guinea';
	if ($country_code == 'GW') $country = 'Guinea-Bissau';
	if ($country_code == 'GY') $country = 'Guyana';
	if ($country_code == 'HT') $country = 'Haiti';
	if ($country_code == 'HM') $country = 'Heard Island and McDonald Islands';
	if ($country_code == 'VA') $country = 'Holy See (Vatican City State)';
	if ($country_code == 'HN') $country = 'Honduras';
	if ($country_code == 'HK') $country = 'Hong Kong';
	if ($country_code == 'HU') $country = 'Hungary';
	if ($country_code == 'IS') $country = 'Iceland';
	if ($country_code == 'IN') $country = 'India';
	if ($country_code == 'ID') $country = 'Indonesia';
	if ($country_code == 'IR') $country = 'Iran';
	if ($country_code == 'IQ') $country = 'Iraq';
	if ($country_code == 'IE') $country = 'Ireland';
	if ($country_code == 'IM') $country = 'Isle of Man';
	if ($country_code == 'IL') $country = 'Israel';
	if ($country_code == 'IT') $country = 'Italy';
	if ($country_code == 'JM') $country = 'Jamaica';
	if ($country_code == 'JP') $country = 'Japan';
	if ($country_code == 'JE') $country = 'Jersey';
	if ($country_code == 'JO') $country = 'Jordan';
	if ($country_code == 'KZ') $country = 'Kazakhstan';
	if ($country_code == 'KE') $country = 'Kenya';
	if ($country_code == 'KI') $country = 'Kiribati';
	if ($country_code == 'KP') $country = 'Korea';
	if ($country_code == 'KR') $country = 'Korea';
	if ($country_code == 'KW') $country = 'Kuwait';
	if ($country_code == 'KG') $country = 'Kyrgyz Republic';
	if ($country_code == 'LA') $country = 'Lao';
	if ($country_code == 'LV') $country = 'Latvia';
	if ($country_code == 'LB') $country = 'Lebanon';
	if ($country_code == 'LS') $country = 'Lesotho';
	if ($country_code == 'LR') $country = 'Liberia';
	if ($country_code == 'LY') $country = 'Libyan Arab Jamahiriya';
	if ($country_code == 'LI') $country = 'Liechtenstein';
	if ($country_code == 'LT') $country = 'Lithuania';
	if ($country_code == 'LU') $country = 'Luxembourg';
	if ($country_code == 'MO') $country = 'Macao';
	if ($country_code == 'MK') $country = 'Macedonia';
	if ($country_code == 'MG') $country = 'Madagascar';
	if ($country_code == 'MW') $country = 'Malawi';
	if ($country_code == 'MY') $country = 'Malaysia';
	if ($country_code == 'MV') $country = 'Maldives';
	if ($country_code == 'ML') $country = 'Mali';
	if ($country_code == 'MT') $country = 'Malta';
	if ($country_code == 'MH') $country = 'Marshall Islands';
	if ($country_code == 'MQ') $country = 'Martinique';
	if ($country_code == 'MR') $country = 'Mauritania';
	if ($country_code == 'MU') $country = 'Mauritius';
	if ($country_code == 'YT') $country = 'Mayotte';
	if ($country_code == 'MX') $country = 'Mexico';
	if ($country_code == 'FM') $country = 'Micronesia';
	if ($country_code == 'MD') $country = 'Moldova';
	if ($country_code == 'MC') $country = 'Monaco';
	if ($country_code == 'MN') $country = 'Mongolia';
	if ($country_code == 'ME') $country = 'Montenegro';
	if ($country_code == 'MS') $country = 'Montserrat';
	if ($country_code == 'MA') $country = 'Morocco';
	if ($country_code == 'MZ') $country = 'Mozambique';
	if ($country_code == 'MM') $country = 'Myanmar';
	if ($country_code == 'NA') $country = 'Namibia';
	if ($country_code == 'NR') $country = 'Nauru';
	if ($country_code == 'NP') $country = 'Nepal';
	if ($country_code == 'AN') $country = 'Netherlands Antilles';
	if ($country_code == 'NL') $country = 'Netherlands the';
	if ($country_code == 'NC') $country = 'New Caledonia';
	if ($country_code == 'NZ') $country = 'New Zealand';
	if ($country_code == 'NI') $country = 'Nicaragua';
	if ($country_code == 'NE') $country = 'Niger';
	if ($country_code == 'NG') $country = 'Nigeria';
	if ($country_code == 'NU') $country = 'Niue';
	if ($country_code == 'NF') $country = 'Norfolk Island';
	if ($country_code == 'MP') $country = 'Northern Mariana Islands';
	if ($country_code == 'NO') $country = 'Norway';
	if ($country_code == 'OM') $country = 'Oman';
	if ($country_code == 'PK') $country = 'Pakistan';
	if ($country_code == 'PW') $country = 'Palau';
	if ($country_code == 'PS') $country = 'Palestinian Territory';
	if ($country_code == 'PA') $country = 'Panama';
	if ($country_code == 'PG') $country = 'Papua New Guinea';
	if ($country_code == 'PY') $country = 'Paraguay';
	if ($country_code == 'PE') $country = 'Peru';
	if ($country_code == 'PH') $country = 'Philippines';
	if ($country_code == 'PN') $country = 'Pitcairn Islands';
	if ($country_code == 'PL') $country = 'Poland';
	if ($country_code == 'PT') $country = 'Portugal, Portuguese Republic';
	if ($country_code == 'PR') $country = 'Puerto Rico';
	if ($country_code == 'QA') $country = 'Qatar';
	if ($country_code == 'RE') $country = 'Reunion';
	if ($country_code == 'RO') $country = 'Romania';
	if ($country_code == 'RU') $country = 'Russian Federation';
	if ($country_code == 'RW') $country = 'Rwanda';
	if ($country_code == 'BL') $country = 'Saint Barthelemy';
	if ($country_code == 'SH') $country = 'Saint Helena';
	if ($country_code == 'KN') $country = 'Saint Kitts and Nevis';
	if ($country_code == 'LC') $country = 'Saint Lucia';
	if ($country_code == 'MF') $country = 'Saint Martin';
	if ($country_code == 'PM') $country = 'Saint Pierre and Miquelon';
	if ($country_code == 'VC') $country = 'Saint Vincent and the Grenadines';
	if ($country_code == 'WS') $country = 'Samoa';
	if ($country_code == 'SM') $country = 'San Marino';
	if ($country_code == 'ST') $country = 'Sao Tome and Principe';
	if ($country_code == 'SA') $country = 'Saudi Arabia';
	if ($country_code == 'SN') $country = 'Senegal';
	if ($country_code == 'RS') $country = 'Serbia';
	if ($country_code == 'SC') $country = 'Seychelles';
	if ($country_code == 'SL') $country = 'Sierra Leone';
	if ($country_code == 'SG') $country = 'Singapore';
	if ($country_code == 'SK') $country = 'Slovakia (Slovak Republic)';
	if ($country_code == 'SI') $country = 'Slovenia';
	if ($country_code == 'SB') $country = 'Solomon Islands';
	if ($country_code == 'SO') $country = 'Somalia, Somali Republic';
	if ($country_code == 'ZA') $country = 'South Africa';
	if ($country_code == 'GS') $country = 'South Georgia and the South Sandwich Islands';
	if ($country_code == 'ES') $country = 'Spain';
	if ($country_code == 'LK') $country = 'Sri Lanka';
	if ($country_code == 'SD') $country = 'Sudan';
	if ($country_code == 'SR') $country = 'Suriname';
	if ($country_code == 'SJ') $country = 'Svalbard & Jan Mayen Islands';
	if ($country_code == 'SZ') $country = 'Swaziland';
	if ($country_code == 'SE') $country = 'Sweden';
	if ($country_code == 'CH') $country = 'Switzerland, Swiss Confederation';
	if ($country_code == 'SY') $country = 'Syrian Arab Republic';
	if ($country_code == 'TW') $country = 'Taiwan';
	if ($country_code == 'TJ') $country = 'Tajikistan';
	if ($country_code == 'TZ') $country = 'Tanzania';
	if ($country_code == 'TH') $country = 'Thailand';
	if ($country_code == 'TL') $country = 'Timor-Leste';
	if ($country_code == 'TG') $country = 'Togo';
	if ($country_code == 'TK') $country = 'Tokelau';
	if ($country_code == 'TO') $country = 'Tonga';
	if ($country_code == 'TT') $country = 'Trinidad and Tobago';
	if ($country_code == 'TN') $country = 'Tunisia';
	if ($country_code == 'TR') $country = 'Turkey';
	if ($country_code == 'TM') $country = 'Turkmenistan';
	if ($country_code == 'TC') $country = 'Turks and Caicos Islands';
	if ($country_code == 'TV') $country = 'Tuvalu';
	if ($country_code == 'UG') $country = 'Uganda';
	if ($country_code == 'UA') $country = 'Ukraine';
	if ($country_code == 'AE') $country = 'United Arab Emirates';
	if ($country_code == 'GB') $country = 'United Kingdom';
	if ($country_code == 'US') $country = 'United States of America';
	if ($country_code == 'UM') $country = 'United States Minor Outlying Islands';
	if ($country_code == 'VI') $country = 'United States Virgin Islands';
	if ($country_code == 'UY') $country = 'Uruguay, Eastern Republic of';
	if ($country_code == 'UZ') $country = 'Uzbekistan';
	if ($country_code == 'VU') $country = 'Vanuatu';
	if ($country_code == 'VE') $country = 'Venezuela';
	if ($country_code == 'VN') $country = 'Vietnam';
	if ($country_code == 'WF') $country = 'Wallis and Futuna';
	if ($country_code == 'EH') $country = 'Western Sahara';
	if ($country_code == 'YE') $country = 'Yemen';
	if ($country_code == 'ZM') $country = 'Zambia';
	if ($country_code == 'ZW') $country = 'Zimbabwe';
	if ($country == '') $country = $country_code;
	return $country;
}


/**
 * Get the timezone offset from UTC.
 *
 * @param string $timezone A timezone string.
 * @return string The timezone offset.
 */
function vs_get_timezone_offset($timezone)
{
	//$timezone = 'Pacific/Nauru';
	$time = new \DateTime('now', new DateTimeZone($timezone));
	$timezoneOffset = $time->format('P');
	//echo $timezoneOffset;
	if ($timezoneOffset == "+00:00") {
		return "UTC";
	} else {
		$nt = str_replace(":00", "", $timezoneOffset);
		$nt = str_replace("+0", "+", $nt);
		$nt = str_replace("-0", "-", $nt);
		return "UTC" . $nt;
	}
}