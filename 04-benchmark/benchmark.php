<?php

/**
 * PHP Script to 04-benchmark PHP
 *
 * inspired by / thanks to:
 * - www.php-04-benchmark-script.com  (Alessandro Torrisi)
 * - www.webdesign-informatik.de
 *
 * @author @hm
 * @license MIT
 */
// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------
set_time_limit(60); // 1 minuto // 2 minutos para pruebas remotas

$options = array();

// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance

$benchmarkResult = test_benchmark($options);

// html output
echo "<!DOCTYPE html>\n<html lang='es'><head>\n";

echo "<style>
    table {
        color: #333; /* Lighten up font color */
        font-family: Helvetica, Arial, sans-serif; /* Nicer font */
        /*width: 640px;*/
        border-collapse:
        collapse; border-spacing: 0;
    }

    td, th {
        border: 1px solid #CCC; height: 30px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
    }
    </style>
    </head>
    <body>";

echo array_to_html($benchmarkResult).'<p>';

echo "<h3>URL desde la que se ejecuta el script</h3>".dameURL();

//echo "<p><iframe src='https://whois.domaintools.com/solired.es' width='800px' height='600px'</iframe>";

//Search whois

echo '<p>Dame IP'.$_SERVER['SERVER_ADDR'].'<p>';
echo '<form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.dameURL().'">'; 
echo '
                
                <button type="submit">Geolocalizar Servidor x URL
                  </button>
                
                <input type="hidden" value="whois" name="service">
             
            </form>';
    
echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/go/'.$_SERVER['SERVER_ADDR'].'">'; 
echo '
                
                <button type="submit">Geolocalizar Servidor x IP
                  </button>
                
                <input type="hidden" value="whois" name="service">
             
            </form>';

echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">'; 
echo '
                
                <button type="submit">Geolocalizar Servidor x IP (2)
                  </button>
                
                <input type="hidden" value="whois" name="service">
             
            </form>';

echo '<p><form role="form" method="GET" target="_blank" action="https://whois.domaintools.com/'.$_SERVER['SERVER_ADDR'].'">'; 
echo '
                
                <button type="submit">Geolocalizar Servidor x IP (3)
                  </button>
                
               
             
            </form>';

echo "<p>
<iframe src='https://whois.domaintools.com/".$_SERVER['SERVER_ADDR']." width='800px' height='600px'</iframe><p>";

   

echo "\n</body></html>";
exit;

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

//Geo-Posicionamiento Server


function dameURL(){
	$url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	return $url;
    
}

function test_benchmark($settings)
{
    $timeStart = microtime(true);

    $result = array();
    $result[''] = 'Benchmark script version 1.4';
    $result['sysinfo']['Fecha & Hora'] = date("d-M-Y //  H:i:s");
    $result['sysinfo']['Version de php'] = PHP_VERSION;
    $result['sysinfo']['Sistema operativo Servidor'] = PHP_OS;
    $result['sysinfo']['Nombre del servidor'] = $_SERVER['SERVER_NAME'];
    $result['sysinfo']['Ip del servidor'] = $_SERVER['SERVER_ADDR'];
	$result['sysinfo']['Geo Servidor'] = $_SERVER['HTTP_HOST'];


    test_math($result);
    test_string($result);
    test_loops($result);
    test_ifelse($result);
    if (isset($settings['db.host'])) {
        test_mysql($result, $settings);
    }

    $result['total'] = timer_diff($timeStart);
    return $result;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
        }
    }
    $result['04-benchmark']['math'] = timer_diff($timeStart);
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'el perro de San Roque no tiene rabo';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    $result['04-benchmark']['string'] = timer_diff($timeStart);
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {

    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    $result['04-benchmark']['loops'] = timer_diff($timeStart);
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {

        } elseif ($i == -2) {

        } else if ($i == -3) {

        }
    }
    $result['04-benchmark']['if-else'] = timer_diff($timeStart);
}


function test_mysql(&$result, $settings)
{
    $timeStart = microtime(true);

    $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
    $result['04-benchmark']['mysql']['connect'] = timer_diff($timeStart);
	//Descomentar si se quiere el test mysql
   // $arr_return['sysinfo']['Version de mysql (mysql_version)'] = '';

    mysqli_select_db($link, $settings['db.name']);
    $result['04-benchmark']['mysql']['select_db'] = timer_diff($timeStart);

    $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
    $arr_row = mysqli_fetch_array($dbResult);
    $result['sysinfo']['mysql_version'] = $arr_row['version'];
    $result['04-benchmark']['mysql']['query_version'] = timer_diff($timeStart);

    $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
    $dbResult = mysqli_query($link, $query);
    $result['04-benchmark']['mysql']['query_benchmark'] = timer_diff($timeStart);

    mysqli_close($link);

    $result['04-benchmark']['mysql']['total'] = timer_diff($timeStart);
    return $result;
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 3);
}

function array_to_html($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<table>';
        foreach ($array as $k => $v) {
            $result .= "\n<tr><td>";
            $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $result .= array_to_html($v);
            $result .= "</td></tr>";
        }
        $result .= "\n</table>";
    } else {
        $result = htmlentities($array);
    }
    return $result;
}

 

