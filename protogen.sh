#!/usr/bin/env bash

protoc -I . \
  --plugin=protoc-gen-twirp_php=bin/protoc-gen-twirp_php \
  --twirp_php_out=generated \
  --php_out=generated \
  proto/server.proto
