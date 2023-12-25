<?php

use Illuminate\Database\Eloquent\Collection;

function customUrl($url, $queryParam)
{
    $pattern = "/\?/i";
    $query = "";
    $i = 0;
    $parseUrl = parse_url($url, PHP_URL_QUERY);
    parse_str($parseUrl, $params);
    foreach (collect($queryParam) as $key => $value) {
        if (!isset($params[$key])) {
            if ($i == 0) {
                $hasQuery = preg_match($pattern, $url);
                if ($hasQuery < 1) {
                    $query .= '?' . $key . '=' . $value;
                } else {
                    $query .= '&' . $key . '=' . $value;
                }
            } else {
                $query .= '&' . $key . '=' . $value;
            }
        }
        $i++;
    }
    return $url ? $url . $query : '';
}

function routeActive(string $route)
{
    $arr = explode(',', $route);
    foreach ($arr as $item) {
        if (request()->is("$item")) {
            return true;
        }
    }
    return false;
}

if (!function_exists('json')) {
    function json($arg, $encode = true)
    {
        if ($encode) {
            return json_encode($arg);
        }
        return json_decode($arg);
    }
}

if (!function_exists('toObject')) {
    function toObject($data)
    {
        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            $data = $data->toArray();
        }
        if (is_string($data)) {
            $data = json_decode($data);
        }
        if($data instanceof Collection){
            $data = $data->toArray();
        }
        if(is_object($data)){
            $data = (array)$data;
        }
        if (is_array($data)) {
            $object = new stdClass();
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $string = json_decode($value);
                    if (is_object($string) || is_array($string)) {
                        $value = $string;
                    }
                }
                if (is_array($value)) {
                    $object->$key = toObject($value);
                } else {
                    $object->$key = $value;
                }
            }
            return $object;
        }
        return $data;
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess($data = null, $items = []) {
        $response = array_merge($items, [
            'message' => true,
            'status_code' => 200,
            'error' => null,
            'data' => $data,
        ]);
        return response()->json($response, 200);
    }
}

if (!function_exists('responseError')) {
    function responseError($message = null, $statusCode = 500) {
        return response()->json([
            'message' => false,
            'status_code' => $statusCode,
            'error' => $message,
            'data' => null,
        ], $statusCode);
    }
}

function translateNumber($number)
{
    $km = app()->getLocale() == config('dummy.locale.km');
    $number = (int)$number;
    $number = str_replace(',', '', $number);
    $number = str_replace('.', '', $number);
    $number = str_replace(' ', '', $number);
    $number = str_replace('-', '', $number);
    $number = str_replace('+', '', $number);
    $number = str_replace('(', '', $number);
    $number = str_replace(')', '', $number);
    $number = str_replace('៛', '', $number);
    $number = str_replace('ៗ', '', $number);
    $number = str_replace('។', '', $number);
    $number = str_replace('១', '1', $number);
    $number = str_replace('២', '2', $number);
    $number = str_replace('៣', '3', $number);
    $number = str_replace('៤', '4', $number);
    $number = str_replace('៥', '5', $number);
    $number = str_replace('៦', '6', $number);
    $number = str_replace('៧', '7', $number);
    $number = str_replace('៨', '8', $number);
    $number = str_replace('៩', '9', $number);
    $number = str_replace('០', '0', $number);

    if ($km) {
        $number = str_replace('1', '១', $number);
        $number = str_replace('2', '២', $number);
        $number = str_replace('3', '៣', $number);
        $number = str_replace('4', '៤', $number);
        $number = str_replace('5', '៥', $number);
        $number = str_replace('6', '៦', $number);
        $number = str_replace('7', '៧', $number);
        $number = str_replace('8', '៨', $number);
        $number = str_replace('9', '៩', $number);
        $number = str_replace('0', '០', $number);
    }

    return $number;
}

function getDayNumberSuffix($dayNumber)
{
    $km = app()->getLocale() == config('dummy.locale.km');
    if (!in_array(($dayNumber % 100), array(11, 12, 13))) {
        switch ($dayNumber % 10) {
            case 1:
                return $km ? 'ទី១' : 'st';
            case 2:
                return $km ? 'ទី២' : 'nd';
            case 3:
                return $km ? 'ទី៣' : 'rd';
        }
    }
    return $km ? 'ទី' : 'th';
}

function translateDay($day)
{
    $day = strtolower($day);
    $km = app()->getLocale() == config('dummy.locale.km');

    switch ($day) {
        case 'monday':
            return $km ? 'ច័ន្ទ' : 'Monday';
        case 'tuesday':
            return $km ? 'អង្គារ' : 'Tuesday';
        case 'wednesday':
            return $km ? 'ពុធ' : 'Wednesday';
        case 'thursday':
            return $km ? 'ព្រហស្បតិ៍' : 'Thursday';
        case 'friday':
            return $km ? 'សុក្រ' : 'Friday';
        case 'saturday':
            return $km ? 'សៅរ៍' : 'Saturday';
        case 'sunday':
            return $km ? 'អាទិត្យ' : 'Sunday';
        default:
            return $day;
    }
}

function translateMonth($month)
{
    $month = strtolower($month);
    $km = app()->getLocale() == config('dummy.locale.km');

    switch ($month) {
        case 'january':
            return $km ? 'មករា' : 'January';
        case 'february':
            return $km ? 'កុម្ភៈ' : 'February';
        case 'march':
            return $km ? 'មីនា' : 'March';
        case 'april':
            return $km ? 'មេសា' : 'April';
        case 'may':
            return $km ? 'ឧសភា' : 'May';
        case 'june':
            return $km ? 'មិថុនា' : 'June';
        case 'july':
            return $km ? 'កក្កដា' : 'July';
        case 'august':
            return $km ? 'សីហា' : 'August';
        case 'september':
            return $km ? 'កញ្ញា' : 'September';
        case 'october':
            return $km ? 'តុលា' : 'October';
        case 'november':
            return $km ? 'វិច្ឆិកា' : 'November';
        case 'december':
            return $km ? 'ធ្នូ' : 'December';
        default:
            return $month;
    }
}

if (!function_exists('translateDate')) {
    function translateDate($date){
        $date = date_create($date);
        $day = date_format($date, 'l');
        $dayNumber = date_format($date, 'd');
        $month = date_format($date, 'F');
        $year = date_format($date, 'Y');

        $dayNumberSuffix = getDayNumberSuffix($dayNumber);
        $dayNumber = translateNumber($dayNumber);
        $day = translateDay($day);
        $month = translateMonth($month);
        $year = translateNumber($year);

        $km = app()->getLocale() == config('dummy.locale.km');
        return $km ? "ថ្ងៃ$day ទី$dayNumber ខែ$month ឆ្នាំ$year" : "$day, $dayNumber$dayNumberSuffix $month $year";
    }
}

if (!function_exists('videoUrl')) {
    function videoUrl($url) {
        // if url is match with youtube url pattern and return video id
        $regExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';

        // if url is match with youtube embed iframe pattern and return video id
        $regYoutubeEmbed = '/<iframe.*?src="(.*?)"[^>]*><\/iframe>/';

        // var match = url.match(regExp);
        $match = preg_match($regExp, $url, $matches);
        $result = '';

        if ($match && strlen($matches[2]) == 11) {
            $result = $matches[2];
        } else if (preg_match($regYoutubeEmbed, $url, $matches)) {
            $result = $matches[1];
        } else {
            $result = $url;
        }

        return $result;
    }
}

if (!function_exists('previewVideo')) {
    function previewVideo($url) {
        $result = videoUrl($url);

        // if result is not youtube video id
        if (strlen($result) != 11) {
            return 'https://www.facebook.com/plugins/video.php?height=315&href=' . $result . '&show_text=false&width=560&t=0';
        }

        return 'https://www.youtube.com/embed/' . $result;
    }
}

if (!function_exists('formatViewerNumber')) {
    function formatViewerNumber($number)
    {
        $number = (0 + str_replace(",", "", $number));
        if (!is_numeric($number)) {
            return false;
        }

        if ($number > 1000000000000) {
            return round(($number / 1000000000000), 2) . 'T';
        } else if ($number > 1000000000) {
            return round(($number / 1000000000), 2) . 'B';
        } else if ($number > 1000000) {
            return round(($number / 1000000), 2) . 'M';
        } else if ($number > 1000) {
            return round(($number / 1000), 2) . 'K';
        }
        return number_format($number);
    }
}
