管理画面側は、簡易的なトークン認証で作る。
参考：https://www.webopixel.net/php/1343.html

認証されていない場合の処理は下記クラスで行われる。
App\Exceptions\Handler
※他の場所に書いたほうが良さそう・・・