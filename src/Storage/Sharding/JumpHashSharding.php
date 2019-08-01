<?php

namespace MyAtomic\Storage\Sharding;

class JumpHashSharding implements ShardingStategyInterface
{
    public function getShard($key, $numberOfShards)
    {
        // TODO: Implement getShard() method.
    }

    public function calculate64BitHexKey($key):string
    {
        $hash128BitBinary = sodium_crypto_generichash($key, 'myatomicsharder2019', 16);
        $hash128BitHex = sodium_bin2hex($hash128BitBinary);
        $hash64BitHex = substr($hash128BitHex, 0, 16);
        return $hash64BitHex;
    }

    public function bchexdec($hex)
    {
        if (strlen($hex) == 1) {
            return hexdec($hex);
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, $this->bchexdec($remain)), hexdec($last));
        }
    }

    public function bcdechex($dec)
    {
        $last = bcmod($dec, 16);
        $remain = bcdiv(bcsub($dec, $last), 16);

        if ($remain == 0) {
            return dechex($last);
        } else {
            return $this->bcdechex($remain).dechex($last);
        }
    }

    public function jumpHash($key, int $buckets)
    {
        //https://github.com/c9s/jchash
        var_dump(jchash(10000, 5));
        exit;
    }
}
