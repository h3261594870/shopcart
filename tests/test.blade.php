<?php
$money='1600';
$date_now = gmdate("Y-m-d H:i:s",strtotime("+8 hours"));
$date_now = strtotime($date_now);
$ItemDesc = '圖書';
$Email = 'h326159487000@gmail.com';
$trade_info_arr = array(
    'MerchantID' => 'MS155127302',
    'RespondType' => 'JSON',
    'TimeStamp' => $date_now,
    'Version' => 1.4,
    'MerchantOrderNo' => $date_now,
    'Amt' => $money,
    'ItemDesc' => '圖書'
);
$mer_key = '3i3acNmzuv6p9zJ84vukMZXAthMrUYc2';
$mer_iv = '4y54sLkCtK7ZVLbe';
//交易資料經 AES 加密後取得 TradeInfo
$TradeInfo = create_mpg_aes_encrypt($trade_info_arr, $mer_key, $mer_iv);

$sha256 = strtoupper(hash("sha256", "HashKey=$mer_key&".$TradeInfo."&HashIV=$mer_iv"));
echo $date_now;
?>



<form name='Spgateway' method='post' action='https://core.spgateway.com/MPG/mpg_gateway'>
<input type='hidden' id="MerchantID" name='MerchantID' value='MS155127302'><br>
    <input type='hidden' id="TradeInfo" name='TradeInfo' value='<?php echo $TradeInfo?>'><br>
    <input type='hidden' id="TradeSha" name='TradeSha' value='<?php echo $sha256 ?>'><br>
    <input type='hidden' id="Version" name='Version' value='1.4'><br>
    <input type='hidden' id="RespondType" name='RespondType' value='JSON'><br>
    <input type='hidden' id="TimeStamp" name='TimeStamp' value='<?php echo $date_now?>'><br>
    <input type='hidden' id="MerchantOrderNo" name='MerchantOrderNo' value="<?php echo$date_now?>"><br>
    <input type='hidden' id="Amt" name='Amt' value='<?php echo $money?>'><br>
    <input type='hidden' id="ItemDesc" name='ItemDesc' value='<?php echo $ItemDesc?>'><br>
    <input type='hidden' id="Email" name='Email' value='<?php echo $Email?>'><br>
    <input type='hidden' id="LoginType" name='LoginType' value='0'><br>

    <input type='submit' value='Submit'></form>

<?php
function create_mpg_aes_encrypt ($parameter = "" , $key = "", $iv = "") {
    $return_str = '';
    if (!empty($parameter)) {
        //將參數經過 URL ENCODED QUERY STRING
        $return_str = http_build_query($parameter);
    }
    return trim(bin2hex(openssl_encrypt(addpadding($return_str), 'aes-256-cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
}
function addpadding($string, $blocksize = 32) {
    $len = strlen($string);
    $pad = $blocksize - ($len % $blocksize);
    $string .= str_repeat(chr($pad), $pad);
    return $string;
}
?>
