<?php
/**
 * Created by PhpStorm.
 * User: ellermister
 * Date: 2021/9/25
 * Time: 14:57
 */

require '../vendor/autoload.php';
require '../lib/latlng_conv_wgs84_2_gcj02.php';
use GeoIp2\Database\Reader;

$geo_city_file = '../data/GeoLite2-City.mmdb';
$geo_asn_file = '../data/GeoLite2-ASN.mmdb';
if (!is_file($geo_city_file) || !is_file($geo_asn_file)) {
    die('GEOIP 数据不完整');
}

$cityReader = new Reader($geo_city_file);
$asnReader = new Reader($geo_asn_file);

if (isset($_GET['ip'])) {
    $ip = trim($_GET['ip']);
    header('Content-Type: application/json; charset=utf-8');

    $cityRecord = $cityReader->city($ip);
    $asnRecord = $asnReader->asn($ip);

    $asn = isset($asnRecord->autonomousSystemNumber) ? 'AS' . $asnRecord->autonomousSystemNumber : '';//    AS4134

    $country = isset($cityRecord->country->names) ? $cityRecord->country->names['zh-CN'] : '';
    $region = isset($cityRecord->mostSpecificSubdivision->names) ? $cityRecord->mostSpecificSubdivision->names['zh-CN'] : '';
    $city = isset($cityRecord->city->names) ? $cityRecord->city->names['zh-CN'] : '';
    $lat = $cityRecord->location->latitude;
    $lng = $cityRecord->location->longitude;

    $res = latlng_conv_wgs84_2_gcj02::conv($lat, $lng);
    $lat = $res['lat'];
    $lng = $res['lng'];
    $as_organ = $asnRecord->autonomousSystemOrganization;

    $area_str = sprintf("%s\t%s\t%s\t\t%s\t%s\t%s", $country, $region, $city, $as_organ, $lat, $lng);
    $data = [
        'as'           => $asn,
        'area'         => $area_str,// "中国\t广东\t广州\t\t电信\t23.12911\t113.264385"
        'country'      => $country,
        'region'       => $region,
        'city'         => $city,
        'owner_domain' => '',
        'isp_domain'   => '',// chinatelecom.com.cn
        'latitude'     => $lat,
        'longitude'    => $lng,
        'lat'          => $lat,
        'lng'          => $lng,
        'line'         => $as_organ,
    ];
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

//    echo '{"as":"AS4134","area":"中国\t广东\t广州\t\t电信\t23.12911\t113.264385","country":"中国","region":"广东","city":"广州","owner_domain":"","isp_domain":"chinatelecom.com.cn","latitude":"23.12911","longitude":"113.264385","line":"电信"}';
//    die;
}