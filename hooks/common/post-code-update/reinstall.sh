#!/bin/sh
#
# Cloud Hook: Import config

site="$1"
target_env="$2"

drush @$site.$target_env cr
drush @$site.$target_env updatedb --yes
drush @$site.$target_env cim sync --yes
drush @$site.$target_env cr
