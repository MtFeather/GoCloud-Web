#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="$1"
boot="$2"
attach="$3"
iso="$4"
if [ "${boot}" == "hd" ]; then
	action=" \
	<device>hd</device>
	<device>cdrom</device>
	"
	result=$( _vm_start_boot "${action}" "${vm_id}" )
        check=$( xmllint --xpath "/action/status/text()" - <<< ${result} )
elif [ "${boot}" == "cdrom" ]; then
	action=" \
	<device>cdrom</device>
	<device>hd</device>
	"
	result=$( _vm_start_boot "${action}" "${vm_id}" )
        check=$( xmllint --xpath "/action/status/text()" - <<< ${result} )
fi 

if [ "${check}" == "failed" ]; then
	echo 'fail'
	exit
fi

_psql "UPDATE student_vms SET last_time = NOW() WHERE vm_id = '${vm_id}';" &> /dev/null

if [ "${attach}" == 1 ]; then
	_attach_iso "${iso}" "${vm_id}" &> /dev/null
fi

while true;
do
	status=$( _vm_status "${vm_id}" )
        [ "${status}" == "powering_up" ] && break
done

echo 'ok'
