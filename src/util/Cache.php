<?php
namespace Dingtalk\util;
use DB;
class Cache {

    public function setJsTicket($ticket)
    {
        $db = DB::connection('mongodb')->collection('mymongo')->where('name','js_ticket')->get();
        if (empty($db))
            DB::connection('mongodb')->collection('mymongo')->update(['ticket'=>$ticket,'expires'=>time()+7000]);
        else
            DB::connection('mongodb')->collection('mymongo')->insert(['name'=>'js_ticket','ticket'=>$ticket,'expires'=>time()+7000]);
//        $memcache = $this->getMemcache();
//        $memcache->set("js_ticket", $ticket, time() + 7000); // js ticket有效期为7200秒，这里设置为7000秒
    }

    public function getJsTicket()
    {
        $res = DB::connection('mongodb')->collection('mymongo')->where('name','js_ticket')->get();
        foreach ($res as $rs ) if ($rs['expires'] > time()) return $rs['ticket'];

        return false;

//        $memcache = $this->getMemcache();
//        return $memcache->get("js_ticket");
    }

    public function setCorpAccessToken($accessToken)
    {
        $db = DB::connection('mongodb')->collection('mymongo')->where('name','corp_access_token')->get();
        if (empty($db))
            DB::connection('mongodb')->collection('mymongo')->update(['accesstoken' => $accessToken,'expires' => time()+7000]);
        else
            DB::connection('mongodb')->collection('mymongo')->insert(['name' => 'corp_access_token','accesstoken' => $accessToken,'expires' => time()+7000]);
//        $memcache = $this->getMemcache('corp_access_token',$accessToken, time() + 7000);
//        $memcache->set("corp_access_token", $accessToken, time() + 7000); // corp access token有效期为7200秒，这里设置为7000秒
    }

    public function getCorpAccessToken($name)
    {
        $res = DB::connection('mongodb')->collection('mymongo')->where('name', $name)->get();
        foreach ($res as $rs ) if ($rs['expires'] > time()) return $rs['accesstoken'];

        return false;

//        $memcache = $this->getMemcache();
//        return $memcache->get("corp_access_token");
    }

    private function getMemcache($name,$accessToken,$expires)
    {
        DB::connection('mongodb')->collection('mymongo')->insert(['name' => $name,'accesstoken' => $accessToken,'expires' => $expires]);
        return new FileCache;
    }

    public function get($key)
    {
        return $this->getMemcache()->get($key);
    }

    public function set($key, $value)
    {
        $this->getMemcache()->set($key, $value);
    }
}

class FileCache
{

    public function __construct()
    {
        $this->path = dirname(__DIR__);
    }

    function set($key, $value, $expire_time = 0) {
        if($key&&$value){
//            $data = json_decode($this->get_file(Config::get('DIR_ROOT') ."filecache.php"),true);
            $data = json_decode($this->get_file($this->path ."/filecache.php"),true);
            $item = array();
            $item["$key"] = $value;

            $item['expire_time'] = $expire_time;
            $item['create_time'] = time();
            $data["$key"] = $item;
            $this->set_file($this->path."/filecache.php",json_encode($data));
        }
    }

    function get($key) {
        if($key){
            $data = json_decode($this->get_file($this->path ."/filecache.php"),true);
            if($data&&array_key_exists($key,$data)){
                $item = $data["$key"];
                if(!$item){
                    return false;
                }
                if($item['expire_time']>0&&$item['expire_time'] < time()){
                    return false;
                }

                return $item["$key"];
            }else{
                return false;
            }

        }
    }

    function get_file($filename) {
        if (!file_exists($filename)) {
            $fp = fopen($filename, "w");
            fwrite($fp, "<?php exit();?>" . '');
            fclose($fp);
            return false;
        }else{
            $content = trim(substr(file_get_contents($filename), 15));
        }
        return $content;
    }

    function set_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}