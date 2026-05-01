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
