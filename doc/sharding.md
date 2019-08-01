## Sharding

Idea:

1. Given $key
2. Calculate a 64 bit hash using sodium_crypto_generichash()
3. Take this 64 bit number and run through jump hash algorith