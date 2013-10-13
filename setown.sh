#!/bin/bash

chown -R www-data:www-data hs/
chown www-data:www-data ./toround.sh
chmod 0755 ./toround.sh
chmod -R 0755 hs/
