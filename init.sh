#!/usr/bin/env bash

for i in "$@"
do
case $i in
    -p=*|--phrase=*)
    PHRASE="${i#*=}"
esac
done

touch app/var/jwt/phrase.key

echo "$PHRASE" > app/var/jwt/phrase.key

# Generate keys
openssl genrsa -out app/var/jwt/private.pem -aes256 -passout pass:$PHRASE 4096

openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem -passin pass:$PHRASE
