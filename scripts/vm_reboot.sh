#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="$1"
reboot_xml=$( _vm_reboot "$vm_id" )
reboot_status=$( xmllint --xpath "/action/status/text()" - <<< ${reboot_xml} )
[ "${reboot_status}" == "complete" ] && echo 'ok'
