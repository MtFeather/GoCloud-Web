#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="${1}"
iso_xml=$( _vm_iso "$vm_id" )
xmllint --xpath "/cdrom/file/@id" - <<< ${iso_xml} | sed 's/ id="\([^"]*\)"/\1/g'
