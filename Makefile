test:
	@docker exec -it norfolkdevelopers-marketeer_laravel.test_1 php ./vendor/bin/phpstan
	@docker exec -it norfolkdevelopers-marketeer_laravel.test_1 php ./vendor/bin/pest
