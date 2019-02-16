install:
	cd gateway-api;composer install
	cd user-service;composer install
	cd group-service;composer install
	cd redis-service;composer install
	cd msg-service;composer install
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
