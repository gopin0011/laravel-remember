<?php

function merchantCategoryOption()
{
    $options = [];
    $merchant_category = \App\MerchantCategory::whereRaw('parent_id is null OR parent_id = 0')->get();
    foreach ($merchant_category as $v1) {
        $options[$v1->id] = $v1->name;
        $merchant_category1 = \App\MerchantCategory::where("parent_id", $v1->id)->get();
        if (!empty($merchant_category1)) {
            foreach ($merchant_category1 as $v2) {
                $options[$v2->id] = str_repeat("-", 3) . $v2->name;
                $merchant_category2 = \App\MerchantCategory::where("parent_id", $v2->id)->get();
                if (!empty($merchant_category2)) {
                    foreach ($merchant_category2 as $v3) {
                        $options[$v3->id] = str_repeat("-", 6) . $v3->name;
                    }
                }
            }
        }
    }

    return $options;
}

function editValue($table, $select_id)
{
    $param = \Route::current()->parameters;
    $value = null;
    if (!empty($param['one'])) {
        $merchant = \DB::table($table)->find($param['one']);
        if (!empty($merchant)) {
            $value = $merchant->{$select_id};
        }
    }

    return $value;
}

function get($key, $default = "")
{
    // $request = new \Illuminate\Http\Request;    
    if (!empty($_GET[$key])) {
        return ($_GET[$key]);
    } else {
        return $default;
    }
}

function zeroDefault($param)
{
    if (!empty($param) && is_numeric($param)) {
        return $param;
    } else {
        return 0;
    }
}

function slug($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function mediaUrlById($id)
{
    $media = \App\Medias::find($id);
    if (!empty($media)) {
        return $media->url;
    } else {
        return "";
    }
}

function nominal($nominal)
{
    if (isset($nominal)) {
        return number_format((float)$nominal,0,',','.');
    } else {
        return number_format(0,0,',','.');
    }
}

function onlyNumber($string){
  	$output = preg_replace( '/[^0-9]/', '', $string );
    return $output;
}

function groupBy($data, $groupby)
{
    $grouped = [];
    if (!empty($data)) {
        foreach ($data as $v) {
            $grouped[$v->{$groupby}] = $v;
        }
    }
    return $grouped;
}

function weightFactor($weight)
{
    if ($weight < 1000) {
        return 1;
    } else {
        return (int)($weight / 1000);
    }
}

function paginationExplode($data)
{
    $meta = $full = $data->toArray();
    unset($meta["data"]);
    return [
        "meta" => $meta,
        "data" => $full["data"]
    ];
}

function json($data)
{
    return response()->json($data, $data['code']);
}

function noSpace($string)
{
    return str_replace(" ", "", $string);
}

function plainjson($data)
{    
    return response()->json($data['data'], $data['code']);
}

function camelkey($data) {
    $res = [];
    foreach ($data->toArray() as $k => $v) {
        $res[camel_case($k)] = $v;
    }
    return $res;
}

function orderIdGenerator($user)
{
    return $user->id . "456" . date("YmdHism");
}

function defaults($statment_1, $statment_2)
{
    if (!empty($statment_1)) {
        return $statment_1;
    } else {
        return $statment_2;
    }
}

function get_category()
{
    $cat = \App\Categories::get();
    return $cat;
}

function slugify($string)
{
    return \Illuminate\Support\Str::slug($string, "-");
}

function unslug($text)
{
    return ucfirst( str_replace("-", " ", $text) );
}

function six_random()
{
    return sprintf("%06d", mt_rand(1, 999999));
}

function is_login()
{
    if (!empty(\Session::get("login"))) {
        return true;
    } else {
        return false;
    }
}

function is_auth()
{
    if (!empty(\Session::get("user"))) {
        return true;
    } else {
        return false;
    }
}

function get_profile()
{
    $user = \Session::get("user");
    if(!empty($user)){
        return $user;
    }else{
        return [];
    }
}

function is_new($date)
{
    if (strtotime($date) >= strtotime(date("Y-m-d") . " -3 days")) {
        return true;
    } else {
        return false;
    }
}

function uri($path, $default = "")
{
    if (!empty($path)) {
        return (asset($path));
    } else {
        if (!empty($default)) {
            return asset($default);
        } else {
            return null;
        }
    }
}

function set_http_query($key = "", $value = "", $toggle = false)
{
    $query = [];
    $query_string = [];
    if (!empty($_GET)) {
        foreach ($_GET as $k => $v) {
            $query[$k] = $v;
        }
    }

    if (empty($_GET[$key])) {
        $query[$key] = $value;
    }

    if (!empty($_GET[$key])) {
        $query[$key] = $value;
        if ($toggle) {
            if ($query[$key] == $value) {
                unset($query[$key]);
            }
        }
    }

    if ($key != "page") {
        unset($query["page"]);
    }

    foreach ($query as $k => $q) {
        $query_string[] = $k . "=" . $q;
    }

    return "?" . join($query_string, "&");
}

function set_http_query_multi($param = [], $toggle = false)
{
    $query = [];
    $query_string = [];
    if (!empty($_GET)) {
        foreach ($_GET as $k => $v) {
            $query[$k] = $v;
        }
    }

    foreach($param as $k => $v) {
        if (empty($_GET[$k])) {
            $query[$k] = $v;
        }

        if (!empty($_GET[$k])) {
            $query[$k] = $v;
            if ($toggle) {
                if ($query[$k] == $v) {
                    unset($query[$k]);
                }
            }
        }
    }

    if ($key != "page") {
        unset($query["page"]);
    }

    foreach ($query as $k => $q) {
        $query_string[] = $k . "=" . $q;
    }

    return "?" . join($query_string, "&");
}

function cart_count()
{
    return \App\Cart::where("users_id", get_login()->id)->count();
}

function payment_method_list()
{
    $selected_payment_methode = explode(",",env("MIDTRANS_ACTIVE_PAYMENT"));
    $payment_list =  [
        "credit_card" => "Credit Card",
        "cimb_clicks" => "CIMB Click",
        "bca_klikbca" => "Klick BCA",
        "bca_klikpay" => "BCA Klikpay",
        "bri_epay" => "BRI E-Pay",
        "telkomsel_cash" => "Telkomsel Cash",
        "echannel" => "Mandiri E-Channel",
        "permata_va" => "Permata Virtual Account",
        "other_va" => "Bank Lain Virtual Account",
        "bca_va" => "BCA Virtual Account",
        "bni_va" => "BNI Virtual Account",
        "bri_va" => "BRI Virtual Account",
        "indomaret" => "Indomaret Pay",
        "danamon_online" => "Danamon Online",
        "akulaku" => "Akulaku",
        "shopeepay" => "Shopee Pay",
        "gopay" => "Gopay"
    ];

    if (!empty($selected_payment_methode) && $selected_payment_methode[0] != "") {
        $res = [];
        foreach ($payment_list as $k => $v) {
            if (in_array($k, $selected_payment_methode)) {
                $res[$k] = $v;
            }
        }
    } else {
        $res = $payment_list;
    }
    
    return $res;
}

function payment_method_label($key)
{
    $label = payment_method_list();

    return $label[$key];
}

function login_user()
{
    $login = session("login");    
    $user_token = \App\UserToken::where('jwt_token' , $login['id_token'])->first();
    if (!empty($user_token)) {
        $user = \App\User::find($user_token->user_id);
        if (!empty($user)) {
            return $user;
        }
    }

    return null;
}

function mytoken()
{
    $login = session("login");    
    if (!empty($login['id_token'])) {
        return $login['id_token'];
    } else {
        return null;
    }
}

function bulan_str($n)
{
    $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    // $bulan = ["January", "February", "Mart", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return $bulan[($n-1)];
}

function bulan_en_str($n)
{
    // $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    $bulan = ["January", "February", "Mart", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return $bulan[($n-1)];
}


function getFungsiKerja()
{
    return [
        "Sales" => "Sales",
        "Marketing" => "Marketing",
        "Finance" => "Finance",
        "HR" => "HR",
        "Produksi" => "Produksi",
        "IT" => "IT",
        "Legal" => "Legal",
        "Pengusaha" => "Founder/Owner/Pengusaha"
    ];
}

function classSlug($class)
{
    return slugify($class->title . "-" . $class->id);
}

function video_progress($user_id, $class_id)
{
    $journey = \App\Service\ClassJourneyService::detail($user_id, $class_id);
    $list = $journey->video_progress;
    if (!empty($list)) {
        return $list;
    } else {
        return [];
    }
}

function journey($user_id, $class_id)
{
    return \App\Service\ClassJourneyService::detail($user_id, $class_id);
}

function is_viewed($content_id, $viewed_list)
{
    if (in_array($content_id, $viewed_list)) {
        return true;
    } else {
        return false;
    }
}

function paymentmethod()
{
    $selected_payment_methode = explode(",",env("MIDTRANS_ACTIVE_PAYMENT"));
    $payment_method = array(
        [
            'title_method'=>'Kartu Kredit',
            'method'=>array(
                [
                    'img_method'=>'group-58@3x.png',
                    'label_method'=>'Kartu Kredit',
                    'value'=>'credit_card',
                    'inisial'=>'credit_card'
                ]
            )
        ],
        [
            'title_method'=>'Internet Banking',
            'method'=>array(
                [
                    'img_method'=>'group-52@3x-1.png',
                    'label_method'=>'BCA KlikBCA',
                    'value'=>'bca_klikbca',
                    'inisial'=>'bca_klikbca'
                ],
                [
                    'img_method'=>'group-57@3x.png',
                    'label_method'=>'BCA Klikpay',
                    'value'=>'bca_klikpay',
                    'inisial'=>'bca_klikpay'
                ]
            )
        ],
        [
            'title_method'=>'ATM / Virtual Account',
            'method'=>array(
                [
                    'img_method'=>'group-60@3x.png',
                    'label_method'=>'BNI Virtual Account',
                    'value'=>'bni_va',
                    'inisial'=>'bni_va'
                ],
                [
                    'img_method'=>'group-55@3x.png',
                    'label_method'=>'BRI Virtual Account',
                    'value'=>'bri_va',
                    'inisial'=>'bri_va'
                ],
                [
                    'img_method'=>'group-54@3x.png',
                    'label_method'=>'Permata Virtual Account',
                    'value'=>'permata_va',
                    'inisial'=>'permata_va'
                ],
                [
                    'img_method'=>'group-52@3x.png',
                    'label_method'=>'BCA Virtual Account',
                    'value'=>'bca_va',
                    'inisial'=>'bca_va'
                ],
                [
                    'img_method'=>'group-51@3x.png',
                    'label_method'=>'Mandiri Virtual Account',
                    'value'=>'echannel',
                    'inisial'=>'echannel'
                ]
            )
        ],
        [
            'title_method'=>'Electronic Money',
            'method'=>array(
                // [
                //     'img_method'=>'',
                //     'label_method'=>'XL Tunai',
                //     'value'=>'EM01',
                //     'inisial'=>'EM'
                // ],
                // [
                //     'img_method'=>'',
                //     'label_method'=>'Mandiri e-cash',
                //     'value'=>'EM02',
                //     'inisial'=>'EM'
                // ],
                [
                    'img_method'=>'',
                    'label_method'=>'Go-Pay',
                    'value'=>'gopay',
                    'inisial'=>'gopay'
                ],
                [
                    'img_method'=>'',
                    'label_method'=>'BRI E-Pay',
                    'value'=>'bri_epay',
                    'inisial'=>'bri_epay'
                ],
                [
                    'img_method'=>'',
                    'label_method'=>'Telkomsel Cash',
                    'value'=>'telkomsel_cash',
                    'inisial'=>'telkomsel_cash'
                ],
                [
                    'img_method'=>'',
                    'label_method'=>'Shopee Pay',
                    'value'=>'shopeepay',
                    'inisial'=>'shopeepay'
                ],
                [
                    'img_method'=>'',
                    'label_method'=>'Danamon Online',
                    'value'=>'danamon_online',
                    'inisial'=>'danamon_online'
                ],
                // [
                //     'img_method'=>'',
                //     'label_method'=>'Qris',
                //     'value'=>'EM04',
                //     'inisial'=>'EM'
                // ]
            )
        ],
        // [
        //     'title_method'=>'Customer Financing',
        //     'method'=>array(
        //         [
        //             'img_method'=>'group-48@2x.png',
        //             'label_method'=>'Akulaku',
        //             'value'=>'akulaku',
        //             'inisial'=>'akulaku'
        //         ],
        //     )
        // ],
        [
            'title_method'=>'Over the Counter',
            'method'=>array(
                // [
                //     'img_method'=>'group-47@3x.png',
                //     'label_method'=>'Alfamart',
                //     'value'=>'OC01',
                //     'inisial'=>'OC'
                // ],
                [
                    'img_method'=>'group-46@3x.png',
                    'label_method'=>'Indomaret Pay',
                    'value'=>'indomaret',
                    'inisial'=>'indomaret'
                ],
                
                // [
                //     'img_method'=>'group-46@3x.png',
                //     'label_method'=>'Indomaret (Phoenix)',
                //     'value'=>'OC03',
                //     'inisial'=>'OC'
                // ],
                // [
                //     'img_method'=>'group-46@3x.png',
                //     'label_method'=>'Indomaret Enfpoint',
                //     'value'=>'OC04',
                //     'inisial'=>'OC'
                // ],
            )
        ],
    );

    if (!empty($selected_payment_methode) && $selected_payment_methode[0] != "") {
        foreach ($payment_method as $key => $val) {
            foreach ($payment_method[$key]['method'] as $k => $v) {
                if (in_array($payment_method[$key]['method'][$k]['inisial'], $selected_payment_methode)) {
                    $payment_method[$key]['method'][$k]['is_active'] = true;
                } else {
                    $payment_method[$key]['method'][$k]['is_active'] = false;
                }
            }
        }
    } else {
        foreach ($payment_method as $key => $val) {
            foreach ($payment_method[$key]['method'] as $k => $v) {
                $payment_method[$key]['method'][$k]['is_active'] = true;
            }
        }
    }    

    return $payment_method;
}

function tipefile($str)
{
    return substr($str, -3);
}

function validation_message($arr) {
    $html = '';
    if (!empty($arr)) {
        foreach ($arr as $v) {
            $html .= '<small class="text-danger">'.$v.'</small>';
        }
    }

    return $html;
}

function cmp_asc($a, $b)
{
    $a = $a[$GLOBALS['field']];
    $b = $b[$GLOBALS['field']];

    if ($a == $b)
    {
        return 0;
    }

    return ($a < $b) ? -1 : 1;
}

function cmp_desc($a, $b)
{
    $a = $a[$GLOBALS['field']];
    $b = $b[$GLOBALS['field']];

    if ($a == $b)
    {
        return 0;
    }

    return ($a > $b) ? -1 : 1;
}

function sortBy($field, &$array, $direction = 'asc')
{
    $result = false;
    $GLOBALS['field'] = $field;
    if ($direction == 'asc') {
        $result = usort($array, "cmp_asc");
    } else {
        $result = usort($array, "cmp_desc");
    }
    $GLOBALS['field'] = null;    

    return $result;
}

function setting($key, $default = "")
{
    $exist = \Illuminate\Support\Facades\Storage::exists('setting/MAIN_SETTING_CONFIG');
    if ($exist) {
        $config = \Illuminate\Support\Facades\Storage::get('setting/MAIN_SETTING_CONFIG');
        if (empty($config)) {
            load_setting();
            $config = \Illuminate\Support\Facades\Storage::get('setting/MAIN_SETTING_CONFIG');
        }
    } else {
        load_setting();
        $config = \Illuminate\Support\Facades\Storage::get('setting/MAIN_SETTING_CONFIG');
    }

    $config = json_decode($config, true);    
    
    if (!empty($config)) {
        if (is_string($config[$key])) {
            return $config[$key];
        } else {
            return $default;
        }
    } else {
        return $default;
    }
}

function load_setting()
{
    $setting = \App\Settings::all();
    $config = [];
    foreach ($setting as $v) {
        $config[$v->key] = $v->value;
    }

    $config = json_encode($config);

    \Illuminate\Support\Facades\Storage::put('setting/MAIN_SETTING_CONFIG', $config);
}

function toAlphanum($string)
{
    return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
}

function dateformat($datetime, $plus = "")
{
    $datetime = gmt_plus($datetime);
    return date("d F Y", strtotime($datetime . " " . $plus)) . " | " . date("H:i", strtotime($datetime . " " . $plus));
}

function dateformatsimple($datetime)
{
    $datetime = gmt_plus($datetime);
    return date("d M Y", strtotime($datetime));
}

function dateformatsimplenoyear($datetime)
{
    $datetime = gmt_plus($datetime);
    return date("d M", strtotime($datetime));
}

function gmt_plus($datetime, $plus = 0)
{
    if (!empty($plus)) {
        return date("Y-m-d H:i:s", strtotime($datetime . " +$plus hours"));
    } else {
        return date("Y-m-d H:i:s", strtotime($datetime . " +7 hours"));
    }
}

function show_rating($class)
{
    if (empty($class->organic_rating)) {
        $rating =  $class->rating > 3 ? $class->rating : 3.5;
    } else {
        $rating = $class->organic_rating;
    }

    return number_format($rating, 1, ".", ".");
}

function curl_get_contents($url) {
    // Initiate the curl session
    $ch = curl_init();
    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // Removes the headers from the output
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Return the output instead of displaying it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Execute the curl session
    $output = curl_exec($ch);
    // Close the curl session
    curl_close($ch);
    // Return the output as a variable
    return $output;
}

function activelang()
{
    // dd(\Request::segment(1));
    // dd(\Session::get("lang"));
    if (\Request::segment(1) == "en") {
        return "EN";
    } else {
        return "ID";
    }
}

function enroute($routename)
{
    $link = route($routename);
    $base_url = url('');
    if (activelang() == "ID") {
        $base_url_en = url('en');
    }
    return str_replace($base_url, $base_url_en, $link);
}

function idroute($routename)
{
    $link = route($routename);
    $base_url = url('en');
    if (activelang() == "ID") {
        $base_url_en = url('');
    }
    return str_replace($base_url, $base_url_en, $link);
}

function stringlang($eng, $ind)
{
    if (activelang() == "EN") {
        return $eng;
    } else {
        return $ind;
    }
}

function lang($arr)
{
    return $arr[activelang()];
}

function legal_page_list()
{
    $post = \App\Post::where([
        ["lang", activelang()],
        ["tipe", "LEGAL"]
    ])->get();

    return $post;
}

function seohelper($type, $location, $reff = 0)
{
    $param[] = ["type", $type];
    $param[] = ["location", $location];
    if (!empty($reff)) {
        $param[] = ["reff_id", $reff];
    }

    return \App\SeoTag::where($param)->first();
}

function ordefault($target, $default = "")
{
    if (!empty($target)) {
        return $target;
    } else {
        return $default;
    }
}

function obj_count($obj)
{
    if (empty($obj)) {
        return 0;
    }
    
    $count = 0;
    foreach ($obj as $v) {
        $count++;
    }

    return $count;
}

function obj_biggest($obj)
{
    if (empty($obj)) {
        return 0;
    }
    
    $list = [];
    foreach ($obj as $k => $v) {
        $list[] = $k;
    }

    $max = max($list);

    return $$max + 1;
}

function url_to_svg($url)
{
    $extension = substr(uri($url), -3, 3);
    if ($extension == 'svg') {
        $url = 	'data:image/svg+xml;base64,' . base64_encode(@file_get_contents($url));
    } else {
        $url = uri($url);
    }
    return $url;
}

function pre($data)
{
    echo "<pre>"; print_r($data); exit();
}

function group_array($data, $group)
{
    $list = [];
    foreach($data as $v) {
        $list[$v->{$group}][] = $v;
    }
    return $list;
}

function get_youtube_id($url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

function post_tag($post_id)
{
    $meta = \App\PostMeta::where([
        ["post_id", $post_id],
        // ["meta_key", "tag"]
    ])->get()->pluck("meta_value");

    return $meta;
}

function asset_to_webp($source)
{    
    //object
    $asset = \App\AssetCompressMapper::where("md5", md5($source))->first();
    //extension check
    $extension = explode(".", $source);
    if (count($extension) < 2) {
        return $source;
    }

    if (empty($asset)) {
        $target_url = "uploads/compress/" . md5($source) . ".webp";
        $target = __DIR__ . "/../storage/app/" . $target_url;
        $new_file = resize(0, $target, $source);        

        $asset = new \App\AssetCompressMapper;
        $asset->md5 = md5($source);
        $asset->image_source = $source;
        $asset->image_target = $target_url;

        if ($new_file === false) {
            $asset->image_target = $source;
        }

        $asset->save();

        return url($asset->image_target);
    } else {
        return url($asset->image_target);
    }
}

function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    break;
                    
            default: 
                    return false;
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);


    if ($newWidth == 0) {
        $newWidth = $width;
        $newHeight = $height;
    } else {
        $newHeight = ($height / $width) * $newWidth;        
    }

    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagesavealpha($tmp, true);
    imagealphablending($tmp, false);
    $white = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
    imagefill($tmp, 0, 0, $white);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);    
    imagewebp($tmp, $targetFile, 100);

    return true;
}

function remove_attr_tag($string)
{
    return preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $string);
}

function seeinindo()
{
    $full = \Request::fullUrl();
    return str_replace("/en", "" , $full);

}

function seeineng()
{
    $full = \Request::fullUrl();    
    return str_replace(url(""), url("") . "/en", $full);
}

function get_media($id)
{
    $media = \App\Medias::find($id);
    if (!empty($media)) {
        return $media->url;
    } else {
        return url('image/default-pic.jpg');
    }
}

function get_path_media_id($id)
{
    $media = \App\Medias::find($id);
    if (!empty($media)) {
        return $media->url;
    } else {
        return 'image/default-pic.jpg';
    }
}

function get_id_media_path($path)
{
    $media = \App\Medias::where("url", $path)->first();
    if (!empty($media)) {
        return $media->id;
    } else {
        return null;
    }
}

function error_message($key)
{
    $message = "";
    $error_message = \Session::get('error_message');
    foreach($error_message[$key] as $v) {
        $message .= "<div>".$v."</div>";
    }

    return $message;
}

function get_page_by_title($string)
{
    $page = \App\Post::where('title', $string)
        ->where('tipe', 'pages')
        ->orderBy('id', 'desc')
        ->first();
    if (!empty($page)) {
        $page = $page->toArray();
    }
    $page['json_meta'] = json_decode($page['json_meta']);
    return $page;
}

function get_page_by_slug($string)
{
    $page = \App\Post::where('slug', $string)
        ->where('tipe', 'pages')
        ->orderBy('id', 'desc')
        ->first();
    if (!empty($page)) {
        $page = $page->toArray();
    }
    $page = post_formating($page);

    return $page;
}

function get_page_child($post, $lang = "EN")
{
    $page = \App\Post::where('parent_id', $post->id)
        ->where("lang", "EN")
        ->orderBy('id', 'desc')
        ->first();
    if (!empty($page)) {
        $page = $page->toArray();
    }
    $page['json_meta'] = json_decode($page['json_meta']);
    return $page;
}

function footer_data()
{
    return get_page_by_title('Footer');
}

function products_data(){
    $products = \App\Post::select('id','title','slug','featured_image')->where([
        ["tipe", "productsandservices"]
    ])->get();

    return $products;
}

function reformat($data)
{
    $list = [];
    foreach ($data as $v) {
        $list[] = $v;
    }
    return $list;
}

function post_formating($page)
{
    $page['json_meta'] = json_decode($page['json_meta'], true);
    if (!empty($page['json_meta'])) {
        foreach ($page['json_meta'] as $k => $v) {
            if (is_array($v)) {
                $page['json_meta'][$k] = reformat($v);                
            }
        }
    }

    return $page;
}

function encrypt_string($string)
{
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */        

    $secret_key     = env('ENCRYPT_SECRET_KEY');
    $secret_iv      = env('ENCRYPT_SECRET_IV');
    $encrypt_method = env('ENCRYPT_METHOD');
    // hash
    $key    = hash("sha256", $secret_key);
    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv     = substr(hash("sha256", $secret_iv), 0, 16);
    //do the encryption given text/string/number
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);
    return $output;
}

function decrypt_string($string)
{
    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */
    $secret_key     = env('ENCRYPT_SECRET_KEY');
    $secret_iv      = env('ENCRYPT_SECRET_IV');
    $encrypt_method = env('ENCRYPT_METHOD');
    // hash
    $key    = hash("sha256", $secret_key);
    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv = substr(hash("sha256", $secret_iv), 0, 16);
    //do the decryption given text/string/number
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

function count_new_comment($news_id)
{
    $forum = \App\Forums::where("news_id", $news_id)->first();
    if (!empty($forum)) {
        $forum_comment = \App\ForumsComment::selectRaw('count(*) as total')->where("forum_id", $forum->id)->first();
    }
    
    if (!empty($forum_comment)) {
        return $forum_comment->total;
    } else {
        return 0;
    }
}

function removenbsp($text)
{
    return str_replace("&nbsp;", " ", $text);
}

function get_setting($key, $default = "")
{
    $setting = \App\Settings::where("key", $key)->orderBy('id', 'desc')->first();
    if (!empty($setting)) {
        return $setting->value;
    } else {
        return $default;
    }
}

function page_url_by_slug($slug)
{
    $url_list = [
        // 'home'          => route('bolu.index'),
        // 'about'         => route('bolu.our-story'),
        // 'product_bolu'  => route('bolu.bolu-stim'),
        // 'product_lapis' => route('bolu.lapis-stim'),
        // 'store'         => route('bolu.our-store'),
        // 'article'       => route('bolu.article'),
        // 'promo'         => route('bolu.promo'),
    ];

    if (!empty($url_list[$slug])) {
        return $url_list[$slug];
    } else {
        return url("page/" . $slug);
    }
}

function seo_get_data($seo, $type)
{
    $seo = json_decode($seo, true);
    return $seo[$type];
}

function datediffday($start, $end)
{
    $now = strtotime($start);
    $your_date = strtotime($end);
    $datediff = $now - $your_date;
    return ceil($datediff / (60 * 60 * 24));
}

function datediff($date_start, $date_end)
{
    $earlier = new DateTime($date_start);
    $later = new DateTime($date_end);

    $abs_diff = $later->diff($earlier)->format("%a");
    return $abs_diff;
}

function mappingorder($order)
{
    $order['merchant']['json_meta'] = json_decode($order['merchant']['json_meta'], true);
    $order['ordershipping']['merchant_address_manifest'] = json_decode($order['ordershipping']['merchant_address_manifest'], true);
    $order['ordershipping']['buyer_address_manifest'] = json_decode($order['ordershipping']['buyer_address_manifest'], true);
    $order['ordershipping']['price']['request_manifest'] = json_decode($order['ordershipping']['price']['request_manifest'], true);
    $order['ordershipping']['price']['response_manifest'] = json_decode($order['ordershipping']['price']['response_manifest'], true);
    return $order;
}

function save_file_from_url($url)
{
    $name = md5($url);
    $extension = substr($url, -3, 3);
    $product_path = 'uploads/' . date("Y-m") . '/' . $name . '.' . $extension;
    $fullpath = __DIR__ . '/../storage/app/' . $product_path;
    $file = file_get_contents($url);
    file_put_contents($fullpath, $file);
    return $product_path;
}

function full_address_from_address_book($address_book)
{
    $address = "";

    $address .= $address_book->address;
    
    $area = $address_book->area;
    $area_json = json_decode($area->json_manifest);

    if (!empty($area_json) && $area_json->name) {
        $address .= ", " . $area_json->name;
    }
    if (!empty($area_json) && $area_json->suburb->name) {
        $address .= ", " . $area_json->suburb->name;
    }
    if (!empty($area_json) && $area_json->city->name) {
        $address .= ", " . $area_json->city->name;
    }
    if (!empty($area_json) && $area_json->province->name) {
        $address .= ", " . $area_json->province->name;
    }
    if (!empty($area_json) && $area_json->postcode) {
        $address .= ", " . $area_json->postcode;
    }

    return $address;
}

function full_address_from_merchant($merchant)
{
    $address = "";
    
    $area = $merchant->area;
    $area_json = json_decode($area->json_manifest);
    
    $address .= $merchant->merchant_address_primary;
    if (!empty($area_json) && $area_json->name) {
        $address .= ", " . $area_json->name;
    }
    if (!empty($area_json) && $area_json->suburb->name) {
        $address .= ", " . $area_json->suburb->name;
    }
    if (!empty($area_json) && $area_json->city->name) {
        $address .= ", " . $area_json->city->name;
    }
    if (!empty($area_json) && $area_json->province->name) {
        $address .= ", " . $area_json->province->name;
    }
    if (!empty($area_json) && $area_json->postcode) {
        $address .= ", " . $area_json->postcode;
    }

    return $address;
}

function status_order_explain($status)
{
    $explaint = [
        "waiting payment"   => "Menunggu pembayaran dari pembeli",
        "confirmation"      => "Menunggu konfirmasi dari penjual",
        "pickup"            => "Penjual mengeluarkan permintaan penjemputan barang oleh Expedisi",
        "send"              => "Penjual sudah mengirimkan barang",
        "complete"          => "Transaksi sudah selesai, barang sudah diantar",
        "cancle"            => "Transaksi dibatalkan oleh pembeli",
        "cancel"            => "Transaksi dibatalkan oleh pembeli",
        "complain"          => "Pembeli mengajukan komplain",
        "approve"           => "Penjual menerima order, bersiap mengirim barang",
        "reject"            => "Penjual menolak order, dana akan dikirimkan ke wallet buyer",
    ];

    if (!empty($explaint[$status])) {
        return $explaint[$status];
    } else {
        return $status;
    }
}

function invoice_status_desc($status)
{
    $desc = [
        'pending' => "Menunggu pembayaran dari pembeli",
        'expired' => "Transaksi telah kadaluarsa",
        'settlement' => "Transaksi sudah dibayar",
    ];

    if (!empty($desc[$status])) {
        return $desc[$status];
    } else {
        return $status;
    }
}

function category_tree_string($product_id)
{
    $category_tree = \App\ProductCategoriesTree::where("product_id", $product_id)->with("category")->get();
    $category_tree_string = "";
    foreach ($category_tree as $i => $x) {
        $category_tree_string = $x->category->name .  ($i == 0 ? '' : '/') . $category_tree_string;
    }

    return $category_tree_string;
}

function get_user_by_request()
{
    $header = request()->header();
    $token = $request->token;

    if (!empty($header['authorization'][0]) || !empty($token)) {
        if (empty($token)) {
            $token = substr($header['authorization'][0], 7);
        }
        $user_token = \App\UserToken::where('jwt_token' , $token)->first();
        if (!empty($user_token)) {
            $user = \App\User::find($user_token->user_id);    
            return $user;
        }
    }
}

function SET($key, $default = "")
{
    $setting = \App\Settings::where("key", $key)->first();
    if (!empty($setting)) {
        return $setting->value;
    } else {
        return $default;
    }
}


function pricemask($product, $promo)
{
    if (!empty($promo) && !empty($product)) {
        //hitung harga baru
        $nominalpotongan = $promo->potongan_presentase / 100 * $product->base_price;
        if ($nominalpotongan >= $promo->max_potongan) {
            $nominalpotongan = $promo->max_potongan;
        }
        $hargabaru = $product->base_price - $nominalpotongan;
        return $hargabaru;
    } else {
        return $product->publish_price;
    }
}

function post_automated_seo($post, $description = "")
{
    $res['seo_title'] = $post->title;
    if (!empty($description)) {
        $res['seo_description'] = $description;
    } else {
        $res['seo_description'] = substr(strip_tags( base64_decode($post->content) ), 0, 150);
    }
    $res['seo_thumbnail'] = uri($post->featured_image);
    $res['seo_keyword'] = str_replace(" ", ",", $post->title);
    return $res;
}

function get_slug_id($slug)
{
    $explode = explode("-", $slug);
    return $explode[( count($explode) - 1 )];
}

function switchlang($lang)
{
    $urlen = url("en");
    $urlid = url("");
    $full = url()->full();
    $full = str_replace($urlen, "", $full);
    $full = str_replace($urlid, "", $full);
    if ($lang == "en") {
        $full = $urlen . $full;
    } else {
        $full = $urlid . $full;
    }

    return $full;
}

function clearstring($str)
{
    $str = str_replace("_", " ", $str);
    $res = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$str);
    return $res;
}