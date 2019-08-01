<?php

namespace MyAtomic\Storage;

use MyAtomic\Exception\StorageException;

class StorageKey
{
    /**
     * @var string
     */
    protected $hashKey;

    /**
     * StorageKey constructor.
     * @param string $key
     */
    public function __construct(string $key)
    {
        if (PHP_INT_SIZE !== 8) {
            throw new StorageException(sprint('Unable to calculate hash key on 32bit systems.'));
        }

        $this->hashKey= $this->generateHashKey($key);
    }

    /**
     * String 32 hex characters
     * @return string
     */
    public function getHashKey():string
    {
        return $this->hashKey;
    }

    /**
     * Max value is 2^56-1
     * @return int
     */
    public function getInt():int
    {
        $hash14 = substr($this->hashKey, 18, 14);
        return hexdec($hash14);
    }

    /**
     * If already a UUID string, then use it.
     * Otherwise create one using the sodium_crypto_generichash() or md5()
     *
     * @param $key
     * @return string
     */
    protected function generateHashKey($key):string
    {
        // trim and normalize
        $key = trim(strtolower($key));

        // Remove separators:
        $key = str_replace('{', '', $key);
        $key = str_replace('-', '', $key);
        $key = str_replace('}', '', $key);

        if (preg_match('/[a-f0-9]{32}/', $key)) {
            return $key;
        }

        if (function_exists('sodium_crypto_generichash')) {
            $hash = sodium_bin2hex(sodium_crypto_generichash($key));
            return substr($hash, 0, 32);
        }

        return md5($key);
    }
}
