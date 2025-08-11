.PHONE codesniffer:
codesniffer:
	cd ./src && composer codesniffer

.PHONE codesnifferFix:
codesnifferFix:
	cd ./src && composer codesnifferFix

.PHONE composerInstall:
composerInstall:
	cd ./src && composer install

.PHONE composerValidate:
composerValidate:
	cd ./src && composer validate --strict

.PHONE dockerComposeUp:
dockerComposeUp:
	docker compose -p wb_assignment up -d

.PHONE phpstan:
phpstan:
	cd ./src && composer phpstan

.PHONE tester:
tester:
	cd ./src && composer tester
