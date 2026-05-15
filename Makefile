.PHONY: generate
generate:
	docker exec telebot protoc -I . \
	--plugin=protoc-gen-twirp_php=/usr/local/bin/protoc-gen-twirp_php \
	--twirp_php_out=generated \
	--php_out=generated \
	proto/server.proto ; \
	docker exec telebot protoc -I . \
	--plugin=protoc-gen-twirp_php=/usr/local/bin/protoc-gen-twirp_php \
	--twirp_php_out=generated \
	--php_out=generated \
	proto/auth.proto ; \
	docker exec telebot chown -R www-data:www-data generated

.PHONY: cert
cert:
	rm ./docker/nginx/fullchain.pem
	rm ./docker/nginx/privkey.pem
	scp witty:/etc/letsencrypt/live/xelbot.com/fullchain.pem ./docker/nginx
	scp witty:/etc/letsencrypt/live/xelbot.com/privkey.pem ./docker/nginx

.PHONY: test
test:
	./test.sh

.PHONY: diff
diff:
	docker exec telebot bash -c "php bin/console doctrine:migrations:diff --allow-empty-diff --configuration=config/migrations/autonotes.yaml"
	docker exec telebot bash -c "php bin/console doctrine:migrations:diff --allow-empty-diff --configuration=config/migrations/telebot.yaml"
	docker exec telebot chown -R www-data:www-data .

.PHONY: migrate
migrate:
	docker exec telebot bash -c "php bin/console doctrine:migrations:migrate --no-interaction --configuration=config/migrations/autonotes.yaml"
	docker exec telebot bash -c "php bin/console doctrine:migrations:migrate --no-interaction --configuration=config/migrations/telebot.yaml"
	docker exec telebot chown -R www-data:www-data .

.PHONY: fixtures
fixtures:
	docker exec telebot bash -c "php bin/console doctrine:fixtures:load --em=main --no-interaction"
	docker exec telebot chown -R www-data:www-data .

.PHONY: css
css:
	./node_modules/.bin/grunt build
