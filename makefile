update:
	cd gateway-api;composer update
	cd user-service;composer update
	cd group-service;composer update
	cd redis-service;composer update
	cd msg-service;composer update
start:
	cd gateway-api;php bin/swoft ws:start -d
	cd user-service;php bin/swoft rpc:start -d
	cd group-service;php bin/swoft rpc:start -d
	cd redis-service;php bin/swoft rpc:start -d
	cd msg-service;php bin/swoft rpc:start -d
stop:
	cd gateway-api;php bin/swoft stop
	cd user-service;php bin/swoft stop
	cd group-service;php bin/swoft stop
	cd redis-service;php bin/swoft stop
	cd msg-service;php bin/swoft stop
