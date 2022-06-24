<?php

// function to swap crypto currencies

function cryptoSwap($currency1,$currency2){
    // connect to dex
    $dex = new dex();
    // get the current price of the currency
    $price1 = $dex->getPrice($currency1);
    $price2 = $dex->getPrice($currency2);
    // get the current balance of the currency
    $balance1 = $dex->getBalance($currency1);
    $balance2 = $dex->getBalance($currency2);

    // swap currency1 with currency2
    $dex->swap($currency1,$currency2);
    // get the new balance of the currency
    $newBalance1 = $dex->getBalance($currency1);
    $newBalance2 = $dex->getBalance($currency2);
    // get the new price of the currency
    $newPrice1 = $dex->getPrice($currency1);
    $newPrice2 = $dex->getPrice($currency2);

    // update wallet
    $wallet = new wallet();
    $wallet->updateWallet($currency1,$newBalance1);
    $wallet->updateWallet($currency2,$newBalance2);
    // update dex
    $dex->updateDex($currency1,$newPrice1);
    $dex->updateDex($currency2,$newPrice2);
    // update history
    $history = new history();
    $history->updateHistory($currency1,$newBalance1,$newPrice1);
    $history->updateHistory($currency2,$newBalance2,$newPrice2);

    // return the new price of the currency
    return $newPrice1;

}

// class dex
class dex{
    // get the current price of the currency
    public function getPrice($currency){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $price = $data[0]["price_usd"];
        return $price;
    }
    // get the current balance of the currency
    public function getBalance($currency){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $balance = $data[0]["available_supply"];
        return $balance;
    }
    // swap currency1 with currency2
    public function swap($currency1,$currency2){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency1."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $price1 = $data[0]["price_usd"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency2."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $price2 = $data[0]["price_usd"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency1."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $balance1 = $data[0]["available_supply"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency2."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $balance2 = $data[0]["available_supply"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency1."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $newBalance1 = $data[0]["available_supply"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency2."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $newBalance2 = $data[0]["available_supply"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency1."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $newPrice1 = $data[0]["price_usd"];

        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency2."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $newPrice2 = $data[0]["price_usd"];

        $wallet = new wallet();
        $wallet->updateWallet($currency1,$newBalance1);
        $wallet->updateWallet($currency2,$newBalance2);
        $dex = new dex();
        $dex->updateDex($currency1,$newPrice1);
        $dex->updateDex($currency2,$newPrice2);
        $history = new history();
        $history->updateHistory($currency1,$newBalance1,$newPrice1);
        $history->updateHistory($currency2,$newBalance2,$newPrice2);
    }
    // update dex
    public function updateDex($currency,$price){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $data[0]["price_usd"] = $price;
        $json = json_encode($data);
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        file_put_contents($url,$json);
    }
    // update wallet
    public function updateWallet($currency,$balance){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $data[0]["available_supply"] = $balance;
        $json = json_encode($data);
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        file_put_contents($url,$json);
    }
    // update history
    public function updateHistory($currency,$balance,$price){
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        $json = file_get_contents($url);
        $data = json_decode($json,true);
        $data[0]["available_supply"] = $balance;
        $data[0]["price_usd"] = $price;
        $json = json_encode($data);
        $url = "https://api.coinmarketcap.com/v1/ticker/".$currency."/";
        file_put_contents($url,$json);
    }
}
