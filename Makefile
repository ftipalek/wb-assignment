.PHONE composerValidate:
composerValidate:
	cd ./src && composer validate --strict
	
.PHONE phpstan:
phpstan:
	cd ./src && composer phpstan	