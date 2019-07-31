#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="${1}"
stop_xml=$( _vm_stop "$vm_id" )
stop_status=$( xmllint --xpath "/action/status/text()" - <<< ${stop_xml} )
[ "${stop_status}" == "complete" ] && echo 'ok'
