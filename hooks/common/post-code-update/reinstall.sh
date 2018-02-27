#!/bin/sh
#
# Cloud Hook: Reinstall Lightning
#
# Install? That doesn't sound sustainable. How about just an import.

site="$1"
target_env="$2"

# Fresh install of Lightning.
drush @$site.$target_env cim --yes