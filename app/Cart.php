<?php

namespace App;

class Cart
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;
    public $ItemDesc = '';
    private $mer_key = '3i3acNmzuv6p9zJ84vukMZXAthMrUYc2';
    private $mer_iv = '4y54sLkCtK7ZVLbe';

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id) {
        $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->price;
    }

    public function reduceByOne($id) {
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$id]['item']['price'];

        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }

    public function removeItem($id) {
        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }
    function addpadding($string, $blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
    function create_mpg_aes_encrypt ($parameter = "" , $key = "", $iv = "") {
        $return_str = '';
        if (!empty($parameter)) {
            //將參數經過 URL ENCODED QUERY STRING
            $return_str = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt($this->addpadding($return_str), 'aes-256-cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
    }


//    public function getDate($date_now)
//    {
//        $this->$date_now = gmdate("Y-m-d H:i:s",strtotime("+8 hours"));
//        $date_now = strtotime($this->$date_now);
//        return $date_now;
//    }
    public function getTitle()
    {
        $title = array();
        foreach ($this->items as $item){
            $title[] = $item['item']['title'];
        }
        $title = implode("\",\"",$title);
        //dd($title);
        return $title;
    }

    public function getArray()
    {
        $Amt = $this->totalPrice;
        $date_now = gmdate("Y-m-d H:i:s",strtotime("+8 hours"));
        $date_now = strtotime($date_now);
        $trade_info_arr = array(
            'MerchantID' => 'MS155127302',
            'RespondType' => 'JSON',
            'TimeStamp' => $date_now,
            'Version' => 1.4,
            'MerchantOrderNo' => $date_now,
            'Amt' => $Amt,
            'ItemDesc' => $this->getTitle()
        );
        return $trade_info_arr;
    }

    //交易資料經 AES 加密後取得 TradeInfo
    public function trade()
    {
        $TradeInfo = $this->create_mpg_aes_encrypt($this->getArray(),$this->mer_key, $this->mer_iv);
        $sha256 = strtoupper(hash("sha256", "HashKey=$this->mer_key&".$TradeInfo."&HashIV=$this->mer_iv"));

        $AES = array(
            'TradeInfo' => $TradeInfo,
            'sha256'    => $sha256
        );
        return $AES;
    }

//    public function sha($TradeInfo)
//    {
//        $sha256 = strtoupper(hash("sha256", "HashKey=$this->mer_key&".$TradeInfo."&HashIV=$this->mer_iv"));
//        echo $sha256;
//    }
//    public function getKey()
//    {
//        return $this->mer_key;
//    }
//
//    public function getIv()
//    {
//        return $this->mer_iv;
//    }
}
?>