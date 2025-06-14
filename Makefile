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
