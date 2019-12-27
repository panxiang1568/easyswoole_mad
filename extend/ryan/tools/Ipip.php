<?php

namespace ryan\tools;

use App\exception\DataException;
use EasySwoole\Component\Singleton;
use ipip\db\City;

class Ipip
{
    use Singleton;

    private $ipip;

    private function __construct()
    {
        $ipDB = \EasySwoole\EasySwoole\Config::getInstance()->getConf('ipDB');
        $this->ipip = new City($ipDB);
    }

    public function find($ip, $lang)
    {
        return $this->ipip->find($ip, $lang);
    }

    public function findMap($ip, $lang)
    {
        try{
            return $this->ipip->findMap($ip, $lang);
        }catch(\Exception $e) {
            throw new DataException("IP parse exception");
        }
    }

}