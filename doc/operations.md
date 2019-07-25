## MyAtomic\SimpleCounter Operations

A MyAtomic\SimpleCounter is referenced using a $key and it is monotonically increasing.
It can never decrease in value.  Every increment is durably stored withing MySQL.   
Any two clients that increment the counter are guaranteed to recieve:
1. Counter values that always increase.
2. The value after incrementing a counter successfully will be unique.

All operations have 3 possible states:
- Success - The operation was successful and you can assume it was persisted.
- Unsuccessful - The operation was unsuccessful and was rolled back. 
- Timeout - The operation was either successful or unsuccessful.

All operations are Indepotent, if you get an exception you can retry (with caveats described below).
In particular, if you call the increment method multiple times, it will succeed on 1st time and throw
exceptions afterwards so that the counter only gets incremented once.

#### Sample Code

    use MyAtomic\SimpleCounter;
    use MyAtomic\CounterFactory;
    use MyAtomic\Storage;
    use MyAtomic\ShardConfig;
    use MyAtomic\CounterException;
    
    $storage = new Storage($shardConfig);  // see documentation on ShardConfig
    $counterFactory = new CounterFactory($storage);
    try{
        $counter = $counterFactory->init('foo');  // create counter for entity 'foo'
        $counter->getValue();   // Returns 0
        $counter->increment();   // Returns 1
    } catch(RuntimeException){
        // Do something with exception
    }


#### Exceptions
All exceptions extend MyAtomic\Exception\CounterException.

If the ConditionFailsException exception is thrown, that means another process updated the counter already and your   
request will not complete.  Try fetching the counter value and trying again.  If you continue to see this exception, 
that means that there is write contention on the key, and look into using a MyAtomic\ShardedCounter which can handle  
more concurrent writes than the MyAtomicSimpleCounter.

#### CounterFactory::init($key, $shards=1, $simple): Indepotent
If the counter does not exist, it will be created.   
No affect if the counter already exists.   
The default value of newly created counters is Zero.

Success: Returns True
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.
Throws MyAtomic\Exception\CounterGone if the counter was deleted.

#### getValue($key): Indepotent
Retrieve the value of the counter.  If the counter does not exist, its value is Zero.

Success: Returns the integer value of the counter.
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.
Throws MyAtomic\Exception\CounterGone if the counter was deleted.

### setValue($key, $targetValue): Indepotent
Set is only successful if the $targetValue is greater thant the current value of the counter.

Under the hood:
Retrieve the value of the counter.  If the current value is > $targetValue then throw exception.
Otherwise update the value of the counter to $targetValue.

Success: Returns the integer value of the counter
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.
Throws MyAtomic\Exception\ConditionFailsException if $targetValue < $currentValue.
Throws MyAtomic\Exception\CounterGone if the counter was deleted.

### exists($key): Indepotent
Returns true if the counter exists and false if it never existed or was deleted.

Success: Returns boolean.
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.

### increment($key, $curValue, $incr): Indepotent
In order to increment a value, you need to provide the value of the counter that you think it is.   
Operationally, this means you must issue a get() then an increment().  If another process updates   
the counter inbetween, you will get a ConditionFailsException

Under the hood:
First retrieves the current value of the counter.
If current value matches the parameter $curValue, then the counter is incremented by $incr.

Success: Returns the new value of the counter
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.
Throws MyAtomic\Exception\ConditionFailsException if current value of the counter does not match.
Throws MyAtomic\Exception\CounterGone if the counter was deleted.

#### delete($key)
Marks a counter as deleted.  Once a counter is deleted, it can never be used again.
You can safely call delete() repeately.

Success: Returns true
Throws MyAtomic\Exception\StorageException if MySQL returns an error.
Throws MyAtomic\Exception\TimeoutException if connection closed before response from MySQL.


