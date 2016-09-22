#!/usr/bin/env bash

echo Please give a pass phrase:

read phrase
touch app/var/jwt/phrase.key

echo "$phrase" > app/var/jwt/phrase.key

# Generate keys
openssl genrsa -out app/var/jwt/private.pem -aes256 4096

echo phrase

openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem

echo phrase

